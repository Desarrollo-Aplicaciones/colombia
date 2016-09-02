<?php
include(dirname(__FILE__) . '/../config/config.inc.php');
include(dirname(__FILE__) . '/../init.php');

class ajax_products extends FrontController {



    public function check_product($id_product) {

        $query = "select pshop.id_product,plang.`name`,tracker.key1 FROM
                    ps_sync_tracker tracker 
                    INNER JOIN ps_product_shop pshop ON (tracker.key2=pshop.id_product)
                    INNER JOIN ps_product prod ON( pshop.id_product=prod.id_product)
                    INNER JOIN ps_product_lang plang ON (prod.id_product=plang.id_product)
		    INNER JOIN ps_stock_available stock ON (prod.id_product=stock.id_product)
                    LEFT JOIN ps_product_black_list blist on (blist.id_product = prod.id_product)
                    WHERE  tracker.sync_module_cd='PS_ITEM' AND pshop.active=1 and prod.active=1
                    AND prod.is_virtual = 0  AND prod.id_category_default <> 783
                    AND  ISNULL(blist.id_product) AND stock.quantity > 0
                    and tracker.key1 ='".$id_product."';";

        if ($results = Db::getInstance()->ExecuteS($query)) {

    
            if (count($results) > 0) {
                return json_encode(array('results' => "enabled"));
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
            echo '!';
            break;
    }
} else {
    echo '!';
}
