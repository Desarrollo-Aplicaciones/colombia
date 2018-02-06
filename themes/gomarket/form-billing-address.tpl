<div class="checkout w-4">
  <h3>¿A <b>dónde</b> llevamos tu pedido?</h3>

	<!-- .container-fluid -->
  <div class="container-fluid">
    <div class="row">
      <!-- .col-xs-12.col-sm-6 -->
      <div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="shipping-state">Departamento:</label>
					<select class="form-control" name="id_state" id="shipping-state" required>
						<option value="" selected="selected" disabled>Selecciona</option>
            <optgroup label="Ciudad / Departamento">
              <option value="326" data-id-city="1184">Bogotá / Cundinamarca</option>
              <option value="342" data-id-city="1976">Cali / Valle del Cauca</option>
              <option value="314" data-id-city="1037">Medellín / Antioquia</option>
              <option value="316" data-id-city="1162">Barranquilla / Atlántico</option>
              <option value="339" data-id-city="1835">Bucaramanga / Santander</option>
            </optgroup>
            <optgroup label="Departamentos">
              {foreach from=$states item=state}
              <option value="{$state['id_state']}">{$state['name']}</option>
              {/foreach}
            </optgroup>
					</select>
				</div>
      </div>
      <!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
      <div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="shipping-city">Ciudad:</label>
          <input type="hidden" name="city" value="">
					<select class="form-control" name="id_city" id="shipping-city" disabled required>
						<option value="" selected="selected" disabled>Selecciona Departamento</option>
					</select>
				</div>
      </div>
      <!-- /.col-xs-12.col-sm-6 -->
    </div>
    <!-- /.row -->

		<div class="row">
      <!-- .col-xs-12 -->
      <div class="col-xs-12">
				<div class="form-group">
					<label for="shipping-address1">Dirección de Entrega:</label>
					<input type="text" class="form-control" name="address1" id="shipping-address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{/if}" required>
				</div>
      </div>
      <!-- /.col-xs-12 -->
    </div>
    <!-- /.row -->

		<div class="row">
      <!-- .col-xs-12 -->
      <div class="col-xs-12">
				<div class="form-group">
					<label for="shipping-address2">Barrio / Indicaciones <sup>(opcional)</sup>:</label>
					<input type="text" class="form-control" name="address2" id="shipping-address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{/if}">
				</div>
      </div>
      <!-- /.col-xs-12 -->
    </div>
    <!-- /.row -->

		<div class="row">
      <!-- .col-xs-12.col-sm-6 -->
      <div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="shipping-phone">Teléfono personal:</label>
					<input type="text" class="form-control" name="phone" id="shipping-phone" pattern="^[0-9]*$" title="Teléfono no válido, sólo números, no espacios" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" required>
				</div>
      </div>
      <!-- /.col-xs-12.col-sm-6 -->
			<!-- .col-xs-12.col-sm-6 -->
      <div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="shipping-phone-mobile">Otro teléfono <sup>(opcional)</sup>:</label>
					<input type="text" class="form-control" name="phone_mobile" id="shipping-phone-mobile" pattern="^[0-9]*$" title="Teléfono no válido, sólo números, no espacios" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{/if}">
				</div>
      </div>
      <!-- /.col-xs-12.col-sm-6 -->
    </div>
    <!-- /.row -->

		<div class="row">
      <!-- .col-xs-12 -->
      <div class="col-xs-12">
				<div class="form-group">
					<label for="shipping-alias">Nombre de la dirección:</label>
					<input type="text" class="form-control" name="alias" id="shipping-alias" placeholder="Ejm: Casa Mamá, Oficina, Amor..." value="{if isset($smarty.post.alias)}{$smarty.post.alias}{/if}" required>
				</div>
      </div>
      <!-- /.col-xs-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.checkout.w-4 --> 