<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 18:04:05
         compiled from "/var/www/themes/gomarket/product-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1821342911534874e52c5267-17618355%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '55862ab08c6ec6eaac6419a2f167da6d31088bf1' => 
    array (
      0 => '/var/www/themes/gomarket/product-list.tpl',
      1 => 1397062678,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1821342911534874e52c5267-17618355',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'products' => 0,
    'grid_product' => 0,
    'product' => 0,
    'link' => 0,
    'specific_prices' => 0,
    'PS_CATALOG_MODE' => 0,
    'restricted_country_mode' => 0,
    'priceDisplay' => 0,
    'add_prod_display' => 0,
    'static_token' => 0,
    'comparator_max_item' => 0,
    'compareProducts' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534874e53d6a22_76324080',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534874e53d6a22_76324080')) {function content_534874e53d6a22_76324080($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
if (!is_callable('smarty_modifier_date_format')) include '/var/www/tools/smarty/plugins/modifier.date_format.php';
?>

<?php if (isset($_smarty_tpl->tpl_vars['products']->value)){?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	_csJnit(IS,n);
});
//]]>
</script>
	<div style="float:left;">
		<ul id="product_list" class="product_grid">
		<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['product']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['product']->iteration=0;
 $_smarty_tpl->tpl_vars['product']->index=-1;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['product']->iteration++;
 $_smarty_tpl->tpl_vars['product']->index++;
 $_smarty_tpl->tpl_vars['product']->first = $_smarty_tpl->tpl_vars['product']->index === 0;
 $_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['first'] = $_smarty_tpl->tpl_vars['product']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['index']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['last'] = $_smarty_tpl->tpl_vars['product']->last;
?>
		<li class="<?php if (isset($_smarty_tpl->tpl_vars['grid_product']->value)){?><?php echo $_smarty_tpl->tpl_vars['grid_product']->value;?>
<?php }elseif(isset($_COOKIE['grid_product'])){?><?php echo $_COOKIE['grid_product'];?>
<?php }else{ ?>grid_6<?php }?> ajax_block_product <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['first']){?>first_item<?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['last']){?>last_item<?php }?> <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['index']%2){?>alternate_item<?php }else{ ?>item<?php }?> clearfix omega alpha">
			<div class="center_block">				
				<div class="image"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['link'], 'htmlall', 'UTF-8');?>
" class="product_img_link" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8');?>
">
					<img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'home_default');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['legend'], 'htmlall', 'UTF-8');?>
" />
				</a>
				<?php if ($_smarty_tpl->tpl_vars['product']->value['specific_prices']){?>
        			<?php $_smarty_tpl->tpl_vars['specific_prices'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value['specific_prices'], null, 0);?>
        			<?php if ($_smarty_tpl->tpl_vars['specific_prices']->value['reduction_type']=='percentage'&&($_smarty_tpl->tpl_vars['specific_prices']->value['from']==$_smarty_tpl->tpl_vars['specific_prices']->value['to']||(smarty_modifier_date_format(time(),'%Y-%m-%d %H:%M:%S')<=$_smarty_tpl->tpl_vars['specific_prices']->value['to']&&smarty_modifier_date_format(time(),'%Y-%m-%d %H:%M:%S')>=$_smarty_tpl->tpl_vars['specific_prices']->value['from']))){?>
	        			<p class="reduction"><?php echo smartyTranslate(array('s'=>'Save '),$_smarty_tpl);?>
<span><?php echo $_smarty_tpl->tpl_vars['specific_prices']->value['reduction']*floatval(100);?>
</span>%</p>
	            	<?php }?>
					<?php }?>
				</div>
				<div class="name_product"><h3><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['link'], 'htmlall', 'UTF-8');?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8');?>
"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8'),45,'...');?>
</a></h3></div>
				<p class="product_desc"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['product']->value['description_short']),200,'...');?>
</p>
				<?php if ((!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value&&((isset($_smarty_tpl->tpl_vars['product']->value['show_price'])&&$_smarty_tpl->tpl_vars['product']->value['show_price'])||(isset($_smarty_tpl->tpl_vars['product']->value['available_for_order'])&&$_smarty_tpl->tpl_vars['product']->value['available_for_order'])))){?>
				<div class="content_price">
					<?php if ($_smarty_tpl->tpl_vars['product']->value['reduction']){?><span class="price-discount"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayWtPrice'][0][0]->displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['product']->value['price_without_reduction']),$_smarty_tpl);?>
</span><?php }?>
					<?php if (isset($_smarty_tpl->tpl_vars['product']->value['show_price'])&&$_smarty_tpl->tpl_vars['product']->value['show_price']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)){?><span class="price<?php if ($_smarty_tpl->tpl_vars['product']->value['reduction']){?> old<?php }?>" style="display: inline;"><?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price']),$_smarty_tpl);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?></span><?php }?>
				</div>
				<?php }?>
				
			<?php if (($_smarty_tpl->tpl_vars['product']->value['id_product_attribute']==0||(isset($_smarty_tpl->tpl_vars['add_prod_display']->value)&&($_smarty_tpl->tpl_vars['add_prod_display']->value==1)))&&$_smarty_tpl->tpl_vars['product']->value['available_for_order']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&$_smarty_tpl->tpl_vars['product']->value['minimal_quantity']<=1&&$_smarty_tpl->tpl_vars['product']->value['customizable']!=2&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
					<?php if (($_smarty_tpl->tpl_vars['product']->value['allow_oosp']||$_smarty_tpl->tpl_vars['product']->value['quantity']>0)){?>
						<?php if (isset($_smarty_tpl->tpl_vars['static_token']->value)){?>
							<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',false,null,"add&amp;id_product=".$_tmp1."&amp;token=".((string)$_smarty_tpl->tpl_vars['static_token']->value),false);?>
" title="<?php echo smartyTranslate(array('s'=>'Agregar al Carrito'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Agregar al Carrito'),$_smarty_tpl);?>
</a>
						<?php }else{ ?>
							<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',false,null,"add&amp;id_product=".$_tmp2,false);?>
" title="<?php echo smartyTranslate(array('s'=>'Agregar al carrito'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Agregar al Carrito'),$_smarty_tpl);?>
</a>
						<?php }?>						
					<?php }else{ ?>
						<span class="exclusive"><?php echo smartyTranslate(array('s'=>'Out of stock'),$_smarty_tpl);?>
</span>
					<?php }?>
				<?php }?>	
				<?php if (isset($_smarty_tpl->tpl_vars['comparator_max_item']->value)&&$_smarty_tpl->tpl_vars['comparator_max_item']->value){?>
					<p class="compare">
						<input type="checkbox" class="comparator" id="comparator_item_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
" value="comparator_item_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['compareProducts']->value)&&in_array($_smarty_tpl->tpl_vars['product']->value['id_product'],$_smarty_tpl->tpl_vars['compareProducts']->value)){?>checked="checked"<?php }?> /> 
						<label for="comparator_item_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
"><?php echo smartyTranslate(array('s'=>'Select to compare'),$_smarty_tpl);?>
</label>
					</p>
				<?php }?>
			</div>
		</li>
	<?php } ?>
	</ul>
</div>
	<!-- /Products list -->
	 <div class="cclearfix" style="float:left;font-size: 7pt;padding: 0 0px 20px 0;text-align: justify;">Bienvenido a Farmalisto, tu droguería online, encontrarás productos de farmacia, salud, nutrición, cuidado personal y para la familia. Compra y haz tus pedidos fácilmente, servicio a domicilio en Bogotá, Antioquia, Valle del Cauca, Atlántico, Santander, Norte de Santander, Tolima, Risaralda, Magdalena, Córdoba, Caldas, Nariño, Cauca, Meta, quindío, Cesar, Huila, Sucre, Boyacá, Cundinamarca, Casanare, La Guajira, Arauca, Caquetá, Putumayo y demás ciudades y pueblos de Colombia, garantizamos el mejor precio. Acá te decimos para qué sirve<label id="tituloCategoryProd"></label>.

A través de farmalisto puedes comprar con diversos medios de pago, tarjeta débito, tarjeta de crédito, baloto, cuenta de ahorros, efectivo, te brindamos seguridad en cada una de tus transacciones a través de nuestro sistema Symantec Powered by verisign un completo sistema de seguridad en tus compras. Puedes llamarnos a nuestra línea de atención y televentas en Bogotá al 2205249 y Nacionalmente en el 01800 9133830. o puedes escribirnos a nuestro correo de contacto, contacto@farmalisto.com.co. 

Nuestros beneficios: Mejor precio garantizado, se trata de una garantía en la que podrás obtener el doble del valor de la diferencia del producto que encuentres a un menor precio en otro establecimiento que sea certificado para la venta de medicamentos, traes la factura o cotización y en tu próxima compra la podrás hacer efectiva, No más filas, con nosotros ya no tendrás que hacer filas al salir de tu médico, IPS o EPS, simplemente compras online y hasta puedes pagar en casa, simple y rápidamente. 

Discreción en todas tus compras, total confidencialidad en todas tus transacciones, hay algunas ocasiones en las que quieres comprar algunos productos y no quieres que nadie se entere, con nosotros puedes mantenerte tranquilo, haces tu pedido y te entregamos en el lugar que nos indiques.

Tu fórmula médica completa en un sólo lugar, ya no tendrás que pasar de farmacia en farmacia para conseguir tus medicamentos, nosotros lo hacemos por ti, simplemente haz tu pedido y nosotros nos encargamos de todo.

Contamos con profesionales certificados de la farmacológia ofreciendo así garantía total en tu experiencia de compra, no somos un ecomerce común somos una droguería 100% online en donde encontrarás todo lo que necesitas.

Sí vas a hacer una compra por más de $99.000 pesos puedes obtener un descuento de $10.000 usando el cupón con el código "AYUDASALUD" permamentemente, sí consumes o compras mensualmente tus medicamentos, con nosotros no olvidarás tomarlos, te cuidamos y nos esforzamos en recordártelo, nuestro interés es tu bienestar.

No te automediques, nosotros somos responsables por la salud de nuestros clientes, por ello exigimos un soporte certificado (fórmula médica) por tú médico o EPS en el que autorice la venta del medicamento, la dósis, presentación del producto, fecha, advertencias, características, posología, indicaciones y contraindicaciones es responsabilidad de tú médico y no nos responsabilizamos por ello.</div> 

	
<?php }?>
<?php }} ?>