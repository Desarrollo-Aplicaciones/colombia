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

{*
** Retro compatibility for PrestaShop version < 1.4.2.5 with a recent theme
*}

{* Two variable are necessaries to display the address with the new layout system *}
{* Will be deleted for 1.5 version and more *}
{if !isset($multipleAddresses)}
	{$ignoreList.0 = "id_address"}
	{$ignoreList.1 = "id_country"}
	{$ignoreList.2 = "id_state"}
	{$ignoreList.3 = "id_customer"}
	{$ignoreList.4 = "id_manufacturer"}
	{$ignoreList.5 = "id_supplier"}
	{$ignoreList.6 = "date_add"}
	{$ignoreList.7 = "date_upd"}
	{$ignoreList.8 = "active"}
	{$ignoreList.9 = "deleted"}

	{* PrestaShop < 1.4.2 compatibility *}
	{if isset($addresses)}
		{$address_number = 0}
		{foreach from=$addresses key=k item=address}
			{counter start=0 skip=1 assign=address_key_number}
			{foreach from=$address key=address_key item=address_content}
				{if !in_array($address_key, $ignoreList)}
					{$multipleAddresses.$address_number.ordered.$address_key_number = $address_key}
					{$multipleAddresses.$address_number.formated.$address_key = $address_content}
					{counter}
				{/if}
			{/foreach}
		{$multipleAddresses.$address_number.object = $address}
		{$address_number = $address_number + 1}
		{/foreach}
	{/if}
{/if}

{* Define the style if it doesn't exist in the PrestaShop version*}
{* Will be deleted for 1.5 version and more *}
{if !isset($addresses_style)}
	{$addresses_style.company = 'address_company'}
	{$addresses_style.vat_number = 'address_company'}
	{$addresses_style.firstname = 'address_name'}
	{$addresses_style.lastname = 'address_name'}
	{$addresses_style.address1 = 'address_address1'}
	{$addresses_style.address2 = 'address_address2'}
	{$addresses_style.city = 'address_city'}
	{$addresses_style.country = 'address_country'}
	{$addresses_style.phone = 'address_phone'}
	{$addresses_style.phone_mobile = 'address_phone_mobile'}
	{$addresses_style.alias = 'address_title'}
{/if}


{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My addresses'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
{include file="$tpl_dir./my-account-menu.tpl"}
<p>{l s='Please configure your default billing and delivery addresses when placing an order. You may also add additional addresses, which can be useful for sending gifts or receiving an order at your office.'}</p>
<p>{l s='Be sure to update your personal information if it has changed.'}</p>
<p class="title">{l s='My addresses'}</p>
<div class="addresses_list">
{if isset($multipleAddresses) && $multipleAddresses}
	{foreach from=$multipleAddresses item=address name=myLoop}
	<script>
		var id_{$address.object.id|intval} = {$address.object|@json_encode};
	</script>
		<ul class="address {if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{else}item{/if}">
			<li class="address_title">{$address.object.alias}</li>
			{foreach from=$address.ordered name=adr_loop item=pattern}
				{if $pattern != "dni" && $pattern != "firstname lastname" && $pattern != "Country:name" && $pattern != "address2"}
				{assign var=addressKey value=" "|explode:$pattern}
				<li>
				{foreach from=$addressKey item=key name="word_loop"}
					<span{if isset($addresses_style[$key])} class="{$addresses_style[$key]}"{/if}>
						{$address.formated[$key|replace:',':'']|escape:'htmlall':'UTF-8'}
					</span>
				{/foreach}
				</li>
				{/if}
			{/foreach}
			<li class="address_update"><a href="javascript:void(0);" title="{l s='Update'}" onclick="loadEdit(id_{$address.object.id|intval});">
			<span>{l s='Update'}</span><span>&#x270E;</span></a></li>
		</ul>
	{/foreach}
{else}
	<p class="warning">{l s='No addresses are available.'}&nbsp;<a href="{$link->getPageLink('address', true)|escape:'html'}">{l s='Add a new address'}</a></p>
<script>
$(window).load(function() {
	standard_lightbox('nueva-direccion');
});
</script>
{/if}
</div>
	<div style="text-align:center;margin:20px auto;">
		<a href="javascript:void(0)" onclick="loadEdit(0);" class="add_address">{l s='Add a new address'}</a>
	</div>
	<div class="container_24" id="nueva-direccion">

		<div class="campoCorto">
			<p class="etiqueta" id="label-estado">Departamento<span class="purpura">*</span>:<br />
				<input id="id_address" type="hidden" class="hidden"/>
				<select class="seleccion" id="estado" name="estado">
					<option value="" selected="selected" disabled>- Departamento -</option>
					<option value="bog">Bogotá - Cundinamarca</option>
					<option value="cal">Cali - Valle del Cauca</option>
					<option value="med">Medellín - Antioquia</option>
					<option value="bar">Barranquilla - Atlántico</option>
					<option value="buc">Bucaramanga - Santander</option>
					<option disabled>──────────────</option>
					{foreach from=$estados item=dp}
					<option value="{$dp['id_state']}">{$dp['state']}</option>
					{/foreach}
				</select><br />
				<input id="ref_estado" type="hidden" class="hidden"/>
			</p>
		</div>
		<div class="campoCorto">
			<p class="etiqueta" id="label-ciudad">Ciudad<span class="purpura">*</span>:<br />
				<select class="seleccion" id="ciudad" name="ciudad">
					<option value="" selected="selected" disabled>- Ciudad -</option>
				</select><br /> 
			</p>
				<input type="hidden" class="hidden" name="nombre_ciudad" id="nombre_ciudad" value="" />
				<input id="ref_nombre_ciudad" type="hidden" class="hidden"/>
		</div>
		<div class="campoCorto">
			<p class="etiqueta" id="label-fijo">Teléfono Fijo<span class="purpura">*</span>:<br />
			<input class="entrada" type="text" value="" placeholder="Número fijo o celular" id="fijo" name="fijo"/><br /> 
			<input id="ref_fijo" type="hidden" class="hidden"/>
			</p>
		</div>
		<div class="campoCorto">
			<p class="etiqueta" id="label-movil">Teléfono Móvil<span class="purpura">*</span>:<br />
			<input class="entrada" type="text" value="" placeholder="Número de celular" id="movil" name="movil"/><br />
			<input id="ref_movil" type="hidden" class="hidden"/>
			</p>
		</div>
			<div class="campoLargo">
				<p class="etiqueta" id="label-direccion">Dirección<span class="purpura">*</span>:<br />
				<input class="entrada larga" type="text" value="" placeholder="Dirección para la entrega" id="direccion" name="direccion"/><br />
				<input id="ref_direccion" type="hidden" class="hidden"/>
				</p> 
			</div>
			<div class="campoLargo">
				<p class="etiqueta" id="label-complemento">Barrio / Indicaciones<span class="purpura">*</span>:<br />
				<input class="entrada larga" type="text" value="" placeholder="Cómo llegar" id="complemento" name="complemento"/><br /> 
				<input id="ref_complemento" type="hidden" class="hidden"/>
				</p>
			</div>
			<div class="campoLargo">
				<p class="etiqueta" id="label-alias">Nombre de dirección<span class="purpura">*</span>:<br />
				<input class="entrada larga" type="text" value="" placeholder="Ej: Mi casa, Mi oficina, Mi mamá" id="alias" name="alias"/><br /> 
				<input id="ref_alias" type="hidden" class="hidden"/>
				</p>
			</div>
		<span class="obliga">(<span class="purpura">*</span>) Campos Obligatorios</span>
		<div style="display:inline-block; margin-top:20px">
			<a href="javascript:void(0);" onclick="lightbox_hide();" class="cancelar">Cancelar</a>
			<input type="submit" value="Guardar" id="new-address"/>
			
		</div>
	</div>
{*<div class="clear address_add"><a href="{$link->getPageLink('address', true)|escape:'html'}" title="{l s='Add an address'}" class="button_large">{l s='Add an address'}</a></div>*}
<script>
function ciudad_s(){
	$("#nombre_ciudad").val($("#ciudad :selected").text()); 
	if(($('#ciudad').val()) != "")$('#fijo').focus();
}
$('#estado').change(function(){
	var id_estado = $(this).val();
	var ciudad = 0;
	switch (id_estado){
	case "bog":
		$(this).val("326");
		ciudad = "1184";
		break;
	case "cal":
		$(this).val("342");
		ciudad = "1976";
		break;
	case "med":
		$(this).val("314");
		ciudad = "1037";
		break;
	case "bar":
		$(this).val("316");
		ciudad = "1162";
		break;
	case "buc":
		$(this).val("339");
		ciudad = "1835";
		break;
	}
	id_estado = $(this).val();
	if (id_estado==""){
		$('#ciudad').html('<option value="" selected disabled>- Ciudad -</option>');
	}else{
		$.ajax({
			type: "post",
			url: "{$base_dir}ajax_formulario_cities.php",
			data: {
				"id_state":id_estado
			},
			success: function(response){
				var json = $.parseJSON(response);
				$('#ciudad').html('<option value="" selected disabled>- Ciudad -</option>'+json.results);
				if (ciudad > 1) {
					$('#ciudad').val(ciudad);
					ciudad_s();
				}else if($("#nombre_ciudad").val()){
					$('#ciudad option').each(function (){
						$(this).removeAttr('selected');
						if ($(this).text() == $("#nombre_ciudad").val()){
							$('#ciudad').val($(this).val());
						}
					});
				};
			}
		});
	}
});
$('#ciudad').change(function(){
	ciudad_s();
});
function loadEdit(id){
	$('.validacion').remove();
		$('#estado').removeAttr("style");
		$('#ciudad').removeAttr("style");
		$('#alias').removeAttr("style");
		$('#direccion').removeAttr("style");
		$('#complemento').removeAttr("style");
		$('#fijo').removeAttr("style");
		$('#movil').removeAttr("style");
	$('#id_address').val(id['id']);
	$('#estado').val(id['id_state']);
	$("#nombre_ciudad").val(id['city']);
	$('#estado').trigger('change');
	$('#fijo').val(id['phone']);
	$('#movil').val(id['phone_mobile']);
	$('#direccion').val(id['address1']);
	$('#complemento').val(id['address2']);
	$('#alias').val(id['alias']);

	$('#ref_estado').val(id['id_state']);
	$("#ref_nombre_ciudad").val(id['city']);
	$('#ref_fijo').val(id['phone']);
	$('#ref_movil').val(id['phone_mobile']);
	$('#ref_direccion').val(id['address1']);
	$('#ref_complemento').val(id['address2']);
	$('#ref_alias').val(id['alias']);
	standard_lightbox('nueva-direccion');
}
$('#new-address').click(function(){
		$('.validacion').remove();
		var id_address = $('#id_address').val();
		var id_country={$pais};
		var id_state=$('#estado').val();
		var id_customer={$cart->id_customer};
		var alias=$('#alias').val();
		var address1=$('#direccion').val();
		var address2=$('#complemento').val();
		var city=$('#nombre_ciudad').val();
		var city_id=$('#ciudad').val();
		var phone=$('#fijo').val();
		var phone_mobile=$('#movil').val();
		var active = 1;
		var ref = 0;
		if (id_address){
			id_country = "";
			id_customer = "";
			active = "";
			if( id_state === $('#ref_estado').val()){
				id_state = "";
			} else{
				ref++;
			}
			if( alias === $('#ref_alias').val()){
				alias = "";
			} else{
				ref++;
			}
			if( address1 === $('#ref_direccion').val()){
				address1 = "";
			} else{
				ref++;
			}
			if( address2 === $('#ref_complemento').val()){
				address2 = "";
			} else{
				ref++;
			}
			if( city === $('#ref_nombre_ciudad').val()){
				city = "";
				city_id = "";
			} else{
				ref++;
			}
			if( phone === $('#ref_fijo').val()){
				phone = "";
			} else{
				ref++;
			}
			if( phone_mobile === $('#ref_movil').val()){
				phone_mobile = "";
			} else{
				ref++;
			}
		}
		{literal}
		if(ref > 0 || !id_address){
			var info = ({
				"id_address":id_address,
				"id_country":id_country,
				"id_state":id_state,
				"id_customer":id_customer,
				"alias":alias,
				"address1":address1,
				"address2":address2,
				"city":city,
				"city_id":city_id,
				"phone":phone,
				"phone_mobile":phone_mobile,
				"active":active
			});
		{/literal}
		$.ajax({
			type:"post",
			url:"{$base_dir}ajax_address_order.php",
			data:info,
			beforeSend: function(ev) {
					var result = Validate();
					if (result){
						$("#nueva-direccion").empty();
						$("#nueva-direccion").html('<img style="margin: auto;" src="{$img_ps_dir}ad/waiting.gif" />');
					}else{
						ev.abort();
					}
			},
			success: function(response){
				lightbox_hide();
				location.reload();
			}
		});
		}
	});
function Validate(){
		var id_state=$('#estado').val();
		var alias=$('#alias').val();
		var address1=$('#direccion').val();
		var address2=$('#complemento').val();
		var city=$('#ciudad').val();
		var phone=$('#fijo').val();
		var phone_mobile=$('#movil').val();
				
		if(id_state==""){
			$('#label-estado').append('<span class="validacion" id="obliga-estado">Campo Requerido</span>');
			$('#estado').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-estado').remove();
			$('#estado').removeAttr("style");
		}
		
		if(city==""){
			$('#label-ciudad').append('<span class="validacion" id="obliga-ciudad">Campo Requerido</span>');
			$('#ciudad').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-ciudad').remove();
			$('#ciudad').removeAttr("style");		 
		}
		{literal}
		if(phone==""){
			$('#label-fijo').append('<span class="validacion" id="obliga-fijo">Campo Requerido</span>');
			$('#fijo').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			if (phone.match(/^[2-8]{1}\d{6}$/) || phone_mobile.match(/^[3]{1}([0-2]|[5]){1}\d{1}[2-9]{1}\d{6}$/)){
				$('#obliga-fijo').remove();
				$('#fijo').removeAttr("style"); 
			}else{
				$('#label-fijo').append('<span class="validacion" id="obliga-fijo">Campo requerido</span>');
				$('#fijo').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			}
		}
		
		if(phone_mobile==""){
			$('#label-movil').append('<span class="validacion" id="obliga-movil">Campo Requerido</span>');
			$('#movil').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			if (phone_mobile.match(/^[3]{1}([0-2]|[5]){1}\d{1}[2-9]{1}\d{6}$/)){
				$('#obliga-movil').remove();
				$('#movil').removeAttr("style"); 
			}else{
				$('#label-movil').append('<span class="validacion" id="obliga-movil">Campo requerido</span>');
				$('#movil').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			}
		}
		{/literal}
		if(address1==""){
			$('#label-direccion').append('<span class="validacion" id="obliga-direccion">Campo Requerido</span>');
			$('#direccion').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-direccion').remove();
			$('#direccion').removeAttr("style"); 
		}
		
		
		if(address2==""){
			$('#label-complemento').append('<span class="validacion" id="obliga-complemento">Campo Requerido</span>');
			$('#complemento').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-complemento').remove();
			$('#complemento').removeAttr("style"); 
		}
		
		
		if(alias==""){
			$('#label-alias').append('<span class="validacion" id="obliga-alias">Campo Requerido</span>');
			$('#alias').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-alias').remove();
			$('#alias').removeAttr("style"); 
		}
		var error=$('.validacion').length;
		
		if(error==0){
			return true;
		}else{
			return false;
		}
	}
	
	$('.enviar-form').click(function(){
		if($('[name="id_address_delivery"]').is(':checked')){
			$('#obliga-eleccion').remove();
			$('#form_dir').submit();
		}else{
			$('#titulo-1').append('<span class="validacion" id="obliga-eleccion">Campo requerido</span>')
		}	
	});
</script>