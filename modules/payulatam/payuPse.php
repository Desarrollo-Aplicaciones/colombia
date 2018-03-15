<?php
ini_set('max_execution_time', 300);
$useSSL = true;
require_once (dirname(__FILE__) . '/../../config/config.inc.php');
require_once (dirname(__FILE__) . '/../../init.php');
require_once (_PS_MODULE_DIR_ . 'payulatam/payulatam.php');
require_once (_PS_MODULE_DIR_ . 'payulatam/config.php');
require_once (_PS_MODULE_DIR_ . 'payulatam/paymentws.php');

class PayuPse extends PayUControllerWS
{
    
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
    }
    
    public function process()
    {
        parent::process();
        $conf = new ConfPayu();
        
        // url para re intentos de pago
        $url_reintento = $_SERVER['HTTP_REFERER'];
        if (! strpos($_SERVER['HTTP_REFERER'], '&step=')) {
            $url_reintento .= '&step=3';
        }
        // vaciar errores en el intento de pago anterior
        if (isset($this->context->cookie->{'error_pay'})) {
            unset($this->context->cookie->{'error_pay'});
        }
        
        $id_cart = $this->getIdCart();
        
        $status_cart = PasarelaPagoCore::is_cart_pay_process($id_cart);
        
        $contador = 0;
        
        $confirmationUrl = $this->_getConfirmationUrl();
        
        while ($confirmationUrl != false || (isset($status_cart['in_pay']) && isset($status_cart['status']))) {
            
            sleep(2);
            
            $confirmationUrl = $this->_getConfirmationUrl();
            $status_cart = PasarelaPagoCore::is_cart_pay_process($id_cart);
            
            if ($confirmationUrl != false) {
                
                $redirect = base64_decode(unserialize($confirmationUrl));
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                Tools::redirectLink($redirect);
                exit();
            }
            
            if ($contador == 5) {
                $redirectLink = 'index.php?controller=history';
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                Tools::redirect($redirectLink);
                exit();
            }
            
            $contador ++;
        }
        
        $id_cart = $this->getIdCart();
        // !Context::getContext()->cart->orderExists() &&
        
        if ($id_cart != false && Tools::getValue('pse_bank') && Tools::getValue('name_bank') && ! empty(Tools::getValue('pse_bank'))) {
            
            PasarelaPagoCore::set_cart_pay_process($id_cart, 1);
            // reglas de carrito para bines
            $payulatam = new PayULatam();
            $payulatam->addCartRulePse($_POST['pse_bank']);
            $params = $this->initParams();
            $conf = new ConfPayu();
            $keysPayu = $conf->keys();
            $customer = new Customer((int) $this->context->cart->id_customer);
            $id_address = $this->context->cart->id_address_delivery;
            $id_order = 0;
            $reference_code = $customer->id . '_' . $id_cart . '_' . $id_order . '_' . $id_address;
            $varRandn = $conf->randString();
            $varRandc = $conf->randString();
            setcookie($varRandn, $varRandc, time() + 900);
            
            $browser = array(
                'ipAddress' => $_SERVER['SERVER_ADDR'],
                'userAgent' => $_SERVER['HTTP_USER_AGENT']
            );
            
            $address = new Address($this->context->cart->id_address_delivery);
            $dni = $conf->get_dni($this->context->cart->id_address_delivery);
            $intentos = $conf->count_pay_cart($id_cart);
            
            $currency = '';
            
            if ($conf->isTest()) {
                
                $currency = 'USD';
            } else {
                
                $currency = $params[9]['currency'];
            }
            
            $total_tax = PasarelaPagoCore::get_total_tax($id_cart);
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
                "referenceCode":"' . $params[2]['referenceCode'] . '_' . $intentos . '",
                "description":"' . $reference_code . '",
                "language":"es",
                "notifyUrl":"' . $conf->urlv() . '",
                "signature":"' . $conf->sing($params[2]['referenceCode'] . '_' . $intentos . '~' . $params[4]['amount'] . '~' . $currency) . '",
                "buyer":{
                    "fullName":"' . $this->context->customer->firstname . ' ' . $this->context->customer->lastname . '",
                    "emailAddress":"' . $params[5]['buyerEmail'] . '",
                    "dniNumber":"' . $dni . '",
                    "shippingAddress":{
                        "street1":"' . substr($address->address1, 0, 99) . '",
                        "city":"' . $address->city . '",
                        "state":"' . $conf->get_state($address->id_state) . '",
                        "country":"' . $this->context->country->iso_code . '",
                        "phone":"' . ((! empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
                    }
                },
                "additionalValues":{
                    "TX_VALUE":{
                        "value":' . $params[4]['amount'] . ',
                        "currency":"' . $currency . '"
                    },
                    "TX_TAX":{
                     "value":' . $total_tax . ',
                     "currency":"' . $params[9]['currency'] . '"
                 },
                 "TX_TAX_RETURN_BASE":{
                     "value":' . ($total_tax == 0.00 ? 0.00 : ($params[4]['amount'] - $total_tax)) . ',
                     "currency":"' . $params[9]['currency'] . '"
                 }
             }
         },
         "payer":{
            "fullName":"' . $this->context->customer->firstname . ' ' . $this->context->customer->lastname . '",
            "emailAddress":"' . $params[5]['buyerEmail'] . '",
            "dniNumber":"' . $dni . '",
            "contactPhone":"' . ((! empty($address->phone)) ? $address->phone : $address->phone_mobile) . '"
        },
        "ipAddress":"' . $browser['ipAddress'] . '",
        "cookie":"' . $varRandn . '",
        "userAgent":"' . $browser['userAgent'] . '",
        "type":"AUTHORIZATION_AND_CAPTURE",
        "paymentMethod":"PSE",
        "extraParameters":{
            "PSE_REFERENCE1":"' . $browser['ipAddress'] . '",
            "FINANCIAL_INSTITUTION_CODE":"' . Tools::getValue('pse_bank') . '",
            "FINANCIAL_INSTITUTION_NAME":"' . Tools::getValue('name_bank') . '",
            "USER_TYPE":"' . Tools::getValue('pse_tipoCliente') . '",
            "PSE_REFERENCE2":"' . Tools::getValue('pse_docType') . '",
            "PSE_REFERENCE3":"' . Tools::getValue('pse_docNumber') . '"
        }
    }
}
';
            
            $response = $conf->sendJson($data);
            
            if ($response['code'] === 'ERROR') {
                
                $conf->error_payu($id_order, $customer->id, $data, $response, 'PSE', $response['transactionResponse']['state'], $id_car, $id_address);
                $error_pay[] = $response;
            } elseif ($response['code'] === 'SUCCESS' && $response['transactionResponse']['state'] === 'PENDING' && $response['transactionResponse']['responseMessage'] != 'ERROR_CONVERTING_TRANSACTION_AMOUNTS') {
                $this->createPendingOrder(array(), 'Pse', 'El sistema esta en espera de la confirmación de la pasarela de pago.', 'PAYU_WAITING_PAYMENT');
                $order = $conf->get_order($id_cart);
                $id_order = $order['id_order'];
                $conf->pago_payu($id_order, $customer->id, $data, $response, 'Pse', $response['code'], $id_cart, $id_address);
                $url_base64 = strtr(base64_encode($response['transactionResponse']['extraParameters']['BANK_URL']), '+/=', '-_,');
                $string_send = __PS_BASE_URI__ . 'order-confirmation.php?key=' . $customer->secure_key . '&id_cart=' . (int) $id_cart . '&id_module=105&id_order=' . (int) $order['id_order'] . '&bankdest2=' . $url_base64;
                $url = serialize(base64_encode($string_send));
                $this->_setConfirmationUrl($id_cart, $url);
                PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
                Tools::redirectLink($string_send);
                exit();
            } else {
                $conf->error_payu($id_order, $customer->id, $data, $response, 'PSE', $response['transactionResponse']['state'], $id_car, $id_address);
                $error_pay[] = $response['transactionResponse']; // array('ERROR'=>'La entidad financiera del medio de pago seleccionado, no responde.');
            }
            $this->context->cookie->{'error_pay'} = json_encode($error_pay);
            PasarelaPagoCore::set_cart_pay_process($id_cart, 0);
            
            Tools::redirectLink($url_reintento);
            exit();
        } else {
            $this->context->cookie->{'error_pay'} = json_encode(array(
                'ERROR' => 'Valida tus datos he intenta de nuevo.'
            ));
            Tools::redirectLink($url_reintento);
            exit();
        }
    }
    
    public function displayContent()
    {
        parent::displayContent();
        
        self::$smarty->display(_PS_MODULE_DIR_ . 'payulatam/tpl/success.tpl');
    }
    
    public function getIdCart()
    {
        $context = Context::getContext();
        
        if (isset($context->cart->id)) {
            return $context->cart->id;
        }
        
        if (isset($context->cookie->id_cart)) {
            return $context->cookie->id_cart;
        }
        
        return false;
    }
    
    private function _setConfirmationUrl($id_cart, $url)
    {
        $id_customer = Context::getContext()->customer->id;
        Db::getInstance()->insert('payu_confirmation_url', array(
            'id_cart' => (int) $id_cart,
            'confirmation_url' => pSQL($url),
            'id_customer' => (int) $id_customer
        ));
    }
    
    private function _getConfirmationUrl()
    {
        $id_customer = Context::getContext()->customer->id;
        $sql = 'SELECT confirmation_url as url
                FROM ' . _DB_PREFIX_ . 'payu_confirmation_url u
                INNER JOIN ( SELECT id_cart FROM ' . _DB_PREFIX_ . 'cart WHERE id_customer = ' . $id_customer . '
                ORDER BY id_cart DESC LIMIT 1) t1 ON (u.id_cart = t1.id_cart)
                WHERE id_customer = ' . $id_customer . '
                AND TIMESTAMPDIFF(SECOND,u.time_stamp,CURRENT_TIMESTAMP())  < 300
                ORDER BY id DESC
                LIMIT 1';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            if (isset($results[0]['url'])) {
                return $results[0]['url'];
            }
        }
        
        return false;
    }
}

$payuPse = new PayuPse();

$payuPse->run();