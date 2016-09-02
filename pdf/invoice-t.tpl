<style type="text/css">
<!--
.textosss {
	color: #000;
}
-->
</style>

<div style="font-size: 8pt; color: #444">
<!-- ADDRESSES -->
<table style="width:40%;" >
    {if !empty($delivery_address)}
        <tr>
            <td>{l s='Delivery Address' pdf='true'}</td>
            <td>{$delivery_address}</td>
        </tr>
        <tr>
            <td>{l s='Billing Address' pdf='true'}</td>
            <td>{$invoice_address}</td>
        </tr>
    {else}
        <tr>
            <td>{l s='Billing & Delivery Address.' pdf='true'}</td>
            <td>{$invoice_address}</td>
        </tr>
    {/if}
    <tr>
        <td><b>Factura de Venta:</b></td>
        <td>{$title|escape:'htmlall':'UTF-8'}</td>
    </tr>
    <tr>
        <td><b>Fecha:</b></td>
        <td>{dateFormat date=$order->date_add full=0}</td>
    </tr>
    <tr>
        <td><span>N&uacute;mero de Pedido:</span></td>
        <td><b>{$order->id}</b></td>
    </tr>
    <tr>
        <td colspan="2">
            <div style="line-height: 1pt; height: auto; text-align: center;">
                <img src="{$img_ps_dir}{$current_state_img}" style="width: 150px; height: auto; text-align: center; "/>
            </div>
        </td>
    </tr>
</table>
<br style="line-height:1px;">
<!-- / ADDRESSES -->
<table border="0" style="width:40%;font-size: 6pt;">
        <tr  style="line-height:6px;">
            <td style="width: 15%; text-align:center">Referencia</td>
            <td style="text-align: left; width: {if !$tax_excluded_display}50%{else}50%{/if}">{l s='Product / Reference' pdf='true'}</td>
            <!-- unit price tax excluded is mandatory -->
        {if !$tax_excluded_display}
            <td style="text-align: right; width: 10%">{l s='Unit Price' pdf='true'}{*l s='(Tax Excl.)' pdf='true'*}</td>
        {/if}
            <td style="text-align: center; width: 5%;">Iva</td>					
            <td style="text-align: center; width: 5%">{l s='Qty' pdf='true'}</td>
            <td style="text-align: right; width: {if !$tax_excluded_display}5%{else}5%{/if}">Subtotal</td>
        </tr>
        <!-- PRODUCTS -->
        {assign var="iva_calc_tot" value="0"} <!--total tax / product -->
        {assign var="sub_total_prod" value="0"} <!--sub total product  no tax-->
        {foreach $order_details as $order_detail}
        {cycle values='#FFF,#DDD' assign=bgcolor}
        <tr style="line-height:6px;">
                <td style="text-align: left; background-color:{$bgcolor}; width:15%">{$order_detail.product_reference}</td>
                <td style="font-size:7pt; text-align: left; background-color:{$bgcolor}; width: {if !$tax_excluded_display}48%{else}45%{/if}">{$order_detail.product_name}</td>
                <!-- unit price tax excluded is mandatory -->
                {if !$tax_excluded_display}
                        <td style="text-align: right; background-color:{$bgcolor}; width: 12%">
                        {displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl}
                        </td>
                {/if}
                <td style="text-align: center; background-color:{$bgcolor}; width:5%">{$order_detail.tax_rate|string_format:"%d"}%</td>
                <td style="text-align: center; background-color:{$bgcolor}; width: 5%">{$order_detail.product_quantity}</td>
                <td style="text-align: right;  background-color:{$bgcolor}; width: {if !$tax_excluded_display}15%{else}25%{/if}">
                        {displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_excl}
                        {assign var="sub_total_prod" value=$sub_total_prod + $order_detail.total_price_tax_excl}
                </td>
        </tr>
            {foreach $order_detail.customizedDatas as $customizationPerAddress}
                {foreach $customizationPerAddress as $customizationId => $customization}
                    <tr style="line-height:6px;background-color:{$bgcolor};">
                        <td style="line-height:3px; text-align: left; width: 45%; vertical-align: top">
                            <blockquote>
                                {if isset($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) && count($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) > 0}
                                        {foreach $customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_] as $customization_infos}
                                                {$customization_infos.name}: {$customization_infos.value}
                                                {if !$smarty.foreach.custo_foreach.last}<br />
                                                {else}
                                                <div style="line-height:0.4pt">&nbsp;</div>
                                                {/if}
                                        {/foreach}
                                {/if}

                                {if isset($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) && count($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) > 0}
                                        {count($customization.datas[$smarty.const._CUSTOMIZE_FILE_])} {l s='image(s)' pdf='true'}
                                {/if}
                            </blockquote>
                        </td>
                        {if !$tax_excluded_display}
                            <td style="text-align: right;"></td>
                        {/if}
                            <td style="text-align: right; width: 10%"><span style="text-align: center; width: 10%; vertical-align: top">({$customization.quantity})</span></td>
                            <td style="text-align: center; width: 10%; vertical-align: top">&nbsp;</td>
                            <td style="width: 15%; text-align: right;"></td>
                    </tr>
                {/foreach}
            {/foreach}
        {/foreach}
        <!-- END PRODUCTS -->

        <!-- CART RULES -->
        {assign var="shipping_discount_tax_excl" value="0"}
        {assign var="shipping_discount_tax_value" value="0"}

        {foreach $cart_rules as $cart_rule}
            {cycle values='#FFF,#DDD' assign=bgcolor}
            <tr style="line-height:6px;background-color:{$bgcolor};text-align:left;">
                <td style="line-height:3px;text-align:left;width:60%;vertical-align:top" colspan="{if !$tax_excluded_display}5{else}4{/if}">{$cart_rule.name}</td>
                <td>
                    {if $tax_excluded_display}
                            - {$cart_rule.value_tax_excl}
                    {else}
                            - {$cart_rule.value}
                    {/if}
                </td>
            </tr>
        {/foreach}
        <!-- END CART RULES -->
    </table>
    <table style="width:40%;margin-left:auto;margin-right: auto;">
        {if (($order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl) > 0)}
        <tr style="line-height:5px;">
                <td style="width: 83%; text-align: right; font-weight: bold">Valor Productos{*l s='Product Total (Tax Excl.)' pdf='true'*}</td>
                <td style="width: 17%; text-align: right;">{*displayPrice currency=$order->id_currency price=$order_invoice->total_products*}{displayPrice currency=$order->id_currency price=$sub_total_prod}</td>
        </tr>

        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Total Tax' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$iva_calc_tot} {*($order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl)*}</td>
        </tr>

        <tr style="line-height:5px;">
                <td style="width: 83%; text-align: right; font-weight: bold">{l s='Product Total (Tax Incl.)' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_products_wt}</td>
        </tr>
        {else}
        <tr style="line-height:5px;">
                <td style="width: 83%; text-align: right; font-weight: bold">Valor Productos{*l s='Product Total' pdf='true'*}</td>
                <td style="width: 17%; text-align: right;">
                        {*displayPrice currency=$order->id_currency price=$order_invoice->total_products_wt*}{displayPrice currency=$order->id_currency price=$sub_total_prod}
                </td>
        </tr>
        {/if}

        {if $order_invoice->total_discount_tax_excl > 0} {*total_discount_tax_incl*}
        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold"> {if $apoyosalud!=NULL}{$apoyosalud}{else}Descuento{/if}{*l s='Total Vouchers' pdf='true'*}</td>
                <td style="width: 17%; text-align: right;">-{displayPrice currency=$order->id_currency price=($order_invoice->total_discount_tax_excl)}</td>					
        </tr>
        {/if}

        {if $order_invoice->total_wrapping_tax_incl > 0}
        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Wrapping Cost' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">

                {if $tax_excluded_display}
                        {displayPrice currency=$order->id_currency price=$order_invoice->total_wrapping_tax_excl}
                {else}
                        {displayPrice currency=$order->id_currency price=$order_invoice->total_wrapping_tax_incl}
                {/if}
                </td>
        </tr>
        {/if}

        {assign var="shipping_discount_tax_excl" value=0}
        {if $order_invoice->total_shipping_tax_incl > 0}
        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Shipping Cost' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">

                        {assign var="shipping_discount_tax_excl" value=($order_invoice->total_shipping_tax_incl/1.16)}

                        {*assign var="shipping_discount_tax_value" value=($order_invoice->total_shipping_tax_incl - $shipping_discount_tax_excl)*}

                        {displayPrice currency=$order->id_currency price=$shipping_discount_tax_excl}

                </td>
        </tr>
        {else}
        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Shipping Cost' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">
                        $ 0
                </td>
        </tr>
        {/if}

        {assign var="subtotal" value=$sub_total_prod-$order_invoice->total_discount_tax_excl+shipping_discount_tax_excl}

        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold"> Sub Total </td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$subtotal}</td>
        </tr>

        {foreach key=key item=item from=$ivas}

        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold"> IVA {$key} % </td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$item}</td>
        </tr>

        {/foreach}

        <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Total' pdf='true'}</td>
                <td style="width: 17%; text-align: right; font-weight: bold;">{displayPrice currency=$order->id_currency price=$order_invoice->total_paid_tax_incl}</td>
        </tr>
    </table>
    <div style="vertical-align:bottom">            
    <table width="100%">
        {if $formu_medical}
            <tr>
                <td style="text-align:justify;"><b>FM</b>&nbsp;<em>Apreciado cliente, recuerde que la formula m&eacute;dica es requisito obligatorio para la venta y/o entrega de medicamentos que requieren prescripci&oacute;n m&eacute;dica seg&uacute;n el art&iacute;culo 19 decreto 2200 del a&ntilde;o 2005, sin copia de este documento nuestro transportador no entregar&aacute; el medicamento; recuerde las diferentes opciones con las que cuenta la compa&ntilde;&iacute;a para cumplir con este requisito, m&aacute;s informaci&oacute;n en <strong>www.farmalisto.com.co</strong></em>
                </td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
            </tr>
        {/if} 
        <tr>
          <td width="48%" style="text-align:justify; font-size:19px;"><br />Por medio de la presente Factura de Venta, el comprador como propietario, representante legal, su representante delegado o dependiente laboral acepta haber recibido real y materialmente las mercanc&iacute;as y/o servicios descritos en este t&iacute;tulo valor por:<br /><br /><b>
                      {displayPrice currency=$order->id_currency price=$order_invoice->total_paid_tax_incl} ({$ValorEnLetras})</b><br><br style="line-height:4px;">
              Lo anterior con fundamento en el art&iacute;culo 772 y siguientes del C.C. Modificados por la Ley 1231 del 17 de Julio de 2008                      
             </td>
          <td width="4%">&nbsp;</td> 
            <td width="48%" style="text-align:justify; font-size:19px;"><blockquote>Nombre Cliente:  &nbsp;&nbsp;&nbsp;&nbsp;_________________________________<br /><br style="line-height:2px;"> Cedula:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________<br />
                <br style="line-height:2px;">Fecha de Recibido:&nbsp;_________________________________<br /><br style="line-height:6px;">
                _______________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________<br style="line-height:6px;"/>
                Firma Autorizada Farmalisto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma Cliente
            </blockquote></td>
        </tr>
        {if $note != ""}
                <tr><strong>Nota:</strong> <em>{$note}</em></tr>
        {/if}
    </table>
    </div>
</div>