<div class="checkout w-4">
  <h3>Datos para generar tu factura</h3>
	<div class="grid" id="document-type">
    <div class="col-1-2">
			<div class="radio-horizontal" data-show="#natural-person">
				<div class="radio">
					<input id="radio-1" name="radio" type="radio">
					<label for="radio-1" class="radio-label">Persona natural</label>
				</div>
			</div>
    </div>
    <div class="col-1-2"> 
			<div class="radio-horizontal" data-show="#nit">
				<div class="radio">
					<input id="radio-2" name="radio" type="radio">
					<label  for="radio-2" class="radio-label">NIT</label>
				</div>
			</div>
    </div>
	</div>

	<div class="grid ctn-document-type" id="natural-person">
    <div class="col-1-2">
			<div class="form-group">
				<label for="billing-document-type">Tipo de Documento:</label>
				<select class="form-control" id="billing-document-type">
					<option value="" selected="selected" disabled>Selecciona</option>
					<option value="saab">Saab</option>
					<option value="mercedes">Mercedes</option>
					<option value="audi">Audi</option>
				</select>
    	</div>
    </div>

    <div class="col-1-2"> 
			<div class="form-group">
				<label for="billing-document-number">Número de Documento:</label>
				<input type="text" class="form-control" id="billing-document-number" placeholder="">
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<label for="billing-name">Nombre:</label>
				<input type="text" class="form-control" id="billing-name" placeholder="">
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<label for="billing-lastname">Apellido:</label>
				<input type="text" class="form-control" id="billing-lastname" placeholder="">
			</div>
		</div>

		<div class="col-1-1">
			<div class="form-label">
				<label for="billing-birthdate">Fecha de nacimiento:</label>
				<input type="hidden" id="billing-birthdate" value="">
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

	<div class="grid ctn-document-type" id="nit">
    <div class="col-1-2">
      <div class="form-group">
				<label for="billing-nit">NIT:</label>
				<input type="text" class="form-control" id="billing-nit" placeholder="">
			</div>
    </div>
    <div class="col-1-2"> 
			<div class="form-group">
				<label for="billing-business-name">Razón social:</label>
				<input type="text" class="form-control" id="billing-business-name" placeholder="">
			</div>
    </div>
	</div>
</div>

<hr class="checkout-line">

<div class="checkout w-4">
  <h3>¿A <b>dónde</b> llevamos tu pedido?</h3>
	
	<div class="grid">
    <div class="col-1-2">
      <div class="form-group">
				<label for="shipping-state">Departamento:</label>
				<select class="form-control" id="shipping-state">
					<option value="" selected="selected" disabled>Selecciona</option>
					<option value="volvo">Año</option>
					<option value="saab">Saab</option>
				</select>
			</div>
    </div>

    <div class="col-1-2"> 
			<div class="form-group">
				<label for="shipping-city">Ciudad:</label>	
				<select class="form-control" id="shipping-city">
					<option value="" selected="selected" disabled>Selecciona</option>
					<option value="volvo">Año</option>
					<option value="saab">Saab</option>
				</select>
			</div>
    </div>

		<div class="col-1-1">
			<div class="form-group">
				<label for="shipping-address1">Dirección de Entrega:</label>
				<input type="text" class="form-control" id="shipping-address1" placeholder="">
			</div>
		</div>

		<div class="col-1-1">
			<div class="form-group">
				<label for="shipping-address2">Barrio / Indicaciones <sup>(opcional)</sup>:</label>
				<input type="text" class="form-control" id="shipping-address2" placeholder="">
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<label for="shipping-phone">Teléfono personal:</label>
				<input type="text" class="form-control" id="shipping-phone" placeholder="">
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<label for="shipping-phone-mobile">Otro teléfono <sup>(opcional)</sup>:</label>
				<input type="text" class="form-control" id="shipping-phone-mobile" placeholder="">
			</div>
		</div>

		<div class="col-1-1">
			<div class="form-group">
				<label for="shipping-alias">Nombre de la dirección:</label>
				<input type="text" class="form-control" id="shipping-alias" placeholder="Ejm: Casa Mamá, Oficina, Amor...">
			</div>
		</div>

	</div>	
</div>

<div class="checkout w-4">
	<div class="grid mt-0">
		<div class="col-1-2"> 
			<div class="form-group">
				<button type="button" class="btn btn-block btn-outline-secondary">Regresar</button>
			</div>
		</div>

		<div class="col-1-2"> 
			<div class="form-group">
				<button type="button" class="btn btn-block btn-primary">Continuar</button>
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

