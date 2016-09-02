<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:35
         compiled from "/var/www/themes/gomarket/modules/blockcontactinfos/blockcontactinfos.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1161699213534873233dc959-59501273%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cc24da930735c7e7b401113bd2222fc4a6e1fe0a' => 
    array (
      0 => '/var/www/themes/gomarket/modules/blockcontactinfos/blockcontactinfos.tpl',
      1 => 1391038254,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1161699213534873233dc959-59501273',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blockcontactinfos_address' => 0,
    'blockcontactinfos_phone' => 0,
    'blockcontactinfos_email' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_53487323455e08_78790083',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53487323455e08_78790083')) {function content_53487323455e08_78790083($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
if (!is_callable('smarty_function_mailto')) include '/var/www/tools/smarty/plugins/function.mailto.php';
?>

<!-- MODULE Block contact infos -->
<div id="block_contact_infos">
	<h4 class="title_block"><?php echo smartyTranslate(array('s'=>'Acerca de Farmalisto','mod'=>'blockcontactinfos'),$_smarty_tpl);?>
</h4>
	<a class="show_hide_footer" href="javascript:void(0)">icon</a>
	<ul class="f_block_content">
		<?php if ($_smarty_tpl->tpl_vars['blockcontactinfos_address']->value!=''){?><li><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['blockcontactinfos_address']->value, 'htmlall', 'UTF-8');?>
</li><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['blockcontactinfos_phone']->value!=''){?><li><?php echo smartyTranslate(array('s'=>'Tel','mod'=>'blockcontactinfos'),$_smarty_tpl);?>
 <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['blockcontactinfos_phone']->value, 'htmlall', 'UTF-8');?>
</li><?php }?>
                <?php if ($_smarty_tpl->tpl_vars['blockcontactinfos_email']->value!=''){?><li><?php echo smartyTranslate(array('s'=>'Email','mod'=>'blockcontactinfos'),$_smarty_tpl);?>
 <?php echo smarty_function_mailto(array('address'=>smarty_modifier_escape($_smarty_tpl->tpl_vars['blockcontactinfos_email']->value, 'htmlall', 'UTF-8'),'encode'=>"hex"),$_smarty_tpl);?>
</li><?php }?>
	</ul>
</div>
<!-- /MODULE Block contact infos -->
<?php }} ?>