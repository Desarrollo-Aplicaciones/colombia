<?php

class HTMLTemplateInvoice extends HTMLTemplateInvoiceCore
{
    public function getContent()
    {
       $current_state_img='blanco-estado.png';   
         
    $extras=null;
    $contact=null;
    $sql ='select adr.phone_mobile, cus.identification, adr.dni, odr.current_state, odr.module, odr.payment, payu.method,payu.extras, GROUP_CONCAT(CONCAT(UPPER(LEFT(mes.message, 1)), LOWER(SUBSTRING(mes.message, 2)))) as note, crt.date_delivery, crt.time_windows
            from ps_orders odr  
            INNER JOIN ps_customer cus ON ( cus.id_customer = odr.id_customer ) 
            LEFT JOIN ps_address adr ON( adr.id_address = odr.id_address_delivery ) 
            LEFT JOIN ps_pagos_payu payu ON (odr.id_order=payu.id_order and odr.id_customer=payu.id_customer)
            LEFT JOIN ps_message mes ON ( odr.id_order = mes.id_order AND mes.id_employee = 0 AND mes.id_customer != 0 )
            LEFT JOIN ps_cart crt ON ( odr.id_cart = crt.id_cart )
            WHERE odr.id_order = '.(int)$this->order->id." ;";
    

if ($results = Db::getInstance()->ExecuteS($sql))
    $type_payment = $results[0]['payment'];
    $this->smarty->assign(array(
        'date_delivery' => $results[0]['date_delivery'],
        'time_windows' => $results[0]['time_windows']
    ));
    foreach ($results as $row)
            {
              $dni=NULL;

              // se toma la nota o mensaje registrada para la orden
              $note = $row['note'];
             
            
           if($row['identification']!=NULL&&$row['identification']!='0')
              {
              $dni=$row['identification'];             
              }
       else if($row['dni']!='1111'&&$row['dni']!='')
             {
             $dni=$row['dni'];  
             }
         else 
            {
            $dni='N/A'; 
            }
    
 
   
          
            switch ($row['current_state']) 
            {
            case 1:
             $current_state_img='payment-pending.jpg';    
             break;
             case 2:
             $current_state_img='cancelado.jpg';    
             break;
             case 20:
             case 3:
                 if($row['module']=='cashondelivery')
                 {
             $current_state_img='payment-pending.jpg';
                 }
                 else
                 {
             $current_state_img='cancelado.jpg';       
                 }
            break;
             case 4:
              if($row['module']=='cashondelivery')
                 {
             $current_state_img='payment-pending.jpg';
                 }
                 else
                 {
             $current_state_img='cancelado.jpg';       
                 }
             break;
             case 5:
             $current_state_img='cancelado.jpg';    
             break;
             case 6:
             $current_state_img='blanco-estado.png';    
             break;
             case 7:
             $current_state_img='blanco-estado.png';   
             break;
             case 8:
             $current_state_img='blanco-estado.png';   
             break;
             case 9:
             $current_state_img='cancelado.jpg';    
             break;
             case 10:
             $current_state_img='payment-pending.jpg';    
             break;
             case 11:
             $current_state_img='payment-pending.jpg';    
             break;
             case 12:
             $current_state_img='cancelado.jpg';    
             break;
             case 15:
             $current_state_img='payment-pending.jpg';    
             break;
            default:             
            $current_state_img='blanco-estado.png';
            }
            
           if(isset($row['method']) && ($row['method']=='Baloto'|| $row['method']=='Efecty'))
            {
              $extras=explode( ';', $row['extras'] );
            }
            
 $contact=(array('phone_mobile'=>$row['phone_mobile'],'dni'=>$dni,'current_state_img'=>$current_state_img));
 break;
            }


        $query = 'select cupon.description 
from ps_orders orden
INNER JOIN ps_cart cart ON(orden.id_cart = cart.id_cart)
INNER JOIN ps_cart_cart_rule cartcup ON(cart.id_cart=cartcup .id_cart)
INNER JOIN ps_cart_rule cupon ON(cartcup.id_cart_rule = cupon.id_cart_rule)
where orden.id_order =' . (int) $this->order->id.' LIMIT 1';

    
$cupon=null;
try {
    


        if ($results = Db::getInstance()->ExecuteS($query))
        {
            foreach ($results as $row2) {
           
               $cupon = $row2['description'];  
            }
         }

} catch (Exception $exc) {
    
Logger::AddLog('Apoyo Salud [HTMLTempleteInvoice.php] getContent() error: ' . $exc->getTraceAsString(), 2, null, null, null, true);
 $cupon=null;    
}

            
        $country = new Country((int)$this->order->id_address_invoice);
        $invoice_address = new Address((int)$this->order->id_address_invoice);
               
                
                $invoice_address->dni= $contact['dni'];
                

           $customer = new Customer((int)$this->order->id_customer);                

        if ( $invoice_address->lastname == '' || $invoice_address->firstname == '') { //validar si la dirección tiene nombre y apellido del cliente, si no, lo tomamos directamente del cliente

            $invoice_address->lastname = $customer->lastname;
            $invoice_address->firstname = $customer->firstname;
        }

        /************ FORMATEO FACTURA ***********/
        $direccion2='';
        
        if($invoice_address->address2!=null && $invoice_address->address2!='')
            {
            $direccion2="<tr><td width=\"70px\" >Dirección2:         </td><td>".$invoice_address->address2."</td></tr>";
            }
        
        $formatted_invoice_address =   "<table >
        <tr><td width=\"70px\" >Identificación:     </td><td>".$invoice_address->dni."</td></tr>".
        "<tr><td width=\"70px\" >Nombre y Apellido: </td><td>".$invoice_address->firstname." ".$invoice_address->lastname."</td></tr>".
        "<tr><td width=\"70px\" >Dirección:         </td><td>".$invoice_address->address1."</td></tr>".
                $direccion2.
        "<tr><td width=\"70px\" >País:              </td><td>".$invoice_address->country."</td></tr>".
        "<tr><td width=\"70px\" >Departamento:      </td><td>".State::getNameById($invoice_address->id_state)."</td></tr>".
        "<tr><td width=\"70px\" >Ciudad:            </td><td>".$invoice_address->city."</td></tr>".
        "<tr><td width=\"70px\" >Teléfono:          </td><td>".$invoice_address->phone."</td></tr>";

        $fa1 = $invoice_address->city;
        $fa2 = $invoice_address->alias;
        $fa3 = $invoice_address->address1;

        // echo '<pre>';
        // print_r($invoice_address);
        // exit();

        $facturaValida = strtoupper($fa1);
        $facturaValida2 = strtoupper($fa2);
        $facturaValida3 = strtoupper($fa3);

        $this->smarty->assign(array(
            'dni' => $invoice_address->dni,
            'namecomplete' => $invoice_address->firstname." ".$invoice_address->lastname,
            'address1' => $invoice_address->address1,
            'address2' => $invoice_address->address2,
            'phone' => $invoice_address->phone,
            'phone_mobile' => $contact['phone_mobile']
        ));

        //$formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
        if(isset($contact['phone_mobile']) && $contact['phone_mobile'] != '') {
                $formatted_invoice_address.="<tr><td width=\"70px\" >Móvil: </td><td>".$contact['phone_mobile']."</td></tr>";
            }
                
                if(isset($row['method']) && ($row['method']=='Baloto')||$row['method']=='Efecty')
                {
                  $formatted_invoice_address.='<tr><td width=\"70px\" >'.$row['method'].': </td><td>'.$extras[0].'</td></tr>
                  <tr><td width=\"70px\" >Fecha expiración: </td><td>'.$extras[1].'</td></tr>';  
                  if($row['method']=='Baloto'){
                   $formatted_invoice_address.='<tr><td width=\"70px\" >Convenio: </td><td>950110</td></tr>';    
                  }
                  elseif($row['method']=='Efecty'){
                       $formatted_invoice_address.='<tr><td width=\"70px\" >Convenio: </td><td>110528</td></tr>';  
                      
                  }
                   
                }
                $formatted_invoice_address.="</table>";
                $formatted_delivery_address = '';

        if ($this->order->id_address_delivery != $this->order->id_address_invoice)
        {
            $delivery_address = new Address((int)$this->order->id_address_delivery);
            $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');
        }

        $customer = new Customer((int)$this->order->id_customer); 

                    
               
           
  // Url archivo de verificaciÃ³n webservice   
$nombre_archivo= parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$nombre_archivo=explode('/', $nombre_archivo);
$var= array_pop($nombre_archivo);
$nombre_archivo=implode('/', $nombre_archivo); 
$urlValidation='http://'.$_SERVER['HTTP_HOST'].$nombre_archivo;          
             


             //////echo "<br>". 
$query = 'SELECT cupon.description, cupon.reduction_percent, cupon.reduction_amount, cupon.reduction_product, cupon.gift_product
FROM ps_orders orden
INNER JOIN ps_order_cart_rule cartcup ON( orden.id_order = cartcup.id_order )
INNER JOIN ps_cart_rule cupon ON(cartcup.id_cart_rule = cupon.id_cart_rule)
WHERE orden.id_order =' . (int) $this->order->id . ' LIMIT 1';


        $cupon = null;
        $cupon_xml_calc = array();
        
        try {
             //////echo "<br> ingresando a validar cupones";
            if ($results = Db::getInstance()->ExecuteS($query)) {
                //////echo "<br> dto cart rule encontrado";
                foreach ($results as $row2) {

                    $cupon = $row2['description'];
                    $cupon_xml_calc['description'] = $row2['description'];
                    $cupon_xml_calc['reduction_product'] = $row2['reduction_product'];
                    $cupon_xml_calc['gift_product'] = $row2['gift_product'];

                    if ( $row2['reduction_percent'] != '0.00' && $row2['reduction_percent'] != '0' ) {

                        $cupon_xml_calc['tipo'] = 'porcentaje';
                        $cupon_xml_calc['reduction'] = $row2['reduction_percent'];

                    } else {

                        $cupon_xml_calc['tipo'] = 'valor';
                        $cupon_xml_calc['reduction'] = $row2['reduction_amount'];


                    }

                }
            }

        } catch (Exception $exc) {

            Logger::AddLog('Apoyo Salud [HTMLTempleteInvoice.php] getContent() error: ' . $exc->getTraceAsString(), 2, null, null, null, true);
            $cupon = null;

        }



$query = 'SELECT o.product_id AS producto, IF(vla.`value` LIKE "SI",fea.id_product,NULL) AS formed, tx.rate, odt.unit_amount
FROM '._DB_PREFIX_.'order_detail o
LEFT JOIN '._DB_PREFIX_.'order_detail_tax odt ON ( o.id_order_detail = odt.id_order_detail )
LEFT JOIN '._DB_PREFIX_.'tax tx ON ( tx.id_tax = odt.id_tax )
LEFT JOIN '._DB_PREFIX_.'feature_product fea ON ( o.product_id = fea.id_product AND fea.id_feature = 11 )
LEFT JOIN '._DB_PREFIX_.'feature_value_lang vla ON ( fea.id_feature_value = vla.id_feature_value  AND vla.`value` LIKE "SI")
WHERE o.id_order = ' . (int) $this->order->id;



        $list_products = $this->order_invoice->getProducts();
       /* echo "<pre>";var_dump(debug_backtrace()); echo "</pre>"; 
                exit();*/
        $formu_medical = false;
        try {
            if ($results = Db::getInstance()->ExecuteS($query)) {


                foreach ($results as $row) {

                    foreach ($list_products as $row2 => $value) {


                        if ($value['product_id'] == $row['formed']) {

                            //<img style="height: 10px;" src="' . $urlValidation . '/../img/formulita.png"> 
                            $list_products[$row2]['product_name'] = '<sup>FM</sup> ' . $list_products[$row2]['product_name'];
                            $formu_medical = true;
                            //////echo "<br> formula medica SIIIII";
                        }


                        if ( $value['product_id'] == $row['producto'] && $row['rate'] != '' && $row['rate'] != null ) {
                            //////echo "<br> ".$value['product_id']." -  ".$row['rate'];

                            $list_products[$row2]['tax_rate'] = $row['rate'];

                        }
                    }
                }
            }
        } catch (Exception $exc) {

            Logger::AddLog('Formula Medica [HTMLTempleteInvoice.php] getContent() error: ' . $exc->getTraceAsString(), 2, null, null, null, true);
        }

/*
$query='select prod.*
FROM
 ps_order_detail orderd
INNER JOIN ps_product prod ON (orderd.product_id= prod.id_product)
INNER JOIN ps_feature_product fea ON (prod.id_product = fea.id_product )
where 
fea.id_feature_value =4121
and orderd.id_order='. (int) $this->order->id;


$list_products = $this->order_invoice->getProducts();
$formu_medical = false;
        try {
            if ($results = Db::getInstance()->ExecuteS($query)) {


                foreach ($results as $row) {

                    foreach ($list_products as $row2 => $value) {


                        if ($value['product_id'] == $row['id_product']) {

    //<img style="height: 10px;" src="' . $urlValidation . '/../img/formulita.png"> 
                            $list_products[$row2]['product_name'] = '<b>FM</b> ' . $list_products[$row2]['product_name'];
                            $formu_medical = true;
                        }
                    }
                }
            }
        } catch (Exception $exc) {

            Logger::AddLog('Formula Medica [HTMLTempleteInvoice.php] getContent() error: ' . $exc->getTraceAsString(), 2, null, null, null, true);
        }
        */
        $cart_rules = $this->order->getCartRules($this->order_invoice->id);


$array_ivas = array();

        ///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE Y LISTADO DE IMPUESTOS DE PRODUCTOS ***///


                
                

                $cant_prods = 0;
                $subTotal_calculado = floatval(0); // tendrá la suma de cada ( precio producto X cantidad ) antes de iva
                $val_total_min_dto_mas_iva = 0; // Total de la venta actual
                $val_total_de_iva = 0; // total del iva calculado
                //$val_iva_X_tax = array();
                
                foreach ($list_products as $key => $value) {

                    $val_iva_prod_actual = 0; // total del iva calculado del producto actual
                    
                    
                    if ( $cant_prods == 0 ) {
                        $subTotal_calculado = $list_products[$key]['total_price_tax_excl'];
                    } else {
                        $subTotal_calculado += $list_products[$key]['total_price_tax_excl'];
                    }



                    if ( isset( $cupon_xml_calc ) && $cupon_xml_calc != null && $cupon_xml_calc['reduction'] != '' ) {

                        if ( $cupon_xml_calc['tipo'] == 'porcentaje' && $cupon_xml_calc['reduction_product'] != '0' && $cupon_xml_calc['reduction_product'] == $list_products[$key]['product_id']) {

                            $iva_prod_actual = Tools::ps_round( Cart::StaticUnitPriceDiscountPercent( Tools::ps_round($list_products[$key]['unit_price_tax_excl'], 2), Tools::ps_round($list_products[$key]['tax_rate'], 2), Tools::ps_round($cupon_xml_calc['reduction'], 2), false, Tools::ps_round($list_products[$key]['product_quantity'], 2), false, true ), 2);

                            
                                /////////echo "<br> val iva descuentop_aplicado % :".$cupon_xml_calc['reduction'];
                            

                        } elseif ( $cupon_xml_calc['tipo'] == 'porcentaje' && $cupon_xml_calc['reduction_product'] == '0' ) {

                            $iva_prod_actual = Tools::ps_round( Cart::StaticUnitPriceDiscountPercent( Tools::ps_round($list_products[$key]['unit_price_tax_excl'], 2), Tools::ps_round($list_products[$key]['tax_rate'], 2), Tools::ps_round($cupon_xml_calc['reduction'], 2), false, Tools::ps_round($list_products[$key]['product_quantity'], 2), false, true ), 2);

                            
                                //////echo "<br> val iva descuentop_aplicado % :".$cupon_xml_calc['reduction'];
                            

                        } else {

                            $iva_prod_actual = Tools::ps_round( Cart::StaticUnitPriceDiscountPercent( Tools::ps_round($list_products[$key]['unit_price_tax_excl'], 2), Tools::ps_round($list_products[$key]['tax_rate'], 2), Tools::ps_round('0.00', 2), false, Tools::ps_round($list_products[$key]['product_quantity'], 2), false, true ), 2);

                            
                                //////echo "<br> val iva descuentop_aplicado $ :".$cupon_xml_calc['reduction'];
                            

                        }
                    } else {

                        $iva_prod_actual = Tools::ps_round( Cart::StaticUnitPriceDiscountPercent( Tools::ps_round($list_products[$key]['unit_price_tax_excl'], 2), Tools::ps_round($list_products[$key]['tax_rate'], 2), Tools::ps_round('0.00', 2), false, Tools::ps_round($list_products[$key]['product_quantity'], 2), false, true ), 2);

                        
                                //////echo "<br> val iva descuentop_aplicado sin descuento :". $iva_prod_actual ;                        

                    }

                    //////echo "<br> precio prod:".$list_products[$key]['unit_price_tax_excl'];
                    //////echo "<br> iva del prod actual: ".$iva_prod_actual;

                    if ( Tools::ps_round( $list_products[$key]['tax_rate'] , 2) != '0.00') {


                            //////echo "<br> si tax del ". /*Tools::ps_round( */Tools::ps_round($list_products[$key]['tax_rate'],0) /*, 2)*/;

                        if ( !isset( $array_ivas[ Tools::ps_round(Tools::ps_round($list_products[$key]['tax_rate'],0),0) ] ) ) {
                            //////echo "<br> no creado tax del %  ".Tools::ps_round($list_products[$key]['tax_rate'],0);

                            $array_ivas[Tools::ps_round($list_products[$key]['tax_rate'],0)] = 0;

                        }

                            //////echo "<br> tax del  ".Tools::ps_round($list_products[$key]['tax_rate'],0)." % antes con ".$array_ivas[Tools::ps_round($list_products[$key]['tax_rate'],0)];


                        $array_ivas[Tools::ps_round($list_products[$key]['tax_rate'],0)] += $iva_prod_actual;
                        $iva_prod_actual = 0;

                            //////echo "<br> tax del  ".Tools::ps_round($list_products[$key]['tax_rate'],0)." % despues con ".$array_ivas[Tools::ps_round($list_products[$key]['tax_rate'],0)];


                    } 

                    $cant_prods++;

                }




if (  $this->order->total_shipping != '0.00' ||  $this->order->total_shipping_tax_incl != '0.00' ) {

                    if ( !isset( $array_ivas['16'] ) ) {
                        $array_ivas['16'] = 0;
                    }

                    //////echo "<br> valor final envio: ".  $this->order->total_shipping;
                    //////echo "<br> - valor sin iva envio: ". 
                    $val_no_iva_envio =  number_format(  $this->order->total_shipping / 1.16 ,3, '.', '');

                    //////echo "<br> - iva envio: ". 
                    $val_iva_envio_act = /* number_format( ( */ $this->order->total_shipping  - $val_no_iva_envio/*) ,2, '.', '')*/;

                    $array_ivas['16'] += number_format( $val_iva_envio_act ,2, '.', '');

                    //////echo "<br> val anterior de iva :". /*number_format( */$val_total_de_iva /*,2, '.', '')*/;

                    $val_total_de_iva += /*number_format( */$val_iva_envio_act /*,2, '.', '')*/;

                    //////echo "<br> val acumulado total de iva :".  /*number_format( */$val_total_de_iva /*,2, '.', '')*/;

                    
                    //////echo "<br> val acumulado total :".
                    $val_total_min_dto_mas_iva += $val_iva_envio_act + $val_no_iva_envio;
                    
                    $subTotal_calculado += number_format( $val_no_iva_envio ,2, '.', '');
                }




                foreach ($array_ivas as $key => $value) {

                    $array_ivas[$key] = number_format( $array_ivas[$key] , 2, '.', '');

                }



        // arsort() ordenar valores mayor a menor
        
        ksort($array_ivas);
/*
    echo '<pre>array_ivas<br>...';
 print_r( $array_ivas );
    echo '<br>factura<br>';
//print_r($this->smarty->fetch($this->getTemplateByCountry($country->iso_code)));
exit();*/

        $sql = "SELECT COUNT(id_order) paid_out
                FROM ps_order_history
                WHERE id_order = ".(int)$this->order->id."
                AND id_order_state IN (2,5)";
        $paid_out = Db::getInstance()->getValue($sql);

        $this->smarty->assign(array(
            'order' => $this->order,
            'order_details' => $list_products,
            'cart_rules' => $cart_rules,
            'delivery_address' => $formatted_delivery_address,
            'invoice_address' => $formatted_invoice_address,
            'facturaValida' => $facturaValida,
            'facturaValida2' => $facturaValida2,
            'facturaValida3' => $facturaValida3,
            'tax_excluded_display' => Group::getPriceDisplayMethod($customer->id_default_group),
            'tax_tab' => $this->getTaxTabContent(),
            'customer' => $customer,
            'paid_out' => (int)$paid_out,
            'type_payment' => $type_payment,
            'current_state_img'=>$current_state_img,
            'apoyosalud'=> $cupon,
            'ivas' => $array_ivas,
            'formu_medical'=>$formu_medical,
            'note' => $note
        ));

        return $this->smarty->fetch($this->getTemplateByCountry($country->iso_code));
    }

}

