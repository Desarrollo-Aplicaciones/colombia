<?php
/**
* Quantity Discount Pro
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2017 idnovate.com
*  @license   See above
*/

include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/array_column.php');

class QuantityDiscountRule extends ObjectModel
{
    public $id_quantity_discount_rule;
    public $name;
    public $active = true;
    public $description;
    public $id_family;
    public $code;
    public $code_prefix;
    public $times_used = 0;
    public $date_from;
    public $date_to;
    public $quantity = 9999;
    public $quantity_per_user = 9999;
    public $priority = 0;
    public $execute_other_rules = 0;
    public $compatible_cart_rules = 0;
    public $compatible_qdp_rules = 1;
    public $modules_exceptions;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'quantity_discount_rule',
        'primary' => 'id_quantity_discount_rule',
        'multilang' => true,
        'fields' => array(
            //Information
            'active'                    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'description'               => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 65534),
            'id_family'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'code'                      => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 10),
            'code_prefix'               => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 10),
            'times_used'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'date_from'                 => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
            'date_to'                   => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
            'priority'                  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'execute_other_rules'       => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'compatible_cart_rules'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'compatible_qdp_rules'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'quantity'                  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'quantity_per_user'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'modules_exceptions'        => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 65534),
            'date_add'                  => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd'                  => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            //Lang fields
            'name'                      => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 254),
        ),
    );

    public function __construct($id = null, $lang = null)
    {
        $this->context = Context::getContext();

        //Default code prefix
        $this->code_prefix = 'QD_';

        parent::__construct($id, $lang);

        $this->times_used = Db::getInstance()->getValue(
            "SELECT count(*)
            FROM "._DB_PREFIX_."orders o
            LEFT JOIN "._DB_PREFIX_."order_cart_rule od ON o.id_order = od.id_order
            LEFT JOIN "._DB_PREFIX_."quantity_discount_rule_order qdro ON od.id_cart_rule = qdro.id_cart_rule
            WHERE qdro.id_quantity_discount_rule = ".(int)$this->id."
            AND ".(int)Configuration::get('PS_OS_ERROR')." != o.current_state"
        );
    }

    public function delete()
    {
        if (!parent::delete()) {
            return false;
        }

        $this->condition_selectors = array('group', 'product', 'category', 'country', 'attribute', 'zone', 'manufacturer', 'carrier', 'supplier', 'order_state', 'shop', 'gender', 'currency');
        $this->action_selectors = array('product', 'category', 'attribute', 'manufacturer', 'supplier');

        $result = Db::getInstance()->delete('quantity_discount_rule_condition', '`id_quantity_discount_rule` = '.(int)$this->id);
        foreach ($this->condition_selectors as $type) {
            $result &= Db::getInstance()->delete('quantity_discount_rule_condition_'.$type, '`id_quantity_discount_rule` = '.(int)$this->id);
        }

        $result &= Db::getInstance()->delete('quantity_discount_rule_action', '`id_quantity_discount_rule` = '.(int)$this->id);
        foreach ($this->action_selectors as $type) {
            $result &= Db::getInstance()->delete('quantity_discount_rule_action_'.$type, '`id_quantity_discount_rule` = '.(int)$this->id);
        }

        $result &= Db::getInstance()->delete('quantity_discount_rule_cart', '`id_quantity_discount_rule` = '.(int)$this->id);
        Db::getInstance()->delete('quantity_discount_rule_message_lang', '`id_quantity_discount_rule_message` IN (SELECT `id_quantity_discount_rule_message` FROM `'._DB_PREFIX_.'quantity_discount_rule_message` WHERE `id_quantity_discount_rule` = '.(int)$this->id.')');

        Db::getInstance()->delete('quantity_discount_rule_message', '`id_quantity_discount_rule` = '.(int)$this->id);
        $result &= Db::getInstance()->delete('quantity_discount_rule_order', '`id_quantity_discount_rule` = '.(int)$this->id);

        return $result;
    }

    public function getGroups($object = false)
    {
        $cache_key = 'QuantityDiscountRule::getGroups_'.(int)$this->id_quantity_discount_rule.'_'.$object;

        if (!Cache::isStored($cache_key)) {
            $result = Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_group` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                ORDER BY `id_quantity_discount_rule_group` ASC'
            );

            if ($object) {
                $result = ObjectModel::hydrateCollection('QuantityDiscountRuleGroup', $result);
            }

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public function getActions($object = false)
    {
        $cache_key = 'QuantityDiscountRule::getActions_'.(int)$this->id_quantity_discount_rule.'_'.$object;

        if (!Cache::isStored($cache_key)) {
            $result = Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_action` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                ORDER BY `id_quantity_discount_rule_action` ASC'
            );

            if ($object) {
                foreach ($result as &$row) {
                    $row = new QuantityDiscountRuleAction((int)$row['id_quantity_discount_rule_action']);
                }
            }

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

         return $result;
    }

    public function getMessages()
    {
        $cache_key = 'QuantityDiscountRule::getMessages_'.(int)$this->id_quantity_discount_rule;

        if (!Cache::isStored($cache_key)) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_message` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule;

            $result = Db::getInstance()->executeS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

         return $result;
    }

    public function getMessagesByHook($hookName)
    {
        if (!$hookName) {
            return;
        }

        $cache_key = 'QuantityDiscountRule::getMessagesByHook_'.(int)$this->id_quantity_discount_rule.'_'.$hookName;

        if (!Cache::isStored($cache_key)) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_message` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                    AND `hook_name` = \''.pSQL($hookName).'\'';

            $result = Db::getInstance()->executeS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

         return $result;
    }

    public static function getQuantityDiscountRules($id_family = false)
    {
        if ($id_family && !(int)$id_family > 0) {
            return;
        }

        $cache_key = 'QuantityDiscountRule::getQuantityDiscountRules_'.($id_family ? (int)$id_family : 'false');
        if (!Cache::isStored($cache_key)) {
            $result = Db::getInstance()->ExecuteS(
                "SELECT * FROM `"._DB_PREFIX_ ."quantity_discount_rule` cp
                LEFT JOIN `"._DB_PREFIX_."quantity_discount_rule_lang` cpl
                    ON (cp.id_quantity_discount_rule=cpl.id_quantity_discount_rule AND cpl.id_lang=".(int)Context::getContext()->cart->id_lang.")
                WHERE cp.active = 1"
                .($id_family ? " AND id_family = ".(int)$id_family : "").
                " ORDER BY cp.priority ASC"
            );

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public static function getNbProductsOrder($id)
    {
        return Db::getInstance()->getValue(
            'SELECT SUM(`product_quantity`)
            FROM `'._DB_PREFIX_.'order_detail`
            WHERE `id_order` = '.(int)$id
        );
    }

    public function getMessagesToDisplay($hookName)
    {
        $messages = array();
        foreach ($this->getMessagesByHook($hookName) as $message) {
            $messages[] = $message;
        };

        return $messages;
    }

    public static function getAllMessagesToDisplay($hookName)
    {
        $quantityDiscountRules = self::getQuantityDiscountRules();

        $messages = array();

        if (is_array($quantityDiscountRules) || is_object($quantityDiscountRules)) {
            foreach ($quantityDiscountRules as $quantityDiscountRule) {
                $quantityDiscountRule = new QuantityDiscountRule((int)$quantityDiscountRule['id_quantity_discount_rule']);

                foreach ($quantityDiscountRule->getMessagesByHook($hookName) as $message) {
                    $messages[] = $message;
                };

                if (!$quantityDiscountRule->execute_other_rules) {
                    break;
                }
            }
        }

        return $messages;
    }

    public function createAndRemoveRules($code = null)
    {

        $context = Context::getContext();

        if (!Validate::isLoadedObject($context->cart)) {
            return;
        }

        if (!$context->cart->nbProducts()) {
            return false;
        }

        //Kept the discount codes to apply again the rule
        $cartRulesCodes = array();
        if ($code = Tools::strtoupper($code)) {
            $cartRulesCodes[] = $code;
        }

        //Remove all quantity discount rules from current cart
        $quantityDiscountRulesAtCart = $this->getQuantityDiscountRulesAtCart((int)$context->cart->id);
        $cartRulesRemoved = false;
        if (is_array($quantityDiscountRulesAtCart) && count($quantityDiscountRulesAtCart)) {
            $cartRulesRemoved = true;
            foreach ($quantityDiscountRulesAtCart as $quantityDiscountRuleAtCart) {
                //We save the discount code to apply it after
                if ($quantityDiscountRuleAtCart['code']) {
                    $cartRulesCodes[] = $quantityDiscountRuleAtCart['code'];
                }
                $this->removeQuantityDiscountCartRule($quantityDiscountRuleAtCart['id_cart_rule'], (int)$context->cart->id);
            }
        }
        $cartRulesCodes = array_unique($cartRulesCodes);

        //Get all rules and check if any of them should be applied
        $cartRulesCreated = false;
        foreach (QuantityDiscountRuleFamily::getQuantityDiscountRuleFamilies() as $ruleFamily) {
            $quantityDiscountRules = self::getQuantityDiscountRules($ruleFamily['id_quantity_discount_rule_family']);
            if (is_array($quantityDiscountRules) && count($quantityDiscountRules)) {
                foreach ($quantityDiscountRules as $quantityDiscountRule) {
                    $quantityDiscountRuleObj = new QuantityDiscountRule((int)$quantityDiscountRule['id_quantity_discount_rule']);
                    if (!$quantityDiscountRuleObj->compatibleCartRules()
                        || !$quantityDiscountRuleObj->isQuantityDiscountRuleValid($cartRulesCodes)
                        || !$quantityDiscountRuleObj->validateQuantityDiscountRuleConditions()) {
                        continue;
                    }

                    if ($cartRulesCreated && !$quantityDiscountRuleObj->compatible_qdp_rules) {
                        continue;
                    }

                    if ($this->createCartRule($quantityDiscountRuleObj)) {
                        $cartRulesCreated = true;
                        if (!$quantityDiscountRuleObj->execute_other_rules) {
                            break;
                        }
                    }
                }
            }

            if ($cartRulesCreated && !$ruleFamily['execute_other_families']) {
                break;
            }
        }

        if (($cartRulesRemoved || $cartRulesCreated) && (isset($this->ajax_refresh) || (int)Tools::getValue('allow_refresh'))) {
            die(Tools::jsonEncode(array('refresh' => true)));
        } elseif ($cartRulesCreated) {
            return true;
        }

        return false;
    }

    public function createCartRule($quantityDiscountRule)
    {

        $cartProducts = $this->context->cart->getProducts();

        $actions = $quantityDiscountRule->getActions(true);


        $minCoincidences = 0;
        $cartRule = new CartRule();

        $reductionAmount = 0;
        $typeAcumulativo = false;

        foreach ($actions as $action) {

            switch ((int)$action->id_type) {
                /**
                 *
                 * Shipping cost - Fixed discount
                 *
                 */
                case 1:
                    $shippingCost = Tools::convertPriceFull($this->context->cart->getPackageShippingCost(null, (int)$action->reduction_tax), $this->context->currency, new Currency((int)$action->reduction_currency));

                    $cartRule->reduction_amount = (($action->reduction_amount > 0 && $shippingCost > $action->reduction_amount) ? $action->reduction_amount : $shippingCost);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;
                /**
                 *
                 * Shipping cost - Percentage discount
                 *
                 */
                case 5:
                    $reductionAmount = Tools::convertPriceFull(($this->context->cart->getPackageShippingCost(null, (int)$action->reduction_percent_tax)*$action->reduction_percent)/100, $this->context->currency, new Currency((int)$action->reduction_currency));

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * Order amount - Fixed discount
                 *
                 */
                case 2:
                    if ($action->reduction_amount) {
                        $orderTotal = $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_PRODUCTS);
                        $shippingCost = $this->context->cart->getPackageShippingCost(null, (int)$action->reduction_tax);

                        $orderTotal -= $this->getGiftProductsValue((int)$action->reduction_tax);

                        /**
                         *
                         * Check if shipping is included in the amount to discount,
                         * because is possible that the amount to discount is higher that the product amount,
                         * so we need to know if we have to reduce only products or products + shipping
                         *
                         */
                        if ((int)$action->reduction_shipping) {
                            $maxDiscount = $orderTotal + $shippingCost;
                        } else {
                            $maxDiscount = $orderTotal;
                        }

                        $maxDiscount = Tools::convertPriceFull($maxDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));

                        $cartRule->reduction_amount = ($maxDiscount > $action->reduction_amount) ? $action->reduction_amount : $maxDiscount;
                        $cartRule->reduction_currency = (int)$action->reduction_currency;
                        $cartRule->reduction_tax = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Order amount - Percentage discount
                 *
                 */
                case 3:
                    $orderTotal = $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_PRODUCTS);
                    $orderTotal -= $this->getGiftProductsValue((int)$action->reduction_percent_tax);

                    /**
                     *
                     * Check if shipping is included in the amount to discount,
                     * because is possible that the amount to discount is higher that the product amount,
                     * so we need to know if we have to reduce only products or products + shipping
                     *
                     */
                    if ((int)$action->reduction_percent_shipping) {
                        $shippingCost = $this->context->cart->getPackageShippingCost(null, (int)$action->reduction_percent_tax);
                        $totalAmount = $orderTotal + $shippingCost;
                    } else {
                        $totalAmount = $orderTotal;
                    }

                    /** Remove discounts */
                    if (!$action->reduction_percent_discount) {
                        $totalAmount -= $this->context->cart->getOrderTotal((int)$action->reduction_percent_tax, Cart::ONLY_DISCOUNTS);
                    }

                    $totalAmount = Tools::convertPriceFull(($totalAmount*$action->reduction_percent)/100, $this->context->currency, new Currency((int)$action->reduction_currency));

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $totalAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $totalAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * Buy X - Get Y with fixed discount
                 *
                 */
                case 6:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);

                    $cartRule->reduction_amount = $reductionAmount;
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Buy X - Get Y with percentage discount
                 *
                 */
                case 7:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $unitDiscount = $productPrice*($action->reduction_percent/100);

                                $unitDiscountConverted = Tools::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));
                                $reductionProductMaxAmountConverted = Tools::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency));

                                $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $reductionAmount += $unitDiscount*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * Buy X - Get Y with fixed price
                 *
                 */
                case 8:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * All products after X - Fixed discount
                 *
                 */
                case 12:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] > (int)$action->products_nb_each) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']-(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']-(int)$action->products_nb_each));
                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

               /**
                 *
                 * All products after X - Percentage discount
                 *
                 */
                case 13:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']-(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']-(int)$action->products_nb_each));
                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $unitDiscount = $productPrice*($action->reduction_percent/100);

                                $unitDiscountConverted = Tools::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));
                                $reductionProductMaxAmountConverted = Tools::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency));

                                $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $reductionAmount += $unitDiscount*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * All products after X - Fixed price
                 *
                 */
                case 14:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }


                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']-(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']-(int)$action->products_nb_each));
                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Each group of X - Fixed discount
                 *
                 */
                case 15:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each));

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Each group of X - Percentage discount
                 *
                 */
                case 16:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] >= (int)$action->products_nb_each) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->products_nb_each;

                            $groupPrice = 0;
                            $groupAggregate = 0;

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $groupAggregate += $timesToApplyPromoInThisProduct;
                                $groupPrice += $productPrice*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }

                            $reductionAmount += $groupPrice*($action->reduction_percent/100);
                        }
                    }

                    $reductionAmount = Tools::convertPriceFull($reductionAmount, $this->context->currency, new Currency((int)$action->reduction_currency));

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * Each group of X - Fixed price
                 *
                 */
                case 17:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] >= (int)$action->products_nb_each) {
                            $timesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each));

                            $remainingTimesToApplyPromo = $timesToApplyPromo*(int)$action->products_nb_each;

                            $groupPrice = 0;
                            $groupAggregate = 0;

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                                $groupAggregate += $timesToApplyPromoInThisProduct;
                                $groupPrice += $productPrice*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }

                            $groupPrice = Tools::convertPriceFull($groupPrice, $this->context->currency, new Currency((int)$action->reduction_currency));
                            $reductionAmount += $groupPrice-($action->reduction_amount)*$timesToApplyPromo;
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Each X-th after Y - Fixed discount
                 *
                 */
                case 18:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $reductionAmount = 0;
                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] >= (int)$action->products_nb_each + (int)$action->apply_discount_to_nb) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)((int)$productGrouped['cart_quantity']-(int)$action->apply_discount_to_nb)/(int)$action->products_nb_each, (int)$action->nb_repetitions_custom) : (int)((int)$productGrouped['cart_quantity']-(int)$action->apply_discount_to_nb)/(int)$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $product = array_shift($productGrouped['products']);
                                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, $product['cart_quantity']);

                                $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    $cartRule->reduction_amount = $reductionAmount;
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Each X-th after Y - Percentage discount
                 *
                 */
                case 19:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $previousMod = 0;
                    $reductionAmount = 0;

                    foreach ($productsGrouped as $productGrouped) {
                        switch ($action->nb_repetitions) {
                            case 'infinite':
                                while (count($productGrouped['products'])) {
                                    $product = array_shift($productGrouped['products']);

                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, (bool)!$action->apply_discount_to_regular_price);

                                    $mod = (int)(($product['cart_quantity'] + $previousMod) % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        //Check if computed price product is higher than fixed price, if not don't do anything
                                        $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));
                                        if ($productPrice - $action->reduction_amount > 0) {
                                            $reductionAmount += ($productPrice - $action->reduction_amount)*(int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);
                                        }
                                    }
                                    $previousMod = $mod;
                                }
                                break;
                            case 'custom':
                                $i = (int)$action->nb_repetitions_custom;
                                while (count($productGrouped['products']) && $i > 0) {
                                    $product = array_shift($productGrouped['products']);

                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, (bool)!$action->apply_discount_to_regular_price);

                                    $mod = (int)($product['cart_quantity'] % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));
                                        if ($productPrice - $action->reduction_amount > 0) {
                                            $reductionAmount += ($productPrice - $action->reduction_amount)*min((int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each), $i);
                                            $i = $i - (int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);
                                        }
                                    }

                                    $previousMod = $mod;
                                }
                                break;
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * Each X-th after Y - Fixed price
                 *
                 */
                case 20:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $previousMod = 0;
                    $reductionAmount = 0;

                    foreach ($productsGrouped as $productGrouped) {
                        switch ($action->nb_repetitions) {
                            case 'infinite':
                                while (count($productGrouped['products'])) {
                                    $product = array_shift($productGrouped['products']);

                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, (bool)!$action->apply_discount_to_regular_price);

                                    $mod = (int)(($product['cart_quantity'] + $previousMod) % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        //Check if computed price product is higher than fixed price, if not don't do anything
                                        $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));
                                        if ($productPrice - $action->reduction_amount > 0) {
                                            $reductionAmount += ($productPrice - $action->reduction_amount)*(int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);
                                        }
                                    }

                                    $previousMod = $mod;
                                }
                                break;
                            case 'custom':
                                $i = (int)$action->nb_repetitions_custom;
                                while (count($productGrouped['products']) && $i > 0) {
                                    $product = array_shift($productGrouped['products']);

                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, (bool)!$action->apply_discount_to_regular_price);

                                    $mod = (int)($product['cart_quantity'] % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));
                                        if ($productPrice - $action->reduction_amount > 0) {
                                            $reductionAmount += ($productPrice - $action->reduction_amount)*min((int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each), $i);
                                            $i = $i - (int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);
                                        }
                                    }

                                    $previousMod = $mod;
                                }
                                break;
                        }
                    }

                    $cartRule->reduction_amount = $reductionAmount;
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Each X spent (over Z) Get Y - Fixed discount
                 *
                 */
                case 21:
                    $originalProducts = count($cartProducts);
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);
                    $filteredProducts = count($cartProductsFiltered);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $totalAmount = 0;
                    //Get products amount
                    foreach ($cartProductsFiltered as $cartProduct) {
                        if ((int)$action->apply_discount_to_special || !Product::getPriceStatic($cartProduct['id_product'], (int)$action->reduction_tax, (isset($cartProduct['id_product_attribute']) ? (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null) : null), 6, null, true) > 0) {
                            $totalAmount += Product::getPriceStatic($cartProduct['id_product'], (int)$action->reduction_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null))*$cartProduct['cart_quantity'];
                        }
                    }

                    //Remove discounts only if there isn't a product filter, as we can not know if a discount is for specific products
                    if ($originalProducts == $filteredProducts) {
                        $totalAmount -= $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_DISCOUNTS);
                    }

                    //Add shipping cost
                    if ((int)$action->reduction_shipping) {
                        $shippingCost = $this->context->cart->getPackageShippingCost(null, (int)$action->reduction_tax);
                        $totalAmount += $shippingCost;
                    }

                    //Subtract gift products value
                    $totalAmount -= $this->getGiftProductsValue((int)$action->reduction_tax);
                    $totalAmount = Tools::convertPriceFull($totalAmount, $this->context->currency, new Currency((int)$action->reduction_currency));

                    //Subtract amount over
                    $totalAmount -= $action->reduction_buy_over;

                    if ($totalAmount) {
                        $timesToApplyPromo = (int)($totalAmount/$action->reduction_amount);
                        $reductionAmount = $action->reduction_buy_amount * $timesToApplyPromo;

                        $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $cartRule->reduction_currency = (int)$action->reduction_currency;
                        $cartRule->reduction_tax = (int)$action->reduction_buy_amount_tax;
                    }

                    break;

                /**
                 *
                 * X spent (over Z) Get Y - Percentage discount
                 *
                 */
                case 26:
                    $originalProducts = count($cartProducts);
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);
                    $filteredProducts = count($cartProductsFiltered);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $totalAmount = 0;
                    //Get products amount
                    foreach ($cartProductsFiltered as $cartProduct) {
                        if ((int)$action->apply_discount_to_special || !Product::getPriceStatic($cartProduct['id_product'], (int)$action->reduction_percent_tax, (isset($cartProduct['id_product_attribute']) ? (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null) : null), 6, null, true) > 0) {
                            $totalAmount += Product::getPriceStatic($cartProduct['id_product'], (int)$action->reduction_percent_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null))*$cartProduct['cart_quantity'];
                        }
                    }

                    //Remove discounts only if there isn't a product filter, as we can not know if a discount is for specific products
                    if ($originalProducts == $filteredProducts) {
                        $totalAmount -= $this->context->cart->getOrderTotal((int)$action->reduction_percent_tax, Cart::ONLY_DISCOUNTS);
                    }

                    //Add shipping cost
                    if ((int)$action->reduction_shipping) {
                        $shippingCost = $this->context->cart->getPackageShippingCost(null, (int)$action->reduction_percent_tax);
                        $totalAmount += $shippingCost;
                    }

                    //Subtract gift products value
                    $totalAmount -= $this->getGiftProductsValue((int)$action->reduction_percent_tax);
                    $totalAmount = Tools::convertPriceFull($totalAmount, $this->context->currency, new Currency((int)$action->reduction_currency));

                    //Subtract amount over
                    $totalAmount -= $action->reduction_buy_over;

                    if ($totalAmount) {
                        $reductionAmount = $totalAmount*($action->reduction_percent/100);

                        $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $cartRule->reduction_currency = (int)$action->reduction_currency;
                        $cartRule->reduction_tax = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Buy X
                 *
                 */
                case 22:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $minCoincidences = PHP_INT_MAX;
                    foreach ($productsGrouped as $productGrouped) {
                        $minCoincidences = min($minCoincidences, (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each));
                    }

                    break;

                /**
                 *
                 * Product discount - Fixed discount
                 *
                 */
                case 27:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $reductionAmount = 0;
                    foreach ($productsGrouped as $productGrouped) {
                        $remainingTimesToApplyPromo = (int)$action->products_nb_each;
                        foreach ($productGrouped['products'] as $product) {
                            $product = array_shift($productGrouped['products']);
                            $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                            $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                            $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                            if ($productPrice > $action->reduction_amount) {
                                $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                            } else {
                                $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                            }

                            $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                            if (!$remainingTimesToApplyPromo) {
                                break;
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

               /**
                 *
                 * Product discount - Percentage discount
                 *
                 */
                case 28:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $reductionAmount = 0;
                    foreach ($productsGrouped as $productGrouped) {
                        $remainingTimesToApplyPromo = (int)$action->products_nb_each;
                        foreach ($productGrouped['products'] as $product) {
                            $product = array_shift($productGrouped['products']);
                            $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                            $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                            $unitDiscount = $productPrice*($action->reduction_percent/100);

                            $unitDiscountConverted = Tools::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));
                            $reductionProductMaxAmountConverted = Tools::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency));

                            $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                            $reductionAmount += $unitDiscount*$timesToApplyPromoInThisProduct;

                            $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                            if (!$remainingTimesToApplyPromo) {
                                break;
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;

                    break;

                /**
                 *
                 * Product discount - Fixed price
                 *
                 */
                case 29:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if ($action->apply_discount_sort == 'cheapest') {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                    } else {
                        usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                    }

                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $reductionAmount = 0;
                    foreach ($productsGrouped as $productGrouped) {
                        $remainingTimesToApplyPromo = (int)$action->products_nb_each;
                        foreach ($productGrouped['products'] as $product) {
                            $product = array_shift($productGrouped['products']);
                            $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                            $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity']);

                            $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                            $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;

                            $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                            if (!$remainingTimesToApplyPromo) {
                                break;
                            }
                        }
                    }

                    $cartRule->reduction_amount = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_tax;

                    break;

                /**
                 *
                 * Get a discount on A - Fixed discount
                 *
                 */
                case 100:
                    if ($minCoincidences) {
                        $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                        if (!$cartProductsFiltered) {
                            continue;
                        }

                        if ($action->apply_discount_sort == 'cheapest') {
                            usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                        } else {
                            usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                        }

                        $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                        if (!$productsGrouped) {
                            continue;
                        }

                        $reductionAmount = 0;
                        foreach ($productsGrouped as $productGrouped) {
                            if ((int)$productGrouped['cart_quantity'] >= (int)$action->apply_discount_to_nb) {
                                $remainingTimesToApplyPromo = min((int)($productGrouped['cart_quantity']/(int)$action->apply_discount_to_nb), (int)$minCoincidences);
                                foreach ($productGrouped['products'] as $product) {
                                    $product = array_shift($productGrouped['products']);
                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));

                                    $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)($product['cart_quantity']/(int)$action->apply_discount_to_nb));

                                    $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                    if ($productPrice > $action->reduction_amount) {
                                        $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                    } else {
                                        $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                    }

                                    $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                    if (!$remainingTimesToApplyPromo) {
                                        break;
                                    }
                                }
                            }
                        }

                        $cartRule->reduction_amount += $reductionAmount;
                        $cartRule->reduction_currency = (int)$action->reduction_currency;
                        $cartRule->reduction_tax = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Get a discount on A - Percentage discount
                 *
                 */
                case 101:
                    if ($minCoincidences) {
                        $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                        if (!$cartProductsFiltered) {
                            continue;
                        }

                        if ($action->apply_discount_sort == 'cheapest') {
                            usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                        } else {
                            usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                        }

                        $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                        if (!$productsGrouped) {
                            continue;
                        }

                        $reductionAmount = 0;
                        foreach ($productsGrouped as $productGrouped) {
                            if ((int)$productGrouped['cart_quantity'] >= (int)$action->apply_discount_to_nb) {
                                $remainingTimesToApplyPromo = min((int)($productGrouped['cart_quantity']/(int)$action->apply_discount_to_nb), (int)$minCoincidences);
                                foreach ($productGrouped['products'] as $product) {
                                    $product = array_shift($productGrouped['products']);
                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));
                                    $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)($product['cart_quantity']/(int)$action->apply_discount_to_nb));

                                    $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                    $reductionAmount += $productPrice*($action->reduction_percent/100)*$timesToApplyPromoInThisProduct;

                                    $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                    if (!$remainingTimesToApplyPromo) {
                                        break;
                                    }
                                }
                            }
                        }

                        $cartRule->reduction_amount += $reductionAmount;
                        $cartRule->reduction_currency = (int)$action->reduction_currency;
                        $cartRule->reduction_tax = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Get a discount on A - Fixed price
                 *
                 */
                case 102:
                    if ($minCoincidences) {
                        $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                        if (!$cartProductsFiltered) {
                            continue;
                        }

                        if ($action->apply_discount_sort == 'cheapest') {
                            usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_ASC)));
                        } else {
                            usort($cartProductsFiltered, $this->makeComparer(array('price_wt', SORT_DESC)));
                        }

                        $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                        if (!$productsGrouped) {
                            continue;
                        }

                        $reductionAmount = 0;
                        foreach ($productsGrouped as $productGrouped) {
                            if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->apply_discount_to_nb)) {
                                $remainingTimesToApplyPromo = min((int)($productGrouped['cart_quantity']/(int)$action->apply_discount_to_nb), (int)$minCoincidences);
                                foreach ($productGrouped['products'] as $product) {
                                    $product = array_shift($productGrouped['products']);
                                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));
                                    $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)($product['cart_quantity']/(int)$action->apply_discount_to_nb));

                                    $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                                    $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;

                                    $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                    if (!$remainingTimesToApplyPromo) {
                                        break;
                                    }
                                }
                            }
                        }

                        $cartRule->reduction_amount += $reductionAmount;
                        $cartRule->reduction_currency = (int)$action->reduction_currency;
                        $cartRule->reduction_tax = (int)$action->reduction_tax;
                    }

                    break;
                /**
                 *
                 * Get a discount on A - Fixed price
                 *
                 */
                case 400:
                    $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }
                    $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

                    if (!$productsGrouped) {
                        continue;
                    }

                    $num_ciclos = (int)$action->products_nb_each;
                    $reduction_ciclos = explode(',',$action->reduction_percent_ciclos);


                    $cicloActual = 0;
                    if(isset($this->context->customer->id) && $this->context->customer->id){
                        $sql = "SELECT IFNULL(SUM(qty_products),0)%".$num_ciclos." qty_products 
                        FROM "._DB_PREFIX_."quantity_discount_rule_order 
                        qdro where exists (select id_order from ps_orders o where qdro.id_order = o.id_order
                                        and o.id_customer = ".$this->context->customer->id." and current_state = 5) 
                        and id_quantity_discount_rule =  ".$quantityDiscountRule->id_quantity_discount_rule;
                        $cicloActual = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

                    }
                    $reductionAmount = 0;
                    $cicloActualAux = $cicloActual;

                    foreach ($productsGrouped as $productGrouped) {
                        foreach ($productGrouped['products'] as $product) {
                            $typeAcumulativo = true;
                            $product = array_shift($productGrouped['products']);
                            $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));
                            $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));

                            if($product['cart_quantity']>=$num_ciclos){
                                $cicloActualAux=0;
                            }


                            for($i=0;$i<$product['cart_quantity'];$i++){
                                if($cicloActualAux>$num_ciclos-1){
                                    $cicloActualAux=0;
                                }

                                $reductionCicloActual = $reduction_ciclos[$cicloActualAux];
                                $reductionAmount += $productPrice*($reductionCicloActual/100);
                                 $cicloActualAux++;
                            }
                        }
                    }

                    $cartRule->reduction_amount = $reductionAmount;
                    $cartRule->reduction_currency = (int)$action->reduction_currency;
                    $cartRule->reduction_tax = (int)$action->reduction_percent_tax;


                    break;
            }
        }

        /* END OF ACTIONS */

        if ((( isset($cartRule->reduction_amount) && $cartRule->reduction_amount > 0) || $typeAcumulativo) || (isset($cartRule->free_shipping) && $cartRule->free_shipping)) {
            $id_customer = (int)$this->context->customer->id;
            $cartRule->id_customer =  (isset($id_customer) && $id_customer) ? $id_customer : 0;

            $cartRule->date_to = $quantityDiscountRule->date_to;
            $cartRule->date_from = $quantityDiscountRule->date_from;
            $cartRule->quantity = 1;
            $cartRule->quantity_per_user = 1;
            $cartRule->minimum_amount = 0;
            $cartRule->partial_use = 0;
            $cartRule->cart_rule_restriction = 0;
            $cartRule->name = $quantityDiscountRule->name;

            if ($quantityDiscountRule->code) {
                $cartRule->code = $quantityDiscountRule->code;
            } else {
                $code = $quantityDiscountRule->code_prefix.Tools::strtoupper(Tools::passwdGen(12));
                $cartRule->code = $code;
            }

            $cartRule->active = 1;

            //Before adding it, check if this rule is already applied to prevent simultaneous async calls
            if ($this->isAlreadyInCart((int)$this->context->cart->id, (int)$quantityDiscountRule->id_quantity_discount_rule)) {
                return false;
            }

            if (!$cartRule->add()) {
                return false;
            }



            $fields = array(
                'id_cart' => (int)$this->context->cart->id,
                'id_quantity_discount_rule' => (int)$quantityDiscountRule->id,
                'id_cart_rule' => (int)$cartRule->id,
            );

            //Before adding it, check if this rule is already applied to prevent simultaneous async calls
            if ($this->isAlreadyInCart((int)$this->context->cart->id, (int)$quantityDiscountRule->id_quantity_discount_rule)) {
                $this->removeQuantityDiscountCartRule((int)$cartRule->id, (int)$this->context->cart->id);
                return false;
            }

            Db::getInstance()->insert('quantity_discount_rule_cart', $fields);

            $this->context->cart->addCartRule((int)$cartRule->id);

            //Clean caches
            //We need to force cache cleaning to get rules
            Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_ALL);
            Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_SHIPPING);
            Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_REDUCTION);
            Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_GIFT);
            if (version_compare(_PS_VERSION_, '1.5.4.0', '>=')) {
                Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_ALL_NOCAP);
            }

            return true;
        } else {
            return false;
        }


        return false;
    }

    public function getQuantityDiscountRulesAtCart($id_cart)
    {
        if (!(int)$id_cart || !(int)$id_cart > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`, qdrc.`id_cart_rule`, qdr.`code`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            WHERE `id_cart` = '.(int)$id_cart;

        return Db::getInstance()->executeS($sql);
    }

    public static function getQuantityDiscountRuleByCode($code)
    {
        if (!Validate::isCleanHtml($code)) {
            return false;
        }

        $sql = 'SELECT `id_quantity_discount_rule` FROM `'._DB_PREFIX_.'quantity_discount_rule` WHERE `code` = \''.pSQL($code).'\'';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public static function cartRuleGeneratedByAQuantityDiscountRuleCode($code)
    {
        if (!Validate::isCleanHtml($code)) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`, qdrc.`id_cart_rule`, qdr.`code`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            LEFT JOIN `'._DB_PREFIX_.'cart_rule` cr ON (qdrc.`id_cart_rule` = cr.`id_cart_rule`)
            WHERE qdr.code <> "" AND cr.code = \''.pSQL($code).'\'';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public function isQuantityDiscountRuleFromThisCart($id_quantity_discount_rule, $id_cart)
    {
        if (!(int)$id_quantity_discount_rule || !(int)$id_quantity_discount_rule > 0) {
            return false;
        }

        if (!(int)$id_cart || !(int)$id_cart > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            WHERE qdrc.`id_cart_rule` = '.(int)$id_quantity_discount_rule.' AND `id_cart` = '.(int)$id_cart;

        return Db::getInstance()->getValue($sql);
    }

    public function removeQuantityDiscountCartRule($id_cart_rule, $id_cart)
    {
        if (!(int)$id_cart_rule || !(int)$id_cart || !(int)$id_cart_rule > 0 || !(int)$id_cart > 0) {
            return false;
        }

        if (!$this->isQuantityDiscountRuleFromThisCart((int)$id_cart_rule, (int)$id_cart)) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $discount = new Discount((int)$id_cart_rule);

            if (!Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart`
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_cart_rule` = ".(int)$id_cart_rule)
               || !$this->context->cart->deleteDiscount((int)$discount->id_discount)
               || !$discount->delete()
               || !$this->context->cart->update()
                ) {
                return false;
            }
        } else {
            $cartRule = new CartRule((int)$id_cart_rule);

            if (!Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart`
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_cart_rule` =".(int)$id_cart_rule)
               || !$this->context->cart->removeCartRule((int)$id_cart_rule)
               || !$cartRule->delete()
                ) {
                return false;
            }
        }

        return true;
    }

    public function isQuantityDiscountRuleValid($cartRulesCodes = null)
    {
        //Check Date
        $now = date('Y-m-d H:i:s');
        if (strtotime($now) <= strtotime($this->date_from)
            || strtotime($now) >= strtotime($this->date_to)) {
            return false;
        }

        //Check if it's not out of stock
        if ($this->quantity != 0 && ($this->times_used >= $this->quantity)) {
            return false;
        }

        if ((int)$this->context->customer->id) {
            $quantityUsed = Db::getInstance()->getValue(
                "SELECT count(*)
                FROM `"._DB_PREFIX_."orders` o
                LEFT JOIN `"._DB_PREFIX_."order_cart_rule` od ON o.`id_order` = od.`id_order`
                LEFT JOIN `"._DB_PREFIX_."quantity_discount_rule_order` qdro ON od.`id_cart_rule` = qdro.`id_cart_rule`
                WHERE o.`id_customer` = ".(int)$this->context->customer->id."
                AND qdro.`id_quantity_discount_rule` = ".(int)$this->id
            );
            if ($quantityUsed + 1 > $this->quantity_per_user) {
                return false;
            }
        }

        if ($this->code && (!$cartRulesCodes || !is_array($cartRulesCodes) || !count($cartRulesCodes) || !in_array($this->code, $cartRulesCodes))) {
            return false;
        }

        if ($this->modules_exceptions) {
            $exceptions = explode(";", $this->modules_exceptions);
            $e = new Exception;
            foreach ($exceptions as $exception) {
                if (strpos($e->getTraceAsString(), $exception) !== false) {
                    return false;
                }
            }
        }

        return true;
    }

    public function validateQuantityDiscountRuleConditions()
    {
        if (!isset($this->context->cart)) {
            return false;
        }

        $cache_key = 'QuantityDiscountRule::validateQuantityDiscountRuleConditions_'.get_class($this).'_'.(int)$this->id_quantity_discount_rule.'_'.md5(Tools::jsonEncode($this->context->cart));

        if (!Cache::isStored($cache_key)) {
            $groupConditions = $this->getGroups(true);
            if (!$groupConditions) {
                $result = true;
                Cache::store($cache_key, $result);
                return $result;
            }

            foreach ($groupConditions as $groupCondition) {
                $conditions = $groupCondition->getConditions();

                if (!$conditions) {
                    $result = true;
                    Cache::store($cache_key, $result);
                    return $result;
                }

                foreach ($conditions as $condition) {
                    $groupValidationPassed = false;
                    $condition = new QuantityDiscountRuleCondition($condition['id_quantity_discount_rule_condition']);

                    switch ((int)$condition->id_type) {
                        /**
                         * Limit to a single customer
                         *
                         * Check if customer matches the condition customer
                         */
                        case 1:
                            if ((int)$this->context->customer->id == (int)$condition->id_customer) {
                                $groupValidationPassed = true;
                            }

                            break;

                        /**
                         * Customer must be suscribed to newsletter
                         *
                         * Check if customer is/or not subscribed to newsletter
                         */
                        case 2:
                            if ((int)$this->context->customer->id) {
                                $customer = new Customer((int)$this->context->customer->id);
                                if ((int)$customer->newsletter == (int)$condition->customer_newsletter) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Customer signed up between a date
                         *
                         * If condition date is by days, substract the number of days to now and check if customer subscribed before this date
                         * If condition date is by interval, check if customer signed up is between these dates
                         */
                        case 3:
                            $time_now = date('Y-m-d');

                            if ((int)$this->context->customer->id) {
                                $customer = new Customer((int)$this->context->customer->id);

                                if ($condition->customer_signedup_date_to == '0000-00-00 00:00:00') {
                                    $condition->customer_signedup_date_to = $time_now;
                                }

                                if (strtotime($condition->customer_signedup_date_from) <= strtotime($customer->date_add) &&
                                    strtotime($condition->customer_signedup_date_to) >= strtotime($customer->date_add)) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Customer and orders done
                         *
                         * If condition date is by days, get the orders from this day onwards.
                         * If condition date is by interval, get the orders from this interval.
                         */
                        case 4:
                            $time_now = date('Y-m-d H:i:s');

                            if ((int)$this->context->customer->id) {
                                $orderStates = $condition->getSelectedAssociatedRestrictions('order_state');
                                if ($condition->customer_orders_nb_days > 0) {
                                    $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_nb_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                } else {
                                    if ($condition->customer_orders_nb_date_to == '0000-00-00 00:00:00') {
                                        $condition->customer_orders_nb_date_to = $time_now;
                                    }

                                    $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_nb_date_from, $condition->customer_orders_nb_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                }

                                if ($condition->filter_by_product
                                    || $condition->filter_by_category
                                    || $condition->filter_by_attribute
                                    || $condition->filter_by_supplier
                                    || $condition->filter_by_manufacturer) {
                                    $productsTotal = 0;

                                    foreach ($orders as $key => $order) {
                                        $order = new Order($order);
                                        $orderProducts = $order->getProducts();

                                        if ($productsFiltered = $this->filterProducts($orderProducts, $condition)) {
                                            $productsTotal += (int)count($productsFiltered);
                                        }
                                    }

                                    if (!$this->compareValue((int)$condition->customer_orders_nb_prod_operator, $productsTotal, (int)$condition->customer_orders_nb_prod)) {
                                        unset($orders[$key]);
                                    }
                                }

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_nb_operator, (int)count($orders), (int)$condition->customer_orders_nb);
                            }


                           break;

                        /**
                         * Customer and amount spent
                         *
                         * If condition date is by days, get the orders from this day onwards.
                         * If condition date is by interval, get the orders from this interval.
                         *
                         * Acumulate amount and convert to currency
                         */
                        case 5:
                            if ((int)$this->context->customer->id && (int)$condition->customer_orders_amount_orders > 0) {
                                $time_now = date('Y-m-d H:i:s');
                                $totalAmount = 0;
                                $orders = array();
                                $orderStates = $condition->getAssociatedRestrictions('order_state', false, true);
                                if ($condition->customer_orders_amount_days > 0) {
                                    $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_amount_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                } else {
                                    if ($condition->customer_orders_amount_date_to == '0000-00-00 00:00:00') {
                                        $condition->customer_orders_amount_date_to = $time_now;
                                    }
                                    $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_amount_date_from, $condition->customer_orders_amount_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                }

                                if ((int)$condition->customer_orders_amount_orders && (count($orders) > (int)$condition->customer_orders_amount_orders)) {
                                    $orders = array_slice($orders, -(int)$condition->customer_orders_amount_orders, (int)$condition->customer_orders_amount_orders, true);
                                }

                                foreach ($orders as $order) {
                                    $order = new Order((int)$order);
                                    $cart = new Cart((int)$order->id_cart);

                                    $totalAmount += $cart->getOrderTotal((int)$condition->customer_orders_amount_tax, ($condition->customer_orders_amount_shipping ? Cart::BOTH : Cart::BOTH_WITHOUT_SHIPPING));

                                    /** Remove discounts */
                                    if (!$condition->customer_orders_amount_discount) {
                                        $totalAmount -= $cart->getOrderTotal((int)$condition->customer_orders_amount_tax, Cart::ONLY_DISCOUNTS);
                                    }
                                }

                                if ((int)$condition->customer_orders_amount_currency != $this->context->currency->id) {
                                    $totalAmount = Tools::convertPriceFull($totalAmount, new Currency((int)$condition->customer_orders_amount_currency), $this->context->currency);
                                }

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_amount_operator, $totalAmount, $condition->customer_orders_amount);
                            }

                            break;

                        /**
                         * Only for first order
                         *
                         * Check if it's/or not the customer's first order
                         *
                         */
                        case 6:
                            $firstOrder = Db::getInstance()->getValue(
                                'SELECT COUNT(`id_customer`) as nb
                                FROM `'._DB_PREFIX_.'orders`
                                WHERE `id_customer` = '.(int)$this->context->customer->id
                            );

                            if (($condition->customer_first_order && !$firstOrder) || (!$condition->customer_first_order && $firstOrder)) {
                                $groupValidationPassed = true;
                            }

                            break;

                        /**
                         * Total cart amount
                         *
                         * Get the cart amount or only the amount from products without special price.
                         * Add shipping cost. Substract gift products.
                         *
                         */
                        case 8:
                            if ($condition->cart_amount > 0) {
                                $cartAmount = $condition->cart_amount;
                                if ((int)$condition->cart_amount_currency != $this->context->currency->id) {
                                    $cartAmount = Tools::convertPriceFull($cartAmount, new Currency((int)$condition->cart_amount_currency), $this->context->currency);
                                }

                                /** Get the cart amount or only the amount from products without special price. */
                                if (!(int)$condition->apply_discount_to_special) {
                                    $cartProducts = $this->context->cart->getProducts();
                                    $cartTotal = 0;
                                    foreach ($cartProducts as $cartProduct) {
                                        if (!Product::getPriceStatic($cartProduct['id_product'], (int)$condition->cart_amount_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null), 6, null, true) > 0) {
                                            $cartTotal += Product::getPriceStatic($cartProduct['id_product'], (int)$condition->cart_amount_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null))*$cartProduct['cart_quantity'];
                                        }
                                    }
                                } else {
                                    $cartTotal = $this->context->cart->getOrderTotal((int)$condition->cart_amount_tax, Cart::ONLY_PRODUCTS);
                                }


                                /** Add shipping cost */
                                if ((int)$condition->cart_amount_shipping) {
                                    $cartTotal += $this->context->cart->getOrderTotal($condition->cart_amount_tax, Cart::ONLY_SHIPPING);
                                }

                                /** Remove discounts */
                                if (!(int)$condition->cart_amount_discount) {
                                    $cartTotal -= $this->context->cart->getOrderTotal($condition->cart_amount_tax, Cart::ONLY_DISCOUNTS);
                                }

                                $cartTotal -= $this->getGiftProductsValue($condition->cart_amount_tax);

                                $groupValidationPassed = $this->compareValue((int)$condition->cart_amount_operator, $cartTotal, $cartAmount);
                            }

                            break;

                        /**
                         * Cart weight
                         *
                         * Check the cart weight
                         *
                         */
                        case 9:
                            if ($condition->cart_weight > 0) {
                                $cartWeight = $this->context->cart->getTotalWeight();

                                $groupValidationPassed = $this->compareValue((int)$condition->cart_weight_operator, $cartWeight, $condition->cart_weight);
                            }

                            break;

                        /**
                         * Products in the cart
                         *
                         * Get all products from the cart. Remove those which don't meet the filters selected at the condition.
                         *
                         */
                        case 10:
                            $cartProducts = $this->context->cart->getProducts();
                            $cartProductsFiltered = $this->filterProducts($cartProducts, $condition);

                            if (!$cartProductsFiltered) {
                                break;
                            }

                            /**
                             * Check if all products from the cart must met the condition
                             */
                            if ((int)$condition->products_all_met && (count($cartProducts) != count($cartProductsFiltered))) {
                                break;
                            }

                            /** Quantity of selected products in cart */
                            if ((int)$condition->products_nb) {
                                $condition->group_products_by = 'all';
                                $productsGrouped = $this->groupProducts((int)$this->context->cart->id, $cartProductsFiltered, $condition);

                                $groupValidationPassed |= $this->compareValue((int)$condition->products_nb_operator, (int)$productsGrouped[0]['cart_quantity'], (int)$condition->products_nb);
                                if ($groupValidationPassed) {
                                    break;
                                }
                            } else {
                                $groupValidationPassed = true;
                            }

                            /** Number of different products from selected products in cart */
                            if ((int)$condition->products_nb_dif) {
                                $condition->group_products_by = 'product';
                                $productsGrouped = $this->groupProducts((int)$this->context->cart->id, $cartProductsFiltered, $condition);

                                $groupValidationPassed &= $this->compareValue((int)$condition->products_nb_dif_operator, (int)count($productsGrouped)-1, (int)$condition->products_nb_dif);
                            }

                            /** Amount of selected products in cart */
                            if ((int)$condition->products_amount) {
                                $cartAmount = 0;

                                foreach ($cartProductsFiltered as $cartProductFiltered) {
                                    $cartAmount += Product::getPriceStatic($cartProductFiltered['id_product'], (int)$condition->products_amount_tax, (int)$cartProductFiltered['id_product_attribute'])*$cartProductFiltered['cart_quantity'];
                                }

                                if ((int)$condition->products_amount_currency != $this->context->currency->id) {
                                    $cartAmount = Tools::convertPriceFull($condition->products_amount, new Currency((int)$condition->products_amount_currency), $this->context->currency);
                                }

                                $groupValidationPassed &= $this->compareValue((int)$condition->products_operator, $cartAmount, $condition->products_amount);
                            }

                            /** Number of products from the same category in cart */
                            if ((int)$condition->products_nb_dif_cat) {
                                $condition->group_products_by = 'category';
                                $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $condition);

                                $groupCategoryValidationPassed = false;
                                foreach ($productsGrouped as $productGrouped) {
                                    $groupCategoryValidationPassed |= $this->compareValue((int)$condition->products_nb_dif_cat_operator, (int)$productGrouped['cart_quantity'], (int)$condition->products_nb_dif_cat);
                                }

                                $groupValidationPassed &= $groupCategoryValidationPassed;
                            }

                            break;

                        /**
                         * Delivery country
                         *
                         * Get the order address delivery and check if it matches condition country
                         */
                        case 11:
                            if ((int)$this->context->cart->id_address_delivery) {
                                $address = new Address($this->context->cart->id_address_delivery);
                                $conditionCountry = $condition->getSelectedAssociatedRestrictions('country');

                                if (count($conditionCountry['selected'])) {
                                    if (in_array($address->id_country, array_column($conditionCountry['selected'], 'id_country'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            }

                            break;

                        /**
                         * Carrier
                         *
                         * Check if carrier matches with selected
                         */
                        case 12:
                            if (!$this->context->cart->id_carrier) {
                                $id_carrier = $this->context->cart->id_carrier;
                                $conditionCarriers = $condition->getSelectedAssociatedRestrictions('carrier');

                                if (count($conditionCarriers['selected'])) {
                                    if (in_array($id_carrier, array_column($conditionCarriers['selected'], 'id_carrier'))) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }

                            break;

                        /**
                         * Group selection
                         *
                         * Check if customer belongs to selected groups
                         */
                        case 13:
                            $conditionGroups = $condition->getSelectedAssociatedRestrictions('group');
                            if ((int)$this->context->customer->id && $condition->customer_default_group) {
                                $customer = new Customer((int)$this->context->customer->id);
                                if (count($conditionGroups['selected'])) {
                                    if (in_array((int)$customer->id_default_group, array_column($conditionGroups['selected'], 'id_group'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            } else {
                                $customerGroups = Customer::getGroupsStatic((int)$this->context->customer->id);
                                if (count($conditionGroups['selected'])) {
                                    foreach ($customerGroups as $customerGroup) {
                                        if (in_array($customerGroup, array_column($conditionGroups['selected'], 'id_group'))) {
                                            $groupValidationPassed = true;
                                            break;
                                        }
                                    }
                                }
                            }

                            break;

                        /**
                         * Shop selection
                         *
                         * Check if shop belongs to selected shops
                         */
                        case 14:
                            $conditionShops = $condition->getSelectedAssociatedRestrictions('shop');

                            if (count($conditionShops['selected'])) {
                                if (in_array($this->context->shop->id, array_column($conditionShops['selected'], 'id_shop'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }

                            break;

                        /**
                         * Delivery zone
                         *
                         * Check if delivery zone matches with selected
                         */
                        case 18:
                            if (!$this->context->cart->id_address_delivery) {
                                break;
                            }

                            $id_zone = Address::getZoneById($this->context->cart->id_address_delivery);
                            $conditionZones = $condition->getSelectedAssociatedRestrictions('zone');

                            if (count($conditionZones['selected'])) {
                                if (in_array($id_zone, array_column($conditionZones['selected'], 'id_zone'))) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Membership
                         *
                         * Compare number of days of membership with defined
                         */
                        case 19:
                            if ((int)$this->context->customer->id) {
                                $now = new DateTime(date('Y-m-d H:i:s'));
                                $customer = new Customer((int)$this->context->customer->id);
                                $diff = $now->diff(new DateTime(date($customer->date_add)))->format("%a");

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_membership_operator, (int)$diff, (int)$condition->customer_membership);
                            }

                            break;

                        /**
                         * Birthday
                         *
                         * Get day/month of customer's birthday and compare with current day
                         */
                        case 20:
                            if ((int)$this->context->customer->id) {
                                $now = date('m-d');
                                $customer = new Customer((int)$this->context->customer->id);

                                if ($now == date('m-d', strtotime($customer->birthday))) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /*
                         * By gender
                         */
                        case 21:
                            if ((int)$this->context->customer->id) {
                                $customer = new Customer((int)$this->context->customer->id);
                                $conditionGenders = $condition->getSelectedAssociatedRestrictions('gender');

                                if ($customer->id_gender) {
                                    if (count($conditionGenders['selected'])) {
                                        if (in_array($customer->id_gender, array_column($conditionGenders['selected'], 'id_gender'))) {
                                            $groupValidationPassed = true;
                                        }
                                    }
                                }
                            }

                            break;

                        /*
                         * By currency
                         */
                        case 22:
                            $conditionCurrencies = $condition->getSelectedAssociatedRestrictions('currency');

                            if (count($conditionCurrencies['selected'])) {
                                if (in_array($this->context->cart->id_currency, array_column($conditionCurrencies['selected'], 'id_currency'))) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Customer age
                         *
                         * Get day/month of customer's birthday and compare if it's between defined age
                         */
                        case 23:
                            if ((int)$this->context->customer->id) {
                                $now = date('m-d');
                                $customer = new Customer((int)$this->context->customer->id);
                                $birthDate = $customer->birthday;
                                if ($birthDate && $birthDate != '0000-00-00') {
                                    $age = date_diff(date_create($birthDate), date_create('now'))->y;
                                    if ($age >= $condition->customer_years_from && $age <= $condition->customer_years_to) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }

                            break;

                        /**
                         * Delivery state
                         *
                         * Check if delivery state matches with selected
                         */
                        case 24:
                            if (!(int)$this->context->cart->id_address_delivery) {
                                $address = new Address($this->context->cart->id_address_delivery);
                                $conditionState = $condition->getSelectedAssociatedRestrictions('state');

                                if (count($conditionState['selected'])) {
                                    if (in_array($address->id_country, array_column($conditionState['selected'], 'id_state'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            }

                            break;
                    }

                    if (!$groupValidationPassed) {
                        break;
                    }
                }

                /**
                 * Logical OR between each group of conditions
                 *
                 * If any of the group condition is valid, then rule must be applied
                 */
                if ($groupValidationPassed) {
                    $result = true;
                    Cache::store($cache_key, $result);
                    return $result;
                }
            }
        } else {
            return Cache::retrieve($cache_key);
        }

        $result = false;
        Cache::store($cache_key, $result);
        return $result;
    }

    public function validateCartRuleForMessages($id_product = null, $strongValidate = true)
    {
        $groupConditions = $this->getGroups(true);

        foreach ($groupConditions as $groupCondition) {
            $conditions = $groupCondition->getConditions();

            if (!$conditions) {
                continue;
            }

            foreach ($conditions as $condition) {
                $groupValidationPassed = false;
                $condition = new QuantityDiscountRuleCondition($condition['id_quantity_discount_rule_condition']);

                switch ((int)$condition->id_type) {
                    /**
                     * Limit to a single customer
                     *
                     * Check if customer matches the condition customer
                     */
                    case 1:
                        if ((int)$this->context->customer->id == (int)$condition->id_customer) {
                            $groupValidationPassed = true;
                        }

                        break;

                    /**
                     * Customer must be suscribed to newsletter
                     *
                     * Check if customer is/or not subscribed to newsletter
                     */
                    case 2:
                        if ((int)$this->context->customer->id) {
                            $customer = new Customer((int)$this->context->customer->id);
                            if ((int)$customer->newsletter == (int)$condition->customer_newsletter) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /**
                     * Customer signed up between a date
                     *
                     * If condition date is by days, substract the number of days to now and check if customer subscribed before this date
                     * If condition date is by interval, check if customer signed up is between these dates
                     */
                    case 3:
                        $time_now = date('Y-m-d');

                        if ((int)$this->context->customer->id) {
                            $customer = new Customer((int)$this->context->customer->id);

                            if ($condition->customer_signedup_date_to == '0000-00-00 00:00:00') {
                                $condition->customer_signedup_date_to = $time_now;
                            }

                            if (strtotime($condition->customer_signedup_date_from) <= strtotime($customer->date_add) &&
                                strtotime($condition->customer_signedup_date_to) >= strtotime($customer->date_add)) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /**
                     * Customer and orders done
                     *
                     * If condition date is by days, get the orders from this day onwards.
                     * If condition date is by interval, get the orders from this interval.
                     */
                    case 4:
                        $time_now = date('Y-m-d H:i:s');

                        if ((int)$this->context->customer->id) {
                            $orderStates = $condition->getSelectedAssociatedRestrictions('order_state');
                            if ($condition->customer_orders_nb_days > 0) {
                                $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_nb_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            } else {
                                if ($condition->customer_orders_nb_date_to == '0000-00-00 00:00:00') {
                                    $condition->customer_orders_nb_date_to = $time_now;
                                }

                                $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_nb_date_from, $condition->customer_orders_nb_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            }

                            if ($condition->filter_by_product
                                || $condition->filter_by_category
                                || $condition->filter_by_attribute
                                || $condition->filter_by_supplier
                                || $condition->filter_by_manufacturer) {
                                $productsTotal = 0;

                                foreach ($orders as $key => $order) {
                                    $order = new Order($order);
                                    $orderProducts = $order->getProducts();

                                    if ($productsFiltered = $this->filterProducts($orderProducts, $condition)) {
                                        $productsTotal += (int)count($productsFiltered);
                                    }
                                }

                                if (!$this->compareValue((int)$condition->customer_orders_nb_prod_operator, $productsTotal, (int)$condition->customer_orders_nb_prod)) {
                                    unset($orders[$key]);
                                }
                            }

                            $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_nb_operator, (int)count($orders), (int)$condition->customer_orders_nb);
                        }

                        break;

                    /**
                     * Customer and amount spent
                     *
                     * If condition date is by days, get the orders from this day onwards.
                     * If condition date is by interval, get the orders from this interval.
                     *
                     * Acumulate amount and convert to currency
                     */
                    case 5:
                        if ((int)$this->context->customer->id && (int)$condition->customer_orders_amount_orders > 0) {
                            $time_now = date('Y-m-d H:i:s');
                            $totalAmount = 0;
                            $orders = array();
                            $orderStates = $condition->getAssociatedRestrictions('order_state', false, true);
                            if ($condition->customer_orders_amount_days > 0) {
                                $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_amount_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            } else {
                                if ($condition->customer_orders_amount_date_to == '0000-00-00 00:00:00') {
                                    $condition->customer_orders_amount_date_to = $time_now;
                                }
                                $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_amount_date_from, $condition->customer_orders_amount_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            }

                            if ((int)$condition->customer_orders_amount_orders && (count($orders) > (int)$condition->customer_orders_amount_orders)) {
                                $orders = array_slice($orders, -(int)$condition->customer_orders_amount_orders, (int)$condition->customer_orders_amount_orders, true);
                            }

                            foreach ($orders as $order) {
                                $order = new Order((int)$order);
                                $cart = new Cart((int)$order->id_cart);

                                $totalAmount += $cart->getOrderTotal((int)$condition->customer_orders_amount_tax, ($condition->customer_orders_amount_shipping ? Cart::BOTH : Cart::BOTH_WITHOUT_SHIPPING));

                                /** Remove discounts */
                                if (!$condition->customer_orders_amount_discount) {
                                    $totalAmount -= $cart->getOrderTotal((int)$condition->customer_orders_amount_tax, Cart::ONLY_DISCOUNTS);
                                }
                            }

                            if ((int)$condition->customer_orders_amount_currency != $this->context->currency->id) {
                                $totalAmount = Tools::convertPriceFull($totalAmount, new Currency((int)$condition->customer_orders_amount_currency), $this->context->currency);
                            }

                            $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_amount_operator, $totalAmount, $condition->customer_orders_amount);
                        }

                        break;

                    /**
                     * Only for first order
                     *
                     * Check if it's/or not the customer's first order
                     *
                     */
                    case 6:
                        $firstOrder = Db::getInstance()->getValue(
                            'SELECT COUNT(`id_customer`) as nb
                            FROM `'._DB_PREFIX_.'orders`
                            WHERE `id_customer` = '.(int)$this->context->customer->id
                        );

                        if (($condition->customer_first_order && !$firstOrder) || (!$condition->customer_first_order && $firstOrder)) {
                            $groupValidationPassed = true;
                        }

                        break;

                    case 8:
                    case 9:
                        $groupValidationPassed = true;
                        break;
                    case 11:
                        if ((int)$this->context->cart->id_address_delivery) {
                            $address = new Address($this->context->cart->id_address_delivery);
                            $conditionCountry = $condition->getSelectedAssociatedRestrictions('country');

                            if (count($conditionCountry['selected'])) {
                                if (in_array($address->id_country, array_column($conditionCountry['selected'], 'id_country'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }
                        }
                        break;
                    case 12:
                    case 18:
                        $groupValidationPassed = true;
                        break;

                    case 10:
                        if (!$strongValidate || (Tools::getValue('id_product') && $this->productIsInFilter(Tools::getValue('id_product'), $condition))) {
                            $groupValidationPassed = true;
                        }

                        if (!$strongValidate || (isset($id_product) && $this->productIsInFilter($id_product, $condition))) {
                            $groupValidationPassed = true;
                        }

                        break;

                    /**
                     * Group selection
                     *
                     * Check if customer belongs to selected groups
                     */
                    case 13:
                        $conditionGroups = $condition->getSelectedAssociatedRestrictions('group');
                        if ((int)$this->context->customer->id && $condition->customer_default_group) {
                            $customer = new Customer((int)$this->context->customer->id);
                            if (count($conditionGroups['selected'])) {
                                if (in_array((int)$customer->id_default_group, array_column($conditionGroups['selected'], 'id_group'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }
                        } else {
                            $customerGroups = Customer::getGroupsStatic((int)$this->context->customer->id);
                            if (count($conditionGroups['selected'])) {
                                foreach ($customerGroups as $customerGroup) {
                                    if (in_array($customerGroup, array_column($conditionGroups['selected'], 'id_group'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            }
                        }

                        break;

                    /**
                     * Shop selection
                     *
                     * Check if shop belongs to selected shops
                     */
                    case 14:
                        $conditionShops = $condition->getSelectedAssociatedRestrictions('shop');

                        if (count($conditionShops['selected'])) {
                            if (in_array($this->context->shop->id, array_column($conditionShops['selected'], 'id_shop'))) {
                                $groupValidationPassed = true;
                                break;
                            }
                        }

                        break;

                    /**
                     * Membership
                     *
                     * Compare number of days of membership with defined
                     */
                    case 19:
                        if ((int)$this->context->customer->id) {
                            $now = new DateTime(date('Y-m-d H:i:s'));
                            $customer = new Customer((int)$this->context->customer->id);
                            $diff = $now->diff(new DateTime(date($customer->date_add)))->format("%a");

                            $groupValidationPassed = $this->compareValue((int)$condition->customer_membership_operator, (int)$diff, (int)$condition->customer_membership);
                        }

                        break;

                    /**
                     * Birthday
                     *
                     * Get day/month of customer's birthday and compare with current day
                     */
                    case 20:
                        if ((int)$this->context->customer->id) {
                            $now = date('m-d');
                            $customer = new Customer((int)$this->context->customer->id);

                            if ($now == date('m-d', strtotime($customer->birthday))) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /*
                     * By gender
                     */
                    case 21:
                        if ((int)$this->context->customer->id) {
                            $customer = new Customer((int)$this->context->customer->id);
                            $conditionGenders = $condition->getSelectedAssociatedRestrictions('gender');

                            if ($customer->id_gender) {
                                if (count($conditionGenders['selected'])) {
                                    if (in_array($customer->id_gender, array_column($conditionGenders['selected'], 'id_gender'))) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }
                        }

                        break;

                    /*
                     * By currency
                     */
                    case 22:
                        $conditionCurrencies = $condition->getSelectedAssociatedRestrictions('currency');

                        if (count($conditionCurrencies['selected'])) {
                            if (in_array($this->context->cart->id_currency, array_column($conditionCurrencies['selected'], 'id_currency'))) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;
                }

                if (!$groupValidationPassed) {
                    break;
                }
            }

            if (!$groupValidationPassed) {
                return false;
            }
        }

        if (!$strongValidate) {
            $actions = $this->getActions(true);

            foreach ($actions as $action) {
                switch ((int)$action->id_type) {
                    /**
                     *
                     * Shipping cost - Fixed discount
                     *
                     */
                    case 6:
                    case 7:
                    case 8:
                    case 12:
                    case 13:
                    case 14:
                    case 15:
                    case 16:
                    case 17:
                    case 18:
                    case 19:
                    case 20:
                    case 21:
                    case 22:
                    case 27:
                    case 28:
                    case 29:
                    case 100:
                    case 101:
                    case 102:
                        if (Tools::getValue('id_product') && $this->productIsInFilter(Tools::getValue('id_product'), $action)) {
                            return true;
                        }

                        if (isset($id_product) && $this->productIsInFilter($id_product, $action)) {
                            return true;
                        }

                        return false;
                }
            }
        }

        return true;
    }

    public function compatibleCartRules()
    {
        if (!$this->compatible_cart_rules) {
            $cartRules = $this->context->cart->getCartRules();
            $quantityDiscountRulesAtCart = $this->getQuantityDiscountRulesAtCart((int)$this->context->cart->id);

            if (count($cartRules) > count($quantityDiscountRulesAtCart)) {
                return false;
            }
        }

        return true;
    }

    protected function compareValue($operator, $a, $b)
    {
        switch ((int)$operator) {
            case 0:
                if ($a < $b) {
                    return false;
                }
                break;
            case 1:
                if ($a != $b) {
                    return false;
                }
                break;
            case 2:
                if ($a > $b) {
                    return false;
                }
                break;
        }

        return true;
    }

    protected function makeComparer()
    {
        // Normalize criteria up front so that the comparer finds everything tidy
        $criteria = func_get_args();
        foreach ($criteria as $index => $criterion) {
            $criteria[$index] = is_array($criterion)
                ? array_pad($criterion, 3, null)
                : array($criterion, SORT_ASC, null);
        }

        return function ($first, $second) use (&$criteria) {
            foreach ($criteria as $criterion) {
                // How will we compare this round?
                list($column, $sortOrder, $projection) = $criterion;
                $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

                // If a projection was defined project the values now
                if ($projection) {
                    $lhs = call_user_func($projection, $first[$column]);
                    $rhs = call_user_func($projection, $second[$column]);
                } else {
                    $lhs = $first[$column];
                    $rhs = $second[$column];
                }

                // Do the actual comparison; do not return if equal
                if ($lhs < $rhs) {
                    return -1 * $sortOrder;
                } else if ($lhs > $rhs) {
                    return 1 * $sortOrder;
                }
            }

            return 0; // tiebreakers exhausted, so $first == $second
        };
    }

    protected function filterProducts($cartProducts, $object)
    {
        if (!is_array($cartProducts) || !is_object($object)) {
            return;
        }

        $cache_key = 'QuantityDiscountRule::filterProducts_'.get_class($object).'_'.(int)$object->getId().'_'.md5(Tools::jsonEncode($cartProducts));
        if (!Cache::isStored($cache_key)) {
            $cartRules = $this->context->cart->getCartRules(CartRule::FILTER_ACTION_GIFT);

            if ($object->filter_by_product) {
                $restrictionProducts = $object->getSelectedAssociatedRestrictions('product');
            }

            if ($object->filter_by_category) {
                $restrictionCategories = $object->getSelectedAssociatedRestrictions('category');
            }

            if ($object->filter_by_attribute) {
                $restrictionAttributes = $object->getSelectedAssociatedRestrictions('attribute');
            }

            if ($object->filter_by_supplier) {
                $restrictionSuppliers = $object->getSelectedAssociatedRestrictions('supplier');
            }

            if ($object->filter_by_manufacturer) {
                $restrictionManufacturers = $object->getSelectedAssociatedRestrictions('manufacturer');
            }

            foreach ($cartProducts as $key => $cartProduct) {
                /* Remove gift products */
                foreach ($cartRules as $cartRule) {
                    if ($cartRule['gift_product']) {
                        if (empty($cartProduct['gift']) && $cartProduct['id_product'] == $cartRule['gift_product'] && $cartProduct['id_product_attribute'] == $cartRule['gift_product_attribute']) {
                            if ($cartProduct['cart_quantity'] > 1) {
                                $cartProducts[$key]['cart_quantity']--;
                            } else {
                                unset($cartProducts[$key]);
                                continue;
                            }
                        }
                    }
                }

                $productsBeforeFilter = count($cartProducts);

                /** Check product */
                if ($object->filter_by_product && (!isset($restrictionProducts['selected']) || !in_array((int)$cartProduct['id_product'], array_column($restrictionProducts['selected'], 'id_product')))) {
                    unset($cartProducts[$key]);
                    continue;
                }

                /** Check categories */
                if ($object->filter_by_category) {
                    if ($object->products_default_category) {
                        if (!isset($restrictionCategories['selected']) || !in_array((int)$cartProduct['id_category_default'], array_column($restrictionCategories['selected'], 'id_category'))) {
                            unset($cartProducts[$key]);
                            continue;
                        }
                    } else {
                        $productIsInCategory = false;
                        $productCategories = Product::getProductCategories($cartProduct['id_product']);
                        foreach ($productCategories as $productCategory) {
                            if (isset($restrictionCategories['selected']) && in_array((int)$productCategory, array_column($restrictionCategories['selected'], 'id_category'))) {
                                $productIsInCategory = true;
                                break;
                            }
                        }

                        if (!$productIsInCategory) {
                            unset($cartProducts[$key]);
                            continue;
                        }
                    }
                }

                /** Check attributes */
                if ($object->filter_by_attribute) {
                    $product = new Product((int)$cartProduct['id_product']);

                    $productHasCombination = false;
                    if (isset($cartProduct['id_product_attribute'])) {
                        if ($combinations = $product->getAttributeCombinationsById((int)$cartProduct['id_product_attribute'], (int)$this->context->cart->id_lang)) {
                            foreach ($combinations as $combination) {
                                //CAUTION! Inverse logic. If product has any of the attributes selected, is considered valid
                                if ((int)$combination['id_attribute'] && in_array((int)$combination['id_attribute'], array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                                    $productHasCombination = true;
                                    break;
                                }
                            }
                        } elseif (in_array(999999, array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                            $productHasCombination = true;
                        }
                    }

                    if (!$productHasCombination) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Check supplier */
                if ($object->filter_by_supplier) {
                    if ((!(int)$cartProduct['id_supplier']  && !in_array(999999, array_column($restrictionSuppliers['selected'], 'id_supplier')))
                        || ((int)$cartProduct['id_supplier'] && !in_array((int)$cartProduct['id_supplier'], array_column($restrictionSuppliers['selected'], 'id_supplier')))) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Check manufacturer */
                if ($object->filter_by_manufacturer) {
                    if ((!(int)$cartProduct['id_manufacturer'] && !in_array(999999, array_column($restrictionManufacturers['selected'], 'id_manufacturer')))
                        || ((int)$cartProduct['id_manufacturer'] && !in_array((int)$cartProduct['id_manufacturer'], array_column($restrictionManufacturers['selected'], 'id_manufacturer')))) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Filter by price */
                if ($object->filter_by_price) {
                    $price = Product::getPriceStatic($cartProduct['id_product'], (int)$object->product_price_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null));
                    $price = Tools::convertPriceFull($price, $this->context->currency, new Currency($object->product_price_currency));
                    if (!$this->compareValue((int)$object->product_price_operator, $price, $object->product_price_amount)) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Filter by stock */
                if ($object->filter_by_stock) {
                    if (!$this->compareValue((int)$object->stock_operator, $cartProduct['stock_quantity'], $object->stock)) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Discard products with special price if configured */
                if (!(int)$object->apply_discount_to_special && Product::getPriceStatic($cartProduct['id_product'], false, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null), 6, null, true) > 0) {
                    unset($cartProducts[$key]);
                    continue;
                }
            }

            if (isset($object->products_all_met) && $object->products_all_met && $productsBeforeFilter != count($cartProducts)) {
                $result = array();
            } else {
                $result = $cartProducts;
            }

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    protected function productIsInFilter($id_product, $object)
    {
        if (!((int)$id_product)) {
            return false;
        }

        $cache_key = 'QuantityDiscountRule::productIsInFilter_'.(int)$id_product.'_'.md5(Tools::jsonEncode($object));
        if (!Cache::isStored($cache_key)) {
            $product = new Product((int)$id_product);

            /** Check product */
            if ($object->filter_by_product) {
                $restrictionProducts = $object->getSelectedAssociatedRestrictions('product');
                if (!isset($restrictionProducts['selected']) || !in_array((int)$id_product, array_column($restrictionProducts['selected'], 'id_product'))) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check attributes */
            if ($object->filter_by_attribute) {
                $restrictionAttributes = $object->getSelectedAssociatedRestrictions('attribute');
                $product = new Product((int)$id_product);
                $combinations = $product->getAttributeCombinations((int)$this->context->cart->id_lang);

                $productHasCombination = false;
                if ($combinations) {
                    foreach ($combinations as $combination) {
                        //CAUTION! Inverse logic. If product has any of the attributes selected, is considered valid
                        if ((int)$combination['id_attribute'] && in_array((int)$combination['id_attribute'], array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                            $productHasCombination = true;
                            break;
                        }
                    }
                } elseif (in_array(999999, array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                    $productHasCombination = true;
                }

                if (!$productHasCombination) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check categories */
            if ($object->filter_by_category) {
                $restrictionCategories = $object->getSelectedAssociatedRestrictions('category');
                if ($object->products_default_category) {
                    if (!isset($restrictionCategories['selected']) || !in_array((int)$product->id_category_default, array_column($restrictionCategories['selected'], 'id_category'))) {
                        Cache::store($cache_key, false);
                        return false;
                    }
                } else {
                    $productIsInCategory = false;
                    $productCategories = Product::getProductCategories((int)$id_product);
                    foreach ($productCategories as $productCategory) {
                        if (isset($restrictionCategories['selected']) && in_array((int)$productCategory, array_column($restrictionCategories['selected'], 'id_category'))) {
                            $productIsInCategory = true;
                            break;
                        }
                    }

                    if (!$productIsInCategory) {
                        Cache::store($cache_key, false);
                        return false;
                    }
                }
            }

            /** Check supplier */
            if ($object->filter_by_supplier) {
                $restrictionSuppliers = $object->getSelectedAssociatedRestrictions('supplier');
                if ((!(int)$product->id_supplier  && !in_array(999999, array_column($restrictionSuppliers['selected'], 'id_supplier')))
                    || ((int)$product->id_supplier && !in_array((int)$product->id_supplier, array_column($restrictionSuppliers['selected'], 'id_supplier')))) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check manufacturer */
            if ($object->filter_by_manufacturer) {
                $restrictionManufacturers = $object->getSelectedAssociatedRestrictions('manufacturer');
                if ((!(int)$product->id_manufacturer  && !in_array(999999, array_column($restrictionManufacturers['selected'], 'id_manufacturer')))
                    || ((int)$product->id_manufacturer && !in_array((int)$product->id_manufacturer, array_column($restrictionManufacturers['selected'], 'id_manufacturer')))) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Filter by price */
            if ($object->filter_by_price) {
                $price = Product::getPriceStatic((int)$id_product, (int)$object->product_price_tax);
                $price = Tools::convertPriceFull($price, $this->context->currency, new Currency($object->product_price_currency));
                if (!$this->compareValue((int)$object->product_price_operator, $price, $object->product_price_amount)) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Filter by stock */
            if ($object->filter_by_stock) {
                if (!$this->compareValue((int)$object->stock_operator, $product->stock_quantity, $object->stock)) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Discard products with special price if configured */
            if (!(int)$object->apply_discount_to_special && Product::getPriceStatic((int)$id_product, false, null, 6, null, true) > 0) {
                Cache::store($cache_key, false);
                return false;
            }

            Cache::store($cache_key, true);
            return true;
        } else {
            return Cache::retrieve($cache_key);
        }
    }

    protected function groupProducts($id_cart, $products, $object)
    {
        if (!is_array($products)) {
            return false;
        }

        if (!$key = $object->group_products_by) {
            return false;
        }

        $cache_key = 'QuantityDiscountRule::groupProducts_'.(int)$id_cart.'_'.$key.'_'.md5(Tools::jsonEncode($products));

        if (!Cache::isStored($cache_key)) {
            switch ($key) {
                case 'product':
                    if ((int)$object->products_nb_same_attributes) {
                        $key = 'by_product';
                    } else {
                        $key = 'by_product_attribute';
                    }
                    break;

                case 'category':
                    if (!$object->filter_by_category || $object->products_default_category) {
                        $key = 'by_default_category';
                    } elseif ($object->filter_by_category) {
                        if ($object->products_default_category) {
                            $key = 'by_default_category';
                        } else {
                            $key = 'by_category';
                        }
                    }
                    break;

                case 'supplier':
                    $key = 'by_supplier';
                    break;

                case 'manufacturer':
                    $key = 'by_manufacturer';
                    break;

                case 'all':
                    $key = 'by_all';
                    break;
            }

            $productsGrouped = array();

            foreach ($products as $product) {
                if ($key == 'by_product'
                    || $key == 'by_attribute'
                    || $key == 'by_product_attribute'
                    || $key == 'by_default_category'
                    || $key == 'by_supplier'
                    || $key == 'by_manufacturer') {
                    switch ($key) {
                        case 'by_product':
                            $index = $product['id_product'];
                            break;
                        case 'by_attribute':
                            $index = $product['id_product_attribute'];
                            break;
                        case 'by_product_attribute':
                            $index = $product['id_product'].'-'.$product['id_product_attribute'];
                            break;
                        case 'by_default_category':
                            $index = $product['id_category_default'];
                            break;
                        case 'by_supplier':
                            $index = $product['id_supplier'];
                            break;
                        case 'by_manufacturer':
                            $index = $product['id_manufacturer'];
                            break;
                    }

                    $productsGrouped[$index]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_product'] = (int)$product['id_product'];
                    $productsGrouped[$index]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['cart_quantity'] = (int)$product['cart_quantity'];
                    $productsGrouped[$index]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                    $productsGrouped[$index]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_category_default'] = (int)$product['id_category_default'];

                    if (isset($productsGrouped[$index]['cart_quantity'])) {
                        $productsGrouped[$index]['cart_quantity'] += (int)$product['cart_quantity'];
                    } else {
                        $productsGrouped[$index]['cart_quantity'] = (int)$product['cart_quantity'];
                    }
                } elseif ($key == 'by_category') {
                    $productCategories = Product::getProductCategories($product['id_product']);
                    $productIsInCategory = false;
                    $categories = $object->getAssociatedRestrictions('category', true, true);

                    foreach ($productCategories as $productCategory) {
                        if (in_array((int)$productCategory, array_column($categories['selected'], 'id_category'))) {
                            $productIsInCategory[] = $productCategory;
                            continue;
                        }
                    }

                    if ($productIsInCategory) {
                        foreach ($productIsInCategory as $productCategory) {
                            $productsGrouped[$productCategory]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_product'] = (int)$product['id_product'];
                            $productsGrouped[$productCategory]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['cart_quantity'] = (int)$product['cart_quantity'];
                            $productsGrouped[$productCategory]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                            $productsGrouped[$productCategory]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_category_default'] = (int)$product['id_category_default'];

                            if (isset($productsGrouped[$productCategory]['cart_quantity'])) {
                                $productsGrouped[$productCategory]['cart_quantity'] += (int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[$productCategory]['cart_quantity'] = (int)$product['cart_quantity'];
                            }
                        }
                    }
                } elseif ($key == 'by_all') {
                    $productsGrouped[0]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_product'] = (int)$product['id_product'];
                    $productsGrouped[0]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['cart_quantity'] = (int)$product['cart_quantity'];
                    $productsGrouped[0]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                    $productsGrouped[0]['products'][$product['id_product'].'-'.$product['id_product_attribute']]['id_category_default'] = (int)$product['id_category_default'];

                    if (isset($productsGrouped[0]['cart_quantity'])) {
                        $productsGrouped[0]['cart_quantity'] += (int)$product['cart_quantity'];
                    } else {
                        $productsGrouped[0]['cart_quantity'] = (int)$product['cart_quantity'];
                    }
                }
            }

            $result = $productsGrouped;
            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public static function getOrdersIdByDateAndState($date_from, $date_to, $id_order_states = null, $id_customer = null, $type = null)
    {
        $sql = 'SELECT `id_order`
                FROM `'._DB_PREFIX_.'orders`
                WHERE DATE_ADD(`date_upd`, INTERVAL -1 DAY) <= \''.pSQL($date_to).'\' AND `date_upd` >= \''.pSQL($date_from).'\'
                    '.Shop::addSqlRestriction()
                    .($type ? ' AND `'.pSQL((string)$type).'_number` != 0' : '')
                    .($id_customer ? ' AND `id_customer` = '.(int)($id_customer) : '')
                    .($id_order_states ? ' AND `current_state` IN ('.implode(',', array_map('intval', $id_order_states)).')' : '');

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $orders = array();
        foreach ($result as $order) {
            $orders[] = (int)($order['id_order']);
        }

        return $orders;
    }

    public function isAlreadyInCart($id_cart, $id_quantity_discount_rule)
    {
        if (!(int)$id_cart || !(int)$id_quantity_discount_rule) {
            return false;
        }

        $sql = 'SELECT id_cart_rule
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart`
            WHERE `id_cart` = '.(int)$id_cart.'
                AND `id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule;

        $result = Db::getInstance()->getRow($sql);

        if (isset($result['id_cart_rule'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isCurrentlyUsed($table = null, $has_active_column = false)
    {
        if ($table === null) {
            $table = self::$definition['table'];
        }

        $query = new DbQuery();
        $query->select('`id_'.bqSQL($table).'`');
        $query->from($table);
        if ($has_active_column) {
            $query->where('`active` = 1');
        }

        return (bool)Db::getInstance()->getValue($query);
    }

    public static function removeUnusedRules()
    {
        $sql = 'SELECT `id_quantity_discount_rule`, `id_cart_rule`
                FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
                WHERE qdrc.`id_cart_rule` NOT IN (SELECT `id_cart_rule` FROM `'._DB_PREFIX_.'order_cart_rule`)';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($result as $rule) {
            $cartRule = new CartRule((int)$rule['id_cart_rule']);
            $cartRule->delete();
            $quantityDiscountRule = new QuantityDiscountRule((int)$rule['id_quantity_discount_rule']);
            $quantityDiscountRule->delete();
        }

        return true;
    }

    public static function removeUnusedRulesByQuantityDiscountRule($id_quantity_discount_rule)
    {
        if (!(int)$id_quantity_discount_rule > 0) {
            return;
        }

        $sql = 'SELECT `id_quantity_discount_rule`, `id_cart_rule`
                FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
                WHERE qdrc.`id_cart_rule` NOT IN (SELECT `id_cart_rule` FROM `'._DB_PREFIX_.'order_cart_rule`)
                    AND `id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule;

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($result as $rule) {
            $cartRule = new CartRule((int)$rule['id_cart_rule']);
            $cartRule->delete();
        }

        return true;
    }

    public function getGiftProductsValue($with_taxes)
    {
        $products = $this->context->cart->getProducts();
        $cartRules = $this->context->cart->getCartRules(CartRule::FILTER_ACTION_GIFT);

        $amount = 0;

        /** Remove amount of gift products */
        foreach ($cartRules as $cartRule) {
            if ($cartRule['gift_product']) {
                foreach ($products as $product) {
                    if (empty($product['gift']) && $product['id_product'] == $cartRule['gift_product'] && $product['id_product_attribute'] == $cartRule['gift_product_attribute']) {
                        $amount += Tools::ps_round($product[$with_taxes ? 'price_wt' : 'price'], (int)$this->context->currency->decimals * _PS_PRICE_COMPUTE_PRECISION_);
                    }
                }
            }
        }

        return $amount;
    }

    public function getQtyAffectedProducts()
    {
        $cartProducts = $this->context->cart->getProducts();

        $actions = $this->getActions(true);

        $qty = 0;

        foreach ($actions as $action) {
            $cartProductsFiltered = $this->filterProducts($cartProducts, $action);

            if (!$cartProductsFiltered) {
                continue;
            }

            $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action);

            if (!$productsGrouped) {
                continue;
            }


            foreach ($productsGrouped as $productGrouped) {
                foreach ($productGrouped['products'] as $product) {
                    $product = array_shift($productGrouped['products']);
                    $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null));
                    $productPrice = Tools::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency));
                    $qty += $product['cart_quantity'];
                }
            }
        }

        return $qty;

    }
}
