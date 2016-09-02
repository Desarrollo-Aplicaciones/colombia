<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * Represents quantities available
 * It is either synchronized with Stock or manualy set by the seller
 *
 * @since 1.5.0
 */
class StockAvailable extends StockAvailableCore
{
	public static function getStockAvailableIdByProductId($id_product, $id_product_attribute = null, $id_shop = null)
	{
		if (!Validate::isUnsignedId($id_product))
			return false;

		$query = new DbQuery();
		$query->select('id_stock_available');
		$query->from('stock_available_mv');
		$query->where('id_product = '.(int)$id_product);

		if ($id_product_attribute !== null)
			$query->where('id_product_attribute = '.(int)$id_product_attribute);

		$query = StockAvailable::addSqlShopRestriction($query, $id_shop);
		return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
	}
	/**
	 * For a given id_product and id_product_attribute, gets its stock available
	 *
	 * @param int $id_product
	 * @param int $id_product_attribute Optional
	 * @param int $id_shop Optional : gets context by default
	 * @return int Quantity
	 */
	public static function getQuantityAvailableByProduct($id_product = null, $id_product_attribute = null, $id_shop = null)
	{
		// if null, it's a product without attributes
		if ($id_product_attribute === null)
			$id_product_attribute = 0;

		$query = new DbQuery();
		$query->select('SUM(quantity)');
		$query->from('stock_available_mv');

		// if null, it's a product without attributes
		if ($id_product !== null)
			$query->where('id_product = '.(int)$id_product);

		$query->where('id_product_attribute = '.(int)$id_product_attribute);
		$query = StockAvailable::addSqlShopRestriction($query, $id_shop);

		return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
	}

}