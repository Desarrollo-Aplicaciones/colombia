<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:35
         compiled from "/var/www/modules/csstaticblocks/views/templates/hook/csstaticblocks_rightcolumn.tpl" */ ?>
<?php /*%%SmartyHeaderCode:148040017453487323276146-95580485%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '20fcb5db0e8ab4ce2482081198cd575929a437a8' => 
    array (
      0 => '/var/www/modules/csstaticblocks/views/templates/hook/csstaticblocks_rightcolumn.tpl',
      1 => 1382817531,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '148040017453487323276146-95580485',
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
  'unifunc' => 'content_53487323291912_78774698',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53487323291912_78774698')) {function content_53487323291912_78774698($_smarty_tpl) {?><!-- Static Block module -->
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