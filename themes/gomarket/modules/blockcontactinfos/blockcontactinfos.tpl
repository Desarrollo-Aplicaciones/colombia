﻿{*
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
*}
<div style="display:none;">
<div class="block" id="lineas-contacto" >
<!-- MODULE Block contact infos -->
	<div id="block_contact_infos">
		<h4 class="title_block">{l s='Customer service' mod='blockcontactinfos'}</h4>
		{* <a class="show_hide_footer" href="javascript:void(0)">icon</a> *}
		<ul class="f_block_content">
			<li>
			{if $blockcontactinfos_address != ''}
				<a href="tel:018009133830">{l s='Tel' mod='blockcontactinfos'} <b>{$blockcontactinfos_address|escape:'htmlall':'UTF-8'}</b></a><br />
			{/if}
			{if $blockcontactinfos_phone != ''}
				<a href="tel:+5714926363">{l s='Bogota' mod='blockcontactinfos'} <b>{$blockcontactinfos_phone|escape:'htmlall':'UTF-8'}</b></a><br />
				<a href="tel:+5723860083">{l s='Cali' mod='blockcontactinfos'} <b>{l s='telCali' mod='blockcontactinfos'}</b></a><br />
				<a href="tel:+5742836150">{l s='Medellin' mod='blockcontactinfos'} <b>{l s='telMedellin' mod='blockcontactinfos'}</b></a><br />
				<a href="tel:+5753197970">{l s='Barranquilla' mod='blockcontactinfos'} <b>{l s='telBarranquilla' mod='blockcontactinfos'}</b></a>
			{/if}


			</li>
	                {if $blockcontactinfos_email != ''}<li><b>{mailto address=$blockcontactinfos_email|escape:'htmlall':'UTF-8' }</b></li>{/if}
		</ul>
	</div>
</div>
	<!-- /MODULE Block contact infos -->
<div class="block">
	<div id="block_mobile_app">
		<h4 class="title_block">{l s='Mobile' mod='blockcontactinfos'}</h4>
		{* <a class="show_hide_footer" href="javascript:void(0)">icon</a> *}
		<ul class="f_block_content">
		<a href="https://itunes.apple.com/co/app/farmalisto/id899599402?mt=8">
		<img src="{$img_dir}footer/appStore.jpg" alt="Descarga la aplicación de farmalisto en el App Store" target="blank"/>
		</a>
		<a href="https://play.google.com/store/apps/details?id=com.ionicframework.appfarmalisto2">
				<img src="{$img_dir}footer/googlePlay.png" alt="Descarga la aplicación de farmalisto en google play" target="blank"/>
		</a>
		</ul>
	</div>
</div>
<div class="block" id="secure_payment">
	<h4>Compra con seguridad</h4>
		{* <a class="show_hide_footer" href="javascript:void(0)">icon</a> *}
		<ul class="f_block_content">
			<img src="{$img_dir}footer/footer-una-pieza.jpg" />
			<!--img src="{$img_dir}footer/symantec.jpg" />
			<img src="{$img_dir}footer/baloto.jpg" />
			<img src="{$img_dir}footer/efecty.jpg" />
			<img src="{$img_dir}footer/pse.jpg" />
			<img src="{$img_dir}footer/dinners.jpg" />
			<img src="{$img_dir}footer/master.jpg" />
			<img src="{$img_dir}footer/visa.jpg" />
			<img src="{$img_dir}footer/amex.jpg" />
			<img src="{$img_dir}footer/cod.jpg" /-->
		</ul>
</div>
</div>