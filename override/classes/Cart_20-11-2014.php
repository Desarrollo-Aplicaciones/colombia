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
	

}