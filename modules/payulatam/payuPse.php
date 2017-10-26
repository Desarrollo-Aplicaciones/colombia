<?php



$useSSL = true;
require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/payulatam.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/config.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/paymentws.php');

class PayuPse extends PayUControllerWS{





    public $ssl = true;
    

    public function setMedia()
    {
      parent::setMedia();
  }

  public function process() {

   if(empty( $this->context->cart->id)){
       Tools::redirect('/');  
   }            

   parent::process();

        // url para re intentos de pago
   $url_reintento=$_SERVER['HTTP_REFERER'];
   if(!strpos($_SERVER['HTTP_REFERER'], '&step=')){
      $url_reintento.='&step=3';
  }
          // vaciar errores en el intento de pago anterior  
  if(isset($this->context->cookie->{'error_pay'})){
    unset($this->context->cookie->{'error_pay'});
}

if (isset($_POST['pse_bank']) && isset($_POST['name_bank']) && !empty($_POST['pse_bank'])) {

            // reglas de carrito para bines
    $payulatam = new PayULatam();
    $payulatam->addCartRulePse($_POST['pse_bank']);
    $params = $this->initParams();
    $conf = new ConfPayu();
    $keysPayu = $conf->keys();
    $customer = new Customer((int) $this->context->cart->id_customer);
    $id_cart = $this->context->cart->id;
    $id_address = $this->context->cart->id_address_delivery;
    $id_order = 0; 
    $reference_code = $customer->id . '_' . $id_cart . '_' . $id_order . '_' . $id_address;
    $varRandn = $conf->randString();
    $varRandc = $conf->randString();
    setcookie($varRandn, $varRandc, time() + 900);


    $browser = array('ipAddress' => $_SERVER['SERVER_ADDR'],
                     'userAgent' => $_SERVER['HTTP_USER_AGENT']);

    $address = new Address($this->context->cart->id_address_delivery); 
    $dni = $conf->get_dni($this->context->cart->id_address_delivery);
    $intentos = $conf->count_pay_cart($id_cart);

    $currency='';

    if($conf->isTest()){

        $currency='USD';
    } else {

        $currency=$params[9]['currency'];
    }

    
    $total_tax = PasarelaPago::get_total_tax($id_cart);
    $data = '{
        "test":false,
        "language":"es",
        "command":"SUBMIT_TRANSACTION",
        "merchant":{
            "apiLogin":"' . $keysPayu['apiLogin'] . '",
            "apiKey":"' . $keysPayu['apiKey'] . '"
        },
        "transaction":{
            "order":{
                "accountId":"' . $keysPayu['pse-CO'] . '",
                "referenceCode":"' . $params[2]['referenceCode'] . '_'.$intentos.'",
                "description":"' . $reference_code . '",
                "language":"es",
                "notifyUrl":"' . $conf->urlv() . '",
                "signature":"' . $conf->sing($params[2]['referenceCode'] . '_'.$intentos.'~' . $params[4]['amount'] . '~'.$currency).'",
                "buyer":{
                    "fullName":"' . $this->context->customer->firstname . ' ' . $this->context->customer->lastname . '",
                    "emailAddress":"' . $params[5]['buyerEmail'] . '",
                    "dniNumber":"'.$dni.'",
                    "shippingAddress":{
                        "street1":"'.substr($address->address1,0, 99).'",
                        "city":"'.$address->city.'",
                        "state":"'.$conf->get_state($address->id_state).'",
                        "country":"' . $this->context->country->iso_code . '",
                        "phone":"'.((!empty($address->phone)) ? $address->phone  : $address->phone_mobile).'"
                    }
                },
                "additionalValues":{
                    "TX_VALUE":{
                        "value":' . $params[4]['amount'] . ',
                        "currency":"' . $currency . '"
                    },
                    "TX_TAX":{  
                     "value":'.$total_tax.',
                     "currency":"'.$params[9]['currency'].'"
                 },
                 "TX_TAX_RETURN_BASE":{  
                     "value":'.($total_tax == 0.00 ? 0.00 : ($params[4]['amount']-$total_tax)).',
                     "currency":"'.$params[9]['currency'].'"
                 }
             }
         },
         "payer":{
            "fullName":"' . $this->context->customer->firstname . ' ' . $this->context->customer->lastname . '",
            "emailAddress":"' . $params[5]['buyerEmail'] . '",
            "dniNumber":"' . $dni. '",
            "contactPhone":"'. ((!empty($address->phone)) ? $address->phone : $address->phone_mobile).'"
        },
        "ipAddress":"' . $browser['ipAddress'] . '",
        "cookie":"' . $varRandn . '",
        "userAgent":"' . $browser['userAgent'] . '",
        "type":"AUTHORIZATION_AND_CAPTURE",
        "paymentMethod":"PSE",
        "extraParameters":{
            "PSE_REFERENCE1":"' . $browser['ipAddress'] . '",
            "FINANCIAL_INSTITUTION_CODE":"' . $_POST['pse_bank'] . '",
            "FINANCIAL_INSTITUTION_NAME":"' . $_POST['name_bank'] . '",
            "USER_TYPE":"' . $_POST['pse_tipoCliente'] . '",
            "PSE_REFERENCE2":"' . $_POST['pse_docType'] . '",
            "PSE_REFERENCE3":"' . $_POST['pse_docNumber'] . '"
        }
    }
}
';

$response = $conf->sendJson($data);

if ($response['code'] === 'ERROR') {

    $conf->error_payu($id_order, $customer->id, $data, $response, 'PSE', $response['transactionResponse']['state'], $this->context->cart->id, $id_address); 
    $error_pay[]=$response;
}
elseif ($response['code'] === 'SUCCESS' && $response['transactionResponse']['state'] === 'PENDING' && $response['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {
   $this->createPendingOrder(array(), 'Pse', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PAYU_WAITING_PAYMENT');
   $order = $conf->get_order($id_cart);
   $id_order = $order['id_order'];    
   $conf->pago_payu($id_order, $customer->id, $data, $response, 'Pse',$response['code'], $id_cart, $id_address);
   $url_base64 = strtr(base64_encode($response['transactionResponse']['extraParameters']['BANK_URL']), '+/=', '-_,');
   $string_send = __PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $id_cart . '&id_module=105&id_order=' . (int) $order['id_order'] . '&bankdest2=' . $url_base64;
   Tools::redirectLink($string_send);
   exit();
} else {
    $conf->error_payu($id_order, $customer->id, $data, $response, 'PSE', $response['transactionResponse']['state'], $this->context->cart->id, $id_address); 
                $error_pay[] = $response['transactionResponse'];//array('ERROR'=>'La entidad financiera del medio de pago seleccionado, no responde.');
            }
            $this->context->cookie->{'error_pay'} = json_encode($error_pay);
            Tools::redirectLink($url_reintento);
            exit();
        }else {
            $this->context->cookie->{'error_pay'} = json_encode(array('ERROR'=>'Valida tus datos he intenta de nuevo.'));
            Tools::redirectLink($url_reintento); 
            exit();   
        }
    } 

    public function displayContent() {
        parent::displayContent();

        self::$smarty->display(_PS_MODULE_DIR_ . 'payulatam/tpl/success.tpl');
    }


}


$payuPse = new PayuPse();

$payuPse->run();
