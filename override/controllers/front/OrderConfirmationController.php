<?php

class OrderConfirmationController extends OrderConfirmationControllerCore
{
    /**
    * Assign template vars related to page content
    * @see FrontController::initContent()
    */
   public function initContent()
   {
           parent::initContent();
           
            $cart = new Cart((int)$this->id_cart);
            $order = new Order((int)($this->id_order));
            $customer = new Customer((int)$order->id_customer);
            $product_cart = $this->getProductsCart($cart->id);
            $address_customer = $this->getAddressCustomer($customer->id);

            $extraPayu = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT extras FROM ps_pagos_payu WHERE id_cart = '.$this->id_cart);
            $extraPayu = explode(';',$extraPayu);

            if(isset($extraPayu[1])){
                $order->numPago = $extraPayu[0];
                $order->fechaCadu = $extraPayu[1];
            }
            
           $this->context->smarty->assign(array(
                   'is_guest' => $this->context->customer->is_guest,
                   'HOOK_ORDER_CONFIRMATION' => $this->displayOrderConfirmation(),
                   'HOOK_PAYMENT_RETURN' => $this->displayPaymentReturn(),
                   'id_customer' => $order->id_customer,
                   'name_customer' => $customer->firstname.' '.$customer->lastname,
                   'email_customer' => $this->context->customer->email,
                   'segmento' => 'Diabéticos',
                   'product' => $product_cart[0]['name'],
                   'id_product' => $product_cart[0]['id_product'],
                   'phone' => $address_customer[0]['phone'],
                    'order' => $order,
                    'address' => new Address($order->id_address_delivery),
           ));

           //ddd($order->getProducts());

           if ($this->context->customer->is_guest)
           {
                   $this->context->smarty->assign(array(
                           'id_order' => $this->id_order,
                           'reference_order' => $this->reference,
                           'id_order_formatted' => sprintf('#%06d', $this->id_order),
                           'email' => $this->context->customer->email
                   ));
                   /* If guest we clear the cookie for security reason */
                   $this->context->customer->mylogout();
           }

           if($this->url_banco != null) {
                   $this->context->smarty->assign(array(
                   'pse' => true,
                   'bankdest2' => $this->url_banco
           ));
           }

           $this->setTemplate(_PS_THEME_DIR_.'order-confirmation.tpl');
   }
   
   public function getProductsCart($id_cart) {
       $query="select p.name, p.id_product from ps_cart_product c "
               . "INNER JOIN ps_product_lang p ON p.id_product = c.id_product WHERE id_cart = ".$id_cart.";";
       $results = Db::getInstance()->ExecuteS($query);
       return $results;
   }
   
   public function getAddressCustomer($id_customer) {
       $query="select phone from ps_address WHERE id_customer = ".$id_customer.";";
       $results = Db::getInstance()->ExecuteS($query);
       return $results;
   }

}