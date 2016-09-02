<?php
//require_once dirname(__FILE__).'/../../../config/config.inc.php';

class Innovative_Link extends ObjectModel
{
        /** @var string Object SQL table */
        protected $table = 'innovativemenu_personalized_link';
        
        /** @var string Object SQL identifier */
	protected $identifier = 'id_link';
        
        /** @var string link */
        public $link;
        
        /** @var integer id in SQL table */
        public $id_link;
        
        /** @var string name */
        public $name;
        
        public $id_menu_context;
        
        protected $fieldsRequiredLang = array('name');
        
        public function getFields() 
	{ 
                $fields = array();
		parent::validateFields();
		$fields['link'] = pSQL($this->link);
                $fields['id_menu_context'] = (int)$this->id_menu_context;
		return $fields;	 
	}
        
        public function save($autodate = true, $nullvalue = true)
        {
                if (empty($this->name[Configuration::get('PS_LANG_DEFAULT')]))
                {
                        $languages = Language::getLanguages();
                        foreach ($languages AS $language)
                                if (!empty($this->name[$language['id_lang']]))
                                {
                                        $this->name[Configuration::get('PS_LANG_DEFAULT')] = $this->name[$language['id_lang']];
                                        break;
                                }
                }
                if (empty($this->name[Configuration::get('PS_LANG_DEFAULT')]))
                        $this->name[Configuration::get('PS_LANG_DEFAULT')] = 'No name';
                return parent::save();
        }
        
        
        public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();

		$fieldsArray = array('name');
		$fields = array();
		$languages = Language::getLanguages(false);
		$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		foreach ($languages as $language)
		{
			$fields[$language['id_lang']]['id_lang'] = (int)($language['id_lang']);
			$fields[$language['id_lang']][$this->identifier] = (int)($this->id);
			foreach ($fieldsArray as $field)
			{
				if (!Validate::isTableOrIdentifier($field))
					die(Tools::displayError());
				if (isset($this->{$field}[$language['id_lang']]) AND !empty($this->{$field}[$language['id_lang']]))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']]);
				elseif (in_array($field, $this->fieldsRequiredLang))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage]);
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
        }
        
        
        public static function get($id_lang, $id_menu_context = 0)
        {
                return Db::getInstance()->ExecuteS('
                                                SELECT * FROM `'._DB_PREFIX_.'innovativemenu_personalized_link` l
                                                LEFT JOIN `'._DB_PREFIX_.'innovativemenu_personalized_link_lang` ll
                                                ON l.`id_link` = ll.`id_link`
                                                WHERE ll.`id_lang` = '.(int)$id_lang
                        .(empty($id_menu_context) ? '' : ' AND `id_menu_context`='.(int)$id_menu_context));
        }
}