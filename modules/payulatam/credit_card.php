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

    public function logtxt($text = "") {
//        return false;
        //$contenido="-- lo que quieras escribir en el archivo -- \r\n";
//        $directorio=_ROUTE_FILE_."/ordensuministro/";
//        $ruta = _ROUTE_FILE_ . "/log_payu/log_credit_cart.log";
        error_log(_ROUTE_FILE_ . "/log_payu/log_credit_cart.log");
        $fp = fopen(_ROUTE_FILE_ . "/log_payu/log_credit_cart.log", "a+");
        fwrite($fp, $text . "\r\n");
        fclose($fp);
    }

    public function process() {

        // Validación que el carrito exista
        if (empty($this->context->cart->id)) {
            $context = Context::getContext();

            if (isset($context->cookie->{'page_confirmation'})) {
                $redirect = json_decode($context->cookie->{'page_confirmation'});
                Tools::redirectLink($redirect);
                exit();
            }
            $redirectLink = 'index.php?controller=history';
            Tools::redirect($redirectLink);
            exit();
        }

        parent::process();
        $conf = new ConfPayu();

        // url para re intentos de pago
        $url_reintento = $_SERVER['HTTP_REFERER'];
        if (!strpos($_SERVER['HTTP_REFERER'], '&step=')) {
            $url_reintento .= '&step=3';
        }

        // vaciar errores en el intento de pago anterior  
        if (isset($this->context->cookie->{'error_pay'})) {
            unset($this->context->cookie->{'error_pay'});
        }

        // Comprueba si el carrito ya esta en confirmación de pago
        // Evita el doble pago
        $status_cart = PasarelaPagoCore::is_cart_pay_process($this->context->cart->id);
        $id_cart = $this->context->cart->id;
        $this->logtxt("     /* ****************************************************** */");
        $this->logtxt(" Fecha y hora: " . date('l jS \of F Y h:i:s A'));
        $this->logtxt(" ID cart: " . $id_cart);
        $cantity = 0;
        while ($status_cart['in_pay'] && $status_cart['status']) {
            $this->logtxt(" Cantity: " . $cantity);
            if ($cantity == 10) {
                break;
            }
            sleep(1);

            $context = Context::getContext();
            /* $this->logtxt(" EXISTE Context: " . isset($context));
              $this->logtxt(" Context: " . json_encode($context));
              $this->logtxt(" EXISTE Conf->existe_transaccion($id_cart): " . $conf->existe_transaccion($id_cart));
              $this->logtxt(" EXISTE Context->cart->id: " . $context->cart->id); */
            if ($conf->existe_transaccion($id_cart) || empty($context->cart->id)) {
                if (isset($context->cookie->{'page_confirmation'})) {
                    $redirect = json_decode($context->cookie->{'page_confirmation'});
                    $this->logtxt(" Redirect: " . $redirect);
                    PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                    Tools::redirectLink($redirect);
                    exit();
                }
                $redirectLink = 'index.php?controller=history';
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                $this->logtxt(" RedirectLink: " . $redirectLink);
                Tools::redirect($redirectLink);
                exit();
            }

            $status_cart = PasarelaPagoCore::is_cart_pay_process($this->context->cart->id);
            $this->logtxt(" Status Cart: " . json_encode($status_cart));
            $cantity++;
            //break;
        }

        PasarelaPagoCore::set_cart_pay_process($id_cart, 1);

        $arraypaymentMethod = array(
          "VISA" => 'VISA',
          'DISCOVER' => 'DINERS',
          'AMERICAN EXPRESS' => 'AMEX',
          'MASTERCARD' => 'MASTERCARD'
        );
        $arraypaymentMethod2 = array(
          "VISA" => 'VISA',
          'DISCOVER' => 'DINERS',
          'AMERICAN EXPRESS' => 'AmEx',
          'MASTERCARD' => 'MasterCard',
          'DinersClub' => 'DinersClub',
          'UnionPay' => 'UnionPay'
        );

        //              If a new credir card arrives
        if ((Tools::getValue('numerot') && !empty(Tools::getValue('numerot')) && strlen(Tools::getValue('numerot')) > 13 && strlen((int) Tools::getValue('numerot')) < 17 && !empty(Tools::getValue('nombre')) && !empty(Tools::getValue('codigot')) && !empty(Tools::getValue('datepicker')) && !empty(Tools::getValue('cuotas'))) || (!empty(Tools::getValue('token_id')) && !empty(Tools::getValue('openpay_device_session_id')) && !empty(Tools::getValue('remember_tarjeta')))
        ) {

            $CCV = new CreditCardValidator();
            $CCV->Validate(Tools::getValue('numerot'));
            $key = $CCV->GetCardName($CCV->GetCardInfo()['type']);
            if ($CCV->GetCardInfo()['status'] == 'invalid') {
                $this->context->cookie->{'error_pay'} = json_encode(array('ERROR' => 'El numero de la tarjeta no es valido.'));
                Tools::redirectLink($url_reintento);
            }

            // reglas de carrito para bines
            $payulatam = new PayULatam();
            $bin = $payulatam->addCartRuleBin((Tools::getValue('numerot')) ? Tools::getValue('numerot') : Tools::getValue('card'));
            $paymentMethod = '';

            if (array_key_exists(strtoupper($key), $arraypaymentMethod)) {
                $paymentMethod = $arraypaymentMethod[strtoupper($key)];
            }

            // se optinen los datos del formulario de pago farmalisto    
            $post = array(
              'masked_number' => (Tools::getValue('masked_number')) ? Tools::getValue('masked_number') : false,
              'nombre' => (Tools::getValue('nombre')) ? Tools::getValue('nombre') : Tools::getValue('holder'),
              'numerot' => (Tools::getValue('numerot')) ? Tools::getValue('numerot') : Tools::getValue('card'),
              'codigot' => (Tools::getValue('codigot')) ? Tools::getValue('codigot') : Tools::getValue('cvv'),
              'date' => Tools::getValue('datepicker'),
              'cuotas' => Tools::getValue('cuotas'),
              'Month' => Tools::getValue('Month'),
              'Year' => Tools::getValue('Year'),
              'remember' => Tools::getValue('remember_card')
            );

            $payulatam = new PayULatam();
            $customer = new Customer((int) $this->context->cart->id_customer);
            $conn = PasarelaPagoCore::GetDataConnect('Tarjeta_credito');
            $keysPayu = $conf->keys();

            if ($conf->existe_transaccion($id_cart)) {
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                if (isset($this->context->cookie->{'page_confirmation'})) {
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

            // REDEBAN
            if ($conn['nombre_pasarela'] == 'redeban') {
                $parameters = array(
                  'idAdquiriente' => $dni,
                  'tipoDocumento' => 'CC',
                  'numDocumento' => $post['nombre'],
                  'franquicia' => $arraypaymentMethod2[strtoupper($key)],
                  'numTarjeta' => $post['numerot'],
                  'fechaExpiracion' => $post['Year'] . '-' . $post['Month'] . '-' . $post['Month'],
                  'codVerificacion' => $post['codigot'],
                  'cantidadCuotas' => $post['cuotas'],
                  'remember' => $post['remember']
                );
                if (!PasarelaPagoCore::isPayCart()) {
                    if (!PasarelaPagoCore::EnviarPagoRedeBan('Tarjeta_credito', $parameters)) {
                        $this->context->cookie->{'error_pay'} = json_encode(array('ERROR' => 'La solicitud de pago fallo.'));
                        Tools::redirectLink($url_reintento);
                        exit();
                    }
                }
                if (PasarelaPagoCore::isPayCart() && !$this->context->cart->orderExists()) {
                    $this->createPendingOrder(array(), 'Tarjeta_credito', 'Orden Procesada exitosamente  con ' . strtoupper($conn['nombre_pasarela']), 'PS_OS_PAYMENT');
                }
                if ($this->context->cart->orderExists() && PasarelaPagoCore::isPayCart()) {
                    Tools::redirectLink(__PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $this->context->cart->id . '&id_module=' . (int) $payulatam->id . '&id_order=' . (int) $this->currentOrder);
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

            //      Global variables
            $intentos = $conf->count_pay_cart($id_cart);
            $params = $this->initParams();
            $currency = $params[9]['currency'];
            $name_card = $post['nombre'];
            $total_tax = PasarelaPagoCore::get_total_tax($id_cart);
            $country = $this->context->country->iso_code;
            $test = (intval($conn['produccion']) == 0) ? 'true' : 'false';
            $referenceCode = $params[2]['referenceCode'] . '_' . $intentos;
            $signature = $conf->sing($params[2]['referenceCode'] . '_' . $intentos . '~' . $params[4]['amount'] . '~' . $currency);

            //      Paymnet gateway PayU and remember card
            if ($conn['nombre_pasarela'] == 'payulatam' && $post['remember']) {

                // Individual credit card registration CREATE_TOKEN

                $createToken = '{
                    "language": "es",
                    "command": "CREATE_TOKEN",
                    "merchant": {
                        "apiLogin": "' . $conn['apilogin_id'] . '",
                        "apiKey": "' . $conn['apikey_privatekey'] . '"
                    },
                    "creditCardToken": {
                      "payerId": "' . $customer->id . '",
                        "name":"' . $name_card . '",
                        "identificationNumber": "' . $dni . '",
                        "paymentMethod": "' . $paymentMethod . '",
                        "number": "' . $post['numerot'] . '",
                        "expirationDate": "' . $post['date'] . '"
                    }
                  }';

                $responseCreateToken = $conf->sendJson($createToken);
                // FIN Individual credit card registration CREATE_TOKEN
                // Error capture PayU Response CREATE_TOKEN
                $error_create_token = array();

                if ($responseCreateToken['code'] === 'ERROR') {
                    $conf->error_payu($id_order, $customer->id, $createToken, $responseCreateToken, 'Tarjeta_credito', 'ERROR_CREATE_TOKEN', $this->context->cart->id, $id_address);
                    $error_create_token[] = $responseCreateToken;
                    // die();
                } elseif ($responseCreateToken['code'] === 'SUCCESS' && $responseCreateToken['error'] === null) {

                    $creditCardTokenId = $responseCreateToken['creditCardToken']['creditCardTokenId'];
                    $name = $responseCreateToken['creditCardToken']['name'];
                    $payerId = $responseCreateToken['creditCardToken']['payerId'];
                    $identificationNumber = $responseCreateToken['creditCardToken']['identificationNumber'];
                    $paymentMethod = $responseCreateToken['creditCardToken']['paymentMethod'];
                    $creationDate = $responseCreateToken['creditCardToken']['creationDate'];
                    $maskedNumber = $responseCreateToken['creditCardToken']['maskedNumber'];
                    $errorDescription = $responseCreateToken['creditCardToken']['errorDescription'];

                    $Token_exist = "SELECT id_customer FROM `" . _DB_PREFIX_ . "payu_cards` WHERE  id_customer = '" . $payerId . "' AND token_id = '" . $creditCardTokenId . "';";

                    if (empty(Db::getInstance()->getValue($Token_exist))) {

                        $accessTokenSave = Db::getInstance()->insert('payu_cards', array(
                          'id_customer' => (int) $payerId,
                          'token_id' => pSQL($creditCardTokenId),
                          'name' => pSQL($name),
                          'identification_number' => pSQL($identificationNumber),
                          'payment_method' => pSQL($paymentMethod),
                          'masked_number' => pSQL($maskedNumber),
                          'error_description' => pSQL($errorDescription),
                          'creation_date' => date("Y-m-d H:i:s"),
                        ));

                        //if (Db::getInstance()->Execute($sql)) {
                        if ($accessTokenSave) {
                            $conf->error_payu($id_order, $customer->id, $createToken, $responseCreateToken, 'Tarjeta_credito', 'SUCCESS_CREATE_TOKEN', $this->context->cart->id, $id_address);

                            // $conf->pago_payu($id_order, $customer->id, $data, $responseCreateToken, 'Tarjeta_credito', $responseCreateToken['code'], $this->context->cart->id, $id_address);
                            // die();


                            $paymentWithToken = '{
                                "language":"es",
                                "command":"SUBMIT_TRANSACTION",
                                "merchant":{
                                    "apiKey":"' . $conn['apikey_privatekey'] . '",
                                    "apiLogin":"' . $conn['apilogin_id'] . '"
                                },
                                "transaction":{
                                  "order":{
                                    "accountId":"' . $conn['accountid'] . '",
                                    "referenceCode":"' . $params[2]['referenceCode'] . '_' . $intentos . '",
                                    "description":"' . $reference_code . '",
                                    "language":"' . $params[10]['lng'] . '",
                                    "notifyUrl":"' . $conf->urlv() . '",
                                    "signature":"' . $signature . '",
                                    "additionalValues":{
                                          "TX_VALUE":{
                                              "value":' . $params[4]['amount'] . ',
                                              "currency":"' . $currency . '"
                                          }
                                    },
                                    "buyer": {
                                          "merchantBuyerId": "' . $payerId . '",
                                          "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                                          "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                                          "emailAddress":"' . $params[5]['buyerEmail'] . '",
                                          "dniNumber":"' . $dni . '",   
                                          "shippingAddress": {
                                              "street1": "' . substr($address->address1, 0, 99) . '",
                                              "street2":"N/A",    
                                              "city": "' . $address->city . '",
                                              "state": "' . $conf->get_state($address->id_state) . '",
                                              "country": "' . $country . '",
                                              "postalCode": "' . $address->postcode . '",
                                              "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                          }
                                    },      
                                    "shippingAddress":{
                                        "street1":"' . substr($address->address1, 0, 99) . '",
                                        "street2":"N/A",
                                        "city":"' . $address->city . '",
                                        "state":"' . $conf->get_state($address->id_state) . '",
                                        "country":"' . $country . '",
                                        "postalCode":"' . $address->postcode . '",
                                        "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                    }  
                                  },
                                  "payer":{
                                    "merchantPayerId": "' . $payerId . '",
                                    "fullName":"' . $customer->firstname . ' ' . $customer->lastname . '",
                                    "emailAddress":"' . $params[5]['buyerEmail'] . '",
                                    "contactPhone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                                    "dniNumber":"' . $dni . '",
                                    "billingAddress":{
                                        "street1":"' . substr($address->address1, 0, 99) . '",
                                        "street2":"N/A",
                                        "city":"' . $address->city . '",
                                        "state":"' . $conf->get_state($address->id_state) . '",
                                        "country":"' . $country . '",
                                        "postalCode":"' . $address->postcode . '",
                                        "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                    }
                                  },
                                  "creditCardTokenId": "' . $creditCardTokenId . '",
                                  "extraParameters":{
                                    "INSTALLMENTS_NUMBER":' . $post['cuotas'] . '
                                  },
                                  "type":"AUTHORIZATION_AND_CAPTURE",
                                  "paymentMethod":"' . $paymentMethod . '",
                                  "paymentCountry":"' . $country . '",
                                  "deviceSessionId": "' . $_deviceSessionId . '",
                                  "ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '",
                                  "userAgent": "' . $_SERVER['HTTP_USER_AGENT'] . '",
                                  "cookie": "' . md5($this->context->cookie->timestamp) . '"  
                                },
                                "test":' . $test . '          
                              }';
                        } else {
                            $conf->error_payu($id_order, $customer->id, "Error inesperado al registrar esta tarjeta en la tabla: " . _DB_PREFIX_ . "payu_cards", $responseCreateToken, 'Tarjeta_credito', "ERROR_TOKEN_INSERT", $this->context->cart->id, $id_address);
                            $error_create_token[] = array('ERROR' => 'Error inesperado al registrar esta tarjeta.</b>.');
                        }
                    } else {
                        $conf->error_payu($id_order, $customer->id, "Error El token : " . $creditCardTokenId . ", ya esta en nuestros registro.", $responseCreateToken, 'Tarjeta_credito', "ERROR_BUSY_TOKEN", $this->context->cart->id, $id_address);
                        echo "<pre>";
                        $error_create_token[] = array('ERROR' => 'Error esta tarjeta ya esta registrada.</b>.');
                    }
                } else {
                    $conf->error_payu($id_order, $customer->id, $data, $responseCreateToken, 'Tarjeta_credito', $responseCreateToken['transactionResponse'], $this->context->cart->id, $id_address);
                    $error_create_token[] = array('ERROR' => 'Error inesperado al generar token de tarjeta.</b>.');
                }

                // If an error occurs in the creation of a token
                if (!empty($error_create_token)) {
                    $this->context->cookie->{'error_pay'} = json_encode($error_create_token);
                    PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                    Tools::redirectLink($url_reintento);
                    exit();
                }
                // FIN Error capture PayU Response CREATE_TOKEN
                //    LOG    //
                $this->logtxt(" Resquest Payment With New Token: ");
                $this->logtxt($paymentWithToken);
                $responsePaymentWithToken = $conf->sendJson($paymentWithToken);
                $this->logtxt(" Response Payment With New Token : ");
                $this->logtxt(json_encode($responsePaymentWithToken));
                $this->logtxt(" ");
                //    FIN LOG   //

                $error_pay = array();

                if ($responsePaymentWithToken['code'] === 'ERROR') {

                    $TokenDelete = "DELETE FROM `" . _DB_PREFIX_ . "payu_cards` WHERE  id_customer = '" . $payerId . "' AND token_id = '" . $creditCardTokenId . "';";
                    if (Db::getInstance()->execute($TokenDelete)) {
                        $conf->error_payu($id_order, $customer->id, $paymentWithToken, $responsePaymentWithToken, 'Tarjeta_credito', "ERROR_DELETE_TOKEN", $this->context->cart->id, $id_address);
                    }
                    $conf->error_payu($id_order, $customer->id, $paymentWithToken, $responsePaymentWithToken, 'Tarjeta_credito', $responsePaymentWithToken['code'], $this->context->cart->id, $id_address);
                    $error_pay[] = $responsePaymentWithToken;
                } elseif ($responsePaymentWithToken['code'] === 'SUCCESS' && ( $responsePaymentWithToken['transactionResponse']['state'] === 'PENDING' || $responsePaymentWithToken['transactionResponse']['state'] === 'APPROVED' ) && $responsePaymentWithToken['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {

                    $conf->pago_payu($id_order, $customer->id, $paymentWithToken, $responsePaymentWithToken, 'Tarjeta_credito', $responsePaymentWithToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    if ($responsePaymentWithToken['transactionResponse']['state'] === 'APPROVED') { //
                        $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PS_OS_PAYMENT');
                    } else {
                        $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PAYU_WAITING_PAYMENT');
                    }

                    $order = $conf->get_order($id_cart);
                    $id_order = $order['id_order'];

                    $page_confirmation = __PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $this->context->cart->id . '&id_module=105&id_order=' . (int) $order['id_order'];
                    $this->context->cookie->{'page_confirmation'} = json_encode($page_confirmation);
                    PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                    Tools::redirectLink($page_confirmation);
                } else {
                    $conf->error_payu($id_order, $customer->id, $paymentWithToken, $responsePaymentWithToken, 'Tarjeta_credito', $responsePaymentWithToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    $error_pay[] = array('ERROR' => 'La entidad financiera rechazo la transacción. <b>Status: ' . $responsePaymentWithToken['transactionResponse']['state'] . '</b>.');
                }

                $this->context->cookie->{'error_pay'} = json_encode($error_pay);
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                Tools::redirectLink($url_reintento);
                exit();
            }
            //      paymnet gateway PayU and not remember card
            elseif ($conn['nombre_pasarela'] == 'payulatam' && !$post['remember']) {

                $resquestPaymentWithoutToken = '{
                        "language":"es",
                        "command":"SUBMIT_TRANSACTION",
                        "merchant":{
                         "apiKey":"' . $conn['apikey_privatekey'] . '",
                         "apiLogin":"' . $conn['apilogin_id'] . '"
                       },
                       "transaction":{

                         "order":{
                          "accountId":"' . $conn['accountid'] . '",
                          "referenceCode":"' . $referenceCode . '",
                          "description":"' . $reference_code . '",
                          "language":"' . $params[10]['lng'] . '",
                          "notifyUrl":"' . $conf->urlv() . '",
                          "signature":"' . $signature . '",
                          "additionalValues":{
                           "TX_VALUE":{
                            "value":' . $params[4]['amount'] . ',
                            "currency":"' . $currency . '"
                          },
                          "TX_TAX":{  
                            "value":' . $total_tax . ',
                            "currency":"' . $currency . '"
                          },
                          "TX_TAX_RETURN_BASE":{  
                            "value":' . ($total_tax == 0.00 ? 0.00 : ($params[4]['amount'] - $total_tax)) . ',
                            "currency":"' . $currency . '"
                          }
                       },

                      "buyer": {
                       "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                       "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                       "emailAddress":"' . $params[5]['buyerEmail'] . '",
                       "dniNumber":"' . $dni . '",   
                       "shippingAddress": {
                        "street1": "' . substr($address->address1, 0, 99) . '",
                        "street2":"N/A",    
                        "city": "' . $address->city . '",
                        "state": "' . $conf->get_state($address->id_state) . '",
                        "country": "' . $country . '",
                      "postalCode": "' . $address->postcode . '",
                      "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                    }
                  },      

                  "shippingAddress":{
                   "street1":"' . substr($address->address1, 0, 99) . '",
                   "street2":"N/A",
                   "city":"' . $address->city . '",
                   "state":"' . $conf->get_state($address->id_state) . '",
                   "country":"' . $country . '",
                  "postalCode":"' . $address->postcode . '",
                  "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                 }  
                 },
                 "payer":{

                   "fullName":"' . $customer->firstname . ' ' . $customer->lastname . '",
                   "emailAddress":"' . $params[5]['buyerEmail'] . '",
                   "contactPhone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                   "dniNumber":"' . $dni . '",
                   "billingAddress":{
                     "street1":"' . substr($address->address1, 0, 99) . '",
                     "street2":"N/A",
                     "city":"' . $address->city . '",
                     "state":"' . $conf->get_state($address->id_state) . '",
                     "country":"' . $country . '",
                    "postalCode":"' . $address->postcode . '",
                    "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                  }      
                 },
                 "creditCard":{
                  "number":"' . $post['numerot'] . '",
                  "securityCode":"' . $post['codigot'] . '",
                  "expirationDate":"' . $post['date'] . '",
                  "name":"' . $name_card . '"
                 },

                 "extraParameters":{
                   "INSTALLMENTS_NUMBER":' . $post['cuotas'] . '
                 },
                 "type":"AUTHORIZATION_AND_CAPTURE",
                 "paymentMethod":"' . $paymentMethod . '",
                 "paymentCountry":"' . $country . '",
                 "deviceSessionId": "' . $_deviceSessionId . '",
                 "ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '",
                 "userAgent": "' . $_SERVER['HTTP_USER_AGENT'] . '",
                 "cookie": "' . md5($this->context->cookie->timestamp) . '"  
                 },
                 "test":' . $test . '          
                 }
                 ';
                ////////////    LOG    ///////////////////

                $resquestPaymentWithoutTokenLog = '{
                        "language":"es",
                        "command":"SUBMIT_TRANSACTION",
                        "merchant":{
                         "apiKey":"' . $conn['apikey_privatekey'] . '",
                         "apiLogin":"' . $conn['apilogin_id'] . '"
                       },
                       "transaction":{

                         "order":{
                          "accountId":"' . $conn['accountid'] . '",
                          "referenceCode":"' . $params[2]['referenceCode'] . '_' . $intentos . '",
                          "description":"' . $reference_code . '",
                          "language":"' . $params[10]['lng'] . '",
                          "notifyUrl":"' . $conf->urlv() . '",
                          "signature":"' . $conf->sing($params[2]['referenceCode'] . '_' . $intentos . '~' . $params[4]['amount'] . '~' . $currency) . '",
                          "additionalValues":{
                           "TX_VALUE":{
                            "value":' . $params[4]['amount'] . ',
                            "currency":"' . $currency . '"
                          },
                          "TX_TAX":{  
                            "value":' . $total_tax . ',
                            "currency":"' . $currency . '"
                          },
                          "TX_TAX_RETURN_BASE":{  
                            "value":' . ($total_tax == 0.00 ? 0.00 : ($params[4]['amount'] - $total_tax)) . ',
                            "currency":"' . $currency . '"
                          }
                       },

                      "buyer": {
                       "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                       "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                       "emailAddress":"' . $params[5]['buyerEmail'] . '",
                       "dniNumber":"' . $dni . '",   
                       "shippingAddress": {
                        "street1": "' . substr($address->address1, 0, 99) . '",
                        "street2":"N/A",    
                        "city": "' . $address->city . '",
                        "state": "' . $conf->get_state($address->id_state) . '",
                        "country": "' . $country . '",
                      "postalCode": "' . $address->postcode . '",
                      "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                    }
                  },      

                  "shippingAddress":{
                   "street1":"' . substr($address->address1, 0, 99) . '",
                   "street2":"N/A",
                   "city":"' . $address->city . '",
                   "state":"' . $conf->get_state($address->id_state) . '",
                   "country":"' . $country . '",
                  "postalCode":"' . $address->postcode . '",
                  "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                 }  
                 },
                 "payer":{

                   "fullName":"' . $customer->firstname . ' ' . $customer->lastname . '",
                   "emailAddress":"' . $params[5]['buyerEmail'] . '",
                   "contactPhone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                   "dniNumber":"' . $dni . '",
                   "billingAddress":{
                     "street1":"' . substr($address->address1, 0, 99) . '",
                     "street2":"N/A",
                     "city":"' . $address->city . '",
                     "state":"' . $conf->get_state($address->id_state) . '",
                     "country":"' . $country . '",
                    "postalCode":"' . $address->postcode . '",
                    "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                  }      
                 },
                 "creditCard":{
                 
                 },

                 "extraParameters":{
                   "INSTALLMENTS_NUMBER":' . $post['cuotas'] . '
                 },
                 "type":"AUTHORIZATION_AND_CAPTURE",
                 "paymentMethod":"' . $paymentMethod . '",
                 "paymentCountry":"' . $country . '",
                 "deviceSessionId": "' . $_deviceSessionId . '",
                 "ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '",
                 "userAgent": "' . $_SERVER['HTTP_USER_AGENT'] . '",
                 "cookie": "' . md5($this->context->cookie->timestamp) . '"  
                 },
                 "test":' . $test . '          
                 }
                 ';

                //    LOG    //
                $this->logtxt(" Resquest Payment Without Token: ");
                $this->logtxt($resquestPaymentWithoutTokenLog);
                $responsePaymentWithoutToken = $conf->sendJson($resquestPaymentWithoutToken);
                $this->logtxt(" Response Payment Without Token: ");
                $this->logtxt(json_encode($responsePaymentWithoutToken));
                $this->logtxt(" ");
                //    FIN LOG   //

                $subs = substr($post['numerot'], 0, (strlen($post['numerot']) - 4));
                $nueva = '';

                for ($i = 0; $i <= strlen($subs); $i++) {
                    $nueva = $nueva . '*';
                }

                $data = str_replace('"number":"' . $subs, '"number":"' . $nueva, $data);
                $data = str_replace('"securityCode":"' . $post['codigot'], '"securityCode":"' . '****', $data);
                // colector Errores Payu
                $error_pay = array();

                if ($responsePaymentWithoutToken['code'] === 'ERROR') {
                    $conf->error_payu($id_order, $customer->id, $resquestPaymentWithoutToken, $responsePaymentWithoutToken, 'Tarjeta_credito', $responsePaymentWithoutToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    $error_pay[] = $responsePaymentWithoutToken;
                } elseif ($responsePaymentWithoutToken['code'] === 'SUCCESS' && ( $responsePaymentWithoutToken['transactionResponse']['state'] === 'PENDING' || $responsePaymentWithoutToken['transactionResponse']['state'] === 'APPROVED' ) && $responsePaymentWithoutToken['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {
                    $conf->pago_payu($id_order, $customer->id, $resquestPaymentWithoutToken, $responsePaymentWithoutToken, 'Tarjeta_credito', $responsePaymentWithoutToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    if ($responsePaymentWithoutToken['transactionResponse']['state'] === 'APPROVED') {
                        $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PS_OS_PAYMENT');
                    } else {
                        $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PAYU_WAITING_PAYMENT');
                    }

                    $order = $conf->get_order($id_cart);
                    $id_order = $order['id_order'];

                    $page_confirmation = __PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $this->context->cart->id . '&id_module=105&id_order=' . (int) $order['id_order'];
                    $this->context->cookie->{'page_confirmation'} = json_encode($page_confirmation);
                    PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                    Tools::redirectLink($page_confirmation);
                } else {
                    $conf->error_payu($id_order, $customer->id, $resquestPaymentWithoutToken, $responsePaymentWithoutToken, 'Tarjeta_credito', $responsePaymentWithoutToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    $error_pay[] = array('ERROR' => 'La entidad financiera rechazo la transacción. <b>Status: ' . $responsePaymentWithoutToken['transactionResponse']['state'] . '</b>.');
                }

                $this->context->cookie->{'error_pay'} = json_encode($error_pay);
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                Tools::redirectLink($url_reintento);
                exit();
            }

            $this->context->cookie->{'error_pay'} = json_encode(array('ERROR' => 'Error interno pasarela de pago, no disponible.'));
            PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
            Tools::redirectLink($url_reintento);
            exit();
        }
        //          Existing credit cards with stored token
        elseif (Tools::getValue('masked_number') && !empty(Tools::getValue('masked_number')) && Tools::getValue('payment_method') && !empty(Tools::getValue('payment_method'))) {

            $maskedNumber = Tools::getValue('masked_number');
            $paymentMethod = Tools::getValue('payment_method');

            //              Get data of Form formTokenPayU   
            $post = array(
              'masked_number' => (Tools::getValue('masked_number')) ? Tools::getValue('masked_number') : false,
              'payment_method' => (Tools::getValue('payment_method')) ? Tools::getValue('payment_method') : false,
              'cuotas' => (Tools::getValue('cuotas')) ? Tools::getValue('cuotas') : 1,
            );

            $payulatam = new PayULatam();
            $customer = new Customer((int) $this->context->cart->id_customer);
            $conn = PasarelaPagoCore::GetDataConnect('Tarjeta_credito');
            $keysPayu = $conf->keys();

            //              Get data of payment method
            $queryDataCreditCard = "SELECT * FROM ps_payu_cards"
                . " WHERE masked_number = '" . $maskedNumber . "'"
                . " AND id_customer = '" . $customer->id . "'"
                . " AND payment_method = '" . $paymentMethod . "';";

            $dataCreditCard = Db::getInstance()->executeS($queryDataCreditCard);

            if (!empty($dataCreditCard)) {

                $creditCardTokenId = $dataCreditCard[0]["token_id"];
                $name = $dataCreditCard[0]["name"];
                $identificationNumber = $dataCreditCard[0]["identification_number"];
                $paymentMethod = $dataCreditCard[0]["payment_method"];
                $creationDate = $dataCreditCard[0]["creation_date"];
                $payerId = $dataCreditCard[0]["id_customer"];
            } else {

                $conf->error_payu($id_order, $customer->id, "Error esta tarjeta no esta dentro de nuestros registros: " . _DB_PREFIX_ . "payu_cards", $queryDataCreditCard, 'Tarjeta_credito', "ERROR_TOKEN_SELECT", $this->context->cart->id, $id_address);
                $error_create_token[] = array('ERROR' => 'Error esta tarjeta no esta dentro de nuestros registros.</b>.');
            }
            //              If exist error in Credit card not found
            if (!empty($error_create_token)) {
                $this->context->cookie->{'error_pay'} = json_encode($error_create_token);
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                Tools::redirectLink($url_reintento);
                exit();
            }

            if ($conf->existe_transaccion($id_cart)) {
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                if (isset($this->context->cookie->{'page_confirmation'})) {
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
            $params = $this->initParams();

            //      Payment gateway PAYULATAM
            if ($conn['nombre_pasarela'] == 'payulatam') {

                $currency = $params[9]['currency'];
                $country = $this->context->country->iso_code;
                $test = (intval($conn['produccion']) == 0) ? 'true' : 'false';

                $paymentWithTokenStored = '{
                  "language":"es",
                  "command":"SUBMIT_TRANSACTION",
                  "merchant":{
                      "apiKey":"' . $conn['apikey_privatekey'] . '",
                      "apiLogin":"' . $conn['apilogin_id'] . '"
                  },
                  "transaction":{
                    "order":{
                      "accountId":"' . $conn['accountid'] . '",
                      "referenceCode":"' . $params[2]['referenceCode'] . '_' . $intentos . '",
                      "description":"' . $reference_code . '",
                      "language":"' . $params[10]['lng'] . '",
                      "notifyUrl":"' . $conf->urlv() . '",
                      "signature":"' . $conf->sing($params[2]['referenceCode'] . '_' . $intentos . '~' . $params[4]['amount'] . '~' . $currency) . '",
                      "additionalValues":{
                            "TX_VALUE":{
                                "value":' . $params[4]['amount'] . ',
                                "currency":"' . $currency . '"
                            }
                      },
                      "buyer": {
                            "merchantBuyerId": "' . $payerId . '",
                            "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                            "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                            "emailAddress":"' . $params[5]['buyerEmail'] . '",
                            "dniNumber":"' . $dni . '",   
                            "shippingAddress": {
                                "street1": "' . substr($address->address1, 0, 99) . '",
                                "street2":"N/A",    
                                "city": "' . $address->city . '",
                                "state": "' . $conf->get_state($address->id_state) . '",
                                "country": "' . $country . '",
                                "postalCode": "' . $address->postcode . '",
                                "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                            }
                      },      
                      "shippingAddress":{
                          "street1":"' . substr($address->address1, 0, 99) . '",
                          "street2":"N/A",
                          "city":"' . $address->city . '",
                          "state":"' . $conf->get_state($address->id_state) . '",
                          "country":"' . $country . '",
                          "postalCode":"' . $address->postcode . '",
                          "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                      }  
                    },
                    "payer":{
                      "merchantPayerId": "' . $payerId . '",
                      "fullName":"' . $customer->firstname . ' ' . $customer->lastname . '",
                      "emailAddress":"' . $params[5]['buyerEmail'] . '",
                      "contactPhone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                      "dniNumber":"' . $dni . '",
                      "billingAddress":{
                          "street1":"' . substr($address->address1, 0, 99) . '",
                          "street2":"N/A",
                          "city":"' . $address->city . '",
                          "state":"' . $conf->get_state($address->id_state) . '",
                          "country":"' . $country . '",
                          "postalCode":"' . $address->postcode . '",
                          "phone":"' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                      }
                    },
                    "creditCardTokenId": "' . $creditCardTokenId . '",
                    "extraParameters":{
                      "INSTALLMENTS_NUMBER":' . $post['cuotas'] . '
                    },
                    "type":"AUTHORIZATION_AND_CAPTURE",
                    "paymentMethod":"' . $paymentMethod . '",
                    "paymentCountry":"' . $country . '",
                    "deviceSessionId": "' . $_deviceSessionId . '",
                    "ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '",
                    "userAgent": "' . $_SERVER['HTTP_USER_AGENT'] . '",
                    "cookie": "' . md5($this->context->cookie->timestamp) . '"  
                  },
                  "test":' . $test . '          
                }';

                ////////////    LOG Existing credit cards with stored token    ///////////////////
                $this->logtxt(" Request With Token Stored: ");
                $this->logtxt($paymentWithTokenStored);
                $responseStoredToken = $conf->sendJson($paymentWithTokenStored);
                $this->logtxt(" Response With Token Stored: ");
                $this->logtxt(json_encode($responseStoredToken));
                $this->logtxt(" ");
                ////////////      FIN LOG Existing credit cards with stored token   //////////////
                //          colector Error Payu
                $error_pay_stored_token = array();

                if ($responseStoredToken['code'] === 'ERROR') {
                    $conf->error_payu($id_order, $customer->id, $paymentWithTokenStored, $responseStoredToken, 'Tarjeta_credito', "ERROR_UNSUPPORTED_PAYMENT", $this->context->cart->id, $id_address);
                    $error_pay_stored_token[] = $responseStoredToken['error'];
                } elseif ($responseStoredToken['code'] === 'SUCCESS' && ( $responseStoredToken['transactionResponse']['state'] === 'PENDING' || $responseStoredToken['transactionResponse']['state'] === 'APPROVED' ) && $responseStoredToken['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {
                    $conf->pago_payu($id_order, $customer->id, $paymentWithTokenStored, $responseStoredToken, 'Tarjeta_credito', $responseStoredToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    if ($responseStoredToken['transactionResponse']['state'] === 'APPROVED') { //
                        $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PS_OS_PAYMENT');
                    } else {
                        $this->createPendingOrder(array(), 'Tarjeta_credito', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PAYU_WAITING_PAYMENT');
                    }

                    $order = $conf->get_order($id_cart);
                    $id_order = $order['id_order'];

                    $page_confirmation = __PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $this->context->cart->id . '&id_module=105&id_order=' . (int) $order['id_order'];
                    $this->context->cookie->{'page_confirmation'} = json_encode($page_confirmation);
                    PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                    Tools::redirectLink($page_confirmation);
                } else {
                    $conf->error_payu($id_order, $customer->id, $paymentWithTokenStored, $responseStoredToken, 'Tarjeta_credito', $responseStoredToken['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    $error_pay_stored_token[] = array('ERROR' => 'La entidad financiera rechazo la transacción. <b>Status: ' . $responseStoredToken['transactionResponse']['state'] . '</b>.');
                }

                if (!empty($error_pay_stored_token)) {
                    $this->context->cookie->{'error_pay'} = json_encode($error_pay_stored_token);
                    PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                    Tools::redirectLink($url_reintento);
                    exit();
                }
            }

            $this->context->cookie->{'error_pay'} = json_encode(array('ERROR' => 'Error interno pasarela de pago, no disponible.'));
            PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
            Tools::redirectLink($url_reintento);
            exit();
        } else {
            $this->context->cookie->{'error_pay'} = json_encode(array('ERROR' => 'Valida tus datos he intenta de nuevo.'));
            PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
            Tools::redirectLink($url_reintento);
            exit();
        }
    }

    public function displayContent() {
        parent::displayContent();
        self::$smarty->display(_PS_MODULE_DIR_ . 'payulatam/tpl/success.tpl');
    }

}

$farmaPayu = new PayuCreditCard();
$farmaPayu->run();
