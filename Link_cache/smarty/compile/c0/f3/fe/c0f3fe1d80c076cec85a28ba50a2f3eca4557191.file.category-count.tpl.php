<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 18:04:05
         compiled from "/var/www/themes/gomarket/category-count.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1754270128534874e50e4e27-68872980%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0f3fe1d80c076cec85a28ba50a2f3eca4557191' => 
    array (
      0 => '/var/www/themes/gomarket/category-count.tpl',
      1 => 1393868962,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1754270128534874e50e4e27-68872980',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'category' => 0,
    'nb_products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534874e5100916_43124135',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534874e5100916_43124135')) {function content_534874e5100916_43124135($_smarty_tpl) {?>
<?php if ($_smarty_tpl->tpl_vars['category']->value->id==1||$_smarty_tpl->tpl_vars['nb_products']->value==0){?>
	<?php echo smartyTranslate(array('s'=>'There are no products.'),$_smarty_tpl);?>

<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['nb_products']->value==1){?>
		<?php echo smartyTranslate(array('s'=>'There is %d product.','sprintf'=>$_smarty_tpl->tpl_vars['nb_products']->value),$_smarty_tpl);?>

	<?php }else{ ?>
		<?php echo smartyTranslate(array('s'=>'There are %d products.','sprintf'=>$_smarty_tpl->tpl_vars['nb_products']->value),$_smarty_tpl);?>

	<?php }?>
<?php }?><?php }} ?>