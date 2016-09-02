<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 18:07:12
         compiled from "/var/www/admin8256/themes/default/template/helpers/list/list_action_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:167457089534875a01701c4-73881963%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b5dc63abf3fd673da65203a684caa230932742d' => 
    array (
      0 => '/var/www/admin8256/themes/default/template/helpers/list/list_action_edit.tpl',
      1 => 1381346077,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '167457089534875a01701c4-73881963',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534875a019d982_82913885',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534875a019d982_82913885')) {function content_534875a019d982_82913885($_smarty_tpl) {?>
<a href="<?php echo $_smarty_tpl->tpl_vars['href']->value;?>
" class="edit" title="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
	<img src="../img/admin/edit.gif" alt="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" />
</a><?php }} ?>