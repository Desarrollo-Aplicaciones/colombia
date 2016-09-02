<?php
include_once(dirname(__FILE__)."/../config/config.inc.php");

if  ( $_REQUEST['nevera'] == 'activar') {

	$update = "UPDATE `ps_configuration` SET `id_configuration`='877', `id_shop_group`=NULL, `id_shop`=NULL, `name`='PS_ID_NEVERA_CIUD_PRIN_1_3', `value`='34107', `date_add`='2016-06-29 10:43:59', `date_upd`='2016-06-29 10:43:59' WHERE (`id_configuration`='877')";
	if ($results = Db::getInstance()->Execute( $update)) {
		echo " <pre> nevera query 1: ";
		print_r($results[0]);
	}

	$update = "UPDATE `ps_configuration` SET `id_configuration`='878', `id_shop_group`=NULL, `id_shop`=NULL, `name`='PS_ID_NEVERA_CIUD_PRIN_4_12', `value`='36398', `date_add`='2016-06-29 10:43:59', `date_upd`='2016-06-29 10:43:59' WHERE (`id_configuration`='878')";
	if ($results = Db::getInstance()->Execute( $update)) {
		echo " <pre> nevera query 2: ";
		print_r($results[0]);
	}

	$update = "UPDATE `ps_configuration` SET `id_configuration`='879', `id_shop_group`=NULL, `id_shop`=NULL, `name`='PS_ID_NEVERA_CIUD_ALED_1_9', `value`='36399', `date_add`='2016-06-29 10:43:59', `date_upd`='2016-06-29 10:43:59' WHERE (`id_configuration`='879')";
	if ($results = Db::getInstance()->Execute( $update)) {
		echo " <pre> nevera query 3: ";
		print_r($results[0]);
	}
}

if  ( $_REQUEST['nevera'] == 'inactivar') {


	$update = "UPDATE `ps_configuration` SET `id_configuration`='877', `id_shop_group`=NULL, `id_shop`=NULL, `name`='PS_ID_NEVERA_CIUD_PRIN_1_3', `value`=NULL, `date_add`='2016-06-29 10:43:59', `date_upd`='2016-06-29 10:43:59' WHERE (`id_configuration`='877')";
	if ($results = Db::getInstance()->Execute( $update)) {
		echo " <pre> null query 1: ";
		print_r($results[0]);
	}

	$update = "UPDATE `ps_configuration` SET `id_configuration`='878', `id_shop_group`=NULL, `id_shop`=NULL, `name`='PS_ID_NEVERA_CIUD_PRIN_4_12', `value`=NULL, `date_add`='2016-06-29 10:43:59', `date_upd`='2016-06-29 10:43:59' WHERE (`id_configuration`='878')";
	if ($results = Db::getInstance()->Execute( $update)) {
		echo " <pre> null query 2: ";
		print_r($results[0]);
	}

	$update = "UPDATE `ps_configuration` SET `id_configuration`='879', `id_shop_group`=NULL, `id_shop`=NULL, `name`='PS_ID_NEVERA_CIUD_ALED_1_9', `value`=NULL, `date_add`='2016-06-29 10:43:59', `date_upd`='2016-06-29 10:43:59' WHERE (`id_configuration`='879')";
	if ($results = Db::getInstance()->Execute( $update)) {
		echo " <pre> null query 3: ";
		print_r($results[0]);
	}
}


?>