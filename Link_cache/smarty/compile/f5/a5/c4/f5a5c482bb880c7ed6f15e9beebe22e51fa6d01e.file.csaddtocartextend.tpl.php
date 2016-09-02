<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:34
         compiled from "/var/www/modules/csaddtocartextend/csaddtocartextend.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2055258518534873226b0351-34959687%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f5a5c482bb880c7ed6f15e9beebe22e51fa6d01e' => 
    array (
      0 => '/var/www/modules/csaddtocartextend/csaddtocartextend.tpl',
      1 => 1393882142,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2055258518534873226b0351-34959687',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page_name' => 0,
    '__PS_BASE_URI__' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873226cf3d1_97826253',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873226cf3d1_97826253')) {function content_534873226cf3d1_97826253($_smarty_tpl) {?><!-- CS add to cart extend module -->
<script type="text/javascript">
//<![CDATA[
	<?php if ($_smarty_tpl->tpl_vars['page_name']->value=="category"){?>
		$(document).ajaxComplete(function( event,request, settings ) {
			$('#add_to_cart input').attr('onclick', 'return OnAddclickDetail();');
			$('a.ajax_add_to_cart_button').attr('onclick', 'return OnAddclickCategory($(this));');
		});
	<?php }?>
	$(window).ready(function(){
		$('#add_to_cart input').attr('onclick', 'return OnAddclickDetail();');
		$('a.ajax_add_to_cart_button').attr('onclick', 'return OnAddclickCategory($(this));');
	});
	
	function OnAddclickDetail() {
		var image_detail = $('#view_full_size img').attr('src');
		image_detail = image_detail.replace("large_default", "small_default"); 
		var name_detail = $('div#pb-left-column h1').html();
		var id_detailt = $("input[name=id_product]").val();
		var link_detail = "<?php echo $_smarty_tpl->tpl_vars['__PS_BASE_URI__']->value;?>
index.php?id_product=" + id_detailt + "&controller=product";
		var string_info = "<a href=" + link_detail + " class=\"product_img_link\"><img src='" +  image_detail + "'/></a>" + "<h3><a href=" + link_detail + ">" + name_detail + "</a></h3> <?php echo smartyTranslate(array('s'=>'ha sido agregado al ','mod'=>'csaddtocartextend'),$_smarty_tpl);?>
<a href='<?php echo $_smarty_tpl->tpl_vars['__PS_BASE_URI__']->value;?>
index.php?controller=order&paso=inicial' class='your_cart'><?php echo smartyTranslate(array('s'=>'carrito','mod'=>'csaddtocartextend'),$_smarty_tpl);?>
</a>" ;
		$.ambiance({
			message: string_info, 
			type: "success",
			timeout: 7
		});
	}
	
	function OnAddclickCategory(element) {
		var id_product = element.attr('rel').substring(16);
		var html_product = element.parent().html();
		$("body").append("<div id=\"add_to_card_extend_"+ id_product + "\" style=\"display:none\">" + html_product + "</div>")
		var image_p = $("#add_to_card_extend_" + id_product + " div.image").html();
		image_p = image_p.replace("home_default", "small_default"); 
		var name_p = $("#add_to_card_extend_" + id_product + " div.name_product").html();
		$('div').remove("#add_to_card_extend_" + id_product + "");
		$.ambiance({
			message: image_p + name_p + "<?php echo smartyTranslate(array('s'=>'ha sido agregado al ','mod'=>'csaddtocartextend'),$_smarty_tpl);?>
<a href='<?php echo $_smarty_tpl->tpl_vars['__PS_BASE_URI__']->value;?>
index.php?controller=order&paso=inicial' class='your_cart'><?php echo smartyTranslate(array('s'=>'carrito','mod'=>'csaddtocartextend'),$_smarty_tpl);?>
</a>", 
			type: "success",
			timeout:7
		});
		
	}
//]]
</script>
<!-- /CS add to cart extend module -->

<?php }} ?>