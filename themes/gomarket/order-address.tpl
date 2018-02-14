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
        $(".fecha_hora").hide();
      }else{
        $(".fecha_hora").show();
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
{assign var='current_step' value='address'}
{include file="$tpl_dir./order-steps.tpl"}
{include file="$tpl_dir./errors.tpl"}

{* New Checkout *}
{* Update of the client data / First address *}
{if !$datacustomer['firstname'] 
    || !$datacustomer['identification'] 
    || !$datacustomer['id_type']
    || !$direcciones}
<form action="{$link->getPageLink('address', true)|escape:'html'}" method="post">
{include file="$tpl_dir./form-billing-user.tpl"}

<hr class="checkout-line">
<div class="checkout w-4">
  <h3>¿A <b>dónde</b> llevamos tu pedido?</h3>
  {include file="$tpl_dir./form-billing-address.tpl"}
</div>
<!-- /.checkout.w-4 --> 

<div class="checkout w-4">
<!-- .container-fluid -->
  <div class="container-fluid">
    <div class="row">
      <!-- .col-xs-12.col-sm-6 -->
      <div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<button type="button" class="btn2 btn-block btn-outline-secondary">Regresar</button>
				</div>
			</div>
      <!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
      <div class="col-xs-12 col-sm-6">
				<div class="form-group">
          <!-- Si la fomula medica existe salto al paso 3 -->			
          <input type="hidden" name="step" value="{if $formula}3{else}2{/if}" />
          {*if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if*}
          <input type="hidden" name="back" value="order.php?step=2&multi-shipping=0" />
		      {if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
          <input type="hidden" name="token" value="{$token}" />
          <input type="hidden" name="id_country" value="{$id_country}" />

					<button type="submit" class="btn2 btn-block btn-primary" name="submitAddress">Continuar</button>
				</div>
			</div>
      <!-- /.col-xs-12.col-sm-6 -->
    </div>
    <!-- /.row -->
	</div>
  <!-- /.container-fluid -->
</div>
<!-- /.checkout.w-4 --> 
</form>
{/if}

{* Address list *}
{if $direcciones}
<form action="{$link->getPageLink($back_order_page, true)}{if $formula}&paso=pagos{else}&paso=formula{/if}" method="post"  id="form_dir" name="form_dir">
<!-- .checkout.w-7 -->
<div class="checkout w-7">
  <h3>¿A <b>dónde</b> llevamos tu pedido?</h3>
  <input type="checkbox" name="same" id="addressesAreEquals" value="1" checked="checked" style="display:none;"/>
  {foreach from=$direcciones item=address}
  <address data-id="{$address['id_address']}" {if $address['id_address'] == $cart->id_address_delivery}class="selected"{/if} onclick="hide_date_delivered({$address['id_address']});">
    <!-- .container-fluid -->
    <div class="container-fluid">
      <div class="row">
        <!-- .col-xs-12.col-md-4 -->
        <div class="col-xs-12 col-md-4">
          <div class="radio">
            <input class="radio-address" id="rb{$address['id_address']}" name="id_address_delivery" value="{$address['id_address']}" type="radio" onchange="enable({$address['id_address']});updateAddressesDisplay();{if $opc}updateAddressSelection();{/if}" {if $address['id_address'] == $cart->id_address_delivery}checked="checked"{/if}>
            <label for="rb{$address['id_address']}" class="radio-label"><b>{$address['alias']}</b></label>
          </div>
        </div>
        <!-- /.col-xs-12.col-md-4 -->

        <!-- .col-xs-12.col-md-7 -->
        <div class="col-xs-12 col-md-7">
          <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            <span itemprop="streetAddress">{$address['address1']|truncate:40:"...":true}</span>
            <span itemprop="addressLocality">{$address['city']}</span>
            <span itemprop="addressRegion">{$address['state']}</span>
          </div>

          <div class="complete-data">
            {if $address['express'] && $expressEnabled && $express_productos}
              <div class="checkbox">
                <input type="checkbox" id="checkbox-{$address['id_address']}" onchange="envioExpress({$address['id_address']})" name="express" value="{$address['id_address']}" {if $xps && $address['id_address'] == $cart->id_address_delivery}checked{/if} {if $address['id_address'] != $cart->id_address_delivery}disabled {/if}>
                <label for="checkbox-{$address['id_address']}">Deseo mi orden con servicio express</label>
              </div>
            {/if}

            <!-- .datetime-delivery -->
            <div class="datetime-delivery">
              <!-- .row --> 
              <div class="row fecha_hora">
                <div class="col-xs-12">            
                  <b>Fecha y hora de entrega</b>
                </div>
              </div> 
              <!-- /.row --> 
              <!-- .row --> 
              <div class="row fecha_hora">
                <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                    <label for="billing-lastname">Día:</label>
                    <select name="" id="day_delivered{$address['id_address']}" name="day_delivered{$address['id_address']}" class="form-control">
                    {if isset($day_delivered) && isset($js_json_delivered)}
                      {$day_delivered}
                    {/if}
                  </div>
                </div>

                <div class="col-xs-12 col-md-6">
                  <div class="form-group">
                    <label for="billing-lastname">Hora:</label>
                    <select class="form-control seleccion" id="hour_delivered{$address['id_address']}">
                    </select>
                  </div>
                </div>
              </div>

              {if isset($day_delivered) && isset($js_json_delivered)}
                  {$js_json_delivered}
                  <script>
                      $(function(){
                      
                          if ($("#form_dir").length) {
                              form_to_add = "#form_dir"; 
                          } else {
                              form_to_add = "#cod_form"; 
                          }        

                          $("<input>").attr({
                              type: "hidden",
                              id: "date_delivered{$address['id_address']}",
                              name: "date_delivered{$address['id_address']}"
                          }).appendTo(form_to_add);

                          $("<input>").attr({
                              type: "hidden",
                              id: "hour_delivered_h{$address['id_address']}",
                              name: "hour_delivered_h{$address['id_address']}"
                          }).appendTo(form_to_add);

                          $("#hour_delivered{$address['id_address']}").attr("enabled", "true");

                          if (js_json_delivered.hasOwnProperty($("#day_delivered{$address['id_address']}").val())) {
                              $.each(js_json_delivered[$("#day_delivered{$address['id_address']}").val()], function() {
                                  $("#hour_delivered{$address['id_address']}").append(
                                      $("<option></option>").text(this).val(this)
                                  );
                              });
                          }

                          $("#day_delivered{$address['id_address']}").change(function() {

                              if (js_json_delivered.hasOwnProperty($("#day_delivered{$address['id_address']}").val())) {
                                  $("#hour_delivered{$address['id_address']}").html("");         
                                  $.each(js_json_delivered[$("#day_delivered{$address['id_address']}").val()], function() {
                                      $("#hour_delivered{$address['id_address']}").append(
                                          $("<option></option>").text(this).val(this)
                                      );
                              
                                  });
                                  $("#hour_delivered_h{$address['id_address']}").val($("#hour_delivered{$address['id_address']}").val());
                                  $("#hour_delivered{$address['id_address']} option[day_delivered{$address['id_address']}]").show();
                              } else {
                                  $("#hour_delivered{$address['id_address']}").html(""); 
                              }
                              
                          });
                                                                                            
                          $("#hour_delivered_h{$address['id_address']}").val($("#hour_delivered{$address['id_address']}").val());
                          $("#date_delivered{$address['id_address']}").val($("#day_delivered{$address['id_address']}").val());
                          $("#day_delivered{$address['id_address']}").change(function() {
                              $("#date_delivered{$address['id_address']}").val($("#day_delivered{$address['id_address']}").val());
                          });

                          $("#hour_delivered{$address['id_address']}").change(function() {
                              $("#hour_delivered_h{$address['id_address']}").val($("#hour_delivered{$address['id_address']}").val());
                              
                          });
                      });
                  </script>
              {/if}
              <!-- /.row -->
              <!-- .row --> 
              <div class="row">
                <div class="col-xs-12 col-md-6 visible-md visible-lg"></div>
                <div class="col-xs-12 col-md-6">
                  <div class="form-group text-right">
                    <input type="hidden" name="step" value="{if $formula}3{else}2{/if}" />
                    <button type="submit" class="btn2 btn-block btn-primary">Continuar</button>
                  </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.datetime-delivery -->
          </div>
          <!-- /.complete-data -->
        </div>
        <!-- /.col-xs-12.col-md-7 -->

        <!-- .col-xs-12.col-md-1 -->
        <div class="col-xs-12 col-md-1" onclick='editar_direccion({$address|@json_encode});'>
          <button type="button" class="btn-edit" title="Editar Dirección"></button>
        </div>
        <!-- /.col-xs-12.col-md-1 -->
      </div>
    </div>
    <!-- /.container-fluid -->
  </address>
  {/foreach}

  <address>
    <!-- .container-fluid -->
    <div class="container-fluid" onclick="nueva_direccion();">
      <div class="row">
        <!-- .col-xs-12 -->
        <div class="col-xs-12">
          <div id="add-address">
            <span>+</span>
            <b>Agregar nueva dirección</b>
          </div>
        </div>
        <!-- /.col-xs-12 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </address>
</div>
<!-- /.checkout.w-7 -->

<!-- .checkout.w-7 -->
<div class="checkout w-7">
  <!-- .container-fluid -->
  <div class="container-fluid">
    <div class="row">
      <!-- .col-xs-12 -->
      <div class="col-xs-12 col-sm-6 col-sm-offset-3">
          <a class="btn2 btn-block btn-outline-secondary" href="{$link->getPageLink($back_order_page, true, NULL, "step=0{if $back}&back={$back}{/if}")}">Regresar</a>
      </div>
      <!-- /.col-xs-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.checkout.w-7 -->
</form>
{/if}
{* /$direcciones *}


<div id="standard_lightbox">
    <div class="fog"></div>
    <div id="lightbox_content"></div>
    <div class="recent"></div>
</div>
<script>
    function nueva_direccion(){
      standard_lightbox("new_address");
    }

    function editar_direccion(arreglo){
      inicializar_direccion(arreglo)
      standard_lightbox("new_address", false, "address_form");
    }

    function inicializar_direccion(arreglo){
      $("#estado").val(arreglo.id_state);
      $("#estado").trigger("change");
      $("#nombre_ciudad").val(arreglo.city);
      $("#direccion").val(arreglo.address1);
      $("#complemento").val(arreglo.address2);
      $("#alias").val(arreglo.alias);
      $("#address_id").val(arreglo.id_address);
      $("#fijo").val(arreglo.phone);
      $("#movil").val(arreglo.phone_mobile);

      setTimeout(function(){ 
        $("#ciudad").val(arreglo.id_city); 
      }, 1000);
    }
</script>
<div id="new_address">
  <form action="{$link->getPageLink($back_order_page, true)}{if $formula}&paso=pagos{else}&paso=formula{/if}" method="post" id="address_form">
  <div class="checkout w-4">
    <h3>Agregar <b>nueva</b> dirección</h3>
    {include file="$tpl_dir./form-billing-address.tpl"}
  </div>

  <div class="checkout w-4">
  <!-- .container-fluid -->
    <div class="container-fluid">
      <div class="row">
        <!-- .col-xs-12.col-sm-6 -->
        <div class="col-xs-12 col-sm-6">
          <div class="form-group">
            <button type="button" class="btn2 btn-block btn-outline-secondary" onclick="lightbox_hide('address_form')">Cancelar</button>
          </div>
        </div>
        <!-- /.col-xs-12.col-sm-6 -->
        <!-- .col-xs-12.col-sm-6 -->
        <div class="col-xs-12 col-sm-6">
          <div class="form-group">
            <!-- Si la fomula medica existe salto al paso 3 -->			
            <input type="hidden" name="step" value="{if $formula}3{else}2{/if}" />
            {*if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if*}
            <input type="hidden" name="back" value="order.php?step=2&multi-shipping=0" />
            {if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
            <input type="hidden" name="token" value="{$token}" />
            <input type="hidden" name="id_country" value="{$id_country}" />

            <button type="button" class="btn2 btn-block btn-primary" name="submitAddress" id="new-address2">Guardar y continuar</button>
          </div>
        </div>
        <!-- /.col-xs-12.col-sm-6 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.checkout.w-4 --> 
  </form>
</div>

<link href="{$base_dir}themes/gomarket/css/Lightbox_ConfirmExpress.css" rel="stylesheet" type="text/css">

<script>  

  $('#ciudad').change(function(){
    ciudad_s();
  });
  
  function ciudad_s()
  {
    //alert($("#ciudad :selected").text());	
    $("#nombre_ciudad").val($("#ciudad :selected").text()); 
    if(($('#ciudad').val()) != "")$('#direccion').focus();
  }

  
  $('#new-address2').click(function(){
    $('.validacion').remove();
    var id_country={$pais};
    var id_state=$('#estado').val();
    var id_customer={$cliente};
    var alias=$('#alias').val();
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
        setTimeout(function(){ 
          updateAddressesDisplay();
          changeAddress(response);
          setTimeout(function(){ 
            $('#form_dir').submit(); 
          }, 1000);
        }, 1000);
        
      }
    })
  })
  
{literal}
  function Validate() {
    var id_state=$('#estado').val();
    var address1=$('#direccion').val();
    var address2=$('#complemento').val();
    var city=$('#ciudad').val();
    var phone=$('#fijo').val();
        
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
             
