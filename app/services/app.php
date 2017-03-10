<?php
require_once('classes/Rest.inc.php');
require_once('classes/Model.php');

class API extends REST {

	public $id_lang_default = 0;

	public function __construct() 
	{
		parent::__construct(); // Init parent contructor
		$this->id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
	}

	/**
	 * Método público para el acceso a la API.
	 * Este método llama dinámicamente el método basado en la cadena de consulta
	 *
	 */
	public function processApi()
	{
		$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
		if((int)method_exists($this,$func) > 0)
			$this->$func();
		else
			$this->response('',404); // If the method not exist with in this class, response would be "Page not found".
	}

	/**
	 * Codifica el array en un JSON
	 */
	private function json($data)
	{
		if(is_array($data)){
			return json_encode($data);
		}
	}

	/** 
	 * Productos API
	 * Consulta de los productos debe ser por método GET
	 * expr : <Nombre del producto o referencia>
	 * page_number : <Número de página>
	 * page_size : <Filas por página>
	 * order_by : <Ordenar por ascendente ó descendente>
	 * order_way : <Ordenar por campo>
	 */
	private function search()
	{
		// Validación Cross si el método de la petición es GET de lo contrario volverá estado de "no aceptable"
		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
		}

		$expr        = $this->_request['expr'];
		$page_number = $this->_request['page_number'];
		$page_size   = $this->_request['page_size'];
		$order_by    = $this->_request['order_by'];
		$order_way   = $this->_request['order_way'];

		$model = new Model();
		$result = $model->productSearch($this->id_lang_default, $expr, $page_number, $page_size, $order_by,	$order_way);

		if (empty($result)) {
			// Si no hay registros, estado "Sin contenido"
			$this->response('', 204);
		} else {
			// Si todo sale bien, enviará cabecera de "OK" y la lista de la búsqueda en formato JSON
			$this->response($this->json($result), 200);
		}
	}

	/** 
	 * Inicio de sesión
	 * Válida credenciales de usuario, si todo sale bien agrega el usuario al contexto
	 * email : <Correo eléctronico>
	 * pwd : <Contraseña>
	 */
	private function login($email_sl = NULL,$passwd_sl = NULL)
	{
		// Validación Cross si el método de la petición es POST de lo contrario volverá estado de "no aceptable"
		if($this->get_request_method() != "POST") {
			$this->response('',406);
		}

		$email = strtolower(trim( $email_sl != NULL ? $email_sl : $this->_request['email']) );
		$password =  trim( $passwd_sl != NULL ? $passwd_sl : $this->_request['pwd']);

		// Validaciones de entrada
		if(!empty($email) and !empty($password)) {
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$customer = new Customer();
				$authentication = $customer->getByEmail($email, $password);

				// Login social meadia
				if($email_sl != NULL && $passwd_sl != NULL && Customer::customerExists($email_sl)){
					$authentication = $customer->getByEmailSM($email);
				}
				
				if (!$authentication || !$customer->id) {
					// Error de autenticación
					$this->response(array('success'=>FALSE), 204);	// Si no hay registros, estado "No Content"
				} else {
					$context = Context::getContext();
					$context->cookie->id_compare = isset($context->cookie->id_compare) 
					? $context->cookie->id_compare
					: CompareProduct::getIdCompareByIdCustomer($customer->id);
					$context->cookie->id_customer = (int)($customer->id);
					$context->cookie->customer_lastname = $customer->lastname;
					$context->cookie->customer_firstname = $customer->firstname;
					$context->cookie->logged = 1;
					$customer->logged = 1;
					$context->cookie->is_guest = $customer->isGuest();
					$context->cookie->passwd = $customer->passwd;
					$context->cookie->email = $customer->email;

					// Agrega el cliente a el contexto
					$context->customer = $customer;

					// Si todo sale bien, enviará cabecera de "OK" y los detalles del usuario en formato JSON
					unset($customer->passwd, $customer->last_passwd_gen);
					$gender = $customer->id_gender  == 1 ? 'M' : ($customer->id_gender  == 2 ? 'F' : "");
					$this->response($this->json(array(
					                'id' => (int) $customer->id,
					                'lastname' => $customer->lastname,
					                'firstname' => $customer->firstname,
					                'email' => $customer->email,
					                'newsletter' => (bool)$customer->newsletter,
					                'dni' => $customer->identification,
					                'gender' => $gender,
					                'id_type' => (int)$customer->id_type,
					                'birthday' => $customer->birthday,
					                'website' => $customer->website,
					                'company' => $customer->company,
					                'success' => TRUE)), 200);
				}
			}
		}

		// Si las entradas son inválidas, mensaje de estado "Bad Request" y la razón
		$this->response($this->json(array(
		                "success" => false, 
		                "message" => "Dirección de correo electrónico o contraseña no válidos"
		                )), 400);
	}

	private function logout()
	{
		$context = Context::getContext();
		$context->customer->mylogout();
		$this->response('', 200);
	}

	private function test()
	{
		$context = Context::getContext();
		$this->response($this->json((array) $context->customer), 200);
	}

	private function isLogin()
	{
		$context = Context::getContext();
		$this->response(json_encode($context->customer->isLogged()), 200);
	}

	private function categories()
	{
		$model = new Model();
		$this->response(json_encode($model->get_category(2, 3, TRUE)),200);
	}


	public function prodCategories() {

		// Validación Cross si el método de la petición es POST de lo contrario volverá estado de "no aceptable"
		if ($this->get_request_method() != "POST") {
			$this->response('', 406);
		}    

		$ids         = $this->_request['ids'];
		$page_number = $this->_request['page_number'];
		$page_size   = $this->_request['page_size'];
		$order_by    = (isset($this->_request['order_by']))?$this->_request['order_by']:NULL;
		$order_way   = $this->_request['order_way'];

		$ids_cats = explode(",", $ids);
		if(!is_array($ids_cats))
			$ids_cats[] = array((int)$ids_cats);

		$model = new Model();

		$result = $model->getProdCategories($ids_cats, $page_number,$page_size, $order_way,$order_by);

		if (empty($result)) {
			// Si no hay registros, estado "Sin contenido"
			$this->response('', 204);
		} else {
			// Si todo sale bien, enviará cabecera de "OK" y la lista de la búsqueda en formato JSON
			$this->response($this->json($result), 200);
		}

	}  

	public function getProductQuantity()
	{
		$result = $this->getProductRealQuantities($this->_request['id'], 0);
		if (empty($result)) {
			// Si no hay registros, estado "Sin contenido"
			$this->response('', 204);
		} else {
			// Si todo sale bien, enviará cabecera de "OK" y la lista de la búsqueda en formato JSON
			$this->response($this->json($result), 200);
		}
	}

	private function header()
	{

	}

	private function myAccount()
	{

	}	
	private function orderHistory()
	{

	}

	private function footer()
	{

	}

	private function product() {

		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
		}

		$id_prod        = $this->_request['id'];

		$model = new Model();
		return $this->response(json_encode($model->getProduct($id_prod)),200);
	}

	private function manufacturers(){
		$model = new Model();
		
		return $this->response(json_encode($model->manufacturers()),200);
	}

	private function socialLogin(){

		$arguments['firstname']	= $this->_request['firstname'];
		$arguments['lastname']	= $this->_request['lastname'];
		$arguments['email']		= $this->_request['email'];
		$arguments['id']		= $this->_request['id'];
		$arguments['passwd'] 	= NULL;
		$arguments['gender'] 	= substr($this->_request['gender'], 0,1);

		if (Validate::isEmail($arguments['email']) && !empty($arguments['id']) && !empty($arguments['firstname']) ){

			$tem_data = explode("@", $arguments['email']);
			$arguments['passwd'] = md5($tem_data[1].$arguments['id'].$tem_data[0]);
			if(!Customer::customerExists($arguments['email'])){
				$model = new Model();
				if($customer = $model->setAccount($arguments)) {
					$this->response($this->json( $customer ),200);
				}

			}else{
				$this->login($arguments['email'],$arguments['passwd']);
			} 
		}

	}

	private function createAccount($update = false){

		// Validación Cross si el método de la petición es POST de lo contrario volverá estado de "no aceptable"
		if ($this->get_request_method() != "POST") {
			$this->response('', 406);
		} 

		$arguments = array();
		$arguments['firstname']		= (isset($this->_request['firstname']))?$this->_request['firstname']:NULL;
		$arguments['lastname']		= (isset($this->_request['lastname']))?$this->_request['lastname']:NULL;
		$arguments['gender'] 		= (isset($this->_request['gender']))?$this->_request['gender']:NULL;
		$arguments['email']			= (isset($this->_request['email']))?$this->_request['email']:NULL;
		$arguments['passwd']		= (isset($this->_request['passwd']))?$this->_request['passwd']:NULL;
		$arguments['signon']		= (isset($this->_request['signon']))?$this->_request['signon']:NULL;			
		$arguments['news']			= (isset($this->_request['news']))?$this->_request['news']:NULL;
		$arguments['dni']			= (isset($this->_request['dni']))?$this->_request['dni']:NULL;
		$arguments['birthday']		= (isset($this->_request['birthday']))?$this->_request['birthday']:NULL;
		$arguments['website']		= (isset($this->_request['website']))?$this->_request['website']:NULL;
		$arguments['company']		= (isset($this->_request['company']))?$this->_request['company']:NULL;
		$arguments['id_type']		= (isset($this->_request['id_type']))?$this->_request['id_type']:NULL;
		$arguments['update']		=  (isset($this->_request['update']))?$this->_request['update']:NULL;	
		$arguments['img_profile']	=  NULL;


		if (Validate::isEmail($arguments['email']) && !empty($arguments['email'])){
			if(!$update){
				if(Customer::customerExists($arguments['email'])){
		// Si las entradas son inválidas, mensaje de estado "Bad Request" y la razón
					$this->response($this->json(array(
					                "success" => false, 
					                "message" => "No se pudo crear la cuenta, el (".$arguments['email']." ) email ya esta registrado"
					                )), 400);
				}
			}
		}else{
			$this->response($this->json(array(
			                "success" => false, 
			                "message" => "se requiere un correo valido (".$arguments['email'].' )' 
			                )), 400);
		}
		if (!Validate::isPasswd($arguments['passwd']) && isset($arguments['update']) && empty($arguments['update']))
			$this->response($this->json(array(
			                "success" => false, 
			                "message" => "La contraseña no es valida, utiliza una contraseña con una longitud mínima de 5 caracteres." 
			                )), 400);	

		$model = new Model();
		if($customer = $model->setAccount($arguments)) {
			$this->response($this->json( $customer ),200);
		}

		$this->response($this->json(array(
		                "success" => false, 
		                "message" => "Error creando la cuenta."
		                )), 400);

	}

	private function updateAccount(){
		$this->createAccount(TRUE);
	}

	private function addresses(){

			// Validación Cross si el método de la petición es GET de lo contrario volverá estado de "no aceptable"
		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
		} 

		$id_customer	= $this->_request['id_customer'];
		$id_address	= (isset($this->_request['id_address']))?$this->_request['id_address']:NULL;
		$model = new Model();		
		return $this->response(json_encode($model->get_address($id_customer,$id_address)),200);	

	} 			

	private function setAddress(){


			// Validación Cross si el método de la petición es GET de lo contrario volverá estado de "no aceptable"
		if ($this->get_request_method() != "POST") {
			$this->response('', 406);
		} 


		$arg = array();

		$arg['id_customer'] = (isset($this->_request['id_customer']))?$this->_request['id_customer']:NULL;
		$arg['id_country'] = (isset($this->_request['id_country']))?$this->_request['id_country']:NULL;
		$arg['id_state'] = (isset($this->_request['id_state']))?$this->_request['id_state']:NULL;
		$arg['alias'] = (isset($this->_request['alias']))?$this->_request['alias']:NULL;
		$arg['lastname'] = (isset($this->_request['lastname']))?$this->_request['lastname']:NULL;
		$arg['firstname'] = (isset($this->_request['firstname']))?$this->_request['firstname']:NULL;
		$arg['address1'] = (isset($this->_request['address1']))?$this->_request['address1']:NULL;
		$arg['address2'] = (isset($this->_request['address2']))?$this->_request['address2']:NULL;
		$arg['city'] = (isset($this->_request['city']))?$this->_request['city']:NULL;
		$arg['phone'] = (isset($this->_request['phone']))?$this->_request['phone']:NULL;
		$arg['mobile'] = (isset($this->_request['mobile']))?$this->_request['mobile']:NULL;
		$arg['dni'] = (isset($this->_request['dni']))?$this->_request['dni']:NULL;
		$arg['postcode'] = (isset($this->_request['postcode']))?$this->_request['postcode']:NULL;	
		$arg['id_colonia'] = (isset($this->_request['id_colonia']))?$this->_request['id_colonia']:NULL;
		$arg['is_rfc'] = (isset($this->_request['is_rfc']))?$this->_request['is_rfc']:NULL;
		$arg['id_city'] = (isset($this->_request['id_city']))?$this->_request['id_city']:NULL;
		$arg['id'] = (isset($this->_request['id']))?$this->_request['id']:NULL;


		$model = new Model();		
		return $this->response(json_encode($model->set_address($arg)),200);	

	}

	private function getPostCodeInfo() {

		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
		} 

		$postcode	= $this->_request['postcode'];
		$model = new Model();

		return $this->response(json_encode($model->get_fromPostcode($postcode)),200);	

	}	


	private function getColoniaByIdCity() {

		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
		} 

		$id_city	= $this->_request['id_city'];
		$model = new Model();

		return $this->response(json_encode($model->get_colonia_fromid_city($id_city)),200);	

	}

	private function countries() {

		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
		} 

		$model = new Model();

		return $this->response(json_encode($model->get_countries()),200);	

	}
/**
 * 
 */
private function states(){
	if ($this->get_request_method() != "GET") {
		$this->response('', 406);
	} 
	$id_country = 	$this->_request['id_country'];

	$model = new Model();

	return $this->response(json_encode($model->get_states($id_country)),200);	
	
}
/**
 * 
 */
private function cities(){
	if ($this->get_request_method() != "GET") {
		$this->response('', 406);
	} 
	$id_state = 	$this->_request['id_state'];

	$model = new Model();

	return $this->response(json_encode($model->get_cities($id_state)),200);	
	
}

private function costoEnvio(){
	if ($this->get_request_method() != "GET") {
		$this->response('', 406);
	} 
	$id_city = 	$this->_request['id_city'];

	$model = new Model();

	return $this->response(json_encode($model->get_costo_envio($id_city)),200);		
}
/**
 * AddVoucher 
 */
private function cart(){

	if ($this->get_request_method() != "POST") {
		$this->response('', 406);
	}

	$param['products'] = 		$this->_request['products'];
	$param['id_customer'] = 	$this->_request['id_customer'];
	$param['discounts'] = 		$this->_request['discounts'];
	$param['deleteDiscount'] = 	(isset($this->_request['deleteDiscount']))?$this->_request['deleteDiscount']: NULL;
	$param['id_address'] = 		$this->_request['id_address'];
	$param['msg'] = 			$this->_request['msg'];
	$param['id_cart'] = 		(isset($this->_request['id_cart']) && $this->_request['id_cart'] > 0) ? $this->_request['id_cart'] : NULL;
	$param['clear'] = 		(!empty($this->_request['clear'])  ? (boolean) $this->_request['clear'] : FALSE);

	$model = new Model();
	$this->response($this->json($model->cart($param['products'],$param['id_customer'],$param['id_address'],$param['discounts'],$param['deleteDiscount'],$param['msg'],$param['id_cart'],$param['clear'])),200);
}
/**
 * 
 */

public function pay(){
	$param['payment'] = 	$this->_request['payment'];
	$param['products'] = 	$this->_request['products'];
	$param['id_customer'] = 	$this->_request['id_customer'];
	$param['id_address'] = 	$this->_request['id_address'];
	$param['discounts'] = 		$this->_request['discounts'];
	$param['msg'] = 			$this->_request['msg'];
	$param['id_cart'] = 		($this->_request['id_cart'] > 0 ? $this->_request['id_cart'] : NULL);			

	$model = new Model();
	$this->response($this->json($model->pay($param)),200);	
}

public function bankPse(){
	return $this->response($this->json(PasarelaPagoCore::get_bank_pse()),200);	
}

public function KeysOpenPay(){
	return $this->response($this->json(PasarelaPagoCore::get_keys_open_pay('Tarjeta_credito')),200);	
}

public function franquicia(){
	$cart_number = 	$this->_request['cart_number'];
	$this->response(json_encode( PasarelaPagoCore::getFranquicia($cart_number, 'payulatam')),200);
}

public function addImg(){
			// Validación Cross si el método de la petición es POST de lo contrario volverá estado de "no aceptable"
	if($this->get_request_method() != "POST") {
		$this->response('',406);
	}

	//$str_img = 	$this->_request['str_img'];
	$option = 	$_REQUEST['option']; //$this->_request['option'];

	$model = new Model();

	// $this->isLogin();

	// $context = Context::getContext();
 //    $this->response(json_encode($context->customer));

	$flag = true;
	foreach ($_FILES as $key) {
		$imagen = $model->add_image($key,$option);
		if(!$imagen){
			$flag = false;
			break;
		}
	}
	// $this->response(json_encode($imagen));
	$this->response(json_encode(array('success'=>$flag)),200);
}

public function password(){

	$email = $this->_request['email'];

/*	if ($this->get_request_method() != "POST") {
		$this->response('', 406);
	}*/
	$model = new Model();
	
//exit(json_encode($email));
	return $this->response($this->json($model->password($email)),200);	
}


/**
 * Retorna las ordenes generadas por un usuario
 */
public function getHistory(){

	$id_customer = 	$this->_request['id'];
	$orders_out = array();
	if ($orders = Order::getCustomerOrders($id_customer))
		$contador = 0;
	foreach ($orders as &$order)
	{
		$contador ++;
		$myOrder = new Order((int)$order['id_order']);
		if (Validate::isLoadedObject($myOrder))
			$order['virtual'] = $myOrder->isVirtual(false);

		$order_state = Db::getInstance()->getValue("SELECT  `name` FROM ps_order_state_lang WHERE id_order_state = ". (int) $order['current_state']);

		$date = new DateTime($order['date_add']);	
		$address = new Address((int) $order['id_address_invoice']);
		$address_str = 	$address->address1.' '.$address->address2.' '.$address->city.'. C.P. '.$address->postcode;	
		$orders_out[] = array('id' => (int) $order['id_order'] ,
		                      'state' =>  $order_state ,
		                      'ref' => $order['reference'] ,
		                      'id_customer' => (int) $order['id_customer'] ,
		                      'id_cart' => (int) $order['id_cart'] ,
		                      'id_address_delivery' => (int) $order['id_address_delivery'] ,
		                      'id_address_invoice' => (int) $order['id_address_invoice'] ,
		                      'address' => $address_str,
		                      'payment' => $order['payment'] ,
		                      'gift_message' => $order['gift_message'] ,
		                      'total' => (float) $order['total_paid'] ,
		                      'total_shipping' => (float) $order['total_shipping'] ,
		                      'total_products' => (float) $order['total_products'] ,
		                      'total_discounts' => (float) $order['total_discounts'] ,
		                      'invoice_number' => (int) $order['invoice_number'],
		                      'date_add' => $date->format("d/m/Y"),
		                      'order_detail' => $this->orderDetail((int) $order['id_order']));
if($contador == 20)
	break;

}

return $this->response($this->json($orders_out),200);
}

private function orderDetail($id_order = NULL){
/*		if ($this->get_request_method() != "POST") {
			$this->response('', 406);
		}*/

		$id = $this->_request['id'];
		$model = new Model();
		if($id_order != NULL)
			return $model->get_order_datail($id_order);

		$this->response($this->json($model->get_order_datail($id)),200);
	}


	private function docTypes(){

		$model = new Model();
		$this->response($this->json($model->get_type_docs()),200);
	}

	private function tracker(){
/*		if ($this->get_request_method() != "POST") {
			$this->response('', 406);
		}*/

		$id_order = 	$this->_request['id'];
		$model = new Model();
		$this->response($this->json($model->get_traker_order($id_order)),200);

	}



	private function callback(){

		if ($this->get_request_method() != "GET" && $this->get_request_method() != "POST") {
			$this->response('', 406);
		}

		$model = new Model();
		//$this->response($this->json($model->get_traker_order($id_order)),200);

		$accountObj = $model->call_api($_REQUEST['accessToken'],"https://www.googleapis.com/plus/v1/people/me");

		return $this->response(json_encode($accountObj),200);
		
	}

	/**
	 * Genera la ruta relativa de las imágenes publicitarias por directorio
	 * dirname : <Nombre del directorio que contiene las imágenes>
	 */
	private function publicityBanners()
	{
		if ($this->get_request_method() != "GET") {
			$this->response('', 406);
	    }

	    if (!isset($this->_request['dirname']) 
	        || empty($this->_request['dirname'])) {
			$this->response('', 204);
	    }

	    $dirname = $this->_request['dirname'];
		$dir = "../publicity/banners/" . $dirname . "/";
		$images = glob($dir . "*.jpg");
		$this->response(json_encode($images), 200);
	}

	/**
	   * Miera si la dirección dada se encuentra en otras ciudades para denegarle acceso a pago contraentrega
	   * @return [type] [description]
	   */
	public function otraCiudad()
	{
	    if ($this->get_request_method() != "POST") {
	      $this->response('', 406);
	    }

	    $id_address =   $this->_request['id'];
	    $model = new Model();
	    $this->response($this->json($model->list_medios_de_pago($id_address)),200);
	}

}


// Access-Control-Allow-Origin | CORS
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Authorization");
//header("Access-Control-Allow-Headers: Content-Type,x-prototype-version,x-requested-with");
//header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, PATCH");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');

// Iniciar
$api = new API;
$api->processApi();

?>
