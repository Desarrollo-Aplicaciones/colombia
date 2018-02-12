<?php
/**
* 2007-2015 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wim_ganalytics_log` (
    `id_wim_ganalytics` int(11) NOT NULL AUTO_INCREMENT,
    `ip_client` VARCHAR(45),
    `data` VARCHAR(10240),
    `response` VARCHAR(10240),
    `response_code` INT(3),
    `date_add` DATETIME,
    PRIMARY KEY  (`id_wim_ganalytics`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wim_ganalytics_uuid` (
    `id_guest` int(11) NOT NULL,
    `uuid` VARCHAR(64) NOT NULL,
    `ip` VARCHAR(32),
    `agent` VARCHAR(10240),
    `location` VARCHAR(128),
    `date_add` DATETIME,
    PRIMARY KEY  (`id_guest`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
