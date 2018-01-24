<?php 
echo "<br> Iniciando validación";

include(dirname(__FILE__).'/../config/config.inc.php');
$update = "UPDATE `ps_configuration` SET `value`='0' WHERE name = 'PS_CATALOG_MODE' AND value = '1'";

if ($results = Db::getInstance()->Execute( $update)) {
	echo " <pre> Modo catagolo habilitado: ";
	print_r($results[0]);
} else {
	echo " <pre> Modo catagolo NO habilitado, Query no ejecutado. ";
}
?>
