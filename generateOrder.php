<?php

require(dirname(__FILE__).'/config/config.inc.php');

$payment_module = Module::getInstanceByName('cashondelivery');
$cart = new Cart(481845);
Context::getContext()->currency = new Currency((int) $cart->id_currency);
Context::getContext()->customer = new Customer((int) $cart->id_customer);
$employee = new Employee(52709);
$cod_pagar = 'COD-Efectivo';
$private_message = 'Sin mensaje';

$payment_module->validateOrder(
(int) $cart->id, 16, $cart->getOrderTotal(true, Cart::BOTH), !empty($cod_pagar) ? $cod_pagar : $payment_module->displayName, 'Manual order -- Employee:' .
substr($employee->firstname, 0, 1) . '. ' . $employee->lastname, array(), null, false, $cart->secure_key, null, $private_message
);

$order_invoice = new OrderInvoice();
$order_invoice->id_order = $payment_module->currentOrder;
$order_invoice->number = Configuration::get('PS_INVOICE_START_NUMBER', null, null, 1);
// If invoice start number has been set, you clean the value of this configuration
if ($order_invoice->number)
        Configuration::updateValue('PS_INVOICE_START_NUMBER', false, false, null, 1);
else
        $order_invoice->number = 132999;

$order_invoice->total_discount_tax_excl = 0;
$order_invoice->total_discount_tax_incl = 0;
$order_invoice->total_paid_tax_excl = 0;
$order_invoice->total_paid_tax_incl = 0;
$order_invoice->total_products = 0;
$order_invoice->total_products_wt = 0;
$order_invoice->total_shipping_tax_excl = 0;
$order_invoice->total_shipping_tax_incl = 0;
$order_invoice->shipping_tax_computation_method = 0;
$order_invoice->total_wrapping_tax_excl = 0;
$order_invoice->total_wrapping_tax_incl = 0;

$contTime = 0;
while(Configuration::get('SEMAFORO_FACTURAS') == 1 && $contTime < 2){
    time_nanosleep(0, 50000000);
    $contTime++;
}
Configuration::updateValue('SEMAFORO_FACTURAS',1);            
if ($order_invoice->number)
   Configuration::updateValue('PS_INVOICE_START_NUMBER', false, false, null, 1);
else
  $order_invoice->number = 132999;

// Save Order invoice
$order_invoice->add();

Configuration::updateValue('SEMAFORO_FACTURAS',0); 
print_r($order_invoice);     
d($payment_module->currentOrder);

