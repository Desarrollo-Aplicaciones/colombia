<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:34
         compiled from "/var/www/themes/gomarket/modules/blockuserinfo/blockuserinfo.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3109187065348732284c707-71579540%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '042ebad04fa79b61ea0e1cf8695947ac1e8ff68b' => 
    array (
      0 => '/var/www/themes/gomarket/modules/blockuserinfo/blockuserinfo.tpl',
      1 => 1395429109,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3109187065348732284c707-71579540',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PS_CATALOG_MODE' => 0,
    'link' => 0,
    'order_process' => 0,
    'cart_qties' => 0,
    'logged' => 0,
    'cookie' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873228bf3c7_32083771',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873228bf3c7_32083771')) {function content_534873228bf3c7_32083771($_smarty_tpl) {?>

<!-- Block user information module HEADER -->
<div id="header_user" <?php if ($_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>class="header_user_catalog"<?php }?>>
	<ul id="header_nav">
		<li id="your_account"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true);?>
" title="<?php echo smartyTranslate(array('s'=>'View my customer account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" rel="nofollow"><?php echo smartyTranslate(array('s'=>'Mi Cuenta','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</a></li>
	</ul>
        <?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
		<div id="shopping_cart">
			<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink($_smarty_tpl->tpl_vars['order_process']->value,true);?>
&paso=inicial" title="<?php echo smartyTranslate(array('s'=>'View my shopping cart','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" style="width: 100%;" rel="nofollow"><span id="nombreCarrito"><?php echo smartyTranslate(array('s'=>'Cart','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</span>
			<span id="numero" class="ajax_cart_quantity<?php if ($_smarty_tpl->tpl_vars['cart_qties']->value==0){?> hidden<?php }?>">(<?php echo $_smarty_tpl->tpl_vars['cart_qties']->value;?>
)</span>
			<span id="numero" class="ajax_cart_no_product<?php if ($_smarty_tpl->tpl_vars['cart_qties']->value>0){?> hidden<?php }?>"><?php echo smartyTranslate(array('s'=>'(empty)','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</span>
			</a>
		</div>
        <?php }?>
	<p id="header_user_info">
		<?php if ($_smarty_tpl->tpl_vars['logged']->value){?>
			<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true);?>
" title="<?php echo smartyTranslate(array('s'=>'View my customer account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" class="account" rel="nofollow"><span><?php echo $_smarty_tpl->tpl_vars['cookie']->value->customer_firstname;?>
 <?php echo $_smarty_tpl->tpl_vars['cookie']->value->customer_lastname;?>
,</span></a>
			<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('index',true,null,"mylogout");?>
" title="<?php echo smartyTranslate(array('s'=>'Log me out','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" class="logout" rel="nofollow"><?php echo smartyTranslate(array('s'=>'Log out','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</a>
		<?php }else{ ?>
			<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true);?>
" title="<?php echo smartyTranslate(array('s'=>'Login to your customer account','mod'=>'blockuserinfo'),$_smarty_tpl);?>
" class="login" rel="nofollow"><?php echo smartyTranslate(array('s'=>'Login','mod'=>'blockuserinfo'),$_smarty_tpl);?>
</a> 
		<?php }?>
	</p>
</div>       
<!-- /Block user information module HEADER -->
<?php }} ?>