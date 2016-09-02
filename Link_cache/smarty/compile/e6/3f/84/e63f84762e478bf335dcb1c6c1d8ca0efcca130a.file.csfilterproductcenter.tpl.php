<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:32
         compiled from "/var/www/modules/csfilterproductcenter/csfilterproductcenter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9312265745348732014ac08-71170928%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e63f84762e478bf335dcb1c6c1d8ca0efcca130a' => 
    array (
      0 => '/var/www/modules/csfilterproductcenter/csfilterproductcenter.tpl',
      1 => 1395950642,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9312265745348732014ac08-71170928',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tabs' => 0,
    'index' => 0,
    'hook' => 0,
    'tab' => 0,
    'cookie' => 0,
    'product' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5348732022c5b4_20356496',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5348732022c5b4_20356496')) {function content_5348732022c5b4_20356496($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
if (!is_callable('smarty_function_math')) include '/var/www/tools/smarty/plugins/function.math.php';
?><!-- /CS Filter Product Module -->
<?php if (count($_smarty_tpl->tpl_vars['tabs']->value)>0){?>
<div class="cs_home_filter_product grid_14">
	<?php $_smarty_tpl->tpl_vars["index"] = new Smarty_variable(1, null, 0);?>
	<?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
?>
		<div class="cs_home_filter_row cat_block <?php if ($_smarty_tpl->tpl_vars['index']->value>2){?> filter_row_<?php echo $_smarty_tpl->tpl_vars['hook']->value;?>
_1<?php }else{ ?>filter_row_<?php echo $_smarty_tpl->tpl_vars['hook']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
<?php }?>">
			<h4 class="title_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['tab']->value->title[(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang];?>
</h4>
			<div class="products_content_tab">
			<?php if ($_smarty_tpl->tpl_vars['tab']->value->product_list){?>
			<div class="slide_image grid_5">
					<ul id="ul_category_featured_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
">
					<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tab']->value->product_list; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
						<li class="ajax_block_product">
						<div class="cs_content_p">
						<a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['link'], 'htmlall', 'UTF-8');?>
" class="product_img_link product_image">
						<img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'home_default');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['legend'], 'htmlall', 'UTF-8');?>
" /></a>
						<div class="title_name_product">
						<h3 class="name_product"><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
"><?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],25,'...'), 'htmlall', 'UTF-8');?>
</a></h3></div>
						<p class="description">
							<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['product']->value['description']),55,'...');?>

						</p>
						<div class="shop_new_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8');?>
"><?php echo smartyTranslate(array('s'=>'comprar','mod'=>'csfilterproductcenter'),$_smarty_tpl);?>
</a></div>
						</div>
						</li>
					<?php } ?>
					</ul>
					<a id="prev_featured_cat_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="prev btn" href="#">&lt;</a>
					<a id="next_featured_cat_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="next btn" href="#">&gt;</a>
			</div>
			<script type="text/javascript">
								/*$(window).resize(function(){
									if ($(window).width() > 767 && $(window).width() <= 960)
									{
										$(".pro_filter_5").remove();
										$(".pro_filter_6").remove();
									}
								});*/
								$(window).load(function(){
									$("#ul_category_featured_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
").carouFredSel({
										auto: false,
										responsive: true,
											width: '100%',
											height : 'variable',
											prev: '#prev_featured_cat_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
											next: '#next_featured_cat_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
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
									$("#ul_category_featured_list_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
").carouFredSel({
										auto: false,
										responsive: true,
											width: '100%',
											height : 'variable',
											prev: '#prev_featured_cat_list_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
											next: '#next_featured_cat_list_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
											swipe: {
												onTouch : true
											},
											items: {
												width: 130,
												height : 'auto',
												visible: {
													min: 2,
													max: 3
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
			<div class="products_home_filter_product">
				<?php if ($_smarty_tpl->tpl_vars['tab']->value->product_list){?>
					<div class="product_list_filter ">
					<ul id="ul_category_featured_list_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
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
						<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['first']||$_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['iteration']%2==1){?>
						<li style="float:left" class="ajax_block_product">
						<?php }?>
							<div class="pro_filter_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['iteration'];?>
 cs_product <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['first']){?>first_item<?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['last']){?>last_item<?php }else{ ?>item<?php }?>">
							<div class="image">	
							<a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['link'], 'htmlall', 'UTF-8');?>
" class="product_img_link product_image">
							<img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'medium_default');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['legend'], 'htmlall', 'UTF-8');?>
" /></a></div>
							<div class="name_product"><h3><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
"><?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],20,'...'), 'htmlall', 'UTF-8');?>
</a></h3></div>
							</div>
						<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['last']||$_smarty_tpl->getVariable('smarty')->value['foreach']['product_list']['iteration']%2==0){?>
						</li>
						<?php }?>
						<?php } ?>
					</ul>
					<a id="prev_featured_cat_list_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="prev btn" href="#">&lt;</a>
					<a id="next_featured_cat_list_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="next btn" href="#">&gt;</a>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
	<?php echo smarty_function_math(array('equation'=>"temp + nb",'temp'=>$_smarty_tpl->tpl_vars['index']->value,'nb'=>1,'assign'=>'index'),$_smarty_tpl);?>

	<?php } ?>
</div>
<?php }?>
<!-- /CS Filter Product Module -->
<?php }} ?>