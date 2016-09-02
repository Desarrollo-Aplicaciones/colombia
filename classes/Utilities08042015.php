<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of utilFilesClass
 *
 * @author Ewing
 */

class UtilitiesCore extends ObjectModel {

 
  /**
   * @see ObjectModel::$definition
   */
  public static $definition = array(
    'table' => 'alias',
    'primary' => 'id_alias',
  );
  
  /*
   * 
   */

    public static function get_order($id_cart) {
        try {
            $sql = 'select ord.id_order 
    from ps_orders ord INNER JOIN ps_cart car ON(ord.id_cart=car.id_cart) 
    WHERE  ord.id_cart=' .(int) $id_cart . ' Limit 1';

            if ($results = Db::getInstance()->ExecuteS($sql)) {
                foreach ($results as $row) {
                    return $row;
                }
            }
            return false;
        } catch (Exception $exc) {
            
            return false;
        }
    }

    /*
     * Insertar message Asociar orden con el empleado y cliente
     */
    
        public static function add_message($id_cart,$id_customer,$id_employee,$id_order,$message,$private,$date_add) {
        try {
            // si get_id_employee no es numerico se llama el metodo get_id_employee() 
            $id_employee = is_numeric($id_employee)? $id_employee : Utilities::get_id_employee($id_employee);
            
            $sql = "insert into ps_message ( `id_cart`,`id_customer` ,  `id_employee` ,`id_order` ,  `message` ,`private` , `date_add` ) 
                    VALUES(".(int)$id_cart.",".(int)$id_customer.",".(int)$id_employee.",".(int)$id_order.",'".$message."',".$private.", '".$date_add."');";
            
                    $fp = fopen("C:\logs/log1.txt", "a+");
        fwrite($fp, $sql . "\r\n");
        fclose($fp);

            if (Db::getInstance()->Execute($sql)) {
          return true;
            }
              } catch (Exception $exc) {
            
            return false;
        }
        return false;
    }
    
    /*
     * get_id_employee
     */
    
        public static function get_id_employee($id_employee_sugar) {
            $results=NULL;
        try {
            $sql = 'SELECT id_employee FROM
                    ps_sync_emp_user 
                    WHERE id_user="'.$id_employee_sugar.'" LIMIT 1;';

            if ($results = Db::getInstance()->ExecuteS($sql) && count($results)>0) {
                return $results[0]['id_employee'];
            }
                 } catch (Exception $exc) {
            
            return false;
        }
        return false;
    }
    
            public static function get_data_employee($id_employee_sugar) {
            $results=NULL;
        try {
            $sql = 'SELECT sync.id_employee,emp.email,emp.firstname,emp.lastname,emp.id_profile 
                    FROM ps_sync_emp_user sync INNER JOIN ps_employee emp ON(sync.id_employee = emp.id_employee)
                    WHERE sync.id_user="'.$id_employee_sugar.'" LIMIT 1;';

            if ($results = Db::getInstance()->ExecuteS($sql)) { 
                return $results[0];
            }
                 } catch (Exception $exc) {
            
            return false;
        }
        return false;
    }

    public static function available_property($property_name,$id_employee,$option)
 {
                    $results=NULL;
        try {
            $sql = "select acc.configure,acc.`view` ,prop.`name`,prop.description
                    from ps_employee emp
                    INNER JOIN ps_module_access_property acc  ON(emp.id_employee = acc.id_employee)
                    INNER JOIN ps_module_propertys  prop ON( acc.id_module_property = prop.id_module_property)
                    INNER JOIN ps_module module  ON (prop.id_module = module.id_module)
                    INNER JOIN ps_module_access modacc  ON (module.id_module = modacc.id_module)
                    INNER JOIN ps_profile prof ON (modacc.id_profile = prof.id_profile AND emp.id_profile = prof.id_profile)
                    WHERE modacc.configure = 1 AND prop.`name`='".$property_name."' AND emp.id_employee = ".$id_employee;
                    if($option === 'configure'){
                        $sql.=' AND acc.configure = 1;';
                      }
                    elseif($option === 'view'){
                       $sql.=' AND acc.`view` = 1;'; 
                    }  
            if ($results = Db::getInstance()->ExecuteS($sql) ) { 
             
                    if($option === 'configure' || $option === 'view'){
                        return TRUE;
                      }
                    else {
                        return $results[0];   
                    }
            }
                 } catch (Exception $exc) {
            
            return false;
        }
        return false;
    
 }
 
 public static function is_ssl() {
    if ( isset($_SERVER['HTTPS']) ) {
        if ( 'on' == strtolower($_SERVER['HTTPS']) )
            return true;
        if ( '1' == $_SERVER['HTTPS'] )
            return true;
    } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
        return true;
    }
    return false;
}

/**
 * Reglas de entrega nocturna que aplican en un momento determinado
 */
public static function is_rules_entrega_nocturna(){

    $query="SELECT regla.id_regla_entrga_nocturna
             FROM
            "._DB_PREFIX_."regla_entrga_nocturna regla
            WHERE (regla.".Utilities::get_day_str_es()." = 1 
            AND '".Utilities::get_dateTime(2)."' BETWEEN regla.hora_inicio AND '23:59:59') OR
            ('".Utilities::get_dateTime(2)."' BETWEEN '00:00:00' AND regla.hora_fin);";
   $result = Db::getInstance()->executeS($query);


   
   if(isset($result) && !empty($result) && count($result)>0){
       
       return TRUE;
   }
   
   return FALSE;
}

/**
 * Valida si la dirección actual tiene localidad y barrio asociados, retorna un arreglo con el id de la localiad y el barrio(id_localidad,id_barrio)
 * @param type $id_addres
 * @return null
 */
public static function is_localidad_barrio($id_addres){
    
    $query ='
            SELECT addr.id_localidad, addr.id_barrio
             FROM   ps_address addr 
                WHERE addr.id_address = '.$id_addres.';';
    if(isset($id_addres) && is_int($id_addres)){
       
        $result = Db::getInstance()->executeS($query);
        if(isset($result) && !empty($result) && count($result)>0 && !empty($result[0]['id_localidad']) && !empty($result[0]['id_barrio'])){
               //  trigger_error(' || Localidad Barrio OK || ', E_USER_NOTICE);
            return array('id_localidad'=>$result[0]['id_localidad'],'id_barrio'=>$result[0]['id_barrio']);
        }
    }
     // trigger_error(' || Localidad Barrio NO || ', E_USER_NOTICE);
    return NULL;
}

/**
 * Si la dirección tiene barrio y localidad se valida si se debe mostrar el envio nocturno en el instante de ejecución
 * @param type (int)$id_addres
 * @param type (int)$id_barrio
 * @return boolean
 */
public static function show_select_localidad($id_localidad,$id_barrio){
    
    if(isset($id_localidad) && isset($id_barrio) ){
        $query="
                 SELECT barrios.id_barrio
                 
                FROM ps_entrega_nocturna entrega INNER JOIN
                ps_regla_entrga_nocturna regla ON(entrega.id_id_regla_entrga_nocturna = regla.id_regla_entrga_nocturna)
                
                INNER JOIN ps_cities_col cities  ON (entrega.id_city = cities.id_city)
                INNER JOIN ps_localidad localidad ON(cities.id_city = localidad.id_city)
                INNER JOIN ps_barrios barrios ON(localidad.id_localidad = barrios.id_localidad)

                WHERE localidad.id_localidad = ".(int)$id_localidad." AND barrios.id_barrio = ".(int)$id_barrio." AND barrios.entrega_nocturna = 1 
                AND  ((regla.".Utilities::get_day_str_es()." = 1 
                AND '".Utilities::get_dateTime(2)."' BETWEEN regla.hora_inicio AND '23:59:59') 
                OR ('".Utilities::get_dateTime(2)."' BETWEEN '00:00:00' AND regla.hora_fin)); ";
           
    $result = Db::getInstance()->executeS($query);

   if(isset($result) && count($result)>0 && $result[0]['id_barrio'] == $id_barrio){
          // trigger_error(' ||show_select_localidad OK || ', E_USER_NOTICE);
       return TRUE;
   }
    }
   // trigger_error(' ||show_select_localidad NO || ', E_USER_NOTICE);
    return FALSE;
}

/**
 * Retorna la lista de localidades disponibles para entrega nocturna en el momento de ejecución
 * @return type
 */
public static function get_list_localidades(){
        $query=" SELECT localidad.id_localidad,localidad.nombre_localidad

                    FROM ps_entrega_nocturna entrega 
                    INNER JOIN ps_regla_entrga_nocturna regla ON(entrega.id_id_regla_entrga_nocturna = regla.id_regla_entrga_nocturna)
                    INNER JOIN ps_cities_col cities  ON (entrega.id_city = cities.id_city)
                    INNER JOIN ps_localidad localidad ON(cities.id_city = localidad.id_city)
                    INNER JOIN ps_barrios barrios ON(localidad.id_localidad = barrios.id_localidad)

                WHERE (regla.".Utilities::get_day_str_es()." = 1 AND barrios.entrega_nocturna =1
                AND '".Utilities::get_dateTime(2)."' BETWEEN regla.hora_inicio AND '23:59:59') OR
                    ('".Utilities::get_dateTime(2)."' BETWEEN '00:00:00' AND regla.hora_fin)
               GROUP BY localidad.id_localidad;  ";
        $result = Db::getInstance()->executeS($query);
        
   

        if(isset($result) && !empty($result) && count($result)>0 ){
            return $result;
          }
        return array();
    }

/**
 * Retorna la lista de barrios disponibles en el mometo de ejecuación de una localiad.
 * @param type $id_localidad
 * @return type
 */
public static function get_list_barrios($id_localidad){
        $query="SELECT barrios.id_barrio,barrios.nombre_barrio

                        FROM ps_entrega_nocturna entrega INNER JOIN
                            ps_regla_entrga_nocturna regla ON(entrega.id_id_regla_entrga_nocturna = regla.id_regla_entrga_nocturna)

                        INNER JOIN ps_cities_col cities  ON (entrega.id_city = cities.id_city)
                        INNER JOIN ps_localidad localidad ON(cities.id_city = localidad.id_city)
                        INNER JOIN ps_barrios barrios ON(localidad.id_localidad = barrios.id_localidad)

                    WHERE barrios.entrega_nocturna = 1 AND ((regla.".Utilities::get_day_str_es()." = 1 
                    AND '".Utilities::get_dateTime(2)."' BETWEEN regla.hora_inicio AND '23:59:59') OR
                    ('".Utilities::get_dateTime(2)."' BETWEEN '00:00:00' AND regla.hora_fin))
                    AND localidad.id_localidad = ".(int)$id_localidad.";";
        $result = Db::getInstance()->executeS($query);
       // trigger_error(' || ##Query '.print_r($query,TRUE).' || ', E_USER_NOTICE);

   if(isset($result) && count($result)>0 ){
       return $result;
   }
   return array();
}

/**
 *  Inserta la localidad y barrio a una dirección
 * @param type $id_address
 * @param type $id_localidad
 * @param type $id_barrio
 * @return boolean
 */
public static function set_localidad_barrio($id_address,$id_localidad,$id_barrio){
    
                        // trigger_error(' || set_localidad_barrio: '.$id_address.' - '.$id_localidad.' '.$id_barrio.' || ', E_USER_NOTICE);
    
    if(isset($id_address) && isset($id_localidad) && isset($id_barrio) && is_int($id_address) && is_int($id_barrio) && is_int($id_localidad) ){
            $result = Db::getInstance()->execute('
                   update ps_address addr
                    SET addr.id_localidad ='.(int)$id_localidad.',  addr.id_barrio = '.(int)$id_barrio.'
                    WHERE addr.id_address = '.(int)$id_address.'; ');
            if(isset($result) && $result){
                return TRUE;
            }
    }
    return FALSE;
    }
    
    
public static function get_parameters(){
    
 
    $query='select entrega.id_entrega_nocturna, entrega.valor, regla.hora_inicio, regla.hora_fin, entrega.id_city,entrega.existencias 
            FROM ps_regla_entrga_nocturna regla INNER JOIN ps_entrega_nocturna entrega on( regla.id_regla_entrga_nocturna = entrega.id_id_regla_entrga_nocturna)
            WHERE regla.lunes = 1 AND entrega.activa = 1';
    $result = Db::getInstance()->executeS(utf8_encode($query));
    
  
   if(isset($result) && !empty($result) && count($result)> 0 ){
      
       return $result[0];
   }
    
    return array();
}

public static function is_city_address($id_address){
    
 
    $query='SELECT adr_ci.id_city
            FROM 
            ps_entrega_nocturna entrega INNER JOIN ps_address_city adr_ci ON (entrega.id_city = adr_ci.id_city)
            INNER JOIN ps_address adr ON(adr_ci.id_address = adr.id_address)
            WHERE adr.id_address = '.(int)$id_address.'
            GROUP BY adr_ci.id_city;';
    $result = Db::getInstance()->executeS(utf8_encode($query));
    
  
   if(isset($result) && !empty($result) && count($result)> 0 ){
      
       return TRUE;
   }
    
    return FALSE;
} 

/**
 *  Retorna la fecha en diferentes formatos opcionalmente incrementa la fecha en un numero días.
 * Formatos de fecha 
 * 0 => Y-m-d H:i:s
 * 1 => Y-m-j
 * 2 => H:i:s
 * @param type $increment    
 * @return datetime
 */
public static function get_dateTime($format_date = 0,$increment = 0){

    $format=NULL;
    switch ($format_date) {
        case 0:

            $format='Y-m-d H:i:s';
            break;
        case 1:

             $format='Y-m-j';
            break;
        case 2:
            $format='H:i:s'; 

            break;        

        default:
            break;
    }
    
     if( $format_date <= 1 && $increment != 0){
        if(is_integer($increment)){
            return strtotime ( '+ '.$increment.' day' , strtotime (date($format) ) ) ;
        }    
    } else {
        return date($format);
    }
}

/**
 * Verifica que una fecha esté dentro del rango de fechas establecidas
 * @param $start_date fecha de inicio
 * @param $end_date fecha final
 * @param $evaluame fecha a comparar
 * @return true si esta en el rango, false si no lo está
 */
public static function check_in_range($start_date, $end_date, $evaluame) {
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($evaluame);
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

/**
 * Retorna el día de la semana en español
 * @return string|null
 */
public static function get_day_str_es(){
    
    $dia=(int) date("N");
    
    switch ($dia) {
        case 7:
            return 'domingo';

            break;
                case 1:
                   return 'lunes'; 

            break;
                case 2:
                    return 'martes';

            break;
                case 3:
                    return 'miercoles';

            break;
                case 4:
                    return 'jueves';

            break;
                case 5:
                    return 'viernes';

            break;
                case 6:
                    return 'sabado';

            break;

        default:
            return NULL;
            break;
    }
}
public static function exportDetailReport(){
      $fechas = Tools::getValue('orderFilter_a!date_add');
      $filtro = new DbQuery();
      $filtro->select('id_order');
      $filtro->from('orders', 'o');
      $filtro->innerJoin('customer', 'c', 'o.id_customer = c.id_customer');
      $filtro->orderBy('o.id_order DESC');
      $filtro->limit(Tools::getValue('order_pagination'));
      if(Tools::getValue('orderFilter_id_order')){
        $filtro->where('o.id_order LIKE ("%'. Tools::getValue('orderFilter_id_order').'%")');
      }
      if(Tools::getValue('orderFilter_reference')){
        $filtro->where('o.reference LIKE ("%'. Tools::getValue('orderFilter_reference').'%")');
      }
      if(Tools::getValue('orderFilter_new')){
        $filtro->where('o.new LIKE ("%'. Tools::getValue('orderFilter_new').'%")');
      }
      if(Tools::getValue('orderFilter_customer')){
        $filtro->where('c.firstname LIKE ("%'. Tools::getValue('orderFilter_customer').'%") OR c.lastname LIKE ("%'. Tools::getValue('orderFilter_customer').'%")');
      }
      if(Tools::getValue('orderFilter_a_total_paid_tax_incl')){
        $filtro->where('o.total_paid LIKE ("%'. Tools::getValue('orderFilter_a_total_paid_tax_incl').'%")');
      }
      if(Tools::getValue('orderFilter_payment')){
        $filtro->where('o.total_paid LIKE ("%'. Tools::getValue('orderFilter_a_total_paid_tax_incl').'%")');
      }
      if(Tools::getValue('orderFilter_os!id_order_state')){
        $filtro->where('o.invoice_number LIKE ("%'. Tools::getValue('orderFilter_os!id_order_state').'%")');
      }
      if($fechas[0]){
        $filtro->where('o.`date_add` >= "'.$fechas[0].' 0:0:0"');
      }
      if($fechas[1]){
        $filtro->where('o.`date_add` <= "'.$fechas[1].' 23:59:59"');
      }
      $query = new DbQuery();
      $query->select('a.`id_order` AS `NUM PEDIDO`,
           IFNULL(oi.number,0) AS `NUM FACTURA`,
           CONCAT(c.`firstname`, " ", c.`lastname`) AS `CLIENTE`,
           a.date_add AS `FECHA`,
           adr.`address1` AS `DIRECCION 1`,
           adr.`address2` AS `DIRECCION 2`,
           b.`product_reference` AS `REFERENCIA`,
           b.`product_NAME` AS `DESCRIPCION`,
           REPLACE(b.unit_price_tax_incl,".",",") AS `PRECIO CON IMPUESTO`,
           REPLACE(b.unit_price_tax_excl,".",",") AS `PRECIO`,
           GROUP_CONCAT(icr.`cod_icr` SEPARATOR ";") AS `ICR`,
           osl.`name` AS `ESTADO ORDEN`,
           ot.`extras` AS `ENVIO`, b.`product_quantity` AS `CANTIDAD`');
      $query->from('order_detail', 'b');
      $query->innerJoin('orders', 'a', 'a.`id_order` = b.`id_order`');
      $query->innerJoin('customer', 'c', 'c.`id_customer` = a.`id_customer`');
      $query->leftJoin('order_invoice', 'oi', ' oi.`id_order` = a.`id_order` ');
      $query->leftJoin('order_picking', 'op', 'op.`id_order_detail` = b.`id_order_detail`'); 
      $query->leftJoin('icr', 'icr', 'op.`id_order_icr` = icr.`id_icr`');
      $query->leftJoin('address', 'adr', 'a.`id_address_invoice` = adr.`id_address`');
      $query->innerJoin('order_state', 'os', 'os.`id_order_state` = a.`current_state`');
      $query->innerJoin('order_state_lang', 'osl' ,'os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = 1');
      $query->leftJoin('orders_transporte', 'ot', 'a.`id_order` = ot.`id_order`');
      $query->groupBy('b.`id_order_detail`');
      $query->orderBy('FECHA DESC');
      $query->where('a.`id_order` IN (SELECT consulta.id_order FROM ('.$filtro->__toString().') AS consulta)');
      $items = Db::getInstance()->executeS($query);
      $string = "";
      $arreglo = array();
      $pos = 0;
      foreach ($items as $value) {
        if (isset($value['ICR'])){
          $icr = explode(";", $value['ICR']);
          unset($value['ICR']);
        }
        $cant = $value['CANTIDAD'];
        unset($value['CANTIDAD']);
        for ($i=0; $i < $cant; $i++) {
          $arreglo[$pos + $i] = $value;
          if(isset($icr[$i])){
            $arreglo[$pos + $i]['ICR'] = $icr[$i];
          }else{
            $arreglo[$pos + $i]['ICR'] = NULL;
          }
          $arreglo[$pos + $i]['CANTIDAD'] = $cant;
        }
        $pos = $pos + $i;
      }
      if(isset($arreglo[0])){
        ksort($arreglo[0]);
        $string = implode("\t", array_keys($arreglo[0])) . "\n";
        foreach ($arreglo as $value) {
          if (is_array($value)){
            ksort($value);
            $string .= implode("\t", $value) . "\n";
          }
        }
      }
      header('Content-type: application/ms-excel');
      header('Content-Disposition: attachment; filename=detail.xls');
      header('Content-Type: application/force-download; charset=UTF-8');
      header('Cache-Control: no-store, no-cache');
      echo $string;
      exit();
    }

    /**
     * [ChangeStateOrderIcr Cambia el estado actual de una orden a verificación manual]
     * @param [type] $IdOrder [id de la orden a modificar]
     */
    public static function ChangeStateOrderIcr( $IdOrder ) {

        if ( is_int( $IdOrder ) && $IdOrder != 0 ) {
            echo "<br> Good Id: ". $IdOrder;

            return Db::getInstance()->update('orders', array(
                'current_state' => (int)'19',
            ), 'id_order = '.(int)$IdOrder);
        } else {
            echo "<br> BAD Id: ". $IdOrder;
            return "Error";
        }


    }

    
//public static function 
}

