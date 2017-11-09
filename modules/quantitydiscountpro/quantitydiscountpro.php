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

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRule.php');
include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRuleFamily.php');
include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountDatabase.php');
include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRuleCondition.php');
include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRuleGroup.php');
include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRuleAction.php');
include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRuleMessage.php');

class QuantityDiscountPro extends Module
{
    protected static $_validRules;

    public function __construct()
    {
        $this->name = 'quantitydiscountpro';
        $this->author = 'idnovate';
        $this->version = '2.1.5';
        $this->tab = 'pricing_promotion';
        $this->module_key = 'd5eaea7fa97b9e11a8788a8294b346bf';
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');

        parent::__construct();

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $this->tabs[] = array(
                'tabClassName' => 'AdminQuantityDiscountRules',
                'tabParentName' => 'AdminPriceRule',
                'tabName' => $this->l('Promotions and discounts'),
            );
        } else {
            $this->tabs[] = array(
                'tabClassName' => 'AdminQuantityDiscountRules',
                'tabParentName' => 'AdminCatalog',
                'tabName' => $this->l('Promotions and discounts'),
            );
        }

        $this->tabs[] = array(
            'tabClassName' => 'AdminQuantityDiscountRulesFamilies',
            'tabParentName' => '',
            'tabName' => $this->l('Rule families'),
        );

        $this->displayName = $this->l('Promotions and discounts - (3x2, reductions, campaigns)');
        $this->description = $this->l('Apply discounts depending on the products from the cart');
        $this->confirmUninstall = $this->l('Are you sure that you want to delete the module and the related data?');

        $this->warning = $this->getWarnings(false);
    }

    public function install()
    {
        $this->copyOverrideFolder();

        /*Register hooks and tab*/
        if (!parent::install()
            || !$this->registerHook('displayLeftColumn')
            || !$this->registerHook('displayLeftColumnProduct')
            || !$this->registerHook('displayRightColumn')
            || !$this->registerHook('displayRightColumnProduct')
            || !$this->registerHook('displayproductButtons')
            || !$this->registerHook('displayProductTab')
            || !$this->registerHook('displayProductTabContent')
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('shoppingCart')
            || !$this->registerHook('shoppingCartExtra')
            || !$this->registerHook('displayBeforeCarrier')
            || !$this->registerHook('displayPaymentTop')
            || !$this->registerHook('displayTop')
            || !$this->registerHook('displayFooter')
            || !$this->registerHook('displayBanner')
            || !$this->registerHook('actionValidateOrder')
            || !$this->registerHook('actionAuthentication')
            || !$this->registerHook('displayQuantityDiscountProCustom1')
            || !$this->registerHook('displayQuantityDiscountProCustom2')
            || !$this->registerHook('displayQuantityDiscountProCustom3')
            || !$this->registerHook('displayQuantityDiscountProCustom4')
            || !$this->registerHook('displayQuantityDiscountProCustom5')
            || !QuantityDiscountDatabase::CreateTables()
            || !$this->installTabs()) {
            return false;
        }

        //install first family
        $qdrf = new QuantityDiscountRuleFamily();
        $qdrf->active = 1;
        $qdrf->name = 'Default';
        $qdrf->execute_other_families = 1;
        $qdrf->save();

        return true;
    }

    public function copyOverrideFolder()
    {
        $override_folder_name = "override";

        $version_override_folder = _PS_MODULE_DIR_.$this->name.'/'.$override_folder_name.'_'.Tools::substr(str_replace('.', '', _PS_VERSION_), 0, 2);
        $override_folder = _PS_MODULE_DIR_.$this->name.'/'.$override_folder_name;

        if (file_exists($override_folder) && is_dir($override_folder)) {
            $this->recursiveRmdir($override_folder);
        }

        if (is_dir($version_override_folder)) {
            $this->copyDir($version_override_folder, $override_folder);
        }

        return true;
    }

    protected function copyDir($src, $dst)
    {
        if (is_dir($src)) {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src.'/'.$file)) {
                        $this->copyDir($src.'/'.$file, $dst.'/'.$file);
                    } else {
                        copy($src.'/'.$file, $dst.'/'.$file);
                    }
                }
            }
            closedir($dir);
        }
    }

    protected function recursiveRmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        $this->recursiveRmdir($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function installTabs()
    {
        foreach ($this->tabs as $myTab) {
            $id_tab = Tab::getIdFromClassName($myTab['tabClassName']);
            if (!$id_tab) {
                $tab = new Tab();
                $tab->class_name = $myTab['tabClassName'];
                if ($myTab['tabParentName']) {
                    $tab->id_parent = Tab::getIdFromClassName($myTab['tabParentName']);
                } else {
                    $tab->id_parent = -1;
                }

                $tab->module = $this->name;

                //Initialize multilang configuration values
                $translations = array();
                $translations['AdminQuantityDiscountRules']['en'] = 'Promotions and discounts';
                $translations['AdminQuantityDiscountRules']['es'] = 'Promociones y descuentos';

                $translations['AdminQuantityDiscountRulesFamilies']['en'] = 'Rule families';
                $translations['AdminQuantityDiscountRulesFamilies']['es'] = 'Famílias de reglas';

                $languages = Language::getLanguages(false);
                foreach ($languages as $lang) {
                    $tab->name[$lang['id_lang']] = isset($translations[$myTab['tabClassName']][$lang['iso_code']]) ? $translations[$myTab['tabClassName']][$lang['iso_code']] : $translations[$myTab['tabClassName']]['en'];
                }

                $tab->add();
            }
        }

        return true;
    }

    public function uninstall()
    {
        $this->copyOverrideFolder();

        if (!parent::uninstall()
            || !QuantityDiscountRule::removeUnusedRules()
            || !QuantityDiscountDatabase::dropTables()) {
            return false;
        }

        foreach ($this->tabs as $myTab) {
            $idTab = Tab::getIdFromClassName($myTab['tabClassName']);
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }

        return true;
    }

    public function getContent()
    {
        if ((version_compare(_PS_VERSION_, '1.5.0.13', '<') && Module::isInstalled('quantitydiscountpro'))
            || (version_compare(_PS_VERSION_, '1.5.0.13', '>=') && Module::isEnabled('quantitydiscountpro'))) {
            $this->installTabs();
        }

        return Tools::redirectAdmin('index.php?controller=AdminQuantityDiscountRules&token='.Tools::getAdminTokenLite('AdminQuantityDiscountRules'));
    }

    public function hookDisplayFooter()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookActionValidateOrder($params)
    {
        //Get all cart rules and check if there are Quantity Discount cart rules, to insert a new record
        $cart_rules = array();

        $cart_rules_array = $this->context->cart->getCartRules();
        foreach ($cart_rules_array as $value) {
            $cart_rules[] = $value['id_cart_rule'];
        }

        $quantity_discount_cart_rules = $this->getQuantityDiscountCartRules((int)$this->context->cart->id);




        if (count($cart_rules) > 0 and count($quantity_discount_cart_rules) > 0) {
            foreach ($quantity_discount_cart_rules as $quantity_discount_cart_rule) {
                if (in_array($quantity_discount_cart_rule['id_cart_rule'], $cart_rules)) {

                    $quantityDiscountRuleObj = new QuantityDiscountRule((int)$quantity_discount_cart_rule['id_quantity_discount_rule']);
                    $qty_products = $quantityDiscountRuleObj->getQtyAffectedProducts();

                    $fields = array(
                        'id_order' => (int)$params['order']->id,
                        'id_quantity_discount_rule' => (int)$quantity_discount_cart_rule['id_quantity_discount_rule'],
                        'id_cart_rule' => (int)$quantity_discount_cart_rule['id_cart_rule'],
                        'qty_products' => (int)$qty_products,
                    );

                    Db::getInstance()->insert('quantity_discount_rule_order', $fields);
                }
            }
        }
    }

    public function hookActionAuthentication()
    {
        $quantityDiscount = new QuantityDiscountRule();
        $quantityDiscount->createAndRemoveRules();
    }

    public function hookActionCustomerAccountAdd()
    {
        $quantityDiscount = new QuantityDiscountRule();
        $quantityDiscount->createAndRemoveRules();
    }

    public function getQuantityDiscountCartRules($id_cart)
    {
        $results = Db::getInstance()->executeS(
            'SELECT id_cart_rule, id_quantity_discount_rule
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart`
            WHERE `id_cart` = '.(int)$id_cart
        );

        $cart_rule = array();
        foreach ($results as $result) {
            $cart_rule[] = $result;
        }

        return $cart_rule;
    }

    /* Common */
    public function hookDisplayLeftColumn()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayRightColumn()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayTop()
    {
        return $this->getMessage(__FUNCTION__);
    }

    /* Product page */
    public function hookDisplayLeftColumnProduct()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayRightColumnProduct()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayProductButtons()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayProductTab()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayProductTabContent()
    {
        return $this->getMessage(__FUNCTION__);
    }

    public function hookDisplayFooterProduct()
    {
        return $this->getMessage(__FUNCTION__);
    }

    /* Category page */
    public function hookDisplayProductPriceBlock($params)
    {
        if (isset($params['product'])) {
            if (is_array($params['product'])) {
                $id_product = $params['product']['id_product'];
            } else {
                $id_product = $params['product']->id;
            }

            if ($params['type'] == 'weight') {
                return $this->getMessage(__FUNCTION__, (int)$id_product);
            }
        }
    }

    /* Shopping cart */
    public function hookShoppingCart()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookShoppingCartExtra()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookDisplayBeforeCarrier()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookDisplayPaymentTop()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    /* Custom */
    public function hookDisplayQuantityDiscountProCustom1()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookDisplayQuantityDiscountProCustom2()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookDisplayQuantityDiscountProCustom3()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookDisplayQuantityDiscountProCustom4()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    public function hookDisplayQuantityDiscountProCustom5()
    {
        return $this->getMessage(__FUNCTION__, null, false);
    }

    private function getMessage($hookName, $id_product = null, $strongValidate = true)
    {
        $html = '';

        $key = (int)$id_product;

        if (!isset(self::$_validRules[$key])) {
            foreach (QuantityDiscountRuleFamily::getQuantityDiscountRuleFamilies() as $ruleFamily) {
                $quantityDiscountRules = QuantityDiscountRule::getQuantityDiscountRules($ruleFamily['id_quantity_discount_rule_family']);
                if (is_array($quantityDiscountRules) && count($quantityDiscountRules)) {
                    foreach ($quantityDiscountRules as $quantityDiscountRule) {
                        $quantityDiscountRuleObj = new QuantityDiscountRule((int)$quantityDiscountRule['id_quantity_discount_rule']);
                        if ($quantityDiscountRuleObj->isQuantityDiscountRuleValid()
                            && ($quantityDiscountRuleObj->validateCartRuleForMessages($id_product, $strongValidate))) {
                            self::$_validRules[$key][] = $quantityDiscountRuleObj->id_quantity_discount_rule;
                        }
                    }
                }
            }
        }

        if (isset(self::$_validRules[$key])) {
            foreach (self::$_validRules[$key] as $validRule) {
                $quantityDiscountRuleObj = new QuantityDiscountRule((int)$validRule);
                $messages = $quantityDiscountRuleObj->getMessagesToDisplay($hookName, $id_product);
                if ($messages && array_filter($messages)) {
                    foreach ($messages as $message) {
                        $message = new QuantityDiscountRuleMessage((int)$message['id_quantity_discount_rule_message'], (int)$this->context->language->id);
                        $html .= $message->message;
                    }
                }
            }
        }

        return $html;
    }

    public function getWarnings($getAll = true)
    {
        $warning = array();

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
                $warning[] = $this->l('You have to enable non PrestaShop modules at ADVANCED PARAMETERS - PERFORMANCE');
            }

            if (Configuration::get('PS_DISABLE_OVERRIDES')) {
                $warning[] = $this->l('You have to enable overrides at ADVANCED PARAMETERS - PERFORMANCE');
            }
        }

        if (count($warning) && !$getAll) {
            return $warning[0];
        }

        return $warning;
    }
}
