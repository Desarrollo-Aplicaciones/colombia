<fieldset>
    <legend>
        <img src="{$module_template_dir}views/img/config.gif" alt="{l s={$displayName} mod='purchasesproducts'}">{l s={$displayName} mod='purchasesproducts'}
    </legend>

    <a class="btn success" href="{$moduleUrl}&page=purchasedetail">Comprar</a>

    <div id="grid"></div>
</fieldset>
    
<script type="text/javascript">
    var globalGridData = {$gridData};
</script>