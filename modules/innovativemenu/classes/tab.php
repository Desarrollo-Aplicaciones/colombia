<?php
//require_once dirname(__FILE__).'/../../../config/config.inc.php';

class Innovative_Tab extends ObjectModel
{
	public $id_tab;
        public $id_menu;
        public $date_add;
        public $date_upd;
	public $id_link;
        public $type;
	public $font_family;
        public $font_size;
        public $font_weight;
        public $font_style;
	public $font_color;
	public $font_color_hover;
	public $background_color;
	public $background_color_hover;
	public $column_width;
        public $column_border_left_width;
        public $column_border_left_color;
        public $column_with_border_left;
	public $active;
	public $position;
        public $block_background_color;
        public $block_border_color;
        public $block_border_width;
        public $column_title_font_color;
        public $column_title_font_size;
        public $column_title_font_family;
        public $column_title_font_style;
        public $column_title_font_weight;
        public $column_title_horizontal_line_width;
        public $column_title_horizontal_line_color;
        public $column_list_font_weight_hover;
        public $column_list_font_style_hover;
	public $column_list_font_color_hover;
        public $column_list_underline_hover;
        public $column_title_with_horizontal_line;
        public $column_font_color;
        public $column_font_size;
        public $column_font_family;
        public $column_font_style;
        public $column_font_weight;
        public $advanced_config;
        public $name;
        public $ads_align;
        public $ads_width;
        public $ads_font_color;
        public $ads_background_color;
        public $with_ads;
        public $column_title_underline;
        
        public static $d_column_title_color  = '783c54';
        
        public static $type_of_tabs = array('cms', 'manufacturers', 'suppliers',
                        'categories', 'products', 'link');
        public static $all_align = array('left'=>'left', 'right'=>'right', 'top'=>'top', 'bottom'=>'bottom');

        protected $fieldsRequired = array('id_menu'); // champs sans lesquels pas d'enregistrement

	protected 	$table = 'innovativemenu_tab';
	protected 	$identifier = 'id_tab';
        
        
        public function loadDefault()
        {
                if (!empty ($this->id_menu))
                {
                        $menu = new Innovative_Menu($this->id_menu);
                        if (!empty($menu->id))
                        {
                                empty($this->font_family) ? $this->font_family = $menu->font_family : '';
                                empty($this->font_style) ? $this->font_style = $menu->font_style : '';
                                empty($this->font_size) ? $this->font_size = $menu->font_size : '';
                                empty($this->font_weight) ? $this->font_weight = $menu->font_weight : '';
                                empty($this->font_color) ? $this->font_color = $menu->font_color : '';
                                empty($this->font_color_hover) ? $this->font_color_hover = $menu->font_color_hover : '';
                                
                                empty($this->column_font_color) ? $this->column_font_color = $menu->block_font_color : '';
                                empty($this->column_font_family) ? $this->column_font_family = $menu->block_font_family : '';
                                empty($this->column_font_style) ? $this->column_font_style = $menu->block_font_style : '';
                                empty($this->column_font_weight) ? $this->column_font_weight = $menu->block_font_weight : '';
                                empty($this->column_font_size) ? $this->column_font_size = $menu->block_font_size : '';
                                empty($this->column_width) ? $this->column_width = 90 : '';
                                empty($this->column_title_font_color) ? $this->column_title_font_color = $menu->column_title_font_color : '';
                                empty($this->column_title_font_family) ? $this->column_title_font_family = $menu->column_title_font_family : '';
                                empty($this->column_title_font_style) ? $this->column_title_font_style = $menu->column_title_font_style : '';
                                empty($this->column_title_font_weight) ? $this->column_title_font_weight = $menu->column_title_font_weight : '';
                                empty($this->column_title_font_size) ? $this->column_title_font_size = $menu->column_title_font_size : '';
                                
                                empty($this->block_background_color) ? $this->block_background_color = $menu->block_background_color : '';
                                empty($this->block_border_color) ? $this->block_border_color = $menu->block_border_color : '';
                                empty($this->block_border_width) ? $this->block_border_width = $menu->block_border_width : '';
                                
                                empty($this->background_color) ? $this->background_color = $menu->tab_background_color : '';
                                empty($this->background_color_hover) ? $this->background_color_hover = $menu->tab_background_color_hover : '';
                                
                                
                                empty($this->ads_font_color) ? $this->ads_font_color = $menu->block_background_color : '';
                                empty($this->ads_background_color) ? $this->ads_background_color = $menu->block_font_color : '';
                                empty($this->border_left_width) ? $this->column_border_left_width = $menu->column_border_left_width : '';
                                empty($this->border_left_color) ? $this->column_border_left_color = $menu->column_border_left_color : '';
                                empty($this->with_border_left) ? $this->column_with_border_left = $menu->column_with_border_left : '';
                                empty($this->title_horizontal_line_width) ? $this->title_horizontal_line_width = $menu->column_title_horizontal_line_width : '';
                                empty($this->title_horizontal_line_color) ? $this->title_horizontal_line_color = $menu->column_title_horizontal_line_color : '';
                                empty($this->title_with_horizontal_line) ? $this->title_with_horizontal_line = $menu->column_title_with_horizontal_line : '';
                                empty($this->column_list_font_weight_hover) ? $this->column_list_font_weight_hover = $menu->column_list_font_weight_hover : '';
                                empty($this->column_list_font_style_hover) ? $this->column_list_font_style_hover = $menu->column_list_font_style_hover : '';
                                empty($this->column_list_font_color_hover) ? $this->column_list_font_color_hover = $menu->column_list_font_color_hover : '';
                        }
                        
                }
                $this->ads_width = 32;
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
        
        
	public function getFields()
	{
                $menu = new Innovative_Menu((int)$this->id_menu);
                $fields = array ();
		parent::validateFields();
                $fields['id_menu'] = (int)$this->id_menu;
                $fields['type'] = pSQL($this->type);
		$fields['id_link'] = (int)($this->id_link);
		$fields['font_size'] = isset($this->font_size) ? (int)$this->font_size : (int)$menu->font_size;
                $fields['font_family'] = isset($this->font_family) ? pSQL($this->font_family) : pSQL($menu->font_family);
                $fields['font_weight'] = isset($this->font_weight) ? pSQL($this->font_weight) : pSQL($menu->font_family);;
                $fields['font_style'] = isset($this->font_style) ? pSQL($this->font_style) : pSQL($menu->font_style);
		$fields['font_color'] = isset($this->font_color) ? pSQL($this->font_color) : pSQL($menu->font_color);
                $fields['font_color_hover'] = isset($this->font_color_hover) ? pSQL($this->font_color_hover) : pSQL($menu->font_color_hover);
                $fields['column_title_font_size'] = $this->column_title_font_size ? (float)$this->column_title_font_size : (float)$menu->column_title_font_size;
                $fields['column_title_font_family'] = $this->column_title_font_family ? pSQL($this->column_title_font_family) : pSQL($menu->column_title_font_family);
                $fields['column_title_font_weight'] = $this->column_title_font_weight ? pSQL($this->column_title_font_weight) : pSQL($menu->column_title_font_weight);
                $fields['column_title_font_style'] = $this->column_title_font_style ? pSQL($this->column_title_font_style) : pSQL($menu->column_title_font_style);
		$fields['column_title_font_color'] = $this->column_title_font_color ? pSQL($this->column_title_font_color) : pSQL($menu->column_title_font_color);
                $fields['column_font_size'] = $this->column_font_size ? (int)$this->column_font_size : (int)$menu->block_font_size;
                $fields['column_font_family'] = $this->column_font_family ? pSQL($this->column_font_family) : pSQL($menu->block_font_family);
                $fields['column_font_weight'] = $this->column_font_weight ? pSQL($this->column_font_weight) : pSQL($menu->block_font_weight);
                $fields['column_font_style'] = $this->column_font_style ? pSQL($this->column_font_style) : pSQL($menu->block_font_style);
		$fields['column_font_color'] = $this->column_font_color ? pSQL($this->column_font_color) : pSQL($menu->block_font_color);
		$fields['background_color'] = $this->background_color ? pSQL($this->background_color) : pSQL($menu->tab_background_color);
		$fields['background_color_hover'] = $this->background_color_hover ? pSQL($this->background_color_hover) : pSQL($menu->tab_background_color_hover);
                $fields['block_background_color'] = $this->block_background_color ? pSQL($this->block_background_color) : pSQL($menu->block_background_color);
		$fields['block_border_color'] = $this->block_border_color ? pSQL($this->block_border_color) : pSQL($menu->block_border_color);
                $fields['block_border_width'] = $this->id ? (int)$this->block_border_width : (int)$menu->block_border_width;
		$fields['column_width'] = isset($this->column_width) ? (int)$this->column_width : (int)$menu->column_width;
                $fields['column_border_left_width'] = isset($this->column_border_left_width) ? (int)$this->column_border_left_width : (int)$menu->column_border_left_width;
                $fields['column_border_left_color'] = isset($this->column_border_left_color) ? pSQL($this->column_border_left_color) : pSQL($menu->column_border_left_color);
                $fields['column_with_border_left'] = isset($this->column_with_border_left) ? (bool)$this->column_with_border_left : (bool)$menu->column_with_border_left;
                $fields['column_title_horizontal_line_width'] = isset($this->column_title_horizontal_line_width) ? (int)$this->column_title_horizontal_line_width : (int)$menu->column_title_horizontal_line_width;
                $fields['column_title_horizontal_line_color'] = isset($this->column_title_horizontal_line_color) ? pSQL($this->column_title_horizontal_line_color) : pSQL($menu->column_title_horizontal_line_color);
                $fields['column_title_with_horizontal_line'] = isset($this->column_title_with_horizontal_line) ? (bool)$this->column_title_with_horizontal_line : (bool)$menu->column_title_with_horizontal_line;
		$fields['column_list_font_style_hover'] = isset($this->column_list_font_style_hover) ? pSQL($this->column_list_font_style_hover) : pSQL($menu->column_list_font_style_hover);
                $fields['column_list_font_weight_hover'] = isset($this->column_list_font_weight_hover) ? pSQL($this->column_list_font_weight_hover) : pSQL($menu->column_list_font_weight_hover);
                $fields['column_list_font_color_hover'] = isset($this->column_list_font_color_hover) ? pSQL($this->column_list_font_color_hover) : pSQL($menu->column_list_font_color_hover);
                $fields['column_list_underline_hover'] = (bool)$this->column_list_underline_hover;
                $fields['active'] = $this->id ? (bool)$this->active : true;
                $fields['ads_align'] = $this->ads_align ? pSQL($this->ads_align) : pSQL(self::$all_align['bottom']);
                $fields['ads_width'] = $this->id ? (int)$this->ads_width : 32;
                $fields['advanced_config'] = !((bool)$menu->general_configuration);
		$fields['position'] = (int)$this->position;
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
                $fields['with_ads'] = $this->id ? (bool)$this->with_ads : false;
                $fields['column_title_underline'] = (bool)$this->column_title_underline;
                $fields['ads_font_color'] = !empty($this->ads_font_color) ? pSQL($this->ads_font_color) : pSQL($menu->block_background_color);
                $fields['ads_background_color'] = !empty($this->ads_background_color) ? pSQL($this->ads_background_color) : pSQL($menu->block_font_color);
                
		return $fields;
	}
        
        
        public function getCSSAttribut($name)
        {
                $name = trim($name);
                $menu = new Innovative_Menu($this->id_menu);
                $tab_fields = $this->getFields();
                $menu_fields = $menu->getFields();
                    
                if (!array_key_exists($name, $tab_fields) && !array_key_exists($name, $menu_fields))
                        return;
                
                $exclude = array('column_title_underline', 'type', 'id_link', 'active', 'ads_align', 'position', );
                if (array_key_exists($name, $exclude))
                        return $this->{$name};
                        
                if ($menu->general_configuration)
                {
                        switch ($name)
                        {
                                case 'column_font_size':
                                        $name = 'block_font_size';
                                        break;
                                case 'column_font_weight':
                                        $name = 'block_font_weight';
                                        break;
                                case 'column_font_family':
                                        $name = 'block_font_family';
                                        break;
                                case 'column_font_style':
                                        $name = 'block_font_style';
                                        break;
                                case 'column_font_color':
                                        $name = 'block_font_color';
                                        break;

                                default:
                                        break;
                        }
                        return Tools::htmlEntitiesUTF8($menu->{$name});
                }
                else
                {
                        switch ($name)
                        {
                                case 'block_font_size':
                                        $name = 'column_font_size';
                                        break;
                                case 'block_font_weight':
                                        $name = 'column_font_weight';
                                        break;
                                case 'block_font_family':
                                        $name = 'column_font_family';
                                        break;
                                case 'block_font_style':
                                        $name = 'column_font_style';
                                        break;
                                case 'block_font_color':
                                        $name = 'column_font_color';
                                        break;

                                default:
                                        break;
                        }
                        return Tools::htmlEntitiesUTF8($this->{$name});
                }
        }
        
        public function getTypeTraduction()
        {
                $module = Module::getInstanceByName('innovativemenu');
                return $module->getTypeTraduction($this->type);
        }
        
        public function save($nullvalues = false, $autodate = true)
        {
                if (empty($this->id_menu))
                        return;
                if (empty($this->position))
                        $this->position = $this->getNextPositionOnMenu();
                if ($this->type == 'link')
                {
                        $link = new Innovative_Link($this->id_link);
                        $this->name = $link->name;
                }

                if (!$this->advanced_config)
                {
                        $columns = $this->getColumns(true);
                        if (!empty($columns))
                                foreach ($columns AS $column)
                                {
                                        $column->advanced_config = false;
                                        $column->save();
                                }
                }
                        
                if (parent::save($nullvalues, $autodate))
                {
                        $this->initMenu();
                        return true;
                }
                        
                return;                
        }
        
        
        public function toggleActive()
        {
                $this->active = !$this->active;               
                return $this->save();
        }
        
        
        public function getNameOfLink()
        {
                global $cookie;
                
                $id_lang = $cookie->id_lang ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT'); 
                
                if ($this->name[$id_lang] AND $this->name[$id_lang] != '#' AND $this->type != 'link')
                        return $this->name[$id_lang];
                
                switch ($this->type) {
                        case 'manufacturers':
                                $manufacturer = new Manufacturer((int)$this->id_link, $id_lang);
                                return $manufacturer->name;

                        case 'suppliers':
                                $supplier = new Supplier((int)$this->id_link, $id_lang);
                                return $supplier->name;
                                
                        case 'categories':
                                $category = new Category((int)$this->id_link, $id_lang);
                                return $category->name;
                                
                        case 'cms':
                                $cms = new CMS((int)$this->id_link, $id_lang);
                                return $cms->meta_title;
                                
                        default :
                                $link = new Innovative_Link((int)$this->id_link, $id_lang);
                                return $link->name;
                }
        }
        
        public function getLink()
        {
                global $cookie;
               	$link = new Link();
                
                $id_lang = $cookie->id_lang ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT'); 
                
                switch ($this->type) {
                        case 'manufacturers':
                                $manufacturer = new Manufacturer((int)$this->id_link, $id_lang);
                                return $link->getManufacturerLink($manufacturer->id, $manufacturer->link_rewrite);

                        case 'suppliers':
                                $supplier = new Supplier((int)$this->id_link, $id_lang);
                                return $link->getSupplierLink($supplier->id, $supplier->link_rewrite);;
                                
                        case 'categories':
                                $category = new Category((int)$this->id_link, $id_lang);
                                return $category->getLink();
                                
                        case 'cms':
                                $cms = new CMS((int)$this->id_link, $id_lang);
                                return $link->getCMSLink($cms->id, $cms->link_rewrite);
                                
                        default :
                                $link = new Innovative_Link((int)$this->id_link, $id_lang);
                                return $link->link;
                }
        }
        
        private function getNextPositionOnMenu()
        {
                if (!(int)$this->id_menu)
                        return;
                
                $response= Db::getInstance()->getValue('SELECT MAX(`position`) AS `pos` FROM `'._DB_PREFIX_.'innovativemenu_tab` WHERE `id_menu`='.(int)$this->id_menu);
                return $response + 1;
        }
        
        public function delete()
        {
                $columns = $this->getColumns(true);
                if ($columns)
                        foreach ($columns as $column)
                                $column->delete();
                
                $ads = $this->getAds(true);
                if ($ads)
                        foreach ($ads as $ad)
                                $ad->delete();
                return parent::delete();
        }
        
        
        public function down()
        {
                $tmp = $this->position;
                $this->position = $this->hasPrevious();
                if (!$this->position OR !$this->id_menu)
                        return;

                $tab = self::getByPosition ($this->position, $this->id_menu);
                $tab->position = $tmp;
                return $tab->save() AND $this->save();
                
        }
        
        
        public function up()
        {
                $this->position = $this->hasNext();
                if (!$this->position OR !$this->id_menu)
                        return;
                $tab = self::getByPosition ($this->position, $this->id_menu);
                return $tab->down();
        }
        
        
        
        public static function getByPosition($position, $id_menu)
        {
                $id_tab = Db::getInstance()->getValue('SELECT `id_tab` FROM `'._DB_PREFIX_.'innovativemenu_tab`
                                                        WHERE `id_menu`='.(int)$id_menu.' AND `position` = '.(int)$position);
                if($id_tab)
                        return new Innovative_Tab((int)$id_tab);
                return;
        }
        
        
        public function hasNext()
        {
                return (int)Db::getInstance()->getValue('SELECT MIN(`position`) FROM `'._DB_PREFIX_.'innovativemenu_tab`
                                                WHERE `id_menu`='.(int)$this->id_menu.' AND `position` > '.(int)$this->position);
        }
        
        public function hasPrevious()
        {
                return (int)Db::getInstance()->getValue('SELECT MAX(`position`) FROM `'._DB_PREFIX_.'innovativemenu_tab`
                                                WHERE `id_menu`='.(int)$this->id_menu.' AND `position` < '.(int)$this->position);
        }


        public function getColumns($object = false)
        {
                $query = 'SELECT * FROM `'._DB_PREFIX_.'innovativemenu_column` WHERE `id_tab`='.(int)$this->id.' ORDER BY `position`, `id_column`';
                $data = Db::getInstance()->ExecuteS($query);
                
                if ($object AND count($data))
                {
                        $columns = array();
                        foreach ($data as $value)
                                $columns[] = new Innovative_Column($value['id_column']);
                        
                        return $columns;
                }
                return $data;
        }
        
        
        
        
        public function getAds($object = false)
        {
                $query = 'SELECT * FROM `'._DB_PREFIX_.'innovativemenu_ads` WHERE `id_tab`='.(int)$this->id.' ORDER BY `position`, `id_ads`';
                $data = Db::getInstance()->ExecuteS($query);
                
                if ($object AND count($data))
                {
                        $all_ads = array();
                        foreach ($data as $ads)
                                $all_ads[] = new Innovative_Ads($ads['id_ads']);
                        return $all_ads;
                }
                return $data;
        }
        
        public static function viewHomeTab($home_text)
        {
                global $cookie;
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

                if (array_key_exists((int)$cookie->id_lang, $home_text) AND !empty($home_text[(int)$cookie->id_lang]))
                        $text = $home_text[$cookie->id_lang];
                elseif (array_key_exists((int)$defaultLanguage, $home_text) AND !empty($home_text[$defaultLanguage]))
                        $text = $home_text[$defaultLanguage];
                else $text = 'home';
                        
                return '
                        <li class="innovative_tab" id="innovative_tab_0">
                                <a href="'.__PS_BASE_URI__.'">'.Tools::htmlentitiesUTF8($text).'</a>
                        </li>';
        }




        public function view()
        {
                $menu = new Innovative_Menu($this->id_menu);
                $columns = $this->getColumns(true);
                $ads = $this->getAds(true);
                if (!in_array($this->type, self::$type_of_tabs))
                        return;
                
                $output_columns = $output_ads = false;
                $first = true;
                if (is_array($columns) AND count($columns))
                {
                        $container_width = (int)($menu->width - 2*$this->block_border_width);
                        if ($this->with_ads AND !empty($ads) AND ($this->ads_align == 'left' OR $this->ads_align == 'right'))
                                $container_width = $container_width - ($container_width*(int)$this->ads_width)/100;
                                
                        $counter = $container_width;
                        foreach ($columns as $column)
                        {
                                // column_margin = 5; column_padding_right = 10; column_padding = 5)
                                if (($counter -= (int)($column->getCSSAttribut('width') + (2*5 + 2*5 + 5))) <= 20)
                                {
                                        $output_columns .= $column->view('clear:both;', true);
                                        $counter = $container_width;
                                        $first = false;
                                }
                                else
                                {
                                        $output_columns .= $column->view(false, $first);
                                        $first = false;
                                }
                        }
                }
                
                
                if (is_array($ads) AND count($ads))
                        foreach ($ads as $value)
                                $output_ads .= $value->view();
                
                $output_ads = InnovativeMenu::cleanHTML($output_ads);
                $output_columns = InnovativeMenu::cleanHTML($output_columns);
                                
                if ($this->ads_align == 'left')
                        $part = '
                                <div id="block_advertising_'.(int)$this->id.'" style="margin:0; '.(!empty($ads) ? 'width:'.(int)$this->ads_width.'%;' : '').' float:left;">'.$output_ads.'</div>
                                <div id="block_columns_'.(int)$this->id.'" style="float:left; '.(!empty($ads) ? 'width:'.(int)(98 - $this->ads_width).'%;' : '').'">'.$output_columns.'</div>';
                
                elseif ($this->ads_align == 'right')
                        $part = '
                                <div id="block_columns_'.(int)$this->id.'" style="float:left; '.(!empty($ads) ? 'width:'.(int)(98 - $this->ads_width).'%;' : '').'">'.$output_columns.'</div>
                                <div id="block_advertising_'.(int)$this->id.'" style="margin:0; '.(!empty($ads) ? 'width:'.(int)$this->ads_width.'%;' : '').' float:right;">'.$output_ads.'</div>';
                
                elseif ($this->ads_align == 'bottom')
                        $part = '
                                <div id="block_columns_'.(int)$this->id.'" style="width:100%; float:left;">'.$output_columns.'</div>
                                <div id="block_advertising_'.(int)$this->id.'" style="margin:0; width:100%; float:left;">'.$output_ads.'</div>';
                
                elseif ($this->ads_align == 'top')
                        $part = '
                                <div id="block_advertising_'.$this->id.'" style="margin:0; width:100%; float:left;">'.$output_ads.'</div>
                                <div id="block_columns_'.$this->id.'" style="width:100%; float:left;">'.$output_columns.'</div>';
                
                if (!empty($output_ads) OR !empty($output_columns))
                        $part = '<div class="innovative_tab_container clearfix" id="innovative_tab_container_'.(int)$this->id.'">
                                        '.$part.'
                                </div>';
                else $part = '';

                return '
                        <li class="innovative_tab" id="innovative_tab_'.$this->id.'">
                                <a href="'.InnovativeMenu::cleanHTML($this->getLink()).'">'.InnovativeMenu::cleanHTML($this->getNameOfLink()).'</a>
                                '.$part.'
                        </li>';
        }
        
        
        
        public function css($style = false)
        {
                $menu = new Innovative_Menu((int)$this->id_menu);
                if (!$this->type)
                        return;
                $v_border_top_radius = $menu->border_top_radius ? ((int)$menu->border_top_radius - 1) : 0;
                $v_border_bottom_radius = $menu->border_bottom_radius ? ((int)$menu->border_bottom_radius - 1) : 0;
                $border_top_radius = '
                        -moz-border-top-left-radius:'.$v_border_top_radius.'px;
                        -ms-border-top-left-radius:'.$v_border_top_radius.'px;
                        -o-border-top-left-radius:'.$v_border_top_radius.'px;
                        -ms-border-top-left-radius:'.$v_border_top_radius.'px;
                        border-top-left-radius:'.$v_border_top_radius.'px;
                        -moz-border-top-right-radius:'.$v_border_top_radius.'px;
                        -ms-border-top-right-radius:'.$v_border_top_radius.'px;
                        -o-border-top-right-radius:'.$v_border_top_radius.'px;
                        -ms-border-top-right-radius:'.$v_border_top_radius.'px;
                        border-top-right-radius:'.$v_border_top_radius.'px;';
                
                $border_bottom_radius = '
                        -moz-border-bottom-left-radius:'.$v_border_bottom_radius.'px;
                        -ms-border-bottom-left-radius:'.$v_border_bottom_radius.'px;
                        -o-border-bottom-left-radius:'.$v_border_bottom_radius.'px;
                        -ms-border-bottom-left-radius:'.$v_border_bottom_radius.'px;
                        border-bottom-left-radius:'.$v_border_bottom_radius.'px;
                        -moz-border-bottom-right-radius:'.$v_border_bottom_radius.'px;
                        -ms-border-bottom-right-radius:'.$v_border_bottom_radius.'px;
                        -o-border-bottom-right-radius:'.$v_border_bottom_radius.'px;
                        -ms-border-bottom-right-radius:'.$v_border_bottom_radius.'px;
                        border-bottom-right-radius:'.$v_border_bottom_radius.'px;';
                
                $border_ads = '';
                if ($this->ads_align == 'top')
                        $border_ads = 'border-bottom :1px solid #'.$this->getCSSAttribut('font_color').';';
                if ($this->ads_align == 'bottom')
                        $border_ads = 'border-top :1px solid #'.$this->getCSSAttribut('font_color').';';
                if ($this->ads_align == 'left')
                        $border_ads = 'border-right :1px solid #'.$this->getCSSAttribut('font_color').';';
                if ($this->ads_align == 'right')
                        $border_ads = 'border-left :1px solid #'.$this->getCSSAttribut('font_color').';';
                
                $container_width = (int)($menu->width - 2*$this->block_border_width);
                $output = '
                        #innovative_tab_'.(int)$this->id.'{
                                background-color: #'.$this->getCSSAttribut('background_color').' 0 0 no-repeat ;
                                border-color:#'.$this->getCSSAttribut('font_color').';
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                        }
                        
                        
                        #innovative_tab_container_'.(int)$this->id.'{
                                display:none;
                                background: #'.$this->getCSSAttribut('block_background_color').' no-repeat 0 0;
                                '.($this->advanced_config ? ' width : '.$container_width.'px; border : #'.Tools::htmlEntitiesUTF8($this->block_border_color).' solid '.(int)$this->block_border_width.'px' : '').'
                        }
                        
                        
                        #innovative_tab_container_'.(int)$this->id.' div:first-child{
                                border-left:none;
                        }
                        
                        
                        .innovative_tab:hover #innovative_tab_container_'.$this->id.'{
                                display:block;
                        }
                        
                        #innovative_tab_'.(int)$this->id.':hover{
                                background: #'.Tools::htmlEntitiesUTF8(($menu->general_configuration ? $menu->tab_background_color : $this->background_color)).' 0 0 no-repeat ;
                        }
                        
                        #innovative_tab_'.(int)$this->id.' > a{
                                color:#'.Tools::htmlEntitiesUTF8($this->getCSSAttribut('font_color')).';
                                font-family:'.Tools::htmlentitiesUTF8($menu->font_family).';
                                font-size:'.(int)($size = $menu->font_size).'px;
                                text-decoration:none;
                                padding: '.((int)($menu->height - $size)/2 - 1).'px 24px;
                                display:block;
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                        }
                        
                        #innovative_tab_'.(int)$this->id.':hover > a{
                                color:#'.$this->getCSSAttribut('font_color_hover').';
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                        }
                        
                        #innovative_column_'.$this->id.'{
                                float:left;
                                padding:5px 5px 5px 10px;
                                margin:5px;
                                font-size:'.$this->getCSSAttribut('column_font_size').'px;
                                font-style:'.$this->getCSSAttribut('column_font_style').';
                                font-family:'.$this->getCSSAttribut('column_font_family').';
                                font-weight:'.$this->getCSSAttribut('column_font_weight').';
                                color:'.$this->getCSSAttribut('column_font_color').';
                                '.($menu->general_configuration ? 'width:'.(int)($this->getCSSAttribut('column_width')).'px;' : '').'
                                display:block;
                        }
                        
                        #innovative_column_'.$this->id.' p{margin:0; padding:0;}
                        

                        #block_advertising_'.$this->id.'{
                                color:#'.Tools::htmlEntitiesUTF8($this->ads_font_color).';
                                background-color:#'.Tools::htmlEntitiesUTF8($this->ads_background_color).';
                                margin:5px;
                                '.$border_ads.'
                        }
                ';
                
                $columns = $this->getColumns(true);
                if (count($columns))
                {
                        foreach ($columns as $column) {
                                $output .= $column->css();
                        }       
                }
                return $style ? '<style>'.$output.'</style>' : $output;
        }
        
        private function initMenu()
        {
                $menu = new Innovative_Menu($this->id_menu);
                $menu->init();
        }
}
