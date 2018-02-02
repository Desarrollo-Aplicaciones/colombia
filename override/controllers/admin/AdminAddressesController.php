<?php


class AdminAddressesController extends AdminAddressesControllerCore
{
	
	public function processSave()
	{
		//$this->errors[] = print_r($_POST,true);
		// Transform e-mail in id_customer for parent processing
		if (Validate::isEmail(Tools::getValue('email')))
		{
			$customer = new Customer();
			$customer->getByEmail(Tools::getValue('email'), null, false);
			if (Validate::isLoadedObject($customer))
				$_POST['id_customer'] = $customer->id;
			else
				$this->errors[] = Tools::displayError('This email address is not registered.');
		}
		else if ($id_customer = Tools::getValue('id_customer'))
		{
			$customer = new Customer((int)$id_customer);
			if (Validate::isLoadedObject($customer))
				$_POST['id_customer'] = $customer->id;
			else
				$this->errors[] = Tools::displayError('Unknown customer');
		}
		else
			$this->errors[] = Tools::displayError('Unknown customer');
		if (Country::isNeedDniByCountryId(Tools::getValue('id_country')) && !Tools::getValue('dni'))
			//$this->errors[] = Tools::displayError('The identification number is incorrect or has already been used.');
			//comentado para no tener obligatorio el DNI al momento de guardar la dirección

		/* If the selected country does not contain states */
		$id_state = (int)Tools::getValue('id_state');
		$bk_estado = Tools::getValue('id_state');

		$id_country = (int)Tools::getValue('id_country');
		$country = new Country((int)$id_country);

		if ( $id_state == '' || $id_state == null || !$id_state || !is_int($id_state)  ) {
			$id_state = $_POST['id_state']; 
		}

		// la clase Country borra el valor de id_state, jumm??
		if ($country && !(int)$country->contains_states && $id_state)
			$this->errors[] = Tools::displayError('You\'ve selected a state for a country that does not contain states.');

		/* If the selected country contains states, then a state have to be selected */
		if ((int)$country->contains_states && !$id_state) {
			$this->errors[] = 'Datos, AdminAddressesController Prod: -'.$country->contains_states.'- -- state:-'.$id_state.'- | -'.$_POST['id_state'];
			$this->errors[] = Tools::displayError('An address located in a country containing states must have a state selected.1');			
		}

		$postcode = Tools::getValue('postcode');		
		/* Check zip code format */
		/*if ($country->zip_code_format && !$country->checkZipCode($postcode))
			$this->errors[] = Tools::displayError('Your Postal / Zip Code is incorrect.').'<br />'.Tools::displayError('It must be entered as follows:').' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $country->zip_code_format)));
		elseif(empty($postcode) && $country->need_zip_code)
			$this->errors[] = Tools::displayError('A Zip / Postal code is required.');
		elseif ($postcode && !Validate::isPostCode($postcode))
			$this->errors[] = Tools::displayError('The Zip / Postal code is invalid.');*/

		if (Configuration::get('PS_ONE_PHONE_AT_LEAST') && !Tools::getValue('phone') && !Tools::getValue('phone_mobile'))		
			$this->errors[] = Tools::displayError('You must register at least one phone number.');

		/* If this address come from order's edition and is the same as the other one (invoice or delivery one)
		** we delete its id_address to force the creation of a new one */
		if ((int)Tools::getValue('id_order'))
		{
			$this->_redirect = false;
			if (isset($_POST['address_type']))
				$_POST['id_address'] = '';
		}

		// Check the requires fields which are settings in the BO
		$address = new Address();
		$this->errors = array_merge($this->errors, $address->validateFieldsRequiredDatabase());

		if (empty($this->errors))
			return parent::processSave();
		else
			// if we have errors, we stay on the form instead of going back to the list
			$this->display = 'edit';

		/* Reassignation of the order's new (invoice or delivery) address */
		$address_type = ((int)Tools::getValue('address_type') == 2 ? 'invoice' : ((int)Tools::getValue('address_type') == 1 ? 'delivery' : ''));
		if ($this->action == 'save' && ($id_order = (int)Tools::getValue('id_order')) && !count($this->errors) && !empty($address_type))
		{
			if (!Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'orders SET `id_address_'.$address_type.'` = '.Db::getInstance()->Insert_ID().' WHERE `id_order` = '.$id_order))
				$this->errors[] = Tools::displayError('An error occurred while linking this address to its order.');
			else
				Tools::redirectAdmin(Tools::getValue('back').'&conf=4');
		}
	}

}
