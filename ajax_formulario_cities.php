<?php
require(dirname(__FILE__).'/config/config.inc.php');

$id_state = $_REQUEST['id_state'];		//4200

if ( $id_state == '') {
	$id_state = $_POST['id_state'];
} 

if ( $id_state == '') {
	$id_state = $_GET['id_state'];
}

//echo "--".$id_state."--";
$str_cities = '';
$cities = City::getCitiesByStateAvailable($id_state);
if($cities) {
	foreach ($cities as $row){
		//$str_cities .= '<option value="'. $row['id_city'] .'">'. $row['city_name'] . '</option>';
		$str_cities .= '<option value="'. $row['id_city'] .'">'. $row['city_name'] . '</option>';
	}
}
$array_result = array('results' => $str_cities);
echo json_encode($array_result);

?>