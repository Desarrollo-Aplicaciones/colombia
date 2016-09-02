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


    protected static $_idZones = array();
    protected static $_idCountries = array();

    	/**
	 * @see ObjectModel::add()
	 */
	public function add($autodate = true, $null_values = false)
	{

        $fp=fopen("/tmp/archivo_dir.txt","a+"); fwrite($fp,print_r($_POST,true)."\r\n\r\n\r\n"); fclose($fp);
        
		$dir_change = explode( "|", $this->city );
		
		$nom_city = $this->city;
          
		if ( is_numeric($dir_change[0]) && $dir_change[0] != '' && $dir_change[0] != null) {

			$arr_nom_city = City::getCityByIdCity($dir_change[0]);
			$nom_city = $arr_nom_city[0]['city_name'];
            $val_id_city = $dir_change[0];

		} elseif ( is_numeric($_POST['city_id'])) {

            $arr_nom_city = City::getCityByIdCity($_POST['city_id']);
            $nom_city = $arr_nom_city[0]['city_name'];
            $val_id_city = $_POST['city_id'];
        }

		$this->city = $nom_city;

        $fp=fopen("/tmp/archivo_dir.txt","a+"); fwrite($fp,print_r($this,true)."\r\n\r\n\r\n"); fclose($fp);


		if ( ($this->postcode == '' ||  $this->postcode == NULL) && ( isset($dir_change[1]) && $dir_change[1] != '' && $dir_change[1] != NULL ) ) {
			$this->postcode = $dir_change[1];
		}

        

		if (!parent::add($autodate, $null_values))
			return false;
                
        
        
                		$Id_address=Db::getInstance()->Insert_ID(); 

				if($Id_address == 0) {
					$Id_address = $this->id;
					
					Db::getInstance()->update('address_city', array( 'id_city'=>(int)$val_id_city ), 'id_address = '.(int)$Id_address );
                    $fp=fopen("/tmp/archivo_dir.txt","a+"); fwrite($fp,"paso update: ".'id_city: '.$val_id_city.' - id_address = '.$Id_address); fclose($fp);

				} else {

					Db::getInstance()->insert('address_city', array( 'id_address'=>(int)$Id_address, 'id_city'=>(int)$val_id_city ));
                    $fp=fopen("/tmp/archivo_dir.txt","a+"); fwrite($fp,"paso insert: ".'id_city: '.$val_id_city.' - id_address = '.$Id_address); fclose($fp);
				}

			if (Validate::isUnsignedId($this->id_customer))
			Customer::resetAddressCache($this->id_customer);

		return true;
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
                  ps_country_active active INNER JOIN ps_country_lang country
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
    

        return '0';
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
        
}

