<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:59:18
         compiled from "/var/www/themes/gomarket/modules/productscategory/productscategory.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1398851096534873c6759854-00134877%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '77a171509488c080964e4d3944ef4ae223e38f43' => 
    array (
      0 => '/var/www/themes/gomarket/modules/productscategory/productscategory.tpl',
      1 => 1397062524,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1398851096534873c6759854-00134877',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'categoryProducts' => 0,
    'img_dir' => 0,
    'categoryProduct' => 0,
    'link' => 0,
    'ProdDisplayPrice' => 0,
    'restricted_country_mode' => 0,
    'PS_CATALOG_MODE' => 0,
    'add_prod_display' => 0,
    'static_token' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873c6920e65_74809489',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873c6920e65_74809489')) {function content_534873c6920e65_74809489($_smarty_tpl) {?><?php if (!is_callable('smarty_function_math')) include '/var/www/tools/smarty/plugins/function.math.php';
if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>

<?php if (count($_smarty_tpl->tpl_vars['categoryProducts']->value)>0&&$_smarty_tpl->tpl_vars['categoryProducts']->value!==false){?>

<div class="clearfix blockproductscategory" id="textosseo">
    <h2 class="productscategory_h2" style="color:#969696 !important;background: #666666;
			background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));
			background: -moz-linear-gradient(#E6E6E6, #fff);
			background: linear-gradient(#E6E6E6, #fff);
			-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 1px -2px 3px #333;
			-webkit-box-shadow: #666 0px 2px 3px;
			-moz-box-shadow: #666 0px 2px 3px;border.radius: 3px 3px 3px 3px; 
			-moz-border-radius:3px 3px 3px 3px;
			-webkit-border-radius:3px 3px 3px 3px;font-weight: 100;font-size: 17px;text-transform: capitalize;"><?php echo count($_smarty_tpl->tpl_vars['categoryProducts']->value);?>
 <?php echo smartyTranslate(array('s'=>'other products in the same category:','mod'=>'productscategory'),$_smarty_tpl);?>
</h2>
	<div id="<?php if (count($_smarty_tpl->tpl_vars['categoryProducts']->value)>5){?>productscategory<?php }else{ ?>productscategory_noscroll<?php }?>" id="contenedor1">
            
            <div id="productscategory_list" class="list_carousel responsive">
			<ul id="carousel-productscategory" <?php if (count($_smarty_tpl->tpl_vars['categoryProducts']->value)>5){?>style="width: <?php echo smarty_function_math(array('equation'=>"width * nbImages",'width'=>107,'nbImages'=>count($_smarty_tpl->tpl_vars['categoryProducts']->value)),$_smarty_tpl);?>
px"<?php }?>>
				
                            <?php  $_smarty_tpl->tpl_vars['categoryProduct'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['categoryProduct']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categoryProducts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['categoryProduct']->key => $_smarty_tpl->tpl_vars['categoryProduct']->value){
$_smarty_tpl->tpl_vars['categoryProduct']->_loop = true;
?>
				<li <?php if (count($_smarty_tpl->tpl_vars['categoryProducts']->value)<6){?><?php }?> class="ajax_block_product grid_5  omega alpha" id="elei">
                                    
                                  <div class="center_block" id="contenedor2" style="background: url('<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
resum_product.png');  background-repeat: no-repeat;">
                                    <div class="image" id="contenedor3">
					<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product'],$_smarty_tpl->tpl_vars['categoryProduct']->value['link_rewrite'],$_smarty_tpl->tpl_vars['categoryProduct']->value['category'],$_smarty_tpl->tpl_vars['categoryProduct']->value['ean13']);?>
" class="lnk_img product_img_link" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['name']);?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['categoryProduct']->value['link_rewrite'],$_smarty_tpl->tpl_vars['categoryProduct']->value['id_image'],'home_default');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['name']);?>
" /></a>
				<img id="imagenn" src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
resum_product_line.png">  
                                    </div>
					
                                
                                
                                <div class="name_product" id="contenedorProducto">
                                            <div id="tituloProduc" ><span style=" display: inline-block; vertical-align: middle; line-height: normal;">
                                            	<span>
                                            	<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product'],$_smarty_tpl->tpl_vars['categoryProduct']->value['link_rewrite'],$_smarty_tpl->tpl_vars['categoryProduct']->value['category'],$_smarty_tpl->tpl_vars['categoryProduct']->value['ean13']);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['name']);?>
"><?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['categoryProduct']->value['name'],45,'...'), 'htmlall', 'UTF-8');?>
</a></span><br>
                                            <span class="price" id="PrecioPrice"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['categoryProduct']->value['displayed_price']),$_smarty_tpl);?>
</span></span></div>                                          

								</div>
					<!--p class="desription"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['categoryProduct']->value['description_short']),90,'...');?>
</p-->
					<?php if ($_smarty_tpl->tpl_vars['ProdDisplayPrice']->value&&$_smarty_tpl->tpl_vars['categoryProduct']->value['show_price']==1&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
                                            
                                            <p class="price_display" style=" "></p>
                                            <div style="height: 30px;"></div>
					<?php }?>
					<?php if (($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product_attribute']==0||(isset($_smarty_tpl->tpl_vars['add_prod_display']->value)&&($_smarty_tpl->tpl_vars['add_prod_display']->value==1)))&&$_smarty_tpl->tpl_vars['categoryProduct']->value['available_for_order']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&$_smarty_tpl->tpl_vars['categoryProduct']->value['minimal_quantity']<=1&&$_smarty_tpl->tpl_vars['categoryProduct']->value['customizable']!=2&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
					<?php if (($_smarty_tpl->tpl_vars['categoryProduct']->value['allow_oosp']||$_smarty_tpl->tpl_vars['categoryProduct']->value['quantity']>0)){?>
						<?php if (isset($_smarty_tpl->tpl_vars['static_token']->value)){?>
                                                    <a class="" style="display:table;"  rel="ajax_id_product_<?php echo intval($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product']);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',false,null,"add&amp;id_product=".$_tmp1."&amp;token=".((string)$_smarty_tpl->tpl_vars['static_token']->value),false);?>
" title="<?php echo smartyTranslate(array('s'=>'Comprar','mod'=>'productscategory'),$_smarty_tpl);?>
">
                                                        
                                                        <div id="botonComprar">
                                                            <span class="comprar_hov" style="position: relative; top: 9px; color: #FFF;  "><?php echo smartyTranslate(array('s'=>'COMPRAR','mod'=>'productscategory'),$_smarty_tpl);?>
</span>
                                                        </div>
                                                    </a>
						<?php }else{ ?>
							<a class="button ajax_add_to_cart_button exclusive" style="  " rel="ajax_id_product_<?php echo intval($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['categoryProduct']->value['id_product']);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',false,null,"add&amp;id_product=".$_tmp2,false);?>
" title="<?php echo smartyTranslate(array('s'=>'Agregar al carrito','mod'=>'productscategory'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Agregar al carrito','mod'=>'productscategory'),$_smarty_tpl);?>
</a>
						<?php }?>						
					<?php }else{ ?>
                                            <div id="botonAgotado">
						<span style="float: left;margin-top: 11px;margin-left: 44px;color: #fff;"><?php echo smartyTranslate(array('s'=>'AGOTADO','mod'=>'productscategory'),$_smarty_tpl);?>
</span>
                                            </div>
					<?php }?>
				<?php }?>
				</div>
                                
				</li>
				<?php } ?>
			</ul>
                        <div class="cclearfix" id="textoSeo">Bienvenido a Farmalisto, tu droguería online, encontrarás productos de farmacia, salud, nutrición, cuidado personal y para la familia. Compra y haz tus pedidos fácilmente, servicio a domicilio en Bogotá, Antioquia, Valle del Cauca, Atlántico, Santander, Norte de Santander, Tolima, Risaralda, Magdalena, Córdoba, Caldas, Nariño, Cauca, Meta, quindío, Cesar, Huila, Sucre, Boyacá, Cundinamarca, Casanare, La Guajira, Arauca, Caquetá, Putumayo y demás ciudades y pueblos de Colombia, garantizamos el mejor precio. Acá te decimos para qué sirve<label id="tituloCategoryProd"></label>.

A través de farmalisto puedes comprar con diversos medios de pago, tarjeta débito, tarjeta de crédito, baloto, cuenta de ahorros, efectivo, te brindamos seguridad en cada una de tus transacciones a través de nuestro sistema Symantec Powered by verisign un completo sistema de seguridad en tus compras. Puedes llamarnos a nuestra línea de atención y televentas en Bogotá al 2205249 y Nacionalmente en el 01800 9133830. o puedes escribirnos a nuestro correo de contacto, contacto@farmalisto.com.co. 

Nuestros beneficios: Mejor precio garantizado, se trata de una garantía en la que podrás obtener el doble del valor de la diferencia del producto que encuentres a un menor precio en otro establecimiento que sea certificado para la venta de medicamentos, traes la factura o cotización y en tu próxima compra la podrás hacer efectiva, No más filas, con nosotros ya no tendrás que hacer filas al salir de tu médico, IPS o EPS, simplemente compras online y hasta puedes pagar en casa, simple y rápidamente. 

Discreción en todas tus compras, total confidencialidad en todas tus transacciones, hay algunas ocasiones en las que quieres comprar algunos productos y no quieres que nadie se entere, con nosotros puedes mantenerte tranquilo, haces tu pedido y te entregamos en el lugar que nos indiques.

Tu fórmula médica completa en un sólo lugar, ya no tendrás que pasar de farmacia en farmacia para conseguir tus medicamentos, nosotros lo hacemos por ti, simplemente haz tu pedido y nosotros nos encargamos de todo.

Contamos con profesionales certificados de la farmacológia ofreciendo así garantía total en tu experiencia de compra, no somos un ecomerce común somos una droguería 100% online en donde encontrarás todo lo que necesitas.

Sí vas a hacer una compra por más de $99.000 pesos puedes obtener un descuento de $10.000 usando el cupón con el código "AYUDASALUD" permamentemente, sí consumes o compras mensualmente tus medicamentos, con nosotros no olvidarás tomarlos, te cuidamos y nos esforzamos en recordártelo, nuestro interés es tu bienestar.

No te automediques, nosotros somos responsables por la salud de nuestros clientes, por ello exigimos un soporte certificado (fórmula médica) por tú médico o EPS en el que autorice la venta del medicamento, la dósis, presentación del producto, fecha, advertencias, características, posología, indicaciones y contraindicaciones es responsabilidad de tú médico y no nos responsabilizamos por ello.</div> 


			<a id="prev-productscategory" class="btn prev" href="#">&lt;</a>
			<a id="next-productscategory" class="btn next" href="#">&gt;</a>
		</div>
	</div>
	<script type="text/javascript">
		$(window).load(function(){
			//	Responsive layout, resizing the items
			$('#carousel-productscategory').carouFredSel({
				responsive: true,
				auto: false,
				height : 'variable',
				prev: '#prev-productscategory',
				next: '#next-productscategory',
				swipe: {
					onTouch : true
				},
				items: {
					width: 140,
					height : 'variable',					
					visible: {
                            //  este valores indican el minimo yl maximo de productos que debe mostar el slide, cuando las resolucion es minima o maxima    
						min: 1,
						max: 5
					}
				}
			});
		});
	</script>
</div>
<?php }?>
<?php }} ?>