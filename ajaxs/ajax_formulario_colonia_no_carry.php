<?php
require(dirname(__FILE__).'/config/config.inc.php');

$id_ciudad = $_POST['city'];		//4200
$str_cities = '<option value="">- Colonia-</option>';
$cities = City::getColoniaByIdCity($id_ciudad);
foreach ($cities as $row){
	//$str_cities .= '<option value="'. $row['id_city'] .'">'. $row['city_name'] . '</option>';
	$str_cities .= '<option value="'. $row['id_codigo_postal'] .'">'. $row['nombrecolonia'] . '</option>';
}

$array_result = array('results' => $str_cities);
echo json_encode($array_result);

?>