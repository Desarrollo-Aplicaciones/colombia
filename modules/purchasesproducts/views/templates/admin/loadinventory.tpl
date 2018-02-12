<fieldset>
    <legend>
        <img src="{$module_template_dir}views/img/config.gif" alt="{l s={$displayName} mod='purchasesproducts'}">{l s={$displayName} mod='purchasesproducts'}
    </legend>

    <a class="btn success" href="{$moduleUrl}">Regresar</a>
    <br>

    <p>
    Seleccione el archivo en formato CSV delimetado por ; para realizar su cargue.
    </p>
    <form action="{$moduleUrl}&page=loadinventoryfield" method="post" name="frm_inventory" id="frm_inventory" enctype="multipart/form-data">
    	<div id="grid">
	    	<input type="file" name="fileField" id="fileField"> <input type="submit" name="btnUpload" id="btnUpload" value="Cargar">
	    </div>
    </form>

</fieldset>