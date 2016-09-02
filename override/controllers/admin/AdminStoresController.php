<?php

class AdminStoresController extends AdminStoresControllerCore
{
	
	public function postProcess()
	{
		if (isset($_POST['submitAdd'.$this->table]))
		{
			/* Cleaning fields */
			foreach ($_POST as $kp => $vp)
				if (!in_array($kp, array('checkBoxShopGroupAsso_store', 'checkBoxShopAsso_store')))
					$_POST[$kp] = trim($vp);

			/* Rewrite latitude and longitude to 8 digits */
			$_POST['latitude'] = number_format($_POST['latitude'], 8);
			$_POST['longitude'] = number_format($_POST['longitude'], 8);

			/* If the selected country does not contain states */
			$id_state = (int)Tools::getValue('id_state');
			$id_country = (int)Tools::getValue('id_country');
			$country = new Country((int)$id_country);

			if ($id_country && $country && !(int)$country->contains_states && $id_state)
				$this->errors[] = Tools::displayError('You\'ve selected a state for a country that does not contain states.');

			/* If the selected country contains states, then a state have to be selected */
			$id_state = (int)Tools::getValue('id_state');
			if ((int)$country->contains_states && !$id_state) {
				$this->errors[] = Tools::displayError('An address located in a country containing states must have a state selected.2');
				$this->errors[] = Tools::displayError('Datos: -'.$country->contains_states.'- -- state:-'.$id_state.'- ');
			}

			$latitude = (float)Tools::getValue('latitude');
			$longitude = (float)Tools::getValue('longitude');

			if (empty($latitude) || empty($longitude))
			   $this->errors[] = Tools::displayError('Latitude and longitude are required.');
			
			$postcode = Tools::getValue('postcode');		
			/* Check zip code format */
			if ($country->zip_code_format && !$country->checkZipCode($postcode))
				$this->errors[] = Tools::displayError('Your Postal / Zip Code is incorrect.').'<br />'.Tools::displayError('It must be entered as follows:').' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $country->zip_code_format)));
			elseif(empty($postcode) && $country->need_zip_code)
				$this->errors[] = Tools::displayError('A Zip / Postal code is required.');
			elseif ($postcode && !Validate::isPostCode($postcode))
				$this->errors[] = Tools::displayError('The Zip / Postal code is invalid.');

			/* Store hours */
			$_POST['hours'] = array();
			for ($i = 1; $i < 8; $i++)
				$_POST['hours'][] .= Tools::getValue('hours_'.(int)$i);
			$_POST['hours'] = serialize($_POST['hours']);
		}

		if (!count($this->errors))
			parent::postProcess();
		else
			$this->display = 'add';
	}

}