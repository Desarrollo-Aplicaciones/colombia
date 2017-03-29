<?php

require(dirname(__FILE__).'/config/config.inc.php');

$action = $_POST['action'];

switch ( $action ) {
	case 'insertDataBillingCustomer':

		if ( $_POST['last_name'] == "" || $_POST['type_document'] == 4 ) {
			$_POST['last_name'] = " ";
		}

		if ( $_POST['first_name'] == "" ) {
			$_POST['first_name'] = " ";
		}

		if ( $_POST['phone_mobile'] == "" ) {
			$_POST['phone_mobile'] = " ";
		}

		if ( $_POST['contdata_billing'] ) {
			$customer = new Customer( $_POST['id_customer'] );
			$customer->firstname = $_POST['first_name'];
			$customer->lastname = $_POST['last_name'];
			$customer->id_type = $_POST['type_document'];
			$customer->identification = $_POST['number_document'];
			if( $_POST['type_document'] == 4 ) {
				$customer_group[0] = 4;
				$customer->addGroups($customer_group);
			}
			$customer->update();
			$customer->updateGroupDiscount($customer->identification);
		}

		if ( $_POST['existdir'] == "" ) {
			$address = new Address();
			$address->id_customer = $_POST['id_customer'];
			$address->id_country = $_POST['id_country'];
			$address->id_state = $_POST['id_state'];
			$address->lastname = $_POST['last_name'];
			$address->firstname = $_POST['first_name'];
			$address->address1 = $_POST['address1'];
			$address->address2 = $_POST['address2'];
			$address->city = $_POST['city'];
			$address->phone = $_POST['phone'];
			$address->phone_mobile = $_POST['phone_mobile'];
			$address->dni = $_POST['number_document'];
			$address->alias = "Dirección 1";
			$address->add();
		}

		echo true;

		break;
	
	default:
		echo false;
		break;
}