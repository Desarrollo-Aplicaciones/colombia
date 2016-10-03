<?php
class AdminCustomersController extends AdminCustomersControllerCore
{
public function __construct()
	{
		$this->required_database = true;
		$this->required_fields = array('newsletter','optin');
		$this->table = 'customer';
		$this->className = 'Customer';
		$this->lang = false;
		$this->deleted = true;
		$this->explicitSelect = true;

		$this->allow_export = true;
		$this->addRowAction('edit');
		$this->addRowAction('view');
		$this->addRowAction('delete');
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Would you like to delete the selected items?')));

		$this->context = Context::getContext();

		$this->default_form_language = $this->context->language->id;

		$genders = array();
		$genders_icon = array();
		$genders_icon[] = array('src' => '../genders/Unknown.jpg', 'alt' => '');		
		foreach (Gender::getGenders() as $gender)
		{
			$gender_file = 'genders/'.$gender->id.'.jpg';
			if (file_exists(_PS_IMG_DIR_.$gender_file))
				$genders_icon[$gender->id] = array('src' => '../'.$gender_file, 'alt' => $gender->name);
			else
				$genders_icon[$gender->id] = array('src' => '../genders/Unknown.jpg', 'alt' => $gender->name);
			$genders[$gender->id] = $gender->name;
		}

		$this->_select = '
		a.date_add,
		IF (YEAR(`birthday`) = 0, "-", (YEAR(CURRENT_DATE)-YEAR(`birthday`)) - (RIGHT(CURRENT_DATE, 5) < RIGHT(birthday, 5))) AS `age`, (
			SELECT c.date_add FROM '._DB_PREFIX_.'guest g
			LEFT JOIN '._DB_PREFIX_.'connections c ON c.id_guest = g.id_guest
			WHERE g.id_customer = a.id_customer
			ORDER BY c.date_add DESC
			LIMIT 1
		) as connect';
		$this->fields_list = array(
			'id_customer' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 20
			),
			'identification' => array(
				'title' => $this->l('Identificación'),
				'width' => '32'
			),
			'lastname' => array(
				'title' => $this->l('Last name'),
				'width' => 'auto'
			),
			'firstname' => array(
				'title' => $this->l('First Name'),
				'width' => 'auto'
			),
			'email' => array(
				'title' => $this->l('Email address'),
				'width' => 140,
			),
			'date_add' => array(
				'title' => $this->l('Registration'),
				'width' => 150,
				'type' => 'date',
				'align' => 'right'
			),
		);

		$this->shopLinkType = 'shop';
		$this->shopShareDatas = Shop::SHARE_CUSTOMER;

		AdminController::__construct();

		// Check if we can add a customer
		if (Shop::isFeatureActive() && (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP))
			$this->can_add_customer = false;
	}


	public function renderForm()
	{

		if (!($obj = $this->loadObject(true)))
			return;

		$genders = Gender::getGenders();
		$list_genders = array();
		foreach ($genders as $key => $gender)
		{
			$list_genders[$key]['id'] = 'gender_'.$gender->id;
			$list_genders[$key]['value'] = $gender->id;
			$list_genders[$key]['label'] = $gender->name;
		}

		$years = Tools::dateYears();
		$months = Tools::dateMonths();
		$days = Tools::dateDays();

		$groups = Group::getGroups($this->default_form_language, true);
		$typeDocuments = Utilities::data_type_documents();

		$this->context->smarty->assign('validateEmailCustomer', 'validateEmailCustomer');

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Customer'),
				'image' => '../img/admin/tab-customers.gif'
			),
			'input' => array(
				array(
					'type' => 'radio',
					'label' => $this->l('Title:'),
					'name' => 'id_gender',
					'required' => false,
					'class' => 't',
					'values' => $list_genders
				),
				array(
					'type' => 'select',
					'label' => $this->l('Tipo de identificación:'),
					'name' => 'id_type',
					'size' => 1,
					'required' => true,
					'options' => array(
						'query' => $typeDocuments,
						'id' => 'id_document',
						'name' => 'document'
					)
					
				),
				array(
					'type' => 'text',
					'label' => $this->l('Identificación:'),
					'name' => 'identification',
					'size' => 20,
					'required' => true,
					'hint' => $this->l('Forbidden characters:').' a-zA-Z!<>,;?=+()@#"�{}_$%:'
				),
				array(
					'type' => 'text',
					'label' => $this->l('First name:'),
					'name' => 'firstname',
					'size' => 33,
					'required' => true,
					'hint' => $this->l('Forbidden characters:').' 0-9!<>,;?=+()@#"�{}_$%:'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Last name:'),
					'name' => 'lastname',
					'size' => 33,
					'required' => true,
					'hint' => $this->l('Invalid characters:').' 0-9!<>,;?=+()@#"�{}_$%:'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Email address:'),
					'name' => 'email',
					'size' => 33,
					'required' => true
				),
				array(
					'type' => 'password',
					'label' => $this->l('Password:'),
					'name' => 'passwd',
					'size' => 33,
					'required' => ($obj->id ? false : true),
					'desc' => ($obj->id ? $this->l('Leave  this field blank if there\'s no change') : $this->l('Minimum of five characters (only letters and numbers).').' -_')
				),
				array(
					'type' => 'birthday',
					'label' => $this->l('Birthday:'),
					'name' => 'birthday',
					'options' => array(
						'days' => $days,
						'months' => $months,
						'years' => $years
					)
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Status:'),
					'name' => 'active',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'desc' => $this->l('Enable or disable customer login')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Newsletter:'),
					'name' => 'newsletter',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'newsletter_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'newsletter_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'desc' => $this->l('Customers will receive your newsletter via email.')
				),
				array(
					'type' => 'radio',
					'label' => $this->l('Opt in:'),
					'name' => 'optin',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'optin_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'optin_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'desc' => $this->l('Customer will receive your ads via email.')
				),
			)
		);

		// if we add a customer via fancybox (ajax), it's a customer and he doesn't need to be added to the visitor and guest groups
		if (Tools::isSubmit('addcustomer') && Tools::isSubmit('submitFormAjax'))
		{
			$visitor_group = Configuration::get('PS_UNIDENTIFIED_GROUP');
			$guest_group = Configuration::get('PS_GUEST_GROUP');
			foreach ($groups as $key => $g)
				if (in_array($g['id_group'], array($visitor_group, $guest_group)))
					unset($groups[$key]);
		}

		$this->fields_form['input'] = array_merge($this->fields_form['input'],
				array(
					array(
								'type' => 'group',
								'label' => $this->l('Group access:'),
								'name' => 'groupBox',
								'values' => $groups,
								'required' => true,
								'desc' => $this->l('Select all the groups that you would like to apply to this customer.')
							),
					array(
						'type' => 'select',
						'label' => $this->l('Default customer group:'),
						'name' => 'id_default_group',
						'options' => array(
							'query' => $groups,
							'id' => 'id_group',
							'name' => 'name'
						),
						'hint' => $this->l('The group will be as applied by default.'),
						'desc' => $this->l('Apply the discount\'s price of this group.')
						)
					)
				);

		// if customer is a guest customer, password hasn't to be there
		if ($obj->id && ($obj->is_guest && $obj->id_default_group == Configuration::get('PS_GUEST_GROUP')))
		{
			foreach ($this->fields_form['input'] as $k => $field)
				if ($field['type'] == 'password')
					array_splice($this->fields_form['input'], $k, 1);
		}

		if (Configuration::get('PS_B2B_ENABLE'))
		{
			$risks = Risk::getRisks();

			$list_risks = array();
			foreach ($risks as $key => $risk)
			{
				$list_risks[$key]['id_risk'] = (int)$risk->id;
				$list_risks[$key]['name'] = $risk->name;
			}

			$this->fields_form['input'][] = array(
				'type' => 'text',
				'label' => $this->l('Company:'),
				'name' => 'company',
				'size' => 33
			);
			$this->fields_form['input'][] = array(
				'type' => 'text',
				'label' => $this->l('SIRET:'),
				'name' => 'siret',
				'size' => 14
			);
			$this->fields_form['input'][] = array(
				'type' => 'text',
				'label' => $this->l('APE:'),
				'name' => 'ape',
				'size' => 5
			);
			$this->fields_form['input'][] = array(
				'type' => 'text',
				'label' => $this->l('Website:'),
				'name' => 'website',
				'size' => 33
			);
			$this->fields_form['input'][] = array(
				'type' => 'text',
				'label' => $this->l('Outstanding allowed:'),
				'name' => 'outstanding_allow_amount',
				'size' => 10,
				'hint' => $this->l('Valid characters:').' 0-9',
				'suffix' => '¤'
			);
			$this->fields_form['input'][] = array(
				'type' => 'text',
				'label' => $this->l('Maximum number of payment days:'),
				'name' => 'max_payment_days',
				'size' => 10,
				'hint' => $this->l('Valid characters:').' 0-9'
			);
			$this->fields_form['input'][] = array(
				'type' => 'select',
				'label' => $this->l('Risk:'),
				'name' => 'id_risk',
				'required' => false,
				'class' => 't',
				'options' => array(
					'query' => $list_risks,
					'id' => 'id_risk',
					'name' => 'name'
				),
			);
		}

		$this->fields_form['submit'] = array(
			'title' => $this->l('Save   '),
			'class' => 'button'
		);

		$birthday = explode('-', $this->getFieldValue($obj, 'birthday'));

		$this->fields_value = array(
			'years' => $this->getFieldValue($obj, 'birthday') ? $birthday[0] : 0,
			'months' => $this->getFieldValue($obj, 'birthday') ? $birthday[1] : 0,
			'days' => $this->getFieldValue($obj, 'birthday') ? $birthday[2] : 0,
		);

		// Added values of object Group
		if (!Validate::isUnsignedId($obj->id))
			$customer_groups = array();
		else
			$customer_groups = $obj->getGroups();
		$customer_groups_ids = array();
		if (is_array($customer_groups))
			foreach ($customer_groups as $customer_group)
				$customer_groups_ids[] = $customer_group;

		// if empty $carrier_groups_ids : object creation : we set the default groups
		if (empty($customer_groups_ids))
		{
			$preselected = array(Configuration::get('PS_UNIDENTIFIED_GROUP'), Configuration::get('PS_GUEST_GROUP'), Configuration::get('PS_CUSTOMER_GROUP'));
			$customer_groups_ids = array_merge($customer_groups_ids, $preselected);
		}

		foreach ($groups as $group)
			$this->fields_value['groupBox_'.$group['id_group']] =
				Tools::getValue('groupBox_'.$group['id_group'], in_array($group['id_group'], $customer_groups_ids));

		return AdminController::renderForm();
	}

	public function initContent()
	{
		if ($this->action == 'select_delete')
			$this->context->smarty->assign(array(
				'delete_form' => true,
				'url_delete' => htmlentities($_SERVER['REQUEST_URI']),
				'boxes' => $this->boxes,
			));

		if (!$this->can_add_customer && !$this->display)
			$this->informations[] = $this->l('You have to select a shop if you want to create a customer.');
		
		$this->addJS(_THEME_JS_DIR_.'admin-customer_controller.js');

		AdminController::initContent();
	}

	public function processSave()
	{
		// $var15 = in_array( 4, Tools::getValue('groupBox'));
		// print_r((bool)$var15);
		// die();

		if (in_array( 4, Tools::getValue('groupBox')) && Tools::getValue('id_type') != 4 ){
			$this->errors[] = Tools::displayError('El tipo de documento no puede ser asociado a un cliente corporativo.');
		}
		// Check that default group is selected
		if (!is_array(Tools::getValue('groupBox')) || !in_array(Tools::getValue('id_default_group'), Tools::getValue('groupBox')))
			$this->errors[] = Tools::displayError('A default customer group must be selected in group box.');

		// Check the requires fields which are settings in the BO
		$customer = new Customer();
		$this->errors = array_merge($this->errors, $customer->validateFieldsRequiredDatabase());

		return parent::processSave();
	}

}
?>