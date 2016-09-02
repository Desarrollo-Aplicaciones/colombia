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
    
}