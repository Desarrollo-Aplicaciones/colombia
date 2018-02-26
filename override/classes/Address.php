<?php
class Address extends AddressCore
{

/** @var int id_colonia */
    public $id_colonia = 0;

    /** @var string colonia_list */
    public $colonia_list = '';

    /** @var string city_name */
    public $city_name = '';

    /** @var string address_city_list */
    public $address_city_list = ''; //<option value=""> - ciudad - </option>';

    protected  $parameters=NULL; 

    protected static $_idZones = array();
    protected static $_idCountries = array();

    /**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $null_values = false)
	{
        if (!parent::add($autodate, $null_values))
			return false;

		if (Validate::isUnsignedId($this->id_customer))
            Customer::resetAddressCache($this->id_customer);

        $dir_change = explode( "|", $this->city );
    
        if ( ($this->postcode == '' ||  $this->postcode == NULL) && ( isset($dir_change[1]) && $dir_change[1] != '' && $dir_change[1] != NULL ) ) {
            $this->postcode = $dir_change[1];
        }

        $idCity = 0;
		if ( is_numeric($dir_change[0]) && $dir_change[0] != '' && $dir_change[0] != null) {
			$arr_nom_city = City::getCityByIdCity($dir_change[0]);
			$this->city = $arr_nom_city[0]['city_name'];
            $idCity = $dir_change[0];
		} elseif ( isset($_POST['city_id']) && is_numeric($_POST['city_id'])) {
            $arr_nom_city = City::getCityByIdCity($_POST['city_id']);
            $this->city = $arr_nom_city[0]['city_name'];
            $idCity = $_POST['city_id'];
        }
        $idCity = empty($idCity) ? (Tools::getValue("id_city") ? Tools::getValue("id_city") : Tools::getValue("city_id")) : $idCity;

        if ($idCity) {
            Db::getInstance()->insert('address_city', array(
                'id_address'=>(int)$this->id, 
                'id_city'=>(int)$idCity
            ));
        }

        if (Tools::getValue("type_document") 
            && Tools::getValue("number_document") 
            && $this->id_customer) {
            $customer = new Customer( $this->id_customer );
			$customer->firstname = Tools::getValue("firstname");
			$customer->lastname = Tools::getValue("lastname");
			$customer->id_type = Tools::getValue("type_document");
			$customer->identification = Tools::getValue("number_document");
			$customer->birthday = Tools::getValue("birthday");
			if( $customer->id_type == 4 ) {
				$customer_group[0] = 4;
				$customer->addGroups($customer_group);
			}
			$customer->update();
			$customer->updateGroupDiscount($customer->identification);
        }

		return true;
	}

    public function update($null_values = false)
    {
        // Empty related caches
        if (isset(self::$_idCountries[$this->id]))
            unset(self::$_idCountries[$this->id]);
        if (isset(self::$_idZones[$this->id]))
            unset(self::$_idZones[$this->id]);

        if (Tools::getValue("id_city") || Tools::getValue("city_id")) {
            $this->id_city = Tools::getValue("id_city") ? Tools::getValue("id_city") : Tools::getValue("city_id");
        }
        
        if (!isset($this->id_city) || empty($this->id_city)) {
            return false;
        }

        $result = Db::getInstance()->update('address_city', array('id_city'=>(int)$this->id_city), 'id_address = '.(int)$this->id);
        if (!$result) {
            return false;
        }

        return ObjectModel::update($null_values);
    }

   	/**
	 * @see ObjectModel::delete()
	 */
	public function delete()
	{
		$customers_add = new Customer($this->id_customer);
		$this->lastname = $customers_add->lastname;
		$this->firstname = $customers_add->firstname;

		if (Validate::isUnsignedId($this->id_customer))
			Customer::resetAddressCache($this->id_customer);

		if (!$this->isUsed())
			return parent::delete();
		else
		{
			$this->deleted = true;
			return $this->update();
		}
	}
        
        
public  function get_countries() {

        $query = "select country.id_country, country.`name` FROM
                  "._DB_PREFIX_."country_active active INNER JOIN "._DB_PREFIX_."country_lang country
                  ON( active.id_country = country.id_country);";

        if ($results = Db::getInstance()->ExecuteS($query)) {

            $str_countries   = '<option value="">- Pais -</option>';
            if (count($results) > 0) {
                foreach ($results as $value) {
                    $str_countries .= '<option value="' . $value['id_country'] . '">' . $value['name'] . '</option>';
                }
                return json_encode(array('results' => $str_countries));
            }
        }

        return '0';
    }

public  function get_countries_app() {

        $query = "select country.id_country as `id`, country.`name` FROM
                  "._DB_PREFIX_."country_active active INNER JOIN "._DB_PREFIX_."country_lang country
                  ON( active.id_country = country.id_country);";

        if ($results = Db::getInstance()->ExecuteS($query)) {
            return $results;
        }

        return false;
    }    

    public function get_states($id_country) {

        $query = "select  state.id_state,state.`name` FROM
ps_country country
INNER JOIN ps_state state ON (country.id_country= state.id_country)
WHERE country.id_country=" . (int) $id_country . ";";

        if ($results = Db::getInstance()->ExecuteS($query)) {

            $str_states = '';
            if (count($results) > 0 && !empty($results) && is_array($results) ) {
                foreach ($results as $value) {
                    if (isset($_POST['selected']) && ((int) $_POST['selected']) > 0 && ((int) $_POST['selected']) === ((int) $value['id_state'])) {
                        $str_states .= '<option value="' . $value['id_state'] . '" selected >' . $value['name'] . '</option>';
                    } else {
                        $str_states .= '<option value="' . $value['id_state'] . '">' . $value['name'] . '</option>';
                    }
                }
                return json_encode(array('results' => $str_states));
            }
        }
        return '0';
    }

static public function get_states_app($id_country) {

        $query = "select  state.id_state as `id`,state.`name` FROM
        "._DB_PREFIX_."country country
        INNER JOIN "._DB_PREFIX_."state state ON (country.id_country= state.id_country)
        WHERE country.id_country=" . (int) $id_country . ";";
        $citye_obj = new City();

        if ($results = Db::getInstance()->ExecuteS($query)) {
            $cities = $citye_obj->getPriorityCitiesWithState();
            error_log(gettype($cities));
            $results = array_merge($cities, $results);
            return $results;
        }

        return false;
    }    

    public function get_cities($id_state) {

        $str_cities = '<option value="">- Ciudad -</option>';
        $citye_obj = new City();
        $cities = $citye_obj->getCitiesByStateAvailable($id_state);
        if (count($cities) > 0 && !empty($cities) && is_array($cities) ) {
            foreach ($cities as $row) {
                if (isset($_POST['selected']) && ((int) $_POST['selected']) > 0 && ((int) $_POST['selected']) === ((int) $row['id_city'])) {
                    $str_cities .= '<option value="' . $row['id_city'] . '" selected >' . $row['city_name'] . '</option>';
                } else {
                    $str_cities .= '<option value="' . $row['id_city'] . '">' . $row['city_name'] . '</option>';
                }
            }
            $array_result = array('results' => $str_cities);
            return json_encode($array_result);
        }

        return '0';
    }

    static public function get_cities_app($id_state) {

       
        $citye_obj = new City();
        $cities = $citye_obj->getCitiesByStateAvailable($id_state);
        if (count($cities) > 0 && !empty($cities) && is_array($cities) ) {
            $out_array = array();
            foreach ($cities as $key => $value) {
                $out_array[$key]['id'] = $value['id_city'];
                $out_array[$key]['name'] = $value['city_name'];
            }
            return $out_array;
        }

        return false;
    }    

    public function get_list_address($billing_account_id) {

        $id_customer = $this->get_id_custumer_account($billing_account_id);
        $str_cityes = '<option value="">- Direcciones -</option>';
        $val_express = Configuration::get('ENVIO_EXPRESS') ? Configuration::get('ENVIO_EXPRESS') : 0;

        if ($id_customer !== '') {
            /*$query = "select address.id_address,address.alias,address.postcode, address.address1,cities.city_name,state.`name` as state_name,country.`name` as country_name FROM
                        ps_customer customer 
                        INNER JOIN ps_address address ON(customer.id_customer=address.id_customer)
                        INNER JOIN ps_address_city city ON(city.id_address=address.id_address)
                        INNER JOIN ps_cities_col cities ON (cities.id_city=city.id_city)
                        INNER JOIN ps_state state ON( state.id_state=cities.id_state)
                        INNER JOIN ps_country_lang country ON( country.id_country=state.id_country)
                        WHERE customer.id_customer IN (
                        select customer.id_customer FROM
                        ps_sync_tracker sync INNER JOIN ps_customer customer
                        ON(sync.key2=customer.id_customer && sync.value2=customer.email)
                        WHERE sync.key1='" . $billing_account_id . "' GROUP BY customer.id_customer );";*/

            $query = "SELECT address.id_address, address.alias, address.postcode, address.address1, cities.city_name, 
                    state.`name` AS state_name, country.`name` AS country_name,
                    cac.precio_kilo, car.id_carrier, 
                    SUBSTRING(REPLACE( crp.delimiter2,'.',','),1,
                    LENGTH(REPLACE( crp.delimiter2,'.',',')) -7) AS delimiter2,
                    cac.express_abajo AS abajo,
                    cac.express_arriba AS arriba
            FROM ps_customer customer 
                    INNER JOIN ps_address address ON (customer.id_customer=address.id_customer)
                    INNER JOIN ps_address_city city ON (city.id_address=address.id_address)
                    INNER JOIN ps_cities_col cities ON (cities.id_city=city.id_city)
                    INNER JOIN ps_state state ON ( state.id_state=cities.id_state)
                    INNER JOIN ps_country_lang country ON ( country.id_country=state.id_country)
                        INNER JOIN ps_carrier_city cac ON (cac.id_city_des = city.id_city) 
                        INNER JOIN ps_carrier car ON (car.id_reference = cac.id_carrier AND car.deleted = 0 AND car.active=1) 
                        INNER JOIN ps_range_price crp ON (crp.id_carrier = car.id_carrier)
                    WHERE customer.id_customer IN (
                                SELECT customer.id_customer FROM
                                ps_sync_tracker sync INNER JOIN ps_customer customer
                                ON (sync.key2 = customer.id_customer && sync.value2 = customer.email)
                                WHERE sync.key1='" . $billing_account_id . "' GROUP BY customer.id_customer )";
            $arr_dirs_user = array();

            if ($results = Db::getInstance()->ExecuteS($query)) {

                if ( count($results) > 0 && !empty($results) && is_array($results)) {

                    foreach ($results as $value) {
                        $str_cityes .= '<option value="' . $value['id_address'] . '">' . $value['city_name'] . ' _ ' . $value['alias'] . ' _ ' . $value['postcode'] . ' _ ' . $value['address1'] . ' _ ' . $value['city_name'] . ' ' . $value['state_name'] . ' ' . $value['country_name'] . '</option>';

                        $arr_dirs_user [ $value['id_address'] ] ['precio'] = $value['precio_kilo'];
                        $arr_dirs_user [ $value['id_address'] ] ['id_carrier'] = $value['id_carrier'];
                        $arr_dirs_user [ $value['id_address'] ] ['umbral'] = $value['delimiter2'];

                        if( isset($val_express) && $val_express != 0) {
                            $arr_dirs_user [ $value['id_address'] ] ['express'] = $val_express;
                            $arr_dirs_user [ $value['id_address'] ] ['abajo'] = $value['abajo'];
                            $arr_dirs_user [ $value['id_address'] ] ['arriba'] = $value['arriba'];
                        }

                    }
                }
            }           
           
            return json_encode(array('results' => $str_cityes, 'id_customer' => $id_customer, 'precio_envio' => $arr_dirs_user));
        }

        return '0';
    }


public static function get_list_address_app($id_customer, $id_address = NULL) {

    $val_express = Configuration::get('EXPRESS') ? Configuration::get('EXPRESS') : 0;

    if ($id_customer !== '') {

        $query = "SELECT address.id_address, address.alias, address.postcode, address.address1,address.address2, cities.city_name, 
        state.`name` AS state_name, country.`name` AS country_name,
        cac.precio_kilo, car.id_carrier, 
        SUBSTRING(REPLACE( crp.delimiter2,'.',','),1,
                  LENGTH(REPLACE( crp.delimiter2,'.',',')) -7) AS delimiter2,
cac.express_abajo AS abajo,
cac.express_arriba AS arriba,address.phone,address.phone_mobile as mobile,address.id_state, add_city.id_city 
FROM "._DB_PREFIX_."customer customer 
INNER JOIN "._DB_PREFIX_."address address ON (customer.id_customer=address.id_customer)
INNER JOIN "._DB_PREFIX_."address_city city ON (city.id_address=address.id_address)
INNER JOIN "._DB_PREFIX_."cities_col cities ON (cities.id_city=city.id_city)
INNER JOIN "._DB_PREFIX_."state state ON ( state.id_state=cities.id_state)
INNER JOIN "._DB_PREFIX_."country_lang country ON ( country.id_country=state.id_country)
INNER JOIN "._DB_PREFIX_."carrier_city cac ON (cac.id_city_des = city.id_city) 
INNER JOIN "._DB_PREFIX_."carrier car ON (car.id_reference = cac.id_carrier AND car.deleted = 0 AND car.active=1) 
INNER JOIN "._DB_PREFIX_."range_price crp ON (crp.id_carrier = car.id_carrier)
INNER JOIN "._DB_PREFIX_."address_city add_city ON(address.id_address = add_city.id_address)
WHERE customer.id_customer = ".(int) $id_customer.($id_address != NULL ? (' AND address.id_address = '.(int)$id_address) : '') ;
// error_log($query, 3, "/var/www/test.farmalisto.com.co/app/log/errors.log");

if ($results = Db::getInstance()->ExecuteS($query)) {

    if ( count($results) > 0 && !empty($results) && is_array($results)) {

if(count($results) === 1 && !empty($id_address) && (int)$id_address > 0)
        return $results[0];
    return $results;

    }
}           

}

return false;
}

   
        public function get_costo_envio($id_city) {

     
   
        $val_express = Configuration::get('ENVIO_EXPRESS') ? Configuration::get('ENVIO_EXPRESS') : 0;

            $query = "SELECT 							cities.city_name, 
                    state.`name` AS state_name, country.`name` AS country_name,
                    cac.precio_kilo, car.id_carrier, 
                    SUBSTRING(REPLACE( crp.delimiter2,'.',','),1,
                    LENGTH(REPLACE( crp.delimiter2,'.',',')) -7) AS delimiter2,
                    cac.express_abajo AS abajo,
                    cac.express_arriba AS arriba
FROM  ps_cities_col cities 
                    INNER JOIN ps_state state ON ( state.id_state=cities.id_state)
                    INNER JOIN ps_country_lang country ON ( country.id_country=state.id_country)
                        INNER JOIN ps_carrier_city cac ON (cac.id_city_des = cities.id_city) 
                        INNER JOIN ps_carrier car ON (car.id_reference = cac.id_carrier AND car.deleted = 0 AND car.active=1) 
                        INNER JOIN ps_range_price crp ON (crp.id_carrier = car.id_carrier)
                    WHERE cities.id_city = ".(int)$id_city;
            $arr_dirs_user = array();

            if ($results = Db::getInstance()->ExecuteS($query)) {

                if ( count($results) > 0 && !empty($results) && is_array($results)) {

                    foreach ($results as $value) {

                        $arr_dirs_user ['precio'] = $value['precio_kilo'];
                        $arr_dirs_user ['id_carrier'] = $value['id_carrier'];
                        $arr_dirs_user ['umbral'] = $value['delimiter2'];

                        if( isset($val_express) && $val_express !== 0) {
                            $arr_dirs_user ['express'] = $val_express;
                            $arr_dirs_user ['abajo'] = $value['abajo'];
                            $arr_dirs_user ['arriba'] = $value['arriba'];
                        }

                    }
                }
            }           
           
            return json_encode(array('precio_envio'=>$arr_dirs_user));
  
    }
    
    
        public function get_id_custumer_account($billing_account_id, $json = false) {
        $query = "select custumer.id_customer
                  from ps_customer custumer INNER JOIN ps_sync_tracker track on(custumer.id_customer=track.key2)
                  WHERE track.key1='" . $billing_account_id . "' AND track.sync_module_cd='PS_CUSTOMER'
                  GROUP BY track.key1;";

        if ($results = Db::getInstance()->ExecuteS($query)) {
            $id_customer='';
            if (count($results) > 0) {
                /* @var $json type  boolean */
                if ($json) {
                    return json_encode(array('id_customer' => $id_customer = $results[0]['id_customer']));
                } else {
                    return  $id_customer = $results[0]['id_customer'];
                }
            }
        }
        return '!';
    }
   
    public static function horaDeEntrega()
    {    
        date_default_timezone_set('America/Bogota');

        $inicio_intervalos = (int) Configuration::get('INIT_INTERVALS');
        $fecha = new DateTime(date('Y-m-d H:i:s'));
        $strDate = NULL;

        if ($fecha->format('i') < 30) {
            $strDate = $fecha->format('Y-m-d H:').'30'.$fecha->format(':s');
            $date = new DateTime($strDate);
            $date->add(new DateInterval('PT'.$inicio_intervalos.'H'));
            $strDate = $date->format('Y-m-d H:i');
        } else {
            $strDate = $fecha->format('Y-m-d H:').'00'.$fecha->format(':s');
            $date = new DateTime($strDate);
            $date->add(new DateInterval('PT'.$inicio_intervalos.'H'));
            $strDate = $date->format('Y-m-d H:i');
        }

        $dias = array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $day_delivered = '';
        $horasSelect = array();

        $numeroDeHoras = (int) Configuration::get('MAX_HOURS_DELIVERED'); // MAX_HOURS_DELIVERED
        $hora_inicio = (int) Configuration::get('INIT_HOUR_DELIVERE');
        $hora_fin = (int) Configuration::get('END_HOUR_DELIVERE');
        $ventanaDeHoras = (int) Configuration::get('WINDOWS_HOUR_DELIVERED'); // WINDOWS_HOUR_DELIVERED

        if ($numeroDeHoras == 0) $numeroDeHoras = 48;
        if ($ventanaDeHoras == 0) $ventanaDeHoras = 2;

        $day = 0;
        for ($i=0; $i < $numeroDeHoras; $i+=$ventanaDeHoras) {
            $date_1 = new DateTime($strDate);
            $date_1->add(new DateInterval('PT'.$i.'H'));
            $now = new DateTime(date('Y-m-d H:i:s'));
            $dNow = $now->format('d');

            if ($day !=  ((int) $date_1->format('d'))){
                if ((int)$dNow == (int)$date_1->format('d')) {
                    $day_delivered .='<option value="'.$date_1->format('Y-m-d').'">Hoy '.$dias[idate('w', $date_1->getTimestamp())]." ". $date_1->format('d '). substr($meses[idate('n', $date_1->getTimestamp())-1], 0, 3).'</option>';
                } elseif (((int)$dNow + 1) == (int)$date_1->format('d')) {
                    $day_delivered .='<option value="'.$date_1->format('Y-m-d').'">Mañana '.$dias[idate('w', $date_1->getTimestamp())]." ". $date_1->format('d '). substr($meses[idate('n', $date_1->getTimestamp())-1], 0, 3).'</option>';
                } else {
                     $day_delivered .='<option value="'.$date_1->format('Y-m-d').'">El '.$dias[idate('w', $date_1->getTimestamp())]." ". $date_1->format('d '). substr($meses[idate('n', $date_1->getTimestamp())-1], 0, 3).'</option>';
                }

                $day = (int)$date_1->format('d');                    
            }

            $date_2 = new DateTime(date($date_1->format('Y-m-d H:i:s')));
            $date_2->add(new DateInterval('PT'.$ventanaDeHoras.'H'));
            $hora_in = (int)$date_1->format('H');
            $hora_out = (int)$date_2->format('H');

            if ($hora_in >= $hora_inicio && $hora_out <= $hora_fin && $hora_out > $hora_inicio) { 
                $horasSelect[$date_1->format('Y-m-d')][] = ''.$date_1->format('H:i').' a '.$date_2->format('H:i').''; 
            }

        }

        $day_delivered.= '</select>';
        $js_json_delivered = '<script type="text/javascript">
                                var js_json_delivered = '.json_encode($horasSelect).'
                                var form_to_add = "";
                            </script>';

        return array('js_json_delivered' => $js_json_delivered, 'day_delivered' =>$day_delivered);
    }


	public static function update_date_delivered(){
		if ( Tools::getValue( 'hour_delivery' )  == 2 ){
			$context = Context::getContext();
			$context->cart->time_windows = "Entrega Inmediata";
			$sql ="UPDATE "._DB_PREFIX_."cart SET  time_windows = '".$context->cart->time_windows."'
				WHERE id_cart = ".$context->cart->id;
			Db::getInstance()->Execute($sql);
		}
		else if  (Tools::getValue('date_delivered') && Tools::getValue('hour_delivered_h')) {
			$context = Context::getContext();
			$context->cart->date_delivery = Tools::getValue('date_delivered');
			$context->cart->time_windows = Tools::getValue('hour_delivered_h');
			$context->cart->time_delivery = substr(Tools::getValue('hour_delivered_h'),0,5);
			$sql ="UPDATE "._DB_PREFIX_."cart SET date_delivery = '".$context->cart->date_delivery."',
				time_windows = '".$context->cart->time_windows."', time_delivery = '".$context->cart->time_delivery."'
				WHERE id_cart = ".$context->cart->id;
				Db::getInstance()->Execute($sql);                        
		}

        }

}

