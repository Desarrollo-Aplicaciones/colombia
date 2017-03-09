<?php
echo "entro";
require('/var/www/test.farmalisto.com.co/colombia/config/config.inc.php');

$sqlOrder = "SELECT * FROM "._DB_PREFIX_."orders WHERE current_state = 4 OR current_state = 22";
$results = Db::getInstance()->ExecuteS($sqlOrder);

$status = array(
		'EN RUTA' => 4,
		'ENTREGADO' => 5,
		'CERRADO CON NOVEDAD' => 19,
		'CANCELACION' => 19
	);

foreach($results as $key => $value) {
echo "consultando";
	$jsonResult = json_decode(file_get_contents("http://181.49.224.186/restfarmalisto/servicio_rest/MantieneReceptor/consulta_pedidos/".$value['id_order']), true);

	//echo $carrierOrder['id_entity'] . ' -> '. $value['current_state']. ' -> '. $status[$jsonResult['estado']] .'<br>';

	if($status[$jsonResult['estado']] == 5 || $status[$jsonResult['estado']] == 19 || $status[$jsonResult['estado']] == 4) {

		$carrierOrder = get_mensajero_order($value['id_order']);

		$sqlUpdateOrder = "UPDATE ps_orders SET current_state = '5'  WHERE id_order = '".$value['id_order']."'";
		$resultsUpdate = Db::getInstance()->ExecuteS($sqlUpdateOrder);

		$sqlInsertHistory = "INSERT INTO ps_order_history(id_employee, id_order, id_order_state, date_add)
			VALUES ('".$carrierOrder['id_employee']."','".$value['id_order']."','5','".date('Y-m-d H:i:s')."')";

		$resultsInsert = Db::getInstance()->ExecuteS($sqlInsertHistory);
	}
}
echo "término de consultar";
function get_mensajero_order($id_order) {
	$sql = "SELECT emp.id_employee, asoc.id_entity
				FROM ps_employee emp LEFT JOIN ps_associate_carrier asoc ON (emp.id_employee = asoc.id_entity)
				INNER JOIN ps_orders orden ON (asoc.id_order = orden.id_order)
				LEFT JOIN ps_carrier trans ON(asoc.id_entity = trans.id_carrier)
				WHERE orden.id_order = ".(int) $id_order.";";

	$result = Db::getInstance()->ExecuteS($sql);
	if(!empty($result) && $result[0]['id_employee'] != NULL){	
		return $result[0];
	}	
	return NULL;
}