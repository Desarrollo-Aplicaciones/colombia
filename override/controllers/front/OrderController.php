<?php

class OrderController extends OrderControllerCore
{
  /**
   * Initialize order controller
   * @see FrontController::init()
   */
  public function init()
  {
    global $orderTotal;
    
    parent::init();
    
    $this->step = (int) (Tools::getValue('step'));
    
    if (!$this->nbProducts)
      $this->step = -1;
    
    // If some products have disappear
    if (!$this->context->cart->checkQuantities()) {
      $this->step     = 0;
      $this->errors[] = Tools::displayError('An item in your cart is no longer available in this quantity. You cannot proceed with your order until the quantity is adjusted.');
    }
    
    // Check minimal amount
    $currency = Currency::getCurrency((int) $this->context->cart->id_currency);
    
    $orderTotal       = $this->context->cart->getOrderTotal();
    $minimal_purchase = Tools::convertPrice((float) Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
    if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimal_purchase && $this->step > 0) {
      $this->step     = 0;
      $this->errors[] = sprintf(Tools::displayError('A minimum purchase total of %s is required in order to validate your order.'), Tools::displayPrice($minimal_purchase, $currency));
    }
    if (!$this->context->customer->isLogged(true) && in_array($this->step, array(
      1,
      2,
      3
    ))) {
      $back_url = $this->context->link->getPageLink('order', true, (int) $this->context->language->id, array(
        'step' => $this->step,
        'multi-shipping' => (int) Tools::getValue('multi-shipping')
      ));
      $params   = array(
        'multi-shipping' => (int) Tools::getValue('multi-shipping'),
        'display_guest_checkout' => (int) Configuration::get('PS_GUEST_CHECKOUT_ENABLED'),
        'back' => $back_url
      );
      Tools::redirect($this->context->link->getPageLink('authentication', true, (int) $this->context->language->id, $params));
    }
    
    if (Tools::getValue('multi-shipping') == 1)
      $this->context->smarty->assign('multi_shipping', true);
    else
      $this->context->smarty->assign('multi_shipping', false);
    
    if ($this->context->customer->id)
      $this->context->smarty->assign('address_list', $this->context->customer->getAddresses($this->context->language->id));
    else
      $this->context->smarty->assign('address_list', array());
    
    /*                if($this->step == 0 && !Context::getContext()->customer->isLogged()){
    $back_url = $this->context->link->getPageLink('order', true, (int)$this->context->language->id, array('step' => $this->step, 'multi-shipping' => (int)Tools::getValue('multi-shipping')));
    $params = array('multi-shipping' => (int)Tools::getValue('multi-shipping'), 'display_guest_checkout' => (int)Configuration::get('PS_GUEST_CHECKOUT_ENABLED'), 'back' => $back_url);
    Tools::redirect($this->context->link->getPageLink('authentication', true, (int)$this->context->language->id, $params));
    }*/
  }
  
  protected $parameters = NULL;
  public function initContent()
  {
    parent::initContent();


		if ($errors = Tools::getValue('errors')) {
      $this->errors = explode("~", $errors);
    }

    // https://github.com/jschr/bootstrap-modal
    $this->context->controller->addJS(_THEME_JS_DIR_ . 'checkout.js', 'all');
    $this->context->controller->addJS(_THEME_JS_DIR_ . 'bootstrap-modal.min.js', 'all');
    $this->context->controller->addJS(_THEME_JS_DIR_ . 'bootstrap-modalmanager.min.js', 'all');
    $this->context->controller->addCSS(_THEME_CSS_DIR_ . 'checkout.css', 'all');
    $this->context->controller->addCSS(_THEME_CSS_DIR_ . 'bootstrap-modal-bs3patch.min.css', 'all');
    $this->context->controller->addCSS(_THEME_CSS_DIR_ . 'bootstrap-modal.min.css', 'all');
    
    if ($this->step == 0)
      $this->context->smarty->tpl_vars['meta_title']->value = 'Mi Carrito';
    if ($this->step == 1)
      $this->context->smarty->tpl_vars['meta_title']->value = 'Datos de entrega';
    if (Tools::getValue('paso')) {
      $paso = Tools::getValue('paso');
      if ($paso == 'formula')
        $this->context->smarty->tpl_vars['meta_title']->value = 'Fórmula médica';
      if ($paso == 'pagos')
        $this->context->smarty->tpl_vars['meta_title']->value = 'Modos de Pago';
    }
    
    if ($this->parameters === NULL) {
      $this->parameters = Utilities::get_parameters();
    }
    // echo 'paso 1<be><pre>'.  print_r($this->parameters,TRUE);
    $this->context->smarty->assign('expressEnabled', Configuration::get('ENVIO_EXPRESS'));
    if (Tools::isSubmit('ajax') && Tools::getValue('method') == 'updateExtraCarrier') {
      // Change virtualy the currents delivery options
      $delivery_option                                      = $this->context->cart->getDeliveryOption();
      $delivery_option[(int) Tools::getValue('id_address')] = Tools::getValue('id_delivery_option');
      $this->context->cart->setDeliveryOption($delivery_option);
      $this->context->cart->save();
      $return = array(
        'content' => Hook::exec('displayCarrierList', array(
          'address' => new Address((int) Tools::getValue('id_address'))
        ))
      );
      die(Tools::jsonEncode($return));
    }
    if ($this->nbProducts)
      $this->context->smarty->assign('virtual_cart', $this->context->cart->isVirtualCart());
    
    if (isset($this->context->cookie->{'error_pay'}) && !empty($this->context->cookie->{'error_pay'})) {
      $error_pay = json_decode($this->context->cookie->{'error_pay'}, true);
      $this->context->smarty->assign('errors_pay', 'true');
      $this->context->smarty->assign('errors_msgs', $error_pay);
      unset($this->context->cookie->{'error_pay'});
    } else {
      $this->context->smarty->assign('errors_pay', 'false');
    }
    // echo "<pre>".print_r(Context::getContext()->cookie->check_xps,true).'</pre>';
    $this->context->smarty->assign('xps', Context::getContext()->cookie->check_xps);
    $this->entrega_nocturna();
    if (isset($this->parameters['express']) && $this->parameters['express']) {
      $this->context->smarty->assign('express_productos', $this->context->cart->expressProduct());
    } else {
      $this->context->smarty->assign('express_productos', FALSE);
    }
    
    // 4 steps to the order
    switch ((int) $this->step) {
      case -1;
        $this->context->smarty->assign('empty', 1);
        $this->setTemplate(_PS_THEME_DIR_ . 'shopping-cart.tpl');
        break;
      
      case 1:
        //###############################################    
        if (!isset($_SESSION)) {
          session_start();
        }
        // si la varible sesión (formulamedica) se creo                            
        if (isset($_SESSION['formulamedica'])) {
          // si la varible de seción (formulamedica) es igual a true
          
          if ($_SESSION['formulamedica'] == true) {
            self::$smarty->assign('formula', true);
          } else {
            self::$smarty->assign('formula', false);
          }
        } else {
          self::$smarty->assign('formula', false);
        }
        
        
        if (!$this->is_formula()) {
          self::$smarty->assign('formula', true);
        }
        
        $this->_assignAddress();
        $this->processAddressFormat();
        if (Tools::getValue('multi-shipping') == 1) {
          $this->_assignSummaryInformations();
          $this->context->smarty->assign('product_list', $this->context->cart->getProducts());
          $this->setTemplate(_PS_THEME_DIR_ . 'order-address-multishipping.tpl');
        }
        
        /******* Codigo para Direcciones Ajax *******/
        $idcliente   = $this->context->customer->id;
        $sql         = "SELECT ad.id_address,
                        ad.id_state,
                        st.name AS state,
                        ad.id_customer,
                        ad.alias,
                        ad.city,
                        ad.address1,
                        ad.address2,
                        ac.id_city,
                        ad.phone,
                        ad.phone_mobile,
                        cc.express_abajo AS express
                    FROM " . _DB_PREFIX_ . "address AS ad
                        Inner Join " . _DB_PREFIX_ . "state AS st
                        ON ad.id_state = st.id_state
                        INNER JOIN " . _DB_PREFIX_ . "address_city AS ac
                        ON ad.id_address=ac.id_address
                        INNER JOIN " . _DB_PREFIX_ . "carrier_city AS cc
                        ON ac.id_city=cc.id_city_des
                    WHERE ad.id_customer='" . $idcliente . "' AND ad.deleted=0";
        $result      = Db::getInstance()->ExecuteS($sql, FALSE);
        $direcciones = array();
        $total       = 0;
        foreach ($result as $row) {
          $direcciones[] = $row;
          $total += 1;
        }
        
        $this->context->smarty->assign('cliente', $idcliente);
        $this->context->smarty->assign('total', $total);
        $this->context->smarty->assign('direcciones', $direcciones);
        $this->context->smarty->assign('express_productos', $this->context->cart->expressProduct());
        /******* Fin Codigo para Direcciones Ajax *******/
        
        $selectCountry = (int) Configuration::get('PS_COUNTRY_DEFAULT');
        $this->context->smarty->assign('id_country', $selectCountry);
        $this->context->smarty->assign('states', State::getStatesByIdCountry($selectCountry));
        $this->context->smarty->assign('token', Tools::getToken(false));

        /* Generate years, months and days */
        if ($this->context->customer->birthday) {
          $birthday = explode('-', $this->context->customer->birthday);
        } else {
          $birthday = array(
            '-',
            '-',
            '-'
          );
        }
        $this->context->smarty->assign('id_country', (int) Configuration::get('PS_COUNTRY_DEFAULT'));
        $this->context->smarty->assign(array(
          'years' => Tools::dateYears(),
          'sl_year' => $birthday[0],
          'months' => Tools::dateMonths(),
          'sl_month' => $birthday[1],
          'days' => Tools::dateDays(),
          'sl_day' => $birthday[2],
          'errors' => $this->errors,
          'genders' => Gender::getGenders()
        ));
        
        $this->setTemplate(_PS_THEME_DIR_ . 'order-address.tpl');
        break;
      
      
      case 2:
        // varible utilizada para validar si se requiere formula medica
        $formula = false;
        
        
        $formula = $this->is_formula();
        
        
        // Si $formula es verdadera se muestra la pantalla para que el cliente registre su formula medica.
        if ($formula) {
          if (Tools::isSubmit('processAddress')) {
            $this->processAddress();
          }

          $this->autoStep();
          $this->_assignCarrier();
          $this->setTemplate(_PS_THEME_DIR_ . 'order-carrier.tpl');
          
        } else {
          $this->disableMediosP();
          $this->show_contra_entrega();
          $this->block_medioP_sobre_costo();
          $this->list_medios_de_pago();
          
          if (Tools::isSubmit('processAddress')) {
            $this->processAddress();
          }
          $this->autoStep();
          $this->_assignCarrier();
          $this->_assignPayment();
          //assign some informations to display cart
          $this->_assignSummaryInformations();
          $this->setTemplate(_PS_THEME_DIR_ . 'order-payment.tpl');
        }
        break;
      
      case 3:
        if (Tools::isSubmit('processAddress')) {
          $this->processAddress();
        }
        $this->block_medioP_sobre_costo();
        $this->disableMediosP();
        $this->show_contra_entrega();
        // Check that the conditions (so active) were accepted by the customer
        $cgv = Tools::getValue('cgv') || $this->context->cookie->check_cgv;
        if (Configuration::get('PS_CONDITIONS') && (!Validate::isBool($cgv) || $cgv == false))
          Tools::redirect('index.php?controller=order&step=2&paso=pagos');
        Context::getContext()->cookie->check_cgv = true;
        
        // Check the delivery option is set
        if (!$this->context->cart->isVirtualCart()) {
          if (!Tools::getValue('delivery_option') && !Tools::getValue('id_carrier') && !$this->context->cart->delivery_option && !$this->context->cart->id_carrier)
            Tools::redirect('index.php?controller=order&step=2&paso=pagos');
          elseif (!Tools::getValue('id_carrier') && !$this->context->cart->id_carrier) {
            $deliveries_options = Tools::getValue('delivery_option');
            if (!$deliveries_options) {
              $deliveries_options = $this->context->cart->delivery_option;
            }
            foreach ($deliveries_options as $delivery_option)
              if (empty($delivery_option))
                Tools::redirect('index.php?controller=order&step=2&paso=pagos');
          }
        }
        
        $this->autoStep();
        
        // Bypass payment step if total is 0
        if (($id_order = $this->_checkFreeOrder()) && $id_order) {
          if ($this->context->customer->is_guest) {
            $order = new Order((int) $id_order);
            $email = $this->context->customer->email;
            $this->context->customer->mylogout(); // If guest we clear the cookie for security reason
            Tools::redirect('index.php?controller=guest-tracking&id_order=' . urlencode($order->reference) . '&email=' . urlencode($email));
          } else
            Tools::redirect('index.php?controller=history');
        }
        $this->_assignPayment();
        // assign some informations to display cart
        $this->_assignSummaryInformations();
        $this->setTemplate(_PS_THEME_DIR_ . 'order-payment.tpl');
        
        
        break;
      
      default:
        $this->_assignSummaryInformations();
        $this->setTemplate(_PS_THEME_DIR_ . 'shopping-cart.tpl');
        break;
    }
    
    // datos tipos de documentos
    $this->context->smarty->assign('document_types', Utilities::data_type_documents());
    // datos customer
    if (isset($idcliente))
      $this->context->smarty->assign('datacustomer', Utilities::data_customer_billing($idcliente));
    
    $this->context->smarty->assign(array(
      'currencySign' => $this->context->currency->sign,
      'currencyRate' => $this->context->currency->conversion_rate,
      'currencyFormat' => $this->context->currency->format,
      'currencyBlank' => $this->context->currency->blank
    ));
  }
  /**
   * Envío Express
   */
  private function valorEnvioExpress($idAddress)
  {
    $valorCompra = $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS); //obtiene el total de los productos
    $valor       = $this->context->cart->valorExpress($idAddress, $valorCompra);
    return $valor;
  }
  
  private function express()
  {
    $this->context->cart->express      = true;
    $this->context->cart->costoExpress = $this->valorEnvioExpress($this->context->cart->id_address_delivery);
    
    /*if($a)
    {
    die('{"express" : true, "valor" : '.$a.'}');
    }
    else
    {
    $this->errors[] = "Envío express no disponible en este momento";
    }
    if ($this->errors)
    {
    if (Tools::getValue('ajax'))
    die('{"hasError" : true, "errors" : ["'.implode('\',\'', $this->errors).'"]}');
    $this->step = 1;
    }*/
  }
  
  public function processAddress()
  {
    if ($this->parameters === NULL) {
      $this->parameters = Utilities::get_parameters();
    }
    /*Si se selecciona el envio nocturno  */
    $entrega_nocturna = Tools::getValue('entregaNocturna');
    
    if (isset($entrega_nocturna) && $entrega_nocturna === 'enabled') {
      Context::getContext()->cookie->entrega_nocturna = 'enabled';
      exit('{"entregaNocturna" : true, "status" : "enabled"}');
    }
    if (isset($entrega_nocturna) && $entrega_nocturna === 'disabled') {
      /*Si se desselecciona el envio nocturno  */
      Context::getContext()->cookie->entrega_nocturna = 'disabled';
      exit('{"entregaNocturna" : true, "status" : "disabled"}');
    }
    
    if (Tools::getValue('valor_nocturno') && Tools::getValue('valor_nocturno') === 'get') {
      exit('{"entrega_nocturna" : true, "valor" : ' . $this->parameters['valor'] . '}');
    }
    
    if (Tools::getValue('update_localidad_barrio') && Tools::getValue('update_localidad_barrio')) {
      if (Utilities::set_localidad_barrio((int) Tools::getValue('id_address'), (int) Tools::getValue('id_localiad'), (int) Tools::getValue('id_barrio'))) {
        //Context::getContext()->cookie->entrega_nocturna = 'enabled';  
        exit('{"entrega_nocturna" : true, "valor" : ' . $this->parameters['valor'] . '}');
      } else {
        // Context::getContext()->cookie->entrega_nocturna = 'disabled';    
        exit('{"entrega_nocturna" : false, "valor" : 0}');
      }
    }

    // Date and time of delivery
    $this->context->cart->date_delivery = Tools::getValue('date_delivery') ? Tools::getValue('date_delivery') : null;
    $this->context->cart->time_windows = Tools::getValue('time_windows') ? Tools::getValue('time_windows') : null;
    $timeWindow = explode("a", Tools::getValue('time_windows'));
    $this->context->cart->time_delivery = count($timeWindow) > 1 ? trim($timeWindow[0]) : null;

    if (Tools::getValue('display_barrios') && Tools::getValue('display_barrios')) {
      $barrios     = Utilities::get_list_barrios(Tools::getValue('lid_localiad'));
      $str_barrios = '<option >-Barrio-</option>';
      foreach ($barrios as $value) {
        $str_barrios .= '<option value="' . $value['id_barrio'] . '">' . strtolower($value['nombre_barrio']) . '</option>';
      }
      $array_result = array(
        'results' => $str_barrios
      );
      
      exit(json_encode($array_result));
    }

    // Express Service 
    Context::getContext()->cookie->check_xps = Tools::getValue('expressService') ? true : false;
    
    if (Tools::getValue('valorExpress')) {
      Context::getContext()->cookie->check_xps = true;
      if (Tools::getValue('checked')) {
        Context::getContext()->cookie->check_xps = false;
      }
      die('{"express" : true, "valor" : 1}');
    }
    if (Tools::getValue('express')) {
      $id = Tools::getValue('id_address');
      
      $a = (float) $this->valorEnvioExpress($id);
      // si no se suma el envió express y nocturno
      if (isset($this->parameters['add_value_express']) && !$this->parameters['add_value_express'] && $this->entrega_nocturna()) {
        $a -= (float) $this->parameters['valor'];
      }
      die('{"express" : true, "valor" : ' . $a . '}');
    }
    if (!Tools::getValue('multi-shipping'))
      $this->context->cart->setNoMultishipping();
    
    $same = Tools::isSubmit('same');
    if (!Tools::getValue('id_address_invoice', false) && !$same)
      $same = true;
    
    if (!Customer::customerHasAddress($this->context->customer->id, (int) Tools::getValue('id_address_delivery')) || (!$same && Tools::getValue('id_address_delivery') != Tools::getValue('id_address_invoice') && !Customer::customerHasAddress($this->context->customer->id, (int) Tools::getValue('id_address_invoice'))))
      $this->errors[] = Tools::displayError('Invalid address', !Tools::getValue('ajax'));
    else {
      $this->context->cart->id_address_delivery = (int) Tools::getValue('id_address_delivery');
      $this->context->cart->id_address_invoice  = $same ? $this->context->cart->id_address_delivery : (int) Tools::getValue('id_address_invoice');
      
      CartRule::autoRemoveFromCart($this->context);
      CartRule::autoAddToCart($this->context);
      
      if (!$this->context->cart->update())
        $this->errors[] = Tools::displayError('An error occurred while updating your cart.', !Tools::getValue('ajax'));
      
      if (!$this->context->cart->isMultiAddressDelivery())
        $this->context->cart->setNoMultishipping(); // If there is only one delivery address, set each delivery address lines with the main delivery address
      
      if (Tools::isSubmit('message'))
        $this->_updateMessage(Tools::getValue('message'));
      
      // Add checking for all addresses
      $address_without_carriers = $this->context->cart->getDeliveryAddressesWithoutCarriers();
      if (count($address_without_carriers) && !$this->context->cart->isVirtualCart()) {
        if (count($address_without_carriers) > 1)
          $this->errors[] = sprintf(Tools::displayError('There are no carriers that deliver to some addresses you selected.', !Tools::getValue('ajax')));
        elseif ($this->context->cart->isMultiAddressDelivery())
          $this->errors[] = sprintf(Tools::displayError('There are no carriers that deliver to one of the address you selected.', !Tools::getValue('ajax')));
        else
          $this->errors[] = sprintf(Tools::displayError('There are no carriers that deliver to the address you selected.', !Tools::getValue('ajax')));
      }
    }
    if ($this->errors) {
      if (Tools::getValue('ajax'))
        die('{"hasError" : true, "errors" : ["' . implode('\',\'', $this->errors) . '"]}');
      $this->step = 1;
    }
    
    if ($this->ajax)
      die(true);
  }
  
  public function entrega_nocturna()
  {
    //echo "<pre>".print_r($this->parameters,true).'</pre>';
    $this->context->smarty->assign('paramEntregaNocturana', $this->parameters);
    $this->valor_entrega_nocturna = isset($this->parameters['valor']) ? $this->parameters['valor'] : 0;
    // validación para control de inventario  
    $opcional                     = true;
    // si se requiere mostrar el envió de nocturno a partir de la disponibilidad de inventario
    if (isset($this->parameters['existencias']) && $this->parameters['existencias'] === '1') {
      if ($this->context->cart->expressProduct()) { // si existen productos en inventario
        $opcional = true;
      } else {
        $opcional = false;
      }
    }
    // valida si se debe mostrar la opción de entrega nocturna
    if (Utilities::is_rules_entrega_nocturna() && $opcional && Utilities::is_city_address((int) $this->context->cart->id_address_delivery)) {
      
      // valida si la dirección actual tiene localidad y barrio, y si se debe mostrar la entrega nocturna
      $result = Utilities::is_localidad_barrio((int) $this->context->cart->id_address_delivery);
      
      if (Utilities::show_select_localidad($result)) {
        $this->context->smarty->assign('entregaNocturnaEnabled', 'enabled');
        $this->context->smarty->assign('localidadesBarriosEnabled', 'disabled');
        $this->context->smarty->assign('entregaNocturna', Context::getContext()->cookie->entrega_nocturna);
        if ($this->parameters['auto_load']) {
          Context::getContext()->cookie->entrega_nocturna = 'enabled';
        }
        // Context::getContext()->cookie->check_xps = true;
        //    trigger_error(' || Mostrar envio nocturno || ', E_USER_NOTICE);
        return TRUE;
      } else {
        $this->context->smarty->assign('entregaNocturnaEnabled', 'enabled');
        $this->context->smarty->assign('localidadesBarriosEnabled', 'enabled');
        
        
        $localidades     = Utilities::get_list_localidades();
        $str_localidades = '';
        if (count($localidades) > 0 && !empty($localidades)) {
          foreach ($localidades as $row) {
            $str_localidades .= '<option value="' . $row['id_localidad'] . '">' . $row['nombre_localidad'] . '</option>';
          }
        }
        $this->context->smarty->assign('list_localidades', $str_localidades);
        
        //trigger_error(' || Mostrar envio nocturno y actualizar dirección || ', E_USER_NOTICE);
        
        
        $this->context->smarty->assign('entregaNocturna', Context::getContext()->cookie->entrega_nocturna);
        
        //Context::getContext()->cookie->check_xps = true;
        if ($this->parameters['auto_load']) {
          Context::getContext()->cookie->entrega_nocturna = 'enabled';
        }
        return TRUE;
      }
      
    }
    $this->context->smarty->assign('localidadesBarriosEnabled', 'disabled');
    $this->context->smarty->assign('entregaNocturnaEnabled', 'disabled');
    
    Context::getContext()->cookie->entrega_nocturna = 'disabled';
    $this->context->smarty->assign('entregaNocturna', Context::getContext()->cookie->entrega_nocturna);
    
    //trigger_error(' || No aplica envio nocturno || ', E_USER_NOTICE);
    return FALSE;
  }
  
}