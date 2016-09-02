<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:32
         compiled from "/var/www/modules/csfilterproductright/csfilterproductright.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9011159315348732026c1d5-09073784%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aebf9a0a87beeae5e780fdc1c6ce6ae046342298' => 
    array (
      0 => '/var/www/modules/csfilterproductright/csfilterproductright.tpl',
      1 => 1379877693,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9011159315348732026c1d5-09073784',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tabs' => 0,
    'tab' => 0,
    'cookie' => 0,
    'product' => 0,
    'link' => 0,
    'restricted_country_mode' => 0,
    'priceDisplay' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873202f5ef4_78854816',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873202f5ef4_78854816')) {function content_534873202f5ef4_78854816($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?><!-- CS Filter Product Right Module -->
<?php if (count($_smarty_tpl->tpl_vars['tabs']->value)>0){?>
<div class="cs_home_filter_product_right grid_5 omega">
	<?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
?>
		<div class="cs_home_filter_right_row cat_block filter_right_row_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
">
			<h4 class="title_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['tab']->value->title[(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang];?>
</h4>
			<?php if ($_smarty_tpl->tpl_vars['tab']->value->product_list){?>
				<div class="filter_right">
					<div class="product_list_filter_right">
					<ul id="ul_filter_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
">
						<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tab']->value->product_list; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['product']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['product']->iteration=0;
 $_smarty_tpl->tpl_vars['product']->index=-1;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['product_list']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['product']->iteration++;
 $_smarty_tpl->tpl_vars['product']->index++;
 $_smarty_tpl->tpl_vars['product']->first = $_smarty_tpl->tpl_vars['product']->index === 0;
 $_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['product_list']['first'] = $_smarty_tpl->tpl_vars['product']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['product_list']['iteration']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['product_list']['last'] = $_smarty_tpl->tpl_vars['product']->last;
?>
						<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['first']||$_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['iteration']%3==1){?>
						<li class="ajax_block_product">
						<?php }?>
							<div class="item_product">							
							<div class="image">	
							<a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['link'], 'htmlall', 'UTF-8');?>
" class="product_img_link product_image" >
							<img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'small_default');?>
" /></a></div>
							<h3 class="name_product"><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],20,'...'), 'htmlall', 'UTF-8');?>
</a></h3>
							<div class="products_list_price">
								<?php if (isset($_smarty_tpl->tpl_vars['product']->value['show_price'])&&$_smarty_tpl->tpl_vars['product']->value['show_price']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)){?>
									<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value&&$_smarty_tpl->tpl_vars['product']->value['reduction']){?><span class="price-discount"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayWtPrice'][0][0]->displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['product']->value['price_without_reduction']),$_smarty_tpl);?>
</span><?php }?>
									<span class="price"><?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price']),$_smarty_tpl);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?></span>
								<?php }?>
							</div>						
							
							</div>
						<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['last']||$_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['iteration']%3==0){?>
						</li>
						<?php }?>
						<?php } ?>
					</ul>
					<a id="prev_filter_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="prev btn" href="#">&lt;</a>
					<a id="next_filter_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="next btn" href="#">&gt;</a>
				</div>
			</div>
			<script type="text/javascript">
								$(window).load(function(){
									$("#ul_filter_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
").carouFredSel({
										auto: false,
										responsive: true,
											width: '100%',
											height: 'variable',
											prev: '#prev_filter_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
											next: '#next_filter_right_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
											swipe: {
												onTouch : true
											},
											items: {
												width: 130,
												height : 'auto',
												visible: {
													min: 1,
													max: 1
												}
											},
											scroll: {
												items : 1 ,       //  The number of items scrolled.
												direction : 'left',    //  The direction of the transition.
												duration  : 500   //  The duration of the transition.
											}

									});
								});
			</script>
		 <?php }?>
	 </div>
	<?php } ?>
</div>
<?php }?>
<!-- CS Filter Product Right Module -->
<?php }} ?>