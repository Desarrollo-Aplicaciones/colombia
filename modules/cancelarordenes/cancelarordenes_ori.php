<?php
/*
* 20014-2015 Farmalisto
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
*  @author Ewing Vásquez <ewing.vasquez@farmalisto.com.co>
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Farmalisto 
*/


if (!defined('_PS_VERSION_'))
	exit;

class cancelarordenes extends Module
{
	private $_html = '';
	private $_postErrors = array();
        private $_msg='';

	function __construct()
	{
		$this->name = 'cancelarordenes';
		$this->tab = 'administration';
		$this->version = '0.1 Alfa';
		$this->author = 'Farmalisto';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Cambiar estado de ordenes');
		$this->description = $this->l('Actualiza las ordenes colocando el estado de Preparación en Curso.');

	}

	public function install()
	{

		if (!$id_tab = Tab::getIdFromClassName('AdminCancelarOrdenes'))
		{
		$tab = new Tab();
		$tab->class_name = 'AdminCancelarOrdenes';
		$tab->module = 'cancelarordenes';
		$tab->id_parent = (int)Tab::getIdFromClassName('AdminParentOrders'); //aparecerá al final del menú ordenes

		foreach (Language::getLanguages(false) as $lang)
		$tab->name[(int)$lang['id_lang']] = 'Cambiar Estado Ordenes';
		if (!$tab->save())
		return $this->_abortInstall($this->l('Imposible crear la pestaña de nuevo'));
		}

		$this->_clearCache('cancelarordenes.tpl');		

		if ( !parent::install() ) {
			return false;
		}

		return true;
	}
	
	public function uninstall()
	{
		$this->_clearCache('cancelarordenes.tpl');
		return parent::uninstall();
	}

	public function getContent()
	{
				

		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitcancelarordenes') && isset($_POST['orden_cambio']) && $_POST['orden_cambio'] != '' ) {

			if( is_numeric( $_POST['orden_cambio'] ) ) {

				$history_order = new OrderHistory();
				$history_order->id_order = $_POST['orden_cambio'];
				$history_order->id_order_state = 3; // Estado preparación en curso
				$history_order->id_employee = Context::getContext()->employee->id;

				if ( $history_order->add() ) {
					$output .= $this->displayConfirmation($this->l('Se ha actualizado la orden << '.$_POST['orden_cambio'].' >> al estado Preparación en curso.'));

				} else {
					$output .= $this->displayError( "No se pudo actualizar el estado de la orden.");	
				}
				
			} else {

				$output .= $this->adminDisplayWarning( "El Id de la orden debe ser numérico." );

			}
		                    
		} elseif ( Tools::isSubmit('submitcancelarordenes') ) {

			$output .= $this->displayError( "No se enviaron los datos necesarios para cambiar el estado de la orden.");
		}

		 return $output.$this->displayForm();
	}


	public function displayForm() {

		$output = ' <p><b>'.$this->_msg.'</b></p>
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
	<p>Con este modulo usted podrá actualizar las ordenes de entrada al estado preparación en curso, tenga en cuenta que este cambio no puede ser reversible.</p>


<p>Id de la orden: <input type="text" name="orden_cambio" id="orden_cambio" />     </p>

				<center><input type="submit" name="submitcancelarordenes" value="Actualizar orden de entrada" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}

}
