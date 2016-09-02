<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 18:04:04
         compiled from "/var/www/themes/gomarket/category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1917519046534874e4ea50a9-20600282%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d32a9885d4ff338b38b9a3ea4c89f9899b4f741' => 
    array (
      0 => '/var/www/themes/gomarket/category.tpl',
      1 => 1393868962,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1917519046534874e4ea50a9-20600282',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'category' => 0,
    'scenes' => 0,
    'link' => 0,
    'categoryNameComplement' => 0,
    'products' => 0,
    'subcategories' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534874e50d9e39_13241176',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534874e50d9e39_13241176')) {function content_534874e50d9e39_13241176($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./breadcrumb.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if (isset($_smarty_tpl->tpl_vars['category']->value)){?>
	<?php if ($_smarty_tpl->tpl_vars['category']->value->id&&$_smarty_tpl->tpl_vars['category']->value->active){?>
		<?php if ($_smarty_tpl->tpl_vars['scenes']->value||$_smarty_tpl->tpl_vars['category']->value->description||$_smarty_tpl->tpl_vars['category']->value->id_image){?>
		<div class="content_scene_cat">
			<?php if ($_smarty_tpl->tpl_vars['scenes']->value){?>
				<!-- Scenes -->
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./scenes.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('scenes'=>$_smarty_tpl->tpl_vars['scenes']->value), 0);?>

			<?php }else{ ?>
				<!-- Category image -->
				<?php if ($_smarty_tpl->tpl_vars['category']->value->id_image){?>
				<div class="align_center">
					<img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getCatImageLink($_smarty_tpl->tpl_vars['category']->value->link_rewrite,$_smarty_tpl->tpl_vars['category']->value->id_image,'category_default');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['category']->value->name, 'htmlall', 'UTF-8');?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['category']->value->name, 'htmlall', 'UTF-8');?>
" id="categoryImage"/>
				</div>
				<?php }?>
			<?php }?>

			
				<div class="cat_desc">
				<h2>
				<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['category']->value->name, 'htmlall', 'UTF-8');?>
<?php if (isset($_smarty_tpl->tpl_vars['categoryNameComplement']->value)){?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['categoryNameComplement']->value, 'htmlall', 'UTF-8');?>
<?php }?>
				</h2>
				<?php if ($_smarty_tpl->tpl_vars['category']->value->description){?>
				<?php if (strlen($_smarty_tpl->tpl_vars['category']->value->description)>120){?>
					<p id="category_description_short"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['category']->value->description,120);?>
</p>
					<p id="category_description_full" style="display:none"><?php echo $_smarty_tpl->tpl_vars['category']->value->description;?>
</p>
					
				<?php }else{ ?>
					<p><?php echo $_smarty_tpl->tpl_vars['category']->value->description;?>
</p>
				<?php }?>
				<?php }?>
				</div>
			
		</div>
		<?php }?>
		<!-- Breadcumb -->
		<script type="text/javascript">
			jQuery(document).ready(function() {
				if (jQuery("#old_bc").html()) {
					jQuery("#bc").html(jQuery("#old_bc").html());
					jQuery("#old_bc").hide();
				}
			});
		</script>
		<div class="bc_line">
			<div id="bc" class="breadcrumb"></div>
		</div>
		
		<div class="resumecat category-product-count">
			<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./category-count.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		</div>
		<?php if ($_smarty_tpl->tpl_vars['products']->value){?>
			<div class="content_sortPagiBar">
				<div class="sortPagiBar">
					<?php echo $_smarty_tpl->getSubTemplate ("./product-compare.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<?php echo $_smarty_tpl->getSubTemplate ("./product-sort.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
					
					<?php echo $_smarty_tpl->getSubTemplate ("./nbr-product-page.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

				</div>
			</div>
			<h1>
			<?php echo smartyTranslate(array('s'=>'Categories'),$_smarty_tpl);?>

			<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['category']->value->name, 'htmlall', 'UTF-8');?>
<?php if (isset($_smarty_tpl->tpl_vars['categoryNameComplement']->value)){?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['categoryNameComplement']->value, 'htmlall', 'UTF-8');?>
<?php }?>
			</h1>
			<?php echo $_smarty_tpl->getSubTemplate ("./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('products'=>$_smarty_tpl->tpl_vars['products']->value), 0);?>

			
			<div class="content_sortPagiBar bottom">
				<div class="sortPagiBar">
					<?php echo $_smarty_tpl->getSubTemplate ("./product-sort.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('paginationId'=>'bottom'), 0);?>

					<?php echo $_smarty_tpl->getSubTemplate ("./pagination.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('paginationId'=>'bottom'), 0);?>

				</div>				
			</div>
		<?php }?>
	<?php }elseif($_smarty_tpl->tpl_vars['category']->value->id){?>
		<p class="warning"><?php echo smartyTranslate(array('s'=>'This category is currently unavailable.'),$_smarty_tpl);?>
</p>
	<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['subcategories']->value)){?>
<script type="text/javascript">
// <![CDATA[
$(window).load(function(){
			//	Responsive layout, resizing the items
			$('ul#ul_subcategories').carouFredSel({
				responsive: true,
				width:'100%',
				prev: '#prev_sub_cat',
				next: '#next_sub_cat',
				auto: false,
				swipe: {
					onTouch : true
				},
				items: {
					width:140,
					height : 155,
					visible: {
						min: 2,
						max: 6
					}
				}
			});
		});
//]]>
</script>
<?php }?>
<?php }?>
<?php }} ?>