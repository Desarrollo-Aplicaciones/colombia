<fieldset>
    <legend>
        <img src="{$module_template_dir}views/img/config.gif" alt="{l s={$displayName} mod='purchasesproducts'}">{l s={$displayName} mod='purchasesproducts'}
    </legend>

    <a class="btn success" href="{$moduleUrl}">Regresar</a> 

    / 

    <!--a class="btn success" href="#dialog" name="modal">Ver inventario</a--> 

    {if count($proveedor) > 0}
        {foreach $proveedor AS $resultProveedor}
            <a href="{$moduleUrl}&page=purchasedetail&id_proveedor={$resultProveedor['id_proveedor']}">{$resultProveedor['proveedor']}</a> /
        {/foreach}
    {/if}
    
    <br><br>

    <div id="grid"></div>
</fieldset>

<!--div id="boxes">
 	<div id="mask"></div> 
    <div id="dialog" class="window"> 

    	<a href="#" class="close">Cerrar</a>

    	<br><br>

    	{if count($inventory) > 0}
    		<ul>
    		{foreach $inventory AS $resultInventory}
    			<li class="name-provider">
    				<a style="cursor: pointer;" class="name-provider-info" id-list="{$resultInventory['id_proveedor']}">
    					<b>{$resultInventory['proveedor']}</b>
    				</a>
    					<ul class="hide-info-inventory" id="info-{$resultInventory['id_proveedor']}">
    						{foreach $resultInventory['groupeddata'] AS $resultProvider}
    							<li class="info-inventory-provider">
    							EAN: {$resultProvider['ean']}
    							<br>
    							Descripción: {$resultProvider['descripcion']}
    							<br>
    							Valor: ${number_format($resultProvider['valor_proveedor'])}
    							<br>
    							Unidades: {$resultProvider['unidades_proveedor']}
    							</li>
    						{/foreach}
    					</ul>
    			</li>
    		{/foreach}
    		</ul>
    	{/if}
 
   	</div>
 
</div-->
    
<script type="text/javascript">
    var globalGridData = {$gridDataDetail};
    for (var i = 0; i < globalGridData.length; i++) {
        if(globalGridData[i].unitReceived == null) {
            globalGridData[i].unitReceived = 0;
        } 
        if(globalGridData[i].unitExpected == null) {
            globalGridData[i].unitExpected = 0;
        }
        if(globalGridData[i].unitPrice == null) {
            globalGridData[i].unitPrice = 0;
        }
        if(globalGridData[i].total_price == null) {
            globalGridData[i].total_price = 0;
        }
        if(globalGridData[i].supplier == null) {
            globalGridData[i].supplier = "No se encontró proveedor";
        }
    }
</script>