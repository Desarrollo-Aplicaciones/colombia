<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:31
         compiled from "/var/www/modules/csfilterproductleft/csfilterproductleft.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17232326355348731fd82da8-39036444%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c1723fd72623c33cfd122f45707f563088bcd3ae' => 
    array (
      0 => '/var/www/modules/csfilterproductleft/csfilterproductleft.tpl',
      1 => 1397149024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17232326355348731fd82da8-39036444',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tabs' => 0,
    'ftab' => 0,
    'index' => 0,
    'hook' => 0,
    'cookie' => 0,
    'tab' => 0,
    'product' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5348731fe84803_85795664',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5348731fe84803_85795664')) {function content_5348731fe84803_85795664($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
if (!is_callable('smarty_function_math')) include '/var/www/tools/smarty/plugins/function.math.php';
?><!-- CS Home Tab module -->
<div class="home_top_tab grid_5 alpha">
<?php if (count($_smarty_tpl->tpl_vars['tabs']->value)>0){?>
<div class="cs_home_none_tab">
<?php $_smarty_tpl->tpl_vars["index"] = new Smarty_variable(1, null, 0);?>
	<?php  $_smarty_tpl->tpl_vars['ftab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ftab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ftab']->key => $_smarty_tpl->tpl_vars['ftab']->value){
$_smarty_tpl->tpl_vars['ftab']->_loop = true;
?>
	<?php $_smarty_tpl->tpl_vars["tab"] = new Smarty_variable($_smarty_tpl->tpl_vars['ftab']->value['info'], null, 0);?>
		<div class="cs_hometab_row cat_block <?php if ($_smarty_tpl->tpl_vars['index']->value>2){?> none_tab_row_<?php echo $_smarty_tpl->tpl_vars['hook']->value;?>
_1<?php }else{ ?>none_tab_row_<?php echo $_smarty_tpl->tpl_vars['hook']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
<?php }?>">
			<h4><?php echo $_smarty_tpl->tpl_vars['tab']->value->title[(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang];?>
</h4>
			<div class="products_none_tab">
				<span class="icon"><?php echo $_smarty_tpl->tpl_vars['tab']->value->title[(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang];?>
</span>
				<?php if ($_smarty_tpl->tpl_vars['tab']->value->product_list){?>
					<div class="list_call_carousel">
						<ul class="call_carousel" id="call_carousel_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
">
						<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tab']->value->product_list; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
							<li class="ajax_block_product">
							<a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" class="product_image"><img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'home_default');?>
"/></a>
							<h3 class="name_product"><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" ><?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],22,'...'), 'htmlall', 'UTF-8');?>
</a></h3>
							<p class="description">
								<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['product']->value['description']),50,'...');?>

							</p>
						</li>
						<?php break 1?>
						<?php } ?>
						</ul>
						<a id="prev_cs_home_none_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="prev btn" href="#">&lt;</a>
						<a id="next_cs_home_none_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
" class="next btn" href="#">&gt;</a>
					</div>
				<?php }?>
				
			</div>
		</div>
		<script type="text/javascript">
		$(window).load(function(){
		$("#call_carousel_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
").carouFredSel({
			auto: false,
			responsive: true,
				width: '100%',
				prev: '#prev_cs_home_none_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
				next: '#next_cs_home_none_<?php echo $_smarty_tpl->tpl_vars['tab']->value->id;?>
',
				swipe: {
					onTouch : true
				},
				items: {
					width: 198,
					visible: {
						min: 1,
						max: 1
					}
				},
				scroll: {
					items : 1 ,       //  The number of items scrolled.
					direction : 'left',
					duration  : 500   //  The duration of the transition.
				}

		});
	});
</script>
<?php echo smarty_function_math(array('equation'=>"temp + nb",'temp'=>$_smarty_tpl->tpl_vars['index']->value,'nb'=>1,'assign'=>'index'),$_smarty_tpl);?>

	<?php } ?>
</div>
<?php }?>
</div>
<!-- /CS Home Tab module -->
<?php }} ?>