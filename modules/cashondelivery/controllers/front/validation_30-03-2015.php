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

/**
 * @since 1.5.0
 */
class CashondeliveryValidationModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	public function postProcess()
	{
		if ($this->context->cart->id_customer == 0 || $this->context->cart->id_address_delivery == 0 || $this->context->cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'cashondelivery')
			{
				$authorized = true;
				break;
			}
		if (!$authorized)
			die(Tools::displayError('This payment method is not available.'));

		$customer = new Customer($this->context->cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

		if (Tools::getValue('confirm'))
		{
			$customer = new Customer((int)$this->context->cart->id_customer);
			$total = $this->context->cart->getOrderTotal(true, Cart::BOTH);
			$this->module->validateOrder((int)$this->context->cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $this->module->displayName, null, array(), null, false, $customer->secure_key);
			
$parameters_url = 'metodo=contraentrega&key=' . $customer->secure_key . '&id_cart=' . (int) ($cart->id) . '&id_module=' . (int) $cashOnDelivery->id . '&id_order=' . (int) $cashOnDelivery->currentOrder;
$url_objetivo='http://'.$this->context->shop->domain.$this->context->shop->physical_uri.'content/16-confirmacion-de-pedido-farmalisto?'.$parameters_url;  
//header('Location: ' . $url_objetivo);
			
	Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->module->id.'&id_order='.(int)$this->module->currentOrder);
		}
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->display_column_left = false;
		parent::initContent();
		$total = $this->context->cart->getOrderTotal(true, Cart::BOTH);


		///*** INICIO VALIDACION DIRECCION FARMALISTO ***///
		$validateaddress = $this->context->cart->validationaddressfarmalisto();
        if ($validateaddress){
            $total_envio = 0;
        } else {
        	$total_envio = $this->context->cart->getTotalShippingCost();
        }
        ///*** FIN VALIDACION DIRECCION FARMALISTO ***///

		///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE ***///
		$cartRules = $this->context->cart->getCartRules();
		$descuento = $cartRules[0]['reduction_percent'];
		if ($descuento != "" && $descuento != 0){
			$totalorderdescuento =  $this->context->cart->recalculartotalconcupon($descuento);
			$total = $totalorderdescuento['total'] + $total_envio;
		}
		///*** FIN CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO PORCENTAJE ***///
		

		///*** INICIO CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO MONETARIO***///
		$cartRules = $this->context->cart->getCartRules();
		$descuentoMoney = $cartRules[0]['reduction_amount'];

		if ( $descuentoMoney != "" && $descuentoMoney != 0 ) {
			$descuentoMonetario = $this->context->cart->RecalcularCuponMonetario();
			$total = $descuentoMonetario['totales']['total_orden'];
		}
		///*** FIN CALCULO COSTO TOTAL, CON CUPON DE DESCUENTO MONETARIO***///

		$this->context->smarty->assign(array(
			'total' => $total,
			'this_path' => $this->module->getPathUri(),//keep for retro compat
			'this_path_cod' => $this->module->getPathUri(),
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));

		$this->setTemplate('validation.tpl');
	}
}
