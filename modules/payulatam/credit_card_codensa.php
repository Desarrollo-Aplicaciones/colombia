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
        //$contenido="-- lo que quieras escribir en el archivo -- \r\n";
        $fp = fopen(_ROUTE_FILE_ . "/log_payu/log_credit_cart.log", "a+");
        fwrite($fp, $text . "\r\n");
        fclose($fp);
    }

    public function process() {

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

        // url para re intentos de pago
        $url_reintento = $_SERVER['HTTP_REFERER'];
        if (!strpos($_SERVER['HTTP_REFERER'], '&step=')) {
            $url_reintento .= '&step=3';
        }
        // vaciar errores en el intento de pago anterior  
        if (isset($this->context->cookie->{'error_pay'})) {
            unset($this->context->cookie->{'error_pay'});
        }

        $conf = new ConfPayu();

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


        $arraypaymentMethod = array("VISA" => 'VISA', 'DISCOVER' => 'DINERS', 'AMERICAN EXPRESS' => 'AMEX', 'MASTERCARD' => 'MASTERCARD', 'CODENSA' => 'CODENSA');
        $arraypaymentMethod2 = array("VISA" => 'VISA', 'DISCOVER' => 'DINERS', 'AMERICAN EXPRESS' => 'AmEx', 'MASTERCARD' => 'MasterCard', 'DinersClub' => 'DinersClub', 'UnionPay' => 'UnionPay', 'CODENSA' => 'CODENSA');

        if ((isset($_POST['numerot']) && !empty($_POST['numerot']) && strlen($_POST['numerot']) > 13 && strlen((int) $_POST['numerot']) < 17 
            && isset($_POST['nombrec']) && !empty($_POST['nombrec']) 
            && isset($_POST['codigot']) && !empty($_POST['codigot']) 
            && isset($_POST['cuotas']) && !empty($_POST['cuotas']) 
            && isset($_POST['device']) && !empty($_POST['device']) 
            && isset($_POST['dniType']) && !empty($_POST['dniType'])
            ) 
            || (isset($_POST['token_id']) && !empty($_POST['token_id']) 
            && isset($_POST['openpay_device_session_id']) && !empty($_POST['openpay_device_session_id']) )) {

            $numerot = Tools::getValue('numerot');
            if (substr($numerot, 0, 6) == '590712') {
                $key = "CODENSA";
            } else {
                $CCV = new CreditCardValidator();
                $CCV->Validate(Tools::getValue('numerot'));
                $key = $CCV->GetCardName($CCV->GetCardInfo()['type']);
                if ($CCV->GetCardInfo()['status'] == 'invalid') {
                    $this->context->cookie->{'error_pay'} = json_encode(array('ERROR' => 'El numero de la tarjeta no es valido.'));
                    Tools::redirectLink($url_reintento);
                }
            }
            // reglas de carrito para bines
            $payulatam = new PayULatam();
            $bin = $payulatam->addCartRuleBin((Tools::getValue('numerot')) ? Tools::getValue('numerot') : Tools::getValue('card'));
            $paymentMethod = '';

            if (array_key_exists(strtoupper($key), $arraypaymentMethod)) {
                $paymentMethod = $arraypaymentMethod[strtoupper($key)];
            }

            // se optinen los datos del formulario de pago farmalisto    
            $post = array('nombre' => (Tools::getValue('nombrec')) ? Tools::getValue('nombrec') : Tools::getValue('holder'),
              'numerot' => (Tools::getValue('numerot')) ? Tools::getValue('numerot') : Tools::getValue('card'),
              'codigot' => (Tools::getValue('codigot')) ? Tools::getValue('codigot') : Tools::getValue('cvv'),
              'date' => Tools::getValue('Year') . '/' . Tools::getValue('Month'),
              'cuotas' => Tools::getValue('cuotas'),
              'Month' => Tools::getValue('Month'),
              'Year' => Tools::getValue('Year')
            );
            $customer = new Customer((int) $this->context->cart->id_customer);
            $conn = PasarelaPagoCore::GetDataConnect('Tarjeta_credito');
            $conf = new ConfPayu();

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
            $dniType = (isset($_POST['dniType']) && !empty($_POST['dniType'])) ? $_POST['dniType'] : '';
            if ($conn['nombre_pasarela'] == 'redeban') {
                $parameters = array('idAdquiriente' => $dni, 'tipoDocumento' => 'CC', 'numDocumento' => $post['nombre'], 'franquicia' => $arraypaymentMethod2[strtoupper($key)],
                  'numTarjeta' => $post['numerot'], 'fechaExpiracion' => $post['Year'] . '-' . $post['Month'] . '-' . $post['Month'], 'codVerificacion' => $post['codigot'],
                  'cantidadCuotas' => $post['cuotas']);
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
            $_deviceSessionId = Tools::getValue('device');
            $intentos = $conf->count_pay_cart($id_cart);
            $params = $this->initParams();
            $currency = $params[9]['currency'];
            $name_card = $post['nombre'];
            $total_tax = PasarelaPagoCore::get_total_tax($id_cart);
            $country = $this->context->country->iso_code;
            $test = (intval($conn['produccion']) == 0) ? 'true' : 'false';
            $referenceCode = $params[2]['referenceCode'] . '_' . $intentos;
            $signature = $conf->sing($params[2]['referenceCode'] . '_' . $intentos . '~' . $params[4]['amount'] . '~' . $currency);
            $street1 = addslashes(substr($address->address1, 0, 99));
            $street2 = "N/A";

            if ($conn['nombre_pasarela'] == 'payulatam') {

                $resquestPaymentWithCodensa = '
                    {
                        "language": "es",
                        "command": "SUBMIT_TRANSACTION",
                        "merchant": {
                            "apiKey": "' . $conn['apikey_privatekey'] . '",
                            "apiLogin": "' . $conn['apilogin_id'] . '"
                        },
                        "transaction": {

                            "order": {
                                "accountId": "' . $conn['accountid'] . '",
                                "referenceCode": "' . $referenceCode . '",
                                "description": "' . $reference_code . '",
                                "language": "' . $params[10]['lng'] . '",
                                "notifyUrl": "' . $conf->urlv() . '",
                                "signature": "' . $signature . '",
                                "additionalValues": {
                                    "TX_VALUE": {
                                        "value": '.$params[4]['amount'].',
                                        "currency": "' . $currency . '"
                                    },
                                    "TX_TAX": {  
                                        "value": '.$total_tax.',
                                        "currency": "' . $currency . '"
                                    },
                                    "TX_TAX_RETURN_BASE": {  
                                        "value": '.($total_tax == 0.00 ? 0.00 : ($params[4]['amount'] - $total_tax)).',
                                        "currency": "' . $currency . '"
                                    }
                                },

                                "buyer": {
                                    "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                                    "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                                    "emailAddress": "' . $params[5]['buyerEmail'] . '",
                                    "dniNumber": "' . $dni . '",   
                                    "shippingAddress": {
                                        "street1": "' . $street1 . '",
                                        "street2": "' . $street2 . '",    
                                        "city": "' . $address->city . '",
                                        "state": "' . $conf->get_state($address->id_state) . '",
                                        "country": "' . $country . '",
                                        "postalCode": "' . $address->postcode . '",
                                        "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                    }
                                },      

                                "shippingAddress": {
                                    "street1": "' . $street1 . '",
                                    "street2": "' . $street2 . '",
                                    "city": "' . $address->city . '",
                                    "state": "' . $conf->get_state($address->id_state) . '",
                                    "country": "' . $country . '",
                                    "postalCode": "' . $address->postcode . '",
                                    "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                }  
                            },
                            "payer": {

                                "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                                "emailAddress": "' . $params[5]['buyerEmail'] . '",
                                "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                                "dniNumber": "' . $dni . '",
                                "dniType": "' . $dniType . '",    
                                "billingAddress": {
                                    "street1": "' . $street1 . '",
                                    "street2": "' . $street2 . '",
                                    "city": "' . $address->city . '",
                                    "state": "' . $conf->get_state($address->id_state) . '",
                                    "country": "' . $country . '",
                                    "postalCode": "' . $address->postcode . '",
                                    "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                }      
                            },
                            "creditCard": {
                                "number": "' . $post['numerot'] . '",
                                "securityCode": "' . $post['codigot'] . '",
                                "expirationDate": "' . $post['date'] . '",
                                "name": "' . $name_card . '"
                            },

                            "extraParameters": {
                                "INSTALLMENTS_NUMBER": '.$post['cuotas'].'
                            },
                            "type": "AUTHORIZATION_AND_CAPTURE",
                            "paymentMethod": "' . $paymentMethod . '",
                            "paymentCountry": "' . $country . '",
                            "deviceSessionId": "' . $_deviceSessionId . '",
                            "ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '",
                            "userAgent": "' . $_SERVER['HTTP_USER_AGENT'] . '",
                            "cookie": "' . md5($this->context->cookie->timestamp) . '"  
                        },
                        "test": '.$test.'          
                    }
                    ';
                ////////////    LOG    ///////////////////

                $resquestPaymentWithCodensaLog = '
                    {
                    "language": "es",
                    "command": "SUBMIT_TRANSACTION",
                    "merchant": {
                        "apiKey": "' . $conn['apikey_privatekey'] . '",
                        "apiLogin": "' . $conn['apilogin_id'] . '"
                    },
                    "transaction": {

                        "order": {
                            "accountId": "' . $conn['accountid'] . '",
                            "referenceCode": "' . $params[2]['referenceCode'] . '_' . $intentos . '",
                            "description": "' . $reference_code . '",
                            "language": "' . $params[10]['lng'] . '",
                            "notifyUrl": "' . $conf->urlv() . '",
                            "signature": "' . $conf->sing($params[2]['referenceCode'] . '_' . $intentos . '~' . $params[4]['amount'] . '~' . $currency) . '",
                            "additionalValues": {
                                "TX_VALUE": {
                                    "value": '.$params[4]['amount'].',
                                    "currency": "' . $currency . '"
                                },
                                "TX_TAX": {  
                                    "value": '.$total_tax.',
                                    "currency": "' . $currency . '"
                                },
                                "TX_TAX_RETURN_BASE": {  
                                    "value": '.($total_tax == 0.00 ? 0.00 : ($params[4]['amount'] - $total_tax)).',
                                    "currency": "' . $currency . '"
                                }
                            },

                            "buyer": {
                                "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                                "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                                "emailAddress": "' . $params[5]['buyerEmail'] . '",
                                "dniNumber": "' . $dni . '",   
                                "shippingAddress": {
                                    "street1": "' . $street1 . '",
                                    "street2": "' . $street2 . '",    
                                    "city": "' . $address->city . '",
                                    "state": "' . $conf->get_state($address->id_state) . '",
                                    "country": "' . $country . '",
                                    "postalCode": "' . $address->postcode . '",
                                    "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                                }
                            },      

                            "shippingAddress": {
                                "street1": "' . $street1 . '",
                                "street2": "' . $street2 . '",
                                "city": "' . $address->city . '",
                                "state": "' . $conf->get_state($address->id_state) . '",
                                "country": "' . $country . '",
                                "postalCode": "' . $address->postcode . '",
                                "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                            }  
                        },
                        "payer": {
                            "fullName": "' . $customer->firstname . ' ' . $customer->lastname . '",
                            "emailAddress": "' . $params[5]['buyerEmail'] . '",
                            "contactPhone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '",
                            "dniNumber": "' . $dni . '",
                            "dniType": "' . $dniType . '",
                            "billingAddress": {
                                "street1": "' . $street1 . '",
                                "street2": "' . $street2 . '",
                                "city": "' . $address->city . '",
                                "state": "' . $conf->get_state($address->id_state) . '",
                                "country": "' . $country . '",
                                "postalCode": "' . $address->postcode . '",
                                "phone": "' . ((!empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                            }      
                        },
                        "creditCard": {
                        },
                        "extraParameters": {
                            "INSTALLMENTS_NUMBER": '.$post['cuotas'].'
                        },
                        "type": "AUTHORIZATION_AND_CAPTURE",
                        "paymentMethod": "' . $paymentMethod . '",
                        "paymentCountry": "' . $country . '",
                        "deviceSessionId": "' . $_deviceSessionId . '",
                        "ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '",
                        "userAgent": "' . $_SERVER['HTTP_USER_AGENT'] . '",
                        "cookie": "' . md5($this->context->cookie->timestamp) . '"  
                    },
                    "test": '.$test.'          
                }
                ';

                //    LOG    //
                $this->logtxt(" Resquest Payment With Codensa: ");
                $this->logtxt($resquestPaymentWithCodensaLog);
                $responsePaymentWithCodensa = $conf->sendJson($resquestPaymentWithCodensa);
                $this->logtxt(" Response Payment With Codensa: ");
                $this->logtxt(json_encode($responsePaymentWithCodensa));
                $this->logtxt(" ");
                $subs = substr($post['numerot'], 0, (strlen($post['numerot']) - 4));
                $nueva = '';

                for ($i = 0; $i <= strlen($subs); $i++) {
                    $nueva = $nueva . '*';
                }

                $resquestPaymentWithCodensa = str_replace('"number":"' . $subs, '"number":"' . $nueva, $resquestPaymentWithCodensa);
                $resquestPaymentWithCodensa = str_replace('"securityCode":"' . $post['codigot'], '"securityCode":"' . '****', $resquestPaymentWithCodensa);
                // colector Errores Payu
                $error_pay = array();

                if ($responsePaymentWithCodensa['code'] === 'ERROR') {
                    $conf->error_payu($id_order, $customer->id, $resquestPaymentWithCodensa, $responsePaymentWithCodensa, 'Tarjeta_credito', $responsePaymentWithCodensa['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    $error_pay[] = $responsePaymentWithCodensa;
                } elseif ($responsePaymentWithCodensa['code'] === 'SUCCESS' && ( $responsePaymentWithCodensa['transactionResponse']['state'] === 'PENDING' || $responsePaymentWithCodensa['transactionResponse']['state'] === 'APPROVED' ) && $responsePaymentWithCodensa['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {
                    $conf->pago_payu($id_order, $customer->id, $resquestPaymentWithCodensa, $responsePaymentWithCodensa, 'Tarjeta_credito', $responsePaymentWithCodensa['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    if ($responsePaymentWithCodensa['transactionResponse']['state'] === 'APPROVED') { //
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
                    $conf->error_payu($id_order, $customer->id, $resquestPaymentWithCodensa, $responsePaymentWithCodensa, 'Tarjeta_credito', $responsePaymentWithCodensa['transactionResponse']['state'], $this->context->cart->id, $id_address);
                    $error_pay[] = array('ERROR' => 'La entidad financiera rechazo la transacción. <b>Status: ' . $responsePaymentWithCodensa['transactionResponse']['state'] . '</b>.');
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
