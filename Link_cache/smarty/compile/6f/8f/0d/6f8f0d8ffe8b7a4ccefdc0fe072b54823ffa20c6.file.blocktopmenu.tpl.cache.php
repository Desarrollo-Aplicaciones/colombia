<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:34
         compiled from "/var/www/themes/gomarket/modules/blocktopmenu/blocktopmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:38336402053487322e55d15-32865967%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f8f0d8ffe8b7a4ccefdc0fe072b54823ffa20c6' => 
    array (
      0 => '/var/www/themes/gomarket/modules/blocktopmenu/blocktopmenu.tpl',
      1 => 1397062492,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '38336402053487322e55d15-32865967',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MENU' => 0,
    'css_dir' => 0,
    'base_dir' => 0,
    'MENU_SEARCH' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_53487322e7ae61_08634742',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53487322e7ae61_08634742')) {function content_53487322e7ae61_08634742($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?><?php if ($_smarty_tpl->tpl_vars['MENU']->value!=''){?> 
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['css_dir']->value;?>
modules/blocktopmenu/css/opt-in.css" />
    
    <form method="post" style="display: inline-block;vertical-align: top;display: inline;float: right;margin-top: 11px;margin-top: 31px;float: left;" action="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
ajax_newsletter.php">       
        <div class="bloque1"><span class="texto-news1" style="color: #FFF;">SUSCRÍBETE A NUESTRO BOLETÍN</span></div>
        <div class="bloque1"><span class="texto-news1" style="color: #dee54b;">DE BIENESTAR Y SALUD</span></div>
        <div class="bloque1"><input type="text" placeholder="Ingresa tu E-mail aquí" name="mail" id="mail" /></div>
        <div class="bloque1">
            <div class="celda1"><input type="submit" name="hombre" id="hombre" value="HOMBRE" /></div>
            <div class="celda1"><input type="submit" name="mujer" id="mujer" value="MUJER" /></div>           
        </div>
    </form>
    
	<!-- Menu -->
	<div class="sf-contener clearfix">
		<ul class="sf-menu clearfix" id="menu_parent">
			<?php echo $_smarty_tpl->tpl_vars['MENU']->value;?>

			<?php if ($_smarty_tpl->tpl_vars['MENU_SEARCH']->value){?>
				<li class="sf-search noBack" style="float:right">
					<form id="searchbox" action="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('search');?>
" method="get">
						<p>
							<input type="hidden" name="controller" value="search" />
							<input type="hidden" value="position" name="orderby"/>
							<input type="hidden" value="desc" name="orderway"/>
							<input type="text" name="search_query" value="<?php if (isset($_GET['search_query'])){?><?php echo smarty_modifier_escape($_GET['search_query'], 'htmlall', 'UTF-8');?>
<?php }?>" />
						</p>
					</form>
				</li>
			<?php }?>
		</ul>
	</div>
	<div class="sf-right">&nbsp;</div>

	<!--/ Menu -->
	<script>$('ul#menu_parent > li').last().addClass("last");$('ul#menu_parent > li').first().addClass("first");</script>       
<?php }?><?php }} ?>