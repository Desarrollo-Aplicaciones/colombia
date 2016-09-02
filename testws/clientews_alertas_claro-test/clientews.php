<?php 

class JSON_WebClient{
    private $URL;
    public function __construct($url){
        $this->URL = $url;
    }
 
    public function call($method, $args, $successCallback = false, $errorCallback = false){
        // se instancia un objeto curl
        $ch = curl_init();
        
        // se establecen las cabeceras y demás propiedades de la solicitud
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_URL, $this->URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        // se muestra los datos a enviar
        echo '<h2> Datos enviados </h2>';
        echo' <textarea  rows="7" cols="132" >'; 
        print_r(json_encode(array('args'=>$args,'method'=>$method)));
        echo '</textarea>';
        // se adjuntan el cuerpo de la solicitud
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('args'=>$args,'method'=>$method)));
        // se envía la solicitud        
        $resposeText = curl_exec($ch);
        $resposeInfo = curl_getinfo($ch);
        
         // si el servicio web responde un código HTTP 200  se llama a la función onSucceededCallback en caso contrario a la función onErrorCallback         
        if($resposeInfo["http_code"] == 200){
            if($successCallback)
                call_user_func($successCallback, json_decode($resposeText,true));
        } else {
            if($errorCallback)
                call_user_func($errorCallback, json_decode($resposeText,true));
        }
    }
}