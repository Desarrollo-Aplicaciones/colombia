<?php 
class AdminCartRulesController extends AdminCartRulesControllerCore
{
	public function displayAjaxSearchCartRuleVouchers()
	{
		//..echo "<br> cont: 16 ";
		$found = false;

		/****** INICIO SOLO MOSTRAR VISITADORES MEDICOS  EWING   ******/

		if ( Tools::getValue('modulovisita') && Tools::getValue('modulovisita') == 'modulovisita' ) {
			$novalidar = 1;
		} else {
			$novalidar = 0;
		}

		/****** FIN SOLO MOSTRAR VISITADORES MEDICOS  EWING   ******/

		if (Tools::getValue('selopcvoucher') && Tools::getValue('selopcvoucher') == 'medico') {
			if ( $vouchers = CartRule::getCartsRuleByNameDoc( Tools::getValue('q'), (int)$this->context->language->id, Tools::getValue('selorandom'), $novalidar)) {
				$found = true; }
			echo Tools::jsonEncode(array('found' => $found, 'vouchers' => $vouchers));

		} elseif (Tools::getValue('selopcvoucher') && Tools::getValue('selopcvoucher') == 'cedula') {
			if ($vouchers = CartRule::getCartsRuleByCedulaDoc(Tools::getValue('q'), (int)$this->context->language->id, Tools::getValue('selorandom'))) {
				$found = true; }
			echo Tools::jsonEncode(array('found' => $found, 'vouchers' => $vouchers));

		} else {

			if ($vouchers = CartRule::getCartsRuleByCode(Tools::getValue('q'), (int)$this->context->language->id))
				$found = true;
			echo Tools::jsonEncode(array('found' => $found, 'vouchers' => $vouchers));
		}
	}

	public function processAdd()
	{
		if ( $_POST['reduction_percent'] > 70 ) {
			$this->errors[] = Tools::displayError('El valor de porcentaje de descuento, es muy alto. El maximo valor es de 70%');
		} else { 

			if ($cart_rule = parent::processAdd())
				$this->context->smarty->assign('new_cart_rule', $cart_rule);
			if (Tools::getValue('submitFormAjax'))
				$this->redirect_after = false;

			return $cart_rule;
		}
	}
}