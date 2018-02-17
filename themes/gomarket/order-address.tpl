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
<!--form action="{$link->getPageLink($back_order_page, true)}{if $formula}&paso=pagos{else}&paso=formula{/if}" method="post"-->
<form action="{$link->getPageLink($back_order_page, true, NULL, "step=2")|escape:'html':'UTF-8'}" method="post">
  <!-- .checkout.w-7 -->
  <div class="checkout w-7">
    <h3>¿A <b>dónde</b> llevamos tu pedido?</h3>
    <input type="hidden" name="processAddress" value="">
    <input type="hidden" name="step" value="{if $formula}3{else}2{/if}">
    {foreach from=$direcciones item=address}
    <section data-id="{$address['id_address']}" {if $address['id_address'] == $cart->id_address_delivery}class="selected"{/if}>
      <!-- .container-fluid -->
      <div class="container-fluid">
        <div class="row">
          <!-- .col-xs-12.col-md-4 -->
          <div class="col-xs-12 col-md-4">
            <div class="radio">
              <input class="radio-address" id="rb{$address['id_address']}" name="id_address_delivery" value="{$address['id_address']}" type="radio" {if $address['id_address'] == $cart->id_address_delivery}checked="checked"{/if}>
              <label for="rb{$address['id_address']}" class="radio-label"><b>{$address['alias']}</b></label>
            </div>
          </div>
          <!-- /.col-xs-12.col-md-4 -->

          <!-- .col-xs-11.col-xs-offset-1.col-md-7 -->
          <div class="col-xs-10 col-xs-offset-1 col-md-7 col-md-offset-0">
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
              <span itemprop="streetAddress">{$address['address1']|truncate:40:"...":true}</span>
              <span itemprop="addressLocality">{$address['city']}</span>
              <span itemprop="addressRegion">{$address['state']}</span>
            </div>

            <div class="complete-data">
              {if $address['express'] && $expressEnabled && $express_productos}
                <div class="checkbox">
                  <input type="checkbox" id="checkbox-{$address['id_address']}" name="expressService" value="{$address['id_address']}" {if $xps && $address['id_address'] == $cart->id_address_delivery}checked{/if}>
                  <label for="checkbox-{$address['id_address']}">Deseo mi orden con servicio express</label>
                </div>
              {/if}

              <!-- .datetime-delivery -->
              <div class="datetime-delivery">
                {if $address['id_city'] == "1184"}
                <!-- .row --> 
                <div class="row">
                  <div class="col-xs-12">            
                    <b>Fecha y hora de entrega</b>
                  </div>
                </div> 
                <!-- /.row --> 
                <!-- .row --> 
                <div class="row">
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
                {/if}
                <!-- /.row -->
                <!-- .row --> 
                <div class="row">
                  <div class="col-xs-12 col-md-6 visible-md visible-lg"></div>
                  <div class="col-xs-12 col-md-6">
                    <div class="form-group text-right">
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
          <!-- /.col-xs-11.col-xs-offset-1.col-md-7 -->

          <!-- .col-xs-12.col-md-1 -->
          <div class="col-xs-12 col-md-1 text-right">
            <button type="button" class="btn-address-edit" title="Editar Dirección" data-address='{$address|@json_encode}'></button>
          </div>
          <!-- /.col-xs-12.col-md-1 -->
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    {/foreach}

    <section>
      <!-- .container-fluid -->
      <div class="container-fluid">
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
    </section>
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


<!-- Modal -->
<div id="modal-address" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <form action="{$link->getPageLink('address', true)|escape:'html'}" method="post">
        <div class="modal-body">
          <div class="checkout margin-3"> 
            <h3>Agregar <b>nueva</b> dirección</h3>
            {include file="$tpl_dir./form-billing-address.tpl"}

            <!-- .container-fluid -->
            <div class="container-fluid">
              <div class="row">
                <!-- .col-xs-12.col-sm-6 -->
                <div class="col-xs-12">
                  <div class="form-group">
                    <!-- Si la fomula medica existe salto al paso 3 -->			
                    <input type="hidden" name="step" value="1" />
                    {*if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if*}
                    <input type="hidden" name="back" value="order.php?step=1&multi-shipping=0" />
                    {if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
                    <input type="hidden" name="token" value="{$token}" />

                    <input type="hidden" name="id_country" value="{$id_country}" />
                    <input type="hidden" name="dni" value="{$datacustomer['identification']}" />
                    <input type="hidden" name="firstname" value="{$datacustomer['firstname']}" />
                    <input type="hidden" name="lastname" value="{$datacustomer['lastname']}" />
                    <input type="hidden" name="id_address" value="" />
                    <!--input type="hidden" name="id_type" value="{$datacustomer['id_type']}" /-->

                    <button type="submit" class="btn2 btn-block btn-primary" name="submitAddress">Guardar y continuar</button>
                  </div>
                </div>
                <!-- /.col-xs-12.col-sm-6 -->
              </div>
              <!-- /.row -->
              <div class="row">
                <!-- .col-xs-12.col-sm-6 -->
                <div class="col-xs-12">
                  <div class="form-group">
                    <button type="button" class="btn2 btn-block btn-outline-secondary" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                  </div>
                </div>
                <!-- /.col-xs-12.col-sm-6 -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
          </div>
        </div>
      </form>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /#modal-address -->

{*
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
  <form action="{$link->getPageLink('address', true)|escape:'html'}" method="post" id="address_form">
  <div class="checkout w-4">
    <h3>Agregar <b>nueva</b> dirección</h3>
    {include file="$tpl_dir./form-billing-address.tpl"}
  </div>

  <div class="checkout w-4">
  <!-- .container-fluid -->
    <div class="container-fluid">
      <div class="row">
        <!-- .col-xs-12.col-sm-6 -->
        <div class="col-xs-12">
          <div class="form-group">
            <!-- Si la fomula medica existe salto al paso 3 -->			
            <input type="hidden" name="step" value="{if $formula}3{else}2{/if}" />
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
      <div class="row">
        <!-- .col-xs-12.col-sm-6 -->
        <div class="col-xs-12">
          <div class="form-group">
            <button type="button" class="btn2 btn-block btn-outline-secondary" onclick="lightbox_hide('address_form')">Cancelar</button>
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
*}             
