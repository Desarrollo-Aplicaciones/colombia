<div class="checkout">
  <h3>Datos para generar tu factura</h3>
	<div class="grid">
    <div class="col-1-2">
      <div class="radio">
				<input id="radio-1" name="radio" type="radio" checked>
				<label for="radio-1" class="radio-label">Persona natural</label>
			</div>
    </div>
    <div class="col-1-2"> 
			<div class="radio">
				<input id="radio-2" name="radio" type="radio">
				<label  for="radio-2" class="radio-label">NIT</label>
			</div>
    </div>
	</div>

	<div class="grid">
    <div class="col-1-2">
			<div class="form-group">
				<label for="">Tipo de Documento:</label>
				<select class="form-control">
					<option value="" selected="selected" disabled>Selecciona</option>
					<option value="saab">Saab</option>
					<option value="mercedes">Mercedes</option>
					<option value="audi">Audi</option>
				</select>
    	</div>
    </div>

    <div class="col-1-2"> 
			<div class="form-group">
				<label for="">Número de Documento:</label>
				<input type="text" class="form-control" placeholder="">
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<label for="">Nombre:</label>
				<input type="text" class="form-control" placeholder="">
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<label for="">Apellido:</label>
				<input type="text" class="form-control" placeholder="">
			</div>
		</div>

		<div class="col-1-1">
			<div class="form-label">
				<label for="">Fecha de nacimiento:</label> 
			</div>
    </div>

		<div class="col-1-3">
			<div class="form-group">
					<select class="form-control">
						<option value="volvo">Día</option>
						<option value="saab">Saab</option>
					</select>
				</div>
		</div>

		<div class="col-1-3">
			<div class="form-group">
					<select class="form-control">
						<option value="volvo">Mes</option>
						<option value="saab">Saab</option>
					</select>
				</div>
		</div>

		<div class="col-1-3">
			<div class="form-group">
					<select class="form-control">
						<option value="volvo">Año</option>
						<option value="saab">Saab</option>
					</select>
				</div>
		</div>
	</div>

	<div class="grid">
    <div class="col-1-2">
      <div class="form-group">
				<label for="">NIT:</label>
				<input type="text" class="form-control" placeholder="">
			</div>
    </div>
    <div class="col-1-2"> 
			<div class="form-group">
				<label for="">Razón social:</label>
				<input type="text" class="form-control" placeholder="">
			</div>
    </div>
	</div>
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

