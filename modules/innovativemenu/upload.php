<?php
require_once dirname(__FILE__).'/../../config/config.inc.php';
require_once dirname(__FILE__).'/../../init.php';

$module = Module::getInstanceByName('innovativemenu');
InnovativeMenu::loadClasses();
$filename = Tools::getValue('qqfile');
$token = Tools::getValue('token');

if (trim($token) != trim(Configuration::get('INNOVATIVE_TOKEN')))
        die('<b>HACK ATTEMPT</b>');

if (strrpos($filename, 'zip') !== false)
{
        if (!file_exists(dirname(__FILE__).'/css/fonts/upload/') OR !is_dir(dirname(__FILE__).'/css/fonts/upload/'))
                mkdir(dirname(__FILE__).'/css/fonts/upload/', 0777);
        echo file_put_contents(dirname(__FILE__).'/css/fonts/upload/'.Tools::passwdGen(8).'.zip', $GLOBALS["HTTP_RAW_POST_DATA"]);
}
exit;