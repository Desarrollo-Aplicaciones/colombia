<?php 
echo "<br> Iniciando validación";

include(dirname(__FILE__).'/../config/config.inc.php');

$update = "UPDATE `ps_configuration` SET `value`='1' WHERE name = 'PS_CATALOG_MODE' AND value = '0'";

if ($results = Db::getInstance()->Execute( $update)) {
	echo " <pre> Modo catagolo inhabilitado: ";
	print_r($results[0]);
} else {
	echo " <pre> Modo catagolo NO inhabilitado, Query no ejecutado. ";
}	

?>
