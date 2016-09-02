<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 18:07:42
         compiled from "/var/www/themes/gomarket/shopping-cart.tpl" */ ?>
<?php /*%%SmartyHeaderCode:735198174534875be220518-14989711%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51a10723789a436a63a01559034ccdba8b6d50db' => 
    array (
      0 => '/var/www/themes/gomarket/shopping-cart.tpl',
      1 => 1397062848,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '735198174534875be220518-14989711',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'img_dir' => 0,
    'account_created' => 0,
    'empty' => 0,
    'PS_CATALOG_MODE' => 0,
    'currencySign' => 0,
    'currencyRate' => 0,
    'currencyFormat' => 0,
    'currencyBlank' => 0,
    'cart' => 0,
    'lastProductAdded' => 0,
    'link' => 0,
    'productNumber' => 0,
    'products' => 0,
    'product' => 0,
    'odd' => 0,
    'productId' => 0,
    'productAttributeId' => 0,
    'customizedDatas' => 0,
    'gift_products' => 0,
    'id_customization' => 0,
    'customization' => 0,
    'type' => 0,
    'CUSTOMIZE_FILE' => 0,
    'custom_data' => 0,
    'pic_dir' => 0,
    'picture' => 0,
    'CUSTOMIZE_TEXTFIELD' => 0,
    'textField' => 0,
    'cannotModify' => 0,
    'quantityDisplayed' => 0,
    'token_cart' => 0,
    'last_was_odd' => 0,
    'voucherAllowed' => 0,
    'errors_discount' => 0,
    'error' => 0,
    'opc' => 0,
    'discount_name' => 0,
    'displayVouchers' => 0,
    'voucher' => 0,
    'use_taxes' => 0,
    'priceDisplay' => 0,
    'display_tax_label' => 0,
    'total_products' => 0,
    'total_products_wt' => 0,
    'total_discounts' => 0,
    'total_discounts_tax_exc' => 0,
    'total_discounts_negative' => 0,
    'total_wrapping' => 0,
    'total_wrapping_tax_exc' => 0,
    'total_shipping_tax_exc' => 0,
    'virtualCart' => 0,
    'carrier' => 0,
    'total_shipping' => 0,
    'total_price_without_tax' => 0,
    'total_tax' => 0,
    'total_price' => 0,
    'discounts' => 0,
    'discount' => 0,
    'show_option_allow_separate_package' => 0,
    'multi_shipping' => 0,
    'HOOK_SHOPPING_CART' => 0,
    'addresses_style' => 0,
    'back' => 0,
    'HOOK_SHOPPING_CART_EXTRA' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534875be74e3a0_18018580',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534875be74e3a0_18018580')) {function content_534875be74e3a0_18018580($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>





<style type="text/css"> 
#cupon{
  width: 32%;
  height: auto;
  margin: 3px 3px;
  padding: 5px 3px;
  float: left;
  min-width: 120px;
} 


#boxmedisp
{
    
  width: 27%;
  height: auto;
  margin: 3px 3px;
  padding: 5px 1px;
  float: left;
  min-width: 200px;
} 

#boxnefi
{
    
  width: 28%;
  height: auto;
  margin: 3px 3px;
  padding: 5px 1px;
  float: left;
  min-width: 200px;
} 

#imgenvio
{
width:30px;
height: 15px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/g644.png)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}


#imgdiscr
{
width:20px;
height: 26px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/g648.png)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}


#imgprecio
{
width:26px;
height: 15px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/g652.png)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}


#imgamex
{
width:51px;
height: 32px;
margin: 5px 5px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/amex.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}


#imgvisa
{
width:51px;
height: 32px;
margin: 5px 5px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/visa.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}

#imgmaster

{
    
width:51px;
height: 32px;
margin: 5px 5px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/master.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}

#imgdiners{
width:51px;
height: 32px;
margin: 5px 5px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/diners.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;

}

#imgpse{
 width:33px;
height: 33px;
margin: 5px 5px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/pse.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;

}


#imgbaloto{
    width:22px;
height: 33px;
margin: 5px 5px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/baloto.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;

}

#imgcod{
    width:51px;
height: auto;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
mediosp/cod.jpg)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;

}   




    
    #processCarrier
{
 padding: 0 0;    
width:145px;
height:40px;
border:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
formula-medica/btn-continuar.png)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;margin-top: 137px;
}

#processCarrier:hover
{
padding: 0 0;    
width:145px;
height:40px;
border:none;
border-style: none;

background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
formula-medica/btn-continuar-hover.png)no-repeat top center !important;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;margin-top: 137px;
}



#atras1
{
padding: 0 0;  
width:145px;
height:40px;

animation: none !important;
border:none;
transition:none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
formula-medica/btn-anterior.png)no-repeat top center;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;margin-top: 137px;
}
#atras1:hover
{
    
padding: 0 0;

animation: none !important;
transition:none;
width:145px;
height:40px;
border:none;
border-style: none;
background:url(<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
formula-medica/btn-anterior-hover.png)no-repeat top center !important;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;margin-top: 137px;
}



</style><style type="text/css">
#productoLabel{width: 125px;float: left;font-weight: 700;margin-top: 7px;color: #969696 !important;background: #666666;background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));background: -moz-linear-gradient(#E6E6E6, #fff);background: linear-gradient(#E6E6E6, #fff);-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 0px 0px 3px #333;-webkit-box-shadow: #666 0px 2px 3px;-moz-box-shadow: #666 0px 2px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-radius: 3px 3px 3px 3px;font-weight: 100;font-size: 13px;text-transform: capitalize;text-align: center;margin: 7px 41px 0 0;}
#descripcionLabel{width: 125px;float: left;font-weight: 700;margin-top: 7px;color: #969696 !important;background: #666666;background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));background: -moz-linear-gradient(#E6E6E6, #fff);background: linear-gradient(#E6E6E6, #fff);-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 0px 0px 3px #333;-webkit-box-shadow: #666 0px 2px 3px;-moz-box-shadow: #666 0px 2px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-radius: 3px 3px 3px 3px;font-weight: 100;font-size: 13px;text-transform: capitalize;text-align: center;margin: 7px 41px 0 0;}
#referenciaLabel{width: 125px;float: left;font-weight: 700;margin-top: 7px;color: #969696 !important;background: #666666;background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));background: -moz-linear-gradient(#E6E6E6, #fff);background: linear-gradient(#E6E6E6, #fff);-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 0px 0px 3px #333;-webkit-box-shadow: #666 0px 2px 3px;-moz-box-shadow: #666 0px 2px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-radius: 3px 3px 3px 3px;font-weight: 100;font-size: 13px;text-transform: capitalize;text-align: center;margin: 7px 41px 0 0;}
#precioLabel{width: 125px;float: left;font-weight: 700;margin-top: 7px;color: #969696 !important;background: #666666;background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));background: -moz-linear-gradient(#E6E6E6, #fff);background: linear-gradient(#E6E6E6, #fff);-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 0px 0px 3px #333;-webkit-box-shadow: #666 0px 2px 3px;-moz-box-shadow: #666 0px 2px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-radius: 3px 3px 3px 3px;font-weight: 100;font-size: 13px;text-transform: capitalize;text-align: center;margin: 7px 41px 0 0;}
#cantidadLabel{width: 125px;float: left;font-weight: 700;margin-top: 7px;color: #969696 !important;background: #666666;background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));background: -moz-linear-gradient(#E6E6E6, #fff);background: linear-gradient(#E6E6E6, #fff);-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 0px 0px 3px #333;-webkit-box-shadow: #666 0px 2px 3px;-moz-box-shadow: #666 0px 2px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-radius: 3px 3px 3px 3px;font-weight: 100;font-size: 13px;text-transform: capitalize;text-align: center;margin: 7px 41px 0 0;}
#totaLabel{width: 125px;font-weight: 700;margin-top: 7px;color: #969696 !important;background: #666666;background: -webkit-gradient(linear, 0 0, 0 bottom, from(#E6E6E6), to(#fff));background: -moz-linear-gradient(#E6E6E6, #fff);background: linear-gradient(#E6E6E6, #fff);-pie-background: linear-gradient(#E6E6E6, #646464);box-shadow: 0px 0px 3px #333;-webkit-box-shadow: #666 0px 2px 3px;-moz-box-shadow: #666 0px 2px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-radius: 3px 3px 3px 3px;font-weight: 100;font-size: 13px;text-transform: capitalize;text-align: center;margin: 7px 41px 0 0;}
#imagenProductoCompra{float:left;width: 175px;}
#contenedor3contenidos{border-radius: 6px; border: 1px solid #E7E7E7; xborder-collapse: collapse; width:715px; height:154px; float:left;margin: 0 10px 0 0;}
#primerLabel{padding: 14px 70px; color:#676767; text-align: right;}
#segundoLabel{text-align: left;margin-top: 102px;}
#tercerLabel{margin-top: -17px;margin-left: 827px;}
#cuartoLabel{margin-top: -17px;margin-left: 888px;}
#quintoLabel{margin-top: -36px;margin-left: 938px;}
#sextoLabel{margin-top: -13px;margin-right: -10px;}
#precioContenedor{float: right;margin-top: -30px;}
#total_tax{margin-top:-28px; color:#676767; text-align: right;}
#total_product{margin-top: -30px;color: #676767;text-align: right;float: right;}
#total_price_without_tax{margin-top:-28px; color:#676767; text-align: right;}
#descuentoValor{text-align: right;margin-right: 59px;color:#676767; }
#descripcioNombre {margin-left: 0px!important;}


@media only screen and (min-width: 768px) and (max-width: 1000px) {
	
		#productoLabel {width: 113px;margin-top: 38px;margin: 38px 7px 0 0;}
		#referenciaLabel {width: 125px;margin-top: 38px;margin: 38px 7px 0 0;}
		#precioLabel {width: 125px;margin-top: 38px;margin: 38px 7px 0 0;}
		#descripcionLabel {width: 125px!important;margin-top: 38px;margin: 38px 7px 0 0;}
		#cantidadLabel{margin-top: 38px;width: 125px;margin: 38px 7px 0 0;}
		#totaLabel{margin-top: 38px;width: 113px}
		.cart_description  {width: 61px!important;margin: 0px 23px 0px 0px;}
		#descripcioNombre{margin-left: -15px!important;float: left;}
		#cantidades{margin-left: -27px!important;margin:38px 7px 0px 0px!important;text-align: left;}
		.cart_ref {width: 111px!important;margin:38px 7px 0px 0px!important;}
		.cart_unit {margin-left: -30px;margin:38px 7px 0px 0px!important;text-align: center;}
		.cart_total .price {margin-left: 25px!important;margin: 38px 7px 0px 95px!important;text-align: center;}
		.cart_delete a.cart_quantity_delete, a.price_discount_delete{margin-right: 253px!important;}
		#atras1{margin: 48px 0 26px 0;margin-top: 132px!important;}
		#processCarrier{margin: 48px 0 26px 0;margin-top: 132px!important;}
		#atras1:hover{margin: 48px 0 26px 0;margin-top: 132px!important;}
		#processCarrier:hover{margin: 48px 0 26px 0;margin-top: 132px!important;}
		#primerLabel{margin-left: 440px;padding: 10px;float: left;}
		#contenedor3contenidos{width:746px;}	
        #precioContenedor{margin-top:19px;}
        #total_tax{margin-top: 10px;color: #676767;text-align: left;margin-left: 179px;float: left;}        
		#total_product{margin-top: 14px;color: #676767;text-align: left;float: left;margin-left: 12px;}
		#total_price_without_tax{margin-top: 11px;text-align: left;float: left;margin-left: 136px;}
		#descuentoValor{text-align: right;margin-right: 136px;}
		#segundoLabel {margin-top: -52px;}
		#tercerLabel {margin-left: 452px;}
		#cuartoLabel {margin-left: 548px;}
		#quintoLabel {margin-left: 697px;}
		#sextoLabel {margin-right: 369px;}
		#descuentoLabel{margin-top: 200px;}
		#cajon{display: flex;}
		#imagenProductoCompra {float: left;width: 157px;}

}

@media only screen and (min-width: 480px) and (max-width: 767px) {

		#tercerLabel {margin-left: 240px;}
		#cuartoLabel {margin-left: 299px;}
		#quintoLabel {margin-left: 352px;}
		#sextoLabel {margin-right: 293px;}
		ul#order_step {width: 100%;}
		#productoLabel {width: 125px;}
		#referenciaLabel {width: 125px;margin-top: 70px;}
		#precioLabel{margin-top: 20px;margin-left: 0px;}
		#cantidadLabel{margin-top: 20px;margin-left: 0px;}
		#totaLabel{margin-top: 20px;}
		#imagenProductoCompra {width: 82px;margin-left: -140px;margin-top: -17px;}
		.cart_description p.s_title_block{margin-top: -12px;margin-left: -61px;width: 89px!important;}
		#descripcioNombre {margin-left: 47px!important;float:left;font-size: 11px!important;margin-top: 13px!important;}
		.cart_description p.s_title_block a{width: 74px!important;}
		.cart_description {width: 74px!important;}
		#descripcionLabel{width:125px!important;margin-top: 88px!important;margin-left: 2px!important;}
		.cart_ref{font-size: 11px;}
		.cart_product{margin-left: 149px;}
		.totaLabel{margin-left: -34px;width: 125px;}
		#cajon{display: table-caption;width: 134px;height: 330px;float: left;position: relative;}
		#contenedorProductos{width: 305px;height: 354px;display: inline-flex;margin: 0 0 20px 0;overflow-x: scroll;}

}
@media screen and (max-width:480px){

		#tercerLabel {margin-left: 240px;}
		#cuartoLabel {margin-left: 299px;}
		#quintoLabel {margin-left: 352px;}
		#sextoLabel {margin-right: 293px;}
		ul#order_step {width: 100%;}
		#productoLabel {width: 125px;}
		#referenciaLabel {width: 125px;margin-top: 70px;}
		#precioLabel{margin-top: 20px;margin-left: 0px;}
		#cantidadLabel{margin-top: 20px;margin-left: 0px;}
		#totaLabel{margin-top: 20px;}
		#imagenProductoCompra {width: 82px;margin-left: -140px;margin-top: -17px;}
		.cart_description p.s_title_block{margin-top: -12px;margin-left: -61px;width: 89px!important;}
		#descripcioNombre {margin-left: 47px!important;float:left;font-size: 11px!important;margin-top: 13px!important;}
		.cart_description p.s_title_block a{width: 74px!important;}
		.cart_description {width: 74px!important;}
		#descripcionLabel{width:125px!important;margin-top: 88px!important;margin-left: 0px!important;}
		.cart_ref{font-size: 11px;}
		.cart_product{margin-left: 149px;}
		.totaLabel{margin-left: -34px;width: 125px;}
		#cajon{display: table-caption;width: 134px;height: 330px;float: left;position: relative;}
		#contenedorProductos{width: 305px;height: 354px;display: inline-flex;margin: 0 0 20px 0;overflow-x: scroll;}

}

</style>


<script type="text/javascript" src="js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />


<h1 id="cart_title" style="color:#9C9C9C; font-size: 24px; font-family:Verdana; ">Mi Carrito</h1> 

<?php if (isset($_smarty_tpl->tpl_vars['account_created']->value)){?>
	<p class="success">
		<?php echo smartyTranslate(array('s'=>'Your account has been created.'),$_smarty_tpl);?>

	</p>
<?php }?>
<?php $_smarty_tpl->tpl_vars['current_step'] = new Smarty_variable('summary', null, 0);?>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./order-steps.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if (isset($_smarty_tpl->tpl_vars['empty']->value)){?>
	<p class="warning"><?php echo smartyTranslate(array('s'=>'Your shopping cart is empty.'),$_smarty_tpl);?>
</p>
<?php }elseif($_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
	<p class="warning"><?php echo smartyTranslate(array('s'=>'This store has not accepted your new order.'),$_smarty_tpl);?>
</p>
<?php }else{ ?>
	<script type="text/javascript">
	// <![CDATA[
	var currencySign = '<?php echo html_entity_decode($_smarty_tpl->tpl_vars['currencySign']->value,2,"UTF-8");?>
';
	var currencyRate = '<?php echo floatval($_smarty_tpl->tpl_vars['currencyRate']->value);?>
';
	var currencyFormat = '<?php echo intval($_smarty_tpl->tpl_vars['currencyFormat']->value);?>
';
	var currencyBlank = '<?php echo intval($_smarty_tpl->tpl_vars['currencyBlank']->value);?>
';
	var txtProduct = "<?php echo smartyTranslate(array('s'=>'product','js'=>1),$_smarty_tpl);?>
";
	var txtProducts = "<?php echo smartyTranslate(array('s'=>'products','js'=>1),$_smarty_tpl);?>
";
	var deliveryAddress = <?php echo intval($_smarty_tpl->tpl_vars['cart']->value->id_address_delivery);?>
;
	// ]]>
	</script>
	<p style="display:none" id="emptyCartWarning" class="warning"><?php echo smartyTranslate(array('s'=>'Your shopping cart is empty.'),$_smarty_tpl);?>
</p>
<!--
        <?php if (isset($_smarty_tpl->tpl_vars['lastProductAdded']->value)&&$_smarty_tpl->tpl_vars['lastProductAdded']->value){?>
	<div class="cart_last_product">
		<div class="cart_last_product_header">
			<div class="left"><?php echo smartyTranslate(array('s'=>'Last product added'),$_smarty_tpl);?>
</div>
		</div>
		<a  class="cart_last_product_img" href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['lastProductAdded']->value['id_product'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['link_rewrite'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['category'],null,null,$_smarty_tpl->tpl_vars['lastProductAdded']->value['id_shop']), 'htmlall', 'UTF-8');?>
"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['lastProductAdded']->value['link_rewrite'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['id_image'],'small_default'), ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['lastProductAdded']->value['name'], 'htmlall', 'UTF-8');?>
"/></a>
		<div class="cart_last_product_content">
			<p class="s_title_block"><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['lastProductAdded']->value['id_product'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['link_rewrite'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['category'],null,null,null,$_smarty_tpl->tpl_vars['lastProductAdded']->value['id_product_attribute']), 'htmlall', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['lastProductAdded']->value['name'], 'htmlall', 'UTF-8');?>
</a></p>
			<?php if (isset($_smarty_tpl->tpl_vars['lastProductAdded']->value['attributes'])&&$_smarty_tpl->tpl_vars['lastProductAdded']->value['attributes']){?><a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['lastProductAdded']->value['id_product'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['link_rewrite'],$_smarty_tpl->tpl_vars['lastProductAdded']->value['category'],null,null,null,$_smarty_tpl->tpl_vars['lastProductAdded']->value['id_product_attribute']), 'htmlall', 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['lastProductAdded']->value['attributes'], 'htmlall', 'UTF-8');?>
</a><?php }?>
		</div>
		<br class="clear" />
	</div> 
<?php }?>
-->
<!-- <p><?php echo smartyTranslate(array('s'=>'Your shopping cart contains:'),$_smarty_tpl);?>
 <span id="summary_products_quantity"><?php echo $_smarty_tpl->tpl_vars['productNumber']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['productNumber']->value==1){?><?php echo smartyTranslate(array('s'=>'product'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'products'),$_smarty_tpl);?>
<?php }?></span></p> 
<p>su carrito contiene: <span id="summary_products_quantity"><?php echo $_smarty_tpl->tpl_vars['productNumber']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['productNumber']->value==1){?><?php echo smartyTranslate(array('s'=>'product'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'products'),$_smarty_tpl);?>
<?php }?></span></p>
-->

<div id="order-detail-content" class="table_block">
	
    <div id="cart_summary" class="std" style="margin-top: 113px;margin-bottom: 20px;width: 100%;border-collapse: inherit;border-radius: 2px;-moz-border-radius: 2px;box-shadow: 0 0 0 transparent;margin-top: 181px;overflow: hidden;">
          
		<div class="m_hide" style="width: 1000px;height: auto;">
			<div id="cajon">
				<div class="cart_product first_item" id="productoLabel"><?php echo smartyTranslate(array('s'=>'Product'),$_smarty_tpl);?>
</div>
				<div class="cart_description item" id="descripcionLabel"><?php echo smartyTranslate(array('s'=>'Description'),$_smarty_tpl);?>
</div>
				<div class="cart_ref item" id="referenciaLabel"><?php echo smartyTranslate(array('s'=>'Ref.'),$_smarty_tpl);?>
</div>
				<div class="cart_unit item" id="precioLabel"><?php echo smartyTranslate(array('s'=>'Unit price'),$_smarty_tpl);?>
</div>
				<div class="cart_quantity item" id="cantidadLabel"><?php echo smartyTranslate(array('s'=>'Qty'),$_smarty_tpl);?>
</div>
				<div class="cart_total item" id="totaLabel"><?php echo smartyTranslate(array('s'=>'Total'),$_smarty_tpl);?>
</div>
				<div class="cart_delete last_item">&nbsp;</div>
			</div>
		
                
                
                <div id="contenedorProductos" >
		<?php $_smarty_tpl->tpl_vars['odd'] = new Smarty_variable(0, null, 0);?>
		<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['product']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['product']->iteration=0;
 $_smarty_tpl->tpl_vars['product']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['product']->iteration++;
 $_smarty_tpl->tpl_vars['product']->index++;
 $_smarty_tpl->tpl_vars['product']->first = $_smarty_tpl->tpl_vars['product']->index === 0;
 $_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
?>
			<?php $_smarty_tpl->tpl_vars['productId'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value['id_product'], null, 0);?>
			<?php $_smarty_tpl->tpl_vars['productAttributeId'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], null, 0);?>
			<?php $_smarty_tpl->tpl_vars['quantityDisplayed'] = new Smarty_variable(0, null, 0);?>
			<?php $_smarty_tpl->tpl_vars['odd'] = new Smarty_variable(($_smarty_tpl->tpl_vars['odd']->value+1)%2, null, 0);?>
			<?php $_smarty_tpl->tpl_vars['ignoreProductLast'] = new Smarty_variable(isset($_smarty_tpl->tpl_vars['customizedDatas']->value[$_smarty_tpl->tpl_vars['productId']->value][$_smarty_tpl->tpl_vars['productAttributeId']->value])||count($_smarty_tpl->tpl_vars['gift_products']->value), null, 0);?>
			
			<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./shopping-cart-product-line.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('productLast'=>$_smarty_tpl->tpl_vars['product']->last,'productFirst'=>$_smarty_tpl->tpl_vars['product']->first), 0);?>

                        
                        
			
			<?php if (isset($_smarty_tpl->tpl_vars['customizedDatas']->value[$_smarty_tpl->tpl_vars['productId']->value][$_smarty_tpl->tpl_vars['productAttributeId']->value])){?>
				<?php  $_smarty_tpl->tpl_vars['customization'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['customization']->_loop = false;
 $_smarty_tpl->tpl_vars['id_customization'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['customizedDatas']->value[$_smarty_tpl->tpl_vars['productId']->value][$_smarty_tpl->tpl_vars['productAttributeId']->value][$_smarty_tpl->tpl_vars['product']->value['id_address_delivery']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['customization']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['customization']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['customization']->key => $_smarty_tpl->tpl_vars['customization']->value){
$_smarty_tpl->tpl_vars['customization']->_loop = true;
 $_smarty_tpl->tpl_vars['id_customization']->value = $_smarty_tpl->tpl_vars['customization']->key;
 $_smarty_tpl->tpl_vars['customization']->iteration++;
 $_smarty_tpl->tpl_vars['customization']->last = $_smarty_tpl->tpl_vars['customization']->iteration === $_smarty_tpl->tpl_vars['customization']->total;
?>
				<div style="border: solid #D8000C;" id="product_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
" class="product_customization_for_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
 <?php if ($_smarty_tpl->tpl_vars['odd']->value){?>odd<?php }else{ ?>even<?php }?> customization alternate_item <?php if ($_smarty_tpl->tpl_vars['product']->last&&$_smarty_tpl->tpl_vars['customization']->last&&!count($_smarty_tpl->tpl_vars['gift_products']->value)){?>last_item<?php }?>">
						
						<div >
							<?php  $_smarty_tpl->tpl_vars['custom_data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['custom_data']->_loop = false;
 $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['customization']->value['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['custom_data']->key => $_smarty_tpl->tpl_vars['custom_data']->value){
$_smarty_tpl->tpl_vars['custom_data']->_loop = true;
 $_smarty_tpl->tpl_vars['type']->value = $_smarty_tpl->tpl_vars['custom_data']->key;
?>
								<?php if ($_smarty_tpl->tpl_vars['type']->value==$_smarty_tpl->tpl_vars['CUSTOMIZE_FILE']->value){?>
									<div class="customizationUploaded">
										<ul class="customizationUploaded">
											<?php  $_smarty_tpl->tpl_vars['picture'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['picture']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['custom_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['picture']->key => $_smarty_tpl->tpl_vars['picture']->value){
$_smarty_tpl->tpl_vars['picture']->_loop = true;
?>
												<li><img src="<?php echo $_smarty_tpl->tpl_vars['pic_dir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['picture']->value['value'];?>
_small" alt="" class="customizationUploaded" /></li>
											<?php } ?>
										</ul>
									</div>
								<?php }elseif($_smarty_tpl->tpl_vars['type']->value==$_smarty_tpl->tpl_vars['CUSTOMIZE_TEXTFIELD']->value){?>
									<ul class="typedText">
										<?php  $_smarty_tpl->tpl_vars['textField'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['textField']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['custom_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['textField']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['textField']->key => $_smarty_tpl->tpl_vars['textField']->value){
$_smarty_tpl->tpl_vars['textField']->_loop = true;
 $_smarty_tpl->tpl_vars['textField']->index++;
?>
											<li>
												<?php if ($_smarty_tpl->tpl_vars['textField']->value['name']){?>
													<?php echo $_smarty_tpl->tpl_vars['textField']->value['name'];?>

												<?php }else{ ?>
													<?php echo smartyTranslate(array('s'=>'Text #'),$_smarty_tpl);?>
<?php echo $_smarty_tpl->tpl_vars['textField']->index+1;?>

												<?php }?>
												<?php echo smartyTranslate(array('s'=>':'),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['textField']->value['value'];?>

											</li>
										<?php } ?>
										
									</ul>
								<?php }?>

							<?php } ?>
						</div>
						<div class="cart_quantity">
							<?php if (isset($_smarty_tpl->tpl_vars['cannotModify']->value)&&$_smarty_tpl->tpl_vars['cannotModify']->value==1){?>
								<span style="float:left"><?php if ($_smarty_tpl->tpl_vars['quantityDisplayed']->value==0&&isset($_smarty_tpl->tpl_vars['customizedDatas']->value[$_smarty_tpl->tpl_vars['productId']->value][$_smarty_tpl->tpl_vars['productAttributeId']->value])){?><?php echo count($_smarty_tpl->tpl_vars['customizedDatas']->value[$_smarty_tpl->tpl_vars['productId']->value][$_smarty_tpl->tpl_vars['productAttributeId']->value]);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['product']->value['cart_quantity']-$_smarty_tpl->tpl_vars['quantityDisplayed']->value;?>
<?php }?></span>
							<?php }else{ ?>
								<div class="cart_quantity_button">
								<a rel="nofollow" class="cart_quantity_up" id="cart_quantity_up_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product_attribute']);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',true,null,"add&amp;id_product=".$_tmp1."&amp;ipa=".$_tmp2."&amp;id_address_delivery=".((string)$_smarty_tpl->tpl_vars['product']->value['id_address_delivery'])."&amp;id_customization=".((string)$_smarty_tpl->tpl_vars['id_customization']->value)."&amp;token=".((string)$_smarty_tpl->tpl_vars['token_cart']->value));?>
" title="<?php echo smartyTranslate(array('s'=>'Add'),$_smarty_tpl);?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/quantity_up.gif" alt="<?php echo smartyTranslate(array('s'=>'Add'),$_smarty_tpl);?>
" width="11" height="11" /></a><br />
								<?php if ($_smarty_tpl->tpl_vars['product']->value['minimal_quantity']<($_smarty_tpl->tpl_vars['customization']->value['quantity']-$_smarty_tpl->tpl_vars['quantityDisplayed']->value)||$_smarty_tpl->tpl_vars['product']->value['minimal_quantity']<=1){?>
								<a rel="nofollow" class="cart_quantity_down" id="cart_quantity_down_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product_attribute']);?>
<?php $_tmp4=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',true,null,"add&amp;id_product=".$_tmp3."&amp;ipa=".$_tmp4."&amp;id_address_delivery=".((string)$_smarty_tpl->tpl_vars['product']->value['id_address_delivery'])."&amp;id_customization=".((string)$_smarty_tpl->tpl_vars['id_customization']->value)."&amp;op=down&amp;token=".((string)$_smarty_tpl->tpl_vars['token_cart']->value));?>
" title="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/quantity_down.gif" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" width="11" height="11" />
								</a>
								<?php }else{ ?>
								<a class="cart_quantity_down" style="opacity: 0.3;" id="cart_quantity_down_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
" href="#" title="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
icon/quantity_down.gif" alt="<?php echo smartyTranslate(array('s'=>'Subtract'),$_smarty_tpl);?>
" width="11" height="11" />
								</a>
								<?php }?>
								<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['customization']->value['quantity'];?>
" name="quantity_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
_hidden"/>
								<input size="2" type="text" value="<?php echo $_smarty_tpl->tpl_vars['customization']->value['quantity'];?>
" class="cart_quantity_input" name="quantity_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
"/>
								</div>
								
							<?php }?>
						</div>
						<div class="cart_delete">
							<?php if (isset($_smarty_tpl->tpl_vars['cannotModify']->value)&&$_smarty_tpl->tpl_vars['cannotModify']->value==1){?>
							<?php }else{ ?>
								<div>
									<a rel="nofollow" class="cart_quantity_delete" id="<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
_<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'];?>
_<?php echo $_smarty_tpl->tpl_vars['id_customization']->value;?>
_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_address_delivery']);?>
" href="<?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
<?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product_attribute']);?>
<?php $_tmp6=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('cart',true,null,"delete&amp;id_product=".$_tmp5."&amp;ipa=".$_tmp6."&amp;id_customization=".((string)$_smarty_tpl->tpl_vars['id_customization']->value)."&amp;id_address_delivery=".((string)$_smarty_tpl->tpl_vars['product']->value['id_address_delivery'])."&amp;token=".((string)$_smarty_tpl->tpl_vars['token_cart']->value));?>
"><?php echo smartyTranslate(array('s'=>'Delete'),$_smarty_tpl);?>
</a>
								</div>
							<?php }?>
						</div>
					</div>
					<?php $_smarty_tpl->tpl_vars['quantityDisplayed'] = new Smarty_variable($_smarty_tpl->tpl_vars['quantityDisplayed']->value+$_smarty_tpl->tpl_vars['customization']->value['quantity'], null, 0);?>
				<?php } ?>
				                           
                                
				<?php if ($_smarty_tpl->tpl_vars['product']->value['quantity']-$_smarty_tpl->tpl_vars['quantityDisplayed']->value>0){?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./shopping-cart-product-line.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('productLast'=>$_smarty_tpl->tpl_vars['product']->last,'productFirst'=>$_smarty_tpl->tpl_vars['product']->first), 0);?>
<?php }?>
			<?php }?>
		<?php } ?>
                
                
		<?php $_smarty_tpl->tpl_vars['last_was_odd'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->iteration%2, null, 0);?>
		<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gift_products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['product']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['product']->iteration=0;
 $_smarty_tpl->tpl_vars['product']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['product']->iteration++;
 $_smarty_tpl->tpl_vars['product']->index++;
 $_smarty_tpl->tpl_vars['product']->first = $_smarty_tpl->tpl_vars['product']->index === 0;
 $_smarty_tpl->tpl_vars['product']->last = $_smarty_tpl->tpl_vars['product']->iteration === $_smarty_tpl->tpl_vars['product']->total;
?>
			<?php $_smarty_tpl->tpl_vars['productId'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value['id_product'], null, 0);?>
			<?php $_smarty_tpl->tpl_vars['productAttributeId'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], null, 0);?>
			<?php $_smarty_tpl->tpl_vars['quantityDisplayed'] = new Smarty_variable(0, null, 0);?>
			<?php $_smarty_tpl->tpl_vars['odd'] = new Smarty_variable(($_smarty_tpl->tpl_vars['product']->iteration+$_smarty_tpl->tpl_vars['last_was_odd']->value)%2, null, 0);?>
			<?php $_smarty_tpl->tpl_vars['ignoreProductLast'] = new Smarty_variable(isset($_smarty_tpl->tpl_vars['customizedDatas']->value[$_smarty_tpl->tpl_vars['productId']->value][$_smarty_tpl->tpl_vars['productAttributeId']->value]), null, 0);?>
			<?php $_smarty_tpl->tpl_vars['cannotModify'] = new Smarty_variable(1, null, 0);?>
			
			<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./shopping-cart-product-line.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('productLast'=>$_smarty_tpl->tpl_vars['product']->last,'productFirst'=>$_smarty_tpl->tpl_vars['product']->first), 0);?>

		<?php } ?> 
		</div>
	</div><!--m_hide cerrar-->
                
                <!-- ########################################### -->
                <div id="contenedorVenta">
                <div class="cart_total_price">
                    	<div class="std" style="border: 0px;" style="margin-bottom: 20px;width: 102%;border-collapse: inherit;border-radius: 2px;-moz-border-radius: 2px;box-shadow: 0 0 0 transparent;">

             <div id="contenedor3contenidos">
                        
                                    
                       <div id="boxnefi">
                           <p  style="color: #399E98; font-size: 14px;" ><b>Beneficios</b></p>
                           
                           	 	<div style="border-bottom: 0px solid; padding :2px 2px" >
                           	 		<div style="float: left;" id="imgenvio"></div>
                           	 	</div>

                           	 	<div style="border-bottom: 0px solid; padding :2px 2px" >
                           	 		<div style="font-size: 10px; float: left;width: 194px;">*Envío <b>gratis</b> por compras superiores a <span style="color:#b7689e"><b><br>$ 49.900</b></span>
                           	 		</div>
                           	 	</div>

                           	 <div style="border-bottom: 0px solid; padding :2px 2px;width: 229px;height:54px;" ><div style="float: left;" id="imgdiscr"></div>
                           	 </div>
                           	 	<div style="border-bottom: 0px solid; padding :2px 2px;width: 227px;" >
                           	 		<div style="font-size: 10px;margin-top: -28px;margin-left: 27px;">* <b>Absoluta</b> discreción</div>
                           	 	</div>
                           	 
                           	 	<div style="border-bottom: 0px solid; padding :2px 2px" >
                           	 		<div style="float: left;" id="imgprecio"></div>
                           	 </div>
                           	 	<div style="border-bottom: 0px solid; padding :2px 2px" >
									<div style="font-size: 10px">* Mejor precio <a href="content/6-garantia-del-mejor-precio"><span style="color:#b7689e; font-size:10px"><b>Garantizado*</b></span></a></div>
                           	 	</div>
                           	 </div>
                             

                       
                        <div id="boxmedisp">
                            
                            <div id="fila1mp"  style="float: none; height: 100%; width: 100%;"> 
                              <p  style="color: #399E98; font-size: 14px;" ><b>Nuestros medios de pago</b></p>
                                <div style="float: left;" id="imgamex"></div> 
                                <div style="float: left;" id="imgvisa"></div> 
                                <div style="float: left;" id="imgmaster"></div> 
                                <div style="float: left;" id="imgdiners"></div> 
                           
                            
                             
                                <div style="float:left;" id="imgpse"></div>
                                <div style="float: left;" id="imgbaloto"></div>
                                <div style="float: left;" id="imgcod" ></div>   
                            </div>
                            
                        </div>

          <!-- Cupon apoyo a la salud -->              
          <div id="cupon">
	<?php if ($_smarty_tpl->tpl_vars['voucherAllowed']->value){?>
		<?php if (isset($_smarty_tpl->tpl_vars['errors_discount']->value)&&$_smarty_tpl->tpl_vars['errors_discount']->value){?>
			<ul class="error">
			<?php  $_smarty_tpl->tpl_vars['error'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['error']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['errors_discount']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['error']->key => $_smarty_tpl->tpl_vars['error']->value){
$_smarty_tpl->tpl_vars['error']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['error']->key;
?>
				<li><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['error']->value, 'htmlall', 'UTF-8');?>
</li>
			<?php } ?>
			</ul>
		<?php }?>
		<form action="<?php if ($_smarty_tpl->tpl_vars['opc']->value){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order-opc',true);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true);?>
<?php }?>" method="post" id="voucher">
			<fieldset>
                            <p ><label for="discount_name" style="color: #399E98; font-size: 14px;"><b>Apoyo Salud</b></label></p>
                            <input type="radio" name="type_voucher" value="md" > <span style="font-size: 13px; font:500 13px/14px 'Open Sans',Helvetica,arial;">Médico &nbsp;| &nbsp;	
                            <input type="radio" name="type_voucher" value="cupon" checked="checked"> Cupón	            </span> <p>
					<input style="width: 95%;" type="text" class="discount_name" id="discount_name" name="discount_name" value="<?php if (isset($_smarty_tpl->tpl_vars['discount_name']->value)&&$_smarty_tpl->tpl_vars['discount_name']->value){?><?php echo $_smarty_tpl->tpl_vars['discount_name']->value;?>
<?php }?>" /> 
					<input type="hidden" name="doc_fnd" id="doc_fnd" value="">
				</p>
				<div id="suggestions"></div>
				<p class="submit"><input type="hidden" name="submitDiscount" /> </p>
                                 <input type="submit" style="" name="submitAddDiscount" id="submitAddDiscount" value="<?php echo smartyTranslate(array('s'=>'OK'),$_smarty_tpl);?>
" class="button" />
			</fieldset>
		</form>
		<?php if ($_smarty_tpl->tpl_vars['displayVouchers']->value){?>
			<p id="title" class="title_offers"><?php echo smartyTranslate(array('s'=>'Take advantage of our offers:'),$_smarty_tpl);?>
</p>
			<div id="display_cart_vouchers">
			<?php  $_smarty_tpl->tpl_vars['voucher'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['voucher']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['displayVouchers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['voucher']->key => $_smarty_tpl->tpl_vars['voucher']->value){
$_smarty_tpl->tpl_vars['voucher']->_loop = true;
?>
				<?php if ($_smarty_tpl->tpl_vars['voucher']->value['code']!=''){?><span onclick="$('#discount_name').val('<?php echo $_smarty_tpl->tpl_vars['voucher']->value['code'];?>
');return false;" class="voucher_name"><?php echo $_smarty_tpl->tpl_vars['voucher']->value['code'];?>
</span> - <?php }?><?php echo $_smarty_tpl->tpl_vars['voucher']->value['name'];?>
<br />
			<?php } ?>
			</div>
		<?php }?>
	<?php }?>
	       </div> 
	       </div> 
	     </div>
	 </div>

	 <!-- total Productos -->
		<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value){?>
			<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value){?>
				<div class="cart_total_price">
					<div  id="primerLabel"><?php if ($_smarty_tpl->tpl_vars['display_tax_label']->value){?><?php echo smartyTranslate(array('s'=>'Total products (tax excl.)'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'Total products'),$_smarty_tpl);?>
<?php }?></div>
					<div class="price" id="total_product"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_products']->value),$_smarty_tpl);?>
</div>
				</div>
			<?php }else{ ?>
				<div class="cart_total_price">
					<div  id="primerLabel"><?php if ($_smarty_tpl->tpl_vars['display_tax_label']->value){?><?php echo smartyTranslate(array('s'=>'Total products (tax incl.)'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'Total products'),$_smarty_tpl);?>
<?php }?></div>
					<div  class="price" id="total_product"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_products_wt']->value),$_smarty_tpl);?>
</div>
				</div>
			<?php }?>
		<?php }else{ ?>
			<div class="cart_total_price">
				<div  id="primerLabel"><?php echo smartyTranslate(array('s'=>'Total products:'),$_smarty_tpl);?>
</div>
				<div  class="price" id="total_product"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_products']->value),$_smarty_tpl);?>
</div>
			</div>
		<?php }?>
                
                <!-- Total Apoyo a la salud -->
			<div class="cart_total_voucher" <?php if ($_smarty_tpl->tpl_vars['total_discounts']->value==0){?>style="display:none"<?php }?>>
				<div  id="descuentoValor">
				<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value&&$_smarty_tpl->tpl_vars['display_tax_label']->value){?>
					<?php echo smartyTranslate(array('s'=>'Total vouchers (tax excl.):'),$_smarty_tpl);?>

				<?php }else{ ?>
					<?php echo smartyTranslate(array('s'=>'Total vouchers:'),$_smarty_tpl);?>

				<?php }?>
				</div>
				<div  style="margin-top: -16px; color:#676767; text-align: right;" class="price-discount price" id="total_discount">
				<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value&&!$_smarty_tpl->tpl_vars['priceDisplay']->value){?>
					<?php $_smarty_tpl->tpl_vars['total_discounts_negative'] = new Smarty_variable($_smarty_tpl->tpl_vars['total_discounts']->value*-1, null, 0);?>
				<?php }else{ ?>
					<?php $_smarty_tpl->tpl_vars['total_discounts_negative'] = new Smarty_variable($_smarty_tpl->tpl_vars['total_discounts_tax_exc']->value*-1, null, 0);?>
				<?php }?>
				<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_discounts_negative']->value),$_smarty_tpl);?>

				</div>
			</div>
                       
			<div class="cart_total_voucher" <?php if ($_smarty_tpl->tpl_vars['total_wrapping']->value==0){?>style="display: none;"<?php }?>>
				<div  style="margin-top: -16px; color:#676767; text-align: right;">
				<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value){?>
					<?php if ($_smarty_tpl->tpl_vars['display_tax_label']->value){?><?php echo smartyTranslate(array('s'=>'Total gift-wrapping (tax incl.):'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'Total gift-wrapping:'),$_smarty_tpl);?>
<?php }?>
				<?php }else{ ?>
					<?php echo smartyTranslate(array('s'=>'Total gift-wrapping:'),$_smarty_tpl);?>

				<?php }?>
				</div>
				<div  style="margin-top: -16px; color:#676767; text-align: right;" class="price-discount price" id="total_wrapping">
				<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value){?>
					<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value){?>
						<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_wrapping_tax_exc']->value),$_smarty_tpl);?>

					<?php }else{ ?>
						<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_wrapping']->value),$_smarty_tpl);?>

					<?php }?>
				<?php }else{ ?>
					<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_wrapping_tax_exc']->value),$_smarty_tpl);?>

				<?php }?>
				</div>
			</div>
                        
                        <!-- total envio -->
                        
			<?php if ($_smarty_tpl->tpl_vars['total_shipping_tax_exc']->value<=0&&!isset($_smarty_tpl->tpl_vars['virtualCart']->value)){?>
				<div class="cart_total_delivery" style="<?php if (!isset($_smarty_tpl->tpl_vars['carrier']->value->id)||is_null($_smarty_tpl->tpl_vars['carrier']->value->id)){?>display:none;<?php }?> padding: 7px 10px; color:#676767;">
                                    <div  style="text-align: right;"><?php echo smartyTranslate(array('s'=>'Shipping'),$_smarty_tpl);?>
</div>
					<div  style="padding: 7px 10px; color:#676767; text-align: right;" class="price" id="total_shipping"><?php echo smartyTranslate(array('s'=>'Free Shipping!'),$_smarty_tpl);?>
</div>
				</div> 
			<?php }else{ ?>
				<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value&&$_smarty_tpl->tpl_vars['total_shipping_tax_exc']->value!=$_smarty_tpl->tpl_vars['total_shipping']->value){?>
					<?php if ($_smarty_tpl->tpl_vars['priceDisplay']->value){?>
						<div class="cart_total_delivery" <?php if ($_smarty_tpl->tpl_vars['total_shipping_tax_exc']->value<=0){?> style="display:none;"<?php }?>>
							<div  style="padding: 7px 10px; color:#676767; text-align: right;"><?php if ($_smarty_tpl->tpl_vars['display_tax_label']->value){?><?php echo smartyTranslate(array('s'=>'Total shipping (tax excl.):'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'Total shipping:'),$_smarty_tpl);?>
<?php }?></div>
							<div  style="padding: 7px 10px; color:#676767; text-align: right;" class="price" id="total_shipping"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_shipping_tax_exc']->value),$_smarty_tpl);?>
</div>
						</div>
					<?php }else{ ?>
						<div class="cart_total_delivery"<?php if ($_smarty_tpl->tpl_vars['total_shipping']->value<=0){?> style="display:none;"<?php }?>>
							<div  style="padding: 7px 10px; color:#676767; text-align: right;"><?php if ($_smarty_tpl->tpl_vars['display_tax_label']->value){?><?php echo smartyTranslate(array('s'=>'Total shipping (tax incl.):'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'Total shipping:'),$_smarty_tpl);?>
<?php }?></div>
							<div  style="padding: 7px 10px; color:#676767;  text-align: right;" class="price" id="total_shipping" ><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_shipping']->value),$_smarty_tpl);?>
</div>
						</div>
					<?php }?>
				<?php }else{ ?>
					<div class="cart_total_delivery"<?php if ($_smarty_tpl->tpl_vars['total_shipping_tax_exc']->value<=0){?> style="display:none;"<?php }?>>
						<div  style="padding: 7px 10px; color:#676767; text-align: right;"><?php echo smartyTranslate(array('s'=>'Total shipping:'),$_smarty_tpl);?>
</div>
						<div  style="padding: 7px 10px; color:#676767; text-align: right;" class="price" id="total_shipping" ><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_shipping_tax_exc']->value),$_smarty_tpl);?>
</div>
					</div>
				<?php }?>
			<?php }?>
                        
                        
                        <!-- total sin IVA -->
                        
			<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value){?>
			<div class="cart_total_price">
				<div  id="primerLabel"><?php echo smartyTranslate(array('s'=>'Total (tax excl.):'),$_smarty_tpl);?>
</div>
				<div  class="price" id="total_price_without_tax"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_price_without_tax']->value),$_smarty_tpl);?>
</div>
			</div>
			<div class="cart_total_tax">
				<div  id="primerLabel"><?php echo smartyTranslate(array('s'=>'Total tax:'),$_smarty_tpl);?>
</div>
				<div  class="price" id="total_tax"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_tax']->value),$_smarty_tpl);?>
</div>
			</div>
			<?php }?>
                        
                        <!-- Total compra  -->
			<div class="cart_total_price total" >				
				<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value){?>
				<div  id="primerLabel"><?php echo smartyTranslate(array('s'=>'Total:'),$_smarty_tpl);?>
</div>
				<div  id="precioContenedor" class="price total_price_container" id="total_price_container">
					<span id="total_price"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_price']->value),$_smarty_tpl);?>
</span>
				</div>
				<?php }else{ ?>
				<div  style="padding: 7px 10px; color:#009207; width: 44%; text-align: right;" ><?php echo smartyTranslate(array('s'=>'Total:'),$_smarty_tpl);?>
xd</div>
				<div  style="padding: 7px 10px; color:#676767; width: 55%; text-align: right;" class="price total_price_container" id="total_price_container">
					
					<span id="total_price"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total_price_without_tax']->value),$_smarty_tpl);?>
</span>
				</div>
				<?php }?>
			 </div>
            <!-- fin Total -->
           </div> 
           </div>                        
		</div>
		
                
         <!-- cupon apoyo a la salud  -->       
	<?php if (sizeof($_smarty_tpl->tpl_vars['discounts']->value)){?>
		<div>
		<?php  $_smarty_tpl->tpl_vars['discount'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['discount']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['discounts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['discount']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['discount']->iteration=0;
 $_smarty_tpl->tpl_vars['discount']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['discount']->key => $_smarty_tpl->tpl_vars['discount']->value){
$_smarty_tpl->tpl_vars['discount']->_loop = true;
 $_smarty_tpl->tpl_vars['discount']->iteration++;
 $_smarty_tpl->tpl_vars['discount']->index++;
 $_smarty_tpl->tpl_vars['discount']->first = $_smarty_tpl->tpl_vars['discount']->index === 0;
 $_smarty_tpl->tpl_vars['discount']->last = $_smarty_tpl->tpl_vars['discount']->iteration === $_smarty_tpl->tpl_vars['discount']->total;
?>
                <div  class="cart_discount <?php if ($_smarty_tpl->tpl_vars['discount']->last){?>last_item<?php }elseif($_smarty_tpl->tpl_vars['discount']->first){?>first_item<?php }else{ ?>item<?php }?>" id="cart_discount_<?php echo $_smarty_tpl->tpl_vars['discount']->value['id_discount'];?>
" style="float:left;margin-top:50px;">
                	<div id="descuentoLabel">
				<div class="cart_discount_name" id="segundoLabel"><?php echo $_smarty_tpl->tpl_vars['discount']->value['name'];?>
</div>
				<div class="cart_discount_price" id="tercerLabel"><span class="price-discount">
					<?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['discount']->value['value_real']*-1),$_smarty_tpl);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['discount']->value['value_tax_exc']*-1),$_smarty_tpl);?>
<?php }?>
				</span></div>
				<div class="cart_discount_delete" id="cuartoLabel">1</div>
				<div class="cart_discount_price" id="quintoLabel">
					<span class="price-discount price" id="quintoLabel"><?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value){?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['discount']->value['value_real']*-1),$_smarty_tpl);?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['discount']->value['value_tax_exc']*-1),$_smarty_tpl);?>
<?php }?></span>
				</div>
				<div class="price_discount_del" id="sextoLabel">
					<?php if (strlen($_smarty_tpl->tpl_vars['discount']->value['code'])){?><a href="<?php if ($_smarty_tpl->tpl_vars['opc']->value){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order-opc',true);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true);?>
<?php }?>?deleteDiscount=<?php echo $_smarty_tpl->tpl_vars['discount']->value['id_discount'];?>
" class="price_discount_delete" title="<?php echo smartyTranslate(array('s'=>'Delete'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Delete'),$_smarty_tpl);?>
</a><?php }?>
				</div>
			</div>
		<?php } ?>
			</div>
		</div>
	<?php }?>
        
        
	</div>
</div>
        
        
<?php if ($_smarty_tpl->tpl_vars['show_option_allow_separate_package']->value){?>
<p>
	<input type="checkbox" name="allow_seperated_package" id="allow_seperated_package" <?php if ($_smarty_tpl->tpl_vars['cart']->value->allow_seperated_package){?>checked="checked"<?php }?> autocomplete="off"/>
	<label for="allow_seperated_package"><?php echo smartyTranslate(array('s'=>'Send available products first'),$_smarty_tpl);?>
</label>
</p>
<?php }?>
<?php if (!$_smarty_tpl->tpl_vars['opc']->value){?>
	<?php if (Configuration::get('PS_ALLOW_MULTISHIPPING')){?>
		<p>
			<input type="checkbox" <?php if ($_smarty_tpl->tpl_vars['multi_shipping']->value){?>checked="checked"<?php }?> id="enable-multishipping" />
			<label for="enable-multishipping"><?php echo smartyTranslate(array('s'=>'I want to specify a delivery address for each individual product.'),$_smarty_tpl);?>
</label>
		</p>
	<?php }?>
<?php }?>

<div id="HOOK_SHOPPING_CART"><?php echo $_smarty_tpl->tpl_vars['HOOK_SHOPPING_CART']->value;?>
</div>



<?php if (!isset($_smarty_tpl->tpl_vars['addresses_style']->value)){?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['company'] = 'address_company';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['vat_number'] = 'address_company';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['firstname'] = 'address_name';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['lastname'] = 'address_name';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['address1'] = 'address_address1';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['address2'] = 'address_address2';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['city'] = 'address_city';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['country'] = 'address_country';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['phone'] = 'address_phone';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['phone_mobile'] = 'address_phone_mobile';?>
	<?php $_smarty_tpl->createLocalArrayVariable('addresses_style', null, 0);
$_smarty_tpl->tpl_vars['addresses_style']->value['alias'] = 'address_title';?>
<?php }?>


<p class="cart_navigation">
	<?php if (!$_smarty_tpl->tpl_vars['opc']->value){?>
		<a id="processCarrier" href="<?php if ($_smarty_tpl->tpl_vars['back']->value){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,null,'step=1&amp;back={$back}');?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,null,'step=1');?>
<?php }?>" class="exclusive standard-checkout" title="<?php echo smartyTranslate(array('s'=>'Next'),$_smarty_tpl);?>
"> </a>
		<?php if (Configuration::get('PS_ALLOW_MULTISHIPPING')){?>
			<a href="<?php if ($_smarty_tpl->tpl_vars['back']->value){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,null,'step=1&amp;back={$back}');?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,null,'step=1');?>
<?php }?>&amp;multi-shipping=1" class="multishipping-button multishipping-checkout exclusive" title="<?php echo smartyTranslate(array('s'=>'Next'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Next'),$_smarty_tpl);?>
 &raquo;</a>
		<?php }?>
	<?php }?>
        <a  id="atras1" href="<?php if ((isset($_SERVER['HTTP_REFERER'])&&strstr($_SERVER['HTTP_REFERER'],'order.php'))||isset($_SERVER['HTTP_REFERER'])&&strstr($_SERVER['HTTP_REFERER'],'order-opc')||!isset($_SERVER['HTTP_REFERER'])){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('index');?>
<?php }else{ ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['secureReferrer'][0][0]->secureReferrer(smarty_modifier_escape($_SERVER['HTTP_REFERER'], 'htmlall', 'UTF-8'));?>
<?php }?>" class="button_large" title="<?php echo smartyTranslate(array('s'=>'Continue shopping'),$_smarty_tpl);?>
"> </a>
</p>
	<?php if (!empty($_smarty_tpl->tpl_vars['HOOK_SHOPPING_CART_EXTRA']->value)){?>
		<div class="clear"></div>
		<div class="cart_navigation_extra">
			<div id="HOOK_SHOPPING_CART_EXTRA"><?php echo $_smarty_tpl->tpl_vars['HOOK_SHOPPING_CART_EXTRA']->value;?>
</div>
		</div>
	<?php }?>
<?php }?>




<script type="text/javascript">


	$(document).ready(function() {

			function setUserID(myValue) {
			     $('#doc_fnd').val(myValue).trigger('change');
			}

		$('#discount_name').change(function(){
			$('#doc_fnd').val('');

			if ($('input:radio[name=type_voucher]:checked').val() == 'cupon') {
				$('#submitAddDiscount').prop( "disabled", false );
			} else {
				$('#submitAddDiscount').prop( "disabled", true );
			}
		});

		$('#doc_fnd').change(function(){
			//alert("cambio id doc");
			if ($('#doc_fnd').val().length === 0) {
				//alert("medico vacio");
				$('#submitAddDiscount').prop( "disabled", true );
			}
			else {
				//alert("medico si");
				$('#submitAddDiscount').prop( "disabled", false );
				
			}
		});

	    $('input[type=radio][name=type_voucher]').change(function() {
	    	
			$('#discount_name').val('');
			$('#doc_fnd').val('');

	        if (this.value == 'md') {
	        		$('#submitAddDiscount').prop( "disabled", true );
	        		var options = {
					script:"lisme.php?",
					varname:"input",
					json:true,
					shownoresults:true,
					maxresults:10,
					timeout:7500, 
					delay:0,
					callback: function (obj) { setUserID(obj.id); /*document.getElementById('doc_fnd').value = obj.id; */ }
				};

	            var as_json = new bsn.AutoSuggest('discount_name', options);	
	        }
	        else if (this.value == 'cupon') {
	        		$('#submitAddDiscount').prop( "disabled", false );
	        		var options = {
	        		minchars:555, 
	        		meth:"post", 
					script:"lisme.php?",
					varname:"service",
					json:true,
					shownoresults:true,
					maxresults:0,
					timeout:0, 
					delay:0,
					maxheight: 0, 
					cache: false, 
					maxentries: 0,
				};			
				$("#discount_name").css({"autocomplete":"on"});
	           var as_json = new bsn.AutoSuggest('discount_name', options);	
	        }
	    });
});

	
		

</script>
<?php }} ?>