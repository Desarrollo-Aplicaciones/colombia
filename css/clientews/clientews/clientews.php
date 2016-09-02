<?php 

class JSON_WebClient{
    private $URL;
    public function __construct($url){
        $this->URL = $url;
    }
 
    public function call($method, $args, $successCallback = false, $errorCallback = false){
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_URL, $this->URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        echo'<br><br><textarea  rows="7" cols="170" >'; 
        print_r(json_encode(array('args'=>$args,'method'=>$method)));
        echo '</textarea><br><br>';
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('args'=>$args,'method'=>$method)));
                
        $resposeText = curl_exec($ch);
        $resposeInfo = curl_getinfo($ch);
        
   
         
        if($resposeInfo["http_code"] == 200){
            if($successCallback)
                call_user_func($successCallback, json_decode($resposeText));
        } else {
            if($errorCallback)
                call_user_func($errorCallback, json_decode($resposeText));
        }
    }
}