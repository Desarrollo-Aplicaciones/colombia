<div class="checkout w-4">
  <h3>Datos para generar tu factura</h3>
	<!-- .container-fluid -->
	<div class="container-fluid">
		<div class="row">
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="radio-horizontal" data-show="#natural-person">
					<div class="radio">
						<input id="radio-person" name="type_document" value="0" type="radio" required>
						<label for="radio-person" class="radio-label">Persona natural</label>
					</div>
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="radio-horizontal" data-show="#nit">
					<div class="radio">
						<input id="radio-nit" name="type_document" value="4" type="radio">
						<label for="radio-nit" class="radio-label">NIT</label>
					</div>
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->

	<!-- .container-fluid -->
	<div class="container-fluid" id="natural-person">
		<div class="row">
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="billing-document-type">Tipo de Documento:</label>
					<select class="form-control" id="billing-document-type" data-validate="true">
						<option value="" selected="selected" disabled>Selecciona</option>
            {foreach from=$document_types item=documentType}
            {if $documentType.document != "NIT"}
						<option value="{$documentType.id_document}" {if $datacustomer['id_type'] == $documentType.id_document }selected{/if}>{$documentType.document}</option>
            {/if}
            {/foreach}
					</select>
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="billing-document-number">Número de Documento:</label>
					<input type="text" class="form-control" name="number_document" id="billing-document-number" value="{$datacustomer['identification']}" data-validate="true" pattern="^[0-9]*$" title="Documento no válido, sólo números, no espacios">
				</div>	
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
		</div>
		<!-- /.row -->

		<div class="row">
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="billing-name">Nombre:</label>
					<input type="text" class="form-control" name="firstname" value="{$datacustomer['firstname']}" id="billing-name" placeholder="" data-validate="true">
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="billing-lastname">Apellido:</label>
					<input type="text" class="form-control" name="lastname" value="{$datacustomer['lastname']}" id="billing-lastname" placeholder="" data-validate="true" pattern="^[a-zA-ZñáéíóúüÑÁÉÍÓÚÜ\s]*$" title="Apellido no válido, sólo letras y espacios">
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
		</div>
		<!-- /.row -->

		<div class="row">
			<!-- .col-xs-12.col-sm-12 -->
			<div class="col-xs-12 col-sm-12">
				<div class="form-label">
					<label for="billing-birthdate">Fecha de nacimiento:</label>
					<input type="hidden" id="billing-birthdate" name="birthday" value="">
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-4 -->
		</div>
		<!-- /.row -->

		<div class="row">
			<!-- .col-xs-12.col-sm-4 -->
			<div class="col-xs-12 col-sm-4">
				<div class="form-group">
					<select class="form-control" id="birthdate-day" data-validate="true">
						<option value="">día: </option>
            			{foreach from=$days item=d}
							<option value="{$d}" {if ($sl_day == $d)}selected="selected"{/if}>{$d}</option>
					  	{/foreach}
					</select>
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-4 -->
			<!-- .col-xs-12.col-sm-4 -->
			<div class="col-xs-12 col-sm-4">
				<div class="form-group">
					<select class="form-control" id="birthdate-month" data-validate="true">
						<option value="">Mes: </option>
						{foreach from=$months key=k item=m}
							<option value="{$k}" {if ($sl_month == $m)}selected="selected"{/if}>{l s=$m}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-4 -->
			<!-- .col-xs-12.col-sm-4 -->
			<div class="col-xs-12 col-sm-4">
				<div class="form-group">
					<select class="form-control" id="birthdate-year" data-validate="true">
						<option value="">Año: </option>
						{foreach from=$years item=y}
							{if $y < (date('Y')-17) && $y > (date('Y')-100)}
								<option value="{$y}" {if ($sl_year == $y)}selected="selected"{/if}>{$y}</option>
							{/if}
						{/foreach}
					</select>
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-4 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->

	<!-- .container-fluid -->
	<div class="container-fluid" id="nit">
		<div class="row">
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="billing-nit">NIT:</label>
					<input type="text" class="form-control" id="billing-nit" name="number_document" placeholder="" data-validate="true">
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="billing-business-name">Razón social:</label>
					<input type="text" class="form-control" id="billing-business-name" name="firstname" value="{$datacustomer['firstname']}" placeholder="" data-validate="true">
					<input type="hidden" name="lastname" value=".">
				</div>
			</div>
			<!-- /.col-xs-12.col-sm-6 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /.checkout.w-4 --> 