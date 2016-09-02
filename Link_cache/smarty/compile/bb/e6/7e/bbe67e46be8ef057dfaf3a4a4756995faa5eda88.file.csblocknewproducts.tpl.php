<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:31
         compiled from "/var/www/modules/csblocknewproducts/csblocknewproducts.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17049793705348731fb4e907-77275875%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bbe67e46be8ef057dfaf3a4a4756995faa5eda88' => 
    array (
      0 => '/var/www/modules/csblocknewproducts/csblocknewproducts.tpl',
      1 => 1391038092,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17049793705348731fb4e907-77275875',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'new_products' => 0,
    'product' => 0,
    'restricted_country_mode' => 0,
    'priceDisplay' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5348731fbed621_11289714',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5348731fbed621_11289714')) {function content_5348731fbed621_11289714($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>

<!-- MODULE Block new products -->
<div id="new-products_block_right" class="block products_block grid_5 omega">
	<div class="new_content">
	<h4 class="title_block"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('new-products');?>
" title="<?php echo smartyTranslate(array('s'=>'Nuevos Productos','mod'=>'csblocknewproducts'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Nuevos Productos','mod'=>'csblocknewproducts'),$_smarty_tpl);?>
</a></h4>
	<div class="block_content">
	<?php if ($_smarty_tpl->tpl_vars['new_products']->value!==false){?>
		<ul class="cs_new_product">
		<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['new_products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
				<li class="ajax_block_product item">
				<h3 class="name_product"><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'html', 'UTF-8');?>
"><?php echo smarty_modifier_escape(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],50,'...')), 'html', 'UTF-8');?>
</a></h3>
				<div class="products_list_price">
				<?php if (isset($_smarty_tpl->tpl_vars['product']->value['show_price'])&&$_smarty_tpl->tpl_vars['product']->value['show_price']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)){?>
					<span class="price"><?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price']),$_smarty_tpl);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?></span>
				<?php }?>
				</div>
                                <a class="product_image" href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['legend'], 'html', 'UTF-8');?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'home_default');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['legend'], 'html', 'UTF-8');?>
" height="60%" width="60%"/></a></li>
		<?php } ?>
		</ul>
		<a id="prev_cs_new_product" class="prev" href="#">&lt;</a>
		<a id="next_cs_new_product" class="next" href="#">&gt;</a>
	<?php }else{ ?>
		<p>&raquo; <?php echo smartyTranslate(array('s'=>'En este momento no hay nuevos productos disponibles','mod'=>'csblocknewproducts'),$_smarty_tpl);?>
</p>
	<?php }?>
	</div>
	</div>
</div>
<script type="text/javascript">
var item = 1;

						$(window).resize(function(){
						if ($.browser.msie  && parseInt($.browser.version, 10) === 7) {
							return;
						}
						else
						{
							if(getWidthBrowser() < 1023)
									var item = 3;
								else 
									var item = 1;
									//alert(item);
								$("ul.cs_new_product").carouFredSel({
									auto: false,
									responsive: true,
										width: '100%',
										prev: '#prev_cs_new_product',
										next: '#next_cs_new_product',
										swipe: {
											onTouch : true
										},
										items: {
											width: 130,
											visible: {
												min: 1,
												max: item
											}
										},
										scroll: {
											items : item ,       //  The number of items scrolled.
											direction : 'left',    //  The direction of the transition.
											duration  : 500   //  The duration of the transition.
										}

								});
						}
						});
							$(window).load(function(){
								if(getWidthBrowser() < 1023)
									var item = 3;
								else 
									var item = 1;
								$("ul.cs_new_product").carouFredSel({
									auto: false,
									responsive: true,
										width: '100%',
										prev: '#prev_cs_new_product',
										next: '#next_cs_new_product',
										swipe: {
											onTouch : true
										},
										items: {
											width: 130,
											visible: {
												min: 1,
												max: item
											}
										},
										scroll: {
											items : item ,       //  The number of items scrolled.
											direction : 'left',    //  The direction of the transition.
											duration  : 500   //  The duration of the transition.
										}

								});
							});
		</script>
<!-- /MODULE Block new products -->
<?php }} ?>