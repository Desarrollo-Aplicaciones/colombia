<?php
require_once dirname(__FILE__).'/../../../config/config.inc.php';

class Innovative_Column extends ObjectModel
{
	public $id_column;
	public $id_tab;
	public $active;
	public $position;
        public $with_title;
        public $title_clickable;
        public $advanced_config;
	public $type;
        public $id_type;
        public $text;
        public $title;
        public $title_link;
	public $title_font_color;
	public $title_font_weight;
	public $title_font_size;
	public $title_font_style;
        public $title_font_family;
        public $title_horizontal_line_width;
        public $title_horizontal_line_color;
        public $title_with_horizontal_line;
        public $font_color;
	public $font_weight;
	public $font_size;
	public $font_style;
        public $font_family;
        public $width;
        public $border_left_width;
        public $border_left_color;
        public $with_border_left;
        public $date_add;
        public $date_upd;
        public $list_font_weight_hover;
        public $list_font_style_hover;
	public $list_font_color_hover;
        public $list_underline_hover;

        public static $all_types = array ('text' => 'text', 'list' => 'list', 'categories' => 'categories');
	protected $fieldsRequired = array('id_tab'); // champs sans lesquels pas d'enregistrement
	protected $fieldsValidate = array('id_tab' => 'isInt'); //utiliser Validate:: pour check tables. 

	protected $table = 'innovativemenu_column';
	protected $identifier = 'id_column';
        
        
        public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();

		$fieldsArray = array('text', 'title');
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
        
        
        
        public function loadDefault()
        {
                if (!empty ($this->id_tab))
                {
                        $tab = new Innovative_Tab($this->id_tab);
                        if (!empty($tab->id) AND $tab->advanced_config)
                        {
                                empty($this->font_family) ? $this->font_family = $tab->column_font_family : '';
                                empty($this->font_style) ? $this->font_style = $tab->column_font_style : '';
                                empty($this->font_size) ? $this->font_size = $tab->column_font_size : '';
                                empty($this->font_weight) ? $this->font_weight = $tab->column_font_weight : '';
                                empty($this->font_color) ? $this->font_color = $tab->column_font_color : '';
                                
                                empty($this->width) ? $this->width = $tab->column_width : '';
                                empty($this->title_font_color) ? $this->title_font_color = $tab->column_title_font_color : '';
                                empty($this->title_font_family) ? $this->title_font_family = $tab->column_title_font_family : '';
                                empty($this->title_font_style) ? $this->title_font_style = $tab->column_title_font_style : '';
                                empty($this->title_font_weight) ? $this->title_font_weight = $tab->column_title_font_weight : '';
                                empty($this->title_font_size) ? $this->title_font_size = $tab->column_title_font_size : '';
                                empty($this->border_left_width) ? $this->border_left_width = $tab->column_border_left_width : '';
                                empty($this->border_left_color) ? $this->border_left_color = $tab->column_border_left_color : '';
                                empty($this->with_border_left) ? $this->with_border_left = $tab->column_with_border_left : '';
                                empty($this->title_horizontal_line_width) ? $this->title_horizontal_line_width = $tab->column_title_horizontal_line_width : '';
                                empty($this->title_horizontal_line_color) ? $this->title_horizontal_line_color = $tab->column_title_horizontal_line_color : '';
                                empty($this->title_with_horizontal_line) ? $this->title_with_horizontal_line = $tab->column_title_with_horizontal_line : '';
                                empty($this->list_font_weight_hover) ? $this->list_font_weight_hover = $tab->column_list_font_weight_hover : '';
                                empty($this->list_font_style_hover) ? $this->list_font_style_hover = $tab->column_list_font_style_hover : '';
                                empty($this->list_font_color_hover) ? $this->list_font_color_hover = $tab->column_list_font_color_hover : '';
                        }
                        
                }
        }
        
	public function getFields()
	{
		parent::validateFields();
                $tab = new Innovative_Tab($this->id_tab);
                $menu = new Innovative_Menu($tab->id_menu);
                $fields = array ();
		$fields['id_tab'] = (int)$this->id_tab;
		$fields['font_color'] = $this->font_color ? pSQL($this->font_color) : pSQL($tab->column_font_color);
		$fields['font_weight'] = $this->font_weight ? pSQL($this->font_weight) : pSQL($tab->column_font_weight);
		$fields['font_size'] = $this->font_size ? (int)$this->font_size : (int)$tab->column_font_size;
                $fields['font_style'] = $this->font_style ? pSQL($this->font_style) : pSQL($tab->column_font_style);
                $fields['font_family'] = $this->font_family ? pSQL($this->font_family) : pSQL($tab->column_font_family);
		$fields['title_font_color'] = $this->title_font_color ? pSQL($this->title_font_color) : pSQL($tab->column_title_font_color);
		$fields['title_font_weight'] = $this->title_font_weight ? pSQL($this->title_font_weight) : pSQL($tab->column_title_font_weight);
		$fields['title_font_size'] = $this->title_font_size ? (float)$this->title_font_size : (float)$tab->column_title_font_size;
                $fields['title_font_style'] = $this->title_font_style ? pSQL($this->title_font_style) : pSQL($tab->column_title_font_style);
                $fields['title_font_family'] = $this->title_font_family ? pSQL($this->title_font_family) : pSQL($tab->column_title_font_family);
                $fields['type'] = pSQL($this->type);
                $fields['id_type'] = (int)$this->id_type;
                $fields['active'] = (bool)$this->active;
                $fields['with_title'] = (bool)$this->with_title;
                $fields['title_clickable'] = (bool)$this->title_clickable;
                $fields['title_link'] = pSQL($this->title_link);
                $fields['advanced_config'] = $tab->advanced_config ? (bool)$this->advanced_config : false;
                $fields['position'] = (int)$this->position;
                $fields['width'] = $this->width ? (int)$this->width : (int)$tab->column_width;
                $fields['date_upd'] = pSQL($this->date_upd);
                $fields['date_add'] = pSQL($this->date_add);
                $fields['active'] = $this->id ? $this->active : true;
                $fields['border_left_width'] = isset($this->border_left_width) ? (int)$this->border_left_width : (int)$tab->column_border_left_width;
                $fields['border_left_color'] = isset($this->border_left_color) ? pSQL($this->border_left_color) : pSQL($tab->column_border_left_color);
                $fields['with_border_left'] = isset($this->with_border_left) ? (bool)$this->with_border_left : (bool)$tab->column_with_border_left;
                $fields['title_horizontal_line_width'] = isset($this->title_horizontal_line_width) ? (int)$this->title_horizontal_line_width : (int)$tab->column_title_horizontal_line_width;
                $fields['title_horizontal_line_color'] = isset($this->title_horizontal_line_color) ? pSQL($this->title_horizontal_line_color) : pSQL($tab->column_title_horizontal_line_color);
                $fields['title_with_horizontal_line'] = isset($this->title_with_horizontal_line) ? (bool)$this->title_with_horizontal_line : (bool)$tab->column_title_with_horizontal_line;
                $fields['list_font_style_hover'] = isset($this->list_font_style_hover) ? pSQL($this->list_font_style_hover) : pSQL($tab->column_list_font_style_hover);
                $fields['list_font_weight_hover'] = isset($this->list_font_weight_hover) ? pSQL($this->list_font_weight_hover) : pSQL($tab->column_list_font_weight_hover);
                $fields['list_font_color_hover'] = isset($this->list_font_color_hover) ? pSQL($this->list_font_color_hover) : pSQL($tab->column_list_font_color_hover);
                $fields['list_underline_hover'] = (bool)$this->list_underline_hover;
                
		return $fields;
	}
	
        
        public function save($nullValues = false, $autodate = true)
        {
                if (!(int)$this->id_tab)
                        return;
                if (!(int)$this->position)
                        $this->position = $this->getNextPositionOnTab();
                
                if (parent::save($nullValues, $autodate))
                {
                        $this->initMenu();
                        return true;
                }
        }
        
        
        public function toggleActive()
        {
                $this->active = !$this->active;               
                return $this->save();
        }
        
        
        public function delete()
        {
                DB::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'innovativemenu_column_content` WHERE `id_column`='.(int)$this->id);
                parent::delete();
                $this->initMenu();
        }
        
        private function getNextPositionOnTab()
        {
                if (!(int)$this->id_tab)
                        return;
                
                $response = (int)DB::getInstance()->getValue('SELECT MAX(`position`) FROM `'._DB_PREFIX_.'innovativemenu_column` WHERE `id_tab`='.(int)$this->id_tab);
                return $response + 1;
        }
        
        public function saveContent($data = array())
        {
                foreach ($data as $d)
                {
                        $content = explode('_', $d);
                        if (count($content) == 3 AND !DB::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'innovativemenu_column_content`
                                                                                        WHERE `content_id` = '.(int)$content[2].'
                                                                                        AND `id_column` = '.(int)$this->id.'
                                                                                        AND `tab_type` = "'.pSQL($content[0]).'"
                                                                                        AND `column_type` = "'.pSQL($content[1]).'"'))
                        {
                                $array = array('id_column' => (int)$this->id,
                                        'content_id' => (int)$content[2],
                                        'tab_type' => pSQL($content[0]),
                                        'column_type' => pSQL($content[1]),
                                    );
                                Db::getInstance()->autoExecute(_DB_PREFIX_.'innovativemenu_column_content', $array, 'INSERT', '`id_column` = '.(int)$this->id);
                        }
                }
                return true;
        }
        
        
        public function dropAllContent()
        {
                return DB::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'innovativemenu_column_content` WHERE `id_column` = '.(int)$this->id.'');
        }
        
        
        public function deleteContent($data)
        {

                $content = explode('_', $data);
                if (count($content) == 3)
                return DB::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'innovativemenu_column_content`
                                                        WHERE `content_id` = '.(int)$content[2].'
                                                        AND `id_column` = '.(int)$this->id.'
                                                        AND `tab_type` = "'.pSQL($content[0]).'"
                                                        AND `column_type` = "'.pSQL($content[1]).'"');
        }
        
        
        public function buildContent($form = false)
        {
                global $cookie;

                $output = '';
                $response = DB::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'innovativemenu_column_content` WHERE `id_column`='.(int)$this->id);
                if ($response AND count($response))
                {
                        $id_lang = $cookie->id_lang ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT'); 
                        foreach ($response as $data)
                        {
                                switch ($data['column_type']) {
                                        case 'products':
                                                $product = new Product((int)$data['content_id'], (int)$id_lang);
                                                $data['link'] = $product->getLink();
                                                $data['name'] = is_array($product->name) ? $product->name[(int)$id_lang] : $product->name;
                                                break;
                                        
                                        case 'categories' :
                                                $category = new Category((int)$data['content_id'], (int)$id_lang);
                                                $data['link'] = $category->getLink();
                                                $data['name'] = is_array($category->name) ? $category->name[(int)$id_lang] : $category->name;
                                                break;
                                        
                                        case 'manufacturers' :
                                                $manufacturer = new Manufacturer((int)$data['content_id'], (int)$id_lang);
                                                $data['link'] = $manufacturer->getLink();
                                                $data['name'] = is_array($manufacturer->name) ? $manufacturer->name[(int)$id_lang] : $manufacturer->name;
                                                break;
                                        
                                        case 'suppliers' :
                                                $supplier = new Supplier((int)$data['content_id'], (int)$id_lang);
                                                $data['link'] = $supplier->getLink();
                                                $data['name'] = is_array($supplier->name) ? $supplier->name[(int)$id_lang] : $supplier->name;
                                                break;                                        
                                        
                                        case 'link':
                                                $link = new Innovative_Link((int)$data['content_id'], (int)$id_lang);
                                                $data['link'] = $link->link;
                                                $data['name'] = $link->name;
                                                break;

                                        default:
                                                break;
                                        
                                }
                                if($form)
                                        $output .= '<option value="'.$data['tab_type'].'_'.$data['column_type'].'_'.$data['content_id'].'" class="element_removable"
                                                        selected ondblclick="javascript:removeElementOnColumn('.(int)$this->id.', this);">'.Tools::htmlEntitiesUTF8($data['name']).'</option>';
                                else $output[] = $data;
                        
                        }
                        
                }
                return $output;
        }
        
        
        private function addHorizontalLine()
        {
                $tab = new Innovative_Tab($this->id_tab);
                $menu = new Innovative_Menu($tab->id_menu);
                if ($menu->general_configuration)
                {
                        if ($menu->column_title_with_horizontal_line)
                                return '<hr style=" height: '.(int)$menu->column_title_horizontal_line_width.'px; margin-bottom : 5px; display:block;
                                        background-color:#'.Tools::htmlentitiesUTF8($menu->column_title_horizontal_line_color).'; 
                                        color:#'.Tools::htmlentitiesUTF8($menu->column_title_horizontal_line_color).'; 
                                        border-color:#'.Tools::htmlentitiesUTF8($menu->column_title_horizontal_line_color).'; margin-top:0" />';
                }              
                elseif ($tab->advanced_config)
                {
                        if ($this->advanced_config)
                        {
                                if ($this->title_with_horizontal_line)
                                        return '<hr style="height: '.(int)$this->title_horizontal_line_width.'px; margin-bottom : 5px; display:block;
                                        background-color:#'.Tools::htmlentitiesUTF8($this->title_horizontal_line_color).'; 
                                        color:#'.Tools::htmlentitiesUTF8($this->title_horizontal_line_color).'; 
                                        border-color:#'.Tools::htmlentitiesUTF8($this->title_horizontal_line_color).'; margin-top:0" />';
                        }
                        elseif ($tab->column_title_with_horizontal_line)
                                return '<hr style="height: '.(int)$tab->column_title_horizontal_line_width.'px; margin-bottom : 5px; display:block;
                                        background-color:#'.Tools::htmlentitiesUTF8($tab->column_title_horizontal_line_color).'; 
                                        color:#'.Tools::htmlentitiesUTF8($tab->column_title_horizontal_line_color).'; 
                                        border-color:#'.Tools::htmlentitiesUTF8($tab->column_title_horizontal_line_color).'; margin-top:0" />';
                }
                return '';
        }
        
        
        private function addVerticalLine()
        {
                $tab = new Innovative_Tab($this->id_tab);
                $menu = new Innovative_Menu($tab->id_menu);
                if ($menu->general_configuration)
                {
                        if ($menu->column_with_border_left)
                                return 'border-left: solid '.((int)$menu->column_border_left_width).'px #'.Tools::htmlentitiesUTF8($menu->column_border_left_color).';';
                }              
                elseif ($tab->advanced_config)
                {
                        if ($this->advanced_config)
                        {
                                if ($this->with_border_left)
                                        return 'border-left: solid '.((int)$menu->border_left_width).'px #'.Tools::htmlentitiesUTF8($menu->border_left_color).';';
                        }
                        elseif ($tab->column_with_border_left)
                                return 'border-left: solid '.((int)$tab->column_border_left_width).'px #'.Tools::htmlentitiesUTF8($tab->column_border_left_color).';';
                }
                return '';
        }



        public function view($clear = false, $first = false)
        {
                global $cookie;
                
                if (!empty($clear))
                        $first = true;
                
                $tab =  new Innovative_Tab($this->id_tab);
                $id_lang = $cookie->id_lang ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT');
                $data = $this->buildContent();
                
                if (!in_array($this->type, self::$all_types) OR !count($data))
                        return ;
                $output = '<div id="innovative_column_'.$tab->id.'" style="float:left; '.($first ? $clear : $this->addVerticalLine()).'">';
                if ($this->with_title)
                {
                        if ($this->type == 'categories')
                        {
                                $category = new Category($this->id_type);
                                $this->title_link = $category->getLink();
                        }
                        $output .= '
                                <span id = "innovative_column_title_'.$this->id.'" style="margin-bottom:5px; display:block;">'
                                        .($this->title_clickable ? '<a href="'.$this->title_link.'">'.$this->getTitleName().'</a>' : $this->getTitleName()).   
                                '</span>'.$this->addHorizontalLine();
                }

                if (($this->type == 'list' OR $this->type == 'categories') AND is_array($data) AND count($data))
                {                     
                        $output .= '<ul style="display:block; float:left; width:'.((int)$this->getCSSAttribut('width')).'px">';
                        foreach ($data as $value) {
                                $output .= '
                                        <li class="innovative_column_line_'.$this->id.'">
                                                <a href="'.$value['link'].'">
                                                        '.$value['name'].'
                                                </a>
                                        </li>';
                        }
                        $output .= '</ul>';
                }
                elseif ($this->type == 'text')
                {
                        $output .= '
                                <span class="innovative_column_text_'.$this->id.'">
                                        '.$this->text[$id_lang].'
                                </span>';
                }
                
                $output .= '</div>';
                
                return $output;
        }
        
        
        public function css($style = false)
        {
                $tab = new Innovative_Tab($this->id_tab);
                $menu = new Innovative_Menu($tab->id_menu);
                if ($menu->general_configuration)
                        $is_underline = $menu->column_list_underline_hover;
                else {
                        if ($tab->advanced_config)
                        {
                                if ($this->advanced_config)
                                        $is_underline = $this->list_underline_hover;
                                else $is_underline = $tab->column_list_underline_hover;
                        }
                        else $is_underline = false;
                }
                $output = '
                        #innovative_column_title_'.$this->id.' a{
                                margin : 0;
                                padding : 0;
                                color : inherit;
                                font-family:inherit;
                                font-size:inherit;
                                font-weight:inherit;
                                font-style:inherit;
                        }
                        
                        #innovative_column_title_'.$this->id.' {
                                font-style : '.$this->getCSSAttribut('title_font_style').';
                                font-family : '.$this->getCSSAttribut('title_font_family').';
                                font-size : '.(float)$this->getCSSAttribut('title_font_size').'em;
                                font-weight : '.$this->getCSSAttribut('title_font_weight').';
                                color : #'.$this->getCSSAttribut('title_font_color').';
                                '.($tab->getCSSAttribut('column_title_underline') ? 'text-decoration:underline;' : '').'
                        }
                        
                        .innovative_column_line_'.$this->id.' a, .innovative_column_line_'.$this->id.' span{
                                font-style : '.$this->getCSSAttribut('font_style').';
                                font-family : '.$this->getCSSAttribut('font_family').';
                                font-size : '.(int)$this->getCSSAttribut('font_size').'px;
                                font-weight : '.$this->getCSSAttribut('font_weight').';
                                color : #'.$this->getCSSAttribut('font_color').';
                                display:block;
                                width:'.(int)$this->getCSSAttribut('width').'px;
                        }
                        
                        .innovative_column_line_'.$this->id.' p{
                                display:block;
                                width:inherit;
                        }
                        
                        .innovative_column_text_'.$this->id.' *{
                                font-style : '.$this->getCSSAttribut('font_style').';
                                font-family : '.$this->getCSSAttribut('font_family').';
                                font-size : '.(int)$this->getCSSAttribut('font_size').'px;
                                font-weight : '.$this->getCSSAttribut('font_weight').';
                                color : #'.$this->getCSSAttribut('font_color').';
                        }
                        

                        .innovative_column_line_'.$this->id.':hover > a {
                                font-weight: '.$this->getCSSAttribut('list_font_weight_hover').';
                                font-style: '.$this->getCSSAttribut('list_font_style_hover').';
                                color: #'.$this->getCSSAttribut('list_font_color_hover').';
                                '.($is_underline ? 'text-decoration:underline' : '').'
                        }
                ';

                return $style ? '<style>'.$output.'</style>' : $output;
        }
        
        
        public function getCSSAttribut($name)
        {
                $tab = new Innovative_Tab($this->id_tab);
                if ($this->advanced_config AND $tab->advanced_config)
                        return Tools::htmlEntitiesUTF8($this->{$name});
                return $tab->getCSSAttribut('column_'.$name); 
        }
        
        public function hasNext()
        {
                return (int)DB::getInstance()->getValue('SELECT MIN(`position`) FROM `'._DB_PREFIX_.'innovativemenu_column`
                                                WHERE `id_tab`='.(int)$this->id_tab.' AND `position` > '.(int)$this->position);
        }
        
        public function hasPrevious()
        {
                return (int)DB::getInstance()->getValue('SELECT MAX(`position`) FROM `'._DB_PREFIX_.'innovativemenu_column`
                                                WHERE `id_tab`='.(int)$this->id_tab.' AND `position` < '.(int)$this->position);
        }
        

        public function down()
        {
                $tmp = $this->position;
                $this->position = $this->hasPrevious();
                if (!$this->position OR !$this->id_tab)
                        return;

                $column = self::getByPosition ($this->position, $this->id_tab);
                $column->position = $tmp;
                return $column->save() AND $this->save();
                
        }
        
        
        public function up()
        {
                $this->position = $this->hasNext();
                if (!$this->position OR !$this->id_tab)
                        return;
                $column = self::getByPosition ($this->position, $this->id_tab);
                return $column->down();
        }
        
        
        public static function getByPosition($position, $id_tab)
        {
                $id_column = DB::getInstance()->getValue('SELECT `id_column` FROM `'._DB_PREFIX_.'innovativemenu_column`
                                                        WHERE `id_tab`='.(int)$id_tab.' AND `position` = '.(int)$position);
                if($id_column)
                        return new Innovative_Column((int)$id_column);
                return;
        }
        
        
        public function getTitleName()
        {
                global $cookie;
                $id_lang = $cookie->id_lang ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT');
                if ($this->type == 'categories')
                {
                        $category = new Category($this->id_type);
                        $title = $category->name[$id_lang];
                        return empty($this->title[$id_lang]) ? $title : $this->title[$id_lang];
                }
                return empty($this->title[$id_lang]) ? $this->title[(int)Configuration::get('PS_LANG_DEFAULT')] : $this->title[$id_lang];
        }
        
        
        private function initMenu()
        {
                $tab = new Innovative_Tab($this->id_tab);
                $menu = new Innovative_Menu($tab->id_menu);
                $menu->init();
        }
}