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

		if (Tools::getValue('selopcvoucher') && Tools::getValue('selopcvoucher') == 'listadoctor') {
			if ( $vouchers = CartRule::getIdDocByNameDoc( Tools::getValue('q'), (int)$this->context->language->id, Tools::getValue('selorandom'), $novalidar)) {
				$found = true; }
			echo Tools::jsonEncode(array('found' => $found, 'vouchers' => $vouchers));

		} elseif (Tools::getValue('selopcvoucher') && Tools::getValue('selopcvoucher') == 'medico') {
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

	protected function afterAdd($currentObject)
	{
		// Add restrictions for generic entities like country, carrier and group
		foreach (array('country', 'carrier', 'group', 'shop') as $type)
			if (Tools::getValue($type.'_restriction') && is_array($array = Tools::getValue($type.'_select')) && count($array))
			{
				$values = array();
				foreach ($array as $id)
					$values[] = '('.(int)$currentObject->id.','.(int)$id.')';
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_'.$type.'` (`id_cart_rule`, `id_'.$type.'`) VALUES '.implode(',', $values));
			}
		// Add cart rule restrictions
		if (Tools::getValue('cart_rule_restriction') && is_array($array = Tools::getValue('cart_rule_select')) && count($array))
		{
			$values = array();
			foreach ($array as $id)
				$values[] = '('.(int)$currentObject->id.','.(int)$id.')';
			Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_combination` (`id_cart_rule_1`, `id_cart_rule_2`) VALUES '.implode(',', $values));
		}
		// Add product rule restrictions
		if (Tools::getValue('product_restriction') && is_array($ruleGroupArray = Tools::getValue('product_rule_group')) && count($ruleGroupArray))
		{
			foreach ($ruleGroupArray as $ruleGroupId)
			{
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_product_rule_group` (`id_cart_rule`, `quantity`)
				VALUES ('.(int)$currentObject->id.', '.(int)Tools::getValue('product_rule_group_'.$ruleGroupId.'_quantity').')');
				$id_product_rule_group = Db::getInstance()->Insert_ID();

				if (is_array($ruleArray = Tools::getValue('product_rule_'.$ruleGroupId)) && count($ruleArray))
					foreach ($ruleArray as $ruleId)
					{
						Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_product_rule` (`id_product_rule_group`, `type`)
						VALUES ('.(int)$id_product_rule_group.', "'.pSQL(Tools::getValue('product_rule_'.$ruleGroupId.'_'.$ruleId.'_type')).'")');
						$id_product_rule = Db::getInstance()->Insert_ID();

						$values = array();
						foreach (Tools::getValue('product_rule_select_'.$ruleGroupId.'_'.$ruleId) as $id)
							$values[] = '('.(int)$id_product_rule.','.(int)$id.')';
						$values = array_unique($values);
						if (count($values))
							Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_product_rule_value` (`id_product_rule`, `id_item`) VALUES '.implode(',', $values));
					}
			}
		}

		// If the new rule has no cart rule restriction, then it must be added to the white list of the other cart rules that have restrictions
		if (!Tools::getValue('cart_rule_restriction'))
		{
			/*Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'cart_rule_combination` (`id_cart_rule_1`, `id_cart_rule_2`) (
				SELECT id_cart_rule, '.(int)$currentObject->id.' FROM `'._DB_PREFIX_.'cart_rule` WHERE cart_rule_restriction = 1
			)');*/
		}
		// And if the new cart rule has restrictions, previously unrestricted cart rules may now be restricted (a mug of coffee is strongly advised to understand this sentence)
		else
		{
			$ruleCombinations = Db::getInstance()->executeS('
			SELECT cr.id_cart_rule
			FROM '._DB_PREFIX_.'cart_rule cr
			WHERE cr.id_cart_rule != '.(int)$currentObject->id.'
			AND cr.cart_rule_restriction = 0
			AND cr.id_cart_rule NOT IN (
				SELECT IF(id_cart_rule_1 = '.(int)$currentObject->id.', id_cart_rule_2, id_cart_rule_1)
				FROM '._DB_PREFIX_.'cart_rule_combination
				WHERE '.(int)$currentObject->id.' = id_cart_rule_1
				OR '.(int)$currentObject->id.' = id_cart_rule_2
			)');
			foreach ($ruleCombinations as $incompatibleRule)
			{
				Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'cart_rule` SET cart_rule_restriction = 1 WHERE id_cart_rule = '.(int)$incompatibleRule['id_cart_rule'].' LIMIT 1');
				Db::getInstance()->execute('
				INSERT IGNORE INTO `'._DB_PREFIX_.'cart_rule_combination` (`id_cart_rule_1`, `id_cart_rule_2`) (
					SELECT id_cart_rule, '.(int)$incompatibleRule['id_cart_rule'].' FROM `'._DB_PREFIX_.'cart_rule`
					WHERE active = 1
					AND id_cart_rule != '.(int)$currentObject->id.'
					AND id_cart_rule != '.(int)$incompatibleRule['id_cart_rule'].'
				)');
			}
		}
	}

}