


<link rel="stylesheet" type="text/css" href="{$css_dir}order-address.css">

{if $opc}
	{assign var="back_order_page" value="order-opc.php"}
{else}
	{assign var="back_order_page" value="order.php"}
{/if}


{* Will be deleted for 1.5 version and more *}
{if !isset($formatedAddressFieldsValuesList)}
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

	{* PrestaShop 1.4.0.17 compatibility *}
	{if isset($addresses)}
		{foreach from=$addresses key=k item=address}
			{counter start=0 skip=1 assign=address_key_number}
			{$id_address = $address.id_address}
			{foreach from=$address key=address_key item=address_content}
				{if !in_array($address_key, $ignoreList)}
					{$formatedAddressFieldsValuesList.$id_address.ordered_fields.$address_key_number = $address_key}
					{$formatedAddressFieldsValuesList.$id_address.formated_fields_values.$address_key = $address_content}
					{counter}
				{/if}
			{/foreach}
		{/foreach}
	{/if}
{/if}

<script type="text/javascript">
// <![CDATA[
	{if !$opc}
	var orderProcess = 'order';
	var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
	var currencyRate = '{$currencyRate|floatval}';
	var currencyFormat = '{$currencyFormat|intval}';
	var currencyBlank = '{$currencyBlank|intval}';
	var txtProduct = "{l s='product' js=1}";
	var txtProducts = "{l s='products' js=1}";
	{/if}
	
	var addressMultishippingUrl = "{$link->getPageLink('address', true, NULL, "back={$back_order_page}?step=1{'&multi-shipping=1'|urlencode}{if $back}&mod={$back|urlencode}{/if}")}";
	var addressUrl = "{$link->getPageLink('address', true, NULL, "back={$back_order_page}?step=1{if $back}&mod={$back}{/if}")}";

	var formatedAddressFieldsValuesList = new Array();

	{foreach from=$formatedAddressFieldsValuesList key=id_address item=type}
		formatedAddressFieldsValuesList[{$id_address}] =
		{ldelim}
			'ordered_fields':[
				{foreach from=$type.ordered_fields key=num_field item=field_name name=inv_loop}
					{if !$smarty.foreach.inv_loop.first},{/if}"{$field_name}"
				{/foreach}
			],
			'formated_fields_values':{ldelim}
					{foreach from=$type.formated_fields_values key=pattern_name item=field_name name=inv_loop}
						{if !$smarty.foreach.inv_loop.first},{/if}"{$pattern_name}":"{$field_name}"
					{/foreach}
				{rdelim}
		{rdelim}
	{/foreach}

	function getAddressesTitles()
	{
		return {
						'invoice': "{l s='Your billing address' js=1}",
						'delivery': "{l s='Your delivery address' js=1}"
			};

	}


	function buildAddressBlock(id_address, address_type, dest_comp)
	{
		//alert(id_address);
		var adr_titles_vals = getAddressesTitles();
		var li_content = formatedAddressFieldsValuesList[id_address]['formated_fields_values'];
		var ordered_fields_name = ['title'];

		ordered_fields_name = ordered_fields_name.concat(formatedAddressFieldsValuesList[id_address]['ordered_fields']);
		ordered_fields_name = ordered_fields_name.concat(['update']);

		dest_comp.html('');

		li_content['title'] = adr_titles_vals[address_type];
		li_content['update'] = '<a href="{$link->getPageLink('address', true, NULL, "id_address")}'+id_address+'&amp;back={$back_order_page}?step=1{if $back}&mod={$back}{/if}" title="{l s='Update' js=1}">&raquo; {l s='Update' js=1}</a>';

		appendAddressList(dest_comp, li_content, ordered_fields_name);
	}

	function appendAddressList(dest_comp, values, fields_name)
	{
		for (var item in fields_name)
		{
			var name = fields_name[item];
			var value = getFieldValue(name, values);
			if (value != "")
			{
				var new_li = document.createElement('li');
				new_li.className = 'address_'+ name;
				new_li.innerHTML = getFieldValue(name, values);
				dest_comp.parent().append(new_li);
			}
		}
	}

	function getFieldValue(field_name, values)
	{
		var reg=new RegExp("[ ]+", "g");

		var items = field_name.split(reg);
		var vals = new Array();

		for (var field_item in items)
		{
			items[field_item] = items[field_item].replace(",", "");
			vals.push(values[items[field_item]]);
		}
		return vals.join(" ");
	}

//]]>
function hide_date_delivered(id_address){

			$.ajax({
				type: "post",
				url: "{$base_dir}ajaxs/ajax_address.php",
				data: {
					"ajax":true,
					"id_city_by_id_address":true,
					"id_address": id_address
				},
				success: function(response){
					var id_city = $.parseJSON(response);
		if(id_city != 1184 ){
			$("#date_delivered").prop( "disabled", true );
			$('#hour_delivered_h').prop( "disabled", true );
			$('#titulo-2').hide();
			$('#label-dia').hide();
			$('#label-hora').hide();
		}else{
			$("#date_delivered").prop( "disabled", false  );
			$('#hour_delivered_h').prop( "disabled", false  );
			$('#titulo-2').show();
			$('#label-dia').show();
			$('#label-hora').show();
		}

				}
			});	

}



	function toggleAddressForm(){
		$('.agregaNueva').toggleClass("titulo");
        $('#nueva-direccion').slideToggle();
		$('.navigation_block').slideToggle();
	}
	function changeAddress(id){ 

		hide_date_delivered(id);
			
		if(!$("#rb"+id).is(':checked')){
		$('#rb'+id).attr('checked', 'checked');
		$('#rb'+id).trigger("change");
        $('.agregaNueva').removeClass("titulo");
        $('#nueva-direccion').slideUp();
        $('.navigation_block').slideDown();
       } 
	}
</script>
<form action="{$link->getPageLink($back_order_page, true)}{if $formula}&paso=pagos{else}&paso=formula{/if}" method="post" id="form_dir" name="form_dir">
{* if !$opc}
		<div class="titulo-pasos">{l s='Datos de Entrega'}</div>
		<div class="botones">
			<input type="button" id="processAddress" name="processAddress" value="{l s='Continue'} >>" class="enviar-form" />
				<a id="atras11"href="{$link->getPageLink($back_order_page, true, NULL, "step=0{if $back}&back={$back}{/if}")}" title="{l s='Previous'}" >
				<< {l s='Previous'}</a>
		</div>
{/if *}
{assign var='current_step' value='address'}
{include file="$tpl_dir./order-steps.tpl"}
{include file="$tpl_dir./errors.tpl"}

<div id="order-address">

{if $datacustomer['firstname'] == "" || $datacustomer['identification'] == "" || $datacustomer['id_type'] == 0 }
	{include file="$tpl_dir./customer_data_billing.tpl"}
{/if}
	<!-- ************************** PRIMERA COLUMNA ****************************-->
	<!-- <form action="{$link->getPageLink($back_order_page, true)}" method="post"> FORMULARIO COLUMNA 1-->
	<!-- <form action="{$link->getPageLink('address', true)|escape:'html'}" method="post" id="add_address"> FORMULARIO COLUMNA 2-->
	<div class="contenedor" id="primera_columna">
		<div class="titulo" id="titulo-1">
			¿A dónde llevamos tu pedido?
		</div>
		<div class="address_container">		
		<input type="checkbox" name="same" id="addressesAreEquals" value="1" checked="checked" style="display:none;"/>
		{if $direcciones}
			{foreach from=$direcciones item=nr}
			<div class="direccion" onclick="changeAddress({$nr['id_address']});">
					<div class="radio-direccion">
						<input type="radio" id="rb{$nr['id_address']}" name="id_address_delivery" value="{$nr['id_address']}" onchange="enable({$nr['id_address']});updateAddressesDisplay();{if $opc}updateAddressSelection();{/if}" {if $nr['id_address'] == $cart->id_address_delivery}checked="checked"{/if}"/>
					</div>
					<div class="nombre-direccion">{$nr['alias']}</div>
					<div class="detalle-direccion">{$nr['address1']|truncate:40:"...":true} <br />
					{if $nr['express'] && $expressEnabled && $express_productos}
					<div class="express" id="texto_{$nr['id_address']}" {if $nr['id_address'] == $cart->id_address_delivery}style="color:#39CB98;font-weight:600" {/if}>
						<input type="checkbox" id="{$nr['id_address']}" name="express" value="{$nr['id_address']}" onchange="envioExpress({$nr['id_address']})" {if $xps && $nr['id_address'] == $cart->id_address_delivery}checked{/if} {if $nr['id_address'] != $cart->id_address_delivery}disabled {/if}>
						Deseo mi orden con servicio express
					</div>
					{/if}
					{* Mostrar envio nocturno y actualizar dirección*}
					{if $entregaNocturnaEnabled eq 'enabled' && $localidadesBarriosEnabled eq 'enabled' && $paramEntregaNocturana['id_city'] == $nr['id_city']}
					<div id="{$nr['id_address']}_box_entrega_nocturna" class="express" id="texto_{$nr['id_address']}_nocturna" {if $nr['id_address'] == $cart->id_address_delivery}style="color:#39CB98" {/if}>
						<select style="height: 10px; font-size: 10px;" id="{$nr['id_address']}localidades" onchange="displayBarrios({$nr['id_address']})" {if $nr['id_address'] != $cart->id_address_delivery}disabled {/if}><option>-Localidad-</option>
						{$list_localidades}
						</select> - <select style="height: 10px; font-size: 10px;" id="{$nr['id_address']}barrios"><option {if $nr['id_address'] != $cart->id_address_delivery}disabled {/if}>-Barrio-</option></select>
						<br> <input type="checkbox" id="{$nr['id_address']}_nocturno_up" name="envioNocturno" value="{$nr['id_address']}" onchange="updateLocaliadBarrio({$nr['id_address']})" {if $entregaNocturna eq 'enabled' && $nr['id_address'] == $cart->id_address_delivery}checked="checked"{/if} {if $nr['id_address'] != $cart->id_address_delivery}disabled {/if}>
							Deseo mi orden esta misma noche.
					</div>
					{/if}
					{*Mostrar envio nocturno*}
					{if $entregaNocturnaEnabled eq 'enabled' && $localidadesBarriosEnabled eq 'disabled' && $paramEntregaNocturana['id_city'] == $nr['id_city'] && !$paramEntregaNocturana['auto_load']}
					<div id="{$nr['id_address']}_box_entrega_nocturna" class="express" id="texto_{$nr['id_address']}_nocturna" {if $nr['id_address'] == $cart->id_address_delivery}style="color:#39CB98" {/if}>
						
						<input type="checkbox" id="{$nr['id_address']}_nocturno" name="envioNocturno" value="{$nr['id_address']}" onchange="envio_nocturno({$nr['id_address']})" {if $entregaNocturna eq 'enabled' && $nr['id_address'] == $cart->id_address_delivery}checked="checked"{/if} {if $nr['id_address'] != $cart->id_address_delivery}disabled {/if}> 
						 Deseo mi orden esta misma noche
						
					</div>
					{/if}
						</div> 
					<div class="ciudad-direccion">{$nr['city']}</div>
					<div class="estado-direccion">{$nr['state']}</div>
			</div>
			{/foreach}
	{* Inicio fecha  y hora de entrega *}		
		<br /><br />
		<div class="titulo" id="titulo-2" style=" text-align: left;">
			Fecha y hora de entrega
		</div>	
		<br /><br />			
		<div class="etiqueta" id="label-dia"><label>Día<span class="purpura">*</span>:<br></label> 
			{if isset($day_delivered) && isset($js_json_delivered)}
			{$day_delivered}
			<br>	
			{$js_json_delivered}		
			{/if}
		</div>


		<div class="etiqueta" id="label-hora"><label>Hora<span class="purpura">*</span>:<br></label> 
			<select class="seleccion" id="hour_delivered" style="width: 150px !important;">
			</select><br>
		</div>
	{* Fin fecha  y hora de entrega *}				
        <br /><br /><br />
        <a href="javascript:void(0);" class="agregaNueva" onclick="toggleAddressForm();">Agregar nueva dirección</a>
		</div>
		{/if}
	</div>
	<!-- ************************** FIN PRIMERA COLUMNA ****************************-->			
	<!-- ************************** SEGUNDA COLUMNA ****************************-->
	<div class="contenedor" id="nueva-direccion">
		<div class="campoCorto">
			<div class="etiqueta" id="label-estado"><label>Departamento<span class="purpura">*</span>:<br /></label>
				<select class="seleccion" id="estado" name="estado">
					<option value="" selected="selected" disabled>- Departamento* -</option>
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
			</div>
        </div>
        <div class="campoCorto">
			<div class="etiqueta" id="label-ciudad"><label>Ciudad<span class="purpura">*</span>:<br /></label>
				<select class="seleccion" id="ciudad" name="ciudad">
					<option value="" selected="selected" disabled>- Ciudad* -</option>
				</select><br /> 
			</div>
				 <input type="hidden" class="hidden" name="nombre_ciudad" id="nombre_ciudad" value="" />
		</div>
		<div class="campoLargo">
			<div class="etiqueta" id="label-direccion"><label>Dirección<span class="purpura">*</span>:<br /></label>
			<input class="entrada larga" type="text" value="" placeholder="Dirección*" id="direccion" name="direccion"/><br />
			</div> 
		</div>
		<div class="campoLargo">
			<div class="etiqueta" id="label-complemento"><label>Barrio / Indicaciones<span class="purpura">*</span>:<br /></label>
			<input class="entrada larga" type="text" value="" placeholder="Barrio / Indicaciones*" id="complemento" name="complemento"/><br /> 
			</div>
		</div>
        <div class="campoCorto">
            <div class="etiqueta" id="label-fijo"><label>Teléfono 1<span class="purpura">*</span>:<br /></label>
            <input class="entrada" type="text" value="" placeholder="Número fijo o celular*" id="fijo" name="fijo"/><br /> 
            </div>
        </div>
        <div class="campoCorto">
            <div class="etiqueta" id="label-movil"><label>Teléfono 2:<br /></label>
            <input class="entrada" type="text" value="" placeholder="Teléfono 2, opcional" id="movil" name="movil"/><br /> 
            </div>
        </div>
			{*<div class="campoLargo">
				<p class="etiqueta" id="label-alias">Nombre de dirección<span class="purpura">*</span>:<br />
				<input class="entrada larga" type="text" value="" placeholder="Ej: Mi casa, Mi oficina, Mi mamá" id="alias" name="alias"/><br /> 
				</p>
			</div>*}
		<span class="obliga">(<span class="purpura">*</span>) Campos Obligatorios</span>
		{if $direcciones}
		<div style="display:inline-block;">
			<input type="button" value="Registrar dirección" id="new-address"/>
            <a href="javascript:void(0);" onclick="toggleAddressForm();" class="cancelar">Cancelar</a>
		</div>
		{/if}
	</div>
	<!-- ************************** FIN SEGUNDA COLUMNA ****************************-->			
	
	<div class="navigation_block">
			<!-- si la fomula medica existe salto al paso 3 -->			
 {if $formula}
	 <input type="hidden" class="hidden" name="step" value="3" />
	{else}
	<input type="hidden" class="hidden" name="step" value="2" />
{/if}
		<input type="hidden" name="back" value="{$back}" />
		<input type="button" id="processAddress2" name="processAddress2" value="{if !($direcciones)}Guardar y {/if}{l s='Continue'} >>" class="enviar-form" />
        <a id="atras12" href="{$link->getPageLink($back_order_page, true, NULL, "step=0{if $back}&back={$back}{/if}")}" title="{l s='Previous'}" >
        << {l s='Previous'}</a>
	</div>
	</form>
</div>

<div id="standard_lightbox">
    <div class="fog"></div>
    <div id="lightbox_content"></div>
    <div class="recent"></div>
</div>
<script>
    function lightbox_hide(){
        $('#standard_lightbox').fadeOut('slow');
        $('#page').removeClass("blurred");
        $('#'+($('#lightbox_content div').attr("id"))).appendTo( '#standard_lightbox .recent' );
        $('#lightbox_content').empty();
        }
    function standard_lightbox(id){
        $('#lightbox_content').empty();
        $('#'+id).appendTo( "#lightbox_content" );
        $('#lightbox_content #'+id).show();
        $('#standard_lightbox').fadeIn('slow');
        $('#page').addClass("blurred");
    }
    $('#standard_lightbox .fog').click(function(){
        lightbox_hide();
    });
</script>

<link href="{$base_dir}themes/gomarket/css/Lightbox_ConfirmExpress.css" rel="stylesheet" type="text/css">

<div class="contenedor container_24" id="pop-confirmExpress">
    <div class="close_express" onclick="lightbox_hide();"></div>
    <div class="block_title_express">
        Confirmación
    </div>

    <div class="block_information_express">
        <label>El pedido llegará en máximo 90 minutos, el costo del servicio express es de <span id="xpsValue"></span><br> adicionales. ¿Deseas activar este servicio?</label>
    </div>
    
    <div class="block_buttons_express">
    	<div id="xpscancel">No, Cancelar</div>
		<div id="xpsaccept">Si, Aceptar</div>
    </div>
</div>

<script>

	$('#estado').change(function(){
		var id_estado = $(this).val();
		var ciudad = "";
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
			$('#ciudad').html('<option value="" selected="selected" disabled>- Ciudad -</option>');
		}else{
			$.ajax({
				type: "post",
				url: "{$base_dir}ajax_formulario_cities.php",
				data: {
					"id_state":id_estado
				},
				success: function(response){
					var json = $.parseJSON(response);
					$('#ciudad').html('<option value="" selected="selected" disabled>- Ciudad -</option>'+json.results);
					$('#ciudad').val(ciudad);
					ciudad_s();
				}
			});
		}
	});
	

	$('#ciudad').change(function(){
		ciudad_s();
		});
	
	function ciudad_s()
	{
		//alert($("#ciudad :selected").text());	
		$("#nombre_ciudad").val($("#ciudad :selected").text()); 
		if(($('#ciudad').val()) != "")$('#direccion').focus();
	}

	
	$('#new-address').click(function(){
		$('.validacion').remove();
		var id_country={$pais};
		var id_state=$('#estado').val();
		var id_customer={$cliente};
		var alias="Direccion {($direcciones|@count)+1}";
		var address1=$('#direccion').val();
		var address2=$('#complemento').val();
		var city=$('#nombre_ciudad').val();
		var city_id=$('#ciudad').val();
		var phone=$('#fijo').val();
		var phone_mobile=$('#movil').val();
		var active = 1;
		$.ajax({
			type:"post",
			url:"{$base_dir}ajax_address_order.php",
			data:{
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
			},
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

				formatedAddressFieldsValuesList[response] =
				{
					'ordered_fields':[
						"dni"
						,"firstname lastname"
						,"address1"
						,"address2"
						,"Country:name"
						,"State:name"
						,"city"
						,"phone"
					],
					'formated_fields_values':{
						"dni":""
						,"firstname":""
						,"lastname":""
						,"address1":address1
						,"address2":address2
						,"Country:name":id_country
						,"State:name":id_state
						,"city":city
						,"phone":phone
					}
				}

				$('#form_dir').append('<input type="radio" id="rb'+response+'" name="id_address_delivery" value="'+response+'" onchange="enable('+response+');updateAddressesDisplay();{if $opc}updateAddressSelection();{/if}" checked="checked"/>');
				changeAddress(response);
				//$('#processAddress2').submit();
				$('#form_dir').submit();
			}
		})
	})
	
{literal}
	function Validate() {
		var id_state=$('#estado').val();
		//var alias=$('#alias').val();
		var address1=$('#direccion').val();
		var address2=$('#complemento').val();
		var city=$('#ciudad').val();
		var phone=$('#fijo').val();
		//var phone_mobile=$('#movil').val();
				
		if(id_state==""){
			$('#obliga-estado').remove();
			$('#label-estado').parent().append('<span class="validacion" id="obliga-estado">Campo Requerido</span>');
			$('#estado').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-estado').remove();
			$('#estado').removeAttr("style");
		}
		
		if(city==""){
			$('#obliga-ciudad').remove();
			$('#label-ciudad').parent().append('<span class="validacion" id="obliga-ciudad">Campo Requerido</span>');
			$('#ciudad').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-ciudad').remove();
			$('#ciudad').removeAttr("style");		 
		}
		
		if(phone==""){
			$('#obliga-fijo').remove();
			$('#label-fijo').parent().append('<span class="validacion" id="obliga-fijo">Campo Requerido</span>');
			$('#fijo').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			if (phone.match(/^[2-8]{1}\d{6}$/) || phone.match(/^[3]{1}([0-2]|[5]){1}\d{1}[2-9]{1}\d{6}$/)){
				$('#obliga-fijo').remove();
				$('#fijo').removeAttr("style"); 
			}else{
				$('#obliga-fijo').remove();
				$('#label-fijo').parent().append('<span class="validacion" id="obliga-fijo">Campo requerido</span>');
				$('#fijo').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			}
		}
		
		/*if(phone_mobile==""){
			$('#obliga-movil').remove();
			$('#label-movil').parent().append('<span class="validacion" id="obliga-movil">Campo Requerido</span>');
			$('#movil').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			if (phone_mobile.match(/^[3]{1}([0-2]|[5]){1}\d{1}[2-9]{1}\d{6}$/)){
				$('#obliga-movil').remove();
				$('#movil').removeAttr("style"); 
			}else{
				$('#obliga-movil').remove();
				$('#label-movil').parent().append('<span class="validacion" id="obliga-movil">Campo requerido</span>');
				$('#movil').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			}
		}*/
		
		if(address1==""){
			$('#obliga-direccion').remove();
			$('#label-direccion').parent().append('<span class="validacion" id="obliga-direccion">Campo Requerido</span>');
			$('#direccion').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-direccion').remove();
			$('#direccion').removeAttr("style"); 
		}
		
		
		if(address2==""){
			$('#obliga-complemento').remove();
			$('#label-complemento').parent().append('<span class="validacion" id="obliga-complemento">Campo Requerido</span>');
			$('#complemento').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-complemento').remove();
			$('#complemento').removeAttr("style"); 
		}
		
		
		/*if(alias==""){
			$('#label-alias').parent().append('<span class="validacion" id="obliga-alias">Campo Requerido</span>');
			$('#alias').attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		}else{
			$('#obliga-alias').remove();
			$('#alias').removeAttr("style"); 
		}*/

		var error=$('.validacion').length;
		
		if(error==0){
			return true;
		}else{
			return false;
		}
	}

	function validarDni(nombre){
		valor = $("#"+nombre).val();
		tipo = $("#txt_type_document_customer").val();
		if(valor && tipo){
			letra = valor.split("");
			ref = "";
			cont = 0;
			for (i = 0; i < letra.length; i++){
				if ( letra[i] != " "){
					if (ref == letra[i]){cont++;}
					else{cont = 0;}
					if (cont < 5){
						comp = 0;
						opciones = ['01234','12345','23456','34567','45678','56789','67890','78901','89012','90123',
									'43210','54321','65432','76543','87654','98765','09876','10987','21098','32109'];
						$.each(opciones, function(index, value){res=valor.split(value);
							if ( res.length > 1 ){comp++;};
						});
						if(comp > 1 ||
							((tipo != 3 && tipo != 2 && tipo != 4) && !((valor > 9999 && valor < 100000000) || (valor > 1000000000 && valor < 4099999999)))
						){
							error = "Campo requerido."
						}
						else {
							if(tipo == 4){
								NIT = valor.split("-");
								if ((NIT.length != 2) || (NIT[1].length != 1) || isNaN(NIT[0]) || isNaN(NIT[1]))
									{error = "Campo requerido.";}
							}else{error ="";}
						}
					}
					else{error = "Campo requerido.";i=letra.length;}
					ref = letra[i];
				}
				else{error = "Campo requerido.";i=letra.length;}
			}
		}
		else {error = "Campo requerido."}
		$('#e_'+nombre).remove();
		$('#'+nombre).parent().parent().append("<label class='errorrequired' id='e_"+nombre+"'>"+error+"</label>");
		if (error){
			$('#'+nombre).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			$('#'+nombre).focus();
			return false;
			}
		else{
			$('#'+nombre).attr("style", "border-color:#3A9B37");
			return true;
			}
	}

	function validarSelect(nombre){
		valor = $("#"+nombre).val();
		if (valor){
			error = "";
			$('#e_'+nombre).remove();
			$('#error'+nombre).html(error);
			$('#'+nombre).attr("style", "border-color:#3A9B37");
			return true;
		}
		error = "Campo requerido.";
		$('#e_'+nombre).remove();
		$('#'+nombre).parent().parent().append("<label class='errorrequired' id='e_"+nombre+"'>"+error+"</label>");
		$('#'+nombre).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
		$('#'+nombre).focus();
		return false;
	}

	 function validarNombre(tipo){
		nombre = $('#'+tipo).val();
		if (nombre.length < 3){
			error = "Campo requerido."
			}
		else{
			letra = nombre.split("");
			ref = "";
			cont = 1;
			for (i = 0; i < letra.length; i++){
				if (isNaN(letra[i]) || letra[i] == " "){
					if (ref == letra[i]){cont++;}
					else{cont = 1;}
					if (cont < 3){
						error="";
					}
					else{error = "Campo requerido.";i=letra.length;}
					ref = letra[i];
				}
				else{error = "Campo requerido.";i=letra.length;}
			}

		}
		$('#e_'+tipo).remove();
		$('#'+tipo).parent().parent().append("<label class='errorrequired' id='e_"+tipo+"'>"+error+"</label>");
		if (error){
			$('#'+tipo).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			$('#'+tipo).focus();
			return false;
			}
		else{
			$('#'+tipo).attr("style", "border-color:#3A9B37");
			return true;
			}
	}

		function validarTelefono(nombre){
		valor = $("#"+nombre).val();
		if(valor){
			if (isNaN(valor) || !(valor.length == 7 || (valor.length == 10 && valor.substr(0,1) == "3"))){
				error = "Campo requerido.";
			}
			else{error="";}
		}
		else {error = "Campo requerido"}
		$('#e_'+nombre).remove();
		$('#'+nombre).parent().parent().append("<label class='errorrequired' id='e_"+nombre+"'>"+error+"</label>");
		if (error){
			$('#'+nombre).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			$('#'+nombre).focus();
			return false;
			}
		else{
			$('#'+nombre).attr("style", "border-color:#3A9B37");
			return true;
			}
	}

	function validarDireccion(nombre){
		valor = $("#"+nombre).val();
		if (valor.length < 10) {
			error = "Campo requerido.";
		} else {
			error = "";
		}

		$('#e_'+nombre).remove();
		$('#'+nombre).parent().parent().append("<label class='errorrequired' id='e_"+nombre+"'>"+error+"</label>");
 		
 		if ( error != "" ) {
			$('#'+nombre).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			$('#'+nombre).focus();
			return false;
		} else {
			$('#'+nombre).attr("style", "border-color:#3A9B37");
			return true;
		}
	}

	function validarVacio(nombre){
		valor = $("#"+nombre).val();
		if (valor.length < 4) {
			error = "Campo requerido.";
		} else {
			error = "";
		}

		$('#e_'+nombre).remove();
		$('#'+nombre).parent().parent().append("<label class='errorrequired' id='e_"+nombre+"'>"+error+"</label>");

		if (error) {
			$('#'+nombre).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			$('#'+nombre).focus();
			return false;
		} else {
			$('#'+nombre).attr("style", "border-color:#3A9B37");
			return true;
		}
	}

	function validarNIT(nombre) {
		valor = $("#"+nombre).val();

		var regex = /^\d{8}-\d$/;
		if ( !regex.test(valor) ) {
			error = "Campo requerido.";
		} else {
			error = "";
		}

		$('#e_'+nombre).remove();
		$('#'+nombre).parent().parent().append("<label class='errorrequired' id='e_"+nombre+"'>"+error+"</label>");

		if (error) {
			$('#'+nombre).attr("style", "border-color:#A5689C;background-color:#FFFAFA");
			$('#'+nombre).focus();
			return false;
		} else {
			$('#'+nombre).attr("style", "border-color:#3A9B37");
			return true;
		}
	}

	function validatedatabilling () {
		errorDataBilling = "";

		if ( $('#container_data_billing').length ) {
			if ( !validarSelect("txt_type_document_customer") ) {
				errorDataBilling += "errortrue";
			}

			if ( !validarNombre("txt_firstname_customer") ) {
				errorDataBilling += "errortrue";
			}

			if ( $('#txt_type_document_customer').val() == 4 ) {

				if ( !validarNIT("txt_number_document_customer") ) {
					errorDataBilling += "errortrue";
				}

			} else if( $('#txt_type_document_customer').val() != 4 ) {

				if ( !validarNombre("txt_lastname_customer")) {
					errorDataBilling += "errortrue";
				}

				if ( !validarDni("txt_number_document_customer") ) {
					errorDataBilling += "errortrue";
				}
			}
		}

		if ( typeof($('[name="id_address_delivery"]').val()) === "undefined" ) {
			if ( !validarSelect('estado') ) {
				errorDataBilling += "errortrue";
			}

			if ( !validarDireccion('direccion') ) {
				errorDataBilling += "errortrue";
			}

			if ( !validarSelect('ciudad') ) {
				errorDataBilling += "errortrue";
			} 

			if ( !validarTelefono('fijo') ) {
				errorDataBilling += "errortrue";
			}

			if ( !validarVacio('complemento') ) {
				errorDataBilling += "errortrue";
			}
		}

		if ( errorDataBilling != "" ) {
			return false;
		} else {
			return true;
		}
	}

{/literal}

	$('.enviar-form').click(function(){

		var type_document = $('#txt_type_document_customer').val();
		var number_document = $('#txt_number_document_customer').val();
		var first_name = $('#txt_firstname_customer').val();
		var last_name = $('#txt_lastname_customer').val();
		var id_state = $('#estado').val();
		var address1 = $('#direccion').val();
		var address2 = $('#complemento').val();
		var city = $('#nombre_ciudad').val();
		var city_id = $('#ciudad').val();
		var phone = $('#fijo').val();
		var phone_mobile = $('#movil').val();
		var active = 1;
		var id_country = {$pais};
		var id_customer = {$cliente};
		var contdata_billing = false;
		var existdir = $('[name="id_address_delivery"]').val();

		if ( $('#container_data_billing').length ) {
			contdata_billing = true;
		}

		if ( typeof($('[name="id_address_delivery"]').val()) === "undefined" ) {
			existdir = "";
		}

        if ( $('[name="id_address_delivery"]').is(':checked') && !contdata_billing ) {
            $('#form_dir').submit();
        } else {

			$.ajax({
				type : "post",
				url : "data_billing.php",
				data : {
					"action" : 'insertDataBillingCustomer',
					"contdata_billing" : contdata_billing,
					"id_customer" : id_customer,
					"type_document" : type_document,
					"number_document" : number_document,
					"first_name" : first_name,
					"last_name" : last_name,
					"id_state" : id_state,
					"address1" : address1,
					"address2" : address2,
					"city" : city,
					"city_id" : city_id,
					"phone" : phone,
					"phone_mobile" : phone_mobile,
					"active" : active,
					"id_country" : id_country,
					"existdir" : existdir
				},
				beforeSend: function(beforeresponse) {
					var resultdatabilling = validatedatabilling();
					if ( !resultdatabilling ){
						beforeresponse.abort();
					}
				},
				success: function(response){
					if ( response == 1 ) {
						$('#form_dir').submit();
					}
				}
			});
		}
	});

    function getDocument(){
        var fieldname = 'Nombre';
        if($('#txt_type_document_customer').val() == 4){
            $('#label-lastname_customer').parent().slideUp();
            fieldname = 'Razón social';
            $('#txt_firstname_customer').val('');
        }else{
            $('#label-lastname_customer').parent().slideDown();

        }
        $('#txt_firstname_customer').attr('placeholder', fieldname+'*');
        fieldname += '<span class="purpura">*</span>:';
        $('#label-firstname_customer label').html(fieldname);
    }
		$(window).load(function() {
			        hide_date_delivered({$cart->id_address_delivery}); 
			{if !($direcciones)}
			$('#nueva-direccion').slideDown();
			{/if}
            $('#txt_type_document_customer').change(function(){
                getDocument();
            });


			});
		</script>
             
