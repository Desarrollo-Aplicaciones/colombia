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
{* 
<!-- COD_efectivo -->
<div name="opcion4" id="opciones" onclick="mouse_overd('div6');">
	<div class="invisible">
		<div id="div6rb" style="display:none">
		</div>
		<div class="visible">
		</div>
	</div>
	<div class="payment_module" id="textradiocontrae">
		<input type="radio" value="div6" name="mediopago" id="mediopagoce"   onclick="mouse_overd('div6');">
		<div class="image">
			<img src="{$img_dir}mediosp/cod.jpg" id="imgcontrae" alt="Pago contra entrega"/>
		</div>
		{l s='Pago en efectivo' mod='cashondelivery'}
		{if isset($show_contra_entrega) && $show_contra_entrega }
			<a href="{$link->getModuleLink('cashondelivery', 'validation', [], true)|escape:'html'}?confirm=1&cod_pagar=COD-Efectivo" title="{l s='Pay with cash on delivery (COD)' mod='cashondelivery'}" rel="nofollow" style="display:none;" id="COD_Efectivo">&nbsp;</a>
		{/if}
	</div>
</div>


<div id="div6" style="display: none;">
	{if isset($medios_de_pago['cashondelivery']) && $medios_de_pago['cashondelivery'] === '1'}
		<p class="contendfrom">
			<span class="textapoyo">Paga tu pedido al recibirlo en la dirección que seleccionaste</span>
		</p>
		<div style="float:left;width:130px;margin-top:30px;">
			<input type="button" onclick="window.location.href=$('#COD_Efectivo').attr('href');" class="paymentSubmit" value="Pagar &raquo;">
		</div>
	{else}
		<p class="contendfromd">
			<span class="textapoyo">El pago en efectivo, no está disponible para tu ciudad, por favor utiliza otro.</span>
		</p>
	{/if}
</div> *}


<!-- Pago contraentrega-->




<div id="ctn-contra-entrega" class="cont-opc-pago">
	<div name="opcion10" id="opciones" onclick="mouse_overd('div10', '#ctn-contra-entrega');">
		<div class="invisible">
			<div id="div10rb"></div>
			<div class="visible2"></div>
			<div class="visible"></div>
		</div>
		<div class="payment_module" id="textradiodatafono">
			<input type="radio" value="div10" name="mediopago" id="mediopagodatafono"   onclick="mouse_overd('div10');">
			<div class="image">
				<img src="{$img_dir}mediosp/pagocontentrega.png" id="imgcontrae" alt="Pago contra entrega" id="img-Pago-contra-entrega"/>
			</div>
			{l s='Pago contra entrega' mod='cashondelivery'}
			<div class="cont-mas-menos">
        		<img id="div10im" src="{$img_dir}mediosp/mas_menos.png">
  			</div>
			{if isset($show_contra_entrega) && $show_contra_entrega }
				<a href="{$link->getModuleLink('cashondelivery', 'validation', [], true)|escape:'html'}?confirm=1&cod_pagar=COD-Efectivo" title="{l s='Pay with cash on delivery (COD)' mod='cashondelivery'}" rel="nofollow" style="display:none;" id="COD_Efectivo">&nbsp;</a>
	      		<a href="{$link->getModuleLink('cashondelivery', 'validation', [], true)|escape:'html'}?confirm=1&cod_pagar=COD-Tarjeta" title="{l s='Pay with cash on delivery (COD)' mod='cashondelivery'}" rel="nofollow" id="COD_Datafono" style="display:none;">&nbsp;</a>
			{/if}
		</div>
	</div>
  	<div id="divs">
		<div id="div10" class="ctn-toggle-payment" style="display: none;">
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
		</div>
	</div>
</div>
<div class="separador-medios-pago"></div>
<!-- Pago contraentrega/-->


<!-- Cuenta Corriente o Ahorros -->
<div id="ctn-cuenta-corriente" class="cont-opc-pago">
	<div name="opcion3" id="opciones" onclick="mouse_overd('div9', '#ctn-cuenta-corriente');">
		<div class="invisible">
			<div id="div9rb"></div>
			<div class="visible2"></div>
			<div class="visible"></div>
		</div>
		<div class="payment_module" id="textpse">
			<input type="radio" value="div9" name="mediopago" id="mediopagop" onclick="mouse_overd('div9');" >
			<div class="image"><img src="{$img_dir}mediosp/pse.jpg" alt="pagos con PSE"/></div>
			Cuenta corriente o ahorros
			<div class="cont-mas-menos">
	    		<img id="div9im" src="{$img_dir}mediosp/mas_menos.png">
			</div>
		</div>
	</div>
	<div id="divs">
		<div id="div9" class="ctn-toggle-payment"  style="display: none; ">
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
		</div>
	</div>
</div>
<div class="separador-medios-pago"></div>
<!-- Cuenta Corriente o Ahorros -->

<!-- Tarjeta Credito -->
<div id="ctn-tarjeta-credito" class="cont-opc-pago">
	<div name="opcion2" id="opciones" onclick="mouse_overd('div8', '#ctn-tarjeta-credito');">
		
		<div class="invisible">
			<div id="div8rb"></div>
			<div class="visible2"></div>
			<div class="visible"></div>
		</div>

		<div class="payment_module" id="texttarjeta">
			<input type="radio" value="div8" name="mediopago" id="mediopagot">
			{* <input type="radio" value="div8" name="mediopago" id="mediopagot" > *}
			<div class="image">
				<img src="{$img_dir}mediosp/credito.jpg" alt="Tarjetas Farmalisto"/>
			</div>
			<div class="ctn-title-medio-pago">Tarjeta de crédito</div>
			<div class="cont-mas-menos"><img id="div8im" src="{$img_dir}mediosp/mas_menos.png"></div>
		</div> 
	</div>
	<div id="divs">
		<div id="div8" class="ctn-toggle-payment" style="display: none; ">
			{if isset($medios_de_pago['Tarjeta_credito']) && $medios_de_pago['Tarjeta_credito'] ==='1'}
				<div class="contendfrom">
					{include file="$tpl_dir../../modules/payulatam/tpl/credit_card.tpl"}
				</div>
			{else}
				<p class="contendfromd">
					<span class="textapoyo"><strong>El pago con tarjeta, no está disponible para tu ciudad, por favor utiliza otro.</strong></span>
				</p>
			{/if}
		</div>
	</div>
</div>
<div class="separador-medios-pago"></div>
<!-- Tarjeta Credito /-->

<!-- Tarjeta Credito codensa -->

<!-- Tarjeta Credito codensa /-->


<!-- Baloto -->
<div id="ctn-baloto" class="cont-opc-pago">
	<div name="opcion1" id="opciones" onclick="mouse_overd('div7', '#ctn-baloto');">
		<div class="invisible">
			<div id="div7rb"></div>
			<div class="visible2"></div>
			<div class="visible"></div>
		</div>
		<div class="payment_module" id="textradiobaloto">
			<input type="radio" value="div7" name="mediopago" id="mediopagob"   onclick="mouse_overd('div7');" >
			<div class="image"><img src="{$img_dir}mediosp/baloto.jpg" id="imgbaloto" alt="Pagos con Baloto"/></div>
			Vía Baloto
			<div class="cont-mas-menos">
        		<img id="div7im" src="{$img_dir}mediosp/mas_menos.png">
  			</div>
		</div>
	</div>
	<div id="divs">
		<div id="div7" class="ctn-toggle-payment" style="display: none;">
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

		</div>
	</div>
</div>
<div class="separador-medios-pago"></div>
<!-- Baloto /-->

<!-- Pago con efecty -->
<div id="ctn-efecty" class="cont-opc-pago">
	<div name="opcion5" id="opciones" onclick="mouse_overd('div5', '#ctn-efecty');">
		<div class="invisible">
			<div id="div5rb"></div>
			<div class="visible2"></div>
			<div class="visible"></div>
		</div>
		<div class="payment_module" id="textradioefecty">
			<input type="radio" value="div5" name="mediopago" id="mediopagoe" onclick="mouse_overd('div5');" >
			<div class="image">
				<img src="{$img_dir}mediosp/efecty.jpg" alt="Pago con Efecty" id="imgEfecty"/>
			</div>
			Pago con Efecty
			<div class="cont-mas-menos">
        		<img id="div5im" src="{$img_dir}mediosp/mas_menos.png">
  			</div>
		</div> 
	</div>

	<div id="divs">
		<div id="div5" class="ctn-toggle-payment" style="display: none;">
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
		</div>
	</div>
</div>
<div class="separador-medios-pago"></div>
<!-- Pago con efecty /-->