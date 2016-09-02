<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
//require_once(_PS_ROOT_DIR_.'/classes/seveFileClass.php');

if (!defined('_PS_VERSION_'))
	exit;

class limpiarcache extends Module
{
	private $_html = '';
	private $_postErrors = array();
        private $_msg='';

	function __construct()
	{
		$this->name = 'limpiarcache';
		$this->tab = 'administration';
		$this->version = '0.1 Gama';
		$this->author = 'Farmalisto';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Ejecutar script que limpia cache.');
		$this->description = $this->l('Ejecuta el comando shell que se encarga de limpiar cache en el servidor.');

	}

	public function install()
	{
		if (!$id_tab = Tab::getIdFromClassName('AdminLimpiaCache'))
		{
		$tab = new Tab();
		$tab->class_name = 'AdminLimpiaCache';
		$tab->module = 'limpiarcache';
		$tab->id_parent = (int)Tab::getIdFromClassName('AdminAdmin'); //aparecerá al final del menú catalogo

		foreach (Language::getLanguages(false) as $lang)
		$tab->name[(int)$lang['id_lang']] = 'Limpiar cache prestashop.';
		if (!$tab->save())
		return $this->_abortInstall($this->l('Imposible crear la pestaña de nuevo'));
		}

		$this->_clearCache('limpiarcache.tpl');
		Configuration::updateValue('HOME_FEATURED_NBR', 8);

		if (!parent::install())
			return false;
		return true;
	}
	
	public function uninstall()
	{
		$this->_clearCache('limpiarcache.tpl');
		return parent::uninstall();
	}

	public function getContent()
	{
		

		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitlimpiarcache')){

			if (Tools::getValue('limpiar')) {
				switch (Tools::getValue('limpiar')) {
					case 'all':
							
                                            
$fp=fopen("/var/www/cache_flag/clear_cache","a+");
fwrite($fp,'x');
fclose($fp) ;
$output .= $this->displayConfirmation("<br>La cache se eliminara a mas tardar en un minuto.<br>");
						break;

					case 'OPcache':
$fp=fopen("/var/www/cache_flag/clear_cache_op","a+");
fwrite($fp,'x');
fclose($fp) ;
$output .= $this->displayConfirmation("<br>La cache de PHP (OPcache)se eliminara a mas tardar en un minuto.<br>");
						break;

					case 'per':
							$output .= $this->displayConfirmation("<br>Permisos<br>".shell_exec('sh /home/ubuntu/cacheps_www.sh'));
						break;
					
					default:
							$output .= $this->displayError("No seleccionó ninguna opción.");
						break;
				}
			}
		} else {
			$output .= $this->displayError("No seleccionó ninguna opción.");
		}

		return $output.$this->displayForm();

	}

	public function displayForm()
	{

	
		
		$output = ' <p><b>'.$this->_msg.'</b></p>
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo_big.png" alt="" title="" />'.$this->l('Settings').'</legend>
                            
           <p> <input type="radio" name="limpiar" value="OPcache"> Limpiar OPcache PHP ? (Recomendado)</p>
	<p> <input type="radio" name="limpiar" value="all"> Limpiar cache y otorgar permisos ? </p>
	

</div>
				<center><input type="submit" name="submitlimpiarcache" value="Limpiar Cache" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}

}
