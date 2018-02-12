{*
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
*  @license	http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{* Assign a value to 'current_step' to display current style *}
{capture name="url_back"}
{if isset($back) && $back}back={$back}{/if}
{/capture}

{if !isset($multi_shipping)}
	{assign var='multi_shipping' value='0'}
{/if}

{if !$opc}
<div id="order-step">
	<div id="accordion">
		<ul>
			<li {if $current_step=='summary'}class="active"{/if}>
				<div>
					<a href="{$link->getPageLink('order', true)}&paso=inicial">
						<span>1</span>
						<p>Mi Carrito</p>
						<div class="icon" id="cart"></div>
					</a>
				</div>
			</li>
			<li {if $current_step=='address'}class="active"{/if}>
				<div>
					<a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=1&multi-shipping={$multi_shipping}")|escape:'html'}">
						<span>2</span>
						<p>Datos de entrega</p>
						<div class="icon" id="delivery"></div>
					</a>
				</div>
			</li>

			<li {if $current_step=='shipping'}class="active"{/if}>
				<div>
					<a href="javascript:void(0);">
						<span>3</span>
						<p>Fórmula médica</p>
						<div class="icon" id="rx"></div>
					</a>
				</div>
			</li>

			<li {if $current_step=='payment'}class="active"{/if}>
				<div>
					<a href="javascript:void(0);">
						<span>4</span>
						<p>Modos de Pago</p>
						<div class="icon" id="pay"></div>
					</a>
				</div>
			</li>
		</ul>
	</div>
</div>
{/if}