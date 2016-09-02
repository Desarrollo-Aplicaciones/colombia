<?php  
 
class Cart extends CartCore {

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
			$subtotal = (round( (round($val_total)/100) *2 , 0)/ 2 ) * 100;
		
			return $this->valorExpress($a,$subtotal);
		}
		//echo "<pre>";print_r(Context::getContext()->cookie); echo "</pre>"; exit();
		$sql = 'SELECT cac.precio_kilo, car.id_carrier, crp.delimiter2
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
		if(isset( $resultado[0]['id_carrier'])) {
			Context::getContext()->cart->id_carrier = $resultado[0]['id_carrier'];

			$val_total=0;
                        
                        if (Context::getContext()->cart->_products) {

                foreach (Context::getContext()->cart->_products as $key => $value) {
                    $val_total += $value['total_wt']; //valor total de la compra sin impuestos
                }
            }

			$valtot_tax = (round( (round($val_total*1.16)/100) *2 , 0)/ 2 ) * 100; //valor con impuesto 16% y redondeado

			if ( $val_total > $resultado[0]['delimiter2']) { // si total de compra es mayor al valor para no cobrar envio
				$val=0;
			}
		}

		
		//echo $val;
		return  $val; //$total_shipping; 
	}




	public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null, $express = false)
	{	
		if ((isset(Context::getContext()->cookie->check_xps) && Context::getContext()->cookie->check_xps)
			|| (isset(Context::getContext()->cart->check_xps) && Context::getContext()->cart->check_xps)
			|| Tools::getValue('express'))
		{
			$a = Context::getContext()->cart->id_address_delivery;
			if (Context::getContext()->cart->_products) {
				$val_total=0;
				foreach (Context::getContext()->cart->_products as $key => $value) {
					$val_total += $value['total_wt']; //valor total de la compra sin impuestos
				}
			}
			$subtotal = (round( (round($val_total)/100) *2 , 0)/ 2 ) * 100;
			return $this->valorExpress($a,$subtotal);
		}
		$sql = 'SELECT cac.precio_kilo, car.id_carrier, crp.delimiter2
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

			foreach (Context::getContext()->cart->_products as $key => $value) {
				$val_total += $value['total_wt']; //valor total de la compra sin impuestos
			}
			
			$valtot_tax = (round( (round($val_total*1.16)/100) *2 , 0)/ 2 ) * 100; //valor con impuesto 16% y redondeado

			if ( $val_total > $resultado[0]['delimiter2']) { // si total de compra es mayor al valor para no cobrar envio
				$shipping_cost=0;
			}
			//echo $shipping_cost;
		return  $shipping_cost;
	}
        
        	public function removeCartRules()
	{
                $cart_rules=$this->getCartRules();     
		Cache::clean('Cart::getCartRules'.$this->id.'-'.CartRule::FILTER_ACTION_ALL);
		Cache::clean('Cart::getCartRules'.$this->id.'-'.CartRule::FILTER_ACTION_SHIPPING);
		Cache::clean('Cart::getCartRules'.$this->id.'-'.CartRule::FILTER_ACTION_REDUCTION);
		Cache::clean('Cart::getCartRules'.$this->id.'-'.CartRule::FILTER_ACTION_GIFT);

		$result = Db::getInstance()->execute('
		DELETE FROM `'._DB_PREFIX_.'cart_cart_rule`
		WHERE  `id_cart` = '.(int)$this->id.';');
                
               
                
              foreach ($cart_rules as $value) {
                  $cart_rule = new CartRule($value['id_cart_rule'], Configuration::get('PS_LANG_DEFAULT'));
		if ((int)$cart_rule->gift_product)
			$this->updateQty(1, $cart_rule->gift_product, $cart_rule->gift_product_attribute, null, 'down', 0, null, false);
                }
		
		
		
		return $result;
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
	
	///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE ***///
	public function recalculartotalconcupon($descuento){
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
			$iva_producto = $precio * ($iva / 100) * $cantidad;
			$total_todo_iva_producto += $iva_producto;
			$descuento_sin_iva = ($total_sin_iva * $descuento) / 100;
			$total_todo_descuento_sin_iva += $descuento_sin_iva;
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
		$total_products_wt = $this->getOrderTotal(true, Cart::ONLY_PRODUCTS);
		$total_products = $this->getOrderTotal(false, Cart::ONLY_PRODUCTS);
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
						$total_products_wt = Tools::ps_round($total_products_wt - $product['price_wt'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
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
						
						// Add a new product line
						$gift_product = $product;
						$gift_product['cart_quantity'] = 1;
						$gift_product['price'] = 0;
						$gift_product['price_wt'] = 0;
						$gift_product['total_wt'] = 0;
						$gift_product['total'] = 0;
						$gift_product['gift'] = true;
						$gift_products[] = $gift_product;
						
						break; // One gift product per cart rule
					}
			}
		}

		foreach ($cart_rules as $key => &$cart_rule)
			if ($cart_rule['value_real'] == 0)
				unset($cart_rules[$key]);


        


		///*** INICIO VALIDACION DIRECCION FARMALISTO ***///
		$validateaddress = $this->validationaddressfarmalisto();
        if ($validateaddress){
            $total_shipping_tax_exc = 0;
        }
        ///*** FIN VALIDACION DIRECCION FARMALISTO ***///


		///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE ***///
		$cartRules = $this->getCartRules();
		$descuento = $cartRules[0]['reduction_percent'];
		$validacartrule = "false";
		if ($descuento != "" && $descuento != 0){
			$totalorderdescuento = $this->recalculartotalconcupon($descuento);

			$base_total_tax_inc = $totalorderdescuento['total'] + $total_shipping_tax_exc;
			$total_discounts = $totalorderdescuento['total_todo_descuento_sin_iva'];
			$base_total_tax_exc = $totalorderdescuento['total_todo_sin_iva'];
			$total_tax = $totalorderdescuento['total_todo_iva_producto'];
			$cart_rules[0]['value_real'] = $totalorderdescuento['total_todo_descuento_sin_iva'];
			$total_discounts_tax_exc = $totalorderdescuento['total_todo_descuento_sin_iva'];
			$validacartrule = "true";
		}
		///*** FIN CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE ***///

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
			'carrier' => new Carrier($this->id_carrier, $id_lang),
			'validacartrule' => $validacartrule,
		);
	}


	public function getOrderTotal($with_taxes = true, $type = Cart::BOTH, $products = null, $id_carrier = null, $use_cache = true)
	{

		if (!$this->id)
			return 0;

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
		if ($type == Cart::ONLY_DISCOUNTS && !CartRule::isFeatureActive())
			return 0;


		// no shipping cost if is a cart with only virtuals products
		$virtual = $this->isVirtualCart();
		if ($virtual && $type == Cart::ONLY_SHIPPING)
			return 0;




		if ($virtual && $type == Cart::BOTH)
			$type = Cart::BOTH_WITHOUT_SHIPPING;

		if ($with_shipping)
		{
			
			if (is_null($products) && is_null($id_carrier)){

				$shipping_fees = $this->getTotalShippingCost(null, (boolean)$with_taxes);
			
		
			}else
				$shipping_fees = $this->getPackageShippingCost($id_carrier, (int)$with_taxes, null, $products);
				
		}

		else{
			$shipping_fees = 0;
			}
	

		if ($type == Cart::ONLY_SHIPPING){
			return $shipping_fees;}

		if ($type == Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING){
			$type = Cart::ONLY_PRODUCTS;}

		$param_product = true;
		if (is_null($products))
		{
			$param_product = false;
			$products = $this->getProducts();
		}


		if ($type == Cart::ONLY_PHYSICAL_PRODUCTS_WITHOUT_SHIPPING)
		{
			foreach ($products as $key => $product)
				if ($product['is_virtual'])
					unset($products[$key]);
			$type = Cart::ONLY_PRODUCTS;
		}



		$order_total = 0;
		if (Tax::excludeTaxeOption())
			$with_taxes = false;

		foreach ($products as $product) // products refer to the cart details
		{

			if ($virtual_context->shop->id != $product['id_shop'])
				$virtual_context->shop = new Shop((int)$product['id_shop']);



			if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice')
				$address_id = (int)$this->id_address_invoice;
			else
				$address_id = (int)$product['id_address_delivery']; // Get delivery address of the product from the cart
			if (!Address::addressExists($address_id))
				$address_id = null;
			

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
				else
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

				$total_price = Tools::ps_round($price * (int)$product['cart_quantity'], 2);

			}
			$order_total += $total_price;
		}
		

	// echo '<pre>3.';
	// print_r($total_price);
	// echo '<pre>4.';
	// print_r($with_taxes);

		$order_total_products = $order_total;


		if ($type == Cart::ONLY_DISCOUNTS)
			$order_total = 0;

		// Wrapping Fees
		$wrapping_fees = 0;
		if ($this->gift)
			$wrapping_fees = Tools::convertPrice(Tools::ps_round($this->getGiftWrappingPrice($with_taxes), 2), Currency::getCurrencyInstance((int)$this->id_currency));
		if ($type == Cart::ONLY_WRAPPING)
			return $wrapping_fees;

		$order_total_discount = 0;

		
				

		if (!in_array($type, array(Cart::ONLY_SHIPPING, Cart::ONLY_PRODUCTS)) && CartRule::isFeatureActive())
		{
			

			// First, retrieve the cart rules associated to this "getOrderTotal"
			if ($with_shipping || $type == Cart::ONLY_DISCOUNTS)
				$cart_rules = $this->getCartRules(CartRule::FILTER_ACTION_ALL);
			

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
				if (($with_shipping || $type == Cart::ONLY_DISCOUNTS) && $cart_rule['obj']->free_shipping)
					$order_total_discount += Tools::ps_round($cart_rule['obj']->getContextualValue($with_taxes, $virtual_context, CartRule::FILTER_ACTION_SHIPPING, ($param_product ? $package : null), $use_cache), 2);
				
				
			
				// If the cart rule is a free gift, then add the free gift value only if the gift is in this package
				if ((int)$cart_rule['obj']->gift_product)
				{
					$in_order = false;
					if (is_null($products))
						$in_order = true;
					else
						foreach ($products as $product)
							if ($cart_rule['obj']->gift_product == $product['id_product'] && $cart_rule['obj']->gift_product_attribute == $product['id_product_attribute'])
								$in_order = true;

					if ($in_order)
						$order_total_discount += $cart_rule['obj']->getContextualValue($with_taxes, $virtual_context, CartRule::FILTER_ACTION_GIFT, $package, $use_cache);
				}

				// If the cart rule offers a reduction, the amount is prorated (with the products in the package)
				if ($cart_rule['obj']->reduction_percent > 0 || $cart_rule['obj']->reduction_amount > 0)
					$order_total_discount += Tools::ps_round($cart_rule['obj']->getContextualValue($with_taxes, $virtual_context, CartRule::FILTER_ACTION_REDUCTION, $package, $use_cache), 2);

				
			}



			$order_total_discount = min(Tools::ps_round($order_total_discount, 2), $wrapping_fees + $order_total_products + $shipping_fees);
			$order_total -= $order_total_discount;
		}

				
		///*** INICIO VALIDACION DIRECCION FARMALISTO ***///
        $validateaddress = $this->validationaddressfarmalisto();
        if ($validateaddress){
            $shipping_fees = 0;
        }
        ///*** FIN VALIDACION DIRECCION FARMALISTO ***///		
		

		if ($type == Cart::BOTH)
			$order_total += $shipping_fees + $wrapping_fees;

		if ($order_total < 0 && $type != Cart::ONLY_DISCOUNTS)
			return 0;

		if ($type == Cart::ONLY_DISCOUNTS)
			return $order_total_discount;


		return Tools::ps_round((float)$order_total, 2);
	}

	///*** INICIO VALIDACION DIRECCION FARMALISTO ***///
	public function validationaddressfarmalisto(){

		$address = new Address($this->id_address_delivery);
		$cityact = strtoupper($address->city);
        $addressact = strtoupper($address->address1);

        $validateaddress = false;

        $addressesoficina = array(
            'CALLE 129A NO. 56B - 23',
            'CALLE 129A NUMERO 56B - 23',
            'CALLE 129A # 56B - 23',
            'CARRERA 56B NO. 129A - 23',
            'CARRERA 56B NUMERO 129A - 23',
            'CARRERA 56B # 129A - 23',
            'CALLE 129A NO. 56B 23',
            'CALLE 129A NUMERO 56B 23',
            'CALLE 129A # 56B 23',
            'CARRERA 56B NO. 129A 23',
            'CARRERA 56B NUMERO 129A 23',
            'CARRERA 56B # 129A 23',
            'CALLE 129A NO. 56B-23',
            'CALLE 129A NUMERO 56B-23',
            'CALLE 129A # 56B-23',
            'CARRERA 56B NO. 129A-23',
            'CARRERA 56B NUMERO 129A-23',
            'CARRERA 56B # 129A-23'
        );

        if (trim(strtoupper($cityact)) == trim(strtoupper('BOGOTá, D.C.'))){
            foreach ($addressesoficina as $addresofi) {
                if (trim($addresofi) == trim(strtoupper($addressact))){
                    $validateaddress = true;
                }
            }
        }
        return $validateaddress;
    }
    ///*** FIN VALIDACION DIRECCION FARMALISTO ***///
}