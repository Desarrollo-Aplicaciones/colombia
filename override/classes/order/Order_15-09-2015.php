<?php  
  
class Order extends OrderCore {  
	
	public function getHistory($id_lang, $id_order_state = false, $no_hidden = false, $filters = 0)
	{
		if (!$id_order_state)
			$id_order_state = 0;
	
		$logable = false;
		$delivery = false;
		$paid = false;
		$shipped = false;
		if ($filters > 0)
		{
			if ($filters & OrderState::FLAG_NO_HIDDEN)
				$no_hidden = true;
			if ($filters & OrderState::FLAG_DELIVERY)
				$delivery = true;
			if ($filters & OrderState::FLAG_LOGABLE)
				$logable = true;
			if ($filters & OrderState::FLAG_PAID)
				$paid = true;
			if ($filters & OrderState::FLAG_SHIPPED)
				$shipped = true;
		}
	
		if (!isset(self::$_historyCache[$this->id.'_'.$id_order_state.'_'.$filters]) || $no_hidden)
		{
			$id_lang = $id_lang ? (int)($id_lang) : 'o.`id_lang`';
			$result = Db::getInstance()->executeS('
			SELECT oh.*, e.`firstname` AS employee_firstname, e.`lastname` AS employee_lastname, osl.`name` AS ostate_name, ot.`extras` AS `extras`
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_history` oh ON o.`id_order` = oh.`id_order`
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON os.`id_order_state` = oh.`id_order_state`
			LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'employee` e ON e.`id_employee` = oh.`id_employee`
			LEFT JOIN `'._DB_PREFIX_.'orders_transporte` ot ON (o.`id_order` = ot.`id_order`)
			WHERE oh.id_order = '.(int)($this->id).'
			'.($no_hidden ? ' AND os.hidden = 0' : '').'
			'.($logable ? ' AND os.logable = 1' : '').'
			'.($delivery ? ' AND os.delivery = 1' : '').'
			'.($paid ? ' AND os.paid = 1' : '').'
			'.($shipped ? ' AND os.shipped = 1' : '').'
			'.((int)($id_order_state) ? ' AND oh.`id_order_state` = '.(int)($id_order_state) : '').'
			ORDER BY oh.date_add DESC, oh.id_order_history DESC');
			if ($no_hidden)
				return $result;
			self::$_historyCache[$this->id.'_'.$id_order_state.'_'.$filters] = $result;
		}
		return self::$_historyCache[$this->id.'_'.$id_order_state.'_'.$filters];
	}
	

	public function addCartRule($id_cart_rule, $name, $values, $id_order_invoice = 0, $free_shipping = null)
	{
		$order_cart_rule = new OrderCartRule();
		$cart_rule = new CartRule($id_cart_rule);

		$order_cart_rule->id_order = $this->id;
		$order_cart_rule->id_cart_rule = $id_cart_rule;
		$order_cart_rule->id_order_invoice = $id_order_invoice;
		$order_cart_rule->name = $name;
		$order_cart_rule->value = $values['tax_incl'];
		$order_cart_rule->value_tax_excl = $values['tax_incl'];

		$order_cart_rule->reduction_percent = $cart_rule->reduction_percent;
		$order_cart_rule->reduction_amount = $cart_rule->reduction_amount;

		if ($free_shipping === null)
		{
			$free_shipping = $cart_rule->free_shipping;
		}

		$order_cart_rule->free_shipping = (int)$free_shipping;

		$order_cart_rule->add();
	}


	public function addOrderPayment($amount_paid, $payment_method = null, $payment_transaction_id = null, $currency = null, $date = null, $order_invoice = null)
	{
		$order_payment = new OrderPayment();
		$order_payment->order_reference = $this->reference;
		$order_payment->id_currency = ($currency ? $currency->id : $this->id_currency);
		// we kept the currency rate for historization reasons
		$order_payment->conversion_rate = ($currency ? $currency->conversion_rate : 1);
		// if payment_method is define, we used this
		$order_payment->payment_method = ($payment_method ? $payment_method : $this->payment);
		$order_payment->transaction_id = $payment_transaction_id;
		$this->total_paid_real = $order_payment->amount = $amount_paid;
		$order_payment->date_add = ($date ? $date : null);

		// SE INHABILITA PORQUE SE DUPLICA EL COSTO DEL TOTAL PAID REAL, AL CUMPLIRSE LA VALIDACION DEL MISMO TIPO DE MONEDA
		// Update total_paid_real value for backward compatibility reasons
		/*if ($order_payment->id_currency == $this->id_currency)
			$this->total_paid_real += $order_payment->amount;
		else
			$this->total_paid_real += Tools::ps_round(Tools::convertPrice($order_payment->amount, $order_payment->id_currency, false), 2);
		*/

		// We put autodate parameter of add method to true if date_add field is null
		
		//echo "<br>order<br>";
		//echo "<pre>"; print_r($order_payment); echo "<br><hr>";
		$res = $order_payment->add(is_null($order_payment->date_add)) && $this->update();
		
		if (!$res)
			return false;
	
		if (!is_null($order_invoice))
		{
			$res = Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'order_invoice_payment`
			VALUES('.(int)$order_invoice->id.', '.(int)$order_payment->id.', '.(int)$this->id.')');

			// Clear cache
			Cache::clean('order_invoice_paid_*');
		}
		
		return $res;
	}
	/*-Habilita la posibilidad de generar ordenes de envío sin generar factura, creando un objeto tipo order cuando no hay factura generada-*/
	public function getInvoicesCollection()
	{
		if (Tools::isSubmit('noInvoice')) {
			$order_invoices = new Collection('Order');		
		}
		 else {
			$order_invoices = new Collection('OrderInvoice');
		}
		$order_invoices->where('id_order', '=', $this->id);
		return $order_invoices;
	}
}