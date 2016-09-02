<?php /* Smarty version Smarty-3.1.14, created on 2014-04-13 08:48:06
         compiled from "/var/www/themes/gomarket/authentication.tpl" */ ?>
<?php /*%%SmartyHeaderCode:260288455534a9596d4aa01-29129978%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7900c41f1d458218cf61a4976e2e038122bbf651' => 
    array (
      0 => '/var/www/themes/gomarket/authentication.tpl',
      1 => 1397062678,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '260288455534a9596d4aa01-29129978',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'email_create' => 0,
    'link' => 0,
    'navigationPipe' => 0,
    'countries' => 0,
    'country' => 0,
    'state' => 0,
    'address' => 0,
    'img_dir' => 0,
    'back' => 0,
    'GUEST_FORM_ENABLED' => 0,
    'comprarapida' => 0,
    'HOOK_CREATE_ACCOUNT_TOP' => 0,
    'genders' => 0,
    'gender' => 0,
    'days' => 0,
    'day' => 0,
    'sl_day' => 0,
    'months' => 0,
    'k' => 0,
    'sl_month' => 0,
    'month' => 0,
    'years' => 0,
    'year' => 0,
    'sl_year' => 0,
    'PS_REGISTRATION_PROCESS_TYPE' => 0,
    'dlv_all_fields' => 0,
    'field_name' => 0,
    'b2b_enable' => 0,
    'v' => 0,
    'sl_country' => 0,
    'postCodeExist' => 0,
    'stateExist' => 0,
    'one_phone_at_least' => 0,
    'HOOK_CREATE_ACCOUNT_FORM' => 0,
    'newsletter' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534a9597180796_74710396',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534a9597180796_74710396')) {function content_534a9597180796_74710396($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?><?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>
	<?php if (!isset($_smarty_tpl->tpl_vars['email_create']->value)){?><?php echo smartyTranslate(array('s'=>'Ingreso al Sistema'),$_smarty_tpl);?>
<?php }else{ ?>
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('authentication',true), ENT_QUOTES, 'UTF-8', true);?>
" rel="nofollow" title="<?php echo smartyTranslate(array('s'=>'Ingreso al Sistema'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Ingreso al Sistema'),$_smarty_tpl);?>
</a>
		<span class="navigation-pipe"><?php echo $_smarty_tpl->tpl_vars['navigationPipe']->value;?>
</span><?php echo smartyTranslate(array('s'=>'Create your account'),$_smarty_tpl);?>

	<?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<script type="text/javascript">
// <![CDATA[
var idSelectedCountry = <?php if (isset($_POST['id_state'])){?><?php echo intval($_POST['id_state']);?>
<?php }else{ ?>false<?php }?>;
var countries = new Array();
var countriesNeedIDNumber = new Array();
var countriesNeedZipCode = new Array(); 
<?php if (isset($_smarty_tpl->tpl_vars['countries']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countries']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value){
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
		<?php if (isset($_smarty_tpl->tpl_vars['country']->value['states'])&&$_smarty_tpl->tpl_vars['country']->value['contains_states']){?>
			countries[<?php echo intval($_smarty_tpl->tpl_vars['country']->value['id_country']);?>
] = new Array();
			<?php  $_smarty_tpl->tpl_vars['state'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['state']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['country']->value['states']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['state']->key => $_smarty_tpl->tpl_vars['state']->value){
$_smarty_tpl->tpl_vars['state']->_loop = true;
?>
				countries[<?php echo intval($_smarty_tpl->tpl_vars['country']->value['id_country']);?>
].push({'id' : '<?php echo intval($_smarty_tpl->tpl_vars['state']->value['id_state']);?>
', 'name' : '<?php echo addslashes($_smarty_tpl->tpl_vars['state']->value['name']);?>
'});
			<?php } ?>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['country']->value['need_identification_number']){?>
			countriesNeedIDNumber.push(<?php echo intval($_smarty_tpl->tpl_vars['country']->value['id_country']);?>
);
		<?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['country']->value['need_zip_code'])){?>
			countriesNeedZipCode[<?php echo intval($_smarty_tpl->tpl_vars['country']->value['id_country']);?>
] = <?php echo $_smarty_tpl->tpl_vars['country']->value['need_zip_code'];?>
;
		<?php }?>
	<?php } ?>
<?php }?>
$(function(){
	$('.id_state option[value=<?php if (isset($_POST['id_state'])){?><?php echo intval($_POST['id_state']);?>
<?php }else{ ?><?php if (isset($_smarty_tpl->tpl_vars['address']->value)){?><?php echo intval($_smarty_tpl->tpl_vars['address']->value->id_state);?>
<?php }?><?php }?>]').attr('selected', true);
});
//]]>
	
	$(document).ready(function() {
	$('#company').on('input',function(){
			vat_number();
		});
		vat_number();
		function vat_number()
		{
			if ($('#company').val() != '')
				$('#vat_number').show();
			else
				$('#vat_number').hide();
		}
	});
	
</script>
<script type="text/javascript">
	
	$(document).ready(function(){
		// Retrocompatibility with 1.4
		if (typeof baseUri === "undefined" && typeof baseDir !== "undefined")
		baseUri = baseDir;
		$('#create-account_form').submit(function(){
			submitFunction();
			return false;
		});
	});
	function submitFunction()
	{
		$('#create_account_error').html('').hide();
		//send the ajax request to the server
		$.ajax({
			type: 'POST',
			url: baseUri,
			async: true,
			cache: false,
			dataType : "json",
			data: {
				controller: 'authentication',
				SubmitCreate: 1,
				ajax: true,
				email_create: $('#email_create').val(),
				back: $('input[name=back]').val(),
				token: token
			},
			success: function(jsonData)
			{
				if (jsonData.hasError)
				{
					var errors = '';
					for(error in jsonData.errors)
						//IE6 bug fix
						if(error != 'indexOf')
							errors += '<li>'+jsonData.errors[error]+'</li>';
					$('#create_account_error').html('<ol>'+errors+'</ol>').show();
				}
				else
				{
					// adding a div to display a transition
					$('#center_column').html('<div id="noSlide">'+$('#center_column').html()+'</div>');
					$('#noSlide').fadeOut('slow', function(){
						$('#noSlide').html(jsonData.page);
						// update the state (when this file is called from AJAX you still need to update the state)
						bindStateInputAndUpdate();
						$(this).fadeIn('slow', function(){
					document.location = '#account-creation';
						});
					});
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				alert("TECHNICAL ERROR: unable to load form.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	}
	
</script>
<div id="encabezados" >
	<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/encabezados.png" style="width:100%;display:block; margin: auto;padding: 10px 0px;"/>
</div>
<?php if (!isset($_smarty_tpl->tpl_vars['back']->value)||$_smarty_tpl->tpl_vars['back']->value!='my-account'){?><?php $_smarty_tpl->tpl_vars['current_step'] = new Smarty_variable('login', null, 0);?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./order-steps.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }?> 
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php $_smarty_tpl->tpl_vars['stateExist'] = new Smarty_variable(false, null, 0);?>
<?php $_smarty_tpl->tpl_vars["postCodeExist"] = new Smarty_variable(false, null, 0);?>
<?php if (!isset($_smarty_tpl->tpl_vars['email_create']->value)){?>
<div style="overflow: hidden; width: 100%; margin-left: auto; margin-right: auto;">
<div class="contenedor" id="primerHole">
<div style="display: inline-block; width:100%;">
<span class="titulo">¿Ya está inscrito?</span>
<samp class="titulo">Compra rapida</samp>
<span class="obliga">(*) Campos Obligatorios</span>
</div>
<div class="form-logueo" id="registrado">
<form action="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('authentication',true);?>
" method="post" id="login_form">
<fieldset>
<label class="etiqueta" style="font-size:8pt;">E-mail*:</label><br/>
<input type="text" id="email" name="email" value="<?php if (isset($_POST['email'])){?><?php echo stripslashes($_POST['email']);?>
<?php }?>" /><br>
<label class="etiqueta" style="font-size:8pt;">Contraseña*:</label><br/>
<input type="password" id="passwd" name="passwd" value="<?php if (isset($_POST['passwd'])){?><?php echo stripslashes($_POST['passwd']);?>
<?php }?>" />
<?php if (isset($_smarty_tpl->tpl_vars['back']->value)){?><input type="hidden" class="hidden" name="back" value="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['back']->value, 'htmlall', 'UTF-8');?>
" /><?php }?>
<div style="display: inline-block; width:100%; margin-top: 27px;margin-left: -59px;">
<div style="display:table-cell;float:left;margin-left: 60px;"><input type="submit" id="SubmitLogin" name="SubmitLogin" value="Ingresar" ></div>
<div style="display:table-cell;float:right;margin-top: 10px;"><a class="olvida" style="font-size: 9pt;" href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('password');?>
"><?php echo smartyTranslate(array('s'=>'Forgot your password?'),$_smarty_tpl);?>
</a></div>
</div>
</fieldset>
</form>
</div>
</div>


<!-- tercer cuadro-->
<div class="contenedor" id="tercerHole">
<div style="display: inline-block; width: 100%; height: 133px;">
<div style="display:table-row; width: 100%">
<div style="display: table-cell;width:50%;"><span class="titulo">Seguridad</span></div>
<div style="display: table-cell;width:50%;margin-left: 22px;"><span class="titulo">Nuestros Medios de pago</span></div>
</div>
<div style="display:table-row; width: 100%;">
<div style="display:table-cell; width: 50%;">
<ul>
<li style="height: 54px;"><div><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/g644.png" id="seguridadImagen"/></div></li>
</ul>
<div style="position:relative; top: -90px; left: 35px; ">
<div ALIGN=center id="seguridadTitulo">Realiza tu compra con tranquilidad, contamos con certificación de seguridad.</div>
<div style="display:block; font-size:7pt; width: 120px; height: 27px;display:none;">* <b>Absoluta</b> discreción</div>
<div style="display:block; font-size:7pt; width: 120px; height: 35px;display:none;">* Mejor precio <span style="color:#b7689e">Garantizado*</span></div>
</div>
</div>
<div style="display:table-cell; width: 22%;">
<p>
<div style="display:inline-block;width: 20%;margin-left: 6px;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/amex.png" style="display:block; margin: auto;"/></div>
<div style="display:inline-block;width: 26%;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/visa.png" style="display:block; margin: auto;"/></div>
<div style="display:inline-block;width: 22%;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/master.png" style="display:block; margin: auto;"/></div>
<div style="display:inline-block;width: 22%;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/diners.png" style="display:block; margin: auto;"/></div>
</p>
<p>
<div style="display:inline-block; width: 33%;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/pse.png" style="display:block; margin-bottom: 7px; margin-left: auto; margin-right: auto;"/></div>
<div style="display:inline-block; width: 33%;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/baloto.png" style="display:block; margin: auto; "/></div>
<div style="display:inline-block; width: 33%;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
authentication/cod.png" style="display:block; margin: auto;"/></div>
</p>
</div>
</div>
</div>
</div>



<div class="contenedor" id="segundoHole">
<div style="display: block; width:100%;">
                    <?php if (isset($_smarty_tpl->tpl_vars['GUEST_FORM_ENABLED']->value)&&$_smarty_tpl->tpl_vars['GUEST_FORM_ENABLED']->value){?>  
                          <span class="titulo"><input type="radio" id="opregistrado" name="opcompra" checked="checked">Crear una cuenta</span>
                         <samp class="titulo"><input type="radio" id="opinvitado" name="opcompra"   <?php if (isset($_smarty_tpl->tpl_vars['comprarapida']->value)){?>  checked="checked"  <?php }?>>Compra rapida</samp>
                      <?php }else{ ?>
                          <span class="titulo">Crear una cuenta</span>
                      <?php }?>
<span class="obliga">(*) Campos Obligatorios</span>
</div>
<div id="create_acount">
<form action="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('authentication',true);?>
" method="post" id="account-creation_form">
<?php echo $_smarty_tpl->tpl_vars['HOOK_CREATE_ACCOUNT_TOP']->value;?>

<fieldset>
<div>
<div style="diplay:table-cell; float:left; width: 25%;">
<p style="font-size:8pt;" id="label-gender"><?php echo smartyTranslate(array('s'=>'Sexo*:'),$_smarty_tpl);?>
</p>
<?php  $_smarty_tpl->tpl_vars['gender'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['gender']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['genders']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['gender']->key => $_smarty_tpl->tpl_vars['gender']->value){
$_smarty_tpl->tpl_vars['gender']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['gender']->key;
?>
<input type="radio" style="font-size:8pt;" name="id_gender" id="id_gender<?php echo $_smarty_tpl->tpl_vars['gender']->value->id;?>
" value="<?php echo $_smarty_tpl->tpl_vars['gender']->value->id;?>
" <?php if (isset($_POST['id_gender'])&&$_POST['id_gender']==$_smarty_tpl->tpl_vars['gender']->value->id){?>checked="checked"<?php }?> />
<label style="font-size:8pt;" for="id_gender<?php echo $_smarty_tpl->tpl_vars['gender']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['gender']->value->name;?>
</label>
<?php } ?>
</div>
<div style="display:table-cell; float:left; width: 74%;">
<p style="font-size:8pt;" id="label-email"><?php echo smartyTranslate(array('s'=>'E-mail'),$_smarty_tpl);?>
*:</p>
<input type="text" style="width: 92%;" id="reg-email" name="email" value="<?php if (isset($_POST['email'])){?><?php echo $_POST['email'];?>
<?php }?>" />
</div>
</div>
<div>
<div style="diplay:table-cell; float:left; width: 48%;">
<p style="font-size:8pt;" id="label-passwd"><?php echo smartyTranslate(array('s'=>'Password'),$_smarty_tpl);?>
*:</p>
<input type="password" style="width: 90%;" name="passwd" id="passwd" selector="clave"/>
</div>
<div style="display:table-cell; float:left; width: 48%; ">
<p style="font-size:8pt;margin-left: 10px;" id="label-conf"><?php echo smartyTranslate(array('s'=>'Confirma Contraseña*:'),$_smarty_tpl);?>
</p>
<input type="password" style="width: 90%; float:right;" name="conf-passwd" id="conf-passwd" selector="confirma"/>
</div>
</div>
<div>
<div style="diplay:table-cell; float:left; width: 48%; ">
<p style="font-size:8pt;" id="label-first"><?php echo smartyTranslate(array('s'=>'First name'),$_smarty_tpl);?>
*:</p>
<input onkeyup="$('#firstname').val(this.value);" style="width: 90%;" type="text" id="customer_firstname" name="customer_firstname" value="<?php if (isset($_POST['customer_firstname'])){?><?php echo $_POST['customer_firstname'];?>
<?php }?>" />
</div>
<div style="display:table-cell; float:left; width: 48%; ">
<p style="font-size:8pt;margin-left: 10px;" id="label-last"><?php echo smartyTranslate(array('s'=>'Last name'),$_smarty_tpl);?>
*:</p>
<input onkeyup="$('#lastname').val(this.value);" style="width: 90%; float:right;" type="text" id="customer_lastname" name="customer_lastname" value="<?php if (isset($_POST['customer_lastname'])){?><?php echo $_POST['customer_lastname'];?>
<?php }?>" />
</div>
</div>
<div>
<div style="diplay:table-cell; float:left; width: 48%; ">
<p style="font-size:8pt;" id="label-id"><?php echo smartyTranslate(array('s'=>'Tipo documento*:'),$_smarty_tpl);?>
</p>
<select id="id" name="id" style="width: 90%;">
<option value="">-</option>
<option value="1">CC</option>
<option value="2">CE</option>
<option value="3">Pasaporte</option>
</select>
</div>
<div style="display:table-cell; float:left; width: 48%; ">
<p style="font-size:8pt; margin-left: 10px;" id="label-dni"><?php echo smartyTranslate(array('s'=>'Identificacion*:'),$_smarty_tpl);?>
</p>
<input type="text" style="width: 90%; float:right;" id="dni" name="dni" />
</div>
</div>
<div>
<div style="diplay:table-cell; float:left; width: 100%; ">
<p style="font-size:8pt;" id="label-birth"><?php echo smartyTranslate(array('s'=>'Date of Birth'),$_smarty_tpl);?>
*:</p>
<div>
<select id="days" name="days" >
<option value="">-</option>
<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['days']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value){
$_smarty_tpl->tpl_vars['day']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['day']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['sl_day']->value==$_smarty_tpl->tpl_vars['day']->value)){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['day']->value;?>
&nbsp;&nbsp;</option>
<?php } ?>
</select>
<select id="months" name="months" style="width:100px;">
<option value="">-</option>
<?php  $_smarty_tpl->tpl_vars['month'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['month']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['months']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['month']->key => $_smarty_tpl->tpl_vars['month']->value){
$_smarty_tpl->tpl_vars['month']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['month']->key;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['sl_month']->value==$_smarty_tpl->tpl_vars['k']->value)){?> selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>$_smarty_tpl->tpl_vars['month']->value),$_smarty_tpl);?>
&nbsp;</option>
<?php } ?>
</select>
<select id="years" name="years">
<option value="">-</option>
<?php  $_smarty_tpl->tpl_vars['year'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['year']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['years']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['year']->key => $_smarty_tpl->tpl_vars['year']->value){
$_smarty_tpl->tpl_vars['year']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['sl_year']->value==$_smarty_tpl->tpl_vars['year']->value)){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['year']->value;?>
&nbsp;&nbsp;</option>
<?php } ?>
</select>
<div>
</div>
</div>
</fieldset>
<?php if (isset($_smarty_tpl->tpl_vars['PS_REGISTRATION_PROCESS_TYPE']->value)&&$_smarty_tpl->tpl_vars['PS_REGISTRATION_PROCESS_TYPE']->value){?>
<fieldset class="account_creation">
<h3><?php echo smartyTranslate(array('s'=>'Your address'),$_smarty_tpl);?>
</h3>
<?php  $_smarty_tpl->tpl_vars['field_name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field_name']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dlv_all_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field_name']->key => $_smarty_tpl->tpl_vars['field_name']->value){
$_smarty_tpl->tpl_vars['field_name']->_loop = true;
?>
<?php if ($_smarty_tpl->tpl_vars['field_name']->value=="company"){?>
<?php if (!$_smarty_tpl->tpl_vars['b2b_enable']->value){?>
<p class="text">
<label for="company"><?php echo smartyTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</label>
<input type="text" class="text" id="company" name="company" value="<?php if (isset($_POST['company'])){?><?php echo $_POST['company'];?>
<?php }?>" />
</p>
<?php }?>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="vat_number"){?>
<div id="vat_number" style="display:none;">
<p class="text">
<label for="vat_number"><?php echo smartyTranslate(array('s'=>'VAT number'),$_smarty_tpl);?>
</label>
<input type="text" class="text" name="vat_number" value="<?php if (isset($_POST['vat_number'])){?><?php echo $_POST['vat_number'];?>
<?php }?>" />
</p>
</div>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="firstname"){?>
<p class="required text">
<label for="firstname"><?php echo smartyTranslate(array('s'=>'First name'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" id="firstname" name="firstname" value="<?php if (isset($_POST['firstname'])){?><?php echo $_POST['firstname'];?>
<?php }?>" />
</p>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="lastname"){?>
<p class="required text">
<label for="lastname"><?php echo smartyTranslate(array('s'=>'Last name'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" id="lastname" name="lastname" value="<?php if (isset($_POST['lastname'])){?><?php echo $_POST['lastname'];?>
<?php }?>" />
</p>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="address1"){?>
<p class="required text">
<label for="address1"><?php echo smartyTranslate(array('s'=>'Address'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" name="address1" id="address1" value="<?php if (isset($_POST['address1'])){?><?php echo $_POST['address1'];?>
<?php }?>" />
<span class="inline-infos"><?php echo smartyTranslate(array('s'=>'Street address, P.O. Box, Company name, etc.'),$_smarty_tpl);?>
</span>
</p>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="address2"){?>
<p class="text">
<label for="address2"><?php echo smartyTranslate(array('s'=>'Address (Line 2)'),$_smarty_tpl);?>
</label>
<input type="text" class="text" name="address2" id="address2" value="<?php if (isset($_POST['address2'])){?><?php echo $_POST['address2'];?>
<?php }?>" />
<span class="inline-infos"><?php echo smartyTranslate(array('s'=>'Apartment, suite, unit, building, floor, etc...'),$_smarty_tpl);?>
</span>
</p>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="postcode"){?>
<?php $_smarty_tpl->tpl_vars['postCodeExist'] = new Smarty_variable(true, null, 0);?>
<p class="required postcode text">
<label for="postcode"><?php echo smartyTranslate(array('s'=>'Zip / Postal Code'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" name="postcode" id="postcode" value="<?php if (isset($_POST['postcode'])){?><?php echo $_POST['postcode'];?>
<?php }?>" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
</p>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="city"){?>
<p class="required text">
<label for="city"><?php echo smartyTranslate(array('s'=>'City'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" name="city" id="city" value="<?php if (isset($_POST['city'])){?><?php echo $_POST['city'];?>
<?php }?>" />
</p>
<!--
if customer hasn't update his layout address, country has to be verified
but it's deprecated
-->
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="Country:name"||$_smarty_tpl->tpl_vars['field_name']->value=="country"){?>
<p class="required select">
<label for="id_country"><?php echo smartyTranslate(array('s'=>'Country'),$_smarty_tpl);?>
 <sup>*</sup></label>
<select name="id_country" id="id_country">
<option value="">-</option>
<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countries']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id_country'];?>
" <?php if (($_smarty_tpl->tpl_vars['sl_country']->value==$_smarty_tpl->tpl_vars['v']->value['id_country'])){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
<?php } ?>
</select>
</p>
<?php }elseif($_smarty_tpl->tpl_vars['field_name']->value=="State:name"||$_smarty_tpl->tpl_vars['field_name']->value=='state'){?>
<?php $_smarty_tpl->tpl_vars['stateExist'] = new Smarty_variable(true, null, 0);?>
<p class="required id_state select">
<label for="id_state"><?php echo smartyTranslate(array('s'=>'State'),$_smarty_tpl);?>
 <sup>*</sup></label>
<select name="id_state" id="id_state">
<option value="">-</option>
</select>
</p>
<?php }?>
<?php } ?>
<?php if ($_smarty_tpl->tpl_vars['postCodeExist']->value==false){?>
<p class="required postcode text hidden">
<label for="postcode"><?php echo smartyTranslate(array('s'=>'Zip / Postal Code'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" name="postcode" id="postcode" value="<?php if (isset($_POST['postcode'])){?><?php echo $_POST['postcode'];?>
<?php }?>" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
</p>
<?php }?>	
<?php if ($_smarty_tpl->tpl_vars['stateExist']->value==false){?>
<p class="required id_state select hidden">
<label for="id_state"><?php echo smartyTranslate(array('s'=>'State'),$_smarty_tpl);?>
 <sup>*</sup></label>
<select name="id_state" id="id_state">
<option value="">-</option>
</select>
</p>
<?php }?>
<p class="textarea">
<label for="other"><?php echo smartyTranslate(array('s'=>'Additional information'),$_smarty_tpl);?>
</label>
<textarea name="other" id="other" cols="26" rows="3"><?php if (isset($_POST['other'])){?><?php echo $_POST['other'];?>
<?php }?></textarea>
</p>
<?php if (isset($_smarty_tpl->tpl_vars['one_phone_at_least']->value)&&$_smarty_tpl->tpl_vars['one_phone_at_least']->value){?>
<p class="inline-infos"><?php echo smartyTranslate(array('s'=>'You must register at least one phone number.'),$_smarty_tpl);?>
</p>
<?php }?>
<p class="text">
<label for="phone"><?php echo smartyTranslate(array('s'=>'Home phone'),$_smarty_tpl);?>
</label>
<input type="text" class="text" name="phone" id="phone" value="<?php if (isset($_POST['phone'])){?><?php echo $_POST['phone'];?>
<?php }?>" />
</p>
<p class="<?php if (isset($_smarty_tpl->tpl_vars['one_phone_at_least']->value)&&$_smarty_tpl->tpl_vars['one_phone_at_least']->value){?>required <?php }?> text">
<label for="phone_mobile"><?php echo smartyTranslate(array('s'=>'Mobile phone'),$_smarty_tpl);?>
<?php if (isset($_smarty_tpl->tpl_vars['one_phone_at_least']->value)&&$_smarty_tpl->tpl_vars['one_phone_at_least']->value){?> <sup>*</sup><?php }?></label>
<input type="text" class="text" name="phone_mobile" id="phone_mobile" value="<?php if (isset($_POST['phone_mobile'])){?><?php echo $_POST['phone_mobile'];?>
<?php }?>" />
</p>
<p class="required text" id="address_alias">
<label for="alias"><?php echo smartyTranslate(array('s'=>'Assign an address alias for future reference.'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" name="alias" id="alias" value="<?php if (isset($_POST['alias'])){?><?php echo $_POST['alias'];?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'My address'),$_smarty_tpl);?>
<?php }?>" />
</p>
</fieldset>
<fieldset class="account_creation dni">
<h3><?php echo smartyTranslate(array('s'=>'Tax identification'),$_smarty_tpl);?>
</h3>
<p class="required text">
<label for="dni"><?php echo smartyTranslate(array('s'=>'Identification number'),$_smarty_tpl);?>
 <sup>*</sup></label>
<input type="text" class="text" name="dni" id="dni" value="<?php if (isset($_POST['dni'])){?><?php echo $_POST['dni'];?>
<?php }?>" />
<span class="form_info"><?php echo smartyTranslate(array('s'=>'DNI / NIF / NIE'),$_smarty_tpl);?>
</span>
</p>
</fieldset>
<?php }?>
<?php echo $_smarty_tpl->tpl_vars['HOOK_CREATE_ACCOUNT_FORM']->value;?>

<?php if ($_smarty_tpl->tpl_vars['newsletter']->value){?>
<div style="margin-top: 30px;">
<div style="diplay:table-cell; float:left; width: 48%; ">
<p style="text-align:left;">
<input type="checkbox" name="newsletter" id="newsletter" value="1" <?php if (isset($_POST['newsletter'])&&$_POST['newsletter']==1){?> checked="checked"<?php }?> autocomplete="off"/>
<label for="newsletter" style="font-size:8pt;"><?php echo smartyTranslate(array('s'=>'Inscribirse al Boletín'),$_smarty_tpl);?>
</label>
</p>

<p style="text-align:left;">
<input type="checkbox" name="sms" id="sms" value="1" autocomplete="off"/>
<label for="sms" style="font-size:8pt;"><?php echo smartyTranslate(array('s'=>'Recibir avisos y ofertas a tú celular'),$_smarty_tpl);?>
</label>
</p>
</div>
<div style="display:table-cell; float:left; width: 48%; ">
<p class="cart_navigation required submit">
<input type="hidden" name="email_create" value="1" />
<input type="hidden" name="is_new_customer" value="1" />
<?php if (isset($_smarty_tpl->tpl_vars['back']->value)){?><input type="hidden" class="hidden" name="back" value="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['back']->value, 'htmlall', 'UTF-8');?>
" /><?php }?>
<div style="margin-left: 20%;margin-top: -86px;"><input type="submit" name="submitAccount" id="submitAccount" value="<?php echo smartyTranslate(array('s'=>'Register'),$_smarty_tpl);?>
" /></div>
</p>
</div>
</div>
<?php }?>
</form>
</div>
  <!-- quest form --  formulario  modo invitado-->     
      <?php if (isset($_smarty_tpl->tpl_vars['GUEST_FORM_ENABLED']->value)&&$_smarty_tpl->tpl_vars['GUEST_FORM_ENABLED']->value){?>   <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./quest_form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
  <?php }?>
</div>

<?php }?>


    <script>
       /* $('#submitAccount').click(function(){           
            var id_gender =$('input[name="id_gender"]').is(':checked');
            var email = $('#reg-email').val();
            var customer_firstname = $('#customer_firstname').val();
            var customer_lastname = $('#customer_lastname').val();
            var id = $('#id').val();
            var dni = $('#dni').val();
            var days = $('#days').val();
            var months = $('#months').val();
            var years = $('#years').val();
            var passwd = $('input[selector="clave"]').val();
            var confpasswd = $('input[selector="confirma"]').val();
        
            $('.validacion').remove();
        
            if(!id_gender){
                $('#label-gender').append('<span class="validacion" id="obliga-gender">*</span>');
            }else{
                $('#obliga-gender').remove();
            }
           
            if(email==""){
                $('#label-email').append('<span class="validacion" id="obliga-email">*</span>');
            }else{
                if (email.match(/[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/)){
                    $('#obliga-email').remove();
                }else{
                    $('#label-email').append('<span class="validacion" id="obliga-email">Email no Válido</span>');
                }
            }
            
            if(customer_firstname==""){
                $('#label-first').append('<span class="validacion" id="obliga-first">*</span>');
            }else{
                $('#obliga-first').remove();           
            }
            
            if(customer_lastname==""){
                $('#label-last').append('<span class="validacion" id="obliga-last">*</span>');
            }else{
                $('#obliga-last').remove();           
            }
            
            if(id==""){
                $('#label-id').append('<span class="validacion" id="obliga-id">*</span>');
            }else{
                $('#obliga-id').remove();
            }
            
            if(dni==""){
                $('#label-dni').append('<span class="validacion" id="obliga-dni">*</span>');
            }else{
                if (dni.match(/^[\w-\.]{5,11}$/)){
                    $('#obliga-dni').remove();
                }else{
                    $('#label-dni').append('<span class="validacion" id="obliga-dni">Documento no Válido</span>');
                }
            }
            
            if((days=="")||(months=="")||(years=="")){
                $('#label-birth').append('<span class="validacion" id="obliga-birth">*</span>');
            }else{
                $('#obliga-birth').remove();
            }
            
            if(confpasswd==""){
                $('#label-conf').append('<span class="validacion" id="obliga-conf">*</span>');
            }else{
                $('#obliga-conf').remove();
                if(passwd==confpasswd){
                    $('#obliga-passwd').remove();
                    $('#obliga-conf').remove();
                }else{
                    $('#obliga-passwd').remove();
                    $('#obliga-conf').remove();
                    $('#label-passwd').append('<span class="validacion" id="obliga-passwd">No Coincide</span>');
                    $('#label-conf').append('<span class="validacion" id="obliga-conf">No Coincide</span>');
                }
            }
            
            if(passwd==""){
                $('#label-passwd').append('<span class="validacion" id="obliga-passwd">*</span>');
            }else{
                $('#obliga-passwd').remove();
            }
            
            var error=$('.validacion').length;
        
            if(error==0){
                $('form').submit();
            }
        });*/
    </script>
<?php }} ?>