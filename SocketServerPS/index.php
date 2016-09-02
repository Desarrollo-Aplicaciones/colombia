<?php

include(dirname(__FILE__) . '/../config/config.inc.php');
require 'PasswordHash.php';
require 'Sync_tracker.php';

class PaymentWs extends PaymentModule {
   
    
    	public function __construct()
	{
		$this->name = 'cashondelivery';
		$this->tab = 'payments_gateways';
		$this->version = '1.0';
		$this->author = 'PrestaShop';
		$this->need_instance = 1;
		$this->module_key = '1bc1eb8640f4234902725736f6bd45e9';

		$this->currencies = false;

		parent::__construct();

		$this->displayName = $this->l('Cash on delivery (COD)');
		$this->description = $this->l('Accept cash on delivery payments');

		/* For 1.4.3 and less compatibility */
		$updateConfig = array('PS_OS_CHEQUE', 'PS_OS_PAYMENT', 'PS_OS_PREPARATION', 'PS_OS_SHIPPING', 'PS_OS_CANCELED', 'PS_OS_REFUND', 'PS_OS_ERROR', 'PS_OS_OUTOFSTOCK', 'PS_OS_BANKWIRE', 'PS_OS_PAYPAL', 'PS_OS_WS_PAYMENT');
		if (!Configuration::get('PS_OS_PAYMENT'))
			foreach ($updateConfig as $u)
				if (!Configuration::get($u) && defined('_'.$u.'_'))
					Configuration::updateValue($u, constant('_'.$u.'_'));
	}


    
}

/*
 * Clase para gestionar las solicitudes de sugar
 */

class serverWsPs extends FrontController {

    public $array_obj = array();
    public $reponse = NULL;
    public $errors = array();

    public function __construct() {
        if (isset(Context::getContext()->controller))
            $controller = Context::getContext()->controller;
        else {
            $controller = new FrontController();
            $controller->init();
        }
    }

    /*
     * Procesa la solicitud en formato Json
     */

    function process_request($json) {

        $this->logtxt($json);
        try {

            if (isset($json)) {
                $array = json_decode($json, TRUE);


                if (isset($array['entity']) && isset($array['action']) && isset($array['content']) && isset($array['id_employee']) && !empty($array['entity']) && !empty($array['action']) && !empty($array['content']) && !empty($array['id_employee'])) {
                    $this->array_obj = $array;
                    $this->resolve();
                    return TRUE;
                }
            }
        } catch (Exception $e) {
            $this->errors[] = $e;
            return false;
        }
        $this->errors[] = 'Parametros no validos';
        return FALSE;
    }
    
    /*
     * Valida el tipo de solicitud he invoca el metodo apropiado 
     */

    public function resolve() {
        switch ($this->array_obj['action']) {
            case 'add':
                $this->reponse = 'add';
                $this->add($this->array_obj);

                break;
            case 'delete':
                $this->reponse = 'delete';

                break;
                $this->reponse = 'edit';
                
            case 'sync_tracker':
            $this->reponse = 'sync_tracker';
             $this->sync_tracker($this->array_obj);   
                break;
            case 'edit':


                break;
            case 'list_employee':
                
                break;          
            default:
                $this->errors[] = 'AcciÃ³n no valida';
                break;
        }
    }

    public function add($array) {
        switch ($array['entity']) {
            case 'order':
                $id_vaucher = $array['content']['id_voucher'];
                $id_address = $array['content']['id_address'];
                $id_customer = $array['content']['id_customer'];

                /*
                 * Valida si la orden contiene todos elementos rqueridos
                 */
                if (isset($id_address) && !empty($id_address) && isset($id_customer) && !empty($id_customer) && count($array['content']['products']) > 0) {

                    $this->create_cart($array['content']['products'], $id_customer, $id_address);
                } else {
                    $this->errors[] = 'Los parametros de la orden no son validos';
                }
                break;

            default:
                $this->errors[] = 'Entidad invalida';
                break;
        }
    }

    public function create_cart($products, $id_customer, $id_address) {

        if (count($products) > 0) {
            
           if (!$this->valid_products($products)){
            $this->errors[] = 'Error actualizando cantidades de producto';  
            Logger::AddLog('[sugar_to_ps-php] Error actualizando cantidades de producto' . $exc->getTraceAsString(), 2, null, null, null, true);
           }

            $this->context = new StdClass(); // crear contexto
            //echo 'crear carrito';
            $this->context->cart = new Cart();
            $this->context = Context::getContext(); // actualizar contexto
            // Agrega el carrito a la base de datos
             $this->context->cart->id_customer = (int) $this->array_obj['content']['id_customer'];
             $this->context->cart->id_address_delivery = (int) $this->array_obj['content']['id_address'];
             $this->context->cart->id_address_invoice =  (int) $this->array_obj['content']['id_address'];
             $this->context->cart->update();
            
             $contextClone = Context::getContext()->cloneContext();
		if(isset($contextClone->cart->id_address_delivery)) { 

			$add_delivery = $contextClone->cart->id_address_delivery;

		} elseif(isset($this->cart->id_address_delivery)) { 

			$add_delivery = $this->cart->id_address_delivery;

		}
            
            
            $this->context->cart->add();
            // agrgar productos al carrito
            foreach ($products as $value) {
                $this->context->cart->updateQty($value['quantity'], $value['id_product_ps'], 0, 0, 'up', 0);
            }

            $this->context->cart->update();
            $this->add_vaucher();
            $this->create_order();

                
        } else {

            $this->errors[] = 'No se enviaron productos para crear la orden.';
        }
    }

    public function add_vaucher() {

        if (isset($this->array_obj['content']['id_voucher']) && !empty($this->array_obj['content']['id_voucher'])) {
            if (($this->array_obj['content']['id_voucher'])) {
                $this->context->cart->addCartRule((int) ($this->array_obj['content']['id_voucher']));
            }
        }
    }

    public function create_order() {

        $payment = new PaymentWs();
        $this->context = Context::getContext(); // actualizar contexto
        

       
        
        $this->context->cart->update();

        $total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);
        $customer = new Customer($this->context->cart->id_customer);

        try {

            $payment->validateOrder((int) $this->context->cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $payment->displayName.'-SugarCRM', NULL, array(), (int) $this->context->currency->id, false, $customer->secure_key);

            $this->context = Context::getContext(); // actualizar contexto

            $respuesta = array('id_cart' => $this->context->cart->id,
                'id_order' => $this->context->smarty->tpl_vars['order']->value->id,
                'reference' => $this->context->smarty->tpl_vars['order']->value->reference);
                              
            // se crea la relación entre el empleado y la orden
              $emp_data=Utilities::get_data_employee($this->array_obj['id_employee']);
              Utilities::add_message($this->context->cart->id,$this->array_obj['content']['id_customer'], $emp_data['id_employee'] , $this->context->smarty->tpl_vars['order']->value->id ,"Pedido Sugar -- ".$emp_data['firstname'].' '.$emp_data['lastname'],1,$this->context->smarty->tpl_vars['order']->value->date_add);
        } catch (Exception $exc) {

            Logger::AddLog('Soket-webservice [sugar_to_ps-php] error al crear la orden ' . $exc->getTraceAsString(), 2, null, null, null, true);
            $this->errors[] = 'Error creando la orden: ' . $exc;
        }

        if (!count($this->errors) > 0) {
            $obj = array('entity' => 'order', 'id_employee' => 1, 'action' => 'response', 'content' => $respuesta, 'error' => array());
        } else {
            $obj = array('entity' => 'order', 'id_employee' => 1, 'action' => 'response', 'content' => $respuesta, 'error' => $this->errors);
        }
        $this->reponse = $obj;
    }
    /*
     *  
     */
    public function logtxt($text = "") {
        $fp = fopen("/tmp/archivo_log.txt", "a+");
        fwrite($fp, $text . "\r\n");
        fclose($fp);
    }
 public function valid_products($products){
     
     $ids_products="";
     $i=0;
            foreach ($products as $value) {
            if ($i < (count($products) - 1)) {
                $ids_products.=$value['id_product_ps'] . ',';
            } else {
                $ids_products.=$value['id_product_ps'];
            }
            $i++;
        }

        $query="   select prod.id_product,stock.quantity FROM
                ps_product prod 
                INNER JOIN ps_product_shop prods ON (prod.id_product=prods.id_product)
                INNER JOIN ps_stock_available stock on( prod.id_product=stock.id_product)
                LEFT JOIN ps_product_black_list black ON(prod.id_product=black.id_product)
                WHERE ISNULL(black.id_product) and prod.is_virtual !=1 AND prod.active=1 AND prods.active=1 and prod.id_product in(".$ids_products.");";

     
        $products_update=array();
      if ($results = Db::getInstance()->ExecuteS($query)) {
          foreach ($results as $value) {
              foreach ($products as $value2) {
                  if($value['id_product']===$value2['id_product_ps'] && ( (int)$value['quantity'] < (int)$value2['quantity']) ){
                      $products_update[]=array('id_product'=>$value2['id_product_ps'],'quantity'=> ((int)$value2['quantity']+1) );
                  }
                  
              }
              
          }
          
      }
   
       
      if(count($products_update)>0){
       $query="";
       foreach ($products_update as $value) {
           $query.="update  ps_product prod 
                    INNER JOIN ps_product_shop prods ON (prod.id_product=prods.id_product)
                    INNER JOIN ps_stock_available stock on( prod.id_product=stock.id_product)
                    LEFT JOIN ps_product_black_list black ON(prod.id_product=black.id_product)
                    SET stock.quantity = ".$value['quantity']."
                    WHERE ISNULL(black.id_product) and prod.is_virtual !=1 AND prod.active=1 AND prods.active=1 and prod.id_product = ".$value['id_product']."; "
                   . "";

           if ($results = Db::getInstance()->ExecuteS($query)) {
            return TRUE;   
           }else{
               return false;
           }
       }
      }

    return TRUE;
}

public function sync_tracker($_array_obj){

                $module_cd      = $_array_obj['content']['module_cd'];
                $key1           = $_array_obj['content']['key1'];
                $value1         = $_array_obj['content']['value1'];
                $key2           = $_array_obj['content']['key2'];
                $value2         = $_array_obj['content']['value2'];
                $modifiedtime   = $_array_obj['content']['modifiedtime'];    
    
            switch ($_array_obj['entity']) {
            case 'customer':
                /*
                 * Validar parametros de sincronización de customer
                 */
                if (!empty($module_cd)&& !empty($key1) && !empty($value1) && !empty($key2) && !empty($value2) && !empty($modifiedtime) ) {
                    $obj_sync_tracker= new Sync_tracker();
                    $obj_sync_tracker->setModule_cd=$module_cd;
                    $obj_sync_tracker->setKey1=$key1;
                    $obj_sync_tracker->setValue1=$value1;
                    $obj_sync_tracker->setKey2=$key2;
                    $obj_sync_tracker->setValue2=$value2;
                    $obj_sync_tracker->setModifiedtime->$modifiedtime;

                    
                    if($obj_sync_tracker->add_sync($module_cd,$key1,$value1,$key2,$value2,$modifiedtime))
                    {
                        return TRUE;
                    }else{
                        return false;
                    }
                   
                } else {
                    $this->errors[] = 'Los parametros de la solicitud no son validos';
                }
                break;

            default:
                $this->errors[] = 'Entidad no valida';
                break;
        }
    
    
}


    
}



if (isset($_POST['parameter1']) && !empty($_POST['parameter1']) && isset($_POST['parameter2']) && !empty($_POST['parameter2'])) {
    
 
    
    $t_hasher = new PasswordHash(8, FALSE);
    $hash = base64_decode($_POST['parameter1']);
    $correct = 'vVkGJ75611YgvTy8GuL1';
    $check = $t_hasher->CheckPassword($correct, $hash);
    if ($check) {
          
        try {

            $process_requets = new serverWsPs();
            $process_requets->process_request($_POST['parameter2']);
            echo json_encode($process_requets->reponse, JSON_PRETTY_PRINT);
            
        } catch (Exception $exc) {
            Logger::AddLog('webservice Json [sugar_to_ps-php] error solicitud POST SugarCRM ' . $exc->getTraceAsString(), 2, null, null, null, true);
        }
    }else{
        echo json_encode('!key', JSON_PRETTY_PRINT); 
    }
}  else {
echo json_encode('!', JSON_PRETTY_PRINT);    
}






