<?php

require(dirname(__FILE__).'/config/config.inc.php');
$id_address=$_REQUEST['id_address'];
$id_country=$_REQUEST['id_country'];
$id_state=$_REQUEST['id_state'];
$id_customer=$_REQUEST['id_customer'];
$alias=$_REQUEST['alias'];
$address1=$_REQUEST['address1'];
$address2=$_REQUEST['address2'];
$city=$_REQUEST['city'];
$city_id=$_REQUEST['city_id'];
$phone=$_REQUEST['phone'];
$phone_mobile=$_REQUEST['phone_mobile'];
$active=$_REQUEST['active'];
$deleted=0;
$date=date('Y-m-d H:i:s');
if ($id_address){
    if ($id_state){
    $arreglo['id_state'] = (int)$id_state;
    }
    if ($alias){
    $arreglo['alias'] = pSQL($alias);
    }
    if ($address1){
    $arreglo['address1'] = pSQL($address1);
    }
    if ($address2){
    $arreglo['address2'] = pSQL($address2);
    }
    if ($city){
    $arreglo['city'] = pSQL($city);
    Db::getInstance()->update('address_city', array(
        'id_city'=>(int)$city_id
    ), 'id_address = '.$id_address);
    }
    if ($phone){
    $arreglo['phone'] = pSQL($phone);
    }
    if ($phone_mobile){
    $arreglo['phone_mobile'] = pSQL($phone_mobile);
    }
    if ($date){
    $arreglo['date_upd'] = $date;
    }
    Db::getInstance()->update('address', $arreglo, 'id_address = '.$id_address);
}else{
	/*$sql_address="INSERT INTO "._DB_PREFIX_."address ('id_country', 'id_state', 'id_customer', 'alias', 'address1', 'address2', 'city', 'phone', 'phone_mobile', 'active', 'deleted') VALUES ('$id_country', '$id_state', '$id_customer', '$alias', '$address1', '$address2', '$city', '$phone', '$phone_mobile', '$active', '$deleted')";
	Db::getInstance()->execute($sql_address);*/
	Db::getInstance()->insert('address', array(
		'id_country'=>(int)$id_country,
		'id_state'=>(int)$id_state,
		'id_customer'=>(int)$id_customer,
		'alias'=>pSQL($alias),
		'lastname'=>pSQL(''),
		'firstname'=>pSQL(''),
		'address1'=>pSQL($address1),
		'address2'=>pSQL($address2),
		'city'=>pSQL($city),
		'phone'=>pSQL($phone),
		'phone_mobile'=>pSQL($phone_mobile),
		'date_add'=>$date,
		'date_upd'=>$date,
		'active'=>(int)$active,
		'deleted'=>(int)$deleted
	));
	$Id_address=Db::getInstance()->Insert_ID(); 

	Db::getInstance()->insert('address_city', array(
		'id_address'=>(int)$Id_address,
		'id_city'=>(int)$city_id
	));
    echo $Id_address;
}