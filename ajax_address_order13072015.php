<?php

require(dirname(__FILE__).'/config/config.inc.php');

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

//echo $result;
