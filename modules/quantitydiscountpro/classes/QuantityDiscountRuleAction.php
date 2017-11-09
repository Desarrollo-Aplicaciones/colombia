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

class QuantityDiscountRuleAction extends ObjectModel
{
    public $id_quantity_discount_rule_action;
    public $id_quantity_discount_rule;
    public $id_type;
    public $free_shipping;
    public $reduction_amount;
    public $reduction_buy_amount;
    public $reduction_buy_amount_tax;
    public $reduction_buy_over;
    public $reduction_currency;
    public $reduction_tax = 1;
    public $reduction_percent;
    public $reduction_percent_tax = 1;
    public $reduction_shipping;
    public $reduction_percent_shipping;
    public $reduction_percent_discount;
    public $reduction_max_amount;
    public $reduction_product_max_amount;
    public $reduction_max_currency;
    public $product_price_operator;
    public $product_price_amount;
    public $product_price_currency;
    public $product_price_tax = 1;
    public $apply_discount_to ='order';
    public $apply_discount_sort = 'cheapest';
    public $apply_discount_to_nb = 1;
    public $apply_discount_to_all;
    public $apply_discount_to_special = 1;
    public $apply_discount_to_regular_price = 0;
    public $nb_repetitions = 'infinite';
    public $nb_repetitions_custom;
    public $products_nb_each;
    public $products_nb_same_attributes;
    public $products_default_category;
    public $group_products_by;
    public $stock_operator;
    public $stock;
    public $filter_by_product;
    public $filter_by_attribute;
    public $filter_by_category;
    public $filter_by_supplier;
    public $filter_by_manufacturer;
    public $filter_by_price;
    public $filter_by_stock;
    public $reduction_percent_ciclos;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'quantity_discount_rule_action',
        'primary' => 'id_quantity_discount_rule_action',
        'fields' => array(
            'id_quantity_discount_rule'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_type'                           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'free_shipping'                     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_amount'                  => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_buy_amount'              => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_buy_amount_tax'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_buy_over'                => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_currency'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_tax'                     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_percent'                 => array('type' => self::TYPE_FLOAT),
            'reduction_percent_tax'             => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_shipping'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_percent_shipping'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_percent_discount'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'reduction_max_amount'              => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_product_max_amount'      => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_max_currency'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_operator'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_amount'              => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'product_price_currency'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_tax'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'apply_discount_to'                 => array('type' => self::TYPE_STRING),
            'apply_discount_sort'               => array('type' => self::TYPE_STRING),
            'apply_discount_to_nb'              => array('type' => self::TYPE_STRING),
            'apply_discount_to_all'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'apply_discount_to_special'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'apply_discount_to_regular_price'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'nb_repetitions'                    => array('type' => self::TYPE_STRING),
            'nb_repetitions_custom'             => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_each'                  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_same_attributes'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_default_category'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'group_products_by'                 => array('type' => self::TYPE_STRING),
            'stock_operator'                    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'stock'                             => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'filter_by_product'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_attribute'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_category'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_supplier'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_manufacturer'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_price'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_stock'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'reduction_percent_ciclos'          => array('type' => self::TYPE_STRING),
        ),
    );

    public function __construct($id = null)
    {
        parent::__construct($id);

        if (Context::getContext()->employee && Context::getContext()->controller instanceof AdminQuantityDiscountRulesController) {
            if ($this->getNbProducts() < 50000) {
                $this->product = $this->getAssociatedRestrictions('product', true, true);
            }
            $this->category = $this->getAssociatedRestrictions('category', true, true);
            $this->attribute = $this->getAssociatedRestrictions('attribute', false, true);
            $this->manufacturer = $this->getAssociatedRestrictions('manufacturer', false, false);
            $this->supplier = $this->getAssociatedRestrictions('supplier', false, false);
        }
    }

    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @param string $type
     * @param bool   $active_only
     * @param bool   $i18n
     * @param int    $offset
     * @param int    $limit
     * @param string $search_cart_rule_name
     * @return array|bool
     * @throws PrestaShopDatabaseException
     */
    public function getAssociatedRestrictions($type, $active_only, $i18n)
    {
        $cache_key = 'QuantityDiscountRuleAction::getAssociatedRestrictions_'.(int)$this->id.'_'.$type;

        if (!Cache::isStored($cache_key)) {
            $array = array('selected' => array(), 'unselected' => array());

            if (!Validate::isLoadedObject($this)) {
                $array['unselected'] = Db::getInstance()->executeS(
                    'SELECT t.`id_'.$type.'`'.($i18n ? ($type == 'attribute' ? ', CONCAT(agl.`name`, " - ", tl.`name`) as name' : ', tl.name') : ', t.name').', 1 as selected
                    FROM `'._DB_PREFIX_.$type.'` t
                    '.($i18n ? 'LEFT JOIN `'._DB_PREFIX_.$type.'_lang` tl ON (t.id_'.$type.' = tl.id_'.$type.' AND tl.id_lang = '.(int)Context::getContext()->language->id.(in_array($type, array('product', 'category')) ? ' AND id_shop = '.(int)Context::getContext()->shop->id : '').')' : '').
                    ($type == 'attribute' ? ' LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (t.id_attribute_group = agl.id_attribute_group AND agl.id_lang = '.(int)Context::getContext()->language->id.')' : '').'
                    WHERE 1
                    '.($active_only ? 'AND t.active = 1' : '').'
                    ORDER BY name ASC'
                );

                if (in_array($type, array('attribute', 'manufacturer', 'supplier'))) {
                    array_unshift($array['unselected'], array('id_'.$type => '999999', 'name' => '- Without '.$type.' -', 'selected' => '1'));
                }
            } else {
                $sql = 'SELECT t.`id_'.$type.'`'.($i18n ? ($type == 'attribute' ? ', CONCAT(agl.`name`, " - ", tl.`name`) as name' : ', tl.name') : ', t.name').', IF(qdrt.id_'.$type.' IS NULL, 0, 1) as selected
                    FROM `'._DB_PREFIX_.$type.'` t
                    '.($i18n ? 'LEFT JOIN `'._DB_PREFIX_.$type.'_lang` tl ON (t.id_'.$type.' = tl.id_'.$type.' AND tl.id_lang = '.(int)Context::getContext()->language->id.(in_array($type, array('product', 'category')) ? ' AND id_shop = '.(int)Context::getContext()->shop->id : '').')' : '').'
                    LEFT JOIN (SELECT id_'.$type.' FROM `'._DB_PREFIX_.'quantity_discount_rule_action_'.$type.'` WHERE id_quantity_discount_rule_action = '.(int)$this->id.') qdrt ON t.id_'.$type.' = qdrt.id_'.$type.
                    ($type == 'attribute' ? ' LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (t.id_attribute_group = agl.id_attribute_group AND agl.id_lang = '.(int)Context::getContext()->language->id.')' : '').'
                    WHERE 1 '.($active_only ? ' AND t.active = 1' : '').'
                    ORDER BY name ASC';

                $resource = Db::getInstance()->query($sql, false);
                while ($row = Db::getInstance()->nextRow($resource)) {
                    $array[($row['selected']) ? 'selected' : 'unselected'][] = $row;
                }

                if (in_array($type, array('attribute', 'manufacturer', 'supplier'))) {
                    $sql = 'SELECT id_'.$type.'
                        FROM `'._DB_PREFIX_.'quantity_discount_rule_action_'.$type.'`
                        WHERE `id_quantity_discount_rule_action` = '.(int)$this->id.'
                        AND `id_'.$type.'` = 999999';

                    if (Db::getInstance()->executeS($sql)) {
                        array_unshift($array['selected'], array('id_'.$type => '999999', 'name' => '- Without '.$type.' -', 'selected' => '1'));
                    } else {
                        array_unshift($array['unselected'], array('id_'.$type => '999999', 'name' => '- Without '.$type.' -', 'selected' => '1'));
                    }
                }
            }

            $result = $array;
            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public function getSelectedAssociatedRestrictions($type)
    {
        $cache_key = 'QuantityDiscountRuleAction::getSelectedAssociatedRestrictions_'.(int)$this->id.'_'.$type;

        if (!Cache::isStored($cache_key)) {
            /* SELECTED ATTRIBUTES */
            $sql = 'SELECT id_'.$type.'
                FROM `'._DB_PREFIX_.'quantity_discount_rule_action_'.$type.'`
                WHERE `id_quantity_discount_rule_action` = '.(int)$this->id;

            $result = array();
            $result['selected'] = Db::getInstance()->executeS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    protected function getNbProducts()
    {
        $sql = 'SELECT count(*)
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p');

        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}
