<?php

class Search extends SearchCore
{

public static function find($id_lang, $expr, $page_number = 1, $page_size = 1, $order_by = 'position2',
		$order_way = 'desc', $ajax = false, $use_cookie = true, Context $context = null)
	{

		if (!$context)
			$context = Context::getContext();
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);

		// Only use cookie if id_customer is not present
		if ($use_cookie)
			$id_customer = $context->customer->id;
		else
			$id_customer = 0;

		// TODO : smart page management
		if ($page_number < 1) $page_number = 1;
		if ($page_size < 1) $page_size = 1;

		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			return false;

		$intersect_array = array();
		$score_array = array();
		$words = explode(' ', Search::sanitize($expr, $id_lang));

		foreach ($words as $key => $word)
			if (!empty($word) && strlen($word) >= (int)Configuration::get('PS_SEARCH_MINWORDLEN'))
			{
				$word = str_replace('%', '\\%', $word);
				$word = str_replace('_', '\\_', $word);

				if ($word[0] != '-')
					$score_array[] = " ( 100 - ( ( levenshtein(sw.word, '".pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH))."') ) * 100)/ length(sw.word) ) >= 70 ";
				
				
			}
			else
				unset($words[$key]);

		if (!count($words))
			return ($ajax ? array() : array('total' => 0, 'result' => array()));

		$product_pool = '';
		$product_pool = ((strpos($product_pool, ',') === false) ? (' = '.(int)$product_pool.' ') : (' IN ('.rtrim($product_pool, ',').') '));

		if ($ajax)
		{

			$conn_string = "host=10.0.1.240 port=5432 dbname=farmalisto_colombia user=search password=search123";
			$dbconn4 = pg_pconnect($conn_string);

			$query_psql = " SELECT si.id_product, SUM(si.weight) AS peso FROM
				ps_search_index si
				INNER JOIN ps_search_word sw ON ( sw.id_word = si.id_word )
				WHERE 
					( ".implode(' OR ', $score_array)."
					)
				GROUP BY si.id_product
				ORDER BY peso DESC";
			$alenum = 0;
			if ( $resultado = pg_exec($query_psql)) {
				
					$alenum = rand(10,50);
					$sql_create = 'CREATE TEMPORARY TABLE tmp_search_'.$alenum.' (
						id_asc  int NOT NULL AUTO_INCREMENT ,
						id_product  int NULL ,
						peso  int NULL ,
						PRIMARY KEY (`id_asc`),
						INDEX indx_pr_search_'.$alenum.' (id_product) USING BTREE
						)';
					
					$result_crear = $db->execute($sql_create);

					$ins_prods_buscar = " INSERT INTO tmp_search_".$alenum." (id_product, peso) VALUES  ";

					while ( $fetch = pg_fetch_row($resultado)) {
						$eligible_products[$fetch['0']] = $fetch['0'];
						$ins_prods_buscar .= " (".$fetch['0'].", ".$fetch['1']."),";
			        }	

			        $ins_prods_buscar = rtrim($ins_prods_buscar, ",");
			        
			        if ( !$ret_insert = $db->execute($ins_prods_buscar) ) {
			        	//echo "<br> nooooooooooooo";
			        	return ($ajax ? array() : array('total' => 0, 'result' => array()));
			        } else {
			        	//echo "<br> siiiiiiiiiiii";
			        }
		        
		    }
		    pg_close($dbconn4);

			$sql = "SELECT DISTINCT p.id_product, pl.name pname, cl.name cname, cl.link_rewrite crewrite, pl.link_rewrite prewrite, position2.peso
				FROM ps_product p
				INNER JOIN ps_product_lang pl ON (p.active = 1 AND p.id_product = pl.id_product AND pl.id_lang = 1 AND pl.id_shop = 1  )
				INNER JOIN ps_product_shop product_shop ON ( product_shop.active = 1 AND product_shop.visibility IN ('both', 'search')
									AND product_shop.indexed = 1 AND product_shop.id_product = p.id_product AND product_shop.id_shop = 1 )
				INNER JOIN ps_category_lang cl ON ( product_shop.id_category_default = cl.id_category AND cl.id_lang = 1 AND cl.id_shop = 1  )
				INNER JOIN tmp_search_".$alenum." position2 ON ( position2.id_product = p.id_product)
				ORDER BY position2.peso DESC
				LIMIT 10";

				if ($alenum != 0 && $result = $db->executeS($sql)) {
					//echo "<br> 222222 siiiiiiii";
					return $result;
				} else {
					//echo "<br> 222222 Noooooooo";
					return ($ajax ? array() : array('total' => 0, 'result' => array()));
				}			
		}

		if (strpos($order_by, '.') > 0)
		{
			$order_by = explode('.', $order_by);
			$order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
		}
		$alias = '';
		if ($order_by == 'price')
			$alias = 'product_shop.';
		else if ($order_by == 'date_upd')
			$alias = 'p.';

		$conn_string = "host=10.0.1.240 port=5432 dbname=farmalisto_colombia user=search password=search123";
		$dbconn4 = pg_pconnect($conn_string);

		$sql = "SELECT si.id_product, SUM(si.weight) AS peso FROM
				ps_search_index si 
				INNER JOIN ps_search_word sw ON ( sw.id_word = si.id_word )
				WHERE 
					( ".implode(' OR ', $score_array)."
					)
				GROUP BY si.id_product
				ORDER BY peso DESC";
			$eligible_products = array();
			$ret_insert = 0;
			$orden_bus = '';
			if ( $resultado = pg_exec($sql)) {
				$alenum = rand(10,50);
				$sql_create = 'CREATE TEMPORARY TABLE tmp_search_'.$alenum.' (
					id_asc  int NOT NULL AUTO_INCREMENT ,
					id_product  int NULL ,
					id_peso  int NULL ,
					PRIMARY KEY (`id_asc`),
					INDEX indx_pr_search_'.$alenum.' (id_product) USING BTREE
					)';
				
				$result_crear = $db->execute($sql_create);

				$ins_prods_buscar = " INSERT INTO tmp_search_".$alenum." (id_product, id_peso) VALUES  ";

				while ( $fetch = pg_fetch_row($resultado)) {
					$eligible_products[$fetch['0']] = $fetch['0'];
					$ins_prods_buscar .= " (".$fetch['0'].", ".$fetch['1']."),";
				}	

				$ins_prods_buscar = rtrim($ins_prods_buscar, ",");
				
				if ( $ret_insert = $db->execute($ins_prods_buscar) ) {
					$orden_bus = " ORDER BY bt.id_asc ASC ";
				} else {
					$ret_insert = 0;
					$orden_bus = ($order_by ? 'ORDER BY  '.$alias.$order_by : '').($order_way ? ' '.$order_way : '');
				}
			}	

			pg_close($dbconn4);

		$product_pool = '';
		foreach ($eligible_products as $id_product) {
			if ($id_product) {
				$product_pool .= (int)$id_product.',';
			}
		}

		$product_pool = ((strpos($product_pool, ',') === false) ? (' = '.(int)$product_pool.' ') : (' IN ('.rtrim($product_pool, ',').') '));


		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, 
				pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
			 MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` manufacturer_name
				FROM '._DB_PREFIX_.'product p
				'.Shop::addSqlAssociation('product', 'p').'
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)				
				'.Product::sqlStock('p', '', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')'.
				($ret_insert ? ' INNER JOIN tmp_search_'.$alenum.' bt ON ( bt.id_product = p.id_product )' : '').'
				WHERE p.active = 1 AND product_shop.active = 1 AND p.`id_product` '.$product_pool.'
				GROUP BY product_shop.id_product
				'.$orden_bus.'			
				LIMIT '.(int)(($page_number - 1) * $page_size).','.(int)$page_size;
		
		$result = $db->executeS($sql);

		$sql = 'SELECT COUNT(*)
				FROM '._DB_PREFIX_.'product p
				'.Shop::addSqlAssociation('product', 'p').'
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE p.`id_product` '.$product_pool;
		$total = $db->getValue($sql);

		if (!$result)
			$result_properties = false;
		else
			$result_properties = Product::getProductsProperties((int)$id_lang, $result);
                

		return array('total' => $total,'result' => $result_properties);
	}
    

        

	public static function findWsMobile($id_lang, $expr, $page_number = 1, $page_size = 1, $order_by = 'position',
	$order_way = 'desc', $ajax = false, $use_cookie = true, Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);

		// Only use cookie if id_customer is not present
		if ($use_cookie)
			$id_customer = $context->customer->id;
		else
			$id_customer = 0;

		// TODO : smart page management
		if ($page_number < 1) $page_number = 1;
		if ($page_size < 1) $page_size = 1;

//		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)){
//                    return 'xd';
//			return false;
//                }

		$intersect_array = array();
		$score_array = array();
		$words = explode(' ', Search::sanitize($expr, $id_lang));

		foreach ($words as $key => $word)
			if (!empty($word) && strlen($word) >= (int)Configuration::get('PS_SEARCH_MINWORDLEN'))
			{
				if ($word[0] != '-')
					$score_array[] = 'sw.word LIKE \''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'';
			}
			else
				 unset($words[$key]);

		if (!count($words))
			return ($ajax ? array() : array('total' => 0, 'result' => array()));

		$score = '';
		if (count($score_array))
			$score = ',(
				SELECT SUM(weight)
				FROM '._DB_PREFIX_.'search_word sw
				LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
				WHERE sw.id_lang = '.(int)$id_lang.'
					AND sw.id_shop = '.$context->shop->id.'
					AND si.id_product = p.id_product
					AND ('.implode(' OR ', $score_array).')
			) position';
                
                
	        

		if (strpos($order_by, '.') > 0)
		{
			$order_by = explode('.', $order_by);
			$order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
		}
		$alias = '';
		if ($order_by == 'price')
			$alias = 'product_shop.';

		/*if( $order_by == 'position') { // mejorar busqueda rapida no toma en cuenta el peso de la palabra a buscar en el producto
			$score = '';
			$order_by = "pl.`name`";
			$order_way = "ASC";
		}*/
                /*
                 * select prod.id_product , prod.price, prod.reference, prodl.`name`,prodl.description , prodl.description_short , CONCAT(GROUP_CONCAT(cat_prodl.meta_title SEPARATOR \"|\")) AS categorias,
CONCAT(GROUP_CONCAT(cat_prodl.id_category SEPARATOR \"|\")) AS ids_categorias 
                 */

		$sql = 'SELECT p.id_product,p.price, p.reference,pl.`name`, pl.description, pl.description_short,
                         CONCAT(GROUP_CONCAT(cat_prodl.meta_title SEPARATOR "|")) AS categorias,
                            CONCAT(GROUP_CONCAT(cat_prodl.id_category SEPARATOR "|")) AS ids_categorias '.$score.' 
                    

		
				FROM '._DB_PREFIX_.'product p
				'.Shop::addSqlAssociation('product', 'p').'
                                INNER JOIN ps_category_product cat_prod ON (cat_prod.id_product=p.id_product)
                                INNER JOIN ps_category_lang cat_prodl ON (cat_prod.id_category=cat_prodl.id_category) 
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa	ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				WHERE p.`id_product` IN 
                                
(select t1.id_product from
(
       SELECT si.id_product
					FROM ps_search_word sw
					LEFT JOIN ps_search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = 1
						AND sw.id_shop = 1';

        $cant_bus = 0;

        for ($i=0; $i < count($words); $i++) {
        	 
            if ( strlen($words[$i])>=3 ) {
             	$cant_bus++;

	            if ($cant_bus == 1) {
	             	$sql.=" AND ( sw.word LIKE '%" . str_replace('%', '\\%', $words[$i]) . "%'";
	            }  elseif ($cant_bus > 1) {
	            	 $sql.=" OR sw.word LIKE '%" . str_replace('%', '\\%', $words[$i]) . "%' ";
	            }          
            
            }
        }

         if ($cant_bus != 0) {
             	$sql.=" )";
             }  

$sql .='
    
GROUP BY si.id_product
			
) as t1

INNER JOIN
(

SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				INNER JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_category` = cg.`id_category`
				INNER JOIN `'._DB_PREFIX_.'category` c ON cp.`id_category` = c.`id_category`
				INNER JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p', false).'
				WHERE c.`active` = 1
					AND product_shop.`active` = 1
					AND product_shop.`visibility` IN ("both", "search")
					AND product_shop.indexed = 1
					AND cg.`id_group` '.(!$id_customer ?  '= 1' : 'IN (
						SELECT id_group FROM '._DB_PREFIX_.'customer_group
						WHERE id_customer = '.(int)$id_customer.'
					 ) ').'
                  GROUP BY cp.`id_product` 

) as t2
ON(t1.id_product=t2.id_product))

				GROUP BY product_shop.id_product
				'.($order_by ? 'ORDER BY  '.$alias.$order_by : '').($order_way ? ' '.$order_way : '').'
			LIMIT '.(int)(($page_number - 1) * $page_size).','.(int)$page_size;

		$result = $db->executeS($sql);

		$sql = 'SELECT COUNT(*)
				FROM '._DB_PREFIX_.'product p
				'.Shop::addSqlAssociation('product', 'p').'
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE p.`id_product` IN 
                                
(select t1.id_product from
(
       SELECT si.id_product
					FROM ps_search_word sw
					LEFT JOIN ps_search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = 1
						AND sw.id_shop = 1';

        $cant_bus = 0;

        for ($i=0; $i < count($words); $i++) {
        	 
            if ( strlen($words[$i])>=3 ) {
             	$cant_bus++;

	            if ($cant_bus == 1) {
	             	$sql.=" AND ( sw.word LIKE '%" . str_replace('%', '\\%', $words[$i]) . "%'";
	            }  elseif ($cant_bus > 1) {
	            	 $sql.=" OR sw.word LIKE '%" . str_replace('%', '\\%', $words[$i]) . "%' ";
	            }          
            
            }
        }

         if ($cant_bus != 0) {
             	$sql.=" )";
             }  

$sql .='
 GROUP BY si.id_product
			
) as t1

INNER JOIN
(

SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				INNER JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_category` = cg.`id_category`
				INNER JOIN `'._DB_PREFIX_.'category` c ON cp.`id_category` = c.`id_category`
				INNER JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
				'.Shop::addSqlAssociation('product', 'p', false).'
				WHERE c.`active` = 1
					AND product_shop.`active` = 1
					AND product_shop.`visibility` IN ("both", "search")
					AND product_shop.indexed = 1
					AND cg.`id_group` '.(!$id_customer ?  '= 1' : 'IN (
						SELECT id_group FROM '._DB_PREFIX_.'customer_group
						WHERE id_customer = '.(int)$id_customer.'
					 ) ').'
             GROUP BY cp.`id_product`                                

) as t2
ON(t1.id_product=t2.id_product))
                                

';

		$total = $db->getValue($sql);

		if (!$result)
			$result_properties = false;
		else
	
		return array('total' => $total,'result' => $result);
	}      
        
}