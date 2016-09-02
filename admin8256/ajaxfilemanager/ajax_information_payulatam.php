<?php
	require_once('../../config/config.inc.php');

	//se captura el id de orden
	$id_order = $_POST['id_order'];

	//consulta los datos de respuesta de payulatam
	$sqlPayuRespu = "SELECT pagos.id_cart, pagos.orderidpayu, pagos.transactionid, pagos.message,pagos.extras, pagos.method
					FROM ps_pagos_payu pagos
					INNER JOIN ps_orders orden ON(pagos.id_cart = orden.id_cart)
						WHERE pagos.orderIdPayu !=0 AND orden.id_order = ".(int)$id_order.";";
	$resultsPayuRespu = Db::getInstance()->ExecuteS($sqlPayuRespu);

	//se valida que exista respuesta de payulatam, si no, se retorna sin respuesta
	if (empty($resultsPayuRespu)){
		$resultsPayuRespu['id_cart'] = "Sin Respuesta";
		$resultsPayuRespu['orderidpayu'] = "Sin Respuesta";
		$resultsPayuRespu['transactionid'] = "Sin Respuesta";
		$resultsPayuRespu['message'] = "Sin Respuesta";
	} else {
		$resultsPayuRespu = $resultsPayuRespu[0];
	}

	//consulta los datos de confirmacion de payulatam
	$sqlPayuConfi = "SELECT repuesta.message, repuesta.date
					FROM ps_log_payu_response repuesta
					INNER JOIN ps_orders orden ON (repuesta.id_cart = orden.id_cart)
					WHERE orden.id_order =  ".(int) $id_order.";";
	$resultsPayuConfi = Db::getInstance()->ExecuteS($sqlPayuConfi);

	//se valida que exista confirmacion de payulatam, si no, se retorna sin confirmacion
	if (empty($resultsPayuConfi)){
		$resultsPayuConfi['date'] = "Sin Confirmaci&oacute;n";
		$resultsPayuConfi['message'] = "Sin Confirmaci&oacute;n";
	} else {
		$resultsPayuConfi = $resultsPayuConfi[0];
	}


	//genera html con la informacion retornada de las consultas
	$html = "<div id='dialog-modal' style='font-size: 13px;'>
			    <p>
		            <fieldset>
		            	<legend>Informaci&oacute;n Transacci&oacute;n PayuLatam</legend>
		            	<b>N&uacute;mero de Pedido: ".$id_order."</b>
		            	<br>
		            	<br>
		            	<fieldset>
		            		<legend>Respuesta PayuLatam</legend>
		            			<b>N&uacute;mero de Carrito:</b> ".$resultsPayuRespu['id_cart']."<br>
		            			<b>N&uacute;mero de Orden PayuLatam:</b> ".$resultsPayuRespu['orderidpayu']."<br>
		            			<b>Codigo de Transacci&oacute;n:</b> ".$resultsPayuRespu['transactionid']."<br>
		            			<b>Mensaje de Respuesta:</b> ".$resultsPayuRespu['message']."
		            	</fieldset><br>";
		            		$extras = explode(";", $resultsPayuRespu['extras']);
							if(count($extras) == 2){
								$html.="<legend>Datos de pago</legend>
								<b>Medio de pago:</b> ".$resultsPayuRespu['method']."<br>
								<b>N&uacute;mero de pago:</b> ".$extras[0]."<br>
								<b>Fecha de Expiraci&oacute;n:</b> ".$extras[1]."<br>
								<b>N&uacute;mero de convenio:</b> ";
								if($resultsPayuRespu['method'] == 'Baloto')
									$html.="950110";
								if($resultsPayuRespu['method'] == 'Efecty')
									$html.="110528";
							}
		            	$html.="<br><br>
		            	<fieldset>
		            		<legend>Confirmaci&oacute;n PayuLatam</legend>
		            			<b>Fecha de Confirmaci&oacute;n:</b> ".$resultsPayuConfi['date']."<br>
		            			<b>Mensaje de Confirmaci&oacute;n:</b> ".$resultsPayuConfi['message']."
		            	</fieldset>
		            </fieldset>
		        </p>
		    </div>";

	//retorna el html generado
	echo $html;
?>