<?php
include(dirname(__FILE__) . '/../config/config.inc.php');
include(dirname(__FILE__) . '/../init.php');

class ajax_products extends FrontController {



    public function check_product($id_product) {

        /*$query = "select pshop.id_product,plang.`name`,tracker.key1 FROM
                    ps_sync_tracker tracker 
                    INNER JOIN ps_product_shop pshop ON (tracker.key2=pshop.id_product)
                    INNER JOIN ps_product prod ON( pshop.id_product=prod.id_product)
                    INNER JOIN ps_product_lang plang ON (prod.id_product=plang.id_product)
		    INNER JOIN ps_stock_available stock ON (prod.id_product=stock.id_product)
                    LEFT JOIN ps_product_black_list blist on (blist.id_product = prod.id_product)
                    WHERE  tracker.sync_module_cd='PS_ITEM' AND pshop.active=1 and prod.active=1
                    AND prod.is_virtual = 0  AND prod.id_category_default <> 783
                    AND  ISNULL(blist.id_product) AND stock.quantity > 0";*/
        
        $query = "SELECT pshop.id_product,plang.`name`,tracker.key1,
        pshop.price AS precio,
        IF (t.rate IS NULL, 0, t.rate) as impuesto, 
        SUBSTRING(REPLACE( (pshop.price * (1+ (IF (t.rate IS NULL, 0, t.rate)/100) )),'.',','),1,
        LENGTH(REPLACE( (pshop.price * (1+ (IF (t.rate IS NULL, 0, t.rate)/100) )),'.',',')) -7)  AS precio_venta 
FROM ps_sync_tracker tracker 
                    INNER JOIN ps_product_shop pshop ON (tracker.key2=pshop.id_product)
                    INNER JOIN ps_product prod ON( pshop.id_product=prod.id_product)
                    INNER JOIN ps_product_lang plang ON (prod.id_product=plang.id_product)
                                        INNER JOIN ps_stock_available stock ON (prod.id_product=stock.id_product)
                    LEFT JOIN ps_product_black_list blist ON (blist.id_product = prod.id_product)

 LEFT JOIN ps_tax_rule tr ON (pshop.id_tax_rules_group = tr.id_tax_rules_group AND tr.id_tax != 0)
 LEFT JOIN ps_tax t ON (t.id_tax = tr.id_tax)

                    WHERE  tracker.sync_module_cd='PS_ITEM' AND pshop.active=1 AND prod.active=1
                    AND prod.is_virtual = 0  AND prod.id_category_default <> 783
                    AND  ISNULL(blist.id_product) AND stock.quantity > 0
                    and tracker.key1 ='".$id_product."';";
                $product_details = array();
        if ($results = Db::getInstance()->ExecuteS($query)) {

    
            if (count($results) > 0) {
                $product_details['id_product'] = $results[0]['id_product'];
                $product_details['key1'] = $results[0]['key1'];
                $product_details['impuesto'] = $results[0]['impuesto'];
                $product_details['precio'] = $results[0]['precio'];
                $product_details['precio_venta'] = (round( (round($results[0]['precio_venta'] * 1)/100) *2 , 0)/ 2 ) * 100;

                return json_encode(array('results' => "enabled", 'vals_prod' => $product_details ));
            }
        }
       return json_encode(array('results' => "disabled"));
    }

   

}

if (isset($_POST) && !empty($_POST) && isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['value']) && !empty($_POST['value'])) {

    $ajax = new ajax_products();

    switch ($_POST['action']) {
        case 'check_product':
            echo $ajax->check_product($_POST['value']);
            break;
            default :
            echo '0';
            break;
    }
} else {
    echo '0';
}
