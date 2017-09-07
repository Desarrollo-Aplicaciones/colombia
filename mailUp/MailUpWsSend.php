<?php
// The sample code described herein is provided on an "as is" basis, without warranty of any kind. 
// MailUp shall not be liable for any direct, indirect or consequential damages or costs of any type arising out of any action taken by you or others related to the sample code.
class MailUpWsSend {
    protected $WSDLUrl = "http://services.mailupnet.it/MailupSend.asmx?WSDL";
    private $soapClient;
    private $xmlResponse;
    protected $domResult;
    function __construct() {
        $this->soapClient = new SoapClient($this->WSDLUrl, array("trace" => 1, "exceptions" => 0));
    }
     
    function __destruct() {
        unset($this->soapClient);
    }
     
    public function getFunctions() {
        print_r($this->soapClient->__getFunctions());
    }
     
    public function loginFromId() {
        try {

            $loginData = array("user" => "m82227",
                           "pwd" => "f4rm4l1st0",
                           "consoleId" => "82227");
             
            $this->soapClient->loginFromId($loginData);
            
            if ($this->readReturnCode("LoginFromId","errorCode") != 0) {
                echo "<br /><br />Error in LoginFromId: ". $this->readReturnCode("LoginFromId","errorDescription");
                die();
            }
            else $this->accessKey = $this->readReturnCode("LoginFromId","accessKey");
                //echo "<br>AccesKey: ". $this->accessKey;
        } catch (SoapFault $soapFault) {   
            var_dump($soapFault);
        }
    }
     
    public function logout() {
        try {

            $this->soapClient->Logout(array("accessKey" => $this->accessKey));
            
            if ($this->readReturnCode("Logout","errorCode") != 0)
                echo "<br /><br />Error in Logout". $this->readReturnCode("Logout","errorDescription");
            
        } catch (SoapFault $soapFault) {   
            var_dump($soapFault);
        }
    }
    private function readReturnCode($func, $param) {
        $this->xmlResponse = $this->soapClient->__getLastResponse();
        $dom = new DomDocument();
        $dom->loadXML($this->xmlResponse) or die("(1)XML file is not valid!");
        $xmlResult = $dom->getElementsByTagName($func."Result");
        $this->domResult = new DomDocument();
        $this->domResult->LoadXML(html_entity_decode($xmlResult->item(0)->nodeValue)) or die("(2)XML file is not valid!");
        $rCode = $this->domResult->getElementsByTagName($param);
        return $rCode->item(0)->nodeValue;
    }
    
    public function sendNewsletter($sendData){
        $result = $this->soapClient->SendNewsletter($sendData);
        print_r($result);

    }
    //... other functions...
    // public function functionName(...) {...}
}

$WsSend = new MailUpWsSend();
$WsSend->loginFromId(); 
//$WsSend->getFunctions();exit;
// use $WsSend->functionName(...) to call other methods 

$sendNewsletterData = array("accessKey"=>$WsSend->accessKey,
                       "listID" => "1",
                       "newsletterID" => "1503",
                       "options" => array(                       
                                    array("Key" =>"from_email", "Value" => "juan.valdes@farmalisto.com.co"),
                                    array("Key" =>"from_name", "Value" => "Prueba mail"),
                                    array("Key" =>"send_to", "Value" => "RECIPIENTS"),
                                    array("Key" =>"recipients", "Value" => "juan.valdes@farmalisto.com.co;juanmemo133@gmail.com"),
                                    /*array("Key" =>"campo1", "Value" => "Juan1;Juan2"),
                                    array("Key" =>"campo2", "Value" => "Valdes1;Valdes2")*/
                                    )
                    );

for($i=count($sendNewsletterData['options']);$i<6;$i++) {
    $sendNewsletterData['options'][$i]['key'] = "campo".$i;
    $sendNewsletterData['options'][$i]['value'] = "Valor".$i;
}
//print_r($key);exit;
//print_r($sendNewsletterData);
//exit;
//$send = $WsSend->sendNewsletter($sendNewsletterData);

//print_r($WsSend->readReturnCode("SendNewsletterResponse","errorCode"));

//$WsSend->logout();

/*
 * Rest mailup
 */

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://send.mailup.com/API/v2.0/messages/sendtemplate",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{ \"Template\": 694, \"Subject\": \"Mensaje de prueba desde la plantilla\", \"From\": {\"Name\": \"Test User\", \"Email\": \"test@example.com\"}, \"A\": [], \"Bcc\": [], \"ReplyTo\": null, \"CharSet \": [{\"Nombre\": \"Max\", \"Email\": \"info@example.com\"}] \":\" utf-8 \", \" ExtendedHeaders \": null, \" Attachments \": null, \" EmbeddedImages \": null, \" XSmtpAPI \": null,}",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "authorization: Basic RmFybWFsaXMxOlNtc2Zhcm1hLjEyMw==",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


/*$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{ \"from\":\"InfoSMS\", \"to\":\"573183909690\", \"text\":\"Test SMS.\" }",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "authorization: Basic RmFybWFsaXMxOlNtc2Zhcm1hLjEyMw==",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}*/
