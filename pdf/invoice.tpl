<div style="font-size: 8pt; font-weight: bold;">
	{assign var="sizeticket" value="100%"}
	<table style="width: {$sizeticket};">
		<tr style="text-align: center;">
			<td></td>
		</tr>
		<br>
		<tr style="text-align: center; font-size: 7pt;">
			<td>
				FARMALATAM COLOMBIA SAS
			</td>
		</tr>
		<tr style="text-align: center; font-size: 7pt;">
			<td>
				NIT: 900.659.494-9
			</td>
		</tr>
		<br>
		<tr style="text-align: center;">
			<td>
				{dateFormat date=$order->date_add full=0}
			</td>
		</tr>
		<br>
		<tr style="text-align: center;">
			<td>
				<span>Factura No: {$order->invoice_number}</span><br>
				<span style="font-size: 12pt;">Pedido: {$order->id}</span>
			</td>
		</tr>
		<br>
		<tr>
			<td>
				<table><tr><td>{$invoice_address}</td></tr></table>
			</td>
		</tr>
	</table>
	<br>
	<table style="width: {$sizeticket};">
		<tr>
			<td>
				Referencia | Producto | Valor C/U | Iva | Cant. | Subtotal
			</td>
		</tr>
		<br style="line-height: 2px;">
		{assign var="iva_calc_tot" value="0"}
		{assign var="sub_total_prod" value="0"}
		{foreach $order_details as $order_detail}
			<tr>
				<td>
					{$order_detail.product_reference} | {$order_detail.product_name} | {displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl} | {$order_detail.tax_rate|string_format:"%d"}% | {$order_detail.product_quantity} | {displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_excl}{assign var="sub_total_prod" value=$sub_total_prod + $order_detail.total_price_tax_excl}
				</td>
			</tr>
			<br style="line-height: 2px;">
		{/foreach}
		<br>
	</table>
	<br>
	<table style="width: {$sizeticket};">
		{assign var="shipping_discount_tax_excl" value="0"}
		{assign var="shipping_discount_tax_value" value="0"}
		{foreach $cart_rules as $cart_rule}
			<tr>
				<td style="width: 50%">{$cart_rule.name}</td>
				<td style="width: 50%">
					{if $tax_excluded_display}
						- {$cart_rule.value_tax_excl}
					{else}
						- {$cart_rule.value}
					{/if}
				</td>
			</tr>
		{/foreach}
	</table>
	<br>
    <table style="width: {$sizeticket}">
		{if (($order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl) > 0)}
			<tr>
				<td style="width: 50%;">Valor Productos</td>
				<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$sub_total_prod}</td>
			</tr>
			<tr>
				<td style="width: 50%;">Total Tax</td>
				<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$iva_calc_tot}</td>
			</tr>
			<tr>
				<td style="width: 50%;">Product Total (Tax Incl.)</td>
				<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_products_wt}</td>
			</tr>
		{else}
			<tr>
				<td style="width: 50%;">Valor Productos</td>
				<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$sub_total_prod}</td>
			</tr>
		{/if}

		{if $order_invoice->total_discount_tax_excl > 0}
			<tr>
				<td style="width: 50%;">{if $apoyosalud!=NULL}{$apoyosalud}{else}Descuento{/if}</td>
				<td style="width: 50%; text-align: right;">-{displayPrice currency=$order->id_currency price=($order_invoice->total_discount_tax_excl)}</td>
			</tr>
		{/if}

		{if $order_invoice->total_wrapping_tax_incl > 0}
			<tr>
				<td style="width: 50%;">{l s='Wrapping Cost' pdf='true'}</td>
				<td style="width: 50%;">
					{if $tax_excluded_display}
						{displayPrice currency=$order->id_currency price=$order_invoice->total_wrapping_tax_excl}
					{else}
						{displayPrice currency=$order->id_currency price=$order_invoice->total_wrapping_tax_incl}
					{/if}
				</td>
			</tr>
		{/if}

		{assign var="shipping_discount_tax_excl" value=0}
		
		{*  INICIO  MODIFICACION UBICACION Y COSTO ENVIO   *}

		{if $order_invoice->total_shipping_tax_incl > 0}			
					{assign var="shipping_discount_tax_excl" value=($order_invoice->total_shipping_tax_incl/ ( 1 + ($iva_envio/100) ) )}
		{/if}

		{*  FIN  MODIFICACION UBICACION Y COSTO ENVIO   *}

		{assign var="subtotal" value=$sub_total_prod-$order_invoice->total_discount_tax_excl+shipping_discount_tax_excl}
		<tr>
			<td style="width: 50%;">Sub Total</td>
			<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$subtotal}</td>
		</tr>
		{foreach key=key item=item from=$ivas}
			<tr>
				<td style="width: 50%;">IVA {$key} %</td>
				<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$item}</td>
			</tr>
		{/foreach}


		{*  INICIO  MODIFICACION UBICACION Y COSTO ENVIO   *}

		{if $order_invoice->total_shipping_tax_incl > 0}
			<tr>
				<td style="width: 50%;">{l s='Shipping Cost' pdf='true'}</td>
				<td style="width: 50%; text-align: right;">					
					{displayPrice currency=$order->id_currency price=$shipping_discount_tax_excl}
				</td>
			</tr>
		{else}
			<tr>
				<td style="width: 50%;">{l s='Shipping Cost' pdf='true'}</td>
				<td style="width: 50%; text-align: right;">$ 0</td>
			</tr>
		{/if}

		{*  FIN  MODIFICACION UBICACION Y COSTO ENVIO   *}


		<tr>
			<td style="width: 50%;">{l s='Total' pdf='true'}</td>
			<td style="width: 50%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_paid_tax_incl}</td>
		</tr>
	</table>
	<br>
	<table style="width: {$sizeticket};">
		<tr style="text-align: center; font-size: 12pt;">
			<td>
				{if $paid_out > 0}
					Sin Recaudo
				{else}
					Con Recaudo
				{/if}
			</td>
		</tr>
		<tr style="text-align: center; font-size: 10pt;"><td>{$type_payment}</td></tr>
	</table>
	<br>
	<table style="width: {$sizeticket};">
		<tr><td>Fecha de Entrega: {$date_delivery}</td></tr>
		<tr><td>Hora de Entrega: {$time_windows}</td></tr>
	</table>
	<br>
	{assign var="resolution" value=""}
	{assign var="numeration" value=""}
        {if $invoice_number_header >= 133000}
		{$resolution = "Resoluci&oacute;n DIAN #18762005308295 de 2017/10/19"}
		{$numeration = "Numeraci&oacute;n autorizada de 133000 a 500000"}
	{else if $invoice_number_header >= 28801}
		{$resolution = "Resoluci&oacute;n DIAN #320001325045 de 2015/10/20"}
		{$numeration = "Numeraci&oacute;n autorizada de 28801 a 500000"}
	{else}
		{$resolution = "Resoluci&oacute;n DIAN #320001070365 de 2013/10/17"}
		{$numeration = "Numeraci&oacute;n autorizada de 1 a 28800"}
	{/if}
	<table style="width: {$sizeticket}; font-size: 6pt;">
		<tr><td>IVA r&eacute;gimen com&uacute;n - No somos autorretenedores - No somos grandes contribuyentes - {$resolution} - Autorizado por computador - {$numeration} - Actividad econ&oacute;mica DIAN 4773/4759 - Actividad econ&oacute;mica ICA en Bogot&aacute; 47731 Tarifa 4.14 X Mil</td></tr>
		<tr><td>Direcci&oacute;n: Calle 129 a # 56 b - 23 Bogot&aacute; - Prado veraniego</td></tr>
		<tr><td>Telefono: 4926363</td></tr>
	</table>
	<br>
	{if $formu_medical}
		<table style="width: {$sizeticket}; font-size: 6pt;">
			<tr>
				<td style="text-align:justify;">FM&nbsp;-&nbsp;<em>Apreciado cliente, recuerde que la formula m&eacute;dica es requisito obligatorio para la venta y/o entrega de medicamentos que requieren prescripci&oacute;n m&eacute;dica seg&uacute;n el art&iacute;culo 19 decreto 2200 del a&ntilde;o 2005, sin copia de este documento nuestro transportador no entregar&aacute; el medicamento; recuerde las diferentes opciones con las que cuenta la compa&ntilde;&iacute;a para cumplir con este requisito, m&aacute;s informaci&oacute;n en www.farmalisto.com.co</em></td>
			</tr>
		</table>
		<br>
	{/if}
	<table style="width: {$sizeticket}; font-size: 6pt;">
		<tr>
			<td style="text-align:justify;">Por medio de la presente Factura de Venta, el comprador como propietario, representante legal, su representante delegado o dependiente laboral acepta haber recibido real y materialmente las mercanc&iacute;as y/o servicios descritos en este t&iacute;tulo valor por:</td>
		</tr>
		<tr>
			<td>{displayPrice currency=$order->id_currency price=$order_invoice->total_paid_tax_incl} ({$ValorEnLetras})</td>
		</tr>
		<tr>
			<td style="text-align:justify;">Lo anterior con fundamento en el art&iacute;culo 772 y siguientes del C.C. Modificados por la Ley 1231 del 17 de Julio de 2008</td>
		</tr>
	</table>
	<br>
	<table style="width: {$sizeticket}">
		<tr><td>********************************************************</td></tr>
		<br>
		<tr style="text-align: center;">
			<td>
				<span>Factura No: {$order->invoice_number}</span><br>
				<span>Pedido: {$order->id}</span>
			</td>
		</tr>
		<tr style="text-align: center;">
			<td>{l s='Total' pdf='true'}: {displayPrice currency=$order->id_currency price=$order_invoice->total_paid_tax_incl}</td>
		</tr>
		<tr style="text-align: center;"><td>{$type_payment}</td></tr>
		<tr style="text-align: center;"><td>Identificación: {$dni}</td></tr>
		<tr style="text-align: center;"><td>Nombre: {$namecomplete}</td></tr>
		<tr style="text-align: center;"><td>Dirección: {$address1}</td></tr>
		<tr style="text-align: center;"><td>Dirección2: {$address2}</td></tr>
		<tr style="text-align: center;"><td>Teléfono: {$phone}</td></tr>
		<tr style="text-align: center;"><td>Móvil: {$phone_mobile}</td></tr>
	</table>
	<br>
	<table style="width: {$sizeticket}">
		<tr>
			<td style="width: 25%">Nombre:</td>
			<td style="width: 75%"><p style="border-bottom-style: solid; border-bottom-width: 0.5px;"></p></td>
		</tr>
		<tr>
			<td style="width: 35%">Identificaci&oacute;n:</td>
			<td style="width: 65%"><p style="border-bottom-style: solid; border-bottom-width: 0.5px;"></p></td>
		</tr>
		<tr>
			<td style="width: 45%">Fecha de Recibido:</td>
			<td style="width: 55%"><p style="border-bottom-style: solid; border-bottom-width: 0.5px;"></p></td>
		</tr>
	</table>
	<br>
	<table style="width: {$sizeticket}">
		<tr>
			<td>Firma Autorizada Farmalisto:</td>
		</tr>
	</table>
	<br>
	{if $note != ""}
		<table style="width: {$sizeticket}">
			<tr>
				<td>Nota: <em>{$note}</em></td>
			</tr>
		</table>
		<br>
	{/if}
	<table style="width: {$sizeticket}">
		<tr><td>********************************************************</td></tr>
	</table>	
</div>
