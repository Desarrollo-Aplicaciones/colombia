<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:32
         compiled from "/var/www/modules/csstaticblocks/views/templates/hook/csstaticblocks_homebottom.tpl" */ ?>
<?php /*%%SmartyHeaderCode:162782231453487320317997-01108283%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bafbd81776a31a0b4b092b6d5fdb4812f4f9607' => 
    array (
      0 => '/var/www/modules/csstaticblocks/views/templates/hook/csstaticblocks_homebottom.tpl',
      1 => 1382817529,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '162782231453487320317997-01108283',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'block_list' => 0,
    'cookie' => 0,
    'block' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_53487320330ea0_57145251',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53487320330ea0_57145251')) {function content_53487320330ea0_57145251($_smarty_tpl) {?><!-- Static Block module -->
<?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['block_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value){
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>
	<?php if (isset($_smarty_tpl->tpl_vars['block']->value->content[(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang])){?>
		<?php echo $_smarty_tpl->tpl_vars['block']->value->content[(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang];?>

	<?php }?>
<?php } ?>
<!-- /Static block module --><?php }} ?>