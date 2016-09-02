<?php 
require_once 'lib.php';

class JSON_WebService {
    private $methods, $args, $strcall, $data_array;
    public function __construct($rawData) {
        
        $this->data_array= json_decode($rawData,TRUE);
        $this->strcall = $this->data_array['method']; //str_replace($_SERVER["SCRIPT_NAME"]."/", "", $_SERVER["REQUEST_URI"]);
        $this->args =  json_encode( $this->data_array['args']);

        $this->methods = array();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json; charset=utf-8');
        
      
    }
 
    public function Register($name) {
        $this->methods[$name] = true;
    }
 
    public function Remove($name) {
        $this->methods[$name] = false;
    }
 
    private function call($name, $args) {
      
     
        if ($this->methods[$name] == true) {
            $result = call_user_func($name, $args);
           //$result = call_user_func_array($name, $args);
            return json_encode($result);
        } else {
               header("HTTP/1.0 403 Not Found ");
        }
    }
 
    function start() {
        try{
            if(!function_exists($this->strcall))
                throw new Exception("Function '".$this->strcall."' does not exist.");
            if (!$this->methods[$this->strcall])
                throw new Exception("Access denied for function '".$this->strcall."'.");
 
            header("HTTP/1.0 200 OK");
            print $this->call($this->strcall, json_decode($this->args,true));
        }
        catch(Exception $e){
            header("HTTP/1.0 500 Internal server error");
            print json_encode(
                array(
                    "message" => $e->getMessage(),
                    "code" => $e->getCode(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                    "stackTrace" => $e->getTrace(),
                    "status" => array("message" => "Internal server error", "code" => "500")
                )
            );
        }
    }
    

}
 



//Obtiene el contenido de la solicitud POST
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : ''; 
 
//Instancia de la clase JSON_WebService
$server = new JSON_WebService($HTTP_RAW_POST_DATA);


/*
 * Registar metodos del web service
 */
$server->register("getTokenStr");

//Retorna los productos
$server->register("getProductos");

//Retorna los productos de una categorias
$server->register("getCategoria");

//Retorna los categorias
$server->register("getCategorias");

//Retorna los categorias
$server->register("getBanners");
//actaulizar estado de ordenes claro
$server->register("setOrderState");
  
//Inicializa el servicio
$server->start();
  



/*
 * Define los metodos del servicio web
 */
function getServerTime($format){ 

    return date($format);
}

function getTokenStr($args)
{
   $obj = new Lib();
   return $obj->getToken();
  
}

// retorna productos
function getProductos($args)
{ 
    if(count($args)==5 || count($args)==3)
    {  
        
    $obj = new Lib();
    
  
    error_log(date( 'Y-m-d H:i:s').": ".print_r($args['Buscar'],true).PHP_EOL, 3, "/home/ubuntu/modules/Ordenes_de_Suministros_FTP/my-errors.log");

        $args['Buscar'] = utf8_decode($args['Buscar']);
        if (is_numeric($obj->remomeCharSql($args['Pagina']))) {
            $args['Pagina'] = $obj->remomeCharSql($args['Pagina']);
        } else {
            $args['Pagina'] = 1;
        }
        if (is_numeric($obj->remomeCharSql($args['Filas']))) {
            $args['Filas'] = $obj->remomeCharSql($args['Filas']);
        } else {
            $args['Filas'] = 10;
        }
        
if(isset($args['Ordenar'])) {     
        $args['Ordenar']=$obj->remomeCharSql($args['Ordenar']);
         $args['Campo']=$obj->remomeCharSql($args['Campo']);
}
       
        if ($args['Buscar'] != NULL && $args['Buscar'] != "" ) {
            if(isset($args['Ordenar'])&&($args['Ordenar']==='A'||$args['Ordenar']==='Z')&& is_string($args['Campo'])){
                
            return $obj->buscarProductos($args['Buscar'], $args['Pagina'], $args['Filas'],$args['Ordenar'],$args['Campo']);      
            }
           
         return $obj->buscarProductos($args['Buscar'], $args['Pagina'], $args['Filas']);  
        }
    }  
    return false;    
    
}

function getCategoria($args)
{
    
    
    if(count($args)==5 || count($args)==3)
    { 
       
    $obj = new Lib();

    if (is_numeric($obj->remomeCharSql($args['Pagina']))) {
        if(((int)$args['Pagina'])>0){
            $args['Pagina'] = $obj->remomeCharSql($args['Pagina']);
        } else{
            $args['Pagina']=1;
        }
        } else {
            $args['Pagina'] = 1;
        }
        if (is_numeric($obj->remomeCharSql($args['Filas']))) {
            if(((int)$args['Filas'])>0){
            $args['Filas'] = $obj->remomeCharSql($args['Filas']);
            }else{
            $args['Filas']=10;    
            }
        } else {
            $args['Filas'] = 10;
        }
        
        if ($args['categoria'] != NULL && $args['categoria'] != "" ) {
            
 $categorias=array(
                  'sin_formula_medica'=>array(587),
                   'con_formula_medica'=>array(477),
                   'cuidado_personal'=>array(766),
                   'bellza'=>array(434,433),
                   'sexualidad'=>array(636,640),
                   'mama_y_babe'=>array(555));
              
 
  
   foreach ($categorias as $key => $value) {
       
    if($key==$args['categoria']){
        

         if(isset($args['Ordenar'])&&($args['Ordenar']==='A'||$args['Ordenar']==='Z')&& is_string($args['Campo'])){
            return $obj->buscarProductosCategoria($value, $args['Pagina'], $args['Filas'],$args['Ordenar'],$args['Campo']);      
            }
            return $obj->buscarProductosCategoria($value, $args['Pagina'], $args['Filas']);
    }
        
        }
    }  
       
    }
   return false;
}



function getCategorias($args)
{

    if ($args == 'farmalisto_colombia') {
        $categorias[] = array('color' => '#FF0000',
            'nombre' => 'Sin Fórmula Médica',
            'url_img' => 'http://127.0.0.1/test.farmalisto.com.co/10611-home_default/img.jpg',
            'value' => 'sin_formula_medica',
            'ids_categorias'=>'587',
            'orden'=>'1');

        $categorias[] = array('color' => '#00FF80',
            'nombre' => 'Con Fórmula Médica',
            'url_img' => 'http://127.0.0.1/test.farmalisto.com.co/10611-home_default/img.jpg',
            'value' => 'con_formula_medica',
            'ids_categorias'=>'477',
            'orden'=>'2');

        $categorias[] = array('color' => '#FF0000',
            'nombre' => 'Cuidado Personal',
            'url_img' => 'http://www.farmalisto.com.co/img/demos/demo1.jpg',
            'value' => 'cuidado_personal',
            'ids_categorias'=>'766',
            'orden'=>'3');

        $categorias[] = array('color' => '#FF0000',
            'nombre' => 'Belleza',
            'url_img' => 'http://www.farmalisto.com.co/img/demos/demo1.jpg',
            'value' => 'bellza',
            'ids_categorias'=>'434,433',
            'orden'=>'4');

        $categorias[] = array('color' => '#FF0000',
            'nombre' => 'Sexualidad',
            'url_img' => 'http://www.farmalisto.com.co/img/demos/demo1.jpg',
            'value' => 'sexualidad',
            'ids_categorias'=>'636,640',
            'orden'=>'5');

        $categorias[] = array('color' => '#FF0000',
            'nombre' => 'Mamá y bebé',
            'url_img' => 'http://www.farmalisto.com.co/img/demos/demo1.jpg',
            'value' => 'mama_y_babe',
            'ids_categorias'=>'555',
            'orden'=>'6');

        return $categorias;
    }
    return false;
}

function getBanners($args)
{
   
     if ($args == 'banners_colombia') {
         
                                    $banners[]=array('titulo'=>' ',
                          'url_img'=>'http://www.farmalisto.com.co/img/demos/demo1.jpg',
                           'descripcion'=>' ',
                           'enlace'=>' ');
                                    
                           $banners[]=array('titulo'=>' ',
                          'url_img'=>'http://www.farmalisto.com.co/img/demos/demo3.jpg',
                           'descripcion'=>' ',
                           'enlace'=>' ');
                        
                          
                                    $banners[]=array('titulo'=>' ',
                          'url_img'=>'http://www.farmalisto.com.co/img/demos/demo2.jpg',
                           'descripcion'=>' ',
                           'enlace'=>' ');

                                    
                                    return $banners;
         
     }
    
}

function setOrderState($args){

$errors =  array();

    if(isset($args) && is_array($args) && count($args) == 9){
        if(crypt('xnsrqkuwo6', $args['passhash']) == $args['passhash'] && $args['user'] == md5('alertasclaro')) {

            if(!is_int($args['guia']))
                $errors[] = 'El numero de guía no es de tipo entero';
            if(!Lib::verifyDate($args['datetime']))
                $errors[] = 'El formato de fecha no es correcto';
            if(!Lib::validateGeoCoords($args['latitud'],$args['longitud']))
                $errors[] = 'Las coordenadas geográficas no son validas';
            $state_order = strtolower($args['state']);
            if($state_order != 'entregado' && $state_order != 'rechazado')
                $errors[] = 'El estado (state) de la guía no es un valor valido (entregado o rechazado)';
            if(!count($errors)>0){
                $obj = new Lib(); 
                return json_encode($obj->setOrderState((int)$args['guia'],Lib::get_conf_status($state_order),$args['datetime'],$args['latitud'],$args['longitud'],$args['tipo'],$args['motivo'],$args['user'])); 
            }else{
                return json_encode($errors);
            }
        }else{
            $respose = 'La contraseña o el usuario no son validos.';
        }
    }else{
        $respose = 'El tipo de parámetros o el numero de parámetros no es correcto.';
    }
   return json_encode($respose);
  

}


