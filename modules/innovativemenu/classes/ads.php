<?php
//require_once dirname(__FILE__).'/../../../config/config.inc.php';

class Innovative_Ads extends ObjectModel{
/* For advertising */ 
        public $id_tab;
        public $id_ads;
        public $content;
        public $title;
        public $width;
        public $active;
        public $position;
                
        public static $all_align = array ('left' => 'left', 'top' => 'top', 'right' => 'right', 'bottom' => 'bottom');
        protected $table = 'innovativemenu_ads';
	protected $identifier = 'id_ads';
        protected $fieldsRequired = array('id_tab');
        
        public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();

		$fieldsArray = array('content', 'title');
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
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']], true);
				elseif (in_array($field, $this->fieldsRequiredLang))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage], true);
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
        }
        
        
        public function getFields() 
	{ 
                $fields = array();
		parent::validateFields();
		$fields['id_tab'] = (int)$this->id_tab;
                $fields['active'] = $this->id ? (bool)$this->active : true;
                if ($this->id_tab)
                {
                        $tab = new innovative_Tab($this->id_tab);
                        $fields['width'] = (int)$tab->ads_width;
                }
                $fields['position'] = (int)$this->position;
		return $fields;	 
	}
        
        
        public function view()
        {
                global $cookie;
                
                $id_lang = $cookie->id_lang ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT');
                if (!($content = $this->content[$id_lang]))
                        $content = InnovativeMenu::cleanHTML($this->content[(int)Configuration::get('PS_LANG_DEFAULT')]);
                
                $tab = new Innovative_Tab($this->id_tab);
                if ($tab->ads_align == 'right')
                        $float = 'right';
                else $float= 'left';
                
                if ($tab->ads_align == 'right' OR $tab->ads_align == 'left')
                        $other = 'margin-bottom:10px;';
                else $other = 'width:'.(int)$tab->ads_width.'%;';
                return '<div style="float:'.$float.'; '.$other.' margin:5px;">'.$content.'</div>';
        }
        
        public function toggleActive()
        {
                $this->active = !$this->active;               
                return $this->save();
        }
        
        
        public function hasNext()
        {
                return (int)DB::getInstance()->getValue('SELECT MIN(`position`) FROM `'._DB_PREFIX_.'innovativemenu_ads`
                                                WHERE `id_tab`='.(int)$this->id_tab.' AND `position` > '.(int)$this->position);
        }
        
        public function hasPrevious()
        {
                return (int)DB::getInstance()->getValue('SELECT MAX(`position`) FROM `'._DB_PREFIX_.'innovativemenu_ads`
                                                WHERE `id_tab`='.(int)$this->id_tab.' AND `position` < '.(int)$this->position);
        }
        

        public function down()
        {
                $tmp = $this->position;
                $this->position = $this->hasPrevious();
                if (!$this->position OR !$this->id_tab)
                        return;

                $ads = self::getByPosition ($this->position, $this->id_tab);
                $ads->position = $tmp;
                return $ads->save() AND $this->save();
                
        }
        
        
        public function up()
        {
                $this->position = $this->hasNext();
                if (!$this->position OR !$this->id_tab)
                        return;
                $ads = self::getByPosition ($this->position, $this->id_tab);
                return $ads->down();
        }
        
         public function save($nullValues = false, $autodate = true)
        {
                if (!(int)$this->id_tab)
                        return;
                if (!(int)$this->position)
                {
                        $this->position = $this->getNextPositionOnTab();
                }
                if (parent::save($nullValues, $autodate))
                {
                        $this->initMenu();
                        return true;
                }
        }
        
        private function getNextPositionOnTab()
        {
                if (!(int)$this->id_tab)
                        return;
                
                $response= (int)DB::getInstance()->getValue('SELECT MAX(`position`) FROM `'._DB_PREFIX_.'innovativemenu_ads` WHERE `id_tab`='.(int)$this->id_tab);
                
                return $response + 1;
        }
        
        public static function getByPosition($position, $id_tab)
        {
                $id_ads = DB::getInstance()->getValue('SELECT `id_ads` FROM `'._DB_PREFIX_.'innovativemenu_ads`
                                                        WHERE `id_tab`='.(int)$id_tab.' AND `position` = '.(int)$position);
                if($id_ads)
                        return new Innovative_Ads((int)$id_ads);
                return;
        }
        
        private function initMenu()
        {
                $tab = new Innovative_Tab($this->id_tab);
                $menu = new Innovative_Menu($tab->id_menu);
                $menu->init();
        }
}