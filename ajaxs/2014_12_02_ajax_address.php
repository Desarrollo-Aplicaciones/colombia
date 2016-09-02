<?php

include(dirname(__FILE__) . '/../config/config.inc.php');
include(dirname(__FILE__) . '/../init.php');

class ajax_states extends FrontController {

    public function get_countryes() {

        $query = "select country.id_country, country.`name` FROM
ps_country_active active INNER JOIN ps_country_lang country
ON( active.id_country = country.id_country);";

        if ($results = Db::getInstance()->ExecuteS($query)) {

            $str_countryes = '';
            if (count($results) > 0) {
                $str_countryes.='<option value="">Seleccionar</option>';
                foreach ($results as $value) {
                    $str_countryes .= '<option value="' . $value['id_country'] . '">' . $value['name'] . '</option>';
                }
                return json_encode(array('results' => $str_countryes));
            }
        }

        return '!';
    }

    public function get_states($id_country) {

        $query = "select  state.id_state,state.`name` FROM
ps_country country
INNER JOIN ps_state state ON (country.id_country= state.id_country)
WHERE country.id_country=" . (int) $id_country . ";";

        if ($results = Db::getInstance()->ExecuteS($query)) {

            $str_states = '';
            if (count($results) > 0) {
                $str_states.='<option value="">Seleccionar</option>';
                foreach ($results as $value) {
                    $str_states .= '<option value="' . $value['id_state'] . '">' . $value['name'] . '</option>';
                }
                return json_encode(array('results' => $str_states));
            }
        }
        return '!';
    }

    public function get_cityes($id_state) {
        $query = "select city.id_city, city.city_name FROM
ps_state state INNER JOIN ps_cities_col city ON(state.id_state=city.id_state)
WHERE state.id_state=" . (int) $id_state . ";";

        if ($results = Db::getInstance()->ExecuteS($query)) {
            $str_cityes = '';
            if (count($results) > 0) {
                $str_cityes.='<option value="">Seleccionar</option>';
                foreach ($results as $value) {
                    $str_cityes .= '<option value="' . $value['id_city'] . '">' . $value['city_name'] . '</option>';
                }
                return json_encode(array('results' => $str_cityes));
            }
        }
        return '!';
    }

    public function get_list_address($billing_account_id) {

        $id_customer = $this->get_id_custumer_account($billing_account_id);
        $str_cityes = '<option value="">Seleccionar</option>';

        if ($id_customer!=='') {
            $query =    "select address.id_address,address.alias,address.postcode, address.address1,cities.city_name,state.`name` as state_name,country.`name` as country_name FROM
                        ps_customer customer 
                        INNER JOIN ps_address address ON(customer.id_customer=address.id_customer)
                        INNER JOIN ps_address_city city ON(city.id_address=address.id_address)
                        INNER JOIN ps_cities_col cities ON (cities.id_city=city.id_city)
                        INNER JOIN ps_state state ON( state.id_state=cities.id_state)
                        INNER JOIN ps_country_lang country ON( country.id_country=state.id_country)
                        WHERE customer.id_customer =(
                        select customer.id_customer FROM
                        ps_sync_tracker sync INNER JOIN ps_customer customer
                        ON(sync.key2=customer.id_customer && sync.value2=customer.email)
                        WHERE sync.key1='" . $billing_account_id . "' GROUP BY sync.key1 );";

            if ($results = Db::getInstance()->ExecuteS($query)) {

                if (count($results) > 0) {
                   
                    foreach ($results as $value) {
                        $str_cityes .= '<option value="' . $value['id_address'] . '">' . $value['city_name'] . ' _ ' . $value['alias'] . ' _ ' . $value['postcode'] . ' _ ' . $value['address1'] . ' _ ' . $value['city_name'] . ' ' . $value['state_name'] . ' ' . $value['country_name'] . '</option>';
                    }
                }
            }
            return json_encode(array('results' => $str_cityes,'id_customer'=> $id_customer));
        }

        return '!-';
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

    public function get_id_custumer($id_address) {
        $query = "select customer.id_customer FROM
ps_address adre INNER JOIN ps_customer customer ON (adre.id_customer=customer.id_customer)
WHERE adre.id_address=" . (int) $id_address . " LIMIT 1;";

        if ($results = Db::getInstance()->ExecuteS($query)) {
            $sid_cusomer = '';
            if (count($results) > 0) {
                   $sid_cusomer = $results[0]['id_customer'];

                return json_encode(array('results' => $sid_cusomer));
            }
        }
        return '!';
    }

}

if (isset($_POST) && !empty($_POST) && isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['value']) && !empty($_POST['value'])) {

    $ajax = new ajax_states();

    switch ($_POST['action']) {
        case 'country':
            echo $ajax->get_countryes();
            break;
        case 'sate':
            echo $ajax->get_states($_POST['value']);
            break;
        case 'city':
            echo $ajax->get_cityes($_POST['value']);
            break;
        case 'address':
            echo $ajax->get_list_address($_POST['value']);
            break;

        case 'id_customer':
            echo $ajax->get_id_custumer($_POST['value']);
            break;

        default :
            echo '!';
            break;
    }
} else {
    echo '!';
}
