<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:36
         compiled from "/var/www/themes/gomarket/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:163432482253487324ee8680-85191420%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '049360890cdc05cffa858f5f47b16870b4a440ef' => 
    array (
      0 => '/var/www/themes/gomarket/index.tpl',
      1 => 1393868966,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '163432482253487324ee8680-85191420',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'HOOK_HOME' => 0,
    'HOOK_CS_HOME_BOTTOM' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_53487324f26162_83461624',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53487324f26162_83461624')) {function content_53487324f26162_83461624($_smarty_tpl) {?>

<?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

<?php if (isset($_smarty_tpl->tpl_vars['HOOK_CS_HOME_BOTTOM']->value)&&$_smarty_tpl->tpl_vars['HOOK_CS_HOME_BOTTOM']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_CS_HOME_BOTTOM']->value;?>
<?php }?>
<?php }} ?>