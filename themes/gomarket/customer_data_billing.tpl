<div class="horizontal-radio-buttons">
	<ul>
		<li>
			<input type="radio" id="f-option" name="documentType" value="CC">
			<label for="f-option">Persona Natural</label>
	
			<div class="check"></div>
		</li>

		<li>
			<input type="radio" id="s-option" name="documentType" value="NIT">
			<label for="s-option">NIT</label>
	
			<div class="check"></div>
		</li>
	</ul>
</div>

<div id="container_data_billing" class="">
	<div class="titulo" id="title_data_billing">
		Datos para generar tu factura
	</div>

	<div id="div_data_billing" >
		<div id="field_type_document_customer" class="campoCorto">
			<div class="etiqueta" id="label-type_document_customer"><label>Tipo de documento<span class="purpura">*</span>:<br /></label>
				<select id="txt_type_document_customer" name="txt_type_document_customer" class="seleccion">
					<option value="" selected="selected" disabled>Tipo documento*</option>
					{foreach from=$document_types item=tipo_documento}
						<option value="{$tipo_documento.id_document}" {if $datacustomer['id_type'] == $tipo_documento.id_document }selected{/if}>{$tipo_documento.document}</option>
					{/foreach}
				</select>
			</div> 
		</div>

		<div id="field_number_document_customer" class="campoLargo">
			<div class="etiqueta" id="label-number_document_customer"><label>Numero de documento<span class="purpura">*</span>:<br /></label>
				<input type="text" value="{$datacustomer['identification']}" id="txt_number_document_customer" name="txt_number_document_customer" placeholder="Número de documento*"/><br />
			</div> 
		</div>

		<div id="field_firstname_customer" class="campoLargo">
			<div class="etiqueta" id="label-firstname_customer"><label>Nombre<span class="purpura">*</span>:<br /></label>
				<input type="text" id="txt_firstname_customer" name="txt_firstname_customer" value="{$datacustomer['firstname']}" placeholder="Nombre*"/>
			</div> 
		</div>

		<div id="field_lastname_customer" class="campoLargo">
			<div class="etiqueta" id="label-lastname_customer"><label>Apellido<span class="purpura">*</span>:<br /></label>
				<input type="text" value="{$datacustomer['lastname']}" id="txt_lastname_customer" name="txt_lastname_customer" placeholder="Apellido*"/>
			</div> 
		</div>
	</div>
</div>

