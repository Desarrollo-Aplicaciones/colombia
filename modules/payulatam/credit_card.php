<?php

/*
 * 2007-2013 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 *  @version  Release: $Revision: 14011 $
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
ini_set('max_execution_time', 300);
$useSSL = true;
require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/payulatam.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/config.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/paymentws.php');
require_once(_PS_MODULE_DIR_ . 'payulatam/creditcards.class.php');

class PayuCreditCard extends PayUControllerWS {

  public $ssl = true;

  public function setMedia() {
    parent::setMedia();
  }
  
  public function generateLogRequestPayU($data) {
    if ($archivo = fopen(_ROUTE_FILE_."/payu/logs_request_payu.log", "a")) {
      if (fwrite($archivo, date("d m Y H:i:s") . " -> " . $data . "\n")) {
        //echo "Se ha ejecutado correctamente";
      } else {
        //echo "Ha habido un problema al crear el archivo";
      }
      fclose($archivo);
    }
  }

  public function process() {

    if (empty($this->context->cart->id)) {
      $context = Context::getContext();

      if(isset($context->cookie->{'page_confirmation'})){
        $redirect = json_decode($context->cookie->{'page_confirmation'});
        Tools::redirectLink($redirect);
        exit();
      }
      $redirectLink = 'index.php?controller=history';
      Tools::redirect($redirectLink);
      exit();
      
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

    $conf = new ConfPayu();

    $status_cart = PasarelaPagoCore::is_cart_pay_process($this->context->cart->id);
    $id_cart = $this->context->cart->id;
    $this->generateLogRequestPayU(" ID cart: ".$id_cart);
    $cantity = 0;
    while ($status_cart['in_pay'] && $status_cart['status']) {
      $this->generateLogRequestPayU(" Cantity: ".$cantity);
        if($cantity == 3) {
            break;
        }
      sleep(1);

      $context = Context::getContext();
      $this->generateLogRequestPayU(" Context: ".json_encode($context));
      if($conf->existe_transaccion($id_cart) || empty($context->cart->id)){
        if(isset($context->cookie->{'page_confirmation'})){
          $redirect = json_decode($context->cookie->{'page_confirmation'});
          $this->generateLogRequestPayU(" Redirect: ".$redirect);
          PasarelaPagoCore::set_cart_pay_process($id_cart,0);
          Tools::redirectLink($redirect);
          exit();
        }
        $redirectLink = 'index.php?controller=history';
        PasarelaPagoCore::set_cart_pay_process($id_cart,0);
        $this->generateLogRequestPayU(" RedirectLink: ".$redirectLink);
        Tools::redirect($redirectLink);
        exit();
      }

      $status_cart = PasarelaPagoCore::is_cart_pay_process($this->context->cart->id);
      $this->generateLogRequestPayU(" Status Cart: ".json_encode($status_cart));
      $cantity++;
      //break;
    }

    PasarelaPagoCore::set_cart_pay_process($id_cart, 1);


    $arraypaymentMethod =  array("VISA"=>'VISA','DISCOVER'=>'DINERS','AMERICAN EXPRESS'=>'AMEX','MASTERCARD'=>'MASTERCARD');
    $arraypaymentMethod2 =  array("VISA"=>'VISA','DISCOVER'=>'DINERS','AMERICAN EXPRESS'=>'AmEx','MASTERCARD'=>'MasterCard', 'DinersClub'=>'DinersClub','UnionPay'=>'UnionPay');

    if ((isset($_POST['numerot']) && !empty($_POST['numerot']) && strlen($_POST['numerot']) > 13 && strlen((int) $_POST['numerot']) < 17
        && isset($_POST['nombre']) && !empty($_POST['nombre']) && isset($_POST['codigot']) && !empty($_POST['codigot']) && 
        isset($_POST['datepicker']) && !empty($_POST['datepicker']) && isset($_POST['cuotas']) && !empty($_POST['cuotas'])) 
      || (isset($_POST['token_id']) && !empty($_POST['token_id']) && isset($_POST['openpay_device_session_id']) && !empty($_POST['openpay_device_session_id']) ) ) {

      $CCV = new CreditCardValidator();
    $CCV->Validate(Tools::getValue('numerot'));
    $key = $CCV->GetCardName($CCV->GetCardInfo()['type']); 
    if($CCV->GetCardInfo()['status'] == 'invalid'){
      $this->context->cookie->{'error_pay'} = json_encode(array('ERROR'=>'El numero de la tarjeta no es valido.'));
      Tools::redirectLink($url_reintento); 
    }
            // reglas de carrito para bines
    $payulatam = new PayULatam(); 
    $bin = $payulatam->addCartRuleBin((Tools::getValue('numerot')) ? Tools::getValue('numerot') : Tools::getValue('card'));
    $paymentMethod='';

    if (array_key_exists(strtoupper($key), $arraypaymentMethod)) {
      $paymentMethod = $arraypaymentMethod[strtoupper($key)];
    }

    $params = $this->initParams();
            // se optinen los datos del formulario de pago farmalisto    
    $post = array('nombre'  =>  (Tools::getValue('nombre')) ? Tools::getValue('nombre') : Tools::getValue('holder'),
                  'numerot' =>  (Tools::getValue('numerot')) ? Tools::getValue('numerot') : Tools::getValue('card'),
                  'codigot' =>  (Tools::getValue('codigot')) ? Tools::getValue('codigot') : Tools::getValue('cvv'),
                  'date'    =>  Tools::getValue('datepicker'),
                  'cuotas'  =>  Tools::getValue('cuotas'),
                  'Month'   =>  Tools::getValue('Month'),
                  'Year'    =>  Tools::getValue('Year')
                  );
    $payulatam = new PayULatam();
    $customer = new Customer((int) $this->context->cart->id_customer);
    $conn = PasarelaPagoCore::GetDataConnect('Tarjeta_credito');
    $conf = new ConfPayu();
    $keysPayu = $conf->keys();

    if($conf->existe_transaccion($id_cart)){
      PasarelaPagoCore::set_cart_pay_process($id_cart,0);
      if(isset($this->context->cookie->{'page_confirmation'})){
        $redirect = json_decode($this->context->cookie->{'page_confirmation'});
                //unset($this->context->cookie->{'page_confirmation'});
        Tools::redirectLink($redirect);
        exit();
      }
      $redirectLink = 'index.php?controller=history';
      Tools::redirect($redirectLink);
      exit();
    }

    $dni = $conf->get_dni($this->context->cart->id_address_delivery);
    if($conn['nombre_pasarela'] == 'redeban'){
      $parameters = array('idAdquiriente' => $dni, 'tipoDocumento' => 'CC', 'numDocumento' => $post['nombre'], 'franquicia' => $arraypaymentMethod2[strtoupper($key)],
                          'numTarjeta' => $post['numerot'], 'fechaExpiracion' => $post['Year'].'-'.$post['Month'].'-'.$post['Month'], 'codVerificacion' =>$post['codigot'],
                          'cantidadCuotas'=> $post['cuotas']);
      if(!PasarelaPagoCore::isPayCart()){
        if(!PasarelaPagoCore::EnviarPagoRedeBan('Tarjeta_credito',$parameters)){
          $this->context->cookie->{'error_pay'} = json_encode(array('ERROR'=>'La solicitud de pago fallo.'));
          Tools::redirectLink($url_reintento); 
          exit(); 
        }
      }
      if(PasarelaPagoCore::isPayCart() && !$this->context->cart->orderExists()){
        $this->createPendingOrder(array(), 'Tarjeta_credito', 'Orden Procesada exitosamente  con '. strtoupper($conn['nombre_pasarela']), 'PS_OS_PAYMENT');
      }
      if($this->context->cart->orderExists() && PasarelaPagoCore::isPayCart()){
        Tools::redirectLink(__PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $this->context->cart->id . '&id_module='.(int)$payulatam->id.'&id_order=' . (int) $this->currentOrder);
        exit();
      }                  

    }

    $address = new Address($this->context->cart->id_address_delivery); 
    $id_order = 0;
    $id_address = $this->context->cart->id_address_delivery;
    $reference_code = $customer->id . '_' . $id_cart . '_' . $id_order . '_' . $id_address;
    $_deviceSessionId = NULL;

    if (isset($this->context->cookie->deviceSessionId) && !empty($this->context->cookie->deviceSessionId) && strlen($this->context->cookie->deviceSessionId) === 32) {
      $_deviceSessionId = $this->context->cookie->deviceSessionId;
    } elseif (isset($_POST['deviceSessionId']) && !empty($_POST['deviceSessionId']) && strlen($_POST['deviceSessionId']) === 32) {
      $_deviceSessionId = $_POST['deviceSessionId'];
    } else {
      $_deviceSessionId = md5($this->context->cookie->timestamp);
    }

    $intentos = $conf->count_pay_cart($id_cart);

    if( $conn['nombre_pasarela'] == 'payulatam' ) {

      $currency='';

      if($conn['produccion'] == 'no'){

        $currency='USD';
      } else {

        $currency=$params[9]['currency'];
      }

      $total_tax = PasarelaPagoCore::get_total_tax($id_cart);

      $data = '{
       "language":"es",
       "command":"SUBMIT_TRANSACTION",
       "merchant":{
        "apiKey":"' . $conn['apikey_privatekey'] . '",
        "apiLogin":"' . $conn['apilogin_id'] . '"
      },
      "transaction":{

        "order":{
         "accountId":"' . $conn['accountid'] . '",
         "referenceCode":"' . $params[2]['referenceCode'] . '_'.$intentos.'",
         "description":"' . $reference_code . '",
         "language":"' . $params[10]['lng'] . '",
         "notifyUrl":"' . $conf->urlv() . '",
         "signature":"' . $conf->sing($params[2]['referenceCode'] . '_'.$intentos.'~' . $params[4]['amount'] . '~'.$currency).'",
         "additionalValues":{
          "TX_VALUE":{
           "value":' . $params[4]['amount'] . ',
           "currency":"'.$currency.'"
         },
         "TX_TAX":{  
           "value":'.$total_tax.',
           "currency":"'.$params[9]['currency'].'"
         },
         "TX_TAX_RETURN_BASE":{  
           "value":'.($total_tax == 0.00 ? 0.00 : ($params[4]['amount']-$total_tax)).',
           "currency":"'.$params[9]['currency'].'"
         }
      },

     "buyer": {
      "fullName": "'.$customer->firstname.' '.$customer->lastname.'",
      "contactPhone": "'.((!empty($address->phone)) ? $address->phone : $address->phone_mobile).'",
      "emailAddress":"'. $params[5]['buyerEmail'].'",
      "dniNumber":"'.$dni.'",   
      "shippingAddress": {
       "street1": "'.substr($address->address1,0, 99).'",
       "street2":"N/A",    
       "city": "'.$address->city.'",
       "state": "'.$conf->get_state($address->id_state).'",
       "country": "';
       if($conn['produccion'] == 'no'){
        $data.='PA';
      }else{
       $data.=$this->context->country->iso_code;
     }
     $data.='",
     "postalCode": "'.$address->postcode.'",
     "phone": "'.((!empty($address->phone)) ? $address->phone : $address->phone_mobile).'"
   }
 },      

 "shippingAddress":{
  "street1":"'.substr($address->address1,0, 99).'",
  "street2":"N/A",
  "city":"'.$address->city.'",
  "state":"'.$conf->get_state($address->id_state).'",
  "country":"';
  if($conn['produccion'] == 'no'){
    $data.='PA';
  }else{
   $data.=$this->context->country->iso_code;
 }
 $data.='",
 "postalCode":"'.$address->postcode.'",
 "phone":"'.((!empty($address->phone)) ? $address->phone : $address->phone_mobile).'"
}  
},
"payer":{

  "fullName":"'.$customer->firstname.' '.$customer->lastname.'",
  "emailAddress":"'. $params[5]['buyerEmail'].'",
  "contactPhone":"'.((!empty($address->phone)) ? $address->phone : $address->phone_mobile).'",
  "dniNumber":"'.$dni.'",
  "billingAddress":{
    "street1":"'.substr($address->address1,0, 99).'",
    "street2":"N/A",
    "city":"'.$address->city.'",
    "state":"'.$conf->get_state($address->id_state).'",
    "country":"';
    if($conn['produccion'] == 'no'){
      $data.='PA';
    }else{
     $data.=$this->context->country->iso_code;
   }
   $data.='",
   "postalCode":"'.$address->postcode.'",
   "phone":"'.((!empty($address->phone)) ? $address->phone : $address->phone_mobile).'"
 }      
},
"creditCard":{
 "number":"' . $post['numerot'] . '",
 "securityCode":"' . $post['codigot'] . '",
 "expirationDate":"' . $post['date'] . '",
 "name":"';
 if($conn['produccion'] == 'no'){
  $data.='APPROVED';
}else{
 $data.=$post['nombre'];
}
$data.='"
},

"extraParameters":{
  "INSTALLMENTS_NUMBER":'.$post['cuotas'].'
},
"type":"AUTHORIZATION_AND_CAPTURE",
"paymentMethod":"' . $paymentMethod . '",
"paymentCountry":"';
if($conn['produccion'] == 'no'){
  $data.='PA';
}else{
 $data.=$this->context->country->iso_code;
}
$data.='",
"deviceSessionId": "'.$_deviceSessionId.'",
"ipAddress": "'.$_SERVER['REMOTE_ADDR'].'",
"userAgent": "'.$_SERVER['HTTP_USER_AGENT'].'",
"cookie": "'.md5($this->context->cookie->timestamp).'"  
},
"test":';
if($conn['produccion'] == 'no'){
  $data.='true';
}else{
 $data.='false';
}
$data.='          
}
';
$this->generateLogRequestPayU(" Data: ".$data);
$response = $conf->sendJson($data);
$this->generateLogRequestPayU(" Response: ".$response);
$subs = substr($post['numerot'], 0, (strlen($post['numerot']) - 4));
$nueva = '';

for ($i = 0; $i <= strlen($subs); $i++) {
  $nueva = $nueva . '*';
}

$data = str_replace('"number":"' . $subs, '"number":"' . $nueva, $data);
$data = str_replace('"securityCode":"' . $post['codigot'], '"securityCode":"' . '****', $data);
                // colector Errores Payu
$error_pay = array();

if ($response['code'] === 'ERROR') {
 $conf->error_payu($id_order, $customer->id, $data, $response, 'Tarjeta_credito', $response['transactionResponse']['state'], $this->context->cart->id, $id_address); 
 $error_pay[]=$response;
} elseif ($response['code'] === 'SUCCESS' && ( $response['transactionResponse']['state'] === 'PENDING' || $response['transactionResponse']['state'] === 'APPROVED' ) && $response['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {
  $conf->pago_payu($id_order, $customer->id, $data, $response, 'Tarjeta_credito', $response['transactionResponse']['state'], $this->context->cart->id, $id_address);                       
                          if($response['transactionResponse']['state'] === 'APPROVED'){ //
                            $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PS_OS_PAYMENT');
                          } else{
                            $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PAYU_WAITING_PAYMENT');  
                          }

                          $order = $conf->get_order($id_cart);
                          $id_order = $order['id_order'];

                          $page_confirmation = __PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $this->context->cart->id . '&id_module=105&id_order=' . (int) $order['id_order']; 
                          $this->context->cookie->{'page_confirmation'} = json_encode($page_confirmation);
                          PasarelaPagoCore::set_cart_pay_process($id_cart,0);
                          Tools::redirectLink($page_confirmation);
                          
                        } else {
                          $conf->error_payu($id_order, $customer->id, $data, $response, 'Tarjeta_credito', $response['transactionResponse']['state'], $this->context->cart->id, $id_address); 
                          $error_pay[]=array('ERROR'=>'La entidad financiera rechazo la transacción. <b>Status: '.$response['transactionResponse']['state'].'</b>.');
                        }


                        $this->context->cookie->{'error_pay'} = json_encode($error_pay);
                        PasarelaPagoCore::set_cart_pay_process($id_cart,0);
                        Tools::redirectLink($url_reintento);
                        exit();
                      }

                      $this->context->cookie->{'error_pay'} = json_encode(array('ERROR'=>'Error interno pasarela de pago, no disponible.'));
                      PasarelaPagoCore::set_cart_pay_process($id_cart,0);
                      Tools::redirectLink($url_reintento); 
                      exit();



                    }  else {
                      $this->context->cookie->{'error_pay'} = json_encode(array('ERROR'=>'Valida tus datos he intenta de nuevo.'));
                      PasarelaPagoCore::set_cart_pay_process($id_cart,0);
                      Tools::redirectLink($url_reintento); 
                      exit();   
                    }

                  }

                  public function displayContent() {
                    parent::displayContent();
                    self::$smarty->display(_PS_MODULE_DIR_ . 'payulatam/tpl/success.tpl');
                  }


                }

                $farmaPayu = new  PayuCreditCard();
                $farmaPayu->run();
