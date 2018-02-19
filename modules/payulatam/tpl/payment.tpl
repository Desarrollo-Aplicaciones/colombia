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
*  @version  Release: $Revision: 14011 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA

* 1 <a href="{$pathSsl|escape:'htmlall':'UTF-8'}modules/payulatam/payment.php" name="ref1"  id="ref1">
* 3 <a href="{$pathSsl|escape:'htmlall':'UTF-8'}modules/payulatam/payment.php">
*}




<div class="checkout w-7 payment"> 
<!-- Pago contraentrega-->
	<section data-id="div10" class="payment" onclick="{literal}$('#mediopagodatafono').click(){/literal}">
		<!-- .container-fluid -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<div class="radio">
						<input class="radio-address" id="mediopagodatafono" name="mediopago" value="div10" type="radio">
						<label for="mediopagodatafono" class="radio-label" style="display: inline-flex;">
							<img src="{$img_dir}mediosp/pagocontentrega.png" id="imgcontrae" alt="Pago contra entrega"/>
						</label>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-2 -->

				<div class="col-xs-8 col-md-8" style="padding-top:10px;">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><b>{l s='Pago contra entrega' mod='cashondelivery'}</b></span>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-9 -->

				{if isset($show_contra_entrega) && $show_contra_entrega }
					<a href="{$link->getModuleLink('cashondelivery', 'validation', [], true)|escape:'html'}?confirm=1&cod_pagar=COD-Efectivo" title="{l s='Pay with cash on delivery (COD)' mod='cashondelivery'}" rel="nofollow" style="display:none;" id="COD_Efectivo">&nbsp;</a>
					<a href="{$link->getModuleLink('cashondelivery', 'validation', [], true)|escape:'html'}?confirm=1&cod_pagar=COD-Tarjeta" title="{l s='Pay with cash on delivery (COD)' mod='cashondelivery'}" rel="nofollow" id="COD_Datafono" style="display:none;">&nbsp;</a>
				{/if}

				<div class="col-xs-12 col-md-12 complete-data">

					{if isset($medios_de_pago['cashondelivery']) && $medios_de_pago['cashondelivery'] === '1'}
						<div class="ctn-vlr-total-pedido">
							El valor total de tu pedido es de <strong class="ctn-vlr-total-pedido-semibold">{displayPrice price=$total_price} (Impuestos incl.)</strong>
						</div>
						<div class="ctn-vlr-total-pedido">¿Deseas pagar en efectivo o con terminal bancaria?</div>
						<div id="ctn-contra-entrega-btns">
							<input type="button" onclick="window.location.href=$('#COD_Efectivo').attr('href');" class="boton_pagos paymentSubmit" value="EFECTIVO">
							<input type="button" onclick="window.location.href=$('#COD_Datafono').attr('href');" class="boton_pagos paymentSubmit" value="DATÁFONO">
						</div>
					{else}
						<p class="contendfromd">
							<span class="textapoyo"><strong>El pago en efectivo, no está disponible para tu ciudad, por favor utiliza otro.</strong></span>
						</p>
					{/if}
					<!-- /.row -->
				</div>
				<!-- /.complete-data -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
<!-- Pago contraentrega/-->


<!-- Cuenta Corriente o Ahorros -->
	<section data-id="div9" class="payment" onclick="{literal}$('#mediopagop').click(){/literal}">
		<!-- .container-fluid -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<div class="radio">
						<input class="radio-address" id="mediopagop" name="mediopago" value="div9" type="radio">
						<label for="mediopagop" class="radio-label" style="display: inline-flex;">
							<img src="{$img_dir}mediosp/pse.jpg" alt="pagos con PSE"/>
						</label>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-2 -->

				<div class="col-xs-8 col-md-8" style="padding-top:10px;">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><b>Cuenta corriente o ahorros</b></span>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-9 -->

				<div class="col-xs-12 col-md-12 complete-data">

					{if isset($medios_de_pago['Pse']) && $medios_de_pago['Pse'] ==='1'} 
						<div class="ctn-vlr-total-pedido">
							El valor total de tu pedido es de <strong class="ctn-vlr-total-pedido-semibold">{displayPrice price=$total_price} (Impuestos incl.)</strong>
						</div>       
						<div class="contendfrom">
							{include file="$tpl_dir../../modules/payulatam/tpl/payuPse.tpl"}
						</div>
					{else}
						<p class="contendfromd">
							<span class="textapoyo"><strong>El pago desde cuenta, no está disponible para tu ciudad, por favor utiliza otro.</strong></span>
						</p>
					{/if}
					<!-- /.row -->
				</div>
				<!-- /.complete-data -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
<!-- Cuenta Corriente o Ahorros -->

<!-- Tarjeta Credito -->
	<section data-id="div8" class="payment" onclick="{literal}$('#mediopagot').click(); $('html, body').animate({ scrollTop: $(this).offset().top }, 1000);{/literal}">
		<!-- .container-fluid -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<div class="radio">
						<input class="radio-address" id="mediopagot" name="mediopago" value="div8" type="radio">
						<label for="mediopagot" class="radio-label" style="display: inline-flex;">
							<img src="{$img_dir}mediosp/credito.jpg" alt="Tarjetas Farmalisto"/>
						</label>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-2 -->

				<div class="col-xs-8 col-md-8" style="padding-top:10px;">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><b>Tarjeta de crédito</b></span>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-9 -->

				<div class="col-xs-12 col-md-12 complete-data">

					{if isset($medios_de_pago['Tarjeta_credito']) && $medios_de_pago['Tarjeta_credito'] ==='1'}
						<div class="contendfrom">
							{include file="$tpl_dir../../modules/payulatam/tpl/credit_card.tpl"}
						</div>
					{else}
						<p class="contendfromd">
							<span class="textapoyo"><strong>El pago con tarjeta, no está disponible para tu ciudad, por favor utiliza otro.</strong></span>
						</p>
					{/if}
					<!-- /.row -->
				</div>
				<!-- /.complete-data -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
<!-- Tarjeta Credito /-->

<!-- Tarjeta Credito codensa -->
	<section data-id="div21" class="payment" onclick="{literal}$('#mediopagocodensa').click(); $('html, body').animate({ scrollTop: $(this).offset().top }, 1000);{/literal}">
		<!-- .container-fluid -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<div class="radio">
						<input class="radio-address" id="mediopagocodensa" name="mediopago" value="div21" type="radio">
						<label for="mediopagocodensa" class="radio-label" style="display: inline-flex;">
							<img src="{$img_dir}mediosp/Pago_Codensa.jpg" alt="Tarjetas Farmalisto"/>
						</label>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-2 -->

				<div class="col-xs-8 col-md-8" style="padding-top:10px;">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><b>Tarjeta de crédito Codensa</b></span>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-9 -->

				<div class="col-xs-12 col-md-12 complete-data">

					{if isset($medios_de_pago['Tarjeta_credito']) && $medios_de_pago['Tarjeta_credito'] ==='1'}
						<div class="contendfrom">
							{include file="$tpl_dir../../modules/payulatam/tpl/credit_card_codensa.tpl"}
						</div>
					{else}
						<p class="contendfromd">
							<span class="textapoyo"><strong>El pago con tarjeta, no está disponible para tu ciudad, por favor utiliza otro.</strong></span>
						</p>
					{/if}
					<!-- /.row -->
				</div>
				<!-- /.complete-data -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
<!-- Tarjeta Credito codensa /-->


<!-- Baloto -->
	<section data-id="div7" class="payment" onclick="{literal}$('#mediopagob').click(){/literal}">
		<!-- .container-fluid -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<div class="radio">
						<input class="radio-address" id="mediopagob" name="mediopago" value="div7" type="radio">
						<label for="mediopagob" class="radio-label" style="display: inline-flex;">
							<img src="{$img_dir}mediosp/baloto.jpg" id="imgbaloto" alt="Pagos con Baloto"/>
						</label>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-2 -->

				<div class="col-xs-8 col-md-8" style="padding-top:10px;">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><b>Vía Baloto</b></span>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-9 -->

				<div class="col-xs-12 col-md-12 complete-data">

					<div class="ctn-vlr-total-pedido">
						El valor total de tu pedido es de <strong class="ctn-vlr-total-pedido-semibold">{displayPrice price=$total_price} (Impuestos incl.)</strong>
					</div> 

					{if isset($disableBaloto) and !$disableBaloto and (isset($isblockmpb) and !$isblockmpb) and isset($medios_de_pago['Baloto']) && $medios_de_pago['Baloto'] ==='1'}


						<div class="ctn-vlr-total-pedido">
							Finaliza tu compra para recibir los datos con los que podrás acercarte a un punto Baloto y realizar tu pago.
							{include file="$tpl_dir../../modules/payulatam/tpl/payuBaloto.tpl"}
						</div>

						<div class="cont-trust-img">
							<input type="button" onclick="$('#botoncitosubmit').click();" class="boton-pagos-excep paymentSubmit" value="PAGAR">
						</div>

					{else if isset($isblockmpb) and $isblockmpb}

						<div class="ctn-vlr-total-pedido">
							<span class="textapoyo"><strong>El monto para Baloto no debe superar los 500.000 pesos. Por favor intenta con otro medio de pago.</strong></span>
						</div>

					{else}

						<div class="ctn-vlr-total-pedido">
							<span class="textapoyo"><strong>El pago con Baloto no está disponible para tu ciudad, por favor intenta con otro medio de pago.</strong></span>
						</div>

					{/if}
					<!-- /.row -->
				</div>
				<!-- /.complete-data -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
<!-- Baloto /-->

<!-- Pago con efecty -->
	<section data-id="div5" class="payment" onclick="{literal}$('#mediopagoe').click(){/literal}">
		<!-- .container-fluid -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4 col-md-4">
					<div class="radio">
						<input class="radio-address" id="mediopagoe" name="mediopago" value="div5" type="radio">
						<label for="mediopagoe" class="radio-label" style="display: inline-flex;">
							<img src="{$img_dir}mediosp/efecty.jpg" alt="Pago con Efecty" id="imgEfecty"/>
						</label>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-2 -->

				<div class="col-xs-8 col-md-8" style="padding-top:10px;">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress"><b>Pago con Efecty</b></span>
					</div>
				</div>
				<!-- /.col-xs-12.col-md-9 -->

				<div class="col-xs-12 col-md-12 complete-data">

					<div class="ctn-vlr-total-pedido">
						El valor total de tu pedido es de <strong class="ctn-vlr-total-pedido-semibold">{displayPrice price=$total_price} (Impuestos incl.)</strong>
					</div>
					{if isset($disableBaloto) and !$disableBaloto and (isset($isblockmpb) and !$isblockmpb) and isset($medios_de_pago['Efecty']) && $medios_de_pago['Efecty'] ==='1'}
						<p class="ctn-vlr-total-pedido">Finaliza tu compra para recibir los datos con los que podras acercarte a un punto Efecty y realizar tu pago.</p>
						<div class="cont-trust-img">
							<input type="button" onclick="$('#botoncitosubmit').click();" class="boton-pagos-excep paymentSubmit" value="PAGAR">
						</div>
						{include file="$tpl_dir../../modules/payulatam/tpl/payuEfecty.tpl"}
					{else if isset($isblockmpb) and $isblockmpb}
						<div class="ctn-vlr-total-pedido">
							<p class="textapoyo"><strong>El monto para Efecty no debe superar los 500.000 pesos. Por favor intenta con otro medio de pago.</strong></p>
						</div>
					{else}
						<div class="ctn-vlr-total-pedido">
							<p class="textapoyo"><strong>El pago con Efecty no está disponible para tu ciudad, por favor intenta con otro medio de pago.</strong></p>
						</div>
					{/if}
					<!-- /.row -->
				</div>
				<!-- /.complete-data -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
<!-- Pago con efecty /-->
</div>