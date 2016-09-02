<?php
include(dirname(__FILE__).'/../config/config.inc.php');
include(dirname(__FILE__).'/../init.php');

class Lib extends FrontController {
    
    private function imgs_product($id_product=null)
    {
        if($id_product!=null)
        {   $array_img=null;
            $query="SELECT i.id_image
			FROM ps_image i
			LEFT JOIN ps_image_lang il ON (i.id_image = il.id_image)
			WHERE i.id_product = ".$id_product." AND il.id_lang = 1
			ORDER BY i.position  ASC";
            
        if ($results = Db::getInstance()->ExecuteS($query)){
            foreach ($results as $value) {
                
                $array_img[] = _PS_BASE_URL_.__PS_BASE_URI__.$value['id_image']."-large_default/img.jpg";
            }
         if($array_img!=NULL)
             
             return $array_img;
        }     
 
        }
         $array_img[]=  _PS_BASE_URL_.__PS_BASE_URI__.'img/p/es-default-large_default.jpg';
        return $array_img;
    }


    function buscarProductos($buscar, $pagina = 1, $filas = 10, $ordenar = null, $campo = null) {
        $array_productos = NULL;


        $buscar = utf8_encode($this->remomeCharSql($this->sanear_string($buscar)));


        if ($buscar != NULL && strlen(trim($buscar)) >= 4) {
            if ($filas > 20) {
                $filas = 20;
            }

            /*
             * Validacioner ordenar
             */
            if ($ordenar != NULL && $campo != NULL) {

                if ($ordenar === 'A') {
                    $ordenar = "ASC";
                }
                if ($ordenar === 'Z') {
                    $ordenar = "DESC";
                }
            }
            if ($ordenar===NULL) {
             $ordenar = "DESC";  
             $campo="position"; 
            }
            
            // cambiar orden de campo id_product a position
            if(isset($campo) && strtolower($campo) == 'id_product'){
               $campo="position"; 
               $ordenar = "DESC";  
            }
 // return 'campo -> '.$campo.' ordener -> '.$ordenar;
            $search = new Search();

            $results = $search->findWsMobile(1, $buscar, $pagina, $filas, $campo, $ordenar, FALSE, FALSE);

            if ((int) $results['total'] > 0) {

                $total_filas = (int) $results['total'];
                $total_paginas = ceil($total_filas / $filas);

                $inicio = 0;
if($pagina <= $total_paginas ){
                
                if ( $pagina === 1) {
                    $pagina = 1;
                    $inicio = 0;
                } else {
                    $inicio = ($pagina - 1) * $filas;
                }

                $array_productos['total_paginas'] = $total_paginas;
                $array_productos['total_filas'] = $total_filas;
                $array_productos['pagina'] = $pagina;
                $array_productos['filas'] = $filas;

                $array_prod = NULL;
                if ($results['result'] != NULL) {
                    foreach ($results['result'] as $value) {

                        $value['description'] = strip_tags($value['description']);
                        $value['description_short'] = strip_tags($value['description_short']);
                        $value['price'] = number_format( $value['price'] ,2, '.', '');
                         $value['position'] = 1;

                        $array_prod[] = array($value, 'imgs' => $this->imgs_product($value['id_product']), 'img_laboratorio' => 'http://www.farmalisto.com.co/img/tmp/manufacturer_mini_74_1.jpg');
                    }
                    if ($array_prod != NULL) {
                        $array_productos['productos'] = $array_prod;
                        return $array_productos;
                    }
                }
            }
        }
        }
        return false;
    }

    public function buscarProductosCategoria($ids_categoria, $pagina=1,$filas=10, $ordenar=null,$campo=null)
  { 
     $array_productos=NULL;   
    
     $buscar= $ids_categoria;
     
$busqueda=NULL;

     foreach ($buscar as $value) {
         $var=str_replace(" ", "", $this->remomeCharSql($value)); 
         if(is_numeric($var))
         $busqueda[]=$var;        
     }
       
 if($busqueda!=NULL)       
 {  
        
        $total_filas = 0;
        $query = " select COUNT(prod.id_product) total 
from 
ps_product prod
INNER JOIN 
ps_product_lang prodl on(prod.id_product=prodl.id_product)
INNER JOIN ps_category_product cat_prod ON (cat_prod.id_product=prod.id_product)
INNER JOIN ps_product_shop prods ON (prod.id_product=prods.id_product AND prod.active = prods.active)
INNER JOIN ps_category_lang cat_prodl ON (cat_prod.id_category=cat_prodl.id_category) 
WHERE cat_prodl.id_category in ('".implode("','",$busqueda)."')"
. " and prod.active=1 AND prods.active=1 and prod.active=1 and prod.is_virtual=0
AND prods.visibility='both'
 limit 1;";
        
       
if ($results = Db::getInstance()->ExecuteS($query)) {
            foreach ($results as $value) {
                $total_filas = (int) $value['total'];
            }
        }
        
        $total_paginas = ceil($total_filas / $filas);

        $inicio = 0;
        
if($pagina <= $total_paginas){
        if ( $pagina === 1) {
            $pagina = 1;
            $inicio = 0;
        } else {
            $inicio = ($pagina - 1) * $filas;
        }
        
$array_productos['total_paginas']=$total_paginas;
$array_productos['total_filas']=$total_filas;
$array_productos['pagina']=$pagina;
$array_productos['filas']=$filas;

$query = " SELECT prod.id_product , prod.reference, prodl.`name`,prodl.description, prodl.description_short , cat_prod.id_category, 
                  CASE prod.id_tax_rules_group
                  WHEN  0 THEN prod.price
                  ELSE prod.price + (prod.price* tax.rate/100)
                  END
                  AS `price`, prod.price as precio
          FROM ps_product prod
          INNER JOIN ps_product_lang prodl on(prod.id_product=prodl.id_product)
          INNER JOIN ps_category_product cat_prod ON (cat_prod.id_product=prod.id_product)
          INNER JOIN ps_product_shop prods ON (prod.id_product=prods.id_product AND prod.active = prods.active)
          INNER JOIN ps_category_lang cat_prodl ON (cat_prod.id_category=cat_prodl.id_category)
          LEFT JOIN ps_tax_rule taxr ON(prod.id_tax_rules_group = taxr.id_tax_rules_group)
          LEFT JOIN ps_tax tax ON(taxr.id_tax = tax.id_tax) 
          WHERE cat_prodl.id_category in ('".implode("','",$busqueda)."') "
      . " AND prod.active=1 AND prods.active=1 AND prod.active=1 
          AND prod.is_virtual=0 AND prods.visibility='both'; ";

   /*
 * Validacioner ordenar
 */
if($ordenar!=NULL && $campo!=NULL)
{
    
     if($ordenar==='A')
    {
        $ordenar="ASC";
    }
    if($ordenar==='Z'){
        $ordenar="DESC";
    }
    
    $query.=" ORDER BY `".$campo."` ".$ordenar;
   
}else{   
      
$query.=" ORDER BY prodl.id_product ASC";
    }
    
$query.=" LIMIT ".$inicio.", ".$filas.";";

$array_prod=NULL;

          if ($results = Db::getInstance()->ExecuteS($query)) {
              foreach ($results as $value) {
                 
                  $value['description']=strip_tags($value['description']);
                  $value['description_short']=strip_tags($value['description_short']);
                  $value['price'] = Tools::ps_round($value['price'], 2);
                  //$value['price'] = number_format( $value['price'] ,2, '.', '');
                  $array_prod[]= array($value,'imgs'=>$this->imgs_product($value['id_product']),'img_laboratorio'=>'http://www.farmalisto.com.co/img/tmp/manufacturer_mini_74_1.jpg');
                   
              }
              if($array_prod!=NULL)
              {
               $array_productos['productos']=$array_prod;  
               return $array_productos;
              }
        }
 
    } 
 }
  
    
    return false;
}

function remomeCharSql($string, $length = NULL){
	$string = trim($string);
        
        $array=array("\"","#","$","%","&","'","(",")","*","+",",","-","/",":",";","<","=",">","?","@","[","]","^","`","{","|","}","~");
	$string = utf8_decode($string);
	$string = htmlentities($string, ENT_NOQUOTES| ENT_IGNORE, "UTF-8");
	$string = str_replace($array, "", $string);        
        $string = preg_replace( "/([ ]+)/", " ", $string );
	
	$length = intval($length);
	if ($length > 0){
		$string = substr($string, 0, $length);
	}
	return $string;
}

function getToken()
{
   $salt = $this->randString();
   // genera token de forma aleatoria
   $token = md5(sha1($salt).md5(uniqid(microtime(), true)));
   // genera fecha de generación del token
   //$tokenTime = time();
   // escribe la información del token en sesión para poder
   //$_SESSION['AntiCsrf']['webservicemobile_token'] = array('token'=>$token, 'time'=>$tokenTime); 
   $time_token= $this->sumarMinutos(time(),15);
   
    $query="insert into ps_token_mobile (token, time) VALUES('".$token."','".date('Y-m-d H:i:s',$time_token)."' ); ";
   
    if ($results = Db::getInstance()->ExecuteS($query)) {
   
   return $token;
    }
   
   
  return false;

  
}

function randString ($length = 32)
{  
$string = "";
$possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXY";
$i = 0;
while ($i < $length)
 {    
$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
$string .= $char;    
$i++;  
}  
return $string;
}

private function sumarMinutos($segundos,$minutos)
{
    $retorno = NULL;
            
    $mes    = date("m",$segundos);
    $dia    = date("d",$segundos);
    $anyo   = date("Y",$segundos);
    $hora   = date("H",$segundos);
    $minuto = date("i",$segundos)+ $minutos;

    $sumadeMeses = mktime($hora,$minuto,0,$mes,$dia,$anyo);

    $retorno = $sumadeMeses;

    return $retorno;
}



//public function logtxt ($text="") { 
// $fp=fopen("C:/wamp/www/archivo.txt","a+"); fwrite($fp,$text."\r\n"); fclose($fp);
//
//}


function sanear_string($string)
{

$string= utf8_encode($string);

$a = array('À', '�?', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', '�?', 'Î', '�?', '�?', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', '�?', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', '�?', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', '�?', 'Ď', '�?', '�?', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', '�?', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', '�?', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', '�?', 'Ŏ', '�?', '�?', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', '�?', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', '�?', 'ǎ', '�?', '�?', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', '�?', 'ώ', 'Ί', 'ί', 'ϊ', '�?', 'Ύ', '�?', 'ϋ', 'ΰ', 'Ή', 'ή');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
 
  return str_replace($a, $b, $string);

}
/**
 * Cambia el estado de una orden de salida
 */
public function setOrderState($id_order,$state_order,$fecha,$latitud,$longitud , $tipo, $motivo){
  $errors = array();
  $id_state_order = Configuration::get($state_order);
  if(!$this->log_state_order($id_order,$id_state_order,$fecha,$latitud,$longitud , $tipo, $motivo)){
      $errors[] = 'Error al guardar el log de la transacci�n';
    }
  if(!$this->change_state_order($id_order,$id_state_order)){
      $errors[] = 'Error cambiando el estado la gu�a';
    }
  if(!$this->set_geo_address_order($id_order,$latitud,$longitud)){
      $errors[] = 'Error al actualizar coordenadas geogr�ficas';
    }
  if(count($errors)>0){
    return json_encode($errors);
  }else{
    return json_encode('sucesfull');
  }
   return 'sucesfull';
}

/**
 * Valida formato de fecha valido para MySql
 */
static public function verifyDate($date)
{
    return DateTime::createFromFormat('Y-m-d H:i:s', $date);
}
/**
 * valida coordenadas GEO
 */
static public function validateGeoCoords($lat=null,$lng=null) {
    return (trim($lat,'0') == (float)$lat) && (trim($lng,'0') == (float)$lng);
}

static public function get_conf_status($alias){
  $estados =  array('entregado' => 'PS_OS_DELIVERED', 
                    'rechazado' => 'VERIFICACION_MANUAL' );
  if(isset($estados[$alias])){ 
     return $estados[$alias];
   }else{
     return NULL;
   }
}

 /**
   * Cambia el estado de una orden de salida
   */
protected function change_state_order($id_order,$id_state_order){
  try{
      $order = new Order((int) $id_order);  
      if($order-> getCurrentState() != (int) $id_state_order  ){ 
          $order->setCurrentState((int) $id_state_order); 
      }
}catch(Exception $e){
  exit(json_encode(var_dump(debug_backtrace(),TRUE)));
  return FALSE;
}
return TRUE;
}
/**
 * 
 */
protected function log_state_order($id_order,$state_order,$fecha,$latitud,$longitud, $tipo, $motivo){ 
  $sql = "INSERT INTO `"._DB_PREFIX_ ."log_state_order` (`id_order`, `id_order_state`, `date`, `latitud`, `longitud`, `tipo`, `motivo`,`date_mscl`) 
          VALUES (".(int)$id_order.", ".(int)$state_order.", '".($fecha)."', '".$latitud."', '".$longitud."' , '".$tipo."' , '".$motivo."','".date('Y-m-d H:i:s')."');";  
          if(Db::getInstance()->Execute($sql)){
            return TRUE;  
          }else{
            return FALSE;

          }
}
/**
 * 
 */
  protected function set_geo_address_order($id_order,$latitud,$longitud){
    $sql = "UPDATE ps_address dir 
          INNER JOIN ps_orders orden ON(dir.id_address = orden.id_address_delivery AND dir.id_address = orden.id_address_invoice)
          SET dir.latitud = '".$latitud."', dir.longitud = '".$longitud."'
          WHERE ISNULL(dir.latitud) AND ISNULL(dir.longitud) AND orden.id_order = ".(int)$id_order."; ";
          return Db::getInstance()->Execute($sql);
  }
} 
