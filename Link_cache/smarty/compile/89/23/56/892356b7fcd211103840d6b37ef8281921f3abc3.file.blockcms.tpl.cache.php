<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:35
         compiled from "/var/www/themes/gomarket/modules/blockcms/blockcms.tpl" */ ?>
<?php /*%%SmartyHeaderCode:67285606534873232e6785-81572168%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '892356b7fcd211103840d6b37ef8281921f3abc3' => 
    array (
      0 => '/var/www/themes/gomarket/modules/blockcms/blockcms.tpl',
      1 => 1387578286,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '67285606534873232e6785-81572168',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'block' => 0,
    'cms_titles' => 0,
    'cms_key' => 0,
    'cms_title' => 0,
    'cms_page' => 0,
    'link' => 0,
    'PS_CATALOG_MODE' => 0,
    'cmslinks' => 0,
    'cmslink' => 0,
    'display_poweredby' => 0,
    'display_stores_footer' => 0,
    'contact_url' => 0,
    'footer_text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873233c6cb1_57976024',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873233c6cb1_57976024')) {function content_534873233c6cb1_57976024($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>

<?php if ($_smarty_tpl->tpl_vars['block']->value==1){?>
	<!-- Block CMS module -->
	<?php  $_smarty_tpl->tpl_vars['cms_title'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cms_title']->_loop = false;
 $_smarty_tpl->tpl_vars['cms_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cms_titles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cms_title']->key => $_smarty_tpl->tpl_vars['cms_title']->value){
$_smarty_tpl->tpl_vars['cms_title']->_loop = true;
 $_smarty_tpl->tpl_vars['cms_key']->value = $_smarty_tpl->tpl_vars['cms_title']->key;
?>
		<div id="informations_block_left_<?php echo $_smarty_tpl->tpl_vars['cms_key']->value;?>
" class="block informations_block_left">
			<h4 class="title_block"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cms_title']->value['category_link'], ENT_QUOTES, 'UTF-8', true);?>
"><?php if (!empty($_smarty_tpl->tpl_vars['cms_title']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['cms_title']->value['name'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['cms_title']->value['category_name'];?>
<?php }?></a></h4>
			<ul class="block_content">
				<?php  $_smarty_tpl->tpl_vars['cms_page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cms_page']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cms_title']->value['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cms_page']->key => $_smarty_tpl->tpl_vars['cms_page']->value){
$_smarty_tpl->tpl_vars['cms_page']->_loop = true;
?>
					<?php if (isset($_smarty_tpl->tpl_vars['cms_page']->value['link'])){?><li class="bullet"><b style="margin-left:2em;">
					<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cms_page']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['cms_page']->value['name'], 'html', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['cms_page']->value['name'], 'html', 'UTF-8');?>
</a>
					</b></li><?php }?>
				<?php } ?>
				<?php  $_smarty_tpl->tpl_vars['cms_page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cms_page']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cms_title']->value['cms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cms_page']->key => $_smarty_tpl->tpl_vars['cms_page']->value){
$_smarty_tpl->tpl_vars['cms_page']->_loop = true;
?>
					<?php if (isset($_smarty_tpl->tpl_vars['cms_page']->value['link'])){?><li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cms_page']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['cms_page']->value['meta_title'], 'html', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['cms_page']->value['meta_title'], 'html', 'UTF-8');?>
</a></li><?php }?>
				<?php } ?>
				<?php if ($_smarty_tpl->tpl_vars['cms_title']->value['display_store']){?><li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('stores'), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Our stores','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Our stores','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li><?php }?>
			</ul>
		</div>
	<?php } ?>
	<!-- /Block CMS module -->
<?php }else{ ?>
	<!-- MODULE Block footer -->
	<div class="block_various_links" id="block_various_links_footer">
		<div class="block">
		<h4 class="title_block"><?php echo smartyTranslate(array('s'=>'Information','mod'=>'blockcms'),$_smarty_tpl);?>
</h4>
		<a class="show_hide_footer" href="javascript:void(0)">icon</a>
		<ul class="f_block_content">
			<!--<?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?><li class="first_item"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('prices-drop');?>
" title="<?php echo smartyTranslate(array('s'=>'Specials','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Specials','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li><?php }?>-->
			<li class="item"><a href="http://www.farmalisto.com.co/content/8-que-es-farmalisto" target="_self" title=<?php echo smartyTranslate(array('s'=>'¿Qué es farmalisto?','mod'=>'blockcms'),$_smarty_tpl);?>
><?php echo smartyTranslate(array('s'=>'¿Qué es farmalisto?','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li>
			<li class="<?php if ($_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>first_<?php }?>item"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('new-products');?>
" title="<?php echo smartyTranslate(array('s'=>'New products','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'New products','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li>
			<?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?><li class="item"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('best-sales');?>
" title="<?php echo smartyTranslate(array('s'=>'Best sellers','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Best sellers','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li><?php }?>
			<?php  $_smarty_tpl->tpl_vars['cmslink'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cmslink']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cmslinks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cmslink']->key => $_smarty_tpl->tpl_vars['cmslink']->value){
$_smarty_tpl->tpl_vars['cmslink']->_loop = true;
?>
				<?php if ($_smarty_tpl->tpl_vars['cmslink']->value['meta_title']!=''){?>
					<li class="item"><a href="<?php echo addslashes($_smarty_tpl->tpl_vars['cmslink']->value['link']);?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['cmslink']->value['meta_title'], 'htmlall', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['cmslink']->value['meta_title'], 'htmlall', 'UTF-8');?>
</a></li>
				<?php }?>
			<?php } ?>
			<?php if ($_smarty_tpl->tpl_vars['display_poweredby']->value){?><li class="last_item"><?php echo smartyTranslate(array('s'=>'Powered by','mod'=>'blockcms'),$_smarty_tpl);?>
 <a class="_blank" href="http://www.prestashop.com">PrestaShop</a>&trade;</li><?php }?>
		</ul>
		</div>
		<div class="block">
		<h4 class="title_block"><?php echo smartyTranslate(array('s'=>'Su farmacia en Internet','mod'=>'blockcms'),$_smarty_tpl);?>
</h4>
		<a class="show_hide_footer" href="javascript:void(0)">icon</a>
		<ul class="f_block_content">
			<!--<?php if ($_smarty_tpl->tpl_vars['display_stores_footer']->value){?><li class="item"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('stores');?>
" title="<?php echo smartyTranslate(array('s'=>'Our stores','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Our stores','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li><?php }?>-->
			<li class="item"><a href="http://www.farmalisto.com.co/content/6-garantia-del-mejor-precio" target="_self" title="<?php echo smartyTranslate(array('s'=>'Garantía del mejor precio','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Garantía del mejor precio','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li>
			<li class="item"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink($_smarty_tpl->tpl_vars['contact_url']->value,true);?>
" title="<?php echo smartyTranslate(array('s'=>'Cont&aacute;ctenos','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Cont&aacute;ctenos','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('sitemap');?>
" title="<?php echo smartyTranslate(array('s'=>'Sitemap','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Sitemap','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li>
			<li class="item"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('manufacturer');?>
" title="<?php echo smartyTranslate(array('s'=>'Marcas','mod'=>'blockcms'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Marcas','mod'=>'blockcms'),$_smarty_tpl);?>
</a></li>
		</ul>
		</div>
	<?php echo $_smarty_tpl->tpl_vars['footer_text']->value;?>

	</div>
	<!-- /MODULE Block footer -->
<?php }?>
<?php }} ?>