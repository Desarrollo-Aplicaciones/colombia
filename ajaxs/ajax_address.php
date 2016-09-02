<?php

include(dirname(__FILE__) . '/../config/config.inc.php');
include(dirname(__FILE__) . '/../init.php');

class ajax_address extends FrontController {

    public function get_colonias($id_ciudad) {
        $str_colonias = '<option value="">- Colonia-</option>';
        $colonia = City::getColoniaByIdCity($id_ciudad);
      

        if ( count($colonia) > 0 &&  !empty ($colonia)) {
            foreach ($colonia as $row) {

                if (isset($_POST['selected']) && ((int) $_POST['selected']) > 0 && ((int) $_POST['selected']) === ((int) $row['id_codigo_postal'])) {
                    $str_colonias .= '<option value="' . $row['id_codigo_postal'] . '" selected  >' . $row['nombrecolonia'] . '</option>';
                } else {
                    $str_colonias .= '<option value="' . $row['id_codigo_postal'] . '">' . $row['nombrecolonia'] . '</option>';
                }
            }
            $array_result = array('results' => $str_colonias);
            return json_encode($array_result);
        }

        return '0';
    }

    public function get_codigo_postal($postcode) {

            if ($result = City::getIdCodPosIdCityIdStateByPostcode($postcode) ) {

			$valores = $result[0];
			if ( $valores['id_codigo_postal'] != '' && $valores['id_city'] != '' && $valores['id_state'] != '') {
				return json_encode( array('id_colonia'=>$valores['id_codigo_postal'],'id_city'=>$valores['id_city'],'id_state'=>$valores['id_state'])); 
                             
			} 
			
		} 
                return  '0';

    }


    
}

if( Tools::getValue('id_city_by_id_address') && Tools::getValue('id_address') && Tools::getValue('ajax') ){

    exit (json_encode(City::getIdCityByIdAddress((int)Tools::getValue('id_address'),false)));
}

if (isset($_POST) && !empty($_POST) && isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['value']) && !empty($_POST['value'])) {

    $ajax = new ajax_address();
    $address_obj= new Address();
    $customer_obj = new Customer();

    switch ($_POST['action']) {
        case 'country':
             echo $address_obj->get_countries();
            break;
        case 'state':
             echo $address_obj->get_states($_POST['value']);
            break;
        case 'city':
            echo $address_obj->get_cities($_POST['value']);
            break;
        case 'address':
             echo $address_obj->get_list_address($_POST['value']);
            break;
        case 'ps_city':
            echo $address_obj->get_costo_envio($_POST['value']);
            break;

        case 'id_customer':
            echo $customer_obj->get_id_custumer($_POST['value']);
            break;
        case 'colonia':
            echo $ajax->get_colonias($_POST['value']);
            break;
                case 'postalcode':
            echo $ajax->get_codigo_postal($_POST['value']);
            break;

        default :
            echo '0';
            break;
    }
} else {
    echo '0';
}
