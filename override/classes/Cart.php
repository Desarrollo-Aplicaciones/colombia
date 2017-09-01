<?php  
 
class Cart extends CartCore {

	public $discountOrder = 0;
	public $removeRulesGroup = false;

	public $CartRuleProgressiveDiscount = 0;
	public $device = NULL;

		/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'cart',
		'primary' => 'id_cart',
		'fields' => array(
			'id_shop_group' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_shop' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_address_delivery' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_address_invoice' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_carrier' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_currency' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_customer' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_guest' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_lang' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'recyclable' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'gift' => 					array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'gift_message' => 			array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
			'mobile_theme' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'delivery_option' => 		array('type' => self::TYPE_STRING),
			'secure_key' => 			array('type' => self::TYPE_STRING, 'size' => 32),
			'allow_seperated_package' =>array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'date_add' => 				array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
			'date_upd' => 				array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
			'device' =>					array('type' => self::TYPE_STRING),
		),
	);

	public static function addCartMedico($id_cart, $id_medico){
		if ($id_medico != 0){
			$sql = 'DELETE FROM '._DB_PREFIX_.'doctor_cart WHERE id_cart = '.$id_cart;
			$data = array('id_cart' => $id_cart,
						  'id_doctor' => $id_medico);
			$error = array();
			if (!Db::getInstance()->execute($sql) || !Db::getInstance()->insert('doctor_cart', $data)){
				die($error['error'] = 'no se pudo agregar el médico');
			}
		}
		return $error;
	}

	public function __construct($id = null, $id_lang = null, $flagRemoveRuleGroup = false)
	{
		$this->removeRulesGroup = $flagRemoveRuleGroup;
		parent::__construct($id, $id_lang);
		if ($this->id_customer)
		{
			if (isset(Context::getContext()->customer) && Context::getContext()->customer->id == $this->id_customer)
				$customer = Context::getContext()->customer;
			else
				$customer = new Customer((int)$this->id_customer);

			if ((!$this->secure_key || $this->secure_key == '-1') && $customer->secure_key)
			{
				$this->secure_key = $customer->secure_key;
				$this->save();
			}
		}
		$this->_taxCalculationMethod = Group::getPriceDisplayMethod(Group::getCurrent()->id);

	}

	public function getTotalShippingCost($delivery_option = null, $use_tax = true, Country $default_country = null, $express = false)
	{
		
		if ((isset(Context::getContext()->cookie->check_xps) && Context::getContext()->cookie->check_xps)
			|| (isset(Context::getContext()->cart->check_xps) && Context::getContext()->cart->check_xps)
			|| Tools::getValue('express'))
		{
			$a=Context::getContext()->cart->id_address_delivery;
			//echo "<pre>";print_r(Context::getContext()->cart->check_xps); echo "</pre>"; exit();
			$val_total=0;
			if (Context::getContext()->cart->_products) {
				foreach (Context::getContext()->cart->_products as $key => $value) {
					$val_total += $value['total_wt']; //valor total de la compra sin impuestos
				}
			}
			$subtotal = $val_total;
			return $this->valorExpress($a,$subtotal);
		}
                
                $parameters = NULL;
                if( isset(Context::getContext()->cookie->entrega_nocturna) && Context::getContext()->cookie->entrega_nocturna === 'enabled' ){                   
                    $parameters = Utilities::get_parameters();
                }
                
                /* validaciones para envio nocturno */
                if ((!isset(Context::getContext()->cookie->check_xps) || !Context::getContext()->cookie->check_xps)
                     && isset(Context::getContext()->cookie->entrega_nocturna) && Context::getContext()->cookie->entrega_nocturna === 'enabled' )
		{
                   // trigger_error(' -| envio nocturno solo |- ', E_USER_NOTICE);
        		return   (int)($parameters['valor']);
		}
                /* validaciones envio Express y envio nocturno */
                if (Context::getContext()->cookie->check_xps
                    && isset(Context::getContext()->cookie->entrega_nocturna) 
                    && Context::getContext()->cookie->entrega_nocturna === 'enabled')
					{
						$a = Context::getContext()->cart->id_address_delivery;
						//echo "<pre>";print_r(Context::getContext()->cart->check_xps); echo "</pre>"; exit();
						$val_total=0;
						if (Context::getContext()->cart->_products) {
							foreach (Context::getContext()->cart->_products as $key => $value) {
								$val_total += $value['total_wt']; //valor total de la compra sin impuestos
							}
						}
					
						$subtotal = (round( (round($val_total)/100) *2 , 0)/ 2 ) * 100;

					if( isset($parameters['add_value_express']) && !$parameters['add_value_express'] ){
						return (float) $this->valorExpress($a,$subtotal); 
					}
					return  (float)($this->valorExpress($a,$subtotal) + (float)($parameters['valor']));
		}
		//echo "<pre>";print_r(Context::getContext()->cookie); echo "</pre>"; exit();
		
		//:Faber: agrego una variable llamada 'adc.id_city' para saber la ciudad
		$sql = 'SELECT cac.precio_kilo, car.id_carrier, crp.delimiter2, adc.id_city, cac.precio_k_add
			FROM '._DB_PREFIX_.'address adr 
			INNER JOIN '._DB_PREFIX_.'address_city adc ON (adc.id_address=adr.id_address) 
			INNER JOIN '._DB_PREFIX_.'carrier_city cac ON (cac.id_city_des = adc.id_city) 
			INNER JOIN '._DB_PREFIX_.'carrier car ON (car.id_reference = cac.id_carrier AND car.deleted = 0 AND car.active=1) 
			INNER JOIN '._DB_PREFIX_.'range_price crp ON (crp.id_carrier = car.id_carrier)
			WHERE adc.id_address='.Context::getContext()->cart->id_address_delivery.'
			ORDER BY cac.precio_kilo';


			$resultado=Db::getInstance()->executeS($sql);

		if (!$val = Db::getInstance()->getValue($sql))
		{
			$val="Ciudad sin costo de envio";
		}

		//id ciudades sin sobrecosto de envio refrigeración
		$ciudades_excluidas = explode(",", Configuration::get('NO_COBRO_REFRIGERADO'));

		$producto_rx3 = false; //Inicializo la variable en falso, para usarla de banderita. :)
		if(isset( $resultado[0]['id_carrier'])) {
			Context::getContext()->cart->id_carrier = $resultado[0]['id_carrier'];

			$val_total=0;
                        
            if (Context::getContext()->cart->_products) 
            {

            	foreach (Context::getContext()->cart->_products as $productico) {
                    $val_total += $productico['total_wt']; //valor total de la compra sin impuestos
                    if ((stripos($productico['name'], "rx3")) !== false){
                		$producto_rx3 = true;
                	}
                }
            }
                        $iva_envio_orden = Configuration::get('IVA_ENVIO_ORDEN');
			$valtot_tax = (round( (round($val_total* ( 1 + ( $iva_envio_orden / 100 ) ) )/100) *2 , 0)/ 2 ) * 100; //valor con impuesto 16% y redondeado

			if ( $val_total > $resultado[0]['delimiter2']) { // si total de compra es mayor al valor para no cobrar envio
				$val=0;
			}
		}

		// si la direccion de envio es la oficina de farmalisto, se toma como 0 el costo de transporte
		$validateaddress = $this->ValidationAddressFarmalisto();
		if ($validateaddress){
			$val = 0;
		}

		// si existe cupon con envio gratuito, se toma como 0 el costo de transporte
		$cartRules = $this->getCartRules();
		if ( isset($cartRules) && !empty($cartRules) && $cartRules[0]['free_shipping'] == 1 ) {
			$val = 0;
		}

		/**************** INVENTARIO POR CIUDAD ***************/

		if ( $this->InventarioPorCiudad() == 1 ) {
			$val = (float)Tools::ps_round((float)(1000), 2);
		}

		
		//echo $val;
		if ( $producto_rx3 && !(in_array($resultado[0]['id_city'], $ciudades_excluidas) ) ) {
			return $resultado[0]['precio_k_add'];
		}
		return $val; //$total_shipping; 
	}

	public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null, $express = false)
	{	
              	/* validaciones envio Express */
		if ((isset(Context::getContext()->cookie->check_xps) && Context::getContext()->cookie->check_xps)
			 && (!isset(Context::getContext()->cookie->entrega_nocturna) || Context::getContext()->cookie->entrega_nocturna==='disabled')) {

			$a=Context::getContext()->cart->id_address_delivery;
			//echo "<pre>";print_r(Context::getContext()->cart->check_xps); echo "</pre>"; exit();
			$val_total=0;
			if (Context::getContext()->cart->_products) {
				$val_total=0;
				foreach (Context::getContext()->cart->_products as $key => $value) {
					$val_total += $value['total_wt']; //valor total de la compra sin impuestos
				}
			}
			$subtotal = (round( (round($val_total)/100) *2 , 0)/ 2 ) * 100;
                        // trigger_error(' -| envio expres solo |- ', E_USER_NOTICE);
                       	return $this->valorExpress($a,$subtotal);
		}
                
        $parameters = NULL;

        if( isset(Context::getContext()->cookie->entrega_nocturna) && Context::getContext()->cookie->entrega_nocturna === 'enabled' ){                   
            $parameters = Utilities::get_parameters();
        }
                
                /* validaciones para envio nocturno */
        if ((!isset(Context::getContext()->cookie->check_xps) || !Context::getContext()->cookie->check_xps)
                     && isset(Context::getContext()->cookie->entrega_nocturna) && Context::getContext()->cookie->entrega_nocturna === 'enabled' ) {
                  //  trigger_error(' -| envio nocturno solo |- ', E_USER_NOTICE);
        		return (int)($parameters['valor']);
		}
                /* validaciones envio Express y envio nocturno */
        if (Context::getContext()->cookie->check_xps && isset(Context::getContext()->cookie->entrega_nocturna)  && Context::getContext()->cookie->entrega_nocturna === 'enabled') {

			$a=Context::getContext()->cart->id_address_delivery;
			//echo "<pre>";print_r(Context::getContext()->cart->check_xps); echo "</pre>"; exit();
			$val_total=0;
			if (Context::getContext()->cart->_products) {
				foreach (Context::getContext()->cart->_products as $key => $value) {
					$val_total += $value['total_wt']; //valor total de la compra sin impuestos
				}
			}
			$subtotal = (round( (round($val_total)/100) *2 , 0)/ 2 ) * 100;
                       // trigger_error(' -| envio nocturno y express |- ', E_USER_NOTICE);
			if( isset($parameters['add_value_express']) && !$parameters['add_value_express'] ){
				return (float) $this->valorExpress($a,$subtotal); 
			}
			return  (int)($this->valorExpress($a,$subtotal) + (int)($parameters['valor']));
		}
           
		$sql = 'SELECT cac.precio_kilo, car.id_carrier, crp.delimiter2, adc.id_city, cac.precio_k_add
			FROM '._DB_PREFIX_.'address adr 
			INNER JOIN '._DB_PREFIX_.'address_city adc ON (adc.id_address=adr.id_address) 
			INNER JOIN '._DB_PREFIX_.'carrier_city cac ON (cac.id_city_des = adc.id_city) 
			INNER JOIN '._DB_PREFIX_.'carrier car ON (car.id_reference = cac.id_carrier AND car.deleted = 0 AND car.active=1) 
			INNER JOIN '._DB_PREFIX_.'range_price crp ON (crp.id_carrier = car.id_carrier)
			WHERE adc.id_address='.Context::getContext()->cart->id_address_delivery. '
			ORDER BY cac.precio_kilo';
		
		$resultado=Db::getInstance()->executeS($sql);

		if (!$val = Db::getInstance()->getValue($sql))
		{
			$val="Ciudad sin costo de envio";
		}
		$shipping_cost = (float)Tools::ps_round((float)$val, 2);
		
			if(isset( $resultado[0]['id_carrier'])) {
				Context::getContext()->cart->id_carrier = $resultado[0]['id_carrier'];
			}

			$val_total=0;
		
		//id ciudades sin sobrecosto de envio refrigeración
		$ciudades_excluidas = explode(",", Configuration::get('NO_COBRO_REFRIGERADO'));
		
			$producto_rx3 = false; //Inicializo la variable en falso, para usarla de banderita. :)

			foreach (Context::getContext()->cart->_products as $key => $productico) {
                            $val_total += $productico['total_wt']; //valor total de la compra sin impuestos
                            if ((stripos($productico['name'], "rx3")) !== false){
                                $producto_rx3 = true;
                            }
			}
			
                        $iva_envio_orden = Configuration::get('IVA_ENVIO_ORDEN');
			$valtot_tax = (round( (round($val_total* ( 1 + ($iva_envio_orden/100) ) )/100) *2 , 0)/ 2 ) * 100; //valor con impuesto 16% y redondeado

			if ( $val_total > $resultado[0]['delimiter2']) { // si total de compra es mayor al valor para no cobrar envio
				$shipping_cost=0;
			}
			
			// si la direccion de envio es la oficina de farmalisto, se toma como 0 el costo de transporte
			$validateaddress = $this->ValidationAddressFarmalisto();
			if ($validateaddress){
				$shipping_cost = 0;
			}

			// si existe cupon con envio gratuito, se toma como 0 el costo de transporte
			$cartRules = $this->getCartRules();
			if ( isset($cartRules) && !empty($cartRules) && $cartRules[0]['free_shipping'] == 1 ) {
				$shipping_cost = 0;
			}


			/**************** INVENTARIO POR CIUDAD ***************/

			if ( $this->InventarioPorCiudad() == 1 ) {
				$shipping_cost = (float)Tools::ps_round((float)(1000), 2);
			}

		//echo $shipping_cost;
		if ( $producto_rx3 && !(in_array($resultado[0]['id_city'], $ciudades_excluidas) ) ) {
			return $resultado[0]['precio_k_add'];
		}
		return $shipping_cost;
	}

	public function removeCartRules()
	{
		$cart_rules = $this->getCartRules();     
		foreach ($cart_rules as $value) {
			$this->removeCartRule((int) $value['id_cart_rule']); 
		}

		// se coloca la bandera removeRulesGroup en true, para que retire los descuentos de categoria de los productos de la orden en el metodo getProducts;

		$this->removeRulesGroup = true;
    }

	public function valorExpress($id,$subtotal)
	{
		$sql="SELECT ac.id_address AS id ,
					 express_abajo as abajo,
					 express_arriba as arriba
			FROM ps_carrier_city AS cc
			Inner Join ps_address_city AS ac
			ON ac.id_city=cc.id_city_des
			WHERE id_address=".$id;
		$express=Db::getInstance()->getRow($sql);
		$sql2 = 'SELECT cac.precio_kilo,
						car.id_carrier,
						crp.delimiter2 as umbral
			FROM '._DB_PREFIX_.'address adr
			INNER JOIN '._DB_PREFIX_.'address_city adc ON (adc.id_address=adr.id_address)
			INNER JOIN '._DB_PREFIX_.'carrier_city cac ON (cac.id_city_des = adc.id_city)
			INNER JOIN '._DB_PREFIX_.'carrier car ON (car.id_reference = cac.id_carrier AND car.deleted = 0 AND car.active=1)
			INNER JOIN '._DB_PREFIX_.'range_price crp ON (crp.id_carrier = car.id_carrier)
			WHERE adc.id_address='.$id.'
			ORDER BY cac.precio_kilo';
		$resultado=Db::getInstance()->getRow($sql2);
		if($subtotal>$resultado['umbral'])
		{
			return $express['arriba'];
		}
		else
		{
			return $express['abajo'];
		}
	}
	public function expressProduct(){
		$compare = array();
		$compare2 = array();
		$lista = "(";
		
		if ( $this->_products ) {
			foreach($this->_products as $productos)
			{
				$compare[$productos["id_product"]]=$productos["cart_quantity"];
				$lista = $lista.$productos["id_product"].",";
			}
		}

		$lista = substr($lista, 0, -1).")";
		$sql= 'SELECT sod.id_product AS id, COUNT(sod.id_product)AS cantidad
			FROM `'._DB_PREFIX_.'supply_order_detail` AS sod
			INNER JOIN `'._DB_PREFIX_.'supply_order_icr` AS soi
			ON sod.id_supply_order_detail = soi.id_supply_order_detail
			INNER JOIN `'._DB_PREFIX_.'icr` AS icr
			ON soi.id_icr = icr.id_icr
			WHERE icr.id_estado_icr = 2
			AND sod.id_product IN '.$lista.' GROUP BY sod.id_product;';
		if ( $resultado=Db::getInstance()->executeS($sql) ) {
			foreach($resultado as $res)
			{
				$compare2[$res["id"]]=$res["cantidad"];
			}
		}
		unset($res);
		if(array_diff_key($compare, $compare2))
		{
			return false;
		}
		else
		{
			foreach($compare as $a => $valor)
			{
				$res[$a] = $compare2[$a]-$compare[$a];
				if ($res[$a]<0)
				{
					return false;
				}
			}
			return true;
		}
	}

	public function getSummaryDetails($id_lang = null, $refresh = false)
	{
		$context = Context::getContext();
		if (!$id_lang)
			$id_lang = $context->language->id;

		$delivery = new Address((int)$this->id_address_delivery);
		$invoice = new Address((int)$this->id_address_invoice);

		// New layout system with personalization fields
		$formatted_addresses['delivery'] = AddressFormat::getFormattedLayoutData($delivery);		
		$formatted_addresses['invoice'] = AddressFormat::getFormattedLayoutData($invoice);

		$base_total_tax_inc = $this->getOrderTotal(true);
		$base_total_tax_exc = $this->getOrderTotal(false);
		
		$total_tax = $base_total_tax_inc - $base_total_tax_exc;

		if ($total_tax < 0)
			$total_tax = 0;
		
		$currency = new Currency($this->id_currency);
		
		$products = $this->getProducts($refresh);
		$gift_products = array();
		$cart_rules = $this->getCartRules();
		$total_shipping = $this->getTotalShippingCost();
		$total_shipping_tax_exc = $this->getTotalShippingCost(null, false);
		$total_products_wt = $this->getOrderTotal(true, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING);
		$total_products = $this->getOrderTotal(false, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING);
		$total_discounts = $this->getOrderTotal(true, Cart::ONLY_DISCOUNTS);
		$total_discounts_tax_exc = $this->getOrderTotal(false, Cart::ONLY_DISCOUNTS);
		
		// The cart content is altered for display
		foreach ($cart_rules as &$cart_rule)
		{
			// If the cart rule is automatic (wihtout any code) and include free shipping, it should not be displayed as a cart rule but only set the shipping cost to 0
			if ($cart_rule['free_shipping'] && (empty($cart_rule['code']) || preg_match('/^'.CartRule::BO_ORDER_CODE_PREFIX.'[0-9]+/', $cart_rule['code'])))
			{
				$cart_rule['value_real'] -= $total_shipping;
				$cart_rule['value_tax_exc'] -= $total_shipping_tax_exc;
				$cart_rule['value_real'] = Tools::ps_round($cart_rule['value_real'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
				$cart_rule['value_tax_exc'] = Tools::ps_round($cart_rule['value_tax_exc'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
				if ($total_discounts > $cart_rule['value_real'])
					$total_discounts -= $total_shipping;
				if ($total_discounts_tax_exc > $cart_rule['value_tax_exc'])
					$total_discounts_tax_exc -= $total_shipping_tax_exc;

				// Update total shipping
				$total_shipping = 0;
				$total_shipping_tax_exc = 0;
			}
			if ($cart_rule['gift_product'])
			{
				foreach ($products as $key => &$product)
					if (empty($product['gift']) && $product['id_product'] == $cart_rule['gift_product'] && $product['id_product_attribute'] == $cart_rule['gift_product_attribute'])
					{
						// Update total products
						$giftProductSpecial = explode(",", Configuration::get('PS_GIFTPRODUCTSPECIAL'));
						$giftSpecial = in_array($cart_rule['id_cart_rule'], $giftProductSpecial);
						if ( !$giftSpecial ) {
							$total_products_wt = Tools::ps_round($total_products_wt - $product['price_wt'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						}

						$total_products = Tools::ps_round($total_products - $product['price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						
						// Update total discounts
						$total_discounts = Tools::ps_round($total_discounts - $product['price_wt'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$total_discounts_tax_exc = Tools::ps_round($total_discounts_tax_exc - $product['price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
					
						// Update cart rule value
						$cart_rule['value_real'] = Tools::ps_round($cart_rule['value_real'] - $product['price_wt'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$cart_rule['value_tax_exc'] = Tools::ps_round($cart_rule['value_tax_exc'] - $product['price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						
						// Update product quantity
						$product['total_wt'] = Tools::ps_round($product['total_wt'] - $product['price_wt'], (int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$product['total'] = Tools::ps_round($product['total'] - $product['price'], (int)$currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
						$product['cart_quantity']--;
						
						if (!$product['cart_quantity'])
							unset($products[$key]);
						
						if ( $giftSpecial ) {
							$gift_product = $product;
							$gift_product['cart_quantity'] = 1;
							$gift_product['price'] = $product['price'];
							$gift_product['price_wt'] = $product['price_wt'];
							$gift_product['total_wt'] = $product['price_wt'];
							$gift_product['total'] = $product['price'];
							$gift_product['gift'] = false;
							$this->price_gift_product = $product['price_wt'];
							$gift_products[] = $gift_product;
						} else {
							$gift_product = $product;
							$gift_product['cart_quantity'] = 1;
							$gift_product['price'] = 0;
							$gift_product['price_wt'] = 0;
							$gift_product['total_wt'] = 0;
							$gift_product['total'] = 0;
							$gift_product['gift'] = true;
							$gift_products[] = $gift_product;
						}
						
						break; // One gift product per cart rule
					}
			}
		}

		/********** DESHABILITADO PARA MOSTRAR CUPONES CON VALOR EN CERO - Ewing 
		foreach ($cart_rules as $key => &$cart_rule)
			if ($cart_rule['value_real'] == 0)
				unset($cart_rules[$key]);
		**************/
		
/*echo "<pre>";
print_r($cart_rules);
echo "</pre>";
echo "<hr>";*/

		return array(
			'delivery' => $delivery,
			'delivery_state' => State::getNameById($delivery->id_state),
			'invoice' => $invoice,
			'invoice_state' => State::getNameById($invoice->id_state),
			'formattedAddresses' => $formatted_addresses,
			'products' => array_values($products),
			'gift_products' => $gift_products,
			'discounts' => $cart_rules,
			'is_virtual_cart' => (int)$this->isVirtualCart(),
			'total_discounts' => $total_discounts,
			'total_discounts_tax_exc' => $total_discounts_tax_exc,
			'total_wrapping' => $this->getOrderTotal(true, Cart::ONLY_WRAPPING),
			'total_wrapping_tax_exc' => $this->getOrderTotal(false, Cart::ONLY_WRAPPING),
			'total_shipping' => $total_shipping,
			'total_shipping_tax_exc' => $total_shipping_tax_exc,
			'total_products_wt' => $total_products_wt,
			'total_products' => $total_products,
			'total_price' => $base_total_tax_inc,
			'total_tax' => $total_tax,
			'total_price_without_tax' => $base_total_tax_exc,
			'is_multi_address_delivery' => $this->isMultiAddressDelivery() || ((int)Tools::getValue('multi-shipping') == 1),
			'free_ship' => $total_shipping ? 0 : 1,
			'carrier' => new Carrier($this->id_carrier, $id_lang)
		);
	}

	public function getProducts($refresh = false, $id_product = false, $id_country = null)
	{
		if (!$this->id)
			return array();
		// Product cache must be strictly compared to NULL, or else an empty cart will add dozens of queries
		if ($this->_products !== null && !$refresh)
		{
			// Return product row with specified ID if it exists
			if (is_int($id_product))
			{
				foreach ($this->_products as $product)
					if ($product['id_product'] == $id_product)
						return array($product);
				return array();
			}
			return $this->_products;
		}

		// Build query
		$sql = new DbQuery();

		// Build SELECT
		$sql->select('cp.`id_product_attribute`, cp.`id_product`, cp.`quantity` AS cart_quantity, cp.id_shop, pl.`name`, p.`is_virtual`,
						pl.`description_short`, pl.`available_now`, pl.`available_later`, p.`id_product`, product_shop.`id_category_default`, p.`id_supplier`,
						p.`id_manufacturer`, product_shop.`on_sale`, product_shop.`ecotax`, product_shop.`additional_shipping_cost`,
						product_shop.`available_for_order`, product_shop.`price`, product_shop.`active`, product_shop.`unity`, product_shop.`unit_price_ratio`, 
						stock.`quantity` AS quantity_available, p.`width`, p.`height`, p.`depth`, stock.`out_of_stock`, p.`weight`,
						p.`date_add`, p.`date_upd`, IFNULL(stock.quantity, 0) as quantity, pl.`link_rewrite`, cl.`link_rewrite` AS category,
						CONCAT(LPAD(cp.`id_product`, 10, 0), LPAD(IFNULL(cp.`id_product_attribute`, 0), 10, 0), IFNULL(cp.`id_address_delivery`, 0)) AS unique_id, cp.id_address_delivery,
						product_shop.`wholesale_price`, product_shop.advanced_stock_management, ps.product_supplier_reference supplier_reference, IFNULL(fvl.`value`, "") AS rx');

		// Build FROM
		$sql->from('cart_product', 'cp');

		// Build JOIN
		$sql->leftJoin('product', 'p', 'p.`id_product` = cp.`id_product`');
		$sql->innerJoin('product_shop', 'product_shop', '(product_shop.id_shop=cp.id_shop AND product_shop.id_product = p.id_product)');
		$sql->leftJoin('product_lang', 'pl', '
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$this->id_lang.Shop::addSqlRestrictionOnLang('pl', 'cp.id_shop')
		);

		// Product RX
		$sql->leftJoin('feature_product', 'fp', '( p.`id_product` = fp.`id_product` AND fp.`id_feature` = 11 )');
		$sql->leftJoin('feature_value_lang', 'fvl', 'fp.`id_feature_value` = fvl.`id_feature_value`');

		$sql->leftJoin('category_lang', 'cl', '
			product_shop.`id_category_default` = cl.`id_category`
			AND cl.`id_lang` = '.(int)$this->id_lang.Shop::addSqlRestrictionOnLang('cl', 'cp.id_shop')
		);

		$sql->leftJoin('product_supplier', 'ps', 'ps.id_product=cp.id_product AND ps.id_product_attribute=cp.id_product_attribute AND ps.id_supplier=p.id_supplier');

		// @todo test if everything is ok, then refactorise call of this method
		$sql->join(Product::sqlStock('cp', 'cp'));

		// Build WHERE clauses
		$sql->where('cp.`id_cart` = '.(int)$this->id);
		if ($id_product)
			$sql->where('cp.`id_product` = '.(int)$id_product);
		$sql->where('p.`id_product` IS NOT NULL');

		// Build GROUP BY
		$sql->groupBy('unique_id');

		// Build ORDER BY
		$sql->orderBy('p.id_product, cp.id_product_attribute, cp.date_add ASC');

		if (Customization::isFeatureActive())
		{
			$sql->select('cu.`id_customization`, cu.`quantity` AS customization_quantity');
			$sql->leftJoin('customization', 'cu',
				'p.`id_product` = cu.`id_product` AND cp.`id_product_attribute` = cu.id_product_attribute AND cu.id_cart='.(int)$this->id);
		}
		else
			$sql->select('NULL AS customization_quantity, NULL AS id_customization');

		if (Combination::isFeatureActive())
		{
			$sql->select('
				product_attribute_shop.`price` AS price_attribute, product_attribute_shop.`ecotax` AS ecotax_attr,
				IF (IFNULL(pa.`reference`, \'\') = \'\', p.`reference`, pa.`reference`) AS reference,
				(p.`weight`+ pa.`weight`) weight_attribute,
				IF (IFNULL(pa.`ean13`, \'\') = \'\', p.`ean13`, pa.`ean13`) AS ean13,
				IF (IFNULL(pa.`upc`, \'\') = \'\', p.`upc`, pa.`upc`) AS upc,
				pai.`id_image` as pai_id_image, il.`legend` as pai_legend,
				IFNULL(product_attribute_shop.`minimal_quantity`, product_shop.`minimal_quantity`) as minimal_quantity
			');

			$sql->leftJoin('product_attribute', 'pa', 'pa.`id_product_attribute` = cp.`id_product_attribute`');
			$sql->leftJoin('product_attribute_shop', 'product_attribute_shop', '(product_attribute_shop.id_shop=cp.id_shop AND product_attribute_shop.id_product_attribute = pa.id_product_attribute)');
			$sql->leftJoin('product_attribute_image', 'pai', 'pai.`id_product_attribute` = pa.`id_product_attribute`');
			$sql->leftJoin('image_lang', 'il', 'il.id_image = pai.id_image AND il.id_lang = '.(int)$this->id_lang);
		}
		else
			$sql->select(
				'p.`reference` AS reference, p.`ean13`,
				p.`upc` AS upc, product_shop.`minimal_quantity` AS minimal_quantity'
			);
		$result = Db::getInstance()->executeS($sql);

		// Reset the cache before the following return, or else an empty cart will add dozens of queries
		$products_ids = array();
		$pa_ids = array();
		if ($result)
			foreach ($result as $row)
			{
				$products_ids[] = $row['id_product'];
				$pa_ids[] = $row['id_product_attribute'];
			}
		// Thus you can avoid one query per product, because there will be only one query for all the products of the cart
		Product::cacheProductsFeatures($products_ids);
		Cart::cacheSomeAttributesLists($pa_ids, $this->id_lang);

		$this->_products = array();
		if (empty($result))
			return array();

		$cart_shop_context = Context::getContext()->cloneContext();
		foreach ($result as &$row)
		{
			if (isset($row['ecotax_attr']) && $row['ecotax_attr'] > 0)
				$row['ecotax'] = (float)$row['ecotax_attr'];

			$row['stock_quantity'] = (int)$row['quantity'];
			// for compatibility with 1.2 themes
			$row['quantity'] = (int)$row['cart_quantity'];

			if (isset($row['id_product_attribute']) && (int)$row['id_product_attribute'] && isset($row['weight_attribute']))
				$row['weight'] = (float)$row['weight_attribute'];

			if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice')
				$address_id = (int)$this->id_address_invoice;
			else
				$address_id = (int)$row['id_address_delivery'];
			if (!Address::addressExists($address_id))
				$address_id = null;

			if ($cart_shop_context->shop->id != $row['id_shop'])
				$cart_shop_context->shop = new Shop((int)$row['id_shop']);

			if ($this->_taxCalculationMethod == PS_TAX_EXC)
			{
				$row['price'] = Product::getPriceStatic(
					(int)$row['id_product'],
					false,
					isset($row['id_product_attribute']) ? (int)$row['id_product_attribute'] : null,
					2,
					null,
					false,
					true,
					(int)$row['cart_quantity'],
					false,
					((int)$this->id_customer ? (int)$this->id_customer : null),
					(int)$this->id,
					((int)$address_id ? (int)$address_id : null),
					$specific_price_output,
					true,
					true,
					$cart_shop_context
				); // Here taxes are computed only once the quantity has been applied to the product price

				$row['price_wt'] = Product::getPriceStatic(
					(int)$row['id_product'],
					true,
					isset($row['id_product_attribute']) ? (int)$row['id_product_attribute'] : null,
					2,
					null,
					false,
					true,
					(int)$row['cart_quantity'],
					false,
					((int)$this->id_customer ? (int)$this->id_customer : null),
					(int)$this->id,
					((int)$address_id ? (int)$address_id : null),
					$null,
					true,
					true,
					$cart_shop_context
				);

				$tax_rate = Tax::getProductTaxRate((int)$row['id_product'], (int)$address_id);

				$row['total_wt'] = Tools::ps_round($row['price'] * (float)$row['cart_quantity'] * (1 + (float)$tax_rate / 100), 2);
				$row['total'] = $row['price'] * (int)$row['cart_quantity'];
			}
			else
			{
				$row['price'] = Product::getPriceStatic(
					(int)$row['id_product'],
					false,
					(int)$row['id_product_attribute'],
					2,
					null,
					false,
					true,
					$row['cart_quantity'],
					false,
					((int)$this->id_customer ? (int)$this->id_customer : null),
					(int)$this->id,
					((int)$address_id ? (int)$address_id : null),
					$specific_price_output,
					true,
					true,
					$cart_shop_context
				);

				$row['price_wt'] = Product::getPriceStatic(
					(int)$row['id_product'],
					true,
					(int)$row['id_product_attribute'],
					2,
					null,
					false,
					true,
					$row['cart_quantity'],
					false,
					((int)$this->id_customer ? (int)$this->id_customer : null),
					(int)$this->id,
					((int)$address_id ? (int)$address_id : null),
					$null,
					true,
					true,
					$cart_shop_context
				);
				
				// In case when you use QuantityDiscount, getPriceStatic() can be return more of 2 decimals
				$row['price_wt'] = Tools::ps_round($row['price_wt'], 2);
				$row['total_wt'] = $row['price_wt'] * (int)$row['cart_quantity'];
				$row['total'] = Tools::ps_round($row['price'] * (int)$row['cart_quantity'], 2);
			}

			// si removeRulesGroup es true, se remueven los descuentos de categoria, seteando sus precios a los valores originales
			if ( $this->removeRulesGroup ) {
				$tax = Tax::getProductTaxRate((int)$row['id_product'], (int)$address_id);

				$row['price'] = (int)$row['wholesale_price'];

				$row['price_wt'] = $row['price'] + ( ( $row['price'] * $tax ) / 100 );

				$row['total_wt'] = $row['price_wt'] * (int)$row['cart_quantity'];
				$row['total'] = $row['price'] * (int)$row['cart_quantity'];
			}

			if ( !empty($this->giftProductSpecial) && $this->giftProductSpecial['id_product'] == (int)$row['id_product'] ) {
				$row['price'] = $this->giftProductSpecial['price'];
				$row['price_wt'] = $row['price'] + ( ( $row['price'] * $tax ) / 100 );
				$row['total_wt'] = $row['price_wt'] * (int)$row['cart_quantity'];
				$row['total'] = $row['price'] * (int)$row['cart_quantity'];
			}

			$CartRules = $this->getCartRules();

			if ( !empty($CartRules) && $CartRules[0]['reduction_percent'] != 0 && $CartRules[0]['reduction_product'] > 0 && $CartRules[0]['reduction_product'] == $row['id_product'] ) {

					// se toma el iva a aplicar del producto
					$tax = Tax::getProductTaxRate((int)$row['id_product'], (int)$address_id);
					$priceDiscount = $this->UnitPriceDiscountPercent( $row['price'], $tax, $CartRules[0]['reduction_percent'], true, $row['cart_quantity']);

					$row['price_wt'] = Tools::ps_round( $priceDiscount, 2);
					$row['total_wt'] = $row['price_wt'] * (int)$row['cart_quantity'];

			} elseif ( !empty($CartRules) && $CartRules[0]['reduction_percent'] != 0  && $CartRules[0]['reduction_product'] == 0 ) {

				// si existe un cupon de descuento por porcentaje, se recalculan los valores price_wt y total_wt aplicando el descuento a cada producto
				
				// se toma el iva a aplicar del producto
				$tax = Tax::getProductTaxRate((int)$row['id_product'], (int)$address_id);

				$priceDiscount = $this->UnitPriceDiscountPercent( $row['price'], $tax, $CartRules[0]['reduction_percent'], true, $row['cart_quantity']);

				$row['price_wt'] = Tools::ps_round( $priceDiscount, 2);
				$row['total_wt'] = $row['price_wt'] * (int)$row['cart_quantity'];
			}	

			if (!isset($row['pai_id_image']) || $row['pai_id_image'] == 0)
			{
				$row2 = Db::getInstance()->getRow('
					SELECT image_shop.`id_image` id_image, il.`legend`
					FROM `'._DB_PREFIX_.'image` i
					JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (i.id_image = image_shop.id_image AND image_shop.cover=1 AND image_shop.id_shop='.(int)$row['id_shop'].')
					LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$this->id_lang.')
					WHERE i.`id_product` = '.(int)$row['id_product'].' AND image_shop.`cover` = 1'
				);

				if (!$row2)
					$row2 = array('id_image' => false, 'legend' => false);
				else
					$row = array_merge($row, $row2);
			}
			else
			{
				$row['id_image'] = $row['pai_id_image'];
				$row['legend'] = $row['pai_legend'];
			}

			$row['reduction_applies'] = ($specific_price_output && (float)$specific_price_output['reduction']);
			$row['quantity_discount_applies'] = ($specific_price_output && $row['cart_quantity'] >= (int)$specific_price_output['from_quantity']);
			$row['id_image'] = Product::defineProductImage($row, $this->id_lang);
			$row['allow_oosp'] = Product::isAvailableWhenOutOfStock($row['out_of_stock']);
			$row['features'] = Product::getFeaturesStatic((int)$row['id_product']);

			if (array_key_exists($row['id_product_attribute'].'-'.$this->id_lang, self::$_attributesLists))
				$row = array_merge($row, self::$_attributesLists[$row['id_product_attribute'].'-'.$this->id_lang]);

			$row = Product::getTaxesInformations($row, $cart_shop_context);

			$this->_products[] = $row;
		}

		return $this->_products;
	}

	public function CartQueryExecute($query) {


		$mysqli_1 = mysqli_init();
        $url_post = explode(':', _DB_SERVER_);

        if ( count($url_post) > 1 ) {

          mysqli_real_connect($mysqli_1, $url_post[0], _DB_USER_, _DB_PASSWD_, _DB_NAME_, $url_post[1]);

        } else {

          mysqli_real_connect($mysqli_1, _DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);

        }

        if (mysqli_connect_errno()) {
            return -1;
        }

        if (!mysqli_query($mysqli_1, $query)) {
            return -2;
        }
		return true;
	}

	public function getOrderTotal($with_taxes = true, $type = Cart::BOTH, $products = null, $id_carrier = null, $use_cache = true)
	{
		/************ Progressive Discounts ************/
		$ProgressiveDiscounts = new Progressivediscounts();
		$addProgressiveDiscounts = $ProgressiveDiscounts->addProgressiveDiscount( $this );
		if ( !$addProgressiveDiscounts ) {
			$ProgressiveDiscounts->removeResidueProgressiveDiscount();
		}

		if (!$this->id) {
			return 0;
		}

		$type = (int)$type;
		$array_type = array(
			Cart::ONLY_PRODUCTS,
			Cart::ONLY_DISCOUNTS,
			Cart::BOTH,
			Cart::BOTH_WITHOUT_SHIPPING,
			Cart::ONLY_SHIPPING,
			Cart::ONLY_WRAPPING,
			Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING,
			Cart::ONLY_PHYSICAL_PRODUCTS_WITHOUT_SHIPPING,
		);
		
		// Define virtual context to prevent case where the cart is not the in the global context
		$virtual_context = Context::getContext()->cloneContext();
		$virtual_context->cart = $this;

		


		if (!in_array($type, $array_type))
			die(Tools::displayError());

		$with_shipping = in_array($type, array(Cart::BOTH, Cart::ONLY_SHIPPING));

		
		
		// if cart rules are not used
		if ($type == Cart::ONLY_DISCOUNTS && !CartRule::isFeatureActive()) {
			return 0;
		}


		// no shipping cost if is a cart with only virtuals products
		$virtual = $this->isVirtualCart();
		if ($virtual && $type == Cart::ONLY_SHIPPING) {
			return 0;
		}




		if ($virtual && $type == Cart::BOTH)
			$type = Cart::BOTH_WITHOUT_SHIPPING;

		if ($with_shipping)
		{
			
			if (is_null($products) && is_null($id_carrier)){

				$shipping_fees = $this->getTotalShippingCost(null, (boolean)$with_taxes);
			
		
			}else {
				$shipping_fees = $this->getPackageShippingCost($id_carrier, (int)$with_taxes, null, $products);
			}
				
		} else {
			$shipping_fees = 0;
			}
	

		if ($type == Cart::ONLY_SHIPPING) {
			return $shipping_fees;
		}

		/*if ($type == Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING){
			$type = Cart::ONLY_PRODUCTS;}*/

		$param_product = true;
		if (is_null($products))
		{
			$param_product = false;
			$products = $this->getProducts();
		}

//----------------------  INICIO  ----------------------------   NEVERAS   -------------------------------------  EWING  -------------//

			//////error_log("|override getOrderTotal prods:|".count($products).'-|-'.debug_backtrace()[1]['class'], 0);
			//////error_log(print_r($products,true), 0);

			
		if (  /*$this->id_customer == 250 && */ !is_null($products) && count($products) > 0 
			&& ( isset(debug_backtrace()[1]['class']) && debug_backtrace()[1]['class'] != "CartRule" && debug_backtrace()[1]['function'] != "getContextualValue") 
			&& ( isset(debug_backtrace()[1]['class']) && debug_backtrace()[1]['class'] != "CartRuleCore" && debug_backtrace()[1]['function'] != "checkValidity") ) {

			$al_log = "\n";

			//////--$al_log .= ("in c:".debug_backtrace()[1]['class']."-f:".debug_backtrace()[1]['function']);
			
			$nevBD = array();

			$nevera1 = Configuration::get('PS_ID_NEVERA_CIUD_PRIN_1_3');
			$nevera2 = Configuration::get('PS_ID_NEVERA_CIUD_PRIN_4_12');
			$nevera3 = Configuration::get('PS_ID_NEVERA_CIUD_ALED_1_9');

			$nevBD[ $nevera1 ] = 0; 
			$nevBD[ $nevera2 ] = 0; 
			$nevBD[ $nevera3 ] = 0; 

			$cantpros_refrigerados = 0;

			if ( $this->id != 0 && $this->id != '' ) {
				$query_neveras_cart = "SELECT id_product, quantity FROM ps_cart_product cpp
					WHERE cpp.id_cart = ".$this->id."
					AND id_product IN (".$nevera1.",".$nevera2.",".$nevera3.")";					

				if ( $result_neveras_cart = Db::getInstance()->ExecuteS($query_neveras_cart) ) {
					foreach ($result_neveras_cart as $key => $value) {	

						if ( array_key_exists( $value['id_product'], $nevBD ) ) {

							$nevBD[ $value['id_product'] ] = $value['quantity'];
						}
					}
				}
			}


			foreach ($products as $key => $product) {

				if ( ( $product["id_product"] != $nevera1 && 
					$product["id_product"] != $nevera2 &&
					$product["id_product"] != $nevera3 ) && (( stripos($product['name'], "rx3") ) !== false ) ) {

					$cantpros_refrigerados += $product["cart_quantity"];
				} 
			}

			//////--$al_log .= ("- cant_ref:".$cantpros_refrigerados);

			if ( $cantpros_refrigerados != 0 ) {

				$al_log .= ("c:".$this->id."-");
				//////--usleep(200000); // para dar tiempo de quitar el producto
				$alenum = rand(0,99);
				$al_log .= ("-N:".$alenum );

				$nevera1_cant = Configuration::get('PS_NEVERA_CIUD_PRIN_1CANT');
				$nevera2_cant = Configuration::get('PS_NEVERA_CIUD_PRIN_2CANT');
				$nevera3_cant = Configuration::get('PS_NEVERA_CIUD_ALED_CANT');


				$ciud_prin_envio = 1;

				if ( $this->id_address_delivery != 0 && $this->id_address_delivery != null) {

					$query_ciud_princ = "SELECT /*adr.id_address, adr.id_city,*/ cn.id_city AS principal
											FROM ps_address_city adr
											LEFT JOIN ps_city_nevera cn ON ( adr.id_city = cn.id_city )										
											WHERE adr.id_address =".$this->id_address_delivery;

					if( $resul_CP = Db::getInstance()->ExecuteS($query_ciud_princ) ) {

						$ciud_prin_envio = ( $resul_CP[0]["principal"] != NULL ) ? 1 : 0;

						$al_log .= ("-adr: |".$this->id_address_delivery."| --cp: ".$ciud_prin_envio."|");

					}

				}

				$cant_add_cp_n1 = 0;  // neveras tipo 1 a comprar
				$cant_add_cp_n2 = 0;  // neveras tipo 2 a comprar
				$cant_add_cf = 0;  // neveras tipo 3 a comprar


				if ( $ciud_prin_envio == 1 && $cantpros_refrigerados > 0 && $cantpros_refrigerados <= $nevera1_cant  ) {
					$al_log .= ("- CP ");

					$cant_add_cp_n1 = 1;

				} elseif ( $ciud_prin_envio == 1 && $cantpros_refrigerados > $nevera1_cant ) {
					$al_log .= ("- CP2 ");

					$resid = $cantpros_refrigerados % $nevera2_cant;
					$divid = (int)($cantpros_refrigerados / $nevera2_cant);

					$cant_add_cp_n2 = $divid;

					if ( $resid > $nevera1_cant) {
						$cant_add_cp_n2 += 1;
						$cant_add_cp_n1 = 0;
					} elseif ( $resid > 0 && $resid <= $nevera1_cant ) {
						$cant_add_cp_n1 = 1;
					}	

					$al_log .= ("-ref 1 mod : ".$resid." - div: ".$divid);
					$al_log .= ("-ref 1 ped : ".$cant_add_cp_n1." - div: ".$cant_add_cp_n2);

				} elseif ( $ciud_prin_envio == 0 &&  $cantpros_refrigerados > 0 ) {
					$al_log .= ("- CF ");
					$resid = $cantpros_refrigerados % $nevera3_cant;
					$divid = (int)($cantpros_refrigerados / $nevera3_cant);
					////----$al_log .= ("-refri 2 mod : ".$resid." - div: ".$divid);
					
					$cant_add_cf = $divid;

					if ( $resid > 0 ) {
						$cant_add_cf += 1;
					}

					$al_log .= ("-ref 2 ped : ".$resid." - div: ".$divid);

				}

				$nev_new[ $nevera1 ] = $cant_add_cp_n1; 
				$nev_new[ $nevera2 ] = $cant_add_cp_n2; 
				$nev_new[ $nevera3 ] = $cant_add_cf; 


				foreach ( $nevBD as $key => $value ) {

					if ( $nev_new[ $key ] != 0 || $nevBD[ $key ] != 0 ) {

						$qr_nev = "";

						$al_log .= ( " p: ".$key." - n:".$nev_new[ $key ]." - bd:".$nevBD[ $key ] );

						if ( $nev_new[ $key ] == 0 && $nevBD[ $key ] != 0 ) {
							
							$qr_nev = 'DELETE FROM ps_cart_product WHERE id_cart = '.$this->id.' AND id_product = '.$key;

						} 

						if ( $nev_new[ $key ] != 0 && $nev_new[ $key ] != $nevBD[ $key ] && $nevBD[ $key ] != 0 ) {
							
							$qr_nev = "UPDATE ps_cart_product SET quantity = ".$nev_new[ $key ]." WHERE id_cart = ".$this->id." AND id_product = ".$key;	

						} 

						if ( $nev_new[ $key ] != 0 && $nevBD[ $key ] == 0 ) {
							
							$qr_nev = "INSERT INTO ps_cart_product (id_cart, id_product, id_address_delivery, id_shop, id_product_attribute, quantity, date_add) VALUES ( '".$this->id."', '".$key."', '".$this->id_address_delivery."', '".$this->id_shop."', '0', '".$nev_new[ $key ]."', '".date("Y-m-d H:i:s")."')";	


						}
						
							$al_log .= $qr_nev;

						if ( $qr_nev != "" ) {

							if ( !Db::getInstance()->execute($qr_nev) /*!$this->CartQueryExecute($qr_nev)*/ ) {
								$al_log .= '_RNO';
							} else {
								$al_log .= '_RSI';
							}

						}
					}

				}
				error_log($al_log, 3, _ROUTE_FILE_."/nevera_error.log");
				
			}

			/*$fp = fopen('/home/ubuntu/nevera_error.log', 'a');
			fwrite($fp, $al_log);
			fclose($fp);*/

			//error_log( print_r( debug_backtrace(), true ), 3, "/home/ubuntu/nevera_error.log");
			//error_log($alenum."-------------------------------------------------------\n", 3, "/home/ubuntu/nevera_error.log");
			//error_log($al_log);
		}


		//----------------------  FIN  ----------------------------   NEVERAS   -------------------------------------  EWING  -------------//

		




//----------------------  INICIO  ----------------------------   ABBOTT   -------------------------------------  EWING  -------------//

			//////error_log("|override getOrderTotal prods:|".count($products).'-|-'.debug_backtrace()[1]['class'], 0);
			//////error_log(print_r($products,true), 0);

			
		if (  /*$this->id_customer == 250 && */ !is_null($products) && count($products) > 0 
			&& ( isset(debug_backtrace()[1]['class']) && debug_backtrace()[1]['class'] != "CartRule" && debug_backtrace()[1]['function'] != "getContextualValue") 
			&& ( isset(debug_backtrace()[1]['class']) && debug_backtrace()[1]['class'] != "CartRuleCore" && debug_backtrace()[1]['function'] != "checkValidity") ) {

			$al_log = "\n";

			//////--$al_log .= ("in c:".debug_backtrace()[1]['class']."-f:".debug_backtrace()[1]['function']);
			
			$nevBD = array();

			$pabbottflete = 39492;//39492; //cobro flete abbott

			$pabbottsensor = 39473; //Sensor * 2
			$pabbottlector = 39474; //Lector * 1

			$abtBD[ $pabbottflete ] = 0; 

			$cantpros_sensor = 0;
			$cantpros_lector = 0;

			$cantmax_sensor = 2;
                        $cantmax_lector = 1;


			if ( $this->id != 0 && $this->id != '' ) {
				$query_pabbotts_cart = "SELECT id_product, quantity FROM ps_cart_product cpp
					WHERE cpp.id_cart = ".$this->id."
					AND id_product IN (".$pabbottflete.")";					

				if ( $result_pabbotts_cart = Db::getInstance()->ExecuteS($query_pabbotts_cart) ) {
					foreach ($result_pabbotts_cart as $key => $value) {	

						if ( array_key_exists( $value['id_product'], $abtBD ) ) {

							$abtBD[ $value['id_product'] ] = $value['quantity'];
						}
					}
				}
			}


			foreach ($products as $key => $product) {

				if ( ( $product["id_product"] != $pabbottflete ) 
						&& ( $product["id_product"] ==  $pabbottsensor ) ) { // Validar sensor y cantidad

					$cantpros_sensor += $product["cart_quantity"];

				} elseif ( ( $product["id_product"] != $pabbottflete ) 
						&& ( $product["id_product"] ==  $pabbottlector ) ) { // validar lector y cantidad

					$cantpros_lector += $product["cart_quantity"];
				}
			}

			//////--$al_log .= ("- cant_ref:".$cantpros_refrigerados);

			if ( $cantpros_sensor != 0 ||  $cantpros_lector != 0  || $abtBD[ $pabbottflete ] != 0 ) {

				$al_log .= ("c:".$this->id."-");
				//////--usleep(200000); // para dar tiempo de quitar el producto
				$alenum = rand(0,99);
				$al_log .= ("-N:".$alenum );

					$qr_nev = "";

					if ( ( $cantpros_sensor < 2 || $cantpros_lector < 1 ) && 
						( ( $cantpros_sensor != 2 || $cantpros_lector != 1 ) && $abtBD[ $pabbottflete ] == 0 ) ) {
						
						$qr_nev = "INSERT INTO ps_cart_product (id_cart, id_product, id_address_delivery, id_shop, id_product_attribute, quantity, date_add) VALUES ( '".$this->id."', '".$pabbottflete."', '".$this->id_address_delivery."', '".$this->id_shop."', '0', '1', '".date("Y-m-d H:i:s")."')";
					}

					if ( $cantpros_sensor >= 2 && $cantpros_lector >= 1 && $abtBD[ $pabbottflete ] != 0 ) {
						
						$qr_nev = 'DELETE FROM ps_cart_product WHERE id_cart = '.$this->id.' AND id_product = '.$pabbottflete;

					} 
					/*elseif ( $cantpros_sensor != 2 || $cantpros_lector != 1  && $abtBD[ $pabbottflete ] == 0  ) {
						
						$qr_nev = "UPDATE ps_cart_product SET quantity = 1 WHERE id_cart = ".$this->id." AND id_product = ".$pabbottflete;	

					} */
					
						$al_log .= $qr_nev;

					if ( $qr_nev != "" ) {

						if ( !Db::getInstance()->execute($qr_nev) /*!$this->CartQueryExecute($qr_nev)*/ ) {
							$al_log .= '_RNO';
						} else {
							$al_log .= '_RSI';
						}

					}

					$qr_nev_lector = "";

					if ( $cantpros_lector > $cantmax_lector ) {
						
						$qr_nev_lector = "UPDATE ps_cart_product SET quantity = ".$cantmax_lector." WHERE id_cart = ".$this->id." AND id_product = ".$pabbottlector;	

					}
					
						$al_log .= $qr_nev_lector;

					if ( $qr_nev_lector != "" ) {

						if ( !Db::getInstance()->execute($qr_nev_lector) /*!$this->CartQueryExecute($qr_nev)*/ ) {
							$al_log .= '_RLNO';
						} else {
							$al_log .= '_RLSI';
						}

					}


					$qr_nev_sensor = "";

					if ( $cantpros_sensor > $cantmax_sensor ) {
						
						$qr_nev_sensor = "UPDATE ps_cart_product SET quantity = ". $cantmax_sensor." WHERE id_cart = ".$this->id." AND id_product = ".$pabbottsensor;	

					}
					
						$al_log .= $qr_nev_sensor;

					if ( $qr_nev_sensor != "" ) {

						if ( !Db::getInstance()->execute($qr_nev_sensor) /*!$this->CartQueryExecute($qr_nev)*/ ) {
							$al_log .= '_RSNO';
						} else {
							$al_log .= '_RSSI';
						}

					}
				}
				

				
				error_log($al_log, 3, _ROUTE_FILE_."/pabbott_error.log");
				
			

			/*$fp = fopen('/home/ubuntu/pabbott_error.log', 'a');
			fwrite($fp, $al_log);
			fclose($fp);*/

			//error_log( print_r( debug_backtrace(), true ), 3, "/home/ubuntu/pabbott_error.log");
			//error_log($alenum."-------------------------------------------------------\n", 3, "/home/ubuntu/pabbott_error.log");
			//error_log($al_log);
		}


		//----------------------  FIN  ----------------------------   ABBOTT   -------------------------------------  EWING  -------------//		



		if ($type == Cart::ONLY_PHYSICAL_PRODUCTS_WITHOUT_SHIPPING)
		{
			foreach ($products as $key => $product)
				if ($product['is_virtual'])
					unset($products[$key]);
			$type = Cart::ONLY_PRODUCTS;
		}



		$order_total = 0;
		if (Tax::excludeTaxeOption()){
			$with_taxes = false;
		}

		$order_total_discount = 0;

		// inicializacion de variables
		$CartRules = "";
		$ReductionPercent = 0;
		$ReductionAmount = 0;
		$GenerateReduction = false;
		$totalTaxProducts = 0;
		$totalPriceIniProducts = 0;
		$totalProductWT = 0;
		$totalTax      = 0;
		$price = 0;

		// se toman las reglas de carrito
		if ( in_array($type, array(Cart::BOTH, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING, Cart::ONLY_DISCOUNTS)) )
		{
			$CartRules = $this->getCartRules();

			// se toman los valores de descuento
			if ( !empty($CartRules) ) {
				$ReductionPercent = $CartRules[0]['reduction_percent'];
				$ReductionAmount = $CartRules[0]['reduction_amount'];
			}
		}

		// si no existe regla de carrito o la regla de carrito aplicada es igual a 0 se realiza el calculo total de la orden normal
		if ( ( $ReductionPercent == 0 && $ReductionAmount == 0 ) || $ReductionAmount != 0 ) {
			// bandera que indica que se aplique descuento monetario
			$GenerateReduction = true;
			foreach ($products as $product) // products refer to the cart details
			{
				if ($virtual_context->shop->id != $product['id_shop']) {
					$virtual_context->shop = new Shop((int)$product['id_shop']);
				}

				if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') 
				{
					$address_id = (int)$this->id_address_invoice;
				}
				else
				{
					$address_id = (int)$product['id_address_delivery']; // Get delivery address of the product from the cart
				}

				if (!Address::addressExists($address_id)) {
					$address_id = null;
				}

				
				if ($this->_taxCalculationMethod == PS_TAX_EXC)
				{
					// Here taxes are computed only once the quantity has been applied to the product price
					$price = Product::getPriceStatic(
						(int)$product['id_product'],
						false,
						(int)$product['id_product_attribute'],
						2,
						null,
						false,
						true,
						$product['cart_quantity'],
						false,
						(int)$this->id_customer ? (int)$this->id_customer : null,
						(int)$this->id,
						$address_id,
						$null,
						true,
						true,
						$virtual_context
					);

					$total_ecotax = $product['ecotax'] * (int)$product['cart_quantity'];
					$total_price = $price * (int)$product['cart_quantity'];

					if ($with_taxes)
					{
						$product_tax_rate = (float)Tax::getProductTaxRate((int)$product['id_product'], (int)$address_id, $virtual_context);
						$product_eco_tax_rate = Tax::getProductEcotaxRate((int)$address_id);

						$total_price = ($total_price - $total_ecotax) * (1 + $product_tax_rate / 100);
						$total_ecotax = $total_ecotax * (1 + $product_eco_tax_rate / 100);
						$total_price = Tools::ps_round($total_price + $total_ecotax, 2);
					}

				}
				else
				{
					if ($with_taxes) 
					{
						$price = Product::getPriceStatic(
							(int)$product['id_product'],
							true,
							(int)$product['id_product_attribute'],
							2,
							null,
							false,
							true,
							$product['cart_quantity'],
							false,
							((int)$this->id_customer ? (int)$this->id_customer : null),
							(int)$this->id,
							((int)$address_id ? (int)$address_id : null),
							$null,
							true,
							true,
							$virtual_context
						);
					}
					else
					{
						$price = Product::getPriceStatic(
							(int)$product['id_product'],
							true,
							(int)$product['id_product_attribute'],
							2,
							null,
							false,
							true,
							$product['cart_quantity'],
							false,
							((int)$this->id_customer ? (int)$this->id_customer : null),
							(int)$this->id,
							((int)$address_id ? (int)$address_id : null),
							$null,
							true,
							true,
							$virtual_context
						);
					}

					// se acumulan los valores del iva de los productos para ser sumados al total de la orden, en caso de que el total de la orden sea 0 al apicar un descuento monetario
					if ( $ReductionAmount != 0 ) {
						$totalPriceIniProducts += $product['price'] * (int)$product['cart_quantity'];
						$totalTaxProducts += ( ( $product['price'] * $product['rate'] ) / 100 ) * (int)$product['cart_quantity'];
						$totalProductWT += $price * (int)$product['cart_quantity'];
					}

					// si removeRulesGroup es true, se remueven los descuentos de categoria, seteando sus precios a los valores originales
					if ( $this->removeRulesGroup ) {
						$price = (int)$product['wholesale_price'];
						$price += ( $price * $product['rate'] ) / 100;
						$GenerateReduction = false;
					}

					$total_price = Tools::ps_round($price * (int)$product['cart_quantity'], 2);
				}

				$order_total += $total_price;
				$totalTax += $this->UnitPriceDiscountPercent($product['price'],  $product['rate'], 0 , false, (int)$product['cart_quantity'], false, true); 
			}
			
			if($ReductionPercent == 0 && $type == 2){
				$this->add_total_tax($totalTax);
			}
		}
		// si existe cupon de descuento por porcentaje se genera calculo aplicando correctamente el descuento
		elseif ( $ReductionPercent != 0 && $CartRules[0]['reduction_product'] > 0 )
		{
			foreach ($products as $product) // products refer to the cart details
			{
				if ( $CartRules[0]['reduction_product'] == $product['id_product'] ) {

					if ( $type == Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING ) {
						$price = $this->UnitPriceDiscountPercent(  $product['price'], $product['rate'], $ReductionPercent, true, (int)$product['cart_quantity'] );
					} else {
						$price = $this->UnitPriceDiscountPercent(  $product['price'], $product['rate'], $ReductionPercent, false, (int)$product['cart_quantity'] );
					}

					$order_total_discount += $this->UnitPriceDiscountPercent( $product['price'],  $product['rate'], $ReductionPercent, false, (int)$product['cart_quantity'], true);
				} else {
					$price = $this->UnitPriceDiscountPercent(  $product['price'], $product['rate'], 0, false, (int)$product['cart_quantity'] );
				}

				// se agrupa el total de la orden con el valor total de cada producto
				$total_price = Tools::ps_round($price * (int)$product['cart_quantity'], 2);
				$order_total += $total_price;
				$totalTax += $this->UnitPriceDiscountPercent($product['price'],  $product['rate'], $ReductionPercent, false, (int)$product['cart_quantity'], false, true); 
			}
			if($type == 2){
			$this->add_total_tax($totalTax);
		}
			
		}
		elseif ( $ReductionPercent != 0  && $CartRules[0]['reduction_product'] == 0 )
		{ 
			foreach ($products as $product) // products refer to the cart details
			{
				// si el tipo de valor a retornar es ONLY_PRODUCTS se toman los valores como tipo show, si no, se toman los valores de del calculo para el total de la orden
				if ( $type == Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING ) {
					$price = $this->UnitPriceDiscountPercent(  $product['price'], $product['rate'], $ReductionPercent, true, (int)$product['cart_quantity'] );
				} else {
					$price = $this->UnitPriceDiscountPercent(  $product['price'], $product['rate'], $ReductionPercent, false, (int)$product['cart_quantity'] );
				}

				// se agrupa el total de la orden con el valor total de cada producto
				$total_price = Tools::ps_round($price * (int)$product['cart_quantity'], 2);
				$order_total += $total_price;

				// se agrupa el total de descuento aplicado a la orden, sumando el descuento de cada producto
				$order_total_discount += $this->UnitPriceDiscountPercent( $product['price'],  $product['rate'], $ReductionPercent, false, (int)$product['cart_quantity'], true);

				$totalTax += $this->UnitPriceDiscountPercent($product['price'],  $product['rate'], $ReductionPercent, false, (int)$product['cart_quantity'], false, true); 
			}

			if($type == 2){
			$this->add_total_tax($totalTax);
		}
		}

		$order_total_products = $order_total;

		if ($type == Cart::ONLY_DISCOUNTS) {
			$order_total = 0;
		}

		// Wrapping Fees
		$wrapping_fees = 0;
		if ($this->gift) {
			$wrapping_fees = Tools::convertPrice(Tools::ps_round($this->getGiftWrappingPrice($with_taxes), 2), Currency::getCurrencyInstance((int)$this->id_currency));
		}
		if ($type == Cart::ONLY_WRAPPING) {
			return $wrapping_fees;
		}

		// se aplica el descuento normal si el cupon de descuento es 0, si no existe cupon o si el descuento amount es diferente de 0
		if ( $GenerateReduction ) {
			if (!in_array($type, array(Cart::ONLY_SHIPPING, Cart::ONLY_PRODUCTS)) && CartRule::isFeatureActive())
			{
				// First, retrieve the cart rules associated to this "getOrderTotal"
				if ($with_shipping || $type == Cart::ONLY_DISCOUNTS) {
					$cart_rules = $this->getCartRules(CartRule::FILTER_ACTION_ALL);
				}
				else
				{
					$cart_rules = $this->getCartRules(CartRule::FILTER_ACTION_REDUCTION);
					// Cart Rules array are merged manually in order to avoid doubles
					foreach ($this->getCartRules(CartRule::FILTER_ACTION_GIFT) as $tmp_cart_rule)
					{
						$flag = false;
						foreach ($cart_rules as $cart_rule)
							if ($tmp_cart_rule['id_cart_rule'] == $cart_rule['id_cart_rule'])
								$flag = true;
						if (!$flag)
							$cart_rules[] = $tmp_cart_rule;
					}
				}
				
				$id_address_delivery = 0;
				if (isset($products[0]))
					$id_address_delivery = (is_null($products) ? $this->id_address_delivery : $products[0]['id_address_delivery']);
				$package = array('id_carrier' => $id_carrier, 'id_address' => $id_address_delivery, 'products' => $products);
				
				// Then, calculate the contextual value for each one
				foreach ($cart_rules as $cart_rule)
				{
					// If the cart rule offers free shipping, add the shipping cost
					if (($with_shipping || $type == Cart::ONLY_DISCOUNTS) && $cart_rule['obj']->free_shipping){
						$order_total_discount += Tools::ps_round($cart_rule['obj']->getContextualValue($with_taxes, $virtual_context, CartRule::FILTER_ACTION_SHIPPING, ($param_product ? $package : null), $use_cache), 2);
					}

				
					// If the cart rule is a free gift, then add the free gift value only if the gift is in this package
					if ((int)$cart_rule['obj']->gift_product)
					{
						$in_order = false;
						if (is_null($products)) {
							$in_order = true;
						} else {
							foreach ($products as $product) {
								if ($cart_rule['obj']->gift_product == $product['id_product'] && $cart_rule['obj']->gift_product_attribute == $product['id_product_attribute']) {
									$in_order = true;
								}
							}
						}
						if ($in_order) {
							$order_total_discount += $cart_rule['obj']->getContextualValue($with_taxes, $virtual_context, CartRule::FILTER_ACTION_GIFT, $package, $use_cache);
							
							/***************** dto a 0 si id_cart esta en config de cobrar regalo *****************/

							$giftProductSpecial = explode(",", Configuration::get('PS_GIFTPRODUCTSPECIAL'));

							if ( in_array($cart_rule['obj']->id, $giftProductSpecial) ) {
								$PrecioDescontar = Db::getInstance()->getRow("SELECT
									p.id_product,
									p.price,
									t.rate,
									ROUND(p.price + ( ( p.price * IF(t.rate IS NULL,0,t.rate) ) / 100 ) ) AS price_total 
								FROM ps_product p
								LEFT JOIN ps_tax t
								ON p.id_tax_rules_group = t.id_tax
								WHERE id_product =".(int)$cart_rule['obj']->gift_product);
								
								$order_total_discount -= $PrecioDescontar['price_total'];
							}

							/***************** dto a 0 si id_cart esta en config de cobrar regalo ****************/

						}
					}

					// If the cart rule offers a reduction, the amount is prorated (with the products in the package)
					if ($cart_rule['obj']->reduction_percent > 0 || $cart_rule['obj']->reduction_amount > 0) {
						$order_total_discount = Tools::ps_round($cart_rule['obj']->getContextualValue($with_taxes, $virtual_context, CartRule::FILTER_ACTION_REDUCTION, $package, $use_cache), 2);

						if ( $cart_rule['obj']->reduction_amount > 0 ) {
							$order_total_discount = Tools::ps_round($cart_rule['obj']->getContextualValue(true, $virtual_context, CartRule::FILTER_ACTION_REDUCTION, $package, $use_cache), 2);
						}

						$isTaxIncl = false;
						if ( $cart_rule['obj']->reduction_amount > 0 && $cart_rule['obj']->reduction_tax == 1 ) {
							$isTaxIncl = true;
							$order_total_discount = $cart_rule['obj']->reduction_amount;
						}
					}
					$order_total_discount = min(Tools::ps_round($order_total_discount, 2), $wrapping_fees + $order_total_products + $shipping_fees);
					
					$giftProductSpecial = explode(",", Configuration::get('PS_GIFTPRODUCTSPECIAL'));
					if ( !in_array((int)$cart_rule['obj']->id, $giftProductSpecial) ) {
						$order_total -= $order_total_discount;
					}
				}


				$totalPriceProducts = $order_total;

				// se suma el iva acumulado de los pruductos al total de la orden, solo si al aplicar un cupon de descuento monetario el total es 0
				if ( $ReductionAmount != 0 && $order_total <= 0 ) {

					// si el descuento del cupon monetario es mayor al descuento posible, se toma como descuento el valor del descuento posible y no el descuento del cupon
					// el descuento posible, es el acumulado de los precios de los productos (iva excl.) del carrito
					if ( $totalPriceIniProducts < $ReductionAmount && !$isTaxIncl ) {
						$order_total_discount = $totalPriceIniProducts;
					}

					if ( !$isTaxIncl ) {
						$order_total = 0;
						$order_total += $totalTaxProducts;
					}
				}

			}
		}

		if ($type == Cart::BOTH) {

			if ( $order_total <= 0 ) {
				$order_total = 0;
			}
			
			$order_total += $shipping_fees + $wrapping_fees;
		}

		if ($order_total < 0 && $type != Cart::ONLY_DISCOUNTS) {
			return 0;
		}
		
		if ($type == Cart::ONLY_DISCOUNTS) {
			$giftProductSpecial = explode(",", Configuration::get('PS_GIFTPRODUCTSPECIAL'));
			foreach ($cart_rules as $cart_rule){
				if ( in_array((int)$cart_rule['obj']->id, $giftProductSpecial) ) {
					return 0;
				} else {
					return $order_total_discount;
				}
			}
		}
		if ( $type == Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING && $ReductionAmount != 0 ) {
			$order_total = $totalProductWT;
		}

		return Tools::ps_round((float)$order_total, 2);
	}


	public function add_total_tax($totalTax){

			$rs = Db::getInstance()->update('cart',
		                   array(
		                         'total_tax' => $totalTax,
		                         ),
		                   'id_cart = '.(int)$this->id);
	}
    
    /**
     * [getOrderTotalPaid Para devolver el valor pagado de la orden creada]
     * @return [float] [retorna el valor de la tabla order]
     */
     public function getOrderTotalPaid() {

		if (!$this->id)
			return 0;
		
		$row2 = Db::getInstance()->getRow('SELECT total_paid FROM `'._DB_PREFIX_.'orders` WHERE id_cart = '.(int)$this->id	);

		if (!$row2) {
			return 0;
		} else {
			return $row2['total_paid'];
		}

	}

	/**
	 * [validationaddressfarmalisto Para validar si la direccion de entrega, es la oficina de farmalisto]
	 * @return [bool] $validateaddress [true si la direccion de entrega es la oficina de farmalisto, si no se retorna false]
	 */
	public function validationaddressfarmalisto(){

		// se crea objeto address para tomar la dirección seleccionada de entrega
		$address = new Address($this->id_address_delivery);
		$cityact = strtoupper($address->city);
        $addressact = strtoupper($address->address1);

        // se inicializa en false la variable a retornar
        $validateaddress = false;

        // se crea arreglo con las direcciones de entrega validas para aplicar envio 0
        $addressesoficina = array(
            'CALLE 129A NO. 56B - 23',
            'CALLE 129A NUMERO 56B - 23',
            'CALLE 129A # 56B - 23',
            'CALLE 129A NO. 56B 23',
            'CALLE 129A NUMERO 56B 23',
            'CALLE 129A # 56B 23',
            'CALLE 129A NO. 56B-23',
            'CALLE 129A NUMERO 56B-23',
            'CALLE 129A # 56B-23'
        );

        // valida que la ciudad de entrega sea bogota
        if (trim(strtoupper($cityact)) == trim(strtoupper('BOGOTá, D.C.'))){

        	// se recorre arreglo de direcciones validas
            foreach ($addressesoficina as $addresofi) {

            	// se valida que la direccion de entrega, sea igual a la direccion del arreglo de direcciones validas
                if (trim($addresofi) == trim(strtoupper($addressact))){

                	// si son iguales, se toma como true la variable a retornar
                    $validateaddress = true;
                }
            }
        }
        return $validateaddress;
    }
    
    /**
	 * [UnitPriceDiscountPercent Para retornar el valor unitario del producto aplicando el respectivo descuento]
	 * @param [int] $price           [Valor inicial del producto]
	 * @param [int] $tax             [% IVA del producto]
	 * @param [int] $discountPercent [% Descuento a aplicar]
	 * @param [bool] $priceShow      [flag para retornar precio a mostrar]
	 * @param [bool] $quantity       [cantidad de productos en carrito]
	 * @param [bool] $showDiscount   [flag para retornar unicarmente el desucuento aplicado por producto]
	 * @return [int] $unitPrice      [valor final unitario del producto]
	 */
	public function UnitPriceDiscountPercent( $price, $tax, $discountPercent, $priceShow, $quantity, $showDiscount = false, $showTaxDiscount = false) {

       // discount almacena descuento aplicado al precio inicial del producto
       $discount = ($price * $discountPercent) / 100;

       // si showDiscount es true, se retorna solamente el descuento de cada producto
       if ( $showDiscount ) {
           return ( $discount * $quantity );
       }

       // priceDiscount almacena el precio inicial del producto con el descuento aplicado
       $priceDiscount = $price - $discount;

       // taxDiscount almacena el iva del producto con el descuento aplicado
       $taxDiscount = ( ( $priceDiscount * $tax ) / 100 );

       // retorna unicamente el iva del producto con el descuento aplicado
       if ( $showTaxDiscount ) {
           return $taxDiscount * $quantity; 
       }

       if ( $priceShow ) {
           // se suma el precio inicial del producto para ser mostrado correctamente en la vista
           $unitPrice = $price + $taxDiscount;
       } else {
           // se suma el precio con descuento para generar el total de la orden correctamente
           $unitPrice = $priceDiscount + $taxDiscount;
       }

       return $unitPrice;
   }

	/**
	 * [GetProductsCartReductionCategory Para retornar true si en el carrito se encuentra algun producto con cupon de descuento por categoria]
	 * @return [bool] $validationReductionCategory [true si se encuentra un producto del carrito con cupon de descuento]
	 */
	public function GetProductsCartReductionCategory( $ruleAdding ) {

		$validationReductionCategory = false;

		// se valida si el cupon a agregar es 0, si esto se cumple, se retorna como falso para que permita agregar el cupon en 0
		$carRule = new CartRule();
		$ruleIs0 = $carRule->getCartRuleDetail( $ruleAdding );
		if ( $ruleIs0 ) {
			return $validationReductionCategory;
		}

		// se toman los productos que se encuentran en el carrito
		$products = $this->getProducts();

		// se crea objeto specificPrice que se encarga de los descuentos de categoria
		$specificPrice = new SpecificPrice();
		
		// se recorren los productos que se encuentran en el carrito
		foreach ($products as $product) {

			// se llama el metodo getByProductId para validar si el producto posee descuento por categoria
			$productCategoryDiscount = $specificPrice->getByProductId( $product['id_product'], false, false, true );

			// si el arreglo es diferente a vacio es porque el producto posee un descuento con categoria
			if ( !empty($productCategoryDiscount) ) {
				$validationReductionCategory = true;
				break;
			}
		}

		return $validationReductionCategory;
	}

		/**
	 * [validateProgressiveDiscount Funcion para validar si se encuentra un cupon de descuento progresivo en el carrito]
	 */
	public function validateProgressiveDiscountInCart() {
		
		$queryProgressiveDiscountInCart = "
			SELECT COUNT(*) as ProgressiveDiscountInCart
			FROM "._DB_PREFIX_."cart_cart_rule ccr
			INNER JOIN "._DB_PREFIX_."cart_cartrule_progressive_discounts ccpd
			ON ( ccr.id_cart = ccpd.id_cart )
			INNER JOIN "._DB_PREFIX_."progressive_discounts pd
			ON ( ccpd.id_progressive_discount = pd.id_progressive_discount )
			WHERE ccr.id_cart = ".(int)$this->id;
			
		$ProgressiveDiscountInCart = Db::getInstance()->ExecuteS($queryProgressiveDiscountInCart);

		if ( $ProgressiveDiscountInCart[0]['ProgressiveDiscountInCart'] > 0 ) {
			return true;
		} else {
			return false;
		}
	}
    
    ///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE ***///
	/*public function recalculartotalconcupon($descuento){
		$products = $this->getProducts();
		
		$total_todo_iva_producto = 0;
		$total_todo_descuento_sin_iva = 0;
		$total_todo_sin_iva = 0;

		foreach ($products as $value) {
			$precio = $value['price'];
			$iva = $value['rate'];
			$cantidad = $value['cart_quantity'];
			
			$total_sin_iva = $precio * $cantidad;
			$total_todo_sin_iva += $total_sin_iva;

			$descuento_sin_iva = ($total_sin_iva * $descuento) / 100;
			$total_todo_descuento_sin_iva += $descuento_sin_iva;

			$iva_producto = ( $precio - (( $precio * $descuento) / 100 )) * ($iva / 100) * $cantidad;
			$total_todo_iva_producto += $iva_producto;
		}

		$total = $total_todo_sin_iva + $total_todo_iva_producto - $total_todo_descuento_sin_iva;

		return array(
			'total' => $total,
			'total_todo_descuento_sin_iva' => $total_todo_descuento_sin_iva,
			'total_todo_sin_iva' => $total_todo_sin_iva,
			'total_todo_iva_producto' => $total_todo_iva_producto
		);
	}
	///*** FIN CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO  PORCENTAJE***///



	///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO MONETARIO ***///
	/*public function RecalcularCuponMonetario(){
		// arreglo de los productos en el carrito
		$products = $this->getProducts();

		// arreglo con las reglas de compra
		$cartRules = $this->getCartRules();

		// costo de envio
		$validateaddressOficina = $this->validationaddressfarmalisto();
        if ( $validateaddressOficina ){
            $total_envio = 0;
            $address_oficina = 1;
        } else {
        	$total_envio = $this->getTotalShippingCost();
        	$address_oficina = 0;
        }
		

		// captura el descuento monetario aplicado y el envio gratuito
		$descuento = $cartRules[0]['reduction_amount'];
		$free_shipping = $cartRules[0]['free_shipping'];

		// declaracion de variables
		$detail_products = "";
		$total_iva_pesos_individual = "";
		$total_iva_pesos_grupal = "";
		$total_precio_iva_individual = "";
		$total_precio_iva_grupal = "";
		$total_precio_cantidad = "";
		$i = 0;

		// se recorren los productos del carrito
		foreach ( $products as $value ) {

			// se toman los datos principales del producto
			$precio = $value['price'];
			$iva = $value['rate'];
			$cantidad = $value['cart_quantity'];

			// inicio calculos individuales
			$iva_pesos_individual = ( $precio * $iva ) / 100;
			$iva_pesos_grupal = $iva_pesos_individual * $cantidad;

			$precio_iva_individual = $precio + $iva_pesos_individual;
			$precio_iva_grupal = $precio_iva_individual * $cantidad;

			$precio_cantidad = $precio * $cantidad;
			// fin calculos individuales

			// inicio calculo totales
			$total_iva_pesos_individual += $iva_pesos_individual;
			$total_iva_pesos_grupal += $iva_pesos_grupal;



			$total_precio_iva_individual += $precio_iva_individual;
			$total_precio_iva_grupal += $precio_iva_grupal;

			$total_precio_cantidad += $precio_cantidad;
			// fin calculo totales


			// crea arreglo con detalles del producto
			$detail_products[$i] = array(
						'id_product' => $value['id_product'],
						// 'reference' => $value['reference'],
						// 'name' => $value['name'],
						// 'price' => $value['price'],
						// 'rate' => $value['rate'],
						// 'cart_quantity' => $value['cart_quantity'],
						// 'price_wt' => $value['price_wt'],
						// 'total_wt' => $value['total_wt'],
						// 'total' => $value['total'],
						'iva_pesos_individual' => $iva_pesos_individual,
						'iva_pesos_grupal' => $iva_pesos_grupal,
						'precio_iva_individual' => $precio_iva_individual,
						'precio_iva_grupal' => $precio_iva_grupal,
						'precio_cantidad' => $precio_cantidad
			);

			$i++;
		}

		// se aplica descuento y se suma al final el iva de los productos
		$precioTotal_descuento_aplicado = $total_precio_cantidad - $descuento;

		if ( $precioTotal_descuento_aplicado < 0 ) {
			$precioTotal_descuento_aplicado = 0;
		}
		$precioTotal_descuento_aplicado += $total_iva_pesos_grupal;


		// si el precio con descuento aplicado es 0, se toma la suma de ivas de los productos, si no se toma el valor calculado
		if ( $precioTotal_descuento_aplicado == 0 ) {
			$total_orden = $total_iva_pesos_grupal;
		} else {
			$total_orden = $precioTotal_descuento_aplicado;
		}


		// se suma el costo del envio solo si la regla del carrito no contiene envio gratuito
		if ( $free_shipping == 0 ) {
			$total_orden += $total_envio;
		}


		// calculo del descuento aplicado
		if ( $total_precio_cantidad >= $descuento){
			$descuento_aplicado = $descuento;
		} else {
			$descuento_aplicado = $total_precio_cantidad;
		}


		// calculo de porcentaje de descuento individual en $ y %
		foreach ( $detail_products as $key => $value ) {

			// se calcula el % de descuento individual
			$porcentaje_descuento_individual = ( 100 * ( $value['precio_iva_grupal'] ) / $total_precio_iva_grupal);
			$porcentaje_descuento_individual = number_format($porcentaje_descuento_individual, 4);

			// se calcula los $ de descuento individual
			$pesos_descuento_individual = ( $total_precio_cantidad * $porcentaje_descuento_individual ) / 100;

			// se asigna los valores de descuento al arreglo de detalles
			$detail_products[$key]['porcentaje_descuento_individual'] = $porcentaje_descuento_individual;
			$detail_products[$key]['pesos_descuento_individual'] = $pesos_descuento_individual;
		}

		// crea arreglo con detalles de los totales
		$detail_products['totales'] = array(
					'total_iva_pesos_individual' => $total_iva_pesos_individual,
					'total_iva_pesos_grupal' => $total_iva_pesos_grupal,
					'total_precio_iva_individual' => $total_precio_iva_individual,
					'total_precio_iva_grupal' => $total_precio_iva_grupal,
					'total_precio_cantidad' => $total_precio_cantidad,
					'free_shipping' => $free_shipping,
					'address_oficina' => $address_oficina,
					'descuento_aplicado' => $descuento_aplicado,
					'total_orden' => $total_orden
		);

		//echo "<pre>"; print_r($detail_products); die();

		return $detail_products;
	}
	///*** FIN CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO MONETARIO***///


public function getPackageList($flush = false)
	{
		static $cache = array();
		if (isset($cache[(int)$this->id]) && $cache[(int)$this->id] !== false && !$flush)
			return $cache[(int)$this->id];

		$product_list = $this->getProducts();
		// Step 1 : Get product informations (warehouse_list and carrier_list), count warehouse
		// Determine the best warehouse to determine the packages
		// For that we count the number of time we can use a warehouse for a specific delivery address
		$warehouse_count_by_address = array();
		$warehouse_carrier_list = array();

		$stock_management_active = Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT');

		foreach ($product_list as &$product)
		{
			if ((int)$product['id_address_delivery'] == 0)
				$product['id_address_delivery'] = (int)$this->id_address_delivery;

			if (!isset($warehouse_count_by_address[$product['id_address_delivery']]))
				$warehouse_count_by_address[$product['id_address_delivery']] = array();

			$product['warehouse_list'] = array();

			if ($stock_management_active &&
				((int)$product['advanced_stock_management'] == 1 || Pack::usesAdvancedStockManagement((int)$product['id_product'])) && Configuration::get('PS_SHIP_WHEN_AVAILABLE'))
			{
				$warehouse_list = Warehouse::getProductWarehouseList($product['id_product'], $product['id_product_attribute'], $this->id_shop);
				if (count($warehouse_list) == 0)
					$warehouse_list = Warehouse::getProductWarehouseList($product['id_product'], $product['id_product_attribute']);
				// Does the product is in stock ?
				// If yes, get only warehouse where the product is in stock

				$warehouse_in_stock = array();
				$manager = StockManagerFactory::getManager();

				foreach ($warehouse_list as $key => $warehouse)
				{
					$product_real_quantities = $manager->getProductRealQuantities(
						$product['id_product'],
						$product['id_product_attribute'],
						array($warehouse['id_warehouse']),
						true
					);

					if ($product_real_quantities > 0 || Pack::isPack((int)$product['id_product']))
						$warehouse_in_stock[] = $warehouse;
				}

				if (!empty($warehouse_in_stock))
				{
					$warehouse_list = $warehouse_in_stock;
					$product['in_stock'] = true;
				}
				else
					$product['in_stock'] = false;
			}
			else
			{
				//simulate default warehouse
				$warehouse_list = array(0);
				$product['in_stock'] = StockAvailable::getQuantityAvailableByProduct($product['id_product'], $product['id_product_attribute']) > 0;
			}

			foreach ($warehouse_list as $warehouse)
			{
				if (!isset($warehouse_carrier_list[$warehouse['id_warehouse']]))
				{
					$warehouse_object = new Warehouse($warehouse['id_warehouse']);
					$warehouse_carrier_list[$warehouse['id_warehouse']] = $warehouse_object->getCarriers();
				}

				$product['warehouse_list'][] = $warehouse['id_warehouse'];
				if (!isset($warehouse_count_by_address[$product['id_address_delivery']][$warehouse['id_warehouse']]))
					$warehouse_count_by_address[$product['id_address_delivery']][$warehouse['id_warehouse']] = 0;

				$warehouse_count_by_address[$product['id_address_delivery']][$warehouse['id_warehouse']]++;
			}
		}
		unset($product);

		arsort($warehouse_count_by_address);

		// Step 2 : Group product by warehouse
		$grouped_by_warehouse = array();
		foreach ($product_list as &$product)
		{
			if (!isset($grouped_by_warehouse[$product['id_address_delivery']]))
				$grouped_by_warehouse[$product['id_address_delivery']] = array(
					'in_stock' => array(),
					'out_of_stock' => array(),
				);
			
			$product['carrier_list'] = array();
			$id_warehouse = 0;
			foreach ($warehouse_count_by_address[$product['id_address_delivery']] as $id_war => $val)
			{
				if (in_array((int)$id_war, $product['warehouse_list']))
				{
					$product['carrier_list'] = array_merge($product['carrier_list'], Carrier::getAvailableCarrierList(new Product($product['id_product']), $id_war, $product['id_address_delivery'], null, $this));
					if (!$id_warehouse)
						$id_warehouse = (int)$id_war;
				}
			}

			if (!isset($grouped_by_warehouse[$product['id_address_delivery']]['in_stock'][$id_warehouse]))
			{
				$grouped_by_warehouse[$product['id_address_delivery']]['in_stock'][$id_warehouse] = array();
				$grouped_by_warehouse[$product['id_address_delivery']]['out_of_stock'][$id_warehouse] = array();
			}

			if (!$this->allow_seperated_package)
				$key = 'in_stock';
			else
				$key = $product['in_stock'] ? 'in_stock' : 'out_of_stock';

			if (empty($product['carrier_list']))
				$product['carrier_list'] = array(0);

			$grouped_by_warehouse[$product['id_address_delivery']][$key][$id_warehouse][] = $product;
		}
		unset($product);

		// Step 3 : grouped product from grouped_by_warehouse by available carriers
		$grouped_by_carriers = array();
		foreach ($grouped_by_warehouse as $id_address_delivery => $products_in_stock_list)
		{
			if (!isset($grouped_by_carriers[$id_address_delivery]))
				$grouped_by_carriers[$id_address_delivery] = array(
					'in_stock' => array(),
					'out_of_stock' => array(),
				);
			foreach ($products_in_stock_list as $key => $warehouse_list)
			{
				if (!isset($grouped_by_carriers[$id_address_delivery][$key]))
					$grouped_by_carriers[$id_address_delivery][$key] = array();
				foreach ($warehouse_list as $id_warehouse => $product_list)
				{
					if (!isset($grouped_by_carriers[$id_address_delivery][$key][$id_warehouse]))
						$grouped_by_carriers[$id_address_delivery][$key][$id_warehouse] = array();
					foreach ($product_list as $product)
					{
						$package_carriers_key = implode(',', $product['carrier_list']);

						if (!isset($grouped_by_carriers[$id_address_delivery][$key][$id_warehouse][$package_carriers_key]))
							$grouped_by_carriers[$id_address_delivery][$key][$id_warehouse][$package_carriers_key] = array(
								'product_list' => array(),
								'carrier_list' => $product['carrier_list'],
								'warehouse_list' => $product['warehouse_list']
							);

						$grouped_by_carriers[$id_address_delivery][$key][$id_warehouse][$package_carriers_key]['product_list'][] = $product;
					}
				}
			}
		}

		$package_list = array();
		// Step 4 : merge product from grouped_by_carriers into $package to minimize the number of package
		foreach ($grouped_by_carriers as $id_address_delivery => $products_in_stock_list)
		{
			if (!isset($package_list[$id_address_delivery]))
				$package_list[$id_address_delivery] = array(
					'in_stock' => array(),
					'out_of_stock' => array(),
				);

			foreach ($products_in_stock_list as $key => $warehouse_list)
			{
				if (!isset($package_list[$id_address_delivery][$key]))
					$package_list[$id_address_delivery][$key] = array();
				// Count occurance of each carriers to minimize the number of packages
				$carrier_count = array();
				foreach ($warehouse_list as $id_warehouse => $products_grouped_by_carriers)
				{
					foreach ($products_grouped_by_carriers as $data)
					{
						foreach ($data['carrier_list'] as $id_carrier)
						{
							if (!isset($carrier_count[$id_carrier]))
								$carrier_count[$id_carrier] = 0;
							$carrier_count[$id_carrier]++;
						}
					}
				}
				arsort($carrier_count);
				foreach ($warehouse_list as $id_warehouse => $products_grouped_by_carriers)
				{
					if (!isset($package_list[$id_address_delivery][$key][$id_warehouse]))
						$package_list[$id_address_delivery][$key][$id_warehouse] = array();
					foreach ($products_grouped_by_carriers as $data)
					{
						foreach ($carrier_count as $id_carrier => $rate)
						{
							if (in_array($id_carrier, $data['carrier_list']))
							{
								if (!isset($package_list[$id_address_delivery][$key][$id_warehouse][$id_carrier]))
									$package_list[$id_address_delivery][$key][$id_warehouse][$id_carrier] = array(
										'carrier_list' => $data['carrier_list'],
										'warehouse_list' => $data['warehouse_list'],
										'product_list' => array(),
									);
								$package_list[$id_address_delivery][$key][$id_warehouse][$id_carrier]['carrier_list'] =
									array_intersect($package_list[$id_address_delivery][$key][$id_warehouse][$id_carrier]['carrier_list'], $data['carrier_list']);
								$package_list[$id_address_delivery][$key][$id_warehouse][$id_carrier]['product_list'] =
									array_merge($package_list[$id_address_delivery][$key][$id_warehouse][$id_carrier]['product_list'], $data['product_list']);

								break;
							}
						}
					}
				}
			}
		}

		// Step 5 : Reduce depth of $package_list
		$final_package_list = array();
		foreach ($package_list as $id_address_delivery => $products_in_stock_list)
		{
			if (!isset($final_package_list[$id_address_delivery]))
				$final_package_list[$id_address_delivery] = array();

			foreach ($products_in_stock_list as $key => $warehouse_list)
				foreach ($warehouse_list as $id_warehouse => $products_grouped_by_carriers)
					foreach ($products_grouped_by_carriers as $data)
					{
						$final_package_list[$id_address_delivery][] = array(
							'product_list' => $data['product_list'],
							'carrier_list' => $data['carrier_list'],
							'warehouse_list' => $data['warehouse_list'],
							'id_warehouse' => $id_warehouse,
						);
					}
		}
		$cache[(int)$this->id] = $final_package_list;
		return $final_package_list;
	}

	public function InventarioPorCiudad() {

		/**************** INVENTARIO POR CIUDAD ***************/
		$envio_en_ciudad = 0;
		if ( Configuration::get('city_filter_inventory') == 1 ) {
			$envio_en_ciudad = 1;

			if ( isset( Context::getContext()->cart->_products ) ) {

				foreach ( Context::getContext()->cart->_products as $key => $value ) {
					if ( $value['cart_quantity'] > $value['quantity_available']) {
						$envio_en_ciudad = 0;
					}
				}
			}

		}

		return $envio_en_ciudad;

	}

		/**
	 * [UnitPriceDiscountPercent Para retornar el valor unitario del producto aplicando el respectivo descuento para facturaxion]
	 * @param [int] $price		   [Valor inicial del producto]
	 * @param [int] $tax			 [% IVA del producto]
	 * @param [int] $discountPercent [% Descuento a aplicar]
	 * @param [bool] $priceShow	  [flag para retornar precio a mostrar]
	 * @param [bool] $quantity	   [cantidad de productos en carrito]
	 * @param [bool] $showDiscount   [flag para retornar unicarmente el desucuento aplicado por producto]
	 * @return [int] $unitPrice	  [valor final unitario del producto]
	 */	

	public static function StaticUnitPriceDiscountPercent( $price, $tax, $discountPercent, $priceShow, $quantity, $showDiscount = false, $showTaxDiscount = false) {

		//echo "<br> ----- cart price: ".$price;
		//echo "<br> ----- cart tax: ".$tax;
		//echo "<br> ----- cart discountPercent: ".$discountPercent;
		//echo "<br> ----- cart priceShow: ".$priceShow;
		//echo "<br> ----- cart quantity: ".$quantity;

		// discount almacena descuento aplicado al precio inicial del producto
		$discount = ($price * $discountPercent) / 100;

		//echo "<br> ----- cart discount: ".$discount;

		// si showDiscount es true, se retorna solamente el descuento de cada producto
		if ( $showDiscount ) {
			return ( $discount * $quantity );
		}

		// priceDiscount almacena el precio inicial del producto con el descuento aplicado
		$priceDiscount = $price - $discount;
		//echo "<br> ----- cart priceDiscount: ".$priceDiscount;
		// taxDiscount almacena el iva del producto con el descuento aplicado
		$taxDiscount = ( ( $priceDiscount * $tax ) / 100 );

		// retorna unicamente el iva del producto con el descuento aplicado
		if ( $showTaxDiscount ) {
			//echo "<br> ----- cart taxDiscount: ".$taxDiscount;
			return $taxDiscount * $quantity; 
		}

		if ( $priceShow ) {
			// se suma el precio inicial del producto para ser mostrado correctamente en la vista
			$unitPrice = $price + $taxDiscount;
		} else {
			// se suma el precio con descuento para generar el total de la orden correctamente
			$unitPrice = $priceDiscount + $taxDiscount;
		}

		return $unitPrice;
		
	}

public function is_formula()
{
//Optener lista de productos del carrito    
 $pruducts = $this->getProducts();
  // recorrer cada producto y validar si requiere formula medica    
  foreach ($pruducts as &$valor) {
     // crear un nuevo producto 
    $product = new Product($valor['id_product'], true, $this->context->language->id, $this->context->shop->id);
    // obtener las caracteristicas del producto
    $features = $product->getFrontFeatures($this->context->language->id);
    foreach($features as $value)
    {
    if($value['name'] === 'Requiere fórmula médica'&&isset($value['value']))
      {
       
      if( strtoupper($value['value']) === 'SI') 
      {
         
      return true;
      }
            
      }
    }
 } 
return false;
}

	public static function prodsHasFormula ($array_prods) {

		$queryProdsFormula = " SELECT count(1) AS hasformula FROM 
			ps_feature_product fpp 
			INNER JOIN ps_feature_lang fll ON ( fpp.id_feature = fll.id_feature ) 
			INNER JOIN ps_feature_value_lang fvl ON ( fvl.id_feature_value = fpp.id_feature_value )
			WHERE fpp.id_product IN ( ". implode(',', $array_prods)." ) AND fll.`name` LIKE '%requiere%' AND fvl.`value` LIKE '%si%' ";

			$resultQueryProdsFormula = Db::getInstance()->ExecuteS($queryProdsFormula);

		if ( $resultQueryProdsFormula && $resultQueryProdsFormula[0]['hasformula'] > 0 ) {
			return true;
		} else {
			return false;
		}

	}

}
