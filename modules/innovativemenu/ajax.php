<?php
header('Access-Control-Allow-Origin: *');
require_once dirname(__FILE__).'/../../config/config.inc.php';
require_once dirname(__FILE__).'/../../init.php';

global $cookie;

$module = Module::getInstanceByName('innovativemenu');
InnovativeMenu::loadClasses();

$action = Tools::getValue('innovativemenu_action');
$token = Tools::getValue('token');

$employee_id_lang = Tools::getValue('employee_id_lang');
$old_id_lang =  $cookie->id_lang;
$cookie->id_lang = $employee_id_lang;

if (trim($token) != trim(Configuration::get('INNOVATIVE_TOKEN')))
        die('<b>HACK ATTEMPT</b>');

switch ($action) {
        case 'loadBOTabs' :
                $tab = Tools::getValue('tab');
                if ($tab == 'manage_menus')
                        echo $module->manageMenus(Tools::getValue('type_context'), Tools::getValue('id_element'));
                elseif ($tab == 'manage_links')
                        echo $module->manageLinks().$module->manageFontsFamilies();
                else echo $module->getUserGuide();
                break;
        case 'saveLink' :
                $link_data = Tools::getValue('link_data');
                echo $module->saveLink($link_data);
                break;
        
        case 'deleteFont' :
                $id_font = (int)Tools::getValue('id_font');
                echo $module->deleteFont($id_font);
                break;
        
         case 'editFont' :
                $id_font = (int)Tools::getValue('id_font');
                echo $module->editFont($id_font);
                break;
        
        case 'saveFont' :
                $font_data = Tools::getValue('font_data');
                $id_font = (int)Tools::getValue('id_font');
                echo $module->saveFont($id_font, $font_data);
                break;
        
        case 'getFonts' :
                echo $module->getFonts();
                break;
        
        case 'editMenu' :
                $id_menu = Tools::getValue('id_menu');
                if(!$id_menu)
                        $id_menu = null;
                else $id_menu = (int)$id_menu;

                echo Tools::jsonEncode($module->editMenu($id_menu));
                break;
                
        case 'getMenus' :
                echo $module->getMenus(Tools::getValue('type_context'), Tools::getValue('id_element'));
                break;
                
        
        case 'getLinks' :
                echo $module->getLinks();
                break;
        
        
        case 'deleteMenu' :
                $id_menu = (int)Tools::getValue('id_menu');
                echo $module->deleteMenu($id_menu);
                break;
        
         case 'saveMenu' :
                $id_menu = (int)Tools::getValue('id_menu');
                $menu_data = Tools::getValue('menu_data');
                echo Tools::jsonEncode($module->saveMenu($id_menu, $menu_data, Tools::getValue('type_context'), Tools::getValue('id_element')));
                break;
        
        case 'viewMenu' :
                $id_menu = (int)Tools::getValue('id_menu');
                echo $module->viewMenu($id_menu);
                break;
        
        case 'editTab' :
                $id_menu = (int)Tools::getValue('id_menu');
                $id_tab = (int)Tools::getValue('id_tab') ? (int)Tools::getValue('id_tab') : NULL; 
                echo Tools::jsonEncode($module->editTab($id_menu, $id_tab));
                break;
        
         case 'editTabType' :
                $id_menu = (int)Tools::getValue('id_menu');
                $id_tab = (int)Tools::getValue('id_tab');
                $type = Tools::getvalue('type');
                echo $module->configTabType($type);
                break;
        
        case 'saveTab' :
                $id_menu = (int)Tools::getValue('id_menu');
                $id_tab = (int)Tools::getValue('id_tab');
                $tab_data = Tools::getValue('tab_data');
                echo Tools::jsonEncode($module->saveTab($id_menu, $id_tab, $tab_data, $html = true));
                break;
        
        case 'saveAds' :
                $id_ads = (int)Tools::getValue('id_ads');
                $id_tab = (int)Tools::getValue('id_tab');
                $ads_data = Tools::getValue('ads_data');
                echo Tools::jsonEncode($module->saveAds($id_tab, $id_ads, $ads_data));
                break;
        
        case 'deleteTab' :
                $id_tab = (int)Tools::getValue('id_tab');
                echo Tools::jsonEncode($module->deleteTab($id_tab));
                break;
        
        case 'editAds' :
                $id_tab = (int)Tools::getValue('id_tab');
                $id_ads = (int)Tools::getValue('id_ads');
                echo $module->editAds($id_tab, $id_ads);
                break;
        
        case 'moveTabPosition' :
                $id_tab = (int)Tools::getValue('id_tab');
                $direction = Tools::getValue('direction');
                echo Tools::jsonEncode($module->moveTabPosition($id_tab, $direction));
                break;
        
        case 'moveColumnPosition' :
                $id_column = (int)Tools::getValue('id_column');
                $direction = Tools::getValue('direction');
                echo Tools::jsonEncode($module->moveColumnPosition($id_column, $direction));
                break;
        
        case 'moveAdsPosition' :
                $id_ads = (int)Tools::getValue('id_ads');
                $direction = Tools::getValue('direction');
                echo Tools::jsonEncode($module->moveAdsPosition($id_ads, $direction));
                break;
        
        case 'getTabsOfMenu' :
                $id_menu = (int)Tools::getValue('id_menu');
                echo $module->getTabsOfMenu($id_menu);
                break;
        
        case 'editColumn' :
                $id_tab = (int)Tools::getValue('id_tab');
                $id_column = (int)Tools::getValue('id_column');
                echo $module->editColumn($id_tab, $id_column);
                break;

        case 'saveColumn' :
                $id_column = (int)Tools::getValue('id_column');
                $id_tab = (int)Tools::getValue('id_tab');
                $column_data = Tools::getValue('column_data');
                echo Tools::jsonEncode($module->saveColumn($id_tab, $id_column, $column_data, $html = true));
                break;
        
        case 'deleteColumn' :
                $id_column = (int)Tools::getValue('id_column');
                echo Tools::jsonEncode($module->deleteColumn($id_column));
                break;
        
        case 'deleteAds' :
                $id_ads = (int)Tools::getValue('id_ads');
                echo Tools::jsonEncode($module->deleteAds($id_ads));
                break;
        
       case 'getColumnsOfTab' :
                $id_tab = (int)Tools::getValue('id_tab');
                echo $module->getColumnsOfTab($id_tab);
                break; 
        
         case 'getAdsOfTab' :
                $id_tab = (int)Tools::getValue('id_tab');
                echo $module->getAdsOfTab($id_tab);
                break; 
        
        case 'allMenus' :
                echo $module->getMenus(Tools::getValue('type_context'), Tools::getValue('id_element'));
                break;
        
        case 'previewMenu' :
                $id_menu = (int)Tools::getValue('idMenu');
                echo $module->previewMenu($id_menu, $context = 'BO');
                break;
        
        case 'configureMenu' :
                $id_menu = (int)Tools::getValue('idMenu');
                echo $module->configureMenu($id_menu);
                break;
        
        case 'changeColumnType' :
                $id_tab = (int)Tools::getValue('id_tab');
                $id_column = (int)Tools::getValue('id_column');
                $value = Tools::getValue('value');
                echo $module->changeColumnType($id_tab, $id_column, $value);
                break;
        
        case 'changeColumnCategory' :
                $id_tab = (int)Tools::getValue('id_tab');
                $id_column = (int)Tools::getValue('id_column');
                $id_category = Tools::getValue('idCategory');
                echo $module->getColumnTypeCategories($id_tab, $id_column, false, $id_category);
                break;
        
        case 'removeElementOnColumn' :
                $id_column = (int)Tools::getValue('id_column');
                $data_element = Tools::getValue('data_element');
                echo $module->removeElementOnColumn($id_column, $data_element);
                break;
                
        case 'toggleActive' :
                $id = (int)Tools::getValue('id');
                $type = Tools::getValue('type');
                echo $module->toggleActive($type, $id);
        
        case 'getLinks' :        
                echo $module->getLinks();
                break;
                
        case 'editLink' :
                $id_link = (int)Tools::getValue('id_link');
                echo $module->editLink($id_link);
                break;
        
        case 'deleteLink' :
                $id_link = (int)Tools::getValue('id_link');
                echo $module->deleteLink($id_link);
                break;
                
        default:
                break;
}
$cookie->id_lang = $old_id_lang;
exit;