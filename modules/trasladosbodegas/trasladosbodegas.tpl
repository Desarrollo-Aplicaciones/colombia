<div>
	<p><b>{l s='Traslado de inventario mediante ICR' mod='trasladosbodegas'}</b></p>
		<fieldset><legend><img src="{$module_template_dir}icon/config.gif" alt="" title="" />{l s='Traslado entre bodegas' mod='trasladosbodegas'}</legend>
			<p>Ingresar el código ICR a trasladar.</p>
			<div id="result"></div>
			<table>
				<tr><td><p>Bodega de Origen:</p></td><td>
					<SELECT name="origin_warehouse" id="origin_warehouse">
						{foreach key=key item=item from=$bodega}
						<option value="{$key}">{$item}</option>
						{/foreach}
					</SELECT>
				</td></tr>
				<tr>
					<td><p>ICR:</p></td>
					<td><input type="text" name="icr" id="icr" placeholder="ICR" />
					&nbsp;<img src="{$module_template_dir}icon/validate.png" id="validar"></td>
				</tr>
				<tr><td colspan="2">
					<form enctype="multipart/form-data" method="post">
					<table id="tabla" width="100%">
						<thead>
							<tr><th></th><th>ICR</th><th>Bodega de Origen</th></tr>
						</thead>
						<tbody>
							<tr style="display:none;"><th class="remove"><td class="icr">ICR</td><td class="origen">Bodega de Origen</td></tr>
						</tbody>
					</table>
					<table width="100%">
						<tr><td><p>Bodega de Destino:</p></td><td>
							<SELECT name="destination_warehouse" id="destination_warehouse">
								{foreach key=key item=item from=$bodega}
									<option value="{$key}">{$item}</option>
								{/foreach}
								<option value="todas">Todas las bodegas</option>
							</SELECT>
						</td></tr>
						<tr><td colspan="2"><center>
							<input type="submit" name="submitICR" value="Trasladar" class="button" />
							</center></td>
						</tr>
						<tr>
							<td colspan="2">
							<input type="submit" name="export" value="Generar Reporte por bodega" class="button" />
							<input type="submit" name="exportDetail" value="Generar Reporte por ICR" class="button" />
							</td>
						</tr>
					</table>
					</form>
				</td></tr>
			</table>
		</fieldset>
</div>
<script>
	function estilo(result){
		$('#result').removeAttr("style")
		if (result == 'error'){
			$('#result').attr("class" , "module_error alert error");
		}
		if (result == 'agregar'){
			$('#result').attr("class" , "module_confirmation conf confirm");
		}
	}
	function validarICR(){
		origen = $('#origin_warehouse').val();
		icr = $('#icr').val();
		$.ajax({
		type: "post",
		url: "{$module_dir}warehousetransfer.php",
		data: {
			"validar":1,
			"origin_warehouse": origen,
			"icr": icr
		},
			success: function(response){
				var json = $.parseJSON(response);
				if(json.error){
					estilo('error');
					$('#result').html(json.error);
				}
				else {
					if(json.cod_icr && !($('#'+json.id_icr).length)){
						estilo('agregar');
						$('#result').html('Producto Agregado');
						$("#tabla tbody tr:eq(0)").clone().removeAttr('style').attr('id', json.id_icr).appendTo("#tabla tbody");
						$('#'+json.id_icr+' .remove').html('<img src="{$module_template_dir}icon/remove.png" onClick="$('+("'#"+json.id_icr+"'")+').remove();" />')
						$('#'+json.id_icr+' .icr').html(json.cod_icr+'<input type="hidden" name="icr_'+json.id_icr+'" value="'+json.id_icr+'" />');
						$('#'+json.id_icr+' .origen').html(json.name+'<input type="hidden" name="origin_'+json.id_icr+'" value="'+json.id_warehouse+'" />');
						$('#origin_warehouse').attr('disabled', 'true');
						$('#icr').val('');
					}
					else{
						estilo('error');
						$('#result').html('El Producto ya fue seleccionado');
					}
				}
			}
		});
	}
	function generarReporte(bodega){
		$.ajax({
		type: "post",
		url: "{$module_dir}warehousetransfer.php",
		data: {
			"generar":1,
			"warehouse": bodega
		},
			success: function(response){
				var json = $.parseJSON(response);
				console.log(json);
			}
		});
	}
	$('#validar').click(function (){
		validarICR();
	});
	$( "#icr" ).on( "keydown", function(event) {
		if(event.which == 13)
			validarICR();
	});
	$('#generar').click(function (){
		generarReporte($('#destination_warehouse').val());
	});
</script>