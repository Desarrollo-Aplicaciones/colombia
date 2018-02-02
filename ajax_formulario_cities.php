<?php
require(dirname(__FILE__).'/config/config.inc.php');

$id_state = $_REQUEST['id_state'];		//4200
$id_country = $_REQUEST['id_country'];

if ( $id_state == '') {
	$id_state = $_POST['id_state'];
} 

if ( $id_state == '') {
	$id_state = $_GET['id_state'];
}

if ( $id_country == '') {
    if(!isset($_POST['id_country'])) {
        $id_country = null;
    } else {
        $id_country = $_POST['id_country'];
    }
} 
if ( $id_country == '') {
    $id_country = $_GET['id_country'];
}
 
//echo "--".$id_state."--";
$str_cities = '';
$cities = City::getCitiesByStateAvailable($id_state, $id_country);
if($cities) {
	foreach ($cities as $row){
		//$str_cities .= '<option value="'. $row['id_city'] .'">'. $row['city_name'] . '</option>';
		$str_cities .= '<option value="'. $row['id_city'] .'">'. $row['city_name'] . '</option>';
	}
}
$array_result = array('results' => $str_cities);
echo json_encode($array_result);

?>