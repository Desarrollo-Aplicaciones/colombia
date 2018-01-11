<fieldset>
    <legend>
        <img src="{$module_template_dir}views/img/config.gif" alt="{l s={$displayName} mod='purchasesproducts'}">{l s={$displayName} mod='purchasesproducts'}
    </legend>

    <a class="btn success" href="{$moduleUrl}&page=purchasedetail">Comprar</a> / 
    <a class="btn success" href="{$moduleUrl}&page=loadinventory">Cargar Inventario</a>

    <br><br>
    
    <div id="grid"></div>
</fieldset>
    
<script type="text/javascript">
    var globalGridData = {$gridData};
</script>