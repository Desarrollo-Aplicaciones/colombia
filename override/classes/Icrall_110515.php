 <?php
/*
**Autor: Esteban Rincón / Ewing Vásquez
**Para: Farmalisto
*/
class Icrall extends IcrallCore {


        public function icrDel($order) {

        $query_prods_restar_stock = "SELECT so.id_warehouse, sod.id_product, count(sod.id_product) AS cantprods, i.id_estado_icr AS estado FROM ps_supply_order so 
            INNER JOIN ps_supply_order_detail sod ON ( so.id_supply_order = sod.id_supply_order )
            INNER JOIN ps_supply_order_icr soi ON ( soi.id_supply_order_detail = sod.id_supply_order_detail )
            INNER JOIN ps_icr i ON ( i.id_icr = soi.id_icr)
            WHERE  so.id_supply_order=".$order."
            GROUP BY so.id_warehouse, sod.id_product"; 
            $id_stock_mvt_reason = Configuration::get('PS_STOCK_MVT_REASON_DEFAULT');
            $usable = 1;
            if ($res_query_prods_restar_stock = Db::getInstance()->ExecuteS($query_prods_restar_stock))
            {
                foreach ($res_query_prods_restar_stock as $fila) {
                    if($fila['estado'] != 2){
                        $this->errors[] = Tools::displayError('hay ICR\'s en estado diferente a "Asignado".');
                        return true;
                    }
                }
                foreach ($res_query_prods_restar_stock as $row_res_stock) {
                    $warehouse = new Warehouse($row_res_stock['id_warehouse']);

                    $id_product = $row_res_stock['id_product'];
                    $id_product_attribute = null;
                    $quantity = $row_res_stock['cantprods'];

                    $stock_manager = StockManagerFactory::getManager();
                    
                    $removed_products = $stock_manager->removeProduct($row_res_stock['id_product'], null, $warehouse, $row_res_stock['cantprods'], $id_stock_mvt_reason, $usable); 

                    if (count($removed_products) > 0)
                    {
                        StockAvailable::synchronize($id_product);
                        // Tools::redirectAdmin($redirect.'&conf=2');
                    }
                    else
                    {
                        $physical_quantity_in_stock = (int)$stock_manager->getProductPhysicalQuantities($id_product, $id_product_attribute, array($warehouse->id), false);
                        $usable_quantity_in_stock = (int)$stock_manager->getProductPhysicalQuantities($id_product, $id_product_attribute, array($warehouse->id), true);
                        $not_usable_quantity = ($physical_quantity_in_stock - $usable_quantity_in_stock);
                        if ($usable_quantity_in_stock < $quantity)
                            $this->errors[] = sprintf(Tools::displayError('You don\'t have enough usable quantity. Cannot remove %d out of %d.'), (int)$quantity, (int)$usable_quantity_in_stock);
                        else if ($not_usable_quantity < $quantity)
                            $this->errors[] = sprintf(Tools::displayError('You don\'t have enough usable quantity. Cannot remove %d out of %d.'), (int)$quantity, (int)$not_usable_quantity);
                        else
                            $this->errors[] = Tools::displayError('It is not possible to remove the specified quantity. Therefore no stock was removed.');
                    }

                }
            }
     
                $query_2 = "UPDATE  ps_stock_mvt sm INNER JOIN ps_employee e ON (e.id_employee = sm.id_employee)
                SET sm.employee_firstname = e.firstname,
                    sm.employee_lastname = e.lastname
                WHERE sm.employee_firstname = '' AND sm.employee_lastname = ''
                AND sm.id_employee = ".$id_emp;

                $results = Db::getInstance()->Execute($query_2);

        $sql="UPDATE ps_icr AS icr
              INNER JOIN ps_supply_order_icr AS soi
              ON icr.id_icr = soi.id_icr
              INNER JOIN ps_supply_order_detail AS sod
              ON sod.id_supply_order_detail = soi.id_supply_order_detail
              SET icr.id_estado_icr = 1
              WHERE sod.id_supply_order = ".$order;
        $sql2="DELETE soi.*
              FROM ps_supply_order_icr soi
              INNER JOIN ps_supply_order_detail sod
              ON sod.id_supply_order_detail = soi.id_supply_order_detail
              WHERE sod.id_supply_order = ".$order;
        if ((DB::getInstance()->execute($sql)) && (DB::getInstance()->execute($sql2)))
            {
                return false;
            }
        else {
            return true;
        }
    }

    /**
     * [cambiarFechasVaciasyNulas Cambia los valores de las fechas de vencimiento cargadas cuando son vacias o nulas]
     * @return [bool] [dependiendo del resultado/ejecucion del query]
     */
    public function cambiarFechasVaciasyNulas() {
      //..-echo "<br>2.5";

        $query_icr_fechas_vence = "UPDATE ps_tmp_cargue_entrada_icr 
                SET fecha_vencimiento = '0000-00-00'
                WHERE ( fecha_vencimiento IS NULL OR TRIM(fecha_vencimiento) = '')";

        if ($results_icr = Db::getInstance()->ExecuteS($query_icr_fechas_vence)) {
            return true;
        } else {
            $this->errores_cargue[] = "Error en la actualización de las fechas de vencimiento no ingresadas.";
            return false;    
        }
    }

    /**
     * ValidateFechaVencEntrada    Validación del ingreso de fecha de vencimiento
    */
   
    public function ValidateFechaVencEntrada() {

        $query_upd_fecven_na = "UPDATE ps_tmp_cargue_entrada_icr
            SET fecha_vencimiento = '1969-12-31'
            WHERE
            fecha_vencimiento LIKE '%N/A%' OR
            fecha_vencimiento LIKE '%NA%'";

        $results_upd_fecven_na = Db::getInstance()->Execute($query_upd_fecven_na);


        /*********    VALIDAR TAMAÑO DE LA FECHA **************/

        $query = 'SELECT COUNT(*) AS tamanofec, GROUP_CONCAT(cod_icr) AS icrs FROM ps_tmp_cargue_entrada_icr
            WHERE CHAR_LENGTH(fecha_vencimiento) != 10 AND TRIM(fecha_vencimiento) != ""';

        if ($retorno = DB::getInstance()->executeS($query) ) {

            if( isset($retorno[0]['tamanofec']) && $retorno[0]['tamanofec'] == 0 ) {

                /*********    VALIDAR FORMATO CON EXPRESION REGULAR **************/

                $query_regexfec = "SELECT COUNT(*) AS regexfec, GROUP_CONCAT(cod_icr) AS icrs 
                        /*REGEXP '^[0-9]{4}-([0]{1}[1-9]{1}|[1]{1}[0-2]{1})-([0]{1}[1-9]{1}|[1-3]{1}[0-9]{1})$' AS valido*/
                    FROM ps_tmp_cargue_entrada_icr
                    WHERE fecha_vencimiento 
                        REGEXP '^[0-9]{4}-([0]{1}[0-9]{1}|[1]{1}[0-2]{1})-([0]{1}[0-9]{1}|[1-3]{1}[0-9]{1})$' = 0
                    AND TRIM(fecha_vencimiento) != '' ";

                if ($retorno_regexfec = DB::getInstance()->executeS($query_regexfec) ) {

                    if( isset($retorno_regexfec[0]['regexfec']) && $retorno_regexfec[0]['regexfec'] == 0 ) {

                        /*********    VALIDAR QUE LA FECHA SEA VÁLIDA **************/

                        $query_diainvalid = "SELECT 
                            COUNT( IF( day( fecha_vencimiento ) IS NULL , 99, day( fecha_vencimiento ) ) ) AS diainvalid 
                            FROM ps_tmp_cargue_entrada_icr
                            WHERE day( fecha_vencimiento ) IS NULL AND TRIM(fecha_vencimiento) != '' ";

                        if ($retorno_diainvalid = DB::getInstance()->executeS($query_diainvalid) ) {

                            if( isset($retorno_diainvalid[0]['diainvalid']) && $retorno_diainvalid[0]['diainvalid'] == 0 ) {

                                /*********    VALIDAR QUE LA FECHA SEA MAYOR A ACTUAL **************/

                                $query_fecinvalid = "SELECT COUNT(cod_icr) AS fecinvalid, GROUP_CONCAT(cod_icr) AS icrs 
                                    FROM ps_tmp_cargue_entrada_icr WHERE
                                    IF(STR_TO_DATE(fecha_vencimiento,'%Y-%m-%d') > NOW(), 1 , 0) = 0 AND TRIM(fecha_vencimiento) != ''
                                    AND fecha_vencimiento != '0000-00-00' AND fecha_vencimiento != '1969-12-31' ";

                                if ($retorno_fecinvalid = DB::getInstance()->executeS($query_fecinvalid) ) {

                                    if( isset($retorno_fecinvalid[0]['fecinvalid']) && $retorno_fecinvalid[0]['fecinvalid'] == 0 ) {
                                        return true;

                                    } else {

                                        $this->errores_cargue[] = "Algunas fechas de vencimiento de algunos ICR no es mayor a la actual. Cantidad de fechas erróneas ( ". $retorno_fecinvalid[0]['fecinvalid']." ). Icrs: ".$retorno_fecinvalid[0]['icrs'];

                                        return false;
                                    }
                                    
                                } else {
                                    $this->errores_cargue[] = "No se puede comprobar que fecha de vencimiento de los ICR sea mayor a la actual.";
                                    return false;
                                }


                            } else {

                                $this->errores_cargue[] = "Algunas fechas de vencimiento de algunos ICR es errónea (Inexistente). Cantidad de fechas erróneas ( ". $retorno_diainvalid[0]['diainvalid']." ). ";

                                return false;
                            }
                            
                        } else {
                            $this->errores_cargue[] = "No se puede comprobar la validez de la fecha de vencimiento de los ICR.";
                            return false;
                        }

                    } else {

                        $this->errores_cargue[] = "El formato de fecha de vencimiento de algunos ICR es erróneo, debe ser YYYY-MM-DD. Cantidad de fechas erróneas ( ". $retorno_regexfec[0]['regexfec']." ). Icrs: ".$retorno_regexfec[0]['icrs'];
                        return false;
                    }

                } else {
                    $this->errores_cargue[] = "No se puede validar formato de fecha, de la fecha de vencimiento de los ICR.";
                    return false;
                }

            } else {

                $this->errores_cargue[] = "Se ha presentado error en el tamaño de fecha de vencimiento de algunos ICR (10 caracteres). Cantidad de fechas erróneas ( ". $retorno[0]['tamanofec']." ). Icrs: ".$retorno[0]['icrs'];
                return false;
            }

        } else {

            $this->errores_cargue[] = "No se puede validar el tamaño de la fecha de vencimiento de los ICR.";
            return false;
        }
        
    }
    

    public function validarFechaProductosSalida() {


        $query = "SELECT COUNT(1) as total, GROUP_CONCAT(ces.cod_icr SEPARATOR ' - ') AS vencidos
        FROM "._DB_PREFIX_."tmp_cargue_icr_salida ces
                INNER JOIN "._DB_PREFIX_."supply_order_icr ordericr ON ( ces.id_icr = ordericr.id_icr )
                INNER JOIN "._DB_PREFIX_."supply_order_detail  orderdtl ON ( orderdtl.id_supply_order_detail= ordericr.id_supply_order_detail )
        INNER JOIN "._DB_PREFIX_."icr icr ON ( ordericr.id_icr= icr.id_icr ) 
        INNER JOIN "._DB_PREFIX_."supply_order_detail s_order_d ON ( ordericr.id_supply_order_detail=s_order_d.id_supply_order_detail )
        WHERE icr.id_estado_icr=2
                AND ordericr.fecha_vencimiento <> '0000-00-00' 
                AND ordericr.fecha_vencimiento <> '1969-12-31' 
                AND STR_TO_DATE(ordericr.fecha_vencimiento, '%Y-%m-%d') < NOW() ;";
                
            if ($results = Db::getInstance()->ExecuteS($query)) {

                if ( $results[0]['total'] == 0 ) {

                    return true;

                } else {

                    $this->errores_cargue[] = "Algunos ICR's ya se encuentran vencidos: ".$results[0]['vencidos'];
                    return false;
                }

            } else {

                $this->errores_cargue[] = " No se pudo validar la fecha de vencimiento de los ICR's ";
                return false;

            }

        
    }
}