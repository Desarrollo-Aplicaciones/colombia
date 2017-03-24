<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminOrdersController extends AdminOrdersControllerCore
{
	protected $extra_vars_tpl = array();



	public function __construct()
	{
		$this->table = 'order';
		$this->className = 'Order';
		$this->lang = false;
		$this->addRowAction('view');
		$this->explicitSelect = true;
		$this->allow_export = true;
		$this->deleted = false;
		$this->context = Context::getContext();

		$this->_select = '
		a.id_currency,
		a.id_order AS `id_pdf`,
                oi.number AS `factura`,
                a.total_paid_tax_incl AS `a.total_paid_tax_incl`,
		CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
                CONCAT(osl.`name`, " ", a.complemento_estado) AS osname,
		os.`color`,
		ot.`extras` AS `extras`,
		ad.city as city_delivery,
		IF((SELECT COUNT(so.id_order) FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = a.id_customer) > 1, 0, 1) as `new`';

		$this->_join = '
		LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)
		LEFT JOIN `'._DB_PREFIX_.'order_invoice` oi ON (oi.`id_order` = a.`id_order`)
		LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`current_state`)
		LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')
		LEFT JOIN `'._DB_PREFIX_.'orders_transporte` ot ON (a.`id_order` = ot.`id_order`)
		LEFT JOIN `'._DB_PREFIX_.'address` ad ON (a.`id_address_delivery` = ad.`id_address`)';
		$this->_orderBy = 'id_order';
		$this->_orderWay = 'DESC';

		$statuses_array = array();
		$statuses = OrderState::getOrderStates((int)$this->context->language->id,(int)$this->context->employee->id_profile);

		foreach ($statuses as $status)
			$statuses_array[$status['id_order_state']] = $status['name'];

		$this->fields_list = array(
		'id_order' => array(
			'title' => $this->l('ID'),
			'align' => 'center',
			'width' => 25
		),
		'customer' => array(
			'title' => $this->l('Customer'),
			'havingFilter' => true,
		),
		'a.total_paid_tax_incl' => array(
			'title' => $this->l('Total'),
			'width' => 70,
			'align' => 'right',
			'prefix' => '<b>',
			'suffix' => '</b>',
			'type' => 'price',
			'currency' => true
		),
		'payment' => array(
			'title' => $this->l('Payment: '),
			'width' => 100
		),
		'osname' => array(
			'title' => $this->l('Status'),
			'color' => 'color',
			'width' => 200,
			'type' => 'select',
			'list' => $statuses_array,
			'filter_key' => 'os!id_order_state',
			'filter_type' => 'int',
			'order_key' => 'osname'
		),
		'city_delivery' => array(
			'title' => $this->l('Ciudad Destino '),
			'width' => 100,
			'prefix' => '<b>',
			'suffix' => '</b>',
			'align' => 'right',
			'filter_key' => 'ad!city'
		),
		'date_add' => array(
			'title' => $this->l('Date'),
			'width' => 130,
			'align' => 'right',
			'type' => 'datetime',
			'filter_key' => 'a!date_add'
		),
		'factura' => array(
			'title' => $this->l('#factura'),
			'align' => 'center',
			'width' => 25,
			'filter_key' => 'oi!number',
		),
		'id_pdf' => array(
			'title' => $this->l('PDF'),
			'width' => 35,
			'align' => 'center',
			'callback' => 'printPDFIcons',
			'orderby' => false,
			'search' => false,
			'remove_onclick' => true)
		);

		$this->shopLinkType = 'shop';
		$this->shopShareDatas = Shop::SHARE_ORDER;

		if (Tools::isSubmit('id_order'))
		{
			// Save context (in order to apply cart rule)
			$order = new Order((int)Tools::getValue('id_order'));
			if (!Validate::isLoadedObject($order))
				throw new PrestaShopException('Cannot load Order object');
			$this->context->cart = new Cart($order->id_cart);
			$this->context->customer = new Customer($order->id_customer);
		}

		AdminController::__construct();
	}
	
	public function printPDFIcons($id_order, $tr)
	{
		$order = new Order($id_order);
		$order_state = $order->getCurrentOrderState();
		if (!Validate::isLoadedObject($order_state) || !Validate::isLoadedObject($order))
		{
			return '';
		}

/*				$sqlPayu = "SELECT  COUNT(pp.id_cart) as total FROM "._DB_PREFIX_."pagos_payu pp INNER JOIN "._DB_PREFIX_."orders o ON(pp.id_cart = o.id_cart) WHERE o.id_order = " . (int) $id_order;
		$total = (int) Db::getInstance()->getValue($sqlPayu);
		if ($total > 0 )*/

		$sqlPayu = "SELECT id_cart FROM "._DB_PREFIX_."pagos_payu WHERE id_cart = " . $order->id_cart;
		$results = Db::getInstance()->ExecuteS($sqlPayu);
		if (empty($results[0]['id_cart'])){
			$validacionPagoPayu = "empty";
		} else {
			$validacionPagoPayu = "full";
		}

		$this->context->smarty->assign(array(
			'order' => $order,
			'order_state' => $order_state,
			'tr' => $tr,
            'order_payu' => $validacionPagoPayu,
            'conditions_order' => $this->statusOrderOfList($order->id)
		));

		return $this->createTemplate('_print_pdf_icon.tpl')->fetch();
	}

	/**
     * [removeProductsOrder description]
     * @param  [type] $id_order [description]
     * @return [type]           [description]
     */
    public function removeProductsOrder($id_order) {

    	$loggin = new Registrolog();
        $loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Ingreso Eliminar ICR de la Orden: ".$id_order." - empleado:".$this->context->employee->id);

        if ($this->addProductStock($id_order)) {
        	
        	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Si se actualizo stock ");

            $query = "SELECT  picking.id_order_picking, id_icr 
			FROM ps_icr icr 
			INNER JOIN ps_order_picking picking ON(icr.id_icr=picking.id_order_icr) 
			WHERE  icr.cod_icr IN ( SELECT icr.cod_icr
				FROM ps_orders orders 
				INNER JOIN ps_order_detail orders_d ON ( orders.id_order= orders_d.id_order)
				INNER JOIN ps_supply_order_detail s_order_d ON (orders_d.product_id=s_order_d.id_product)
				INNER JOIN ps_supply_order_icr s_order_i ON (s_order_d.id_supply_order_detail=s_order_i.id_supply_order_detail)
				INNER JOIN ps_icr icr ON (s_order_i.id_icr=icr.id_icr)
				INNER JOIN ps_order_picking o_picking ON (orders_d.id_order_detail= o_picking.id_order_detail AND s_order_i.id_supply_order_icr =o_picking.id_order_supply_icr)
				INNER JOIN ps_product product ON (s_order_d.id_product=product.id_product)
				WHERE icr.id_estado_icr=3 and orders.id_order=".$id_order." 
				GROUP BY icr.cod_icr )";

            $array_icr = null;
            $array_order_picking = null;

            if ($results = Db::getInstance()->ExecuteS($query)) {

                foreach ($results as $row) {
                    $array_order_picking[] = $row['id_order_picking'];
                    $array_icr[] = $row['id_icr'];
                }

                if ($array_icr != NULL && $array_order_picking != NULL) {

                    $query_2 = "DELETE from ps_order_picking WHERE id_order_picking IN ('" . implode("','", $array_order_picking) . "')";

                        $loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Query delete: ".$query_2);

                    $query_3 = "UPDATE ps_icr SET id_estado_icr=2 WHERE id_icr IN ('" . implode("','", $array_icr) . "')";

                        $loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Query Update: ".$query_3);
                    
                    if ($results = Db::getInstance()->Execute($query_2) && $results3 = Db::getInstance()->Execute($query_3)) {

                    	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "ICR actualizados ");
                        return true;

                    } else {
                    	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Querys no ejecutados correctamente.");
                    }

                } else {
                	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Alguno de los array estan vacios");
                }

            } else {
            	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "No se ejecuto correctamente el query Select");	
            }

        } else {
        	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "No se actualizo stock ");


            $query = "SELECT  picking.id_order_picking, id_icr 
			FROM ps_icr icr 
			INNER JOIN ps_order_picking picking ON(icr.id_icr=picking.id_order_icr) 
			WHERE  icr.cod_icr IN ( SELECT icr.cod_icr
				FROM ps_orders orders 
				INNER JOIN ps_order_detail orders_d ON ( orders.id_order= orders_d.id_order)
				INNER JOIN ps_supply_order_detail s_order_d ON (orders_d.product_id=s_order_d.id_product)
				INNER JOIN ps_supply_order_icr s_order_i ON (s_order_d.id_supply_order_detail=s_order_i.id_supply_order_detail)
				INNER JOIN ps_icr icr ON (s_order_i.id_icr=icr.id_icr)
				INNER JOIN ps_order_picking o_picking ON (orders_d.id_order_detail= o_picking.id_order_detail AND s_order_i.id_supply_order_icr =o_picking.id_order_supply_icr)
				INNER JOIN ps_product product ON (s_order_d.id_product=product.id_product)
				WHERE icr.id_estado_icr=3 and orders.id_order=".$id_order." 
				GROUP BY icr.cod_icr )";

            $array_icr = null;
            $array_order_picking = null;

            if ($results = Db::getInstance()->ExecuteS($query)) {

                foreach ($results as $row) {
                    $array_order_picking[] = $row['id_order_picking'];
                    $array_icr[] = $row['id_icr'];
                }

                if ($array_icr != NULL && $array_order_picking != NULL) {

                    $query_2 = "DELETE from ps_order_picking WHERE id_order_picking IN ('" . implode("','", $array_order_picking) . "')";

                        $loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Query delete: ".$query_2);

                    $query_3 = "UPDATE ps_icr SET id_estado_icr=2 WHERE id_icr IN ('" . implode("','", $array_icr) . "')";

                        $loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Query Update: ".$query_3);
                    
                    if ($results = Db::getInstance()->Execute($query_2) && $results3 = Db::getInstance()->Execute($query_3)) {

                    	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "ICR actualizados ");
                        return true;

                    } else {
                    	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Querys no ejecutados correctamente.");
                    }

                } else {
                	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "Alguno de los array estan vacios");
                }

            } else {
            	$loggin->lwrite("AdminOrdersController", "remover_productos.txt", "No se ejecuto correctamente el query Select");	
            }

        }
        return false;
    }

    public function initToolbar()
	{  //exit('<pre>'.print_r($this->context->cookie->{'id_employee'},true));
		// procesamiento de peticiones ajax 
		$this->processAjax();
		if ($this->display == 'view')
		{
			$order = new Order((int)Tools::getValue('id_order'));
			if ($order->hasBeenShipped())
				$type = $this->l('Return products');
			elseif ($order->hasBeenPaid())
				$type = $this->l('Standard refund');
			else
				$type = $this->l('Cancel products');

			if (Configuration::get('PS_ORDER_EDIT') == '1') {
							
				if (!$order->hasBeenShipped() && !$this->lite_display) {
					$this->toolbar_btn['new'] = array(
						'short' => 'Create',
						'href' => '#',
						'desc' => $this->l('Add a product'),
						'class' => 'add_product'
					);
				}

				if (Configuration::get('PS_ORDER_RETURN') && !$this->lite_display) {
					$this->toolbar_btn['standard_refund'] = array(
						'short' => 'Create',
						'href' => '',
						'desc' => $type,
						'class' => 'process-icon-standardRefund'
					);
				}
				
				if ($order->hasInvoice() && !$this->lite_display) {
					$this->toolbar_btn['partial_refund'] = array(
						'short' => 'Create',
						'href' => '',
						'desc' => $this->l('Partial refund'),
						'class' => 'process-icon-partialRefund'
					);
				}			
			}
		}

		$res = AdminController::initToolbar();
		if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP && isset($this->toolbar_btn['new']) && Shop::isFeatureActive())
			unset($this->toolbar_btn['new']);
		return $res;
	}
	public function processAjax()
	{ 



		// /admin8256/index.php?controller=AdminOrders&id_order=16330&vieworder&token=3356a854ebecbf64da66b8ec2cc38c3f&ajax&option_jax=transportador_fm
		// /admin8256/index.php?controller=AdminOrders&id_order=16330&vieworder&token=3356a854ebecbf64da66b8ec2cc38c3f&ajax&option_jax=transportadora
		               if(Tools::getIsset('controller') && Tools::getValue('controller') =='AdminOrders' 
		                  && Tools::getIsset('vieworder') && Tools::getIsset('id_order')
		                  && Tools::getIsset('ajax') && Tools::getIsset('option_jax')
		                  && Tools::getIsset('token') )
                {   
                	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
					header("Cache-Control: post-check=0, pre-check=0", false);
					header("Pragma: no-cache");

                	$option_ajax = Tools::getValue('option_jax'); 
                	// retorna lista de empleados transportadores                          
                 	if($option_ajax === 'transportador_fm'){
                 		$str_transportador='<div class="celdadiv"></div> <div class="celdadiv"><select id="transportador" opcion="transportador">';
                 	 	foreach (Employee::getEmployeesByProfileList('Mensajeros') as $key ) {
                 				$str_transportador.='<option value="'.$key['id_employee'].'">'.$key['firstname'].'&nbsp'.$key['lastname'].'</option>';
                 	 			}
                 		$str_transportador.='</select></div>'; 

                 		exit(json_encode(array('results' => $str_transportador))); 

                	} elseif($option_ajax ==='transportadora'){ // retorna lista de empresas transportadoras 
                 			$str_transportadora='<div class="celdadiv"></div><div class="celdadiv"> <select id="transportador" opcion="transportadora">';
                 			foreach (Carrier::getCarrierList() as $key) {
                 					$str_transportadora.='<option value="'.$key['id_reference'].'">'.$key['name'].'</option>';
                 			}
                 			$str_transportadora.='</select></div>';
                   		exit(json_encode(array('results' => $str_transportadora)));
                 	}elseif($option_ajax ==='save_order_carrier'){
                 		//exit(json_encode(array('results' => "sucesfull")));
                 		if($this->opcionTransportista($_GET)){
                 			$success = array('results' => "sucesfull");
                 			$errorSmart = null;
                 			$order = new Order(Tools::getValue('id_order'));
							if($order->current_state == 22) {
								$fechaHora = $order->delivery_date;
							
								$hora = strtotime($fechaHora);
								$hora = date("Hi", $hora);
								
								$fecha = strtotime($fechaHora);
								$fecha = date("Y-m-d", $fecha);
								
								$customer = new Customer($order->id_customer);
								$address = new Address($order->id_address_delivery);

								$getOrderDelivery = $this->get_mensajero_order($order->id);
								$ccDelivery = explode("@",$getOrderDelivery['email']);
								
								$server='www.smartquick.com.co'; 
								//$server='181.49.224.186';
								$pedido=urlencode($order->id);
								$fecha_entrega=urlencode($fecha); // Puede ser enviado con o sin guiones;
								$hora_entrega=urlencode($hora); // Debe ser en hora militar sin (:)
								$ciudad=urlencode($address->city);
								$direccion=urlencode($address->address1);
								$doc_cliente=urlencode($customer->identification);
								$nom_cliente=urlencode($customer->firstname.' '.$customer->lastname);
								$telefono=urlencode($address->phone_mobile);
								$observacion=urlencode($order->private_message);
								$doc_mensajero=urlencode($ccDelivery[0]);
								$url_insercion='http://'.$server.'/restfarmalisto/servicio_rest/MantieneReceptor/insertar_visita/'.$pedido.'/'.$fecha_entrega.'/'.$hora_entrega.'/'.$ciudad.'/'.$direccion.'/'.$doc_cliente.'/'.$nom_cliente.'/'.$telefono.'/'.$observacion.'/'.$doc_mensajero;

								$result = json_decode(file_get_contents($url_insercion));
								
								if($result->status == 'ERROR') {
									$errorSmart = "error";
								} else {
									$errorSmart = "sucesfull";
								}
								$success['smart'] = $errorSmart;
							}
                 			exit(json_encode($success));
                 		}else{
                 			exit(json_encode(array('results' => "error")));
                 		}
                 	}elseif($option_ajax ==='order_icrs'){
                 	$str_list_icrs = '<table border="1">
										<tr>
											<th>Referencia</th>
											<th>Nombre</th>
											<th>ICR</th>
										</tr>';	
                 	foreach ($this->getListIcrs($_GET) as $key => $value) {
                 			$str_list_icrs .= '
                 								<tr>
													<th>'.$value['reference'].'</td>
													<td>'.$value['name'].'</td>
													<td>'.$value['cod_icr'].'</td>
												</tr>';
                 		}
                 	$str_list_icrs .= '	</table>';
                 	exit(json_encode(array('results' =>$str_list_icrs)));

                 	}elseif($option_ajax ==='opciones_cancelacion'){
                 		$opciones_cancelacion = '<select name="opcion_cancelacion" id="opcion_cancelacion">
                 								<option value="">-Seleccionar-</option>';	
                 		foreach ($this->opciones_cancelacion() as $key => $value) {
                 			$opciones_cancelacion .= '<option value="'.$value['id_motivo_cancelacion'].'">'.$value['nombre'].'</option>';
                 		}
                 	$opciones_cancelacion .= '</select>';
                 	exit(json_encode(array('results' =>$opciones_cancelacion)));

                 	}
                 	elseif($option_ajax ==='aplicar_cancelacion'){
                 		$opcion_cancelacion = '';

                 		if($this->save_opcion_cancelacion($_GET)){
                 			$opcion_cancelacion = 'sucesfull';

							/* START mail quality score */
								if ( $_GET['opcion_cancelacion'] != 4 ) {
									$order = new Order($this->id_object);
									$template_vars['{firstname}'] = $this->context->customer->firstname;
									$template_vars['{lastname}'] = $this->context->customer->lastname;
									$template_vars['{order_name}'] = $order->reference;
									$template_vars['id_customer'] = $this->context->customer->id;
									$template_vars['id_order'] = $this->id_object;
									Mail::Send(
										1,
										'canceled',
										'Tu pedido ha sido cancelado. Por favor califica nuestro servicio',
										$template_vars,
										$this->context->customer->email,
										( $this->context->customer->firstname." ".$this->context->customer->lastname ),
										Configuration::get('PS_SHOP_EMAIL'),
										Configuration::get('PS_SHOP_NAME')
									);
								}
							/* END mail quality score */
                 		} else{
                 			$opcion_cancelacion = 'error';
                 		}
                 		exit(json_encode(array('results' =>$opcion_cancelacion)));	
                 	}
       
				}
				
	}

protected function getListIcrs($array){
	$sql = 'SELECT  	s_order_d.reference,s_order_d.`name`,icr.cod_icr
					FROM ps_orders orders 
					INNER JOIN ps_order_detail orders_d ON( orders.id_order= orders_d.id_order)
					INNER JOIN ps_supply_order_detail s_order_d ON(orders_d.product_id=s_order_d.id_product)
					INNER JOIN ps_supply_order_icr s_order_i ON (s_order_d.id_supply_order_detail=s_order_i.id_supply_order_detail)
					INNER JOIN ps_icr icr ON (s_order_i.id_icr=icr.id_icr)
					INNER JOIN ps_order_picking o_picking ON (orders_d.id_order_detail= o_picking.id_order_detail AND s_order_i.id_supply_order_icr =o_picking.id_order_supply_icr)
					WHERE icr.id_estado_icr=3 and orders.id_order ='.(int) $array['id_order'].';';
	$results = Db::getInstance()->ExecuteS($sql); 
	if (count($results) > 0) {
		return $results;
        }
    return false;    
}

protected function opciones_cancelacion(){
	$sql = 'SELECT id_motivo_cancelacion,nombre FROM
			ps_motivo_cancelacion
			LIMIT 100;';
	$results = Db::getInstance()->ExecuteS($sql);

	if (count($results) > 0) {
		return $results;
        }
    return false;    
}

protected function save_opcion_cancelacion($array){

	if(isset($array['opcion_cancelacion']) &&  isset( $this->context->employee->id) && isset($array['id_order']) && (int)$array['opcion_cancelacion']> 0 && (int)$this->context->employee->id >0){

		$sql="INSERT INTO `"._DB_PREFIX_."order_motivo_cancelacion` (`id_motivo_cancelacion`, `id_order`, `id_employee`, `comentario`, `motivo_personalizado`, `fecha`) 
				VALUES ('".(int)$array['opcion_cancelacion']."', '".(int)$array['id_order']."', '".(int)$this->context->employee->id."', '".htmlspecialchars($array['descripcion_cancelacion'])."', '".htmlspecialchars($array['otra_opcion'])."', '".date("Y-m-d H:i:s")."');";
		 if( Db::getInstance()->Execute($sql))
		 	return TRUE;
		}
		return FALSE;
}	

    public function logtxt ($text="")
{
            //$contenido="-- lo que quieras escribir en el archivo -- \r\n";
$fp=fopen("/home/ubuntu/log_payu/log_order_cambio.txt","a+");
fwrite($fp,$text."\r\n");
fclose($fp) ;
            
        }

public function postProcess()
	{
		if (Tools::isSubmit('submitReport')){
			$this->generarReporteDetallado();
		}
		// If id_order is sent, we instanciate a new Order object
		if (Tools::isSubmit('id_order') && Tools::getValue('id_order') > 0)
		{
			$order = new Order(Tools::getValue('id_order'));
             //$this->logtxt ('linea 373 '.print_r($order,true));
                        
			if (!Validate::isLoadedObject($order))
				throw new PrestaShopException('Can\'t load Order object');
			ShopUrl::cacheMainDomainForShop((int)$order->id_shop);
		}

		/* Update shipping number */
		if (Tools::isSubmit('submitShippingNumber') && isset($order))
		{
			if ($this->tabAccess['edit'] === '1')
			{
				$order_carrier = new OrderCarrier(Tools::getValue('id_order_carrier'));
				if (!Validate::isLoadedObject($order_carrier))
					$this->errors[] = Tools::displayError('The order carrier ID is invalid.');
				elseif (!Validate::isTrackingNumber(Tools::getValue('tracking_number')))
					$this->errors[] = Tools::displayError('The tracking number is incorrect.');
				else
				{
					// update shipping number
					// Keep these two following lines for backward compatibility, remove on 1.6 version
					$order->shipping_number = Tools::getValue('tracking_number');
					$order->update();

					// Update order_carrier
					$order_carrier->tracking_number = pSQL(Tools::getValue('tracking_number'));
					if ($order_carrier->update())
					{
						// Send mail to customer
						$customer = new Customer((int)$order->id_customer);
						$carrier = new Carrier((int)$order->id_carrier, $order->id_lang);
						if (!Validate::isLoadedObject($customer))
							throw new PrestaShopException('Can\'t load Customer object');
						if (!Validate::isLoadedObject($carrier))
							throw new PrestaShopException('Can\'t load Carrier object');
						$templateVars = array(
							'{followup}' => str_replace('@', $order->shipping_number, $carrier->url),
							'{firstname}' => $customer->firstname,
							'{lastname}' => $customer->lastname,
							'{id_order}' => $order->id,
							'{shipping_number}' => $order->shipping_number,
							'{order_name}' => $order->getUniqReference()
						);
						if (@Mail::Send((int)$order->id_lang, 'in_transit', Mail::l('Package in transit'), $templateVars,
							$customer->email, $customer->firstname.' '.$customer->lastname, null, null, null, null,
							_PS_MAIL_DIR_, true, (int)$order->id_shop))
						{
							Hook::exec('actionAdminOrdersTrackingNumberUpdate', array('order' => $order, 'customer' => $customer, 'carrier' => $carrier));
							Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=4&token='.$this->token);
						}
						else
							$this->errors[] = Tools::displayError('An error occurred while sending an email to the customer.');
					}
					else
						$this->errors[] = Tools::displayError('The order carrier cannot be updated.');
				}
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}

		/* Change order state, add a new entry in order history and send an e-mail to the customer if needed */
		elseif (Tools::isSubmit('submitState') && isset($order))
		{
//                        error_log("Este es el Order: ".print_r($order,true),3,"/tmp/states.log");
			if ($this->tabAccess['edit'] === '1')
			{
				$order_state = new OrderState(Tools::getValue('id_order_state'));

				if (!Validate::isLoadedObject($order_state))
                                { 
					$this->errors[] = Tools::displayError('The new order status is invalid.');
                                }
				else  
				{    
                                  // si el estado de la orden es cancelado  
                                 if($this->getNameStatusOrder($order_state->id)=="PS_OS_CANCELED")
                                { 
                                   $this->removeProductsOrder($order->id);  //print_r($order);
                                } 
					$current_order_state = $order->getCurrentOrderState();
					if ($current_order_state->id != $order_state->id)
					{
						// Create new OrderHistory
						$history = new OrderHistory();
						$history->id_order = $order->id;
						$history->id_employee = (int)$this->context->employee->id;
                        
						$errorSmart = null;
						$getOrderDelivery = $this->get_mensajero_order($order->id);
						$ccDelivery = explode("@",$getOrderDelivery['email']);
						if($order_state->id == 22 && count($ccDelivery) > 1) {
							$fechaHora = $order->delivery_date;
						
							$hora = strtotime($fechaHora);
							$hora = date("Hi", $hora);
							
							$fecha = strtotime($fechaHora);
							$fecha = date("Y-m-d", $fecha);
							
							$customer = new Customer($order->id_customer);
							$address = new Address($order->id_address_delivery);
							
							$server='www.smartquick.com.co'; 
							//$server='181.49.224.186';
							$pedido=urlencode($order->id);
							$fecha_entrega=urlencode($fecha); // Puede ser enviado con o sin guiones;
							$hora_entrega=urlencode($hora); // Debe ser en hora militar sin (:)
							$ciudad=urlencode($address->city);
							$direccion=urlencode($address->address1);
							$doc_cliente=urlencode($customer->identification);
							$nom_cliente=urlencode($customer->firstname.' '.$customer->lastname);
							$telefono=urlencode($address->phone_mobile);
							$observacion=urlencode($order->private_message);
							$doc_mensajero=urlencode($ccDelivery[0]);
							$url_insercion='http://'.$server.'/restfarmalisto/servicio_rest/MantieneReceptor/insertar_visita/'.$pedido.'/'.$fecha_entrega.'/'.$hora_entrega.'/'.$ciudad.'/'.$direccion.'/'.$doc_cliente.'/'.$nom_cliente.'/'.$telefono.'/'.$observacion.'/'.$doc_mensajero;

							$result = json_decode(file_get_contents($url_insercion));
							
							if($result->status == 'ERROR') {
								$errorSmart = "&smart=false";
							} else {
								$errorSmart = "&smart=true";
							}
						}

						$use_existings_payment = false;
						if (!$order->hasInvoice())
							$use_existings_payment = true;     $this->logtxt ('linea 462 '.print_r($order,true));
						$history->changeIdOrderState((int)$order_state->id, $order, $use_existings_payment);
                                                
                                                $products = $this->getProducts($order);
                                                foreach ($products as &$product){
                                                    $product['current_stock'] = StockAvailable::getQuantityAvailableByProduct($product['product_id'], $product['product_attribute_id'], $product['id_shop']);
                                                    if (Configuration::get('PS_STOCK_MANAGEMENT') && $product['current_stock'] < $product['product_quantity'] && $current_order_state->id == 3){
//                                                        error_log("\n\n el producto: ".$product['product_id']." - ".$product['product_name']."No esta en stock y faltan: ".$product['out_of_stock'],3,"/tmp/states.log");
                                                        $history = new OrderHistory();
                                                        $history->id_order = (int) $order->id;
                                                        $history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
                                                        $history->addWithemail();
                                                    }
                                                }

						$carrier = new Carrier($order->id_carrier, $order->id_lang);
						$templateVars = array();
						if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $order->shipping_number)
							$templateVars = array('{followup}' => str_replace('@', $order->shipping_number, $carrier->url));
						// Save all changes
						if ($history->addWithemail(true, $templateVars))
						{
							// synchronizes quantities if needed..
							if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
							{
								foreach ($order->getProducts() as $product)
								{
									if (StockAvailable::dependsOnStock($product['product_id']))
										StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
								}
							}

							Tools::redirectAdmin(self::$currentIndex.'&id_order='.(int)$order->id.'&vieworder&token='.$this->token.$errorSmart);
						}
						$this->errors[] = Tools::displayError('An error occurred while changing order status, or we were unable to send an email to the customer.');
					}
					else
						$this->errors[] = Tools::displayError('The order has already been assigned this status.');
				}
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}

		/* Add a new message for the current order and send an e-mail to the customer if needed */
		elseif (Tools::isSubmit('submitMessage') && isset($order))
		{
			if ($this->tabAccess['edit'] === '1')
			{
				$customer = new Customer(Tools::getValue('id_customer'));
				if (!Validate::isLoadedObject($customer))
					$this->errors[] = Tools::displayError('The customer is invalid.');
				elseif (!Tools::getValue('message'))
					$this->errors[] = Tools::displayError('The message cannot be blank.');
				else
				{
					/* Get message rules and and check fields validity */
					$rules = call_user_func(array('Message', 'getValidationRules'), 'Message');
					foreach ($rules['required'] as $field)
						if (($value = Tools::getValue($field)) == false && (string)$value != '0')
							if (!Tools::getValue('id_'.$this->table) || $field != 'passwd')
								$this->errors[] = sprintf(Tools::displayError('field %s is required.'), $field);
					foreach ($rules['size'] as $field => $maxLength)
						if (Tools::getValue($field) && Tools::strlen(Tools::getValue($field)) > $maxLength)
							$this->errors[] = sprintf(Tools::displayError('field %1$s is too long (%2$d chars max).'), $field, $maxLength);
					foreach ($rules['validate'] as $field => $function)
						if (Tools::getValue($field))
							if (!Validate::$function(htmlentities(Tools::getValue($field), ENT_COMPAT, 'UTF-8')))
								$this->errors[] = sprintf(Tools::displayError('field %s is invalid.'), $field);

					if (!count($this->errors))
					{
						//check if a thread already exist
						$id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($customer->email, $order->id);
						if (!$id_customer_thread)
						{
							$customer_thread = new CustomerThread();
							$customer_thread->id_contact = 0;
							$customer_thread->id_customer = (int)$order->id_customer;
							$customer_thread->id_shop = (int)$this->context->shop->id;
							$customer_thread->id_order = (int)$order->id;
							$customer_thread->id_lang = (int)$this->context->language->id;
							$customer_thread->email = $customer->email;
							$customer_thread->status = 'open';
							$customer_thread->token = Tools::passwdGen(12);
							$customer_thread->add();
						}
						else
							$customer_thread = new CustomerThread((int)$id_customer_thread);

						$customer_message = new CustomerMessage();
						$customer_message->id_customer_thread = $customer_thread->id;
						$customer_message->id_employee = (int)$this->context->employee->id;
						$customer_message->message = htmlentities(Tools::getValue('message'), ENT_COMPAT, 'UTF-8');
						$customer_message->private = Tools::getValue('visibility');

						if (!$customer_message->add())
							$this->errors[] = Tools::displayError('An error occurred while saving the message.');
						elseif ($customer_message->private)
							Tools::redirectAdmin(self::$currentIndex.'&id_order='.(int)$order->id.'&vieworder&conf=11&token='.$this->token);
						else
						{
							$message = $customer_message->message;
							if (Configuration::get('PS_MAIL_TYPE', null, null, $order->id_shop) != Mail::TYPE_TEXT)
								$message = Tools::nl2br($customer_message->message);

							$varsTpl = array(
								'{lastname}' => $customer->lastname,
								'{firstname}' => $customer->firstname,
								'{id_order}' => $order->id,
								'{order_name}' => $order->getUniqReference(),
								'{message}' => $message
							);
							if (@Mail::Send((int)$order->id_lang, 'order_merchant_comment',
								Mail::l('New message regarding your order', (int)$order->id_lang), $varsTpl, $customer->email,
								$customer->firstname.' '.$customer->lastname, null, null, null, null, _PS_MAIL_DIR_, true, (int)$order->id_shop))
								Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=11'.'&token='.$this->token);
						}
						$this->errors[] = Tools::displayError('An error occurred while sending an email to the customer.');
					}
				}
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}

		/* Partial refund from order */
		elseif (Tools::isSubmit('partialRefund') && isset($order))
		{
			if ($this->tabAccess['edit'] == '1')
			{
				if (is_array($_POST['partialRefundProduct']))
				{
					$amount = 0;
					$order_detail_list = array();
					foreach ($_POST['partialRefundProduct'] as $id_order_detail => $amount_detail)
					{
						$order_detail_list[$id_order_detail]['quantity'] = (int)$_POST['partialRefundProductQuantity'][$id_order_detail];

						if (empty($amount_detail))
						{
							$order_detail = new OrderDetail((int)$id_order_detail);
							$order_detail_list[$id_order_detail]['amount'] = $order_detail->unit_price_tax_incl * $order_detail_list[$id_order_detail]['quantity'];
						}
						else
							$order_detail_list[$id_order_detail]['amount'] = (float)str_replace(',', '.', $amount_detail);
						$amount += $order_detail_list[$id_order_detail]['amount'];

						$order_detail = new OrderDetail((int)$id_order_detail);
						if (!$order->hasBeenDelivered() || ($order->hasBeenDelivered() && Tools::isSubmit('reinjectQuantities')) && $order_detail_list[$id_order_detail]['quantity'] > 0)
							$this->reinjectQuantity($order_detail, $order_detail_list[$id_order_detail]['quantity']);
					}

					$shipping_cost_amount = (float)str_replace(',', '.', Tools::getValue('partialRefundShippingCost'));
					if ($shipping_cost_amount > 0)
						$amount += $shipping_cost_amount;

					$order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
					if (Validate::isLoadedObject($order_carrier))
					{
						$order_carrier->weight = (float)$order->getTotalWeight();
						if ($order_carrier->update())
							$order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);							
					}																		

					if ($amount > 0)
					{
						if (!OrderSlip::createPartialOrderSlip($order, $amount, $shipping_cost_amount, $order_detail_list))
							$this->errors[] = Tools::displayError('You cannot generate a partial credit slip.');

						// Generate voucher
						if (Tools::isSubmit('generateDiscountRefund') && !count($this->errors))
						{
							$cart_rule = new CartRule();
							$cart_rule->description = sprintf($this->l('Credit slip for order #%d'), $order->id);
							$languages = Language::getLanguages(false);
							foreach ($languages as $language)
								// Define a temporary name
								$cart_rule->name[$language['id_lang']] = sprintf('V0C%1$dO%2$d', $order->id_customer, $order->id);

							// Define a temporary code
							$cart_rule->code = sprintf('V0C%1$dO%2$d', $order->id_customer, $order->id);
							$cart_rule->quantity = 1;
							$cart_rule->quantity_per_user = 1;

							// Specific to the customer
							$cart_rule->id_customer = $order->id_customer;
							$now = time();
							$cart_rule->date_from = date('Y-m-d H:i:s', $now);
							$cart_rule->date_to = date('Y-m-d H:i:s', $now + (3600 * 24 * 365.25)); /* 1 year */
							$cart_rule->partial_use = 1;
							$cart_rule->active = 1;

							$cart_rule->reduction_amount = $amount;
							$cart_rule->reduction_tax = true;
							$cart_rule->minimum_amount_currency = $order->id_currency;
							$cart_rule->reduction_currency = $order->id_currency;

							if (!$cart_rule->add())
								$this->errors[] = Tools::displayError('You cannot generate a voucher.');
							else
							{
								// Update the voucher code and name
								foreach ($languages as $language)
									$cart_rule->name[$language['id_lang']] = sprintf('V%1$dC%2$dO%3$d', $cart_rule->id, $order->id_customer, $order->id);
								$cart_rule->code = sprintf('V%1$dC%2$dO%3$d', $cart_rule->id, $order->id_customer, $order->id);

								if (!$cart_rule->update())
									$this->errors[] = Tools::displayError('You cannot generate a voucher.');
								else
								{
									$currency = $this->context->currency;
									$customer = new Customer((int)($order->id_customer));
									$params['{lastname}'] = $customer->lastname;
									$params['{firstname}'] = $customer->firstname;
									$params['{id_order}'] = $order->id;
									$params['{order_name}'] = $order->getUniqReference();
									$params['{voucher_amount}'] = Tools::displayPrice($cart_rule->reduction_amount, $currency, false);
									$params['{voucher_num}'] = $cart_rule->code;
									$customer = new Customer((int)$order->id_customer);
									@Mail::Send((int)$order->id_lang, 'voucher', sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
										$params, $customer->email, $customer->firstname.' '.$customer->lastname, null, null, null,
										null, _PS_MAIL_DIR_, true, (int)$order->id_shop);
								}
							}
						}
					}
					else
						$this->errors[] = Tools::displayError('You have to enter an amount if you want to create a partial credit slip.');

					// Redirect if no errors
					if (!count($this->errors))
						Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=30&token='.$this->token);
				}
				else
					$this->errors[] = Tools::displayError('The partial refund data is incorrect.');
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}

		/* Cancel product from order */
		elseif (Tools::isSubmit('cancelProduct') && isset($order))
		{
		 	if ($this->tabAccess['delete'] === '1')
			{
				if (!Tools::isSubmit('id_order_detail') && !Tools::isSubmit('id_customization'))
					$this->errors[] = Tools::displayError('You must select a product.');
				elseif (!Tools::isSubmit('cancelQuantity') && !Tools::isSubmit('cancelCustomizationQuantity'))
					$this->errors[] = Tools::displayError('You must enter a quantity.');
				else
				{
					$productList = Tools::getValue('id_order_detail');
					if ($productList)
						$productList = array_map('intval', $productList);
					
					$customizationList = Tools::getValue('id_customization');
					if ($customizationList)
						$customizationList = array_map('intval', $customizationList);
						
					$qtyList = Tools::getValue('cancelQuantity');
					if ($qtyList)
						$qtyList = array_map('intval', $qtyList);
						
					$customizationQtyList = Tools::getValue('cancelCustomizationQuantity');
					if ($customizationQtyList)
						$customizationQtyList = array_map('intval', $customizationQtyList);

					$full_product_list = $productList;
					$full_quantity_list = $qtyList;

					if ($customizationList)
						foreach ($customizationList as $key => $id_order_detail)
						{
							$full_product_list[(int)$id_order_detail] = $id_order_detail;
							if (isset($customizationQtyList[$key]))
								$full_quantity_list[(int)$id_order_detail] += $customizationQtyList[$key];
						}

					if ($productList || $customizationList)
					{
						if ($productList)
						{
							$id_cart = Cart::getCartIdByOrderId($order->id);
							$customization_quantities = Customization::countQuantityByCart($id_cart);

							foreach ($productList as $key => $id_order_detail)
							{
								$qtyCancelProduct = abs($qtyList[$key]);
								if (!$qtyCancelProduct)
									$this->errors[] = Tools::displayError('No quantity has been selected for this product.');

								$order_detail = new OrderDetail($id_order_detail);
								$customization_quantity = 0;
								if (array_key_exists($order_detail->product_id, $customization_quantities) && array_key_exists($order_detail->product_attribute_id, $customization_quantities[$order_detail->product_id]))
									$customization_quantity = (int)$customization_quantities[$order_detail->product_id][$order_detail->product_attribute_id];

								if (($order_detail->product_quantity - $customization_quantity - $order_detail->product_quantity_refunded - $order_detail->product_quantity_return) < $qtyCancelProduct)
									$this->errors[] = Tools::displayError('An invalid quantity was selected for this product.');

							}
						}
						if ($customizationList)
						{
							$customization_quantities = Customization::retrieveQuantitiesFromIds(array_keys($customizationList));

							foreach ($customizationList as $id_customization => $id_order_detail)
							{
								$qtyCancelProduct = abs($customizationQtyList[$id_customization]);
								$customization_quantity = $customization_quantities[$id_customization];

								if (!$qtyCancelProduct)
									$this->errors[] = Tools::displayError('No quantity has been selected for this product.');

								if ($qtyCancelProduct > ($customization_quantity['quantity'] - ($customization_quantity['quantity_refunded'] + $customization_quantity['quantity_returned'])))
									$this->errors[] = Tools::displayError('An invalid quantity was selected for this product.');
							}
						}

						if (!count($this->errors) && $productList)
							foreach ($productList as $key => $id_order_detail)
							{
								$qty_cancel_product = abs($qtyList[$key]);
								$order_detail = new OrderDetail((int)($id_order_detail));

								if (!$order->hasBeenDelivered() || ($order->hasBeenDelivered() && Tools::isSubmit('reinjectQuantities')) && $qty_cancel_product > 0)
									$this->reinjectQuantity($order_detail, $qty_cancel_product);
								
								// Delete product
								$order_detail = new OrderDetail((int)$id_order_detail);
								if (!$order->deleteProduct($order, $order_detail, $qty_cancel_product))
									$this->errors[] = Tools::displayError('An error occurred while attempting to delete the product.').' <span class="bold">'.$order_detail->product_name.'</span>';
								// Update weight SUM
								$order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
								if (Validate::isLoadedObject($order_carrier))
								{
									$order_carrier->weight = (float)$order->getTotalWeight();
									if ($order_carrier->update())
										$order->weight = sprintf("%.3f ".Configuration::get('PS_WEIGHT_UNIT'), $order_carrier->weight);									
								}
								Hook::exec('actionProductCancel', array('order' => $order, 'id_order_detail' => (int)$id_order_detail));
							}
						if (!count($this->errors) && $customizationList)
							foreach ($customizationList as $id_customization => $id_order_detail)
							{
								$order_detail = new OrderDetail((int)($id_order_detail));
								$qtyCancelProduct = abs($customizationQtyList[$id_customization]);
								if (!$order->deleteCustomization($id_customization, $qtyCancelProduct, $order_detail))
									$this->errors[] = Tools::displayError('An error occurred while attempting to delete product customization.').' '.$id_customization;
							}
						// E-mail params
						if ((Tools::isSubmit('generateCreditSlip') || Tools::isSubmit('generateDiscount')) && !count($this->errors))
						{
							$customer = new Customer((int)($order->id_customer));
							$params['{lastname}'] = $customer->lastname;
							$params['{firstname}'] = $customer->firstname;
							$params['{id_order}'] = $order->id;
							$params['{order_name}'] = $order->getUniqReference();
						}

						// Generate credit slip
						if (Tools::isSubmit('generateCreditSlip') && !count($this->errors))
						{
							if (!OrderSlip::createOrderSlip($order, $full_product_list, $full_quantity_list, Tools::isSubmit('shippingBack')))
								$this->errors[] = Tools::displayError('A credit slip cannot be generated. ');
							else
							{
								Hook::exec('actionOrderSlipAdd', array('order' => $order, 'productList' => $full_product_list, 'qtyList' => $full_quantity_list));
								@Mail::Send(
									(int)$order->id_lang,
									'credit_slip',
									Mail::l('New credit slip regarding your order', $order->id_lang),
									$params,
									$customer->email,
									$customer->firstname.' '.$customer->lastname,
									null,
									null,
									null,
									null,
									_PS_MAIL_DIR_,
									true,
									(int)$order->id_shop
								);
							}
						}

						// Generate voucher
						if (Tools::isSubmit('generateDiscount') && !count($this->errors))
						{
							$cartrule = new CartRule();
							$languages = Language::getLanguages($order);
							$cartrule->description = sprintf($this->l('Credit card slip for order #%d'), $order->id);
							foreach ($languages as $language)
							{
								// Define a temporary name
								$cartrule->name[$language['id_lang']] = 'V0C'.(int)($order->id_customer).'O'.(int)($order->id);
							}
							// Define a temporary code
							$cartrule->code = 'V0C'.(int)($order->id_customer).'O'.(int)($order->id);

							$cartrule->quantity = 1;
							$cartrule->quantity_per_user = 1;
							// Specific to the customer
							$cartrule->id_customer = $order->id_customer;
							$now = time();
							$cartrule->date_from = date('Y-m-d H:i:s', $now);
							$cartrule->date_to = date('Y-m-d H:i:s', $now + (3600 * 24 * 365.25)); /* 1 year */
							$cartrule->active = 1;

							$products = $order->getProducts(false, $full_product_list, $full_quantity_list);

							$total = 0;
							foreach ($products as $product)
								$total += $product['unit_price_tax_incl'] * $product['product_quantity'];

							if (Tools::isSubmit('shippingBack'))
								$total += $order->total_shipping;

							$cartrule->reduction_amount = $total;
							$cartrule->reduction_tax = true;
							$cartrule->minimum_amount_currency = $order->id_currency;
							$cartrule->reduction_currency = $order->id_currency;

							if (!$cartrule->add())
								$this->errors[] = Tools::displayError('You cannot generate a voucher.');
							else
							{
								// Update the voucher code and name
								foreach ($languages as $language)
									$cartrule->name[$language['id_lang']] = 'V'.(int)($cartrule->id).'C'.(int)($order->id_customer).'O'.$order->id;
								$cartrule->code = 'V'.(int)($cartrule->id).'C'.(int)($order->id_customer).'O'.$order->id;
								if (!$cartrule->update())
									$this->errors[] = Tools::displayError('You cannot generate a voucher.');
								else
								{
									$currency = $this->context->currency;
									$params['{voucher_amount}'] = Tools::displayPrice($cartrule->reduction_amount, $currency, false);
									$params['{voucher_num}'] = $cartrule->code;
									@Mail::Send((int)$order->id_lang, 'voucher', sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
									$params, $customer->email, $customer->firstname.' '.$customer->lastname, null, null, null,
									null, _PS_MAIL_DIR_, true, (int)$order->id_shop);
								}
							}
						}
					}
					else
						$this->errors[] = Tools::displayError('No product or quantity has been selected.');

					// Redirect if no errors
					if (!count($this->errors))
						Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=31&token='.$this->token);
				}
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}
		elseif (Tools::isSubmit('messageReaded'))
			Message::markAsReaded(Tools::getValue('messageReaded'), $this->context->employee->id);
		elseif (Tools::isSubmit('submitAddPayment') && isset($order))
		{
			if ($this->tabAccess['edit'] === '1')
			{
				$amount = str_replace(',', '.', Tools::getValue('payment_amount'));
				$currency = new Currency(Tools::getValue('payment_currency'));
				$order_has_invoice = $order->hasInvoice();
				if ($order_has_invoice)
					$order_invoice = new OrderInvoice(Tools::getValue('payment_invoice'));
				else
					$order_invoice = null;

				if (!Validate::isLoadedObject($order))
					$this->errors[] = Tools::displayError('The order cannot be found');
				elseif (!Validate::isNegativePrice($amount) || !(float)$amount)
					$this->errors[] = Tools::displayError('The amount is invalid.');
				elseif (!Validate::isString(Tools::getValue('payment_method')))
					$this->errors[] = Tools::displayError('The selected payment method is invalid.');
				elseif (!Validate::isString(Tools::getValue('payment_transaction_id')))
					$this->errors[] = Tools::displayError('The transaction ID is invalid.');
				elseif (!Validate::isLoadedObject($currency))
					$this->errors[] = Tools::displayError('The selected currency is invalid.');
				elseif ($order_has_invoice && !Validate::isLoadedObject($order_invoice))
					$this->errors[] = Tools::displayError('The invoice is invalid.');
				elseif (!Validate::isDate(Tools::getValue('payment_date')))
					$this->errors[] = Tools::displayError('The date is invalid');
				else
				{
					if (!$order->addOrderPayment($amount, Tools::getValue('payment_method'), Tools::getValue('payment_transaction_id'), $currency, Tools::getValue('payment_date'), $order_invoice))
						$this->errors[] = Tools::displayError('An error occurred during payment.');
					else
						Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=4&token='.$this->token);
				}
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}
		elseif (Tools::isSubmit('submitEditNote'))
		{
			$note = Tools::getValue('note');
			$order_invoice = new OrderInvoice((int)Tools::getValue('id_order_invoice'));
			if (Validate::isLoadedObject($order_invoice) && Validate::isCleanHtml($note))
			{
				if ($this->tabAccess['edit'] === '1')
				{
					$order_invoice->note = $note;
					if ($order_invoice->save())
						Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order_invoice->id_order.'&vieworder&conf=4&token='.$this->token);
					else
						$this->errors[] = Tools::displayError('The invoice note was not saved.');
				}
				else
					$this->errors[] = Tools::displayError('You do not have permission to edit this.');
			}
			else
				$this->errors[] = Tools::displayError('The invoice for edit note was unable to load. ');
		}
/*boton crear una orden*/
		elseif (Tools::isSubmit('submitAddOrder')
                        && ($id_cart = Tools::getValue('id_cart'))
                        && ($module_name = Tools::getValue('payment_module_name'))
                        && ($id_order_state = Tools::getValue('id_order_state')) 
                        && Validate::isModuleName($module_name))
		
                {       //echo 'else if override';                
                $query_update_employee  = "UPDATE ps_cart
                    SET id_employee = ".(int)Context::getContext()->cookie->id_employee."
                    WHERE id_cart = ".(int)$id_cart;
                    //echo $query_update_employee;

                //if ( Db::getInstance()->Execute($query_update_employee) ){
                    //echo "<br> actu emploooo";
                //}
                    
                    if ($this->tabAccess['edit'] === '1') {
                        $payment_module = Module::getInstanceByName($module_name);
                        $cart = new Cart((int)$id_cart);
                        Context::getContext()->currency = new Currency((int)$cart->id_currency);
                        Context::getContext()->customer = new Customer((int)$cart->id_customer);
                        $employee = new Employee((int)Context::getContext()->cookie->id_employee);
                        $cod_pagar = Tools::getValue('cod_pagar');
                        $private_message = stripslashes (Tools::getValue('private_message'));
                        
                        if($payment_module->validateOrder(
                            (int)$cart->id, (int)$id_order_state,
                            $cart->getOrderTotal(true, Cart::BOTH), !empty($cod_pagar) ? $cod_pagar : $payment_module->displayName, $this->l('Manual order -- Employee:').
                            substr($employee->firstname, 0, 1).'. '.$employee->lastname, array(), null, false, $cart->secure_key, null, $private_message 
                        ) && Tools::isSubmit('express'))                 
                        { 
                            $sql = "SELECT id_ps_orders_transporte FROM ps_orders_transporte WHERE id_order = $payment_module->currentOrder";
                            $sql2=0;
                            if(Db::getInstance()->ExecuteS($sql)) {
                                $sql2 = "UPDATE ps_orders_transporte SET extras='express' WHERE id_order = $payment_module->currentOrder";
                            }
                            else $sql2 = "INSERT INTO ps_orders_transporte (id_order,EXTRAS) VALUES ($payment_module->currentOrder,'express')";
                            Db::getInstance()->Execute($sql2);
                        }
                                Address::update_date_delivered();
				if ($payment_module->currentOrder)
					Tools::redirectAdmin(self::$currentIndex.'&id_order='.$payment_module->currentOrder.'&vieworder'.'&token='.$this->token);
		    }
	            else
                        $this->errors[] = Tools::displayError('You do not have permission to add this.');
		}
		elseif ((Tools::isSubmit('submitAddressShipping') || Tools::isSubmit('submitAddressInvoice')) && isset($order))
		{
                    if ($this->tabAccess['edit'] === '1') {
                        $address = new Address(Tools::getValue('id_address'));
                        if (Validate::isLoadedObject($address)) {
                            // Update the address on order
                            if (Tools::isSubmit('submitAddressShipping'))
                            $order->id_address_delivery = $address->id;
                            elseif (Tools::isSubmit('submitAddressInvoice'))
                            $order->id_address_invoice = $address->id;
                            $order->update();
                            Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=4&token='.$this->token);
                        } else
                        $this->errors[] = Tools::displayError('This address can\'t be loaded');
                    } else
                        $this->errors[] = Tools::displayError('You do not have permission to edit this.');
                } elseif (Tools::isSubmit('submitChangeCurrency') && isset($order)) {
                    if ($this->tabAccess['edit'] === '1') {
                        if (Tools::getValue('new_currency') != $order->id_currency && !$order->valid) {
                            $old_currency = new Currency($order->id_currency);
                            $currency = new Currency(Tools::getValue('new_currency'));
			    if (!Validate::isLoadedObject($currency))
			    throw new PrestaShopException('Can\'t load Currency object');
                            // Update order detail amount
                            
                            foreach ($order->getOrderDetailList() as $row) {
                                $order_detail = new OrderDetail($row['id_order_detail']);
                                $fields = array(
                                        'ecotax',
                                        'product_price',
                                        'reduction_amount',
                                        'total_shipping_price_tax_excl',
                                        'total_shipping_price_tax_incl',
                                        'total_price_tax_incl',
                                        'total_price_tax_excl',
                                        'product_quantity_discount',
                                        'purchase_supplier_price',
                                        'reduction_amount',
                                        'reduction_amount_tax_incl',
                                        'reduction_amount_tax_excl',
                                        'unit_price_tax_incl',
                                        'unit_price_tax_excl',
                                        'original_product_price'

                                );
                                foreach ($fields as $field)
                                        $order_detail->{$field} = Tools::convertPriceFull($order_detail->{$field}, $old_currency, $currency);

                                $order_detail->update();
                                $order_detail->updateTaxAmount($order);
                            }

                            $id_order_carrier = (int)$order->getIdOrderCarrier();
                            if ($id_order_carrier) {
                                $order_carrier = $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
                                $order_carrier->shipping_cost_tax_excl = (float)Tools::convertPriceFull($order_carrier->shipping_cost_tax_excl, $old_currency, $currency);
                                $order_carrier->shipping_cost_tax_incl = (float)Tools::convertPriceFull($order_carrier->shipping_cost_tax_incl, $old_currency, $currency);
                                $order_carrier->update();
                            }

                            // Update order && order_invoice amount
                            $fields = array(
                                'total_discounts',
                                'total_discounts_tax_incl',
                                'total_discounts_tax_excl',
                                'total_discount_tax_excl',
                                'total_discount_tax_incl',
                                'total_paid',
                                'total_paid_tax_incl',
                                'total_paid_tax_excl',
                                'total_paid_real',
                                'total_products',
                                'total_products_wt',
                                'total_shipping',
                                'total_shipping_tax_incl',
                                'total_shipping_tax_excl',
                                'total_wrapping',
                                'total_wrapping_tax_incl',
                                'total_wrapping_tax_excl',
                            );

                            $invoices = $order->getInvoicesCollection();
                            if ($invoices)
                                foreach ($invoices as $invoice) {
                                    foreach ($fields as $field)
                                    if (isset($invoice->$field))
                                    $invoice->{$field} = Tools::convertPriceFull($invoice->{$field}, $old_currency, $currency);
                                    $invoice->save();
                                }

			    foreach ($fields as $field)
			    if (isset($order->$field))
			    $order->{$field} = Tools::convertPriceFull($order->{$field}, $old_currency, $currency);

                            // Update currency in order
                            $order->id_currency = $currency->id;
                            // Update exchange rate
                            $order->conversion_rate = (float)$currency->conversion_rate;
                            $order->update();
			}
			else
					$this->errors[] = Tools::displayError('You cannot change the currency.');
                    }
                        else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		} elseif (Tools::isSubmit('submitGenerateInvoice') && isset($order)) {
                    
                    if (!Configuration::get('PS_INVOICE', null, null, $order->id_shop))
                        $this->errors[] = Tools::displayError('Invoice management has been disabled.');
		    elseif ($order->hasInvoice())
			$this->errors[] = Tools::displayError('This order already has an invoice.');
                    else {
                        $order->setInvoice(true);
			Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=4&token='.$this->token);
                    }
		}
		elseif (Tools::isSubmit('submitDeleteVoucher') && isset($order)) {
                    if ($this->tabAccess['edit'] === '1') {
                        $order_cart_rule = new OrderCartRule(Tools::getValue('id_order_cart_rule'));
			if (Validate::isLoadedObject($order_cart_rule) && $order_cart_rule->id_order == $order->id) {
                            if ($order_cart_rule->id_order_invoice) {
                                $order_invoice = new OrderInvoice($order_cart_rule->id_order_invoice);
				if (!Validate::isLoadedObject($order_invoice))
                                    throw new PrestaShopException('Can\'t load Order Invoice object');

                                // Update amounts of Order Invoice
                                $order_invoice->total_discount_tax_excl -= $order_cart_rule->value_tax_excl;
                                $order_invoice->total_discount_tax_incl -= $order_cart_rule->value;

                                $order_invoice->total_paid_tax_excl += $order_cart_rule->value_tax_excl;
                                $order_invoice->total_paid_tax_incl += $order_cart_rule->value;

                                // Update Order Invoice
                                $order_invoice->update();
                            }

                            // Update amounts of order
                            $order->total_discounts -= $order_cart_rule->value;
                            $order->total_discounts_tax_incl -= $order_cart_rule->value;
                            $order->total_discounts_tax_excl -= $order_cart_rule->value_tax_excl;

                            $order->total_paid += $order_cart_rule->value;
                            $order->total_paid_tax_incl += $order_cart_rule->value;
                            $order->total_paid_tax_excl += $order_cart_rule->value_tax_excl;

                            // Delete Order Cart Rule and update Order
                            $order_cart_rule->delete();
                            $order->update();
                            Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=4&token='.$this->token);
                        } else
                            $this->errors[] = Tools::displayError('You cannot edit this cart rule.');
                    } else
                        $this->errors[] = Tools::displayError('You do not have permission to edit this.');
		} elseif (Tools::getValue('submitNewVoucher') && isset($order)) {
                    if ($this->tabAccess['edit'] === '1') {
                        if (!Tools::getValue('discount_name'))
                                $this->errors[] = Tools::displayError('You must specify a name in order to create a new discount.');
                        else {
                            if ($order->hasInvoice()) {
                                // If the discount is for only one invoice
                                if (!Tools::isSubmit('discount_all_invoices')) {
                                    $order_invoice = new OrderInvoice(Tools::getValue('discount_invoice'));
                                    if (!Validate::isLoadedObject($order_invoice))
                                        throw new PrestaShopException('Can\'t load Order Invoice object');
                                }
                            }

                            $cart_rules = array();
                            $discount_value = (float)str_replace(',', '.', Tools::getValue('discount_value'));
                                switch (Tools::getValue('discount_type')) {
                                    // Percent type
                                    case 1:
                                        if ($discount_value < 100) {
                                            if (isset($order_invoice)) {
                                                $cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($order_invoice->total_paid_tax_incl * $discount_value / 100, 2);
                                                $cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($order_invoice->total_paid_tax_excl * $discount_value / 100, 2);

                                                // Update OrderInvoice
                                                $this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);
                                            }
                                            elseif ($order->hasInvoice()) {
                                                $order_invoices_collection = $order->getInvoicesCollection();
                                                foreach ($order_invoices_collection as $order_invoice) {
                                                    $cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($order_invoice->total_paid_tax_incl * $discount_value / 100, 2);
                                                    $cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($order_invoice->total_paid_tax_excl * $discount_value / 100, 2);

                                                    // Update OrderInvoice
                                                    $this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);
                                                }
                                            }
                                            else {
                                                $cart_rules[0]['value_tax_incl'] = Tools::ps_round($order->total_paid_tax_incl * $discount_value / 100, 2);
                                                $cart_rules[0]['value_tax_excl'] = Tools::ps_round($order->total_paid_tax_excl * $discount_value / 100, 2);
                                            }
                                        } else
                                            $this->errors[] = Tools::displayError('The discount value is invalid.');
					break;
                                    // Amount type
                                    case 2:
                                        if (isset($order_invoice)) {
                                            if ($discount_value > $order_invoice->total_paid_tax_incl)
                                                $this->errors[] = Tools::displayError('The discount value is greater than the order invoice total.');
                                            else  {
                                                $cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($discount_value, 2);
                                                $cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($discount_value / (1 + ($order->getTaxesAverageUsed() / 100)), 2);
                                                // Update OrderInvoice
                                                $this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);
                                            }
					}
					elseif ($order->hasInvoice()) {
                                            $order_invoices_collection = $order->getInvoicesCollection();
                                            foreach ($order_invoices_collection as $order_invoice) {
                                                if ($discount_value > $order_invoice->total_paid_tax_incl)
                                                    $this->errors[] = Tools::displayError('The discount value is greater than the order invoice total.').$order_invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$order->id_shop).')';
                                                else {
                                                    $cart_rules[$order_invoice->id]['value_tax_incl'] = Tools::ps_round($discount_value, 2);
                                                    $cart_rules[$order_invoice->id]['value_tax_excl'] = Tools::ps_round($discount_value / (1 + ($order->getTaxesAverageUsed() / 100)), 2);

                                                    // Update OrderInvoice
                                                    $this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);
                                                }
                                            }
					}
					else {
                                            if ($discount_value > $order->total_paid_tax_incl)
                                                $this->errors[] = Tools::displayError('The discount value is greater than the order total.');
                                            else {
                                                $cart_rules[0]['value_tax_incl'] = Tools::ps_round($discount_value, 2);
                                                $cart_rules[0]['value_tax_excl'] = Tools::ps_round($discount_value / (1 + ($order->getTaxesAverageUsed() / 100)), 2);
                                            }
					}
					break;
                                    // Free shipping type
                                    case 3:
                                        if (isset($order_invoice)) {
                                            if ($order_invoice->total_shipping_tax_incl > 0) {
                                                $cart_rules[$order_invoice->id]['value_tax_incl'] = $order_invoice->total_shipping_tax_incl;
                                                $cart_rules[$order_invoice->id]['value_tax_excl'] = $order_invoice->total_shipping_tax_excl;

                                                // Update OrderInvoice
                                                $this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);
                                            }
					}
					elseif ($order->hasInvoice()) {
                                            $order_invoices_collection = $order->getInvoicesCollection();
                                            foreach ($order_invoices_collection as $order_invoice) {
                                                if ($order_invoice->total_shipping_tax_incl <= 0)
						continue;
                                                $cart_rules[$order_invoice->id]['value_tax_incl'] = $order_invoice->total_shipping_tax_incl;
                                                $cart_rules[$order_invoice->id]['value_tax_excl'] = $order_invoice->total_shipping_tax_excl;

                                                // Update OrderInvoice
                                                $this->applyDiscountOnInvoice($order_invoice, $cart_rules[$order_invoice->id]['value_tax_incl'], $cart_rules[$order_invoice->id]['value_tax_excl']);
                                            }
					}
					else {
                                            $cart_rules[0]['value_tax_incl'] = $order->total_shipping_tax_incl;
                                            $cart_rules[0]['value_tax_excl'] = $order->total_shipping_tax_excl;
					}
					break;
					default:
							$this->errors[] = Tools::displayError('The discount type is invalid.');
				}

                            $res = true;
                            foreach ($cart_rules as &$cart_rule) {
                                $cartRuleObj = new CartRule();
                                $cartRuleObj->date_from = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($order->date_add)));
                                $cartRuleObj->date_to = date('Y-m-d H:i:s', strtotime('+1 hour'));
                                $cartRuleObj->name[Configuration::get('PS_LANG_DEFAULT')] = Tools::getValue('discount_name');
                                $cartRuleObj->quantity = 0;
                                $cartRuleObj->quantity_per_user = 1;
                                if (Tools::getValue('discount_type') == 1)
                                        $cartRuleObj->reduction_percent = $discount_value;
                                elseif (Tools::getValue('discount_type') == 2)
                                        $cartRuleObj->reduction_amount = $cart_rule['value_tax_excl'];
                                elseif (Tools::getValue('discount_type') == 3)
                                        $cartRuleObj->free_shipping = 1;
                                        $cartRuleObj->active = 0;
                                if ($res = $cartRuleObj->add())
                                        $cart_rule['id'] = $cartRuleObj->id;
                                else
                                break;
                            }
                            if ($res) {
                                foreach ($cart_rules as $id_order_invoice => $cart_rule)  {
                                    // Create OrderCartRule
                                    $order_cart_rule = new OrderCartRule();
                                    $order_cart_rule->id_order = $order->id;
                                    $order_cart_rule->id_cart_rule = $cart_rule['id'];
                                    $order_cart_rule->id_order_invoice = $id_order_invoice;
                                    $order_cart_rule->name = Tools::getValue('discount_name');
                                    $order_cart_rule->value = $cart_rule['value_tax_incl'];
                                    $order_cart_rule->value_tax_excl = $cart_rule['value_tax_excl'];
                                    $res &= $order_cart_rule->add();

                                    $order->total_discounts += $order_cart_rule->value;
                                    $order->total_discounts_tax_incl += $order_cart_rule->value;
                                    $order->total_discounts_tax_excl += $order_cart_rule->value_tax_excl;
                                    $order->total_paid -= $order_cart_rule->value;
                                    $order->total_paid_tax_incl -= $order_cart_rule->value;
                                    $order->total_paid_tax_excl -= $order_cart_rule->value_tax_excl;
                                }

                                // Update Order
                                $res &= $order->update();
                            }
                            if ($res)
                                Tools::redirectAdmin(self::$currentIndex.'&id_order='.$order->id.'&vieworder&conf=4&token='.$this->token);
                            else
                            $this->errors[] = Tools::displayError('An error occurred during the OrderCartRule creation');
			}
                    }
                    else
                        $this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}

		parent::postProcess();
	}

	public function generarReporteDetallado(){
		Utilities::exportDetailReport();
	}


        public function getNameStatusOrder($id_state_order) {
        $query = "	select conf.`name` 
					from ps_order_state_lang o_state_l
					INNER JOIN ps_configuration conf ON(o_state_l.id_order_state=conf.`value`)
					WHERE o_state_l.id_order_state= " . $id_state_order . " and conf.`name` in(select name from ps_conf_status)";

        if ($results = Db::getInstance()->ExecuteS($query)) {

            foreach ($results as $row) {
                return $row['name'];
            }
        }
        return false;
    }
    
   	public function addProductStock($id_order) {
       
        $query="SELECT  s_order_d.id_product , COUNT(s_order_d.id_product) as cantidad,so.id_warehouse,s_order_d.unit_price_te,s_order_d.id_supply_order
			FROM ps_orders orders 
			INNER JOIN ps_order_detail orders_d ON( orders.id_order= orders_d.id_order)
			INNER JOIN ps_supply_order_detail s_order_d ON(orders_d.product_id=s_order_d.id_product)
			INNER JOIN ps_supply_order_icr s_order_i ON (s_order_d.id_supply_order_detail=s_order_i.id_supply_order_detail)
			INNER JOIN ps_icr icr ON (s_order_i.id_icr=icr.id_icr)
			INNER JOIN ps_order_picking o_picking ON (orders_d.id_order_detail= o_picking.id_order_detail AND s_order_i.id_supply_order_icr =o_picking.id_order_supply_icr)
			INNER JOIN  ps_supply_order so ON( so.id_supply_order = s_order_d.id_supply_order )
			WHERE icr.id_estado_icr=3 and orders.id_order=".$id_order." 
			GROUP BY so.id_warehouse,s_order_d.id_product;";
           
 		if ($results = Db::getInstance()->ExecuteS($query)) {

   			$stock_manager = StockManagerFactory::getManager();       
    
     			foreach ($results as $value) {       

                    $warehouse = new Warehouse($value['id_warehouse']);
                    
                    $id_stock_mvt_reason = Configuration::get('PS_STOCK_MVT_INC_REASON_DEFAULT');
                    $usable = true;  

                  	if ($stock_manager->addProduct($value['id_product'], 0, $warehouse, $value['cantidad'], $id_stock_mvt_reason, $value['unit_price_te'], $usable)) {
	                 	StockAvailable::synchronize($value['id_product']);                        
                    }
     			}

  			return true;
 		}       

   		return false;     
    }


function parse_classname ($name)
{
  return array('classname' => join('', array_slice(explode('\\', $name), -1)));
  /// ----> Dynamically create PHP object based on string
  // -- Paseo dinamico PHP
  // parse_str — Convierte el string en variables
  // ${"this"}

  /*
  	$str = "One";
	$class = "Class".$str;
	$object = new $class();

	$eb = new ${'classname'}();
  */

  	//$reflection = new ReflectionClass($classname);
	//$object = $reflection->newInstanceArgs($args);
}

/**
 * Valida el estado de una orden de la lista
 */
protected function statusOrderOfList($id_order){

	$array_errors = array();
	$stop_step = FALSE;
	$sql="SELECT confi.`value`, estado.`name`, confi.`name` as status_name,cf_status.conditions,cf_status.messages,cf_status.objects,cf_status.stop_step
				FROM ps_orders orden 
				INNER JOIN ps_order_state_lang estado ON(orden.current_state = estado.id_order_state)
				INNER JOIN ps_configuration confi ON(estado.id_order_state = confi.`value`)
				INNER JOIN ps_conf_status cf_status ON(confi.`name` = cf_status.`name`)
			WHERE orden.id_order = ".(int)$id_order.";";

	   if ($results = Db::getInstance()->ExecuteS($sql) )
    		{
    			if($results[0]['stop_step'])
                    { 
                    	$stop_step = TRUE; 
                      	if(isset($results[0]['conditions']) && !empty($results[0]['conditions'])){ 
                      		$results[0]['conditions'] = str_replace('"id_order"', $id_order , $results[0]['conditions']);
                      		$array_errors =	$this->validate_conditions($results[0]);
                      		}	
                    }
    		}
    	$array = array();
        $array['status_name']= isset($results[0]['status_name']) ? $results[0]['status_name'] : NULL;
       // si existen validaciones que no se cumplen para el estado actual de la orden se detiene el estado y se muestra al usuario los mensajes de error o advertencia 	
       if(count($array_errors)>0){
       		$array['ERRORS_THIS_STEP'] = $array_errors;
       	    $array['detener_estado'] = 'true';                        
            $array['PS_STOP_THIS_STEP'] = 'true';
            $array['stop_step'] = $stop_step; 
       }else{
       	    $array['detener_estado'] = 'false';                        
            $array['PS_STOP_THIS_STEP'] = 'false'; 
            //$array['status_name'] = NULL;
            $array['stop_step'] = $stop_step;
       }
       return $array;		
}


    /*
     *  valida el estado de una orden de salida y los estados que dependen de esta
     * 
     */

    protected function statusOrder($OrderStates,$select){
        $array = array();
        $array['detener_estado']= 'false'; 
 
        $query="SELECT 	confdes.`value`,
                        estado.`name`,
                        confdes.`name` as status_name, 
                        detino.conditions,detino.messages,
                        detino.objects,
                        detino.stop_step
                FROM ps_configuration confdes
                INNER JOIN ps_conf_status detino ON (confdes.`name` = detino.`name`)
                INNER JOIN ps_status_options opciones ON (detino.id_conf_status = opciones.id_option_status)
                INNER JOIN ps_conf_status origen ON (opciones.id_conf_status = origen.id_conf_status)
                INNER JOIN ps_configuration confori ON (origen.`name` = confori.`name`)
                INNER JOIN ps_order_state_lang estado ON (confdes.`value` = estado.id_order_state)
                WHERE confori.`value` = ".(int)$select.';';
//        error_log("\n\nEste es query: ".print_r($query, true),3, "/tmp/states.log" );
        $results = Db::getInstance()->ExecuteS($query);
//        error_log("\n\nEste es results query: ".print_r($results, true),3, "/tmp/states.log" );
        if ($results){   
            $osname=null;
            foreach ($results as $row ) {
                $osname[$row['value']]=$row['name'];
            }

            $array_errors = array();
            $estados=null;
            $i=0;
            foreach ($OrderStates as $row){ 

                foreach ($results as $row2){
                    if((int)$row['id_order_state']==$row2['value']){ 
                        $estados[]=$OrderStates[$i];
                    }

                    //  Valida las restricciones que se deben aplicar al estado actual  
                    if((int)$row['id_order_state']==(int)$select){ 
                        // carga las restricciones del estado actual de la orden
                        $array = $this->statusOrderOfList($this->id_object);   	
                    }
                }
                $i++;
            }

            $array['osname']= $osname;
            $array['status_order']= $estados;
            // si existen validaciones que no se cumplen para el estado actual de la orden se detiene el estado y se muestra al usuario los mensajes de error o advertencia 	
            if(count($array_errors)>0){
                $array['ERRORS_THIS_STEP'] = $array_errors;
                $array['detener_estado'] = 'true';                        
                $array['PS_STOP_THIS_STEP'] = 'true'; 
            }
            else{
                $array['detener_estado'] = 'false';                        
                $array['PS_STOP_THIS_STEP'] = 'false'; 
            }
            return $array;
        }
        //error_log("Este es array FINAL: ".print_r($array, true),3, "/tmp/states.log" );
        return false;
    }
        
   

    public static function complemento_estado($id_profile)
    {
        $complement = Profile::getProfile ($id_profile); 
        $estado= Configuration::get('ps_complemento_estado'); 
        $array = explode( ",", $estado); 
        
        foreach ($array as $key => $value) {
            //echo "<br>---".$complement['name']. "_-".$value;
            if (strpos(strtolower($complement['name']),strtolower( $value)) !== FALSE) {
                return $value;
            }
        }
        return ('');        
    }
      /**
     * Validación de condiciones 
     */ 
    protected function validate_conditions($row2)
    {
        $array_errors = array();

        // Lista de validaciones 
        $array['conditions'] =  json_decode($row2['conditions'],true);
        foreach ($array['conditions'] as $condition ) {
            // valida condiciones del estado actual utilizando los métodos disponibles
            if(isset($array['conditions']['methods']) && !empty($array['conditions']['methods'])){
                foreach ($array['conditions']['methods'] as $method => $options) { 
                    if($this->validate_condition($method,$options,'method')){ 
                        $array_errors[]= array('class'=>$options['class'],'message'=>$options['message'],'locations'=>$options['locations'],"control_vars"=>$options['control_vars']);
                    }
                }
            }
        }
                    // valida condiciones del estado actual utilizando los atributos disponibles
                    /* if(isset($array['conditions']['attributes']) && !empty($array['conditions']['attributes'])){
                        foreach ($array['conditions']['attributes'] as $attribute =>  $options) {
                            if($this->validate_conditions->validate_condition($method,$options,'attribute')){ 
                                $array_errors[]= array('class'=>$options['class'],'message'=>$options['message'],'locations'=>$options['locations'],"control_vars"=>$options['control_vars']);
                            }
                        }
                    }*/

        return $array_errors;
    }
     /**
      * Valida una condición asociada a estado de orden 
      */
    protected function validate_condition($property,$options,$option)
    {
        if(isset($property) && isset($option) && !empty($property) && !empty($option)) {
            if($option === 'method'){
                //exit('return:<pre>'.print_r($options['return'],true));
                if($options['return'] == call_user_func_array(array($this, $property), $options['parameters'])) {
                    return true; // Si el valor retornado por el método es igual al valor de la restricción 
                } else {
                    return false;
                }
            }
            elseif($option === 'attribute') {
            }   
        }

        if(isset($array_conditions['parameters'])) {
            foreach ($array_conditions['parameters'] as $key) {
            }
        }
        foreach ($results as $key) {
            if(!$key) // si alguna de las condiciones no es verdadera 
                return false;	
        }
            return true; // si todas las condiciones son verdaderas              
    }

   /**
     * valida si una orden tiene asociado un mensajero de envió o una empresa transportadora
     */  
    protected function is_sassociate_carrier_order($id_order = NULL)
    { 
   
        if($id_order == NULL) {
            $id_order = $this->id_object;
   	}
       	$query="SELECT asoc.id_associate_carrier, ordenes.id_order,asoc.entity, asoc.id_entity
				FROM ps_orders ordenes
				INNER JOIN ps_associate_carrier asoc ON (ordenes.id_order = asoc.id_order)
				WHERE asoc.id_order = ".(int)$id_order;
     	if ($results = Db::getInstance()->ExecuteS($query)) {
            if(isset($results) && !empty($results[0]['id_associate_carrier']) && $results[0]['id_order'] == (int)$id_order) {
                return true; // solo si la orden tiene un mensajero asociado
            }
        }
        return false; // en cualquier otro caso se retorna falso.
    }
   
   /*
	Valida si una orden de salida tiene todos los ICRS asociados. 
   */
    public  function ordenCompleta($id_order = NULL)
    {
        if($id_order == NULL){
            $id_order = $this->id_object;
   	}
    
        $query="SELECT 'NO' in
                        (SELECT if(t1.car_cantidad=t2.ord_total,'SI','NO') as completo
			FROM
                            (SELECT order_d.product_id ord_product_id,order_d.product_quantity ord_total  FROM ps_orders orders
                                INNER JOIN ps_order_detail  order_d ON(orders.id_order=order_d.id_order)
				WHERE order_d.id_order =".$id_order." and order_d.id_order> 1813) as t2

			    LEFT JOIN
				(SELECT  s_order_d.id_product car_id_product, COUNT(s_order_d.id_product) as car_cantidad
					FROM ps_orders orders 
					INNER JOIN ps_order_detail orders_d ON( orders.id_order= orders_d.id_order)
					INNER JOIN ps_supply_order_detail s_order_d ON(orders_d.product_id=s_order_d.id_product)
					INNER JOIN ps_supply_order_icr s_order_i ON (s_order_d.id_supply_order_detail=s_order_i.id_supply_order_detail)
					INNER JOIN ps_icr icr ON (s_order_i.id_icr=icr.id_icr)
					INNER JOIN ps_order_picking o_picking ON (orders_d.id_order_detail= o_picking.id_order_detail AND s_order_i.id_supply_order_icr =o_picking.id_order_supply_icr)
					WHERE icr.id_estado_icr=3 and orders.id_order=".$id_order." and orders.id_order>1813
					GROUP BY s_order_d.id_product) as t1
                                        ON(t1.car_id_product=t2.ord_product_id)) as incompleta";
        
        if ($results = Db::getInstance()->ExecuteS($query)) {
            if($results[0]['incompleta']==0)
                return true;
        }  
        
        return false;    

    }
    
    public function transportista($order)
    { 
  
        if(isset($order)&&  $order->module==='cashondelivery'  ) { 
            $query = " SELECT nombre,`value`FROM
                            ps_transporte_opciones; ";
            if ($results = Db::getInstance()->ExecuteS($query)) {
                foreach ($results as $valores) {
                    $opciones_transportador[$valores['value']]=$valores['nombre'];
                }
        	$this->context->smarty->assign(array('opciones_transportador'=>$opciones_transportador));
            }    
            $this->context->smarty->assign(array('opcion_transportador'=>TRUE));
        } else {
    	$this->context->smarty->assign(array('opcion_transportador'=>FALSE));
        }
    }   
/*
 * Almacena la opción de COD Farmalisto
 */
    public function opcionTransportista($request)
    {   //exit(json_encode(array('result'=>$request)));
        if($this->is_sassociate_carrier_order()) {        
            $query=" UPDATE `ps_associate_carrier` SET  `entity`='".ucwords($request['entity'])."', `propertis_entity`='{}', `id_entity`=".(int)$request['id_entity'].", `id_employee`=".$this->context->cookie->{'id_employee'}.", date_update='".date('Y-m-d H:i:s')."', `id_shop`='1' WHERE (`id_order`=".(int)$this->id_object.");";
                // exit(json_encode(array('results' => $query)));
            if ($results = Db::getInstance()->Execute($query)) {
                $this->add_sassociate_carrier_history();
                return true;
            }
        } else {
            // Insert
            $query="INSERT INTO `ps_associate_carrier` (`entity`, `id_entity`,  `id_order`,  `date`, `id_shop`,`id_employee`) VALUES ('".$request['entity']."', ".$request['id_entity'].", ".(int)$this->id_object.", '".date('Y-m-d H:i:s')."', '1', ".$this->context->cookie->{'id_employee'}.");"; 
            //exit(json_encode(array('results' => $query)));
            
            if ($results = Db::getInstance()->ExecuteS($query)) {
                $this->add_sassociate_carrier_history();
       		return true;
            }
   	}
   	return false;
    }

    protected function get_sassociate_carrier_order()
    {
        $sql="SELECT  ascar.* FROM
				"._DB_PREFIX_."associate_carrier ascar
				INNER JOIN "._DB_PREFIX_."orders ordenes ON (ascar.id_order = ordenes.id_order) 
				WHERE ascar.id_order = ".$this->id;
    }

    protected function add_sassociate_carrier_history()
    {
        $sql = "INSERT INTO `ps_sassociate_carrier_history` (`id_employee`, `id_order`, `log`, `date`) VALUES (".$this->context->cookie->{'id_employee'}.", ".(int)$this->id_object.", '', '".date('Y-m-d H:i:s')."');";
        //exit(json_encode(array('results' => $sql)));
	if ($results = Db::getInstance()->ExecuteS($sql)) {
            return true;
        }
    }

    protected function add_smarty_vars($ps_conf_var = NULL, $smarty_var, $order = NULL)
    {
        if((isset($this->{'object'}) || !empty($order)) && $ps_conf_var != NULL) {
            $current_state = 0;
            if(isset($this->{'object'}->current_state)) {
		$current_state = (int)$this->{'object'}->current_state;
	    } else {
                $current_state = (int)$order->current_state;
            }
            $sql = "SELECT confdes.`name` 
			FROM ps_configuration confdes
                        INNER JOIN ps_conf_status detino ON (confdes.`name` = detino.`name`)
                        INNER JOIN ps_status_options opciones ON (detino.id_conf_status = opciones.id_option_status)
                        INNER JOIN ps_conf_status origen ON (opciones.id_conf_status = origen.id_conf_status)
                        INNER JOIN ps_configuration confori ON (origen.`name` = confori.`name`)
                        INNER JOIN ps_order_state_lang estado ON (confdes.`value` = estado.id_order_state)
                        WHERE confori.`value` = ".$current_state." AND confdes.`name` = '".$ps_conf_var."';";

            $result = Db::getInstance()->getValue($sql);
            if(!empty($result) && $result == $ps_conf_var && !empty($smarty_var)) {
		if(is_array($smarty_var)) {
                    foreach ($smarty_var as $key => $value) {
                        $this->extra_vars_tpl[$key] = $value;  
                    }
		} else {
                    $this->extra_vars_tpl[trim($smarty_var)] = TRUE;  
		}
            }
	} else {

			if(is_array($smarty_var)){
				foreach ($smarty_var as $key => $value) {
						$this->extra_vars_tpl[$key] = $value;  
					}
				}else{
					$this->extra_vars_tpl[trim($smarty_var)] = TRUE;  
				}
				
		}

	}

	protected function motivo_cancelcion($order = NULL){
				$this->add_smarty_vars('PS_OS_CANCELED', 'motivo_cancelcion', $order);
		return TRUE;
	}

	protected function get_mensajero_order($id_order){
		$sql = "SELECT 
					CASE asoc.entity
					WHEN  'employee' THEN CONCAT(emp.firstname,' ' , emp.lastname)
					WHEN  'Carrier' THEN trans.`name`
					ELSE 'N/A'
					END
					AS name_entity, asoc.id_entity, asoc.entity, emp.email
						FROM ps_employee emp LEFT JOIN ps_associate_carrier asoc ON (emp.id_employee = asoc.id_entity)
						INNER JOIN ps_orders orden ON (asoc.id_order = orden.id_order)
						LEFT JOIN ps_carrier trans ON(asoc.id_entity = trans.id_carrier)
						WHERE orden.id_order = ".(int) $id_order.";";

			$result = Db::getInstance()->ExecuteS($sql);
			if(!empty($result) && $result[0]['name_entity'] != NULL){	

				$this->add_smarty_vars(NULL, $result[0] , NULL);
				return $result[0];
			}	
			return NULL;	
	}

	public function renderView()
	{   $id_order = Tools::getValue('id_order');
		if(!isset($id_order) || empty($id_order)) // Si el id_order no existe en la solicitud se toma del contexto de PS
			$id_order = $this->id_object;
		$order = new Order($id_order);
		if (!Validate::isLoadedObject($order))
			throw new PrestaShopException('object can\'t be loaded');
                
                //error_log("\n\n\nORDER: n".print_r($order,true),3,"/tmp/states.log");
		$order->private_message = addslashes($order->private_message);
		$customer = new Customer($order->id_customer);
		$carrier = new Carrier($order->id_carrier);
		$products = $this->getProducts($order);
		$currency = new Currency((int)$order->id_currency);
		// Carrier module call
		$carrier_module_call = null;
		if ($carrier->is_module)
		{
			$module = Module::getInstanceByName($carrier->external_module_name);
			if (method_exists($module, 'displayInfoByCart'))
				$carrier_module_call = call_user_func(array($module, 'displayInfoByCart'), $order->id_cart);
		}

		// Retrieve addresses information
		$addressInvoice = new Address($order->id_address_invoice, $this->context->language->id);
		if (Validate::isLoadedObject($addressInvoice) && $addressInvoice->id_state)
			$invoiceState = new State((int)$addressInvoice->id_state);

		if ($order->id_address_invoice == $order->id_address_delivery)
		{
			$addressDelivery = $addressInvoice;
			if (isset($invoiceState))
				$deliveryState = $invoiceState;
		}
		else
		{
			$addressDelivery = new Address($order->id_address_delivery, $this->context->language->id);
			if (Validate::isLoadedObject($addressDelivery) && $addressDelivery->id_state)
				$deliveryState = new State((int)($addressDelivery->id_state));
		}

		$this->toolbar_title = sprintf($this->l('Order #%1$d (%2$s) - %3$s %4$s'), $order->id, $order->reference, $customer->firstname, $customer->lastname);
		if (Shop::isFeatureActive())
		{
			$shop = new Shop((int)$order->id_shop);
			$this->toolbar_title .= ' - '.sprintf($this->l('Shop: %s'), $shop->name);
		}

		// gets warehouses to ship products, if and only if advanced stock management is activated
		$warehouse_list = null;

		$order_details = $order->getOrderDetailList();
		foreach ($order_details as $order_detail)
		{
			$product = new Product($order_detail['product_id']);

			if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')
				&& $product->advanced_stock_management)
			{
				$warehouses = Warehouse::getWarehousesByProductId($order_detail['product_id'], $order_detail['product_attribute_id']);
				foreach ($warehouses as $warehouse)
				{
					if (!isset($warehouse_list[$warehouse['id_warehouse']]))
						$warehouse_list[$warehouse['id_warehouse']] = $warehouse;
				}
			}
		}

		$payment_methods = array();
		foreach (PaymentModule::getInstalledPaymentModules() as $payment)
		{
			$module = Module::getInstanceByName($payment['name']);
			if (Validate::isLoadedObject($module) && $module->active)
				$payment_methods[] = $module->displayName;
		}

		// display warning if there are products out of stock
		$display_out_of_stock_warning = false;
		$current_order_state = $order->getCurrentOrderState();
		if (Configuration::get('PS_STOCK_MANAGEMENT') && (!Validate::isLoadedObject($current_order_state) || ($current_order_state->delivery != 1 && $current_order_state->shipped != 1)))
			$display_out_of_stock_warning = true;

		// products current stock (from stock_available)
                $flagStockDisplayOption = false;
		foreach ($products as &$product)
		{
			$product['current_stock'] = StockAvailable::getQuantityAvailableByProduct($product['product_id'], $product['product_attribute_id'], $product['id_shop']);
			
			$resume = OrderSlip::getProductSlipResume($product['id_order_detail']);
			$product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];
			$product['amount_refundable'] = $product['total_price_tax_incl'] - $resume['amount_tax_incl'];
			$product['amount_refund'] = Tools::displayPrice($resume['amount_tax_incl'], $currency);
			$product['refund_history'] = OrderSlip::getProductSlipDetail($product['id_order_detail']);
			$product['return_history'] = OrderReturn::getProductReturnDetail($product['id_order_detail']);
			
                        //error_log("\n\n si entro ".print_r($product, true),3,"/tmp/states.log");
                        
			// if the current stock requires a warning
                        if ( $product['current_stock'] < $product['product_quantity'] ) {
                            //error_log("\n\n si entro",3,"/tmp/states.log");
                            $missingProduct = $product['product_quantity'] - $product['current_stock'];
//                            $errorW = 'Faltan '.$missingProduct.' cantidades del producto '.$product['product_name'],3,"/tmp/states.log";
                            $this->displayWarning('Faltan '.$missingProduct.' cantidades del producto '.$product['product_name']);
                            if($current_order_state->id == 9){
                                //error_log("Entro".print_r($current_order_state,true),3,"/tmp/states.log");
                                $flagStockDisplayOption = true;
                            }
                        }
//			if ($product['current_stock'] == 0 && $display_out_of_stock_warning){
//                            $this->displayWarning($this->l('This product is out of stock: ').' '.$product['product_name']);
//                        }
			if ($product['id_warehouse'] != 0)
			{
				$warehouse = new Warehouse((int)$product['id_warehouse']);
				$product['warehouse_name'] = $warehouse->name;
			}
			else
				$product['warehouse_name'] = '--';
		}

		$gender = new Gender((int)$customer->id_gender, $this->context->language->id);
                     
                //error_log("\n\n Variable display_out_of_stock_warning: ".print_r($display_out_of_stock_warning,true),3,"/tmp/states.log");
                
                
                $estados=$this->statusOrder(OrderState::getOrderStates((int)Context::getContext()->language->id,(int)$this->context->employee->id_profile),$order->current_state);
        $this->fields_list['osname']['list'] = $estados['osname'];
        
        
        
        //error_log("\n\n\n\n\n\n\n orderCurrentState: ".print_r($order->getCurrentOrderState(),true),3,"/tmp/states.log");
        //error_log("\n\n Estos son los estados: ".print_r($estados['status_order'],true),3,"/tmp/states.log");
        
        $cart = new Cart($order->id_cart);             
		// Smarty assign
		$this->tpl_view_vars = array(
			'order' => $order,
			'cart' => $cart,
			'customer' => $customer,
			'gender' => $gender,
			'customer_addresses' => $customer->getAddresses($this->context->language->id),
			'addresses' => array(
				'delivery' => $addressDelivery,
				'deliveryState' => isset($deliveryState) ? $deliveryState : null,
				'invoice' => $addressInvoice,
				'invoiceState' => isset($invoiceState) ? $invoiceState : null
			),
			'customerStats' => $customer->getStats(),
			'products' => $products,
			'discounts' => $order->getCartRules(),
			'orders_total_paid_tax_incl' => $order->getOrdersTotalPaid(), // Get the sum of total_paid_tax_incl of the order with similar reference
			'total_paid' => $order->getTotalPaid(),
			'returns' => OrderReturn::getOrdersReturn($order->id_customer, $order->id),
			'customer_thread_message' => CustomerThread::getCustomerMessages($order->id_customer, 0),
			'orderMessages' => OrderMessage::getOrderMessages($order->id_lang),
			'messages' => Message::getMessagesByOrderId($order->id, true),
			'carrier' => new Carrier($order->id_carrier),
			'history' => $order->getHistory($this->context->language->id),
			'states' => $estados['status_order'],
			'warehouse_list' => $warehouse_list,
			'sources' => ConnectionsSource::getOrderSources($order->id),
			'currentState' => $order->getCurrentOrderState(),
			'currency' => new Currency($order->id_currency),
			'currencies' => Currency::getCurrencies(),
			'previousOrder' => $order->getPreviousOrderId(),
			'nextOrder' => $order->getNextOrderId(),
			'current_index' => self::$currentIndex,
			'carrierModuleCall' => $carrier_module_call,
			'iso_code_lang' => $this->context->language->iso_code,
			'id_lang' => $this->context->language->id,
			'can_edit' => ($this->tabAccess['edit'] == 1),
			'current_id_lang' => $this->context->language->id,
			'invoices_collection' => $order->getInvoicesCollection(),
			'not_paid_invoices_collection' => $order->getNotPaidInvoicesCollection(),
			'payment_methods' => $payment_methods,
			'invoice_management_active' => Configuration::get('PS_INVOICE', null, null, $order->id_shop),
			'display_warehouse' => (int)Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),
            'orden_completa' => $this->ordenCompleta(),
            'status_name' => isset($estados['status_name']) ? $estados['status_name'] : NULL,
            'PS_STOP_THIS_STEP' => isset($estados['PS_STOP_THIS_STEP']) ? $estados['PS_STOP_THIS_STEP'] : 'false' ,
            'ERRORS_THIS_STEP' => isset($estados['ERRORS_THIS_STEP']) ? $estados['ERRORS_THIS_STEP'] : NULL ,
            "stop_step" => $estados['stop_step'],
            "formula_medica" => Utilities::is_formula($cart,$this->context),
            "imgs_formula_medica" =>  Utilities::getImagenesFormula($order->id),
                    'flagStockDisplayOption' => $flagStockDisplayOption
		);
		$this->motivo_cancelcion();
		$this->get_mensajero_order($this->id_object);
		$this->get_employee_to_cart($order->id_cart);

		$this->tpl_view_vars = array_merge_recursive($this->tpl_view_vars,$this->extra_vars_tpl);
    	
 	//exit('<pre>'.print_r($this->tpl_view_vars,true));
		return AdminController::renderView();
	}

	public function renderForm()
	{
		$this->context->smarty->assign(Address::horaDeEntrega());
		$this->context->smarty->assign('expressEnabled',Configuration::get('ENVIO_EXPRESS'));
                $this->context->smarty->assign('is_entrega_nocturna', Utilities::is_rules_entrega_nocturna());
		if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP && Shop::isFeatureActive())
			$this->errors[] = $this->l('You have to select a shop before creating new orders.');

		$id_cart = (int)Tools::getValue('id_cart');
		$cart = new Cart((int)$id_cart);
		if ($id_cart && !Validate::isLoadedObject($cart))
			$this->errors[] = $this->l('This cart does not exists');
		if ($id_cart && Validate::isLoadedObject($cart) && !$cart->id_customer)
			$this->errors[] = $this->l('The cart must have a customer');
		if (count($this->errors))
			return false;

		parent::renderForm();
		unset($this->toolbar_btn['save']);
		$this->addJqueryPlugin(array('autocomplete', 'fancybox', 'typewatch'));

		$defaults_order_state = array('cheque' => (int)Configuration::get('PS_OS_CHEQUE'),
												'bankwire' => (int)Configuration::get('PS_OS_BANKWIRE'),
												'cashondelivery' => (int)Configuration::get('PS_OS_PREPARATION'),
												'other' => (int)Configuration::get('PS_OS_PAYMENT'));
		$payment_modules = array();
		foreach (PaymentModule::getInstalledPaymentModules() as $p_module)
			$payment_modules[] = Module::getInstanceById((int)$p_module['id_module']);
		$this->context->smarty->assign(array(
			'recyclable_pack' => (int)Configuration::get('PS_RECYCLABLE_PACK'),
			'gift_wrapping' => (int)Configuration::get('PS_GIFT_WRAPPING'),
			'cart' => $cart,
			'currencies' => Currency::getCurrencies(),
			'langs' => Language::getLanguages(true, Context::getContext()->shop->id),
			'payment_modules' => $payment_modules,
			'order_states' => OrderState::getOrderStates((int)Context::getContext()->language->id,(int)$this->context->employee->id_profile),
                        'order_states_back' => OrderState::getOrderStates( (int)Context::getContext()->language->id, (int)$this->context->employee->id_profile, true ),
			'defaults_order_state' => $defaults_order_state,
			'show_toolbar' => $this->show_toolbar,
			'toolbar_btn' => $this->toolbar_btn,
			'toolbar_scroll' => $this->toolbar_scroll,
			'title' => array($this->l('Orders'), $this->l('Create order'))
		));
		$this->content .= $this->createTemplate('form.tpl')->fetch();
	}

    /**
     * Para mostrar el empleado asociado a un pedido.
     */
    public function get_employee_to_cart($id_cart) {
        $sql = "SELECT CONCAT(e.firstname,' ',e.lastname) as employee_name
    	FROM	
    	"._DB_PREFIX_."cart c INNER JOIN "._DB_PREFIX_."employee e ON(c.id_employee = e.id_employee)
    	WHERE c.id_cart = ".(int) $id_cart;
        //echo $sql;      
       
    	$employee_name = Db::getInstance()->getValue($sql);
    	if (isset($employee_name) && !empty($employee_name)) {
            $this->extra_vars_tpl['employee_name'] = $employee_name; 
            //echo '<br><br>ENTRA 1<br><br>';   
           
        }
    }
}
          
         
