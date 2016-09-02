<?php

// For Prestashop 1.5

class InnovativeMenu extends Module
{
	public function __construct()
	{
		$this->name = 'innovativemenu';
                $this->tab = 'front_office_features';
		$this->version = '2.0.14';
		$this->module_key = 'f472c5b5c90daefee3d2568cd4a730ff';
		parent::__construct();		
 
 		 
 /** Backward compatibility */ 
 		//require(_PS_MODULE_DIR_.'/innovativemenu/backward_compatibility/backward.php'); 


		$this->displayName = $this->l('Rich menu');
		$this->description = $this->l('Fully customize your front office menu');
                $this->logo = $this->getBaseURL().'modules/innovativemenu/logo.gif';
                $this->type_of_tabs = array (
                    'null' => '--------------------------',
                    'categories' => $this->l('Categories'),
                    'cms' => $this->l('CMS'),
                    'manufacturers' => $this->l('Manufacturers'),
                    'suppliers' => $this->l('Suppliers'),
                    'link' => $this->l('Personalized link'));
	}
        
        
	public function displayFlags($languages, $default_language, $ids, $id, $return = false, $use_vars_instead_of_ids = false)
	{
		if (sizeof($languages) == 1)
			return false;
		$output = '
			<div class="displayed_flag">
				<img src="../img/l/'.(int)$default_language.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="toggleLanguageFlags(this);" alt="" />
			</div>
			<div id="languages_'.$id.'" class="language_flags">
			'.$this->l('Choose language:').'<br /><br />';
			
			foreach ($languages as $language)
				$output .= '<img src="../img/l/'.(int)($language['id_lang']).'.jpg" class="pointer" alt="'.Tools::htmlEntitiesUTF8($language['name']).'" title="'.Tools::htmlEntitiesUTF8($language['name']).'" onclick="my_changeLanguage(this, '.(int)($language['id_lang']).');" /> ';
			$output .= '</div>';

            return $output;
	}
        
        
        public static function clearMenuCache($id_menu_context = null, $id_lang = null)
	{
                self::loadClasses();
                $module = Module::getInstanceByName('innovativemenu');
                if (empty($id_menu_context))
                {
                         $context_all = Innovative_Context::load(Shop::CONTEXT_ALL, 0)->id;
                         $context_group = Innovative_Context::load(Shop::CONTEXT_GROUP, Shop::getContextShopGroupID())->id;
                         $context_shop = Innovative_Context::load(Shop::CONTEXT_SHOP, Shop::getContextShopID())->id;
                }
                else $context_all = $context_group = $context_shop = 0;

                if (empty($id_lang))
                        foreach (Language::getLanguages(true) AS $lang)
                        {
                                $module->_clearCache('innovativemenu_'.$context_all.'_'.$lang['iso_code'].'.tpl');
                                $module->_clearCache('innovativemenu_'.$context_group.'_'.$lang['iso_code'].'.tpl');
                                $module->_clearCache('innovativemenu_'.$context_shop.'_'.$lang['iso_code'].'.tpl');
                                $module->_clearCache('innovativemenu_'.$id_menu_context.'_'.$lang['iso_code'].'.tpl');
                        }
                else
                {
                        $module->_clearCache('innovativemenu_'.$id_menu_context.'_'.Language::getIsoById($id_lang).'.tpl');
                        $module->_clearCache('innovativemenu_'.$context_all.'_'.Language::getIsoById($id_lang).'.tpl');
                        $module->_clearCache('innovativemenu_'.$context_group.'_'.Language::getIsoById($id_lang).'.tpl');
                        $module->_clearCache('innovativemenu_'.$context_shop.'_'.Language::getIsoById($id_lang).'.tpl');
                }
	}
        
        public function getTypeTraduction($type)
        {
                $array = array (
                    'cms' => $this->l('cms'),
                    'manufacturers' => $this->l('manufacturers'),
                    'suppliers' => $this->l('suppliers'),
                    'categories' => $this->l('categories'),
                    'link' => $this->l('Personalized link')
                );

                return array_key_exists($type, $array) ? $array[$type] : '';
        }
        
        
        public function rmdirRec($dir, $only_content = true)
        {
                if (trim($dir, '/') == trim(_PS_MODULE_DIR_.'innovativemenu/css/fonts/upload', '/'))
                        return 1;
                
                if (is_dir($dir))
                {
                        $handle = opendir($dir);
                        if ($handle !== false)
                                while(false !== ($file = readdir($handle)))
                                        if ($file != '.' AND $file != '..')
                                        {
                                                $file = $dir.'/'.$file;
                                                if (is_dir($file))
                                                        $this->rmdirRec($file, false);
                                                else unlink($file);
                                        }
                }
                if ($only_content)
                        return closedir($handle);
                return rmdir($dir);
        }
        
        
        public static function loadClasses()
        {
                $files = array ('link', 'menu', 'tab', 'column', 'ads', 'font_family', 'context');
                foreach ($files as $file)
                        require_once dirname(__FILE__).'/classes/'.$file.'.php';
        }
        
	public function install()
	{
                $query = array ();
                $response = true;
                include(dirname(__FILE__).'/sql/install.php');
                foreach ($query as $q)
                        $response = ($response AND Db::getInstance()->Execute($q));
                if (!file_exists(_PS_MODULE_DIR_.'innovativemenu/css/fonts/upload/') OR !is_dir(_PS_MODULE_DIR_.'innovativemenu/css/fonts/upload/'))
                        mkdir(_PS_MODULE_DIR_.'innovativemenu/css/fonts/upload/', 0777);
                return $response ? (
                        parent::install()
                        AND $this->registerHook('displayTop')
                        AND $this->registerHook('actionObjectCategoryUpdateAfter')
			AND $this->registerHook('actionObjectCategoryDeleteAfter')
			AND $this->registerHook('actionObjectCmsUpdateAfter')
			AND $this->registerHook('actionObjectCmsDeleteAfter')
			AND $this->registerHook('actionObjectSupplierUpdateAfter')
			AND $this->registerHook('actionObjectSupplierDeleteAfter')
			AND $this->registerHook('actionObjectManufacturerUpdateAfter')
			AND $this->registerHook('actionObjectManufacturerDeleteAfter')
			AND $this->registerHook('actionObjectProductUpdateAfter')
			AND $this->registerHook('actionObjectProductDeleteAfter')
			AND $this->registerHook('categoryUpdate')
			AND $this->registerHook('actionShopDataDuplication')) : false;
	}
	
	public function uninstall()
	{	
                $this->rmdirRec(dirname(__FILE__).'/css/fonts/');
                $this->unregisterHook('displayTop');
                $this->unregisterHook('actionObjectCategoryUpdateAfter');
		$this->unregisterHook('actionObjectCategoryDeleteAfter');
		$this->unregisterHook('actionObjectCmsUpdateAfter');
		$this->unregisterHook('actionObjectCmsDeleteAfter');
		$this->unregisterHook('actionObjectSupplierUpdateAfter');
		$this->unregisterHook('actionObjectSupplierDeleteAfter');
		$this->unregisterHook('actionObjectManufacturerUpdateAfter');
		$this->unregisterHook('actionObjectManufacturerDeleteAfter');
		$this->unregisterHook('actionObjectProductUpdateAfter');
		$this->unregisterHook('actionObjectProductDeleteAfter');
		$this->unregisterHook('categoryUpdate');
		$this->unregisterHook('actionShopDataDuplication');
                
                return (parent::uninstall() 
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_tab`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_tab_lang`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_column`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_column_lang`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_column_content`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_personalized_link_lang`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_personalized_link`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_ads_lang`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_ads`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_context`')
                        AND Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'innovativemenu_font_family`'));
	}
        
        
        public function getListOfCreatedMenu($type_context, $id_element)
        {
                $output = '<a href="javascript:editMenu();">
                                <img src="'._PS_ADMIN_IMG_.'add.gif"/>
                                '.$this->l('Create new menu').'
                        </a>
                        <div class="clear">&nbsp;</div>';
                return $output.'<div class="all_menus">'.$this->getMenus($type_context, $id_element).'</div>';
        }
        
        
        public function deleteMenu($id_menu)
        {
                $menu = new Innovative_Menu((int)$id_menu);
                $menu->delete();
                return $this->getMenus($menu->innovative_context->type_context, $menu->Innovative_context->id_element);
        }
        
        public function toggleActive($type, $id)
        {
                $type = strtolower(trim($type));
                $response = false;
                switch ($type) {
                        case 'menu':
                                $menu = new Innovative_Menu((int)$id);
                                if ($menu->id)
                                        $response = $menu->toggleActive();
                                break;
                                
                        case 'tab':
                                $tab = new Innovative_Tab((int)$id);
                                if ($tab->id)
                                        $response = $tab->toggleActive();
                                break;
                                
                        case 'ads':
                                $ads = new Innovative_Ads((int)$id);
                                if ($ads->id)
                                        $response = $ads->toggleActive();
                                break;
                                
                        case 'column':
                                $column = new Innovative_Column((int)$id);
                                if ($column->id)
                                        $response = $column->toggleActive();
                                break;

                        default:
                                break;
                }
                return $response;
        }
        
        
        /**
         * Provides all menus, according to a context
         * id_element could be id_shop or id_shop_group or 0 (All shops)
         */
        public function getMenus($type_context, $id_element = 0)
        {
                global $smarty;

                if ((($type_context == Shop::CONTEXT_GROUP) OR ($type_context == Shop::CONTEXT_SHOP )) AND !empty($id_element))
                        $response = Db::getInstance()->ExecuteS('
                                SELECT * 
                                FROM `'._DB_PREFIX_.'innovativemenu` `m`
                                LEFT JOIN `'._DB_PREFIX_.'innovativemenu_context` `c` ON `c`.`id_menu_context` = `m`.`id_menu_context`
                                WHERE `id_element`='.(int)$id_element.' AND `type_context`='.(int)$type_context);
  
                
                elseif ($type_context == Shop::CONTEXT_ALL)
                        $response = Db::getInstance()->ExecuteS('
                                SELECT * 
                                FROM `'._DB_PREFIX_.'innovativemenu` `m`
                                LEFT JOIN `'._DB_PREFIX_.'innovativemenu_context` `c` ON `c`.`id_menu_context` = `m`.`id_menu_context`
                                WHERE `type_context`='.(int)$type_context);
                

                $no_active = !$this->getActiveMenu();
                if (!empty($response) AND count($response))
                {
                        $smarty->assign(array('admin_img' => _PS_ADMIN_IMG_, 'response' => $response, 'no_active' => $no_active));
                        return $this->display(__FILE__, 'tpl/get_menus.tpl');
                }
               
        }
        
        
        private function getBaseURL()
        {
                if (!$this->context->shop->domain)
			return false;
		return (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://')
                        .$this->context->shop->domain
                        .$this->context->shop->getBaseURI();
        }
        
        public function addJS()
        {
                global $cookie;
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($defaultLanguage);
                $ad = dirname($_SERVER["PHP_SELF"]);
                
                if(!in_array(basename($ad), scandir(_PS_ROOT_DIR_)))
                        return ; 
                
                if (Shop::getContext() == Shop::CONTEXT_SHOP)
                        $id_element = $this->context->shop->id;
                elseif (Shop::getContext() == Shop::CONTEXT_GROUP)
                        $id_element = $this->context->shop->id_shop_group;
                else $id_element = 0;
                
                $base_url = $this->getBaseURL();
                return '
                        <script type="text/javascript">
                                var url = "'.$base_url.'modules/'.$this->name.'/ajax.php";
                                var iso = "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'" ;
                                var pathCSS = "'._THEME_CSS_DIR_.'" ;
                                var ad = "'.Tools::htmlentitiesUTF8($ad).'" ;
                                var tb_pathToImage = "'.$base_url.'modules/'.$this->name.'/loadingAnimation.gif";
                                var token = "'.Tools::getAdminTokenLite('modules').'";
                                var employee_id_lang = '.$cookie->id_lang.';
                                var url_upload = "'.$base_url.'modules/'.$this->name.'/upload.php";
                                var id_element = '.$id_element.';
                                var type_context = '.(Shop::getContext()).';
                        </script>
                        <script type="text/javascript" src="'.$base_url.'js/tiny_mce/tiny_mce.js"></script>
                        <script type="text/javascript" src="'.$base_url.'js/tinymce.inc.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/'.$this->name.'.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/colorpicker/colorpicker.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/colorpicker/eye.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/colorpicker/utils.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/colorpicker/layout.js?ver=1.0.2"></script>
			<script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/jquery.scrollTo-1.4.2-min.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/fileuploader.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/jquery.ajaxupload.js"></script>
                        <script type="text/javascript" src="'.$base_url.'modules/'.$this->name.'/js/jquery.attach-btn.js"></script>';
        }
        
        
        public function addCSS()
        {           
                $base_url = $this->getBaseURL();
                return '
                        <link rel="stylesheet" type="text/css" href="'.$base_url.'modules/'.$this->name.'/css/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
                        <link rel="stylesheet" href="'.$base_url.'modules/'.$this->name.'/css/colorpicker/colorpicker.css" type="text/css" />
                        <link rel="stylesheet" href="'.$base_url.'modules/'.$this->name.'/css/'.$this->name.'.css" type="text/css" />';                
        }
        
        public function getTabsOfMenu($id_menu)
        {
                global $smarty;
                if (is_numeric($id_menu) AND ((int)$id_menu) > 0)
                {
                        $menu = new Innovative_Menu((int)$id_menu);              
                        $all_tabs = $menu->getTabs(true);
                        if ($all_tabs AND count($all_tabs))
                        {
                                $smarty->assign(array (
                                    'menu' => $menu,
                                    'all_tabs' => $all_tabs,
                                    'admin_img' => _PS_ADMIN_IMG_
                                ));
                                return $this->display(__FILE__, 'tpl/get_tabs_of_menu.tpl');
                        }
                }
        }
        
        
        public function viewMenu($id_menu)
        {
                if (!is_numeric($id_menu))
                        return;
                
                $id_menu = (int)$id_menu;
                $menu = new Innovative_menu($id_menu);
                
                return '<fieldset>
                                <legend>'.$this->l('Preview :').' '.$menu->name.'</legend>'.$menu->view().'
                        </fieldset>';
        }
        
        
        public function editMenu($id_menu = null, $ajax = true)
        {
                global $smarty;
                if (is_numeric($id_menu) AND ((int)$id_menu) > 0)
                        $menu = new Innovative_Menu((int)$id_menu);
                else $menu = new Innovative_Menu();
                
                $text_column_title_font_style = $text_column_title_font_weight = $text_column_title_font_size = $text_column_title_font_family =
                $text_block_font_size = $text_block_font_family = $text_block_font_weight = $text_block_font_style =  
                $text_menu_font_style = $text_menu_font_family = $text_menu_font_size = $text_menu_font_weight = $text_column_list_font_style_hover = $text_column_list_font_weight_hover = '';
                
                foreach (Innovative_Menu::$all_column_title_font_size as $font)
                        $text_column_title_font_size .= '<option value="'.$font.'" '.($font == $menu->column_title_font_size ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $text_column_title_font_weight .= '<option value="'.$font.'" '.($font == $menu->column_title_font_weight ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $text_column_title_font_style .= '<option value="'.$font.'" '.($font == $menu->column_title_font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $text_column_title_font_family .= '<option value="'.$font.'" '.($font == $menu->column_title_font_family ? 'selected' : '').'>'.$font.'</option>';

                foreach (Innovative_Menu::$all_font_style as $font)
                        $text_column_list_font_style_hover .= '<option value="'.$font.'" '.($font == $menu->column_list_font_style_hover ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $text_column_list_font_weight_hover .= '<option value="'.$font.'" '.($font == $menu->column_list_font_weight_hover ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_size as $font)
                        $text_block_font_size .= '<option value="'.$font.'" '.($font == $menu->block_font_size ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $text_block_font_weight .= '<option value="'.$font.'" '.($font == $menu->block_font_weight ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $text_block_font_style .= '<option value="'.$font.'" '.($font == $menu->block_font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $text_block_font_family .= '<option value="'.$font.'" '.($font == $menu->block_font_family ? 'selected' : '').'>'.$font.'</option>';
                
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $text_menu_font_style .= '<option value="'.$font.'" '.($font == $menu->font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $text_menu_font_family .= '<option value="'.$font.'" '.($font == $menu->font_family ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_size as $font)
                        $text_menu_font_size .= '<option value="'.$font.'" '.($font == $menu->font_size ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $text_menu_font_weight .= '<option value="'.$font.'" '.($font == $menu->font_weight ? 'selected' : '').'>'.$font.'</option>';

                
                $default_language = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
                $display_flags = $this->displayFlags($languages, $default_language, 'home_text', 'home_text', true);
                
                $smarty->assign(array(
                        'edit_menu' => true,
                        'menu' => $menu,
                        'tabs_of_menu' => $this->getTabsOfMenu($menu->id),
                        'admin_img' => _PS_ADMIN_IMG_,
                        'text_block_font_style' => self::cleanHTML($text_block_font_style),
                        'text_block_font_family' => self::cleanHTML($text_block_font_family),
                        'text_block_font_weight' => self::cleanHTML($text_block_font_weight),
                        'text_block_font_size' => self::cleanHTML($text_block_font_size),
                        'text_menu_font_style' => self::cleanHTML($text_menu_font_style),
                        'text_menu_font_family' => self::cleanHTML($text_menu_font_family),
                        'text_menu_font_size' => self::cleanHTML($text_menu_font_size),
                        'text_menu_font_weight' => self::cleanHTML($text_menu_font_weight),
                        'text_column_title_font_weight' => self::cleanHTML($text_column_title_font_weight),
                        'text_column_title_font_weight' => self::cleanHTML($text_column_title_font_weight),
                        'text_column_title_font_style' => self::cleanHTML($text_column_title_font_style),
                        'text_column_title_font_size' => self::cleanHTML($text_column_title_font_size),
                        'text_column_title_font_family' => self::cleanHTML($text_column_title_font_family),
                        'text_column_list_font_style_hover' => self::cleanHTML($text_column_list_font_style_hover),
                        'text_column_list_font_weight_hover' => self::cleanHTML($text_column_list_font_weight_hover),
                        'display_flags' => $display_flags,
                        'default_language' => $default_language,
                        'languages' => $languages
                    ));
                
                if ($ajax)
                {
                        $output['configure'] = $this->display(__FILE__, 'tpl/edit_element.tpl');
                        $output['view'] = $this->viewMenu($menu->id);
                }
                else $output = $this->display(__FILE__, 'tpl/edit_element.tpl');
                
                return $output;
        }
        

        public function saveMenu($id_menu, $data, $type_context, $id_element = 0)
        {
                $output = array();
                if (array_key_exists('id_menu', $data) AND  $id_menu == (int)$data['id_menu'])
                        if (is_numeric($id_menu))
                        {
                                if (!array_key_exists('menu_general_configuration', $data))
                                        $data['menu_general_configuration'] = false;
                                if (!array_key_exists('menu_column_title_underline', $data))
                                        $data['menu_column_title_underline'] = false;
                                if (!array_key_exists('menu_with_home_tab', $data))
                                        $data['menu_with_home_tab'] = false;
                                if (!array_key_exists('menu_column_with_border_left', $data))
                                        $data['menu_column_with_border_left'] = false;
                                if (!array_key_exists('menu_column_title_with_horizontal_line', $data))
                                        $data['menu_column_title_with_horizontal_line'] = false;
                                if (!array_key_exists('menu_column_list_underline_hover', $data))
                                        $data['menu_column_list_underline_hover'] = false;
                                
                                $output = false;
                                $menu = ($id_menu == 0 ? new Innovative_Menu() : new Innovative_Menu($id_menu));

                                if ($id_menu == $data['id_menu'])
                                {
                                        foreach ($data as $key=>$value)
                                        {
                                                if ($key != 'id_menu')
                                                {
                                                        $key = trim(str_replace('menu_', '', $key));
                                                        
                                                        if (substr($key, 0, 9) == 'home_text')
                                                        {
                                                                $key = explode('_', $key);
                                                                $menu->home_text[$key[2]] = $value;
                                                        }
							else
							{
		                                                if($key == 'color')
		                                                        $key = trim(str_replace('#', '', $key));
		                                                $menu->{$key} = $value;
							}
                                                }
                                        }
                                        
                                        $menu->innovative_context = Innovative_Context::load($type_context, $id_element);
                                        $menu->save();

                                        $output['all'] = $this->getMenus($menu->innovative_context->type_context, $menu->innovative_context->id_element);
                                        $output['view'] = $this->viewMenu($menu->id);
                                        $output['configure'] = $this->editMenu($menu->id, false);
                                }

                        }

                return $output;
        }
        
        
        public function getColumnsOfTab($id_tab)
        {
                global $smarty;
                $output = '';
                if (is_numeric($id_tab) AND ((int)$id_tab) > 0)
                {
                        $tab = new Innovative_Tab((int)$id_tab);
               
                        $all_columns = $tab->getColumns(true);
                        if ($all_columns AND count($all_columns))
                        {
                                $smarty->assign(array(
                                    'tab' => $tab,
                                    'all_columns' => $all_columns,
                                    'admin_img' => _PS_ADMIN_IMG_,
                                    'default_lang' => (int)(Configuration::get('PS_LANG_DEFAULT'))
                                ));
                                return $this->display(__FILE__, 'tpl/get_columns_of_tab.tpl');
                        }
                }

                return $output;
        }
        
        public function editTab($id_menu, $id_tab = null, $ajax = true)
        {
                global $smarty;
                if (!is_numeric($id_menu))
                        return;

                $menu = new Innovative_Menu((int)$id_menu);
                if (!$menu->id)
                        return;

                if (is_numeric($id_tab))
                        $tab = new Innovative_Tab((int)$id_tab);
                else
                        $tab = new Innovative_Tab();

                $tab->id_menu = $menu->id;
                if (empty($tab->id))
                        $tab->loadDefault();
                $advertising_align = array (
                    'left' => $this->l('left'),
                    'bottom' => $this->l('bottom'),
                    'right' => $this->l('right'),
                    'top' => $this->l('top')
                );
                
                $select_column_font_style = $select_column_font_family = $select_column_font_weight = $select_column_font_size =
                $select_column_title_font_style = $select_column_title_font_family = $select_column_title_font_weight = 
                $select_column_title_font_size = $select_advertising_align = $select_column_list_font_style_hover = $select_column_list_font_weight_hover = '';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $select_column_title_font_style .= '<option value="'.$font.'" '.($font == $tab->column_title_font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $select_column_title_font_family .= '<option value="'.$font.'" '.($font == $tab->column_title_font_family ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $select_column_title_font_weight .= '<option value="'.$font.'" '.($font == $tab->column_title_font_weight ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_column_title_font_size as $font)
                        $select_column_title_font_size .= '<option value="'.$font.'" '.($font == $tab->column_title_font_size ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $select_column_font_style .= '<option value="'.$font.'" '.($font == $tab->column_font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $select_column_font_family .= '<option value="'.$font.'" '.($font == $tab->column_font_family ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $select_column_font_weight .= '<option value="'.$font.'" '.($font == $tab->column_font_weight ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_size as $font)
                        $select_column_font_size .= '<option value="'.$font.'" '.($font == $tab->column_font_size ? 'selected' : '').'>'.$font.'</option>';

                foreach (Innovative_Tab::$all_align as $key=>$value)
                        $select_advertising_align .= '<option value="'.$key.'" '.($value == $tab->ads_align ? 'selected' : '').'>'.$advertising_align[$value].'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $select_column_title_font_family .= '<option value="'.$font.'" '.($font == $tab->column_title_font_family ? 'selected' : '').'>'.$font.'</option>';

                foreach (Innovative_Menu::$all_font_style as $font)
                        $select_column_list_font_style_hover .= '<option value="'.$font.'" '.($font == $tab->column_list_font_style_hover ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $select_column_list_font_weight_hover .= '<option value="'.$font.'" '.($font == $tab->column_list_font_weight_hover ? 'selected' : '').'>'.$font.'</option>';

                
                $output = array();
                if ($tab->save())
                {
                        $part = '';
                        foreach ($this->type_of_tabs as $key => $t_tab)
                                $part .= '<option value="'.$key.'" '.($key == $tab->type ? 'selected' : '').'>'.$t_tab.'</option>';
                        $smarty->assign(array(
                                'edit_tab' => true,
                                'part' => $part,
                                'tab' => $tab,
                                'menu' =>$menu,
                                'has_type' => in_array($tab->type, Innovative_Tab::$type_of_tabs),
                                'admin_img' => _PS_ADMIN_IMG_,
                                'configure_tabs_content' => array_key_exists($tab->type, $this->type_of_tabs) ? $this->configTabType($tab->type, (int)$tab->id_link, $tab->name) : '',
                                'all_columns' => $this->getColumnsOfTab($tab->id),
                                'all_ads' => $this->getAdsOfTab($tab->id),
                                'select_column_title_font_style' => self::cleanHTML($select_column_title_font_style),
                                'select_column_title_font_family' => self::cleanHTML($select_column_title_font_family),
                                'select_cdisplay:block;olumn_title_font_size' => self::cleanHTML($select_column_title_font_size),
                                'select_column_title_font_weight' => self::cleanHTML($select_column_title_font_weight),
                                'select_column_font_style' => self::cleanHTML($select_column_font_style),
                                'select_column_font_family' => self::cleanHTML($select_column_font_family),
                                'select_column_font_weight' => self::cleanHTML($select_column_font_weight),
                                'select_column_font_size' => self::cleanHTML($select_column_font_size),
                                'select_advertising_align' => self::cleanHTML($select_advertising_align),
                                'select_column_list_font_style_hover' => self::cleanHTML($select_column_list_font_style_hover),
                                'select_column_list_font_weight_hover' => self::cleanHTML($select_column_list_font_weight_hover)
                            ));
                        
                        if ($ajax)
                        {
                                $output['configure'] = $this->display(__FILE__, 'tpl/edit_element.tpl');
                                $output['all'] = $this->getTabsOfMenu($menu->id);
                                $output['id_tab'] = $tab->id;
                        }
                        else $output = $this->display(__FILE__, 'tpl/edit_element.tpl');
                        
                }
                return $output;
        }
        
        
        public function saveTab($id_menu, $id_tab, $tab_data = array())
        {
                if(array_key_exists('id_menu', $tab_data)
                        AND array_key_exists('id_tab', $tab_data)
                        AND $tab_data['id_tab'] == $id_tab
                        AND $tab_data['id_menu'] == $id_menu)
                {
                        $tab = new Innovative_Tab((int)$id_tab);
                        if (!array_key_exists('tab_with_ads', $tab_data))
                                $tab_data['tab_with_ads'] = false;
                        if (!array_key_exists('tab_column_title_underline', $tab_data))
                                $tab_data['tab_column_title_underline'] = false;
                        if (!array_key_exists('tab_column_with_border_left', $tab_data))
                                $tab_data['tab_column_with_border_left'] = false;
                        if (!array_key_exists('tab_column_title_with_horizontal_line', $tab_data))
                                $tab_data['tab_column_title_with_horizontal_line'] = false;
                        if (!array_key_exists('tab_column_list_underline_hover', $tab_data))
                                $tab_data['tab_column_list_underline_hover'] = false;

                        foreach ($tab_data as $key=>$value)
                        {
                                if (substr($key, 0, 18) == 'personalized_name_')
                                {
                                        $id_lang = (int)substr($key, -1);
                                        $tab->name[$id_lang] = $value;
                                }
                                
                                if (!in_array($key, array('id_menu', 'id_tab')))
                                {
                                        $key = trim(str_replace('tab_', '', $key));
                                        if ($key == 'type')
                                        {
                                                $tab->type = $value;
                                                switch ($tab->type)
                                                {
                                                        case 'categories' :
                                                                $tab->id_link = array_key_exists('id_category', $tab_data) ? $tab_data['id_category'] : 0;
                                                                break;
                                                        
                                                        case 'cms' :
                                                                $tab->id_link = array_key_exists('id_cms', $tab_data) ? $tab_data['id_cms'] : 0;
                                                                break;
                                                        
                                                        case 'suppliers' :
                                                                $tab->id_link = array_key_exists('id_supplier', $tab_data) ? $tab_data['id_supplier'] : 0;
                                                                break;
                                                        
                                                        case 'manufacturers' :
                                                                $tab->id_link = array_key_exists('id_manufacturer', $tab_data) ? $tab_data['id_manufacturer'] : 0;
                                                                break;
                                                        
                                                        case 'products' :
                                                                $tab->id_link = array_key_exists('id_product', $tab_data) ? $tab_data['id_product'] : 0;
                                                                break;
                                                        
                                                        default :
                                                                $tab->id_link = array_key_exists('id_personalized_link', $tab_data) ? $tab_data['id_personalized_link'] : 0;
                                                }
                                        }
                                
                                        else
                                        {
                                                $tab->{$key} = $value;
                                        }
                                }
                        }
                        if ($tab_data AND count($tab_data) > 10 AND !array_key_exists('tab_advanced_config', $tab_data))
                                $tab->advanced_config = true;

                        $tab->save();
                        $output = array();
                        $output['all'] = $this->getTabsOfMenu($tab->id_menu);
                        $output['view_menu'] = $this->viewMenu($tab->id_menu);
                        $output['configure'] = $this->editTab($tab->id_menu, $tab->id_tab, false);
                        return $output;
                }
                return;
        }
        
        public function deleteTab($id_tab)
        {
                if (is_numeric($id_tab))
                {
                        $tab = new Innovative_Tab((int)$id_tab);
                        $tab->delete();
                        $output = array();
                        $output['all'] = $this->getTabsOfMenu($tab->id_menu);
                        $output['view_menu'] = $this->viewMenu($tab->id_menu);
                        return $output;
                }
        }
        
        
        
        public function moveTabPosition($id_tab, $direction)
        {
                if (is_numeric($id_tab) AND in_array(strtolower($direction), array('up', 'down')))
                {
                        $tab = new Innovative_Tab((int)$id_tab);
                        if (strtolower($direction) == 'up')
                                $tab->up();
                        else $tab->down();
                        
                        return array('view_menu' => $this->viewMenu($tab->id_menu), 'all_tabs' => $this->getTabsOfMenu($tab->id_menu));
                }
                return array();
        }
        
        
        public function editAds($id_tab, $id_ads = NULL)
        {
                global $smarty;
                if(!is_numeric($id_tab))
                        return;
                $id_tab = (int)$id_tab;
                
                if ((int)$id_ads)
                        $ads = new Innovative_Ads($id_ads);
                else $ads = new Innovative_Ads();
                
                $default_language = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
                $display_flags['content'] = $this->displayFlags($languages, $default_language, 'content¤title', 'content', true);
                $display_flags['title'] = $this->displayFlags($languages, $default_language, 'content¤title', 'title', true);
                
                $smarty->assign(array(
                    'edit_ads' => true,
                    'ads' => $ads,
                    'id_tab' => $id_tab,
                    'id_ads' => $id_ads,
                    'languages' => $languages,
                    'display_flags' => $display_flags,
                    'default_language' => $default_language
                ));
                
                return $this->display(__FILE__, 'tpl/edit_element.tpl');
        }
        
        
        
        public function getAdsOfTab($id_tab)
        {
                global $smarty;
                $output = '';
                if (is_numeric($id_tab) AND ((int)$id_tab) > 0)
                {
                        $tab = new Innovative_Tab((int)$id_tab);
               
                        $all_ads = $tab->getAds(true);
                        if ($all_ads AND count($all_ads))
                        {
                                $smarty->assign(array(
                                    'all_ads' => $all_ads,
                                    'tab' => $tab,
                                    'admin_img' => _PS_ADMIN_IMG_,
                                    'default_lang' => $default_language = (int)(Configuration::get('PS_LANG_DEFAULT'))
                                ));
                                return $this->display(__FILE__, 'tpl/get_ads_of_tab.tpl');
                                
                        }
                }
                return $output;
        }
        
        
        public function saveAds($id_tab, $id_ads, $ads_data = array())
        {
                $output = '';
                if(array_key_exists('id_tab', $ads_data)
                        AND array_key_exists('id_ads', $ads_data)
                        AND $ads_data['id_tab'] == $id_tab
                        AND $ads_data['id_ads'] == $id_ads)
                {
                        $ads = new innovative_Ads($id_ads);
                        if ($ads->id AND ($ads->id_tab != $id_tab))
                                return;
                        $ads->id_tab = (int)$id_tab;
                                
                        foreach ($ads_data as $key=>$value)
                        {
                                if (substr($key, 0, 12) == 'ads_content_')
                                {
                                        $key = explode('_', $key);
                                        $ads->content[(int)$key[2]] = $value;
                                }
                                elseif (substr($key, 0, 10) == 'ads_title_')
                                {
                                        $key = explode('_', $key);
                                        $ads->title[(int)$key[2]] = $value;
                                }
                                elseif (!in_array($key, array('id_tab', 'id_ads')))
                                {
                                        $key = trim(str_replace('ads_', '', $key));
                                        $ads->{$key} = $value;
                                }
                        }
                        $ads->save();
                        $tab = new innovative_Tab($ads->id_tab);
                        
                        $output = array();
                        $output['view_menu'] = $this->viewMenu($tab->id_menu);
                        $output['all'] = $this->getAdsOfTab($tab->id);
                }
                return $output;
        }
        
        public function moveColumnPosition($id_column, $direction)
        {
                if (is_numeric($id_column) AND in_array(strtolower($direction), array('up', 'down')))
                {
                        $column = new Innovative_Column((int)$id_column);
                        if (strtolower($direction) == 'up')
                                $column->up();
                        else $column->down();
                        
                        $tab = new Innovative_Tab($column->id_tab);
                        return array ('view_menu' => $this->viewMenu($tab->id_menu), 'all_columns' => $this->getColumnsOfTab($tab->id));
                }
                return array();
        }
        
        
        public function moveAdsPosition($id_ads, $direction)
        {
                if (is_numeric($id_ads) AND in_array(strtolower($direction), array('up', 'down')))
                {
                        $ads = new Innovative_Ads((int)$id_ads);
                        if (strtolower($direction) == 'up')
                                $ads->up();
                        else $ads->down();
                        
                        $tab = new Innovative_Tab($ads->id_tab);
                        return array ('view_menu' => $this->viewMenu($tab->id_menu), 'all_ads' => $this->getAdsOfTab($tab->id));
                }
                return array();
        }
        
        
        public function editColumn($id_tab, $id_column = null)
        {
                global $smarty;
                if (!is_numeric($id_tab))
                        return;
                
                $tab = new Innovative_tab((int)$id_tab);
                if (!$tab->id)
                        return;
                
                if (is_numeric($id_column))
                        $column = new Innovative_Column((int)$id_column);
                else $column = new Innovative_Column();
               
                $column->id_tab = (int)$tab->id;
                $column->loadDefault();
                $output = '';
                
                $select_font_style = $select_font_family = $select_font_weight = $select_font_size =
                $select_title_font_style = $select_title_font_family = $select_title_font_weight = $select_title_font_size = 
                $select_list_font_style_hover = $select_list_font_weight_hover = '';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $select_title_font_style .= '<option value="'.$font.'" '.($font == $column->title_font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $select_title_font_family .= '<option value="'.$font.'" '.($font == $column->title_font_family ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $select_title_font_weight .= '<option value="'.$font.'" '.($font == $column->title_font_weight ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_column_title_font_size as $font)
                        $select_title_font_size .= '<option value="'.$font.'" '.($font == $column->title_font_size ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $select_font_style .= '<option value="'.$font.'" '.($font == $column->font_style ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Font_Family::get(true) as $font)
                        $select_font_family .= '<option value="'.$font.'" '.($font == $column->font_family ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $select_font_weight .= '<option value="'.$font.'" '.($font == $column->font_weight ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_size as $font)
                        $select_font_size .= '<option value="'.$font.'" '.($font == $column->font_size ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_style as $font)
                        $select_list_font_style_hover .= '<option value="'.$font.'" '.($font == $column->list_font_style_hover ? 'selected' : '').'>'.$font.'</option>';
                
                foreach (Innovative_Menu::$all_font_weight as $font)
                        $select_list_font_weight_hover .= '<option value="'.$font.'" '.($font == $column->list_font_weight_hover ? 'selected' : '').'>'.$font.'</option>';
                
                if ($column->save())
                {
                        if ($column->type == Innovative_Column::$all_types['text'])
                                $innovative_column_content = $this->getColumnTypeText($column->id);
                        elseif ($column->type == Innovative_Column::$all_types['categories'])
                                $innovative_column_content = $this->getColumnTypeCategories($tab->id, $column->id, true, $column->id_type);
                        else $innovative_column_content = $this->getColumnTypeList($tab->id, $column->id);
                        
                        $default_language = (int)(Configuration::get('PS_LANG_DEFAULT'));
                        $languages = Language::getLanguages();
                        $divLangName = 'column-'.$column->id.'-title';
                        $smarty->assign(array(
                                'edit_column' => true,
                                'column' => $column,
                                'tab' => $tab,
                                'innovative_column_content' => $innovative_column_content,
                                'select_title_font_style' => self::cleanHTML($select_title_font_style),
                                'select_title_font_family' => self::cleanHTML($select_title_font_family),
                                'select_title_font_weight' => self::cleanHTML($select_title_font_weight),
                                'select_title_font_size' => self::cleanHTML($select_title_font_size),
                                'select_font_style' => self::cleanHTML($select_font_style),
                                'select_font_family' => self::cleanHTML($select_font_family),
                                'select_font_weight' => self::cleanHTML($select_font_weight),
                                'select_font_weight' => self::cleanHTML($select_font_weight),
                                'select_font_size' => self::cleanHTML($select_font_size),
                                'select_list_font_weight_hover' => self::cleanHTML($select_list_font_weight_hover),
                                'select_list_font_style_hover' => self::cleanHTML($select_list_font_style_hover),
                                'display_flags' => $this->displayFlags($languages, $default_language, $divLangName, $divLangName, true),
                                'default_language' => $default_language,
                                'all_types' => Innovative_Column::$all_types
                        ));
                        
                        return $this->display(__FILE__, 'tpl/edit_element.tpl');                        
                }
                return $output;
        }
        
        
        public function saveColumn($id_tab, $id_column, $column_data = array())
        {
                if (array_key_exists('id_tab', $column_data) AND array_key_exists('id_column', $column_data)
                        AND $column_data['id_tab'] == $id_tab AND $column_data['id_column'] == $id_column)
                {
                        $column = new Innovative_Column((int)$id_column);
                        if ($column_data['column_type'] == 'categories' AND $column_data['column_id_type'] != $column->id_type)
                                $column->dropAllContent();
                        
                        if (!array_key_exists('column_list_underline_hover', $column_data))
                                $column_data['column_list_underline_hover'] = false;
                        
                        foreach ($column_data as $key=>$value)
                        {
                                if (!in_array($key, array('id_column', 'id_tab')))
                                {
                                        $key = trim(str_replace('column_', '', $key));
                                        if (substr($key, 0, 4) == 'text')
                                        {
                                                $key = explode('_', $key);
                                                $column->text[$key[1]] = $value;
                                        }
                                        elseif (substr($key, 0, 5) == 'title' AND !in_array($key, array('title_clickable', 'title_link')))
                                        {
                                                $key = explode('_', $key);
                                                $column->title[$key[1]] = $value;
                                        }
                                        elseif ($key == 'content_'.$column->id)
                                                $column->saveContent($value);
                                        else
                                                $column->{$key} = $value;
                                }
                        }

                        $column->save();
                        $tab = new Innovative_Tab($column->id_tab);
                        
                        $output = array();
                        $output['view_menu'] = $this->viewMenu($tab->id_menu);
                        $output['all'] = $this->getColumnsOfTab($tab->id);
                        $output['configure'] = $this->editColumn($column->id_tab, $column->id);
                        
                        return $output;
                }  
        }
        
        
        public function deleteColumn($id_column)
        {
                if (is_numeric($id_column))
                {
                        $column = new Innovative_Column((int)$id_column);
                        $tab = new Innovative_Tab($column->id_tab);
                        $column->delete();
                        
                        $output = array();
                        $output['view_menu'] = $this->viewMenu($tab->id_menu);
                        $output['all'] = $this->getColumnsOfTab($tab->id);
                        
                        return $output;
                }
        }
        
        
        public function deleteAds($id_ads)
        {
                if (is_numeric($id_ads))
                {
                        $ads = new Innovative_Ads((int)$id_ads);
                        $tab = new Innovative_Tab($ads->id_tab);
                        $ads->delete();
                        
                        $output = array();
                        $output['view_menu'] = $this->viewMenu($tab->id_menu);
                        $output['all'] = $this->getAdsOfTab($tab->id);
                        
                        return $output;
                }
        }
        
        
        public function removeElementOnColumn($id_column, $data_element)
        {
                if ((int)$id_column)
                {
                        $column = new Innovative_Column((int)$id_column);
                        return $column->deleteContent($data_element);
                }
        }
        
        public function getColumnTypeList($id_tab, $id_column)
        {
                global $cookie;
                $output = false;
                $tab = new Innovative_Tab((int)$id_tab);
                $column = new Innovative_Column((int)$id_column);
                $output = '';
                if ($tab->id)
                {         
                        $exception = 0;
                        if($tab->type == 'link')
                                $exception = $tab->id_link;
                        
                        if (Shop::getContext() == Shop::CONTEXT_SHOP)
                        {
                                $type_context = Shop::CONTEXT_SHOP;
                                $id_element = Shop::getContextShopID();
                        }
                        elseif (Shop::getContext() == Shop::CONTEXT_GROUP)
                        {
                                $type_context = Shop::CONTEXT_GROUP;
                                $id_element = Shop::getContextShopGroupID();
                        }
                        else
                        {
                                $type_context = Shop::CONTEXT_ALL;
                                $id_element = 0;
                        }
                        
                        
                        $links = Innovative_Link::get($cookie->id_lang);
                        $output_links = '<optgroup label="'.$this->l('Personalized links').'">';
                        if (is_array($links) AND !empty($links))
                                foreach ($links as $link)
                                {
                                        $output_links .= ($exception == $link['id_link'] ? '': '<option value="'.strtolower($tab->type).'_link_'.Tools::htmlentitiesUTF8($link['id_link']).'"
                                                ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')">'.Tools::htmlentitiesUTF8($link['name']).'</option>');
                                }
                        $output_links .= '</optgroup>';
                        
                        switch ($tab->type) {
                                case 'categories':
                                        $categories = Category::getChildren($tab->id_link, $cookie->id_lang);
                                        $parent = new Category($tab->id_link, $cookie->id_lang);
                                        $output = '<optgroup label="'.$this->l('Subcategories').'">';
                                        if (count($categories))
                                                foreach ($categories as $category)
                                                {
                                                        $output .= '<option value="categories_categories_'.(int)$category['id_category'].'"
                                                                ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')" >'.Tools::htmlentitiesUTF8($category['name']).'</option>';
                                                }
                                        $output .= '</optgroup>';
                                        
                                        $products = $parent->getProducts($cookie->id_lang, 1, 100);
                                        $output .= '<optgroup label="'.$this->l('Products').'">';
                                        if (is_array($products) AND count($products))
                                                foreach ($products as $product)
                                                {
                                                        $output .= '<option value="categories_products_'.(int)$product['id_product'].'"
                                                                ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')">'.Tools::htmlentitiesUTF8($product['name']).'</option>';
                                                }
                                        $output .= '</optgroup>';
                                        break;
                                
                                case 'suppliers':
                                        $products = Supplier::getProducts($tab->id_link, $cookie->id_lang, 1, 100);
                                        $output = '<optgroup label="'.$this->l('Products').'">';
                                        if (count($products))
                                                foreach ($products as $product)
                                                {
                                                        $output .= '<option value="suppliers_products_'.(int)$product['id_product'].'"
                                                                ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')">'.Tools::htmlentitiesUTF8($product['name']).'</option>';
                                                }
                                        $output .= '</optgroup>';
                                        break;
                                
                                case 'cms':
                                        break;
                                
                                case 'manufacturers':
                                        $products = Manufacturer::getProducts($tab->id_link, $cookie->id_lang, 1, 100);
                                        $output = '<optgroup label="'.$this->l('Products').'">';
                                        if (count($products))
                                                foreach ($products as $product)
                                                {
                                                        $output .= '<option value="manufacturers_products_'.(int)$product['id_product'].'"
                                                                ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')">'.Tools::htmlentitiesUTF8($product['name']).'</option>';
                                                }
                                        $output .= '</optgroup>';
                                        break;
                                
                                case 'products':
                                        break;

                                default:
                                        break;
                        }
                        
                        $output = '<label>'.$this->l('Edit list of items').'</label>
                                <div><select name="column_content_'.$id_column.'" multiple="multiple" size="10" id="column_content_'.$id_column.'" style="width: 300px; padding:10px;">
                                        '.($column->id ? self::cleanHTML($column->buildContent(true)) : '').'
                                </select>
                                <select size="10" class="column_default" id="column_default_'.$id_column.'" style="width: 300px; padding:10px;">
                                        '.$output.'
                                        '.$output_links.'
                                </select></div><div class="margin-form">'.$this->l('Edit the contents of the column. Double click to add or delete a line from column.').'</div>';
                }
                return $output;
        }
        
        
        
        public function getColumnTypeCategories($id_tab, $id_column, $choice_of_category = true, $id_category = NULL)
        {
                global $cookie;
                $tab = new Innovative_Tab((int)$id_tab);
                $column = new Innovative_Column((int)$id_column);

                $output_select = '';
                if ($tab->id AND $tab->type == 'categories')
                {       
                        $categories = Category::getChildren($tab->id_link, $cookie->id_lang);
                        if (empty($id_category) AND !empty($categories[0]['id_category']))
                                $id_category = $categories[0]['id_category'];
                        
                        if (!empty($id_category))
                        {
                                $exception = 0;
                                if ($tab->type == 'link')
                                        $exception = $tab->id_link;
                        
                                $links = Innovative_Link::get($cookie->id_lang);
                                $output_links = '<optgroup label="'.$this->l('Personalized links').'">';
                                if (is_array($links) AND !empty($links))
                                        foreach ($links as $link)
                                                $output_links .= ($exception == $link['id_link'] ? '': '<option value="'.strtolower($tab->type).'_link_'.Tools::htmlentitiesUTF8($link['id_link']).'"
                                                        ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')">'.Tools::htmlentitiesUTF8($link['name']).'</option>');
                                $output_links .= '</optgroup>';
                        
                                $categories = Category::getChildren($id_category, $cookie->id_lang);
                                $parent = new Category($id_category, $cookie->id_lang);
                                $output_select = '<optgroup label="'.$this->l('Subcategories').'">';
                                if (count($categories))
                                        foreach ($categories as $category)
                                        {
                                                $output_select .= '<option value="categories_categories_'.(int)$category['id_category'].'"
                                                        ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')" >'.Tools::htmlentitiesUTF8($category['name']).'</option>';
                                        }
                                $output_select .= '</optgroup>';
                                        
                                $products = $parent->getProducts($cookie->id_lang, 1, 100);
                                $output_select .= '<optgroup label="'.$this->l('Products').'">';
                                if (is_array($products) AND count($products))
                                        foreach ($products as $product)
                                        {
                                                $output_select .= '<option value="categories_products_'.(int)$product['id_product'].'"
                                                        ondblclick="javascript:addElementOnColumn(this, '.(int)$id_tab.', '.(int)$id_column.')">'.Tools::htmlentitiesUTF8($product['name']).'</option>';
                                        }
                                $output_select .= '</optgroup>';
                                

                                $output_select = '<label>'.$this->l('Edit list of items').'</label>
                                        <div><select name="column_content_'.$id_column.'" multiple="multiple" size="10" id="column_content_'.$id_column.'" style="width: 300px; padding:10px;">
                                                '.($column->id ? $column->buildContent(true) : '').'
                                        </select>
                                        <select size="10" class="column_default" id="column_default_'.$id_column.'" style="width: 300px; padding:10px;">
                                                '.$output_select.'
                                                '.$output_links.'
                                        </select></div><div class="margin-form">'.$this->l('Edit the contents of the column. Double click to add or delete a line from column.').'</div>';
                        }
                        
                        if ($choice_of_category)
                        {
                                $categories = Category::getChildren($tab->id_link, $cookie->id_lang);
                                $parent = new Category($tab->id_link, $cookie->id_lang);
                                $output_choice = '
                                        <select name="column_id_type" onChange="javascript:changeColumnCategory('.(int)$id_tab.', '.(int)$id_column.', $(this).val())">';
                                if (is_array($categories) AND !empty($categories))
                                        foreach ($categories AS $category)
                                                $output_choice .= '
                                                        <option value="'.(int)$category['id_category'].'">'
                                                                .Tools::htmlentitiesUTF8($category['name']).'
                                                        </option>';
                                $output_choice .= '</select><div class="clear">&nbsp;</div>';
                                
                                return '<label>'.$this->l('Choose category').'</label>
                                        '.$output_choice.'
                                        <div id="innovative_column_'.(int)$id_column.'_category">'.$output_select.'</div>
                                        <div class="clear">&nbsp;</div>';
                        }
                                
                        return $output_select;
                        
                                                        
                }
                
        }
        
        
        public function getColumnTypeText($id_column)
        {
                $output = false;
                $column = new Innovative_Column((int)$id_column);
                
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$divLangName = 'column_text';
                
                $output = '<label>'.$this->l('Edit text').'</label>';
                foreach ($languages AS $language)
                {
                        $output .= '
                                <div class="multilangs_fields" id="column_text_'.$language['id_lang'].'" style="display:'.($defaultLanguage == (int)$language['id_lang'] ? 'block' : 'none').'">
                                        <textarea class="rte" name="column_text_'.$language['id_lang'].'">
                                                '.(is_array($column->text) ? self::cleanHTML($column->text[$language['id_lang']]) : '').'
                                        </textarea>
                                </div>';
                }
                
                $output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'column_text', true);
                $output .= '<div class="clear">&nbsp;</div>';
                
                return $output;
        }
        
        
        public function changeColumnType($id_tab, $id_column, $value)
        {
                if (strtolower($value) == 'text')
                        return $this->getColumnTypeText($id_column);
                elseif (strtolower($value) == 'categories')
                        return $this->getColumnTypeCategories($id_tab, $id_column);
                return $this->getColumnTypeList($id_tab, $id_column);
        }
        
        public function configTabType($type = 'categories', $id_type = NULL, $name = array())
        {
                if (!array_key_exists($type, $this->type_of_tabs))
                        return ;
                switch ($type) {
                        case 'manufacturers' :
                                return $this->configTabManufacturers($id_type, $name);                                
                        case 'cms':
                                return $this->configTabCMS($id_type, $name);                                
                        case 'suppliers' :
                                return $this->configTabSuppliers($id_type, $name);                                
                        case 'products' :
                                return $this->configTabProducts($id_type, $name);                                
                        case 'categories' :
                                return $this->configTabCategories($id_type, $name);                               
                        default:
                                return $this->configPersonalizedLink($id_type, $name);
                }
        }
        
        
        private function configPersonalizedName($name = array())
        {
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$divLangName = 'personalized_name';
                
                $form = '<label>'.$this->l('Personalized name').'</label>';
                foreach ($languages AS $language)
                {
                        $form .= '
                        <div class="multilangs_fields" id="personalized_name_'.(int)$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
                                <input type="text" name="personalized_name_'.(int)$language['id_lang'].'" size=60
                                        value="'.(array_key_exists($language['id_lang'], $name) ? $name[$language['id_lang']] : '').'"/>
                        </div>';
                }
                
                $form .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'personalized_name', true);
                
                return $form;
        }
        
        
        private function configTabManufacturers($id_manufacturer = NULL, $name = array())
        {
                global $cookie;
                $manufacturers = Manufacturer::getManufacturers(false, $cookie->id_lang);
                $output = '';
                if (count($manufacturers))
                {
                        $output = '
                                <label>'.$this->l('Manufacturers').'</label>
                                        <select name="id_manufacturer">';
                        foreach ($manufacturers as $manufacturer)
                                $output .= '<option value="'.(int)$manufacturer['id_manufacturer'].'" '.($manufacturer['id_manufacturer'] == (int)$id_manufacturer ? 'selected' : '').'>'.Tools::htmlEntitiesUTF8($manufacturer['name']).'</option>';
                        $output .= '</select><div class="clear">&nbsp;</div>';
                }
                
                return $output.$this->configPersonalizedName($name);
        }        
        
        
        private function configTabCMS($id_cms = NULL, $name = array())
        {
                global $cookie;
                $cms = CMS::listCms($cookie->id_lang);
                $output = '';
                if (count($cms))
                {
                        $output = '
                                <label>'.$this->l('CMS').'</label>
                                        <select name="id_cms">';
                        foreach ($cms as $c)
                                $output .= '<option value="'.(int)$c['id_cms'].'" '.($c['id_cms'] == (int)$id_cms ? 'selected' : '').'>'.Tools::htmlEntitiesUTF8($c['meta_title']).'</option>';
                        $output .= '</select><div class="clear">&nbsp;</div>';
                }
                
                return $output.$this->configPersonalizedName($name);
        }
        
       
        private function configTabSuppliers($id_supplier = NULL, $name = array())
        {
                global $cookie;
                $suppliers = Supplier::getSuppliers(false, $cookie->id_lang);
                $output = '';
                if (count($suppliers))
                {
                        $output = '
                                <label>'.$this->l('Suppliers').'</label>
                                        <select name="id_supplier">';
                        foreach ($suppliers as $supplier)
                                $output .= '<option value="'.(int)$supplier['id_supplier'].'" '.($supplier['id_supplier'] == (int)$id_supplier ? 'selected' : '').'>'.Tools::htmlEntitiesUTF8($supplier['name']).'</option>';
                        $output .= '</select><div class="clear">&nbsp;</div>';
                }
                
                return $output.$this->configPersonalizedName($name);
        }
        
        
        private function configTabCategories($id_category = NULL, $name = array())
        {
                global $cookie;
                $output = '';
		$categories = Category::getSimpleCategories($cookie->id_lang);

                if (count($categories))
                {
                        $output = '
                                <label>'.$this->l('Categories').'</label>
                                        <select name="id_category">';
                        foreach ($categories as $category)
                                $output .= '<option value="'.(int)$category['id_category'].'" '.($category['id_category'] == (int)$id_category ? 'selected' : '').'>'.Tools::htmlEntitiesUTF8($category['name']).'</option>';
                        $output .= '</select><div class="clear">&nbsp;</div>';
                }
                
                return $output.$this->configPersonalizedName($name);
        }
        
        
        private function getListOfPersonalizedLink($html = true)
        {
                global $cookie;
                $links = Innovative_Link::get($cookie->id_lang);
                if (!$html)
                        return $links;
                $output = '';
                if (!empty($links) AND is_array($links))
                {
                        $output = '
                                <table class="table" cellspacing=0 cellpadding=0>
                                        <thead>
                                                <tr>
                                                        <th>'.$this->l('Id').'</th>
                                                        <th>'.$this->l('Name').'</th>
                                                        <th>'.$this->l('Link').'</th>
                                                        <th>'.$this->l('Action').'</th>
                                                </tr>
                                        </thead>
                                        <tbody>';
                        foreach ($links as $link)
                                $output .= '
                                        <tr>
                                                <td>'.(int)$link['id_link'].'</td>
                                                <td>'.Tools::htmlEntitiesUTF8($link['name']).'</td>
                                                <td>'.Tools::htmlEntitiesUTF8($link['link']).'</td>
                                                <td>
                                                        <table>
                                                                <tr>
                                                                        <td>
                                                                                <a href="javascript:editLink('.(int)$link['id_link'].');">
                                                                                                <img src="'._PS_ADMIN_IMG_.'edit.gif" alt="'.$this->l('Edit').'"/>
                                                                                </a>
                                                                        </td>
                                                                        <td>
                                                                                <a href="javascript:deleteLink('.(int)$link['id_link'].');">
                                                                                                <img src="'._PS_ADMIN_IMG_.'delete.gif" alt="'.$this->l('Delete').'"/>
                                                                                </a>
                                                                        </td>
                                                                </tr>
                                                        </table>
                                                </td>
                                        </tr>';
                        
                        return $output.'</tbody></table>';
                }
                
                return false;
        }

        
        public function configPersonalizedLink($id_link)
        {
                global $cookie;
                $links = Innovative_Link::get($cookie->id_lang);
                $output = '';
                if (count($links))
                {
                        $output = '
                                <label>'.$this->l('Personalized Links').'</label>
                                        <select name="id_link">';
                        foreach ($links as $link)
                                $output .= '<option value="'.(int)$link['id_link'].'" '.($link['id_link'] == (int)$id_link ? 'selected' : '').'>'.Tools::htmlEntitiesUTF8($link['name']).'</option>';
                        
                        return $output.'</select><div class="clear">&nbsp;</div>';
                }
                
                return false;
        }
        
        
        public function editLink($id_link = NULL)
        {
                if (is_numeric($id_link))
                        $link = new Innovative_Link((int)$id_link);
                else $link = new Innovative_Link();
                
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$divLangName = 'link_name';
                
                $form_link = '<label>'.$this->l('Name').'</label>';
                foreach ($languages AS $language)
                {
                        $form_link .= '
                        <div class="multilangs_fields" id="link_name_'.(int)$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
                                <input type="text" name="link_name_'.(int)$language['id_lang'].'" size=60 value="'.($link->id ? $link->name[$language['id_lang']] : '').'"/>
                        </div>';
                }
                
                $form_link .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'link_name', true).'
                        <input type="hidden" name="id_link" value="'.($link->id ? $link->id : 0).'"/>
                        <div class="clear">&nbsp;</div>
                        <label>'.$this->l('Link').'</label>
                        
                        <input type="text" name="link_link" size=60 value="'.($link->id ? Tools::htmlEntitiesUTF8($link->link) : '').'"/>
                        <div class="clear">&nbsp;</div>
                        
                        <input style="margin:15px 45%;;" class="button" type="button" value="'.$this->l('Save link').'" onClick="javascript:saveLink()">';
                 
                 return $form_link;
        }
        
        
        public function saveLink($link_data)
        {
                $languages = Language::getLanguages();
                if ((int)$link_data['id_link'])
                        $link = new Innovative_Link($link_data['id_link']);
                else $link = new Innovative_Link();
                
                foreach ($languages AS $language)
                        $link->name[$language['id_lang']] = $link_data['link_name_'.$language['id_lang']];

                $link->link = $link_data['link_link'];
                
                if ($link->save())
                        return $this->editLink();
                return;
        }
        
        
        public function deleteLink($id_link)
        {
                $link = new Innovative_Link($id_link);
                return $link->delete();
        }
        
        
        public function getLinks()
        {
                return $this->getListOfPersonalizedLink($html = true);
        }
        
        
        public function getContent()
        {
                $dir = _PS_MODULE_DIR_.'innovativemenu';
                if (!is_writable($dir))
                {
                        $result = @chmod($dir, 0777);
                        if (!$result)
                                $result = @chmod($dir, 0755);
                        
                        if (!$result)
                                return $this->displayError($dir.' : '.$this->l('Impossible to write in this file. It is necessary to change the permissions to use this module.'));               
                }

                global $smarty;
                self::loadClasses();
                Configuration::updateValue('INNOVATIVE_TOKEN', Tools::getAdminTokenLite('modules'));
                
                if (Shop::getContext() == Shop::CONTEXT_SHOP)
                        $id_element = $this->context->shop->id;
                elseif (Shop::getContext() == Shop::CONTEXT_GROUP)
                        $id_element = $this->context->shop->id_shop_group;
                else $id_element = 0;
                
                $smarty->assign(array(
                                'all_links' => $this->getListOfPersonalizedLink($html = true),
                                'all_font_families' => $this->getListOfFontFamilies($html = true),
                                'link_form' => $this->editLink(),
                                'all_menus' => $this->getListOfCreatedMenu(Shop::getContext(), $id_element),
                                'logo' => $this->logo,
                                'display_name' => $this->displayName,
                                'description' => $this->description,
                                'user_guide' => $this->getUserGuide(),
                                'module_img' => _MODULE_DIR_.'innovativemenu/images/'
                        ));
                
                return $this->addJS()
                        .$this->addCSS()
                        .$this->display(__FILE__, 'tpl/get_content.tpl');
        }
        
        
        public function manageLinks()
        {
                global $smarty;
                $smarty->assign(array(
                    'all_links' => $this->getListOfPersonalizedLink($html = true),
                    'link_form' => $this->editLink()
                ));
                
                return $this->display(__FILE__, 'tpl/manage_links.tpl');
        }
        
        
        public function manageFontsFamilies()
        {
                global $smarty;
                $smarty->assign(array(
                    'all_fonts' => $this->getListOfFontFamilies($html = true),
                    'font_form' => $this->editFont()
                ));
                
                return $this->display(__FILE__, 'tpl/manage_fonts.tpl');
        }
        
        
        public function manageMenus($type_context, $id_element)
        {
                global $smarty;
                $smarty->assign(array(
                    'all_menus' => $this->getListOfCreatedMenu($type_context, $id_element)
                ));
                
                return $this->display(__FILE__, 'tpl/manage_menus.tpl');
        }
        
        
        public function getUserGuide()
        {
                return $this->display(__FILE__, 'tpl/user_guide.tpl');
        }
        
        
        public function getFonts()
        {
                return $this->getListOfFontFamilies($html = true);
        }
        
        
        private function getListOfFontFamilies($html = true)
        {
                $fonts = Innovative_Font_Family::get();
                if (!$html)
                        return $fonts;
                $output = '';
                if (is_array($fonts) AND count($fonts))
                {
                        $output = '
                                <table class="table" cellspacing=0 cellpadding=0>
                                        <thead>
                                                <tr>
                                                        <th>'.$this->l('Id').'</th>
                                                        <th>'.$this->l('Name').'</th>
                                                        <th>'.$this->l('With file').'</th>
                                                        <th>'.$this->l('Action').'</th>
                                                </tr>
                                        </thead>
                                        <tbody>';
                        
                        foreach ($fonts as $font)
                        {
                                if (!empty($font['alt_name1']))
                                        $font['name'] .= ', '.$font['alt_name1'];
                                if (!empty($font['alt_name2']))
                                        $font['name'] .= ', '.$font['alt_name2'];
                                
                                $output .= '
                                        <tr>
                                                <td>'.(int)$font['id_font'].'</td>
                                                <td>'.Tools::htmlEntitiesUTF8($font['name']).'</td>
                                                <td>'.($font['with_file'] ? $this->l('Yes') : $this->l('No')).'</td>
                                                <td>
                                                        <table>
                                                                <tr>
                                                                        <td>
                                                                                <a href="javascript:editFont('.(int)$font['id_font'].');">
                                                                                                <img src="'._PS_ADMIN_IMG_.'edit.gif" alt="'.$this->l('Edit').'"/>
                                                                                </a>
                                                                        </td>
                                                                        <td>
                                                                                <a href="javascript:deleteFont('.(int)$font['id_font'].');">
                                                                                                <img src="'._PS_ADMIN_IMG_.'delete.gif" alt="'.$this->l('Delete').'"/>
                                                                                </a>
                                                                        </td>
                                                                </tr>
                                                        </table>
                                                </td>
                                        </tr>';
                        }
                        return $output.'</tbody></table>';
                }
                
                return false;
        }
        
        
        public function editFont($id_font = NULL)
        {
                if (is_numeric($id_font))
                        $font = new Innovative_Font_Family((int)$id_font);
                else $font = new Innovative_Font_Family();

                $form_font = '
                        <input type="hidden" name="id_font" value="'.($font->id ? $font->id : 0).'"/>
                        <div class="clear">&nbsp;</div>
                        
                        <label>'.$this->l('Name').'</label>
                        <input type="text" name="font_name" size=60 value="'.($font->id ? Tools::htmlEntitiesUTF8($font->name) : '').'"/>
                        <div class="clear">&nbsp;</div>
                        
                        <label>'.$this->l('First alternative').'</label>
                        <input type="text" name="font_alt_name1" size=60 value="'.($font->id ? Tools::htmlEntitiesUTF8($font->alt_name1) : '').'"/>
                        <div class="clear">&nbsp;</div>
                        
                        <label>'.$this->l('Second alternative').'</label>
                        <input type="text" name="font_alt_name2" size=60 value="'.($font->id ? Tools::htmlEntitiesUTF8($font->alt_name2) : '').'"/>
                        <div class="clear">&nbsp;</div>
                        
                        <div class="clear">&nbsp;</div>
                        <label>'.$this->l('Add Zip file ?').'</label>
                        <input type="checkbox" id="with_file" name="font_with_file" onClick="javascript:toggleDiv($(\'#with_file\'), $(\'#font_zip_file\'))"/>

                        <div id="font_zip_file" class="container upload_zip" style="display:none;">
                                <div class="clear">&nbsp;</div>
                                <label><a href="#" id="ajax-upload-2" class="btn">
                                        <b>'.$this->l('Click to upload').'</b>
                                        <img src="'._PS_ADMIN_IMG_.'arrow_up.png" alt=""> 
                                </a></label>
                                <br/>
                        </div>
                        
                        <input style="margin:15px 45%;;" class="button" type="button" value="'.$this->l('Save font').'" onClick="javascript:saveFont('.(int)$id_font.')">';
                 
                 return $form_font;
        }
        
        
        public function saveFont($id_font = NULL, $font_data = array())
        {
                
                if (empty($id_font) AND is_numeric($id_font)
                        AND array_key_exists('id_font', $font_data) AND $font_data['id_font'] == $id_font)
                        $font = new Innovative_Font_Family($id_font);
                else $font = new Innovative_Font_Family();
                
                foreach ($font_data AS $key=>$value)
                {
                        $key = trim(str_replace('font_', '', $key));
                        $font->{$key} = $value;
                }
                
                $response = $font->save();
                if (array_key_exists('font_with_file', $font_data) AND $response)
                        $font->updateFile();
        }
        
        
        public function deleteFont($id_font)
        {
                $font = new Innovative_Font_Family($id_font);
                return $font->delete();
        }
        
        
        public function getActiveMenu()
        {
                $context = Context::getContext();
                $menu = Innovative_Menu::get(true, Shop::CONTEXT_SHOP, $context->shop->id);
                
                // If no specific menu is active for this store, find the active menu of group
                if (empty($menu->id))
                {
                        $menu = Innovative_Menu::get(true, Shop::CONTEXT_GROUP, $context->shop->id_shop_group);
                
                        // If no specific menu is active for this group, find the active menu of all stores
                        if (empty($menu->id))
                                $menu = Innovative_Menu::get(true, Shop::CONTEXT_ALL, 0);
                }
                
                return $menu;
        }
        
        public function hookDisplayTop($params)
        {
			$context = Context::getContext();
			self::loadClasses();
			$iso = Language::getIsoById($params['cookie']->id_lang); 
			$menu = $this->getActiveMenu();

			if (!empty($menu->id))
			{
					if (!file_exists(_PS_MODULE_DIR_.'innovativemenu/innovativemenu_'.$menu->id_menu_context.'_header.css')
						OR !file_exists(_PS_MODULE_DIR_.'innovativemenu/innovativemenu_fonts.css')
						OR !file_exists(_PS_MODULE_DIR_.'innovativemenu/innovativemenu_'.$menu->id_menu_context.'_'.$iso.'.tpl')){
						$menu->init(true, true);
						header('Location: '.$_SERVER['PHP_SELF']);
						die;
					}

					$context->controller->addCSS($this->_path.'innovativemenu_fonts.css');
					$context->controller->addCSS($this->_path.'innovativemenu_'.$menu->id_menu_context.'_header.css');
					return $this->display(__FILE__, 'innovativemenu_'.$menu->id_menu_context.'_'.$iso.'.tpl');
			}
        }


        public static function cleanHTML($html)
        {
                /*
                $jsEvent = 'onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave|onerror';
                $html = preg_replace('/<[ \t\n]*script/i', '', $html);
                $html = preg_replace('/<?.*('.$jsEvent.')[ \t\n]*=/i', '', $html);
                $html = preg_replace('/.*script\:/i', '', $html);
                 * 
                 */
                return $html;
        }

        public function hookActionObjectCategoryUpdateAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectCategoryDeleteAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectCmsUpdateAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectCmsDeleteAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectSupplierUpdateAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectSupplierDeleteAfter($params)
	{
		self::clearMenuCache();
	}	

	public function hookActionObjectManufacturerUpdateAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectManufacturerDeleteAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectProductUpdateAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookActionObjectProductDeleteAfter($params)
	{
		self::clearMenuCache();
	}
	
	public function hookCategoryUpdate($params)
	{
		self::clearMenuCache();
	}
	
}
