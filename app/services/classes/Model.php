<?php
include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');

class Model extends PaymentModule {
	/**
	 * Obtiene todas las imagenes del producto
	 * @return array
	 */
	private $errors = array();

	public function __construct() {
				$this->context = Context::getContext(); // actualizar contexto
			}

			public function getImageProduct($id_product = 0) 
			{
				if ( (int) $id_product ) {
					$array_img = array();
					$query = "SELECT i.id_image
					FROM ps_image i
					LEFT JOIN ps_image_lang il ON (i.id_image = il.id_image)
					WHERE i.id_product = ".$id_product." AND il.id_lang = 1
					ORDER BY i.position  ASC";

					if ( $results = Db::getInstance()->ExecuteS($query) ) {
						foreach ($results as $value) {
							$array_img[] = _PS_BASE_URL_
							. __PS_BASE_URI__
							. 'img/p/'
							. Image::getImgFolderStatic($value['id_image'])
							. $value['id_image']
							. '-home_default.jpg';
						}

						if ( count($array_img) ) return $array_img;
					}
				}

				$array_img[] = _PS_BASE_URL_
				. __PS_BASE_URI__
				. 'img/p/es-default-home_default.jpg';
				return $array_img;
			}

	/**
	 * Busca producto(s) por los datos dados
	 * @return array
	 */
	public function productSearch($id_lang, $expr, $page_number, $page_size, $order_by,	$order_way)
	{
		$page_number = empty($page_number) ? 1 : $page_number;
		$page_size   = empty($page_size) ? 1 : $page_size;
		$order_by    = empty($order_by) ? 'position' : $order_by;
		$order_way   = empty($order_way) ? 'desc' : $order_way;
		$context = Context::getContext();
		$results = Search::findApp($id_lang, $expr, $page_number, $page_size, $order_by, $order_way, FALSE, FALSE);
		$products = array();
		if ((int) count($results['result']) > 0) {
			$total_rows = (int) $results['total'];
			// $total_rows = (int) count($results['result']);
			$total_pages = ceil($total_rows / $page_size);
			$start = 0;

			if ($page_number <= $total_pages ) {
				if ( $page_number === 1) {
					$page_number = 1;
					$start = 0;
				} else {
					$start = ($page_number - 1) * $page_size;
				}

				$products['title'] = strtolower( $expr );
				$products['total_pages'] = $total_pages;
				$products['total_rows'] = $total_rows;
				$products['page'] = $page_number;
				$products['rows'] = $page_size;

				$array_prod = NULL;
				if ($results['result'] != NULL) {

					foreach ($results['result'] as $value) {
						
						$img_url = '';
						$img_url_large = '';

						if ( $value['id_image'] != NULL ) {

							$img_url = _PS_BASE_URL_
							. __PS_BASE_URI__
							. 'img/p/'
							. Image::getImgFolderStatic($value['id_image'])
							. $value['id_image']
							. '-home_default.jpg';

							$img_url_large = _PS_BASE_URL_
							. __PS_BASE_URI__
							. 'img/p/'
							. Image::getImgFolderStatic($value['id_image'])
							. $value['id_image']
							. '-large_default.jpg';
							

						} else {

							$img_url = _PS_BASE_URL_
							. __PS_BASE_URI__
							. 'img/p/'
							. 'es-default-home_default.jpg';

							$img_url_large = _PS_BASE_URL_
							. __PS_BASE_URI__
							. 'img/p/'
							. 'es-default-large_default.jpg';

						}

						$textocorto = '';
						if( strlen($value['name']) > 62 ) {
							$textocorto = mb_strcut( $value['name'], 0, 62 ).'...';
						} else {
							$textocorto = $value['name'];
						}
						$array_prod[] = array(
						                      'id' => (int) $value['id_product'],
						                      'reference' => (string) $value['reference'],
						                      'name' => strtolower(strip_tags($value['name'])),
						                      'shortname' => strtolower($textocorto),
							//'ref' => $value['reference'],
							//'desc' => strip_tags($value['description']),
							//'desc_short' => strip_tags($value['description_short']),
						                      'price' => Tools::ps_round($value['price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_),
						                      'img' => $img_url,
						                      'img_large' => $img_url_large,
						                      'cat' => $value['cat'],
						                      'id_cat' => $value['id_cat'],
						                      'id_lab'  => $value['id_lab'],
						                      'lab'  => $value['lab'],
						                      'porcen'  => $value['id_porcent'],
						                      'peso'  => $value['id_peso']
						                      );
					}
					if ($array_prod != NULL) {
						$products['products'] = $array_prod;
						return $products;
					}
				}
			}
		}
		return array();
	}
	/**
	 * @param $level_depth_min int nivel inferior de categorías
	 * @param $level_depth_max int nivel superior de categorías
	 * @param $con_activo bool si está en false traerá todas las categorías sin el filtro de active
	 * @return array
	 */
	public function get_category($level_depth_min = 2,$level_depth_max = 3, $con_activo = FALSE){
		if(!(is_integer($level_depth_min) && is_integer($level_depth_max) && $level_depth_min > 0 && ($level_depth_min < $level_depth_max))){
			$level_depth_min = 2;
			$level_depth_max = 3;
		}
		
		$sql_activo = ($con_activo)?' AND cat.active = 1':'';

		$query ="SELECT cat.id_category as i, cat.id_parent, cat.level_depth, LOWER(catl.`name`) as n 
		FROM "._DB_PREFIX_."category cat 
		INNER JOIN "._DB_PREFIX_."category_lang catl ON (cat.id_category = catl.id_category)
		WHERE cat.level_depth BETWEEN  ".$level_depth_min." AND   ".$level_depth_max.$sql_activo.";";


		if ( $results = Db::getInstance()->ExecuteS($query) ) {
			$aux =  array();
		// ordenamiento de resultados
			foreach ($results as $key => $row) {
				$aux[$key] = (int) $row['level_depth'];
			}
			array_multisort($aux, SORT_ASC, $results);
			unset($aux);
        // Asigna todas las categorías de un level_depth a un sub-array
			$level_depth_flag = 0; 
			$level = 0;
			$level_min = 0;
			$level_max = 0;
			$aux_3 = array();
			foreach ($results as $key => $value) {
				if($level_depth_flag == 0){
					$level_depth_flag = (int) $value['level_depth'];
					$level_min  = $level_depth_flag;
				}
				if ( (int) $value['level_depth'] == $level_depth_flag) {
					$aux_3[$level][] = $value;
				} else{
					$level ++;
					$aux_3[$level][] = $value;
					$level_depth_flag = (int) $value['level_depth'];
					$level_max = $level_depth_flag;
				}
			}

			unset($results);

	    // Asigna valida las dependencias de categorías y las asigna a su respectiva parent.
			for ($i = count($aux_3)-1; $i >= 0; $i--) { 
				foreach ($aux_3[$i] as $key => $value) {   
					foreach ($aux_3 as $key_2 => $value_2) { 
						foreach ($value_2 as $key_3 => $value_3) {
							if(isset($value['id_parent']) && $value['id_parent'] == $value_3['i']){
								unset($value['id_parent']);
								unset($value['level_depth']);
								$aux_3[$key_2][$key_3]['s'][] = $value;
								unset($aux_3[$i][$key]);

							}
						}
					}
				}
			}

			$aux_4 = $aux_3[0];
			unset($aux_3);
		// elimina los sub-arrays vicios generados en el proceso 
			foreach ($aux_4 as $key => $value) {
				unset($aux_4[$key]['id_parent']);
				unset($aux_4[$key]['level_depth']);			

			}
		// retorna árbol de categorías 
			return $aux_4;
		}
	}

	public function getProdCategories( $ids_categories, $page_number = 1, $page_size = 10, $order_way = null, $order_by = null ) {
		$array_productos= array();  

		$buscar = $ids_categories;
		$context = Context::getContext();

		$busqueda=NULL;
		if ($page_size > 300) {
			$page_size = 300;
		}

		foreach ($buscar as $value) {
			$var=str_replace(" ", "", $this->remomeCharSql($value)); 
			if(is_numeric($var))
				$busqueda[]=$var;        
		}
		if($busqueda!=NULL)       
		{  

			$total_filas = 0;
			$query = " SELECT COUNT(prod.id_product) total, cat_prodl.name AS title
			FROM "._DB_PREFIX_."product prod
			INNER JOIN "._DB_PREFIX_."product_lang prodl on(prod.id_product=prodl.id_product)
			INNER JOIN "._DB_PREFIX_."product_shop prods ON (prod.id_product=prods.id_product AND prod.active = prods.active)
			LEFT JOIN "._DB_PREFIX_."category_lang cat_prodl ON (cat_prodl.id_category=prod.id_category_default)
			LEFT JOIN "._DB_PREFIX_."tax_rule taxr ON(prods.id_tax_rules_group = taxr.id_tax_rules_group AND taxr.id_tax != 0)
			LEFT JOIN "._DB_PREFIX_."tax tax ON(taxr.id_tax = tax.id_tax AND tax.active = 1 AND tax.deleted = 0) 
			LEFT JOIN "._DB_PREFIX_."manufacturer m ON (m.`id_manufacturer` = prod.`id_manufacturer`)
			LEFT JOIN "._DB_PREFIX_."image i ON (i.`id_product` = prod.`id_product` AND  i.cover = 1 )
			WHERE cat_prodl.id_category in ('".implode("','",$busqueda)."')  
			AND prod.active=1 AND prods.active=1
			AND prod.is_virtual=0 AND prods.visibility='both' AND (taxr.id_tax != 0 OR ISNULL(taxr.id_tax)) limit 1";

			if ($results = Db::getInstance()->ExecuteS($query)) {
				foreach ($results as $value) {
					$total_filas = (int) $value['total'];
					$array_productos['title']= strtolower( $value['title'] );
				}
			}

			$total_paginas = ceil($total_filas / $page_size);
			$inicio = 0;
			if ( $page_number > $total_paginas | $page_number == 1) {
				$page_number = 1;
				$inicio = 0;
			} else {
				$inicio = ($page_number - 1) * $page_size;
			}

			
			$array_productos['total_pages']=$total_paginas;
			$array_productos['total_rows']=$total_filas;
			$array_productos['page']=$page_number;
			$array_productos['rows']=$page_size;


			$query = " SELECT prod.id_product, prod.reference, prodl.`name`, cat_prodl.id_category as id_cat, cat_prodl.`name` as cat,
			m.id_manufacturer AS id_lab, m.`name` AS lab,
			CASE prod.id_tax_rules_group
			WHEN  0 THEN ROUND(prod.price,2)
			ELSE IF(taxr.id_tax IS NOT NULL, ROUND( prod.price + (prod.price * tax.rate/100),2), ROUND(prod.price,2) )
			END
			AS `price`,			
			prod.price as bprice, 
			MAX(i.`id_image`) AS id_image
			FROM "._DB_PREFIX_."product prod
			INNER JOIN "._DB_PREFIX_."product_lang prodl on(prod.id_product=prodl.id_product)
			INNER JOIN "._DB_PREFIX_."product_shop prods ON (prod.id_product=prods.id_product AND prod.active = prods.active)
			LEFT JOIN "._DB_PREFIX_."category_lang cat_prodl ON (cat_prodl.id_category=prod.id_category_default)
			LEFT JOIN "._DB_PREFIX_."tax_rule taxr ON(prods.id_tax_rules_group = taxr.id_tax_rules_group AND taxr.id_tax != 0)
			LEFT JOIN "._DB_PREFIX_."tax tax ON(taxr.id_tax = tax.id_tax AND tax.active = 1 AND tax.deleted = 0) 
			LEFT JOIN "._DB_PREFIX_."manufacturer m ON (m.`id_manufacturer` = prod.`id_manufacturer`)
			LEFT JOIN "._DB_PREFIX_."image i ON (i.`id_product` = prod.`id_product` AND  i.cover = 1 )
			WHERE cat_prodl.id_category in ('".implode("','",$busqueda)."')  
			AND prod.active=1 AND prods.active=1
			AND prod.is_virtual=0 AND prods.visibility='both' AND (taxr.id_tax != 0 OR ISNULL(taxr.id_tax))
			GROUP BY prod.id_product";

		  //Validacioner ordenar

			if($order_way!=NULL && $order_by!=NULL) {

				if(strtoupper($order_way) ==='ASC') {
					$order_way="ASC";
				}

				if(strtoupper($order_way)==='DESC') {
					$order_way="DESC";
				}

				$query.=" ORDER BY `".$order_by."` ".$order_way;

			} else {   

				$query.=" ORDER BY prodl.id_product ASC";
			}

			$query.=" LIMIT ".$inicio.", ".$page_size.";";
		    // return $query;
			$array_prod = array();

			if ( $results = Db::getInstance()->ExecuteS($query) ) {

				foreach ( $results as $value ) {

					$img_url = '';
					$img_url_large = '';

					if ( $value['id_image'] != NULL ) {

						$img_url = _PS_BASE_URL_
						. __PS_BASE_URI__
						. 'img/p/'
						. Image::getImgFolderStatic($value['id_image'])
						. $value['id_image']
						. '-home_default.jpg';

						$img_url_large = _PS_BASE_URL_
						. __PS_BASE_URI__
						. 'img/p/'
						. Image::getImgFolderStatic($value['id_image'])
						. $value['id_image']
						. '-large_default.jpg';
						

					} else {

						$img_url = _PS_BASE_URL_
						. __PS_BASE_URI__
						. 'img/p/'
						. 'es-default-home_default.jpg';

						$img_url_large = _PS_BASE_URL_
						. __PS_BASE_URI__
						. 'img/p/'
						. 'es-default-large_default.jpg';

					}

					$textocorto = '';

					if( strlen($value['name']) > 62 ) {

						$textocorto = mb_strcut( $value['name'], 0, 62 ).'...';

					} else {

						$textocorto = $value['name'];

					}

					$array_prod[] = array(
					                      'id' => (int) $value['id_product'],
					                      'reference' => (string) $value['reference'],
					                      'name' => strtolower(strip_tags($value['name']) ),
					                      'shortname' =>  strtolower($textocorto),
					                      'price' => Tools::ps_round($value['price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_),
					                      'bprice' => $value['bprice'],
					                      'img' => $img_url,
					                      'img_large' => $img_url_large,
					                      'cat' => strtolower( $value['cat'] ),
					                      'id_cat' => $value['id_cat'],
					                      'id_lab'  => $value['id_lab'],
					                      'lab'  =>  $value['lab']
					                      );

				}
				if ( $array_prod!=NULL ) {
					$array_productos['products'] = $array_prod;
					return $array_productos;
				}

			}   
		}
		return array();
	}


private function remomeCharSql($string, $length = NULL){
	$string = trim($string);

	$array=array("\"","#","$","%","&","'","(",")","*","+",",","-","/",":",";","<","=",">","?","@","[","]","^","`","{","|","}","~");
	$string = utf8_decode($string);
	$string = htmlentities($string, ENT_NOQUOTES| ENT_IGNORE, "UTF-8");
	$string = str_replace($array, "", $string);        
	$string = preg_replace( "/([ ]+)/", " ", $string );
	
	$length = intval($length);
	if ($length > 0){
		$string = substr($string, 0, $length);
	}
	return $string;
}

public function manufacturers(){
	$manufacturers_tmp = Manufacturer::getManufacturers();
	$manufacturers = array();
	foreach ($manufacturers_tmp as $key => $value) {
		$manufacturers[$key]['id'] = $value['id_manufacturer'];
		$manufacturers[$key]['name'] = $value['name'];
	//$manufacturers[$key]['desc'] = $value['description'];
	//$manufacturers[$key]['short_desc'] = $value['short_description'];
	//$manufacturers[$key]['img'] = _PS_BASE_URL_.'/img/m/'.$value['id_manufacturer'].'.jpg';	
	}
	unset($manufacturers_tmp);
	return $manufacturers;
}

public function getProduct($id) {

	$query = "SELECT p.id_product AS id, pl.`name` AS name, pl.description_short AS 'desc', GROUP_CONCAT( DISTINCT im.id_image ORDER BY im.cover DESC SEPARATOR ',') AS imgs,
	ROUND( ps.price + IF ( t.rate IS NULL , 0 , ps.price * ( ( t.rate/100 ) ) ), 2) AS price,
	GROUP_CONCAT( DISTINCT CONCAT(pvc.id_supplier,',',ROUND(pvc.price,0),',',IF (pvc.date IS NULL OR pvc.date = '0000-00-00','0',pvc.date) ) ORDER BY pvc.price ASC SEPARATOR ';' ) AS prov
	FROM "._DB_PREFIX_."product p
	INNER JOIN "._DB_PREFIX_."product_shop ps ON ( p.id_product = ps.id_product )
	INNER JOIN "._DB_PREFIX_."product_lang pl ON ( p.id_product = pl.id_product )
	LEFT JOIN "._DB_PREFIX_."tax_rule tr ON ( tr.id_tax_rules_group = ps.id_tax_rules_group AND tr.id_tax_rule NOT IN (3,4) )
	LEFT JOIN "._DB_PREFIX_."tax t ON ( t.id_tax = tr.id_tax AND t.active = 1 AND t.deleted = 0 )
	LEFT JOIN "._DB_PREFIX_."image im ON ( p.id_product = im.id_product )
	LEFT JOIN "._DB_PREFIX_."proveedores_costo pvc ON ( p.id_product = pvc.id_product )
	WHERE p.id_product = ".$id." AND  p.active=1 AND ps.active=1
	AND p.is_virtual=0 AND ps.visibility='both' AND (tr.id_tax != 0 OR ISNULL(tr.id_tax))";

	$array_img = array();

	if ( $results = Db::getInstance()->ExecuteS($query) ) {

		if ( $results[0]['imgs'] != NULL ) {
			foreach (explode(',', $results[0]['imgs']) as $value) {
				$array_img[] = _PS_BASE_URL_
				. __PS_BASE_URI__
				. 'img/p/'
				. Image::getImgFolderStatic($value)
				. $value
				. '-large_default.jpg';
			}
		} else {
			$array_img[] = _PS_BASE_URL_
			. __PS_BASE_URI__
			. 'img/p/'
			. 'es-default-large_default.jpg';
		}


		$prov = 0;
		$array_proveedores = array();
		$mostRecent = 0;
		$mostRecent2 = 0;

		if ( $results[0]['prov'] != NULL ) {
			foreach (explode(';', $results[0]['prov']) as $proveedores_l) {

				$detalle_precio = explode(',', $proveedores_l);

				if ( $detalle_precio[1] > $results[0]['price'] ) {

					$array_proveedores[$prov]['id'] = $detalle_precio[0];
					$array_proveedores[$prov]['price'] = $detalle_precio[1];

					if ( $detalle_precio[2] != '' AND $detalle_precio[2] != NULL AND $detalle_precio[2] != 0 ) {

						$curDate = strtotime($detalle_precio[2]);
						/* menor fecha 
						//$array_proveedores[$prov]['date'] = $detalle_precio[2];
						//$array_proveedores[$prov]['date2'] = $curDate;
						if ($curDate < $mostRecent && $mostRecent != 0 ) {
						    $mostRecent = $curDate;
						    $mostRecent2 = $detalle_precio[2];
						} elseif( $mostRecent == 0 ){
							$mostRecent = $curDate;
							$mostRecent2 = $detalle_precio[2];
						}
						// fin menor fecha */

						//------------------ mayor fecha -----------------//

						if ($curDate > $mostRecent ) {
						    $mostRecent = $curDate;
						    $mostRecent2 = $detalle_precio[2];
						} 

						//--------------- fin mayor fecha ----------------//
					}

					$prov++;
				}
			}
		}

		$context = Context::getContext();

		$results[0]['prov'] = $array_proveedores;
		$results[0]['prov_date'] = $mostRecent2;
		$results[0]['imgs'] = $array_img;
		$results[0]['name'] = strtolower($results[0]['name']);
		$results[0]['price'] = Tools::ps_round($results[0]['price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);  //number_format($results[0]['price'], 2, '.', '');

		return $results;
	}

	return false;

}
public function setAccount($arg){

	$customer = new Customer();
	$email = strtolower(trim($arg["email"]));
	$customer = $customer->getByEmail($email);
	if(!Validate::isLoadedObject($customer))
		$customer = new Customer();

	if(!empty($arg["firstname"]))
		$customer->firstname = $arg["firstname"];

	if(!empty($arg["lastname"]))
		$customer->lastname =  $arg["lastname"];
	
	if(!empty($email))
		$customer->email = $email;

	if(isset($arg["passwd"]) && !empty($arg["passwd"])){
		$customer->passwd =  md5(_COOKIE_KEY_.trim($arg["passwd"]));

	}

	$customer->is_guest = 0;
	if(!empty($arg["gender"]))
		$customer->id_gender = (strtoupper($arg["gender"])  == 'M' ? 1 : (strtoupper($arg["gender"])  == 'F' ? 2 : 0 )); 

	if(!empty($arg["news"]))
		$customer->newsletter = !empty($arg["news"]) ? (boolean) $arg["news"] : 0; 
	// $customer->newsletter = $arg["signon"];
	if(!empty($arg["dni"]))
		$customer->identification = $arg["dni"];
	if(!empty($arg["birthday"]))
		$customer->birthday =  $arg["birthday"];
	if(!empty($arg["website"]))
		$customer->website = $arg["website"];
	if(!empty($arg["company"]))
		$customer->company =  $arg["company"];
	if(!empty($arg["id_type"]))
		$customer->id_type = (int) $arg["id_type"];
	$customer->img_profile = $arg["img_profile"];

	$flag = empty($customer->id) ? $customer->add() : $customer->update();

	if($flag){
		$gender = $customer->id_gender  == 1 ? 'M' : ($customer->id_gender  == 2 ? 'F' : '');
		$context = Context::getContext();
		$context->cookie->id_compare = isset($context->cookie->id_compare) ? $context->cookie->id_compare: CompareProduct::getIdCompareByIdCustomer($customer->id);
		$context->cookie->id_customer = (int)($customer->id);
		$context->cookie->customer_lastname = $customer->lastname;
		$context->cookie->customer_firstname = $customer->firstname;
		$context->cookie->logged = 1;
		$customer->logged = 1;
		$context->cookie->is_guest = $customer->isGuest();
		$context->cookie->passwd = $customer->passwd;
		$context->cookie->email = $customer->email;
		$context->customer = $customer;

		return array('id' => (int)$customer->id,
		             'lastname' => $customer->lastname, 
		             'firstname' => $customer->firstname, 
		             'email' => $customer->email,
		             'newsletter' => (bool)$customer->newsletter,
		             'dni' => $customer->identification,
		             'gender' => $gender,
		             'id_type' => $customer->id_type,
		             'birthday' => $customer->birthday,
		             'website' => $customer->website,
		             'company' => $customer->company,
		             'img_profile' => $customer->img_profile,
		             'success' => TRUE);

	}
	return  array('success' => FALSE);	
}

public function get_address($id_customer = NULL, $id_address = NULL){
	return Address::get_list_address_app($id_customer,$id_address);
}

public function set_address($arg){

	$id_address_rfc = $this->get_address_rfc($arg['id_customer']);
	$address = NULL;
	if($id_address_rfc != 0){
		$address = new Address($id_address_rfc);
	}elseif (isset($arg['id'])) {
		$address = new Address((int) $arg['id']);
	}
	
	if (!Validate::isLoadedObject($address))
		$address = new Address();
	

	$address->id_customer = $arg['id_customer'];
	$address->id_country = $arg['id_country'];
	$address->id_state = $arg['id_state'];
	$address->id_city = $arg['id_city'];


	if (isset($arg['alias'])) {
		$address->alias = $arg['alias'];
	}elseif (!isset($address->id) ) {
		$sql = 'SELECT COUNT(id_address)
		FROM '._DB_PREFIX_.'address 
		WHERE id_customer ='.(int) $arg['id_customer'];
		$total = (Db::getInstance()->getValue($sql)) + 1;		
		$address->alias = 'Dirección '.$total;	
	}	

	$customer = new Customer((int) $arg['id_customer']);
	$address->lastname = $customer->lastname;
	$address->firstname = $customer->firstname;
	$address->address1 = $arg['address1'];
	$address->address2 = $arg['address2'];
	$address->city = $arg['city'];
	$address->phone = $arg['phone'];
	$address->phone_mobile = $arg['mobile'];

	if(isset($arg['is_rfc']) && Validate::isRFC($arg['is_rfc']) && isset($arg['dni'])){
		$address->dni = $arg['dni'];
	}elseif (isset($arg['dni'])) {
		$address->dni = $arg['dni'];
	}

	$address->postcode = $arg['postcode'];
	if(isset($arg['id_colonia']))
		$address->id_colonia = $arg['id_colonia'];
	if(isset($arg['is_rfc']))
		$address->is_rfc = (boolean) $arg['is_rfc'];

	$flag = FALSE;
	if($id_address_rfc != 0 || !empty($address->id)){
		$flag = $address->update();
	}else{
		$flag = $address->add();
	}

	if($flag){
		return  array('id'=>$address->id,
		              'success' => TRUE);
	}
	return  array('success' => FALSE);

}

/**
 * Retorna la dirección de facturación
 */
private function get_address_rfc($id_customer){

	return	$id_address = (int) Db::getInstance()->getValue("SELECT id_address
	                                                       FROM "._DB_PREFIX_."address
	                                                       WHERE is_rfc = 1 AND id_customer = ". (int) $id_customer );
}

	/**
	 * Obtiene todas las imagenes del producto
	 * @return array
	 */
	public function get_fromPostcode($cod_postal){
		$temp = City::getIdCodPosIdCityIdStateByPostcode($cod_postal);
		return array('id_postal'=>$temp[0]['id_codigo_postal'],
		             'postal_code' => $temp[0]['codigo_postal'],
		             'id_city' => $temp[0]['id_city'], 'id_state' => $temp[0]['id_state']);
	}


	/**
	 * Obtiene todas las imagenes del producto
	 * @return array
	 */
	public function get_colonia_fromid_city($id_city){
		$out_array = array();
		foreach (City::getColoniaByIdCity($id_city) as $key => $value) {
			$out_array[$key]['id'] = $value['id_codigo_postal'];
			$out_array[$key]['name'] = $value['nombrecolonia'];
		}
		
		return $out_array;

	}

	/**
	 * 
	 */
	public function get_countries(){
		return Address::get_countries_app();
	}

	/**
	 * 
	 */
	public function get_states($id_country){
		return Address::get_states_app($id_country);
	}

	/**
	 * 
	 */ 
	public function get_cities($id_state){
		return Address::get_cities_app($id_state);
	}
// get_costo_envio
	public function get_costo_envio($id_city) {
		$address = new Address();
		return $address->get_costo_envio($id_city);
	}


	/*public function cart($products_app, $id_customer, $id_address = 0 ,$discounts = NULL, $deleteDiscount = NULL, $msg= NULL) {
		if (count($products_app) > 0) {
            //echo 'crear carrito';
            $this->context = Context::getContext(); // actualizar contexto
            if (!isset($this->context->cookie->id_cart) && empty($this->context->cookie->id_cart))
            {
            	$this->context->cart = new Cart();
            	$this->context->cart->id_currency = Configuration::get('PS_CURRENCY_DEFAULT'); 
    			// Agrega el carrito a la base de datos
            	$this->context->cart->add();
            	$this->context->cookie->id_cart = $this->context->cart->id;
            }else{
            	$this->context->cart = new Cart($this->context->cookie->id_cart);
            	$this->clearCart();	
            }
            $this->context->cookie->{'msg_app'} = json_encode($msg);
            include(_PS_CLASS_DIR_.'Mobile_Detect.php');
            $detect = new Mobile_Detect;
            $this->context->cart->device = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');            

            $this->context->cart->id_customer = (int) $id_customer;
            $this->context->cart->id_address_delivery = (int) $id_address;
            $this->context->cart->id_address_invoice =  (int) $id_address;
            $this->context->cart->update();

            // agrgar productos al carrito
            foreach ($products_app as $value) {
            	$this->context->cart->updateQty($value['qty'], $value['id'], 0, 0, 'up', 0);
            }
            // Valida y agrega cupon de descuento
            if (isset($discounts) && !empty($discounts) && is_array($discounts) && count($discounts) > 0 ) {
            	foreach ($discounts as $key => $value) {
            		$cartRule = NULL;
            		if( $value['type_voucher'] == 'md' ) {							
            			$iddoc = trim($value['cupon']);
            			$cartRule = new CartRule(CartRule::getIdByDoctor($iddoc));
            		} elseif( $value['type_voucher'] == 'cupon' ) {
            			$cartRule = new CartRule(CartRule::getIdByCode(trim($value['cupon'])));
            		}
            		if(!empty($cartRule))
            			$this->context->cart->addCartRule($cartRule->id);

            	//exit(print_r($cartRule));
            	}
            }
            // Valida y remueve regla de carrito
           // if(isset($deleteDiscount) && !empty($deleteDiscount)){
           //  	if (($id_cart_rule = (int)$deleteDiscount) && Validate::isUnsignedId($id_cart_rule)){
           //  		$this->context->cart->removeCartRule($id_cart_rule);
           //  	}
           //  }
            $this->context->cart->update();
        } else {
        	$this->errors[] = 'No se enviaron productos para crear el Carrito.';
        }
        $products = array();
        foreach ($this->context->cart->getProducts() as $key => $value) {
        	$img = NULL;
        	foreach ($products_app as  $value2) {
        		if($value2['id'] == $value['id_product'] ){
        			$img = $value2['img'];  
        		}
        	}

        	$products[] = array('id' => (int)$value['id_product'],'name' => $value['name'],'price' => $value['price_wt'],'img'=> $img,'qty' => (int)$value['cart_quantity']);
        }

        
        	$totales = array();
        
        	$totales['total_sin_inpuestos'] = $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);
			$totales['total_con_inpuestos'] = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
			$totales['total_con_inpuestos_2'] = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING);
			$totales['total_sin_inpuestos_2'] = $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING);

			print_r($totales);
			exit();*/
/*        $discounts = array();
        foreach ($this->context->cart->getDiscounts() as $key => $value) {
        	$discounts[] = array('id_cart_rule' => (int)$value['id_cart_rule'], 'code' => $value['code'], 'value_real' => $value['value_real']);
        }

        $msg = json_decode($this->context->cookie->{'msg_app'});
        return array('id_customer' => (int)$this->context->cart->id_customer,'msg' => $msg,'id_address' => (int)$this->context->cart->id_address_invoice ,'order_total' => $this->context->cart->getOrderTotal(), 'sub_total' => 13456.85,'products' => $products,'discounts' => $discounts,
                     'total_discounts'=>$this->context->cart->getOrderTotal(TRUE,Cart::ONLY_DISCOUNTS),'shipping_cost'=>(float)$this->context->cart->getTotalShippingCost(),
                     'rx'=>true);
}*/



public function cart($products_app, $id_customer, $id_address = 0 ,$discounts = NULL, $deleteDiscount = NULL, $msg= NULL, $id_cart = null, $clear = FALSE) {

	if (is_array($products_app) && count($products_app) > 0) {
            //echo 'crear carrito';
            $this->context = Context::getContext(); // actualizar contexto
            // carga un carrito de la sesión o por el id_cart
            $cart_exist = (int) Db::getInstance()->getValue("SELECT COUNT(id_cart) total FROM ps_cart WHERE id_cart = ". (int) (isset($id_cart)? $id_cart :  $this->context->cookie->id_cart ));
            if ( (isset($this->context->cookie->id_cart) && !empty($this->context->cookie->id_cart)) || $cart_exist != 0) {
            	$this->context->cart = new Cart((int) (isset($id_cart)? $id_cart :  $this->context->cookie->id_cart ) );
            	$this->context->cookie->id_cart = $this->context->cart->id;
            	$this->clearCart();	

            } else {
            	// crear un carrito nuevo
            	$this->context->cart = new Cart();
            	$this->context->cart->id_currency = Configuration::get('PS_CURRENCY_DEFAULT'); 
    			// Agrega el carrito a la base de datos
            	$this->context->cart->add();
            	$this->context->cookie->id_cart = $this->context->cart->id;
            }
            // agrgar productos al carrito
            foreach ($products_app as $value) {
            	$this->context->cart->updateQty($value['qty'], $value['id'], 0, 0, 'up', 0);
            }

        }
        // Cargar un carrito de la sesión o por id_cart
        if ( (isset($this->context->cookie->id_cart) && !empty($this->context->cookie->id_cart)) || $id_cart != NULL && empty($products_app)) {
        	$this->context = Context::getContext(); // actualizar contexto
        	$this->context->cart = new Cart((int) (isset($id_cart)? $id_cart :  $this->context->cookie->id_cart ) );
        	$this->context->cookie->id_cart = $this->context->cart->id;
        	if($clear){
        		$this->clearCart();	
        	}
        }

        
        // actualizar atributos del carrito si existe 
        if(isset($this->context->cart) && !empty($this->context->cart) && (isset($this->context->cookie->id_cart) && !empty($this->context->cookie->id_cart)) ){
        	$this->context->cookie->{'msg_app'} = json_encode($msg);
        	include(_PS_CLASS_DIR_.'Mobile_Detect.php');
        	$detect = new Mobile_Detect;
        	$this->context->cart->device = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet_app' : 'phone_app') : 'computer_app');            

        	$this->context->cart->id_customer = (int) $id_customer;
        	$this->context->cart->id_address_delivery = (int) $id_address;
        	$this->context->cart->id_address_invoice =  (int) $id_address;
        	$this->context->cart->update();

            // Valida y agrega cupon de descuento
        	$aplicar_cupon = 0;
        	if (isset($discounts) && !empty($discounts) && is_array($discounts) && count($discounts) > 0 ) {
        		$aplicar_cupon = 1;
        		foreach ($discounts as $key => $value) {
        			$cartRule = NULL;
        			if (isset($value['type_voucher'])) {
	        			if( $value['type_voucher'] == 'md' ) {							
	        				$iddoc = trim($value['cupon']);
	        				$cartRule = new CartRule(CartRule::getIdByDoctor($iddoc));
	        			} elseif( $value['type_voucher'] == 'cupon' ) {
	        				$cartRule = new CartRule(CartRule::getIdByCode(trim($value['cupon'])));
	        			}
        			}
        			if(!empty($cartRule))
        				$this->context->cart->addCartRule($cartRule->id);

            	//exit(print_r($cartRule));
        		}
        	}
            // Valida y remueve regla de carrito
/*            if(isset($deleteDiscount) && !empty($deleteDiscount)){
            	if (($id_cart_rule = (int)$deleteDiscount) && Validate::isUnsignedId($id_cart_rule)){
            		$this->context->cart->removeCartRule($id_cart_rule);
            	}
            }*/
            $this->context->cart->update();   
            
            $products = array();
            $productsFormula = array();

            foreach ($this->context->cart->getProducts() as $key => $value) {

            	$img = NULL;
            	foreach ($products_app as  $value2) {

            		if($value2['id'] == $value['id_product'] ){
            			$img = $value2['img'];  
            		}

            	}

            	$products[] = array('id' => (int)$value['id_product'],'name' => $value['name'],'price' => $value['price_wt'],'img'=> $img,'qty' => (int)$value['cart_quantity']);
            	$productsFormula[] = $value['id_product'];

            }


            $subtotal = 0;

            if ( $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS) != $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING) ) {
            	$subtotal = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING);
            } else {
            	$subtotal = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
            }


            if ( $id_cart != NULL ) {

            	$discounts_return = array();
            	foreach ($this->context->cart->getDiscounts() as $key => $value) {
            		$type_voucher = 'cupon';
            		if($value['id_cart_rule'] == Db::getInstance()->getValue("SELECT id_cart_rule FROM "._DB_PREFIX_."cruzar_medcupon WHERE id_cart_rule = ".(int)$value['id_cart_rule']))
            			$type_voucher ='md';

            		$discounts_return[] = array('type_voucher' => 'cupon', 'cupon' => $value['code'], 'success' => true, 'msg' => 'Cupon aplicado correctamente');
            	}

            } else {
            	$discounts_return = array();
            	$discounts_return[] = array('success' => null);
            }


            if ( isset($discounts_return[0]) && $aplicar_cupon == 1 && !isset( $discounts_return[0]['success'] ) && !$discounts_return[0]['success'] == true ) {

            	$discounts_return = array();
            	$discounts_return[] = array( 'success' => false, 'msg' => 'Cupon incorrecto, no aplicado' );

            }


/*        $discounts = array();
        foreach ($this->context->cart->getDiscounts() as $key => $value) {
        	$discounts[] = array('id_cart_rule' => (int)$value['id_cart_rule'], 'code' => $value['code'], 'value_real' => $value['value_real']);
        }*/

        $medios_de_pago = NULL;

        if((int) $id_address > 0){
        	$medios_de_pago = $this->list_medios_de_pago();
        }

        $msg = json_decode($this->context->cookie->{'msg_app'});
        return array('id_cart' => (int)$this->context->cart->id,'id_customer' => (int)$this->context->cart->id_customer,'msg' => $msg,'id_address' => (int)$this->context->cart->id_address_invoice ,'order_total' => $this->context->cart->getOrderTotal(), 'sub_total' => $subtotal,'products' => $products,'discounts' => $discounts_return,
                     'total_discounts'=>$this->context->cart->getOrderTotal(TRUE,Cart::ONLY_DISCOUNTS),'shipping_cost'=>(float)$this->context->cart->getTotalShippingCost(),
                     'rx'=> Cart::prodsHasFormula($productsFormula),'mediosp' => $medios_de_pago);                     
    }

    return array("ERROR" => 'Parámetros inválidos.','success' => false);
}


// limpiar carrito
private function clearCart()
{
	$this->context = Context::getContext();
	$this->context->cart->removeCartRules();
	$products = $this->context->cart->getProducts();
	foreach ($products as $product) {
		$this->context->cart->deleteProduct($product["id_product"]);
	}
}	
/**
 * 
 */
public function pay($args){

	$app_cart = $this->cart($args['products'],$args['id_customer'],$args['id_address'],$args['discounts'],NULL,$args['msg'], $args['id_cart']);
	$this->context = Context::getContext();
	if (!isset($this->context->cookie->id_cart) && empty($this->context->cookie->id_cart))
	{ 
		$this->errors[] = 'No existe un carrito en el contexto.';
		return $this->errors;
	}
	$flg = false;
	// valida si el pago debe enviarse a una pasarela de pago
	foreach (PasarelaPagoCore::GetPMediosPsarelas() as $key => $value) {
		if ($value['nombre'] == $args['payment']['method']) {
			$flg = true;
			break;
		}
	}
	// Enviando el pago a una pasarela de pago
	if($flg){

		$data_payment = array('id_cart' 				=> $this->context->cart->id,
		                      'total_paid' 				=> $this->context->cart->getOrderTotal(true, Cart::BOTH),
		                      'id_customer' 			=> $this->context->cart->id_customer,
		                      'id_order' 				=> 0,
		                      'id_address_invoice' 		=> $this->context->cart->id_address_invoice,
		                      'option_pay'				=> $args['payment']['method'],
		                      'numerot' 				=> $args['payment']['number'],
		                      'codigot' 				=> $args['payment']['cvc'],
		                      'date' 					=> $args['payment']['expiry'],
		                      'nombre' 					=> $args['payment']['name'],
		                      'cuotas' 					=> $args['payment']['dues'],
		                      'pse_bank' 				=> $args['payment']['pse_code'],
		                      'name_bank' 				=> $args['payment']['pse_name'],
		                      'pse_tipoCliente' 		=> $args['payment']['customer_type'],
		                      'pse_docType' 			=> $args['payment']['doc_type'],
		                      'pse_docNumber' 			=> $args['payment']['customer_dni'],
		                      'token_id'				=> $args['payment']['token_id'],
		                      'openpay_device_session_id'		=> $args['payment']['device_session_id']);

		$order_state = PasarelaPagoCore::payOrder($data_payment);


		if($order_state == Configuration::get('PS_OS_ERROR')){
			$message = 'Ha ocurrido un error al realizar el pago, valida tus datos o intenta con otro medio de pago.';
			return array('success' => FALSE,'message' => $message , "erros" => PasarelaPagoCore::getLastErrorByCart($this->context->cart->id));
		}else{
			return $this->createOrder($args['payment']['method'],$order_state);
		}


	}else{ // pagos con cashondelivery
		return $this->createOrder($args['payment']['method'] , Configuration::get('PS_OS_PREPARATION'));
	}	
}

/**
 * 
 */
private function createOrder($method,$state) {


	require_once(_PS_MODULE_DIR_ . 'cashondelivery/cashondelivery.php');
	$payment = new CashOnDelivery();
	//$payment = new PaymentWs();
        $this->context = Context::getContext(); // actualizar contexto
        $this->context->cart->update();

        $total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);
        $customer = new Customer($this->context->cart->id_customer);
        $extra_vars = array();

        try {
        	// variables de pago deposito en efectivo
        	$extra_vars = PasarelaPagoCore::get_extra_vars_payu($this->context->cart->id,$method);

        	// agregar a la sonda
        	$conn = PasarelaPagoCore::GetDataConnect($method);
        	$date = date("Y-m-d H:i:s");
        	$interval = 0;
        	if($method == 'Tarjeta_credito'){
        		$interval = 11;         
        	}else{
        		$interval = 61; 
        	}


        	$result = Db::getInstance()->insert('sonda_payu', array(
        	                                    'id_cart' => (int)$this->context->cart->id,
        	                                    'date_add'      => pSQL($date),
        	                                    'interval' => (int)$interval,
        	                                    'last_update' => pSQL($date),
        	                                    'pasarela' => pSQL($conn['nombre_pasarela']),
        	                                    ));


        	if(!$result)
        		Logger::AddLog('Error al agregar la sonda_payu (App) id_cart: '.$this->context->cart->id, 2, null, null, null, true);


		if($conn['nombre_pasarela'] == 'payulatam')
        	$payment->name = $conn['nombre_pasarela'];

        	$payment->validateOrder((int) $this->context->cart->id, $state, $total, $method, NULL, $extra_vars, (int) $this->context->currency->id, false, $customer->secure_key);

        	$order = new Order();
        	$order = new Order($order->getOrderByCartId($this->context->cart->id));

            $this->context = Context::getContext(); // actualizar contexto

            $order_state = Db::getInstance()->getValue("SELECT  `name` FROM ps_order_state_lang WHERE id_order_state = ". (int) $state);

            $response = array('id_cart' => $this->context->cart->id,
                              'id' => $order->id,
                              'reference' => $order->reference,
                              'total_order'=>$order->total_paid,
                              'total_products_wt'=>$order->total_products_wt,
                              'total_shipping'=>$order->total_shipping,
                              'total_products'=>$order->total_products,
                              'total_discounts_tax_incl'=>$order->total_discounts_tax_incl,
                              'total_discounts_tax_excl'=>$order->total_discounts_tax_excl,
                              'order_state' => $order_state);

        } catch (Exception $exc) {


        	$this->errors['message'] .= 'Error creando la orden: ' . $exc;
        }


        $obj= array();
        if (!count($this->errors) > 0) {
        	$obj = array('order' => $response, 'pay_info' => $extra_vars,'success' => TRUE,'message'=>'Orden creada satisfactoriamente.');
        } else {
        	$obj = array('response' => $response, 'success' => FALSE,'message' => $this->errors['message']);
        }
        return $obj;
    }

    public function add_image($files,$option){

    	//$re = "/^data:image\\/[\\D]{3,14};base64,.*$/"; 
    	//if(!preg_match($re, $str_img))
    	if($files['name'] == '{')
    		$files['name'] = PasarelaPagoCore::randString(15).'.jpg';

    	if(!isset($files['name']) && !isset($files['type']) && !isset($files['tmp_name']))
    		return array('ERROR'=>'Debes enviar un archivo');

    	$this->context = Context::getContext();
    	 //return $this->context->customer;

    	if (!isset($this->context->customer->id) || empty($this->context->customer->id))
    		return array('ERROR'=>'Debes iniciar sesión para agregar la foto de tu perfil.');

    	$path = _PS_ROOT_DIR_.'/KWE54O31MDORBOJRFRPLMM8C7H24LQQR/';    	
    	if($option == 'profile'){
    		$path = _PS_ROOT_DIR_.'/img/customers/profile/';		
    	}


    	//$name_file = "Receta_medica_".$this->context->cart->id_customer_.date('Y-m-d H:i:s');
    	//$name_file = str_replace(' ','-',$name_file);
    	$storage_name = ""; //PasarelaPagoCore::randString(); 
    	$full_name = ""; 

    	$flag = TRUE;
    	while ($flag)
    	{
    		$storage_name = PasarelaPagoCore::randString();
    		$full_name = $path.$storage_name;
    		if (!file_exists($full_name))
    		{
    			$flag= false;
    		}
    	}

    	$uploadfilename = $files['tmp_name'];

    	$save_flag = false;

    	if($option == 'profile'){
    		$img_resize = $this->resize_image($uploadfilename, 200, 200);
    		if( imagejpeg($img_resize, $full_name)){	
    			$save_flag = TRUE;
    			$img_del = $path.$this->get_img_profile($this->context->customer->id);
    			if (file_exists($img_del))
    				unlink($img_del);
    		}
    	}elseif(move_uploaded_file($uploadfilename,$full_name)){
    		$save_flag = TRUE;
    	}

    	if ($save_flag)
    	{
//.'|'.end(explode('.',$files['name']));

    		if($option == 'profile'){
    			$this->context->customer->img_profile = $storage_name;
    			return $this->context->customer->update();

    		}
    		return	Db::getInstance()->autoExecute(_DB_PREFIX_.'formula_medica', array(
    		                                      'medio_formula' =>    (int)4,
    		                                      'nombre_archivo_original' =>    pSQL($files['name']),   
    		                                      'nombre_archivo' =>    pSQL($storage_name), 
    		                                      'fecha' =>    pSQL(date('Y-m-d H:i:s')),
    		                                      'id_cart_fk' =>    (int) $this->context->cart->id,
    		                                      'id_cunstomer_fk' =>    (int) $this->context->cart->id_customer,

    		                                      ), 'INSERT'); 
    	}


    	return FALSE;
    }

    public function resize_image($file, $w, $h, $crop=FALSE) {
    	list($width, $height) = getimagesize($file);
    	$r = $width / $height;
    	if ($crop) {
    		if ($width > $height) {
    			$width = ceil($width-($width*abs($r-$w/$h)));
    		} else {
    			$height = ceil($height-($height*abs($r-$w/$h)));
    		}
    		$newwidth = $w;
    		$newheight = $h;
    	} else {
    		if ($w/$h > $r) {
    			$newwidth = $h*$r;
    			$newheight = $h;
    		} else {
    			$newheight = $w/$r;
    			$newwidth = $w;
    		}
    	}
    	$src = imagecreatefromjpeg($file);
    	$dst = imagecreatetruecolor($newwidth, $newheight);
    	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    	return $dst;
    }


    public function password($email){
    	if (isset($email) && !empty($email))
    	{
    		if (!($email = trim(Tools::getValue('email'))) || !Validate::isEmail($email))
    			return array('success' => FALSE,'message'=>'Dirección de correo invalida');
    		else
    		{

    			$customer = new Customer();
    			$customer->getByemail($email);
    			if (!Validate::isLoadedObject($customer))
    				return array('success' => FALSE,'message'=>'No hay una cuenta registrada con esta dirección de correo.');
    			elseif (!$customer->active)
    				return array('success' => FALSE,'message'=>'No puedes regenerar la contraseña para esta cuenta.');
    			elseif ((strtotime($customer->last_passwd_gen.'+'.(int)($min_time = Configuration::get('PS_PASSWD_TIME_FRONT')).' minutes') - time()) > 0)
    				return array('success' => FALSE,'message'=>'Puede regenerar la contraseña cada '.(int)$min_time.' minutos ');
    			else
    			{
    				$mail_params = array(
    				                     '{email}' => $customer->email,
    				                     '{lastname}' => $customer->lastname,
    				                     '{firstname}' => $customer->firstname,
    				                     '{url}' => $this->context->link->getPageLink('password', true, null, 'token='.$customer->secure_key.'&id_customer='.(int)$customer->id)
    				                     );
    				if (Mail::Send($this->context->language->id, 'password_query', Mail::l('Password query confirmation'), $mail_params, $customer->email, $customer->firstname.' '.$customer->lastname))
    					return array('success' => TRUE,'message'=>'Enviamos enviado un mensaje al correo '.$customer->email.', sigue las instrucciones del mensaje para cambiar tu contraseña.');
    				else
    					return array('success' => FALSE,'message'=>'Ocurrió un error enviando el correo.');
    			}
    		}
    	}
    	return array('success' => FALSE,'message'=>'Debes colocar tu dirección de correo.');
    }

    private  function base64_to_img($base64_string, $file_name) {
    	try{
    		$ifp = fopen($file_name, "wb"); 
    		$data = explode(',', $base64_string);
    		fwrite($ifp, base64_decode($data[1])); 
    		fclose($ifp); 

    		return TRUE; 
    	}catch (Exception $e) {
    		exit(print_r($e,true));
    		return FALSE;
    	}	
    }   

    public function get_order_datail($id_order){
    	$sql = " SELECT p.`name`,
    	od.product_quantity,
    	od.product_price,
    	od.total_price_tax_incl,
    	MAX(i.`id_image`) AS id_image,
    	p.id_product
    	FROM `ps_order_detail` od
    	LEFT JOIN `ps_product_lang` p ON (p.id_product = od.product_id)
    	LEFT JOIN `ps_product_shop` ps ON (ps.id_product = p.id_product AND ps.id_shop = od.id_shop)
    	LEFT JOIN `ps_image` i ON (i.`id_product` = p.`id_product` AND  i.cover = 1)
    	LEFT JOIN `ps_image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = 0) 
    	WHERE od.`id_order` = ".(int) $id_order. "
    	GROUP BY p.id_product;
    	";

    	//error_log($sql,0);
    	if ($results = Db::getInstance()->ExecuteS($sql)) {

    		foreach ($results as $key => $value) {
    			$results[$key]['product_price'] = Tools::ps_round($results[$key]['product_price'], 2);
    			$results[$key]['total_price_tax_incl'] = Tools::ps_round($results[$key]['total_price_tax_incl'], 2);
    			$img_url = NULL;
    			if(!empty($value['id_image'])){
    				$img_url = _PS_BASE_URL_
    				. __PS_BASE_URI__
    				. 'img/p/'
    				. Image::getImgFolderStatic($value['id_image'])
    				. $value['id_image']
    				. '-home_default.jpg';
    			}else{
    				$img_url = _PS_BASE_URL_
    				. __PS_BASE_URI__
    				. 'img/p/es-default-home_default.jpg';


    			}
    			$results[$key]['img_url'] = $img_url; 
    		}

    		return $results;

    	}
    	return array('success'=> FALSE);
    }
    public function get_type_docs(){
    	$sql = "SELECT id_document as id, document as `value`
    	FROM ps_document 
    	WHERE active = 1";
    	return Db::getInstance()->ExecuteS($sql);
    }

    public function get_traker_order($id_order){

    	$sql = "SELECT ol.id_order_state,
    	ol.`name` as order_state,
    	cu.firstname,
    	cu.lastname,
    	o.id_order,
    	o.reference,
    	o.payment,
    	o.total_paid_tax_incl,
    	o.total_discounts,
    	o.total_paid,
    	asoc.entity,
    	o.total_products,
    	o.total_products_wt,
    	o.total_shipping,o.total_shipping_tax_incl,
    	CASE asoc.entity
    	WHEN  'employee' THEN CONCAT(emp.firstname,' ' , emp.lastname)
    	WHEN  'Carrier' THEN carri.`name`
    	ELSE 'N/A'
    	END AS carrier,
    	ad.address1,
    	ad.address2,
    	ad.postcode
    	FROM "._DB_PREFIX_."orders o
    	INNER JOIN "._DB_PREFIX_."order_state_lang ol ON (o.current_state = ol.id_order_state)
    	LEFT JOIN "._DB_PREFIX_."associate_carrier ac ON (o.id_order = ac.id_order)
    	LEFT JOIN "._DB_PREFIX_."customer cu ON (o.id_customer = cu.id_customer)
    	LEFT JOIN "._DB_PREFIX_."associate_carrier asoc ON(o.id_order = asoc.id_order)
    	LEFT JOIN "._DB_PREFIX_."employee emp ON(asoc.id_entity = emp.id_employee )
    	LEFT JOIN "._DB_PREFIX_."carrier carri ON (asoc.id_entity = carri.id_carrier)
    	LEFT JOIN "._DB_PREFIX_."address ad ON (o.id_address_delivery = ad.id_address)
    	WHERE o.id_order = ".(int) $id_order;

    	return Db::getInstance()->getRow($sql);



    } 



    private function get_id_city_select_address($id_address = null) {

    	$this->context = Context::getContext();
    	$direccion = (is_null($id_address))?$this->context->cart->id_address_delivery:$id_address;

    	try {
    		$sql = 'SELECT adc.id_city
    		FROM '._DB_PREFIX_.'address adr 
    		INNER JOIN '._DB_PREFIX_.'address_city adc ON (adc.id_address=adr.id_address)
    		WHERE adc.id_address= ' . (int) $direccion;

    		if ($results = Db::getInstance()->ExecuteS($sql)) {

    			foreach ($results as $row) {

    				if ($row['id_city'] != null && $row['id_city'] != '' && $row['id_city'] != 0) {
    					return $row['id_city'];
    				} else {
    					return null;
    				}
    			}
    		} else {
    			return null;
    		}
    	} catch (Exception $exc) {
    		return null;
    	}
    }


    public function list_medios_de_pago($id_address = null) {

    	$this->context = Context::getContext();

    	$query = "select mediosp.id_medio_de_pago,mediosp.Activo, IF (ISNULL(pepe.id_medio_de_pago),0,1) as inrule,mediosp.nombre 
    	from "._DB_PREFIX_."medios_de_pago mediosp LEFT JOIN 
    	( SELECT mediospin.id_medio_de_pago, rules.id_ciudad FROM "._DB_PREFIX_."medios_de_pago mediospin INNER JOIN "._DB_PREFIX_."rules_mediosp_ciudades rules 
    	 ON( mediospin.id_medio_de_pago = rules.id_medio_de_pago AND rules.id_ciudad = " .(int) $this->get_id_city_select_address($id_address). ")
    	 ) AS pepe ON (pepe.id_medio_de_pago = mediosp.id_medio_de_pago);";

$list_mediosp = array();

if ($results = Db::getInstance()->ExecuteS($query)) {
	if (count($results) > 0) {

		foreach ($results as $value) {
                    if ($value['Activo'] && $value['inrule']) { // Si el medio de pago esta en modo activo y la ciudad esta en la tabla rules, etonces se oculta para este medio de pago
                    	$list_mediosp[] = array("active" => 0, 'name' => $value['nombre']);
                    } elseif (!$value['Activo'] && $value['inrule']) { // Si el medio de pago esta en modo inactivo y la ciudad esta en la tabla rules, etonces se muestra la ciudad para este medio de pago
                    	$list_mediosp[] = array("active" => 1, 'name' => $value['nombre']);
                    } elseif ($value['Activo'] && !$value['inrule']) { // Si el medio de pago esta en modo activo y la ciudad no esta en la tabla rules, etonces se muestra el medio de pago para esta ciudad
                    	$list_mediosp[] = array("active" => 1, 'name' => $value['nombre']);
                    } elseif (!$value['Activo'] && !$value['inrule']) { // si el medio de pago esta en modo inactivo y la ciudad no esta en la tabla rules, etonces se oculta el medio de pago para esta ciudad
                    	$list_mediosp[] = array("active" => 0, 'name' => $value['nombre']);
                    }
                }
            }
        }
        
        $str_list='';
        $flag = true;
        foreach ($list_mediosp as $value) {

        	if ($flag) {
        		$str_list .=$value['name'] . '=' . $value['active'];
        		$flag = FALSE;
        	} else {
        		$str_list .='&' . $value['name'] . '=' . $value['active'];
        	}
        }
        $output = array();
        parse_str($str_list, $output);

        return $output;
    }

    public function get_img_profile($id_customer){

    	$sql= "SELECT img_profile
    	FROM
    	"._DB_PREFIX_."customer 
    	WHERE id_customer = ".(int) $id_customer;

    	return (Db::getInstance()->getValue($sql));	
    }

    public function call_api($accessToken,$url){

/*    	require_once dirname(__FILE__).'/../../../tools/google/autoload.php';

    	  $state = sha1(openssl_random_pseudo_bytes(1024));
		  $app['session']->set('state', $state);
		  // Set the client ID, token state, and application name in the HTML while
		  // serving it.
		  return $app['twig']->render('index.html', array(
		      'CLIENT_ID' => CLIENT_ID,
		      'STATE' => $state,
		      'APPLICATION_NAME' => 'appflwebclient'
		  ));

*/
echo "<br> at: ".$accessToken;

    	//$accessToken = 'ya29.CjHaAtQ95_PSmXHoRYITUHADFZ-JvHSTHmPonWoktncdOG9wr2bUlg8NOutyNGHNXivJ';
$userDetails = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $accessToken);
echo "\r\n <br> det:";
print_r($userDetails);
$userData = json_decode($userDetails);

if (!empty($userData)) {

	$googleUserId = '';
	$googleEmail = '';
	$googleVerified = '';
	$googleName = '';
	$googleUserName = '';

	if (isset($userData->id)) {
		echo " <br> 1 :".$googleUserId = $userData->id;
	}
	if (isset($userData->email)) {
		echo " <br> 2 :".$googleEmail = $userData->email;
		echo " <br> 3 :".$googleEmailParts = explode("@", $googleEmail);
		echo " <br> 4 :".$googleUserName = $googleEmailParts[0];
	}
	if (isset($userData->verified_email)) {
		echo " <br> 5 :".$googleVerified = $userData->verified_email;
	}
	if (isset($userData->name)) {
		echo " <br> 6 :".$googleName = $userData->name;
	}
} else {

	echo "Not logged In";
}

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
$curlheader[0] = "Authorization: Bearer " . $accessToken;
curl_setopt($curl, CURLOPT_HTTPHEADER, $curlheader);

$json_response = curl_exec($curl);

echo "\r\n <br> 1 :";
print_r($json_response);
curl_close($curl);


$responseObj = json_decode($json_response);

return $responseObj;       

}

}
