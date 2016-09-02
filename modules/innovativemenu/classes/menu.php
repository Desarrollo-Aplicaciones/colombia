<?php
//require_once dirname(__FILE__).'/../../../config/config.inc.php';

Class Innovative_Menu extends ObjectModel
{
        /** @var string name */
        public $name;
        
        /** @var integer id in SQL table */
        public $id_menu;
        
        /** @var string HTML basic color of menubar */
        public $background_color;
        
        /** @var string HTML basic background color of tabs */
        public $tab_background_color;
        
        /** @var string HTML basic background color of tabs when mouse over */
        public $tab_background_color_hover;
        public $innovative_context;
        
        public $id_menu_context;
        
        public $block_border_color;
        public $block_border_width;
        public $block_background_color;
        public $block_font_size;
        public $block_font_color;
        public $block_font_family;
        public $block_font_style;
        public $block_font_weight;
        
        public $font_family;
        public $font_style;
        public $font_size;
        public $font_weight;
        
        /** @var string HTML font color of menubar */
        public $font_color;
        
        /** @var string HTML font color of menubar when mouse over */
        public $font_color_hover;
        
        public $column_title_font_color;
        public $column_title_font_size;
        public $column_title_font_family;
        public $column_title_font_style;
        public $column_title_font_weight;
        public $column_title_underline;
        
        public $column_title_horizontal_line_width;
        public $column_title_horizontal_line_color;
        public $column_title_with_horizontal_line;
        
        public $general_configuration;
        public $border_top_radius;
        public $border_bottom_radius;
        public $radial_gradient;
        public $column_width;
        public $column_border_left_width;
        public $column_border_left_color;
        public $column_with_border_left;
	public $column_list_font_weight_hover;
        public $column_list_font_style_hover;
	public $column_list_font_color_hover;
        public $column_list_underline_hover;
        
        
        public $home_text;
        public $with_home_tab;
        /** @var integer height of menu */
        public $height;
        
        /** @var integer width of menu */
        public $width;
        
        /** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public	$date_upd;
        
        public $active;
        
        public static $d_border_top_radius = 5;
        public static $d_border_bottom_radius = 0;
        
        public static $d_background_color = '4d4b4b';
        
        public static $d_tab_background_color = 'f5f5f5';
        
        public static $d_tab_background_color_hover = 'f5f5f5';
        
        public static $d_block_font_color = 'f5f5f5';
        
        public static $d_block_border_color = 'f5f5f5';
        
        public static $d_block_background_color = '4d4b4b';
        
        public static $d_block_border_width = 5;
        
        public static $d_font_color = 'f5f5f5';
        
        public static $d_font_color_hover = '4d4b4b';
        
        public static $d_column_title_font_color = '197f8c';
        
        public static $d_column_width = 90;
        
        public static $d_height = 30;
        
        public static $d_width = 980;             

        /** @var string Object SQL table */
        protected $table = 'innovativemenu';
        
        /** @var string Object SQL identifier */
	protected $identifier = 'id_menu';
        
        public static $all_font_style = array (
            0 => 'normal',
            1 => 'italic',
            2 => 'oblique'
        );
        
        public static $all_column_title_font_size = array (
            0 => '1',
            1 => '1.1',
            2 => '1.2',
            3 => '1.3',
            4 => '1.4',
            5 => '1.5'
        );
        
        public static $all_font_size = array (
            0 => '12',
            1 => '13',
            2 => '14',
            3 => '15',
            4 => '16',
            5 => '17',
        );
        
        public static $all_font_weight = array (
            0 => 'normal',
            1 => 'bold',
            2 => 'bolder',
            3 => 'lighter'
        );
        
        
        public function __construct($id = null, $id_lang = null)
        {
                parent::__construct($id, $id_lang);
                $this->innovative_context = new Innovative_Context($this->id_menu_context);

        }
        
        
        public function activate()
        {
                Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'innovativemenu` 
                                                SET `active` = 0
                                                WHERE `id_menu_context` = '.(int)$this->id_menu_context.' AND `id_menu` <>'.(int)$this->id);
                $this->active = true;
                $this->save();
                $this->init();
        }
        
        
        public static function get($active = false, $type_context = null, $id_element = 0)
        {
                $query = '
                        SELECT `im`.`id_menu`
                        FROM `'._DB_PREFIX_.'innovativemenu` AS `im`';
                
                if (!empty($type_context))
                        $query .= '
                                LEFT JOIN `'._DB_PREFIX_.'innovativemenu_context` AS `ic` ON `ic`.`id_menu_context` = `im`.`id_menu_context`
                                WHERE `ic`.`type_context`='.(int)$type_context.' AND `ic`.`id_element`='.(int)$id_element;
                else $query .= ' WHERE 1=1';
                        
                
                if ($active)
                {
                        $query .= ' AND `active` = 1';
                        
                        $result = Db::getInstance()->getValue($query);
                        
                        if ($result)
                               return new Innovative_Menu($result);
                }
                else
                {                        
                        $results = Db::getInstance()->ExecuteS($query);
                        $output = array ();
                        if (count($results))
                                foreach ($results AS $res)
                                        $output[] = new Innovative_Menu($res['id_menu']);
                        return $output;
                }
                return;
        }
        
        /**
         * Return all fields of this Object
         * 
         * @return array
         */
        public function getFields()
	{
                $fields = array();
		parent::validateFields();
		$fields['name'] = pSQL($this->name);
                $fields['background_color'] = $this->background_color ? pSQL($this->background_color) : pSQL(self::$d_background_color);
                
                $fields['tab_background_color'] = $this->tab_background_color ? pSQL($this->tab_background_color) : pSQL(self::$d_tab_background_color);
                
                $fields['block_border_color'] = $this->block_border_color ? pSQL($this->block_border_color) : pSQL(self::$d_block_border_color);
                $fields['block_border_width'] = (int)$this->block_border_width;
                $fields['block_background_color'] = $this->block_background_color ? pSQL($this->block_background_color) : pSQL(self::$d_block_background_color);
                $fields['block_font_size'] = $this->block_font_size ? (int)$this->block_font_size : (int)self::$all_font_size[2];
                $fields['block_font_color'] = $this->block_font_color ? pSQL($this->block_font_color) : pSQL(self::$d_block_font_color);
                $fields['block_font_family'] = $this->block_font_family ? pSQL($this->block_font_family) : pSQL(self::$all_font_family[0]);
                $fields['block_font_style'] = $this->block_font_style ? pSQL($this->block_font_style) : pSQL(self::$all_font_style[0]);
                $fields['block_font_weight'] = $this->block_font_weight ? pSQL($this->block_font_weight) : pSQL(self::$all_font_weight[0]);
                
                
                $fields['font_color'] = $this->font_color ? pSQL($this->font_color) : pSQL(self::$d_font_color);
                $fields['font_color_hover'] = $this->font_color_hover ? pSQL($this->font_color_hover) : pSQL(self::$d_font_color_hover);
                $fields['font_family'] = $this->font_family ? pSQL($this->font_family) : pSQL(self::$all_font_family[0]);
                $fields['font_style'] = $this->font_style ? pSQL($this->font_style) : pSQL(self::$all_font_style[0]);
                $fields['font_weight'] = $this->font_weight ? pSQL($this->font_weight) : pSQL(self::$all_font_weight[0]);
                $fields['font_size'] = $this->font_size ? (int)$this->font_size : (int)self::$all_font_size[3];
                
                $fields['column_title_font_color'] = $this->column_title_font_color ? pSQL($this->column_title_font_color) : pSQL(self::$d_column_title_font_color);
                $fields['column_title_font_family'] = $this->column_title_font_family ? pSQL($this->column_title_font_family) : pSQL(self::$all_font_family[0]);
                $fields['column_title_font_style'] = $this->column_title_font_style ? pSQL($this->column_title_font_style) : pSQL(self::$all_font_style[0]);
                $fields['column_title_font_weight'] = $this->column_title_font_weight ? pSQL($this->column_title_font_weight) : pSQL(self::$all_font_weight[0]);
                $fields['column_title_font_size'] = $this->column_title_font_size ? (float)$this->column_title_font_size : (float)self::$all_column_title_font_size[2];
                $fields['column_width'] = $this->column_width ? (int)$this->column_width : (int)self::$d_column_width;
                $fields['column_title_underline'] = (bool)$this->column_title_underline;
                
                $fields['column_border_left_width'] = (int)$this->column_border_left_width;
                $fields['column_border_left_color'] = $this->column_border_left_color ? pSQL($this->column_border_left_color) : $fields['font_color'];
                $fields['column_with_border_left'] = (bool)$this->column_with_border_left;
                $fields['column_title_horizontal_line_width'] = (int)$this->column_title_horizontal_line_width;
                $fields['column_title_horizontal_line_color'] = $this->id ? pSQL($this->column_title_horizontal_line_color) : $fields['column_title_font_color'];
                $fields['column_title_with_horizontal_line'] = (bool)$this->column_title_with_horizontal_line;


		$fields['column_list_font_style_hover'] = $this->column_list_font_style_hover ? pSQL($this->column_list_font_style_hover) : pSQL(self::$all_font_style[0]);
                $fields['column_list_font_weight_hover'] = $this->column_list_font_weight_hover ? pSQL($this->column_list_font_weight_hover) : pSQL(self::$all_font_weight[0]);
		$fields['column_list_font_color_hover'] = $this->column_list_font_color_hover ? pSQL($this->column_list_font_color_hover) : pSQL(self::$d_font_color);
                $fields['column_list_underline_hover'] = (bool)$this->column_list_underline_hover;
                
                $fields['border_top_radius'] = (int)$this->border_top_radius;
                $fields['border_bottom_radius'] = (int)$this->border_bottom_radius;
                $fields['general_configuration'] = (bool)$this->general_configuration;
                $fields['with_home_tab'] = (bool)$this->with_home_tab;
                $fields['height'] = $this->height ? (int)$this->height : (int)self::$d_height;
                $fields['width'] = $this->width ? (int)$this->width : (int)self::$d_width;
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
                $fields['active'] = (bool)$this->active;
                $fields['id_menu_context'] = (int)$this->id_menu_context;

		return $fields;
	}
        
        
        public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();

		$fieldsArray = array('home_text');
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
        
        
        public function getTabs($object = false)
        {
                $query = 'SELECT * FROM `'._DB_PREFIX_.'innovativemenu_tab` WHERE `id_menu`='.(int)$this->id.' ORDER BY `position`, `id_menu`';
                $data = Db::getInstance()->ExecuteS($query);
                
                if (count($data))
                {
                        $tabs = array();
                        foreach ($data as $value) {
                                $tab = new Innovative_Tab($value['id_tab']);
                                if ($object)
                                        $tabs[] = $tab;
                                else
                                {
                                        $value['has_next'] = $tab->hasNext();
                                        $value['has_previous'] = $tab->hasPrevious();
                                        $value['name_of_link'] = $tab->getNameOfLink();
                                        $tabs[] = $value;
                                }
                        }
                        return $tabs;
                }
                return false;
        }
        
        
        public function save($nullvalues = false, $autodate = true)
        {
                if (!empty($this->innovative_context->id))
                        $this->id_menu_context = $this->innovative_context->id;
                
                if ($this->general_configuration)
                {
                        $tabs = $this->getTabs(true);
                        if (is_array($tabs) AND !empty($tab))
                                foreach ($tabs AS $tab)
                                {
                                        $tab->advanced_config = false;
                                        $tab->save();
                                }
                }
                $response = parent::save($nullvalues, $autodate);
                $this->init(true);
                
                
                return $response;
        }


        public function toggleActive()
        {
                if ($this->active)
                {
                        $this->deleteFiles();
                        $this->active = false;
                        $this->save();
                }
                else $this->activate();

                return true;
        }
        
        
        public function init($new_file = true, $css = true)
        {
                if ($this->active)
                {
                        $this->view(true, $new_file);
                        if ($css)
                                $this->css(false);
                }
        }
        
        public function delete()
        {
                $tabs = $this->getTabs(true);
                if (!empty($tabs) AND !is_array($tabs))
                        foreach ($tabs as $tab)
                                $tab->delete();
                $this->deleteFiles();
                return parent::delete();
        }


        public function deleteFiles()
        {
                if ($this->active)
                {
                        $languages = Language::getLanguages(true);
                        foreach ($languages As $lang)
                                if (file_exists(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_'.$lang['iso_code'].'.tpl'))
                                        unlink(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_'.$lang['iso_code'].'.tpl');
                                
                        if (file_exists(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_'.'header.css'))
                                unlink(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_'.'header.css');
                        
                        if (file_exists(dirname(__FILE__).'/../innovativemenu_fonts.css'))
                                unlink(dirname(__FILE__).'/../innovativemenu_fonts.css');
                }
        }

        public function view($tpl = false, $new_file = true)
        {
                global $cookie;
                
                $tabs = $this->getTabs(true);
                
		$output_tabs = '';
                if ($tabs AND count($tabs))
                {
                        foreach ($tabs as $tab)
                                $output_tabs .= $tab->view();      
                }
                $output = '
                        </div>
                        <div class="clear">&nbsp;</div>
                        <div class="innovative_container">
                                <ul class="clearfix">
                                        '.$output_tabs.'
                                </ul>
                        </div>
                        <div>';
                $id_lang = (empty($cookie->id_lang)? Configuration::get('PS_LANG_DEFAULT') : $cookie->id_lang);
                $iso = Language::getIsoById($id_lang); 
                
                $output = InnovativeMenu::cleanHTML($output);
                if ($tpl AND $this->active)
                {
                        if ($new_file)
                        {
                                $languages = Language::getLanguages(true);
                        }
                        
			$file_exists = file_exists(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_'.$iso.'.tpl');
                        if (!$file_exists)
                        {

                                InnovativeMenu::clearMenuCache($this->id_menu_context, $id_lang);
                                $return = file_put_contents(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_'.$iso.'.tpl', $output);
                                
                                if (file_exists(_PS_THEME_DIR_.'modules/innovativemenu/') AND is_dir(_PS_THEME_DIR_.'modules/innovativemenu/'))
                                        @copy(dirname(__FILE__).'/../innovativemenu_'.$iso.'.tpl', _PS_THEME_DIR_.'moduules/innovativemenu/innovativemenu_'.$this->id_menu_context.'_'.$iso.'.tpl');
                                
                                return $return;
                        }
                }
                return $this->css().$output;
        }
        
        
        public function css($style = true)
        {
                $border_top_radius = '
                        -moz-border-top-left-radius:'.(int)$this->border_top_radius.'px;
                        -ms-border-top-left-radius:'.(int)$this->border_top_radius.'px;
                        -o-border-top-left-radius:'.(int)$this->border_top_radius.'px;
                        -ms-border-top-left-radius:'.(int)$this->border_top_radius.'px;
                        border-top-left-radius:'.(int)$this->border_top_radius.'px;
                        -moz-border-top-right-radius:'.(int)$this->border_top_radius.'px;
                        -ms-border-top-right-radius:'.(int)$this->border_top_radius.'px;
                        -o-border-top-right-radius:'.(int)$this->border_top_radius.'px;
                        -ms-border-top-right-radius:'.(int)$this->border_top_radius.'px;
                        border-top-right-radius:'.(int)$this->border_top_radius.'px;';
                
                $border_bottom_radius = '
                        -moz-border-bottom-left-radius:'.(int)$this->border_bottom_radius.'px;
                        -ms-border-bottom-left-radius:'.(int)$this->border_bottom_radius.'px;
                        -o-border-bottom-left-radius:'.(int)$this->border_bottom_radius.'px;
                        -ms-border-bottom-left-radius:'.(int)$this->border_bottom_radius.'px;
                        border-bottom-left-radius:'.(int)$this->border_bottom_radius.'px;
                        -moz-border-bottom-right-radius:'.(int)$this->border_bottom_radius.'px;
                        -ms-border-bottom-right-radius:'.(int)$this->border_bottom_radius.'px;
                        -o-border-bottom-right-radius:'.(int)$this->border_bottom_radius.'px;
                        -ms-border-bottom-right-radius:'.(int)$this->border_bottom_radius.'px;
                        border-bottom-right-radius:'.(int)$this->border_bottom_radius.'px;';
                
                
                $container_width = (int)($this->width - 2*$this->block_border_width);
                $output = '
                        .innovative_container q:before, .innovative_container q:after{content:""}
                        .innovative_container a {cursor:pointer; text-decoration:none;}
                        /*.innovative_container p{width:inherit;}*/
                        
                        .innovative_container .clearfix:before,
                        .innovative_container .clearfix:after {
                                content: ".";
                                display: block;
                                height: 0;
                                overflow: hidden
                        }
                        .innovative_container .clearfix:after {clear: both}
                        .innovative_container .clearfix {zoom: 1}
                        
                        .innovative_container{
                                clear: both;
                                position:relative;
                                margin:10px auto;
                                width:'.(int)$this->width.'px;
                                height:'.(int)$this->height.'px;
                                background-color:#'.Tools::htmlentitiesUTF8($this->background_color).';
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                                font-style:'.Tools::htmlentitiesUTF8($this->font_style).';
                                font-family:'.Tools::htmlentitiesUTF8($this->font_family).';
                                font-weight:'.Tools::htmlentitiesUTF8($this->font_weight).';
                        }
                        
                        *:first-child+html .innovative_container{
                                z-index:1000;
                        }
                        
                        .innovative_container ul{
                                list-style:none;
                                padding:0;
                        }
                        
                        .innovative_container ul li{
                                float:left;
                        }
                        
                        .innovative_container ul li:first-child{
                                border-left:none;
                        }
                        
                        .innovative_tab_container{
                                display:none;
                                position:absolute;
                                left:0;
                                top:'.(int)$this->height.'px;
                                height:auto;
                                width:'.(int)($container_width).'px;
                                '.($this->general_configuration ? 'border : solid '.(int)$this->block_border_width.'px #'.Tools::htmlentitiesUTF8($this->block_border_color).';' : '').'
                                z-index:3000;
                                font-style:'.Tools::htmlentitiesUTF8($this->block_font_style).';
                                font-family:'.Tools::htmlentitiesUTF8($this->block_font_family).';
                                font-weight:'.Tools::htmlentitiesUTF8($this->block_font_weight).';
                                color:'.Tools::htmlentitiesUTF8($this->block_font_color).';
                        }
                        
                        #innovative_tab_0 {
                                background-color: #'.Tools::htmlentitiesUTF8($this->background_color).' 0 0 no-repeat ;
                                border-color:#'.Tools::htmlentitiesUTF8($this->font_color).';
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                        }
                        
                        #innovative_tab_0 > a{
                                color:#'.Tools::htmlEntitiesUTF8($this->font_color).';
                                font-family:'.Tools::htmlentitiesUTF8($this->font_family).';
                                font-size:'.(int)($size = $this->font_size).'px;
                                text-decoration:none;
                                padding: '.((int)($this->height - $size)/2 - 1).'px 24px;
                                display:block;
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                        }
                        
                        #innovative_tab_0:hover > a{
                                background-color:#'.Tools::htmlentitiesUTF8($this->font_color).';
                                color:#'.Tools::htmlentitiesUTF8($this->font_color_hover).';
                                '.$border_top_radius.'
                                '.$border_bottom_radius.'
                        }
                ';
                
                $tabs = $this->getTabs(true);
                if ($tabs AND count($tabs))
                        foreach ($tabs as $tab)
                                $output .= $tab->css();

                $output = InnovativeMenu::cleanHTML($output);
                
                $font_css = NULL;
                $fonts = Innovative_Font_Family::get(false, true);
                if (!empty($fonts) AND is_array($fonts))
                        foreach ($fonts AS $font)
                                $font_css .= $font->css();
                
                if ($style)
                        return '<style>'.$font_css.'</style>
                                <style>'.$output.'</style>';
                
                elseif ($this->active)
                {
                        file_put_contents(dirname(__FILE__).'/../innovativemenu_'.$this->id_menu_context.'_header.css', $output);
                        file_put_contents(dirname(__FILE__).'/../innovativemenu_fonts.css', $font_css);
                }
        }
        
}
