<?php
include(dirname(__FILE__) . '/../config/config.inc.php');

$str_employee = '<option value="">- Seleccione -</option>';
$employee = Employee::getEmployees();

foreach ($employee as $row){
	//$str_employee .= '<option value="'. $row['id_city'] .'">'. $row['city_name'] . '</option>';
	$str_employee .= '<option value="'. $row['id_employee'] .'">'. $row['lastname'] . ' '. $row['firstname'] . '</option>';
}

$array_result = array('results' => $str_employee);
echo json_encode($array_result);

?>