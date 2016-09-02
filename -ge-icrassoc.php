<?php

require_once('classes/OrdenSuministroDetail.php');

// $pepe =  new OrdenSuministroDetail();
// $pepe -> ajaxProductosOrden(1,'7707232092041');

// echo "<pre>";
// print_r($_POST);

if (isset($_POST)) {
    

	if (isset($_POST['accion']) && $_POST['accion'] != '') {

	$accion = $_POST['accion'];

	$ordsum =  new OrdenSuministroDetail();

		switch ($accion) {
			case 'ajaxProductoOrden':

				if( isset($_POST['id_orden']) && isset($_POST['referencia']) && isset($_POST['opcion']) ) {

					if($_POST['opcion'] == 'save') {
						$ordsum -> ajaxProductoOrden($_POST['id_orden'], $_POST['referencia']);
					}

				if($_POST['opcion'] == 'update') {

						$ordsum -> ajaxProductoOrdenDel($_POST['id_orden'], $_POST['referencia']);
					}
				
				}
							
				break;

			case 'ajaxIcrAdd':

				if( isset($_POST['cod_icr']) && isset($_POST['opcion']) ) {

					if($_POST['opcion'] == 'save') {

						$ordsum -> ajaxIcrAdd($_POST['cod_icr']);
					}

					if($_POST['opcion'] == 'update' && isset($_POST['id_orden']) && isset($_POST['referencia']) ) {

						$ordsum -> ajaxIcrDel($_POST['cod_icr'], $_POST['id_orden'], $_POST['referencia']);
					}
					
				}
							
				break;
			
			default:
					echo "Opción inválida";
				break;
		}
	} elseif (isset($_POST['add_products_order']) && $_POST['add_products_order'] != '') {

		$arrprodsicrs = array();
		$arrprods = array();
		$arricrs = array();

		if (isset($_POST['id_supply_order']) && $_POST['id_supply_order'] != '' && isset($_POST['opcion'])) {

			$ordsum =  new OrdenSuministroDetail();

			foreach ($_POST as $key => $value) {

				if (substr($key,0,3) == 'pr_') {

					$arr_prod_icr = explode("_", $key);

					if ( !isset( $arrprods[ $arr_prod_icr[1] ] ) ) {

						$arrprods[ $arr_prod_icr[1]  ] = $arr_prod_icr[1] ;
					}

					foreach ($value as $id_icr => $value_icr) {

						$arrprodsicrs[ $arr_prod_icr[1] ][$arr_prod_icr[3]] = $value_icr;

						$arricrs[ $arr_prod_icr[3] ] = $arr_prod_icr[3];
						//echo "<br>val: ".$value_icr;						
					}

					
				}
			}

			$ordsum->supply_order = $_POST['id_supply_order'];
            $ordsum->setId_employee($_POST['id_emp']);
            $ordsum->setNomemployee($_POST['firstname']);
            $ordsum->setApeemployee($_POST['lastname']);
			$ordsum->setProductos($arrprods);
			$ordsum->setIcr($arricrs);
			$ordsum->setProductosIcr($arrprodsicrs);

			if($_POST['opcion'] == "save") {

				//echo "<br>cant_pro:".count($arrprods);
				//echo "<br>prods validos:".
				$prodsValidos = $ordsum -> validarProductosOrden("add");

				//echo "<pre><br>prods_icr:";
				//print_r($ordsum->productoicr);

				//echo "<br>icr validos:".
				$icrValidos = $ordsum -> validarIcrOrden();

				if( $prodsValidos == 1 && $icrValidos == 1 ) {
					$ordsum -> InsertarProductosIcrOrdenLoad();
					$ordsum -> InsertarProductosIcrOrden();
					$ordsum -> updateIcrProductoOrder();
					$ordsum -> updateIcrStatus();

					echo " <script language=\"javascript\"> parent.document.location = parent.document.location; </script>";
				} else {
					echo "<br><b>Error en la validación de los productos y/o los icr's a asociar, intente nuevamente.</b>";
				}
				
			} elseif ($_POST['opcion'] == "update") {

				//echo "<br>cant_pro:".count($arrprods);
				//echo "<br>prods validos:".
				$prodsValidos = $ordsum -> validarProductosOrden("del");

				//echo "<pre><br>prods_icr:";
				//print_r($ordsum->productoicr);

				//echo "<br>icr validos:".
				$icrValidos = $ordsum -> validarIcrOrden();	

				if( $prodsValidos == 1 && $icrValidos == 1 ) {
					$ordsum -> InsertarProductosIcrOrdenLoad();
					$ordsum -> updateIcrProductoOrder();
					$ordsum -> updateIcrStatus();
					$ordsum -> eliminarIcrProducto();

					echo " <script language=\"javascript\"> parent.document.location = parent.document.location; </script>";
				} else {
					echo "<br><b>Error en la validación de los productos y/o los icr's a desasociar, intente nuevamente.</b>";
				}
								
			}
			/*
			echo "<br>cant_pro:".count($arrprods);
			echo "<br>prods validos:".$prodsValidos = $ordsum -> validarProductosOrden("add");

			echo "<pre><br>prods_icr:";
			print_r($ordsum->productoicr);

			echo "<br>icr validos:".$icrValidos = $ordsum -> validarIcrOrden();

			if( $prodsValidos == 1 && $icrValidos == 1 ) {
				$ordsum -> InsertarProductosIcrOrdenLoad();
				$ordsum -> InsertarProductosIcrOrden();
				$ordsum -> updateIcrProductoOrder();
				$ordsum -> updateIcrStatus();
				//$ordsum -> eliminarIcrProducto();
			}
			//$prodsIcrValidos = $ordsum -> validarProductosIcrOrden();
			*/
		//
		}


	}
}





 