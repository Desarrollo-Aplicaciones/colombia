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
print_r($sendNewsletterData);
exit;
//$send = $WsSend->sendNewsletter($sendNewsletterData);

//print_r($WsSend->readReturnCode("SendNewsletterResponse","errorCode"));

$WsSend->logout();