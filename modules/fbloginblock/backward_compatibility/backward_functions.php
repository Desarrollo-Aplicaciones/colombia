<?php
/*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * Backward function compatibility
 * Need to be called for each module in 1.4
 */

// Get out if the context is already defined
//if (!in_array('Context', get_declared_classes()))
	//require_once(dirname(__FILE__).'/Context.php');


function redirect_custom_fbloginblock($url){
        Tools::redirect($url);

}

function custom_copy_fbloginblock($file, $file_to){
		Tools::copy($file, $file_to);

}

function session_start_fbloginblock(){
        session_start();

}