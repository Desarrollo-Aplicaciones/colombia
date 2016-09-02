<?php
require(dirname(__FILE__).'/config/config.inc.php');

$id_country = $_REQUEST['id_country'];		//4200
$postcode = $_REQUEST['postcode'];		//4200
if ($id_country == "") {
	$id_country = Configuration::get('PS_COUNTRY_DEFAULT');
}

 	$sql_location_postcode='
		SELECT cp.id_codigo_postal, cp.codigo_postal, cc.id_city, s.id_state  FROM ps_cod_postal cp 
		INNER JOIN ps_precio_tr_codpos ptcp ON ( ptcp.codigo_postal = cp.codigo_postal )
		INNER JOIN ps_cities_col cc ON (cc.id_city = cp.id_ciudad)
		INNER JOIN ps_state s ON (s.id_state = cc.id_state)
		WHERE s.id_country = '.$id_country.' AND cp.codigo_postal = '.$postcode.'
		GROUP BY cp.codigo_postal, cc.id_city, s.id_state';

		if ($result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql_location_postcode) ) {
			//print_r($result);
			$valores = $result[0];
			if ( $valores['id_codigo_postal'] != '' && $valores['id_city'] != '' && $valores['id_state'] != '') {
				echo $valores['id_codigo_postal'].";".$valores['id_city'].";".$valores['id_state'];
			} else {
				echo "0";
			}
			
		} else {
			echo "0";
		}


?>