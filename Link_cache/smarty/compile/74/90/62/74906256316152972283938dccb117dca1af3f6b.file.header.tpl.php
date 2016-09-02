<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:37
         compiled from "/var/www/themes/gomarket/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:180923686553487325044848-05156021%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74906256316152972283938dccb117dca1af3f6b' => 
    array (
      0 => '/var/www/themes/gomarket/header.tpl',
      1 => 1397062361,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '180923686553487325044848-05156021',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lang_iso' => 0,
    'meta_title' => 0,
    'meta_description' => 0,
    'meta_keywords' => 0,
    'meta_language' => 0,
    'nobots' => 0,
    'nofollow' => 0,
    'favicon_url' => 0,
    'img_update_time' => 0,
    'content_dir' => 0,
    'base_uri' => 0,
    'static_token' => 0,
    'token' => 0,
    'priceDisplayPrecision' => 0,
    'currency' => 0,
    'priceDisplay' => 0,
    'roundMode' => 0,
    'css_files' => 0,
    'css_uri' => 0,
    'media' => 0,
    'js_files' => 0,
    'settings' => 0,
    'js_uri' => 0,
    'css_dir' => 0,
    'HOOK_HEADER' => 0,
    'page_name' => 0,
    'hide_left_column' => 0,
    'hide_right_column' => 0,
    'content_only' => 0,
    'restricted_country_mode' => 0,
    'geolocation_country' => 0,
    'HOOK_TOP' => 0,
    'base_dir' => 0,
    'shop_name' => 0,
    'logo_url' => 0,
    'CS_MEGA_MENU' => 0,
    'HOOK_CS_SLIDESHOW' => 0,
    'grid_column' => 0,
    'HOOK_LEFT_COLUMN' => 0,
    'iexplorerold' => 0,
    'newsletter' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_534873251b2998_33184318',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_534873251b2998_33184318')) {function content_534873251b2998_33184318($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/var/www/tools/smarty/plugins/modifier.escape.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
">
<head>
		<title><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['meta_title']->value, 'htmlall', 'UTF-8');?>
</title>
<?php if (isset($_smarty_tpl->tpl_vars['meta_description']->value)&&$_smarty_tpl->tpl_vars['meta_description']->value){?>
		<meta name="description" content="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['meta_description']->value, 'html', 'UTF-8');?>
" />
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['meta_keywords']->value)&&$_smarty_tpl->tpl_vars['meta_keywords']->value){?>
		<meta name="keywords" content="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['meta_keywords']->value, 'html', 'UTF-8');?>
" />
<?php }?>

		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta http-equiv="content-language" content="<?php echo $_smarty_tpl->tpl_vars['meta_language']->value;?>
" />
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="<?php if (isset($_smarty_tpl->tpl_vars['nobots']->value)){?>no<?php }?>index,<?php if (isset($_smarty_tpl->tpl_vars['nofollow']->value)&&$_smarty_tpl->tpl_vars['nofollow']->value){?>no<?php }?>follow" />
		<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport"/>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		
		<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon_url']->value;?>
?<?php echo $_smarty_tpl->tpl_vars['img_update_time']->value;?>
" />
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon_url']->value;?>
?<?php echo $_smarty_tpl->tpl_vars['img_update_time']->value;?>
" />
		<script type="text/javascript">
			var baseDir = '<?php echo addslashes($_smarty_tpl->tpl_vars['content_dir']->value);?>
';
			var baseUri = '<?php echo addslashes($_smarty_tpl->tpl_vars['base_uri']->value);?>
';
			var static_token = '<?php echo addslashes($_smarty_tpl->tpl_vars['static_token']->value);?>
';
			var token = '<?php echo addslashes($_smarty_tpl->tpl_vars['token']->value);?>
';
			var priceDisplayPrecision = <?php echo $_smarty_tpl->tpl_vars['priceDisplayPrecision']->value*$_smarty_tpl->tpl_vars['currency']->value->decimals;?>
;
			var priceDisplayMethod = <?php echo $_smarty_tpl->tpl_vars['priceDisplay']->value;?>
;
			var roundMode = <?php echo $_smarty_tpl->tpl_vars['roundMode']->value;?>
;
		</script>

<!-- Start Visual Website Optimizer Code -->
<script type='text/javascript'>
var _vis_opt_account_id = 67766;
var _vis_opt_protocol = (('https:' == document.location.protocol) ? 'https://' : 'http://');
document.write('<s' + 'cript src="' + _vis_opt_protocol + 
'dev.visualwebsiteoptimizer.com/deploy/js_visitor_settings.php?v=1&a='+_vis_opt_account_id+'&url='
+encodeURIComponent(document.URL)+'&random='+Math.random()+'" type="text/javascript">' + '<\/s' + 'cript>');
</script>

<script type='text/javascript'>
if(typeof(_vis_opt_settings_loaded) == "boolean") { document.write('<s' + 'cript src="' + _vis_opt_protocol + 
'd5phz18u4wuww.cloudfront.net/vis_opt.js" type="text/javascript">' + '<\/s' + 'cript>'); }
// if your site already has jQuery 1.4.2, replace vis_opt.js with vis_opt_no_jquery.js above
</script>

<script type='text/javascript'>
if(typeof(_vis_opt_settings_loaded) == "boolean" && typeof(_vis_opt_top_initialize) == "function") {
        _vis_opt_top_initialize(); vwo_$(document).ready(function() { _vis_opt_bottom_initialize(); });
}
</script>
<!-- End Visual Website Optimizer Code -->

<?php if (isset($_smarty_tpl->tpl_vars['css_files']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['media'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['media']->_loop = false;
 $_smarty_tpl->tpl_vars['css_uri'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['css_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['media']->key => $_smarty_tpl->tpl_vars['media']->value){
$_smarty_tpl->tpl_vars['media']->_loop = true;
 $_smarty_tpl->tpl_vars['css_uri']->value = $_smarty_tpl->tpl_vars['media']->key;
?>
	<link href="<?php echo $_smarty_tpl->tpl_vars['css_uri']->value;?>
" rel="stylesheet" type="text/css" media="<?php echo $_smarty_tpl->tpl_vars['media']->value;?>
" />
	<?php } ?>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['js_files']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['js_uri'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['js_uri']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['js_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['js_uri']->key => $_smarty_tpl->tpl_vars['js_uri']->value){
$_smarty_tpl->tpl_vars['js_uri']->_loop = true;
?>	
		<?php if (isset($_smarty_tpl->tpl_vars['settings']->value->column)&&$_smarty_tpl->tpl_vars['settings']->value->column=='1_column'){?>
			<?php if (!strpos($_smarty_tpl->tpl_vars['js_uri']->value,"blocklayered.js")){?>
				<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js_uri']->value;?>
"></script>
			<?php }?>
		<?php }else{ ?>
			<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js_uri']->value;?>
"></script>
		<?php }?>
	<?php } ?>
<?php }?>
<!--[if IE 7]><link href="<?php echo $_smarty_tpl->tpl_vars['css_dir']->value;?>
global-ie.css" rel="stylesheet" type="text/css" media="<?php echo $_smarty_tpl->tpl_vars['media']->value;?>
" /><![endif]-->

<?php echo $_smarty_tpl->tpl_vars['HOOK_HEADER']->value;?>

	</head>
	
	<body <?php if (isset($_smarty_tpl->tpl_vars['page_name']->value)){?>id="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['page_name']->value, 'htmlall', 'UTF-8');?>
"<?php }?> class="<?php if ($_smarty_tpl->tpl_vars['hide_left_column']->value){?>hide-left-column<?php }?> <?php if ($_smarty_tpl->tpl_vars['hide_right_column']->value){?>hide-right-column<?php }?> <?php if ($_smarty_tpl->tpl_vars['content_only']->value){?> content_only <?php }?>">
	<?php if (!$_smarty_tpl->tpl_vars['content_only']->value){?>
		<?php if (isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)&&$_smarty_tpl->tpl_vars['restricted_country_mode']->value){?>
		<div id="restricted-country">
			<p><?php echo smartyTranslate(array('s'=>'You cannot place a new order from your country.'),$_smarty_tpl);?>
 <span class="bold"><?php echo $_smarty_tpl->tpl_vars['geolocation_country']->value;?>
</span></p>
		</div>
		<?php }?>
		<div id="page">
			<!-- Header -->
			<div class="mode_header" id="mode_header">
				<div class="container_24">
					<div id="header" class="grid_24 clearfix omega alpha">						
						<div id="header_right">
							<?php echo $_smarty_tpl->tpl_vars['HOOK_TOP']->value;?>
							
						</div>
						<a id="header_logo" href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['shop_name']->value, 'htmlall', 'UTF-8');?>
">
							<img class="logo" src="<?php echo $_smarty_tpl->tpl_vars['logo_url']->value;?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['shop_name']->value, 'htmlall', 'UTF-8');?>
" />
						</a>
						<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index'){?>
						<?php if (isset($_smarty_tpl->tpl_vars['CS_MEGA_MENU']->value)){?><?php echo $_smarty_tpl->tpl_vars['CS_MEGA_MENU']->value;?>
<?php }?>
						<?php }?>
					</div>
				</div>
			</div>					
			<script>
//				$(document).ready(function(){
//				  var acc = prompt("Digite la clave de acceso")
//				  if (acc !='farma13'){
//					window.location='http://www.google.com';
//				  }
//				})
			</script>
	
			<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='index'){?>
			<div class="container_24">
				<div class="hook_csslide_new">
				<?php if (isset($_smarty_tpl->tpl_vars['HOOK_CS_SLIDESHOW']->value)){?><?php echo $_smarty_tpl->tpl_vars['HOOK_CS_SLIDESHOW']->value;?>
<?php }?>
				</div>
				<?php if (isset($_smarty_tpl->tpl_vars['CS_MEGA_MENU']->value)){?><?php echo $_smarty_tpl->tpl_vars['CS_MEGA_MENU']->value;?>
<?php }?>
			</div>
			<?php }?>
			
			<div class="mode_container">
				<div class="container_24">
				<div id="columns" class="<?php if (isset($_smarty_tpl->tpl_vars['grid_column']->value)){?><?php echo $_smarty_tpl->tpl_vars['grid_column']->value;?>
<?php }?> grid_24 omega alpha">
				<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index'){?>
					<?php if (isset($_smarty_tpl->tpl_vars['settings']->value)){?>
						<?php if ((($_smarty_tpl->tpl_vars['settings']->value->column=='2_column_left'||$_smarty_tpl->tpl_vars['settings']->value->column=='3_column'))){?>
							<!-- Left -->
							<div id="left_column" class="<?php echo $_smarty_tpl->tpl_vars['settings']->value->left_class;?>
 alpha">				
								<?php echo $_smarty_tpl->tpl_vars['HOOK_LEFT_COLUMN']->value;?>

							</div>
						<?php }?>
					<?php }else{ ?>
						<!-- Left -->
							<div id="left_column" class="grid_5 alpha">
								
								<?php echo $_smarty_tpl->tpl_vars['HOOK_LEFT_COLUMN']->value;?>

							</div>
					<?php }?>
				<?php }?>
					<!-- Center -->
					<div id="center_column" class="<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='index'){?>grid_24 omega alpha<?php }else{ ?><?php if (isset($_smarty_tpl->tpl_vars['settings']->value)){?><?php echo $_smarty_tpl->tpl_vars['settings']->value->center_class;?>
 <?php }else{ ?>grid_19 omega<?php }?><?php }?>">
		<?php }?>

      
<?php if (isset($_smarty_tpl->tpl_vars['iexplorerold']->value)){?>
   


    
<style>
#popup {
	left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 1001;
}

.content-popup {
	margin:0px auto;
	margin-top:120px;
	position:relative;
	padding:10px;
	width:500px;
	min-height:250px;
	border-radius:4px;
	background-color:#FFFFFF;
	box-shadow: 0 2px 5px #666666;
}

.content-popup h2 {
	color:#48484B;
	border-bottom: 1px solid #48484B;
    margin-top: 0;
    padding-bottom: 4px;
}

.popup-overlay {
	left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 999;
	display:none;
	background-color: #777777;
    cursor: pointer;
    opacity: 0.7;
}

.close {
	position: absolute;
    right: 15px;
}
</style>
                        
                        
 <script type="text/javascript">
     
           
     function closePopaUp()
     {
	$('#popup').fadeOut('slow');
        $('.popup-overlay').fadeOut('slow');
		 
     
      <?php if (isset($_smarty_tpl->tpl_vars['newsletter']->value)&&isset($_smarty_tpl->tpl_vars['iexplorerold']->value)){?>      
          
                
         $('#news').fadeIn('slow');
		$('.news-overlay').fadeIn('slow');
		$('.news-overlay').height($(window).height());
          
      <?php }?>
              
      return false;       
     }
     
     
$(document).ready(function(){
    

		$('#popup').fadeIn('slow');
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
	
	      
});


</script>                        
                        

    
<?php }?>


<?php if (isset($_smarty_tpl->tpl_vars['newsletter']->value)&&$_smarty_tpl->tpl_vars['newsletter']->value=='newsletter'){?>
    
   
     
<style type="text/css">
#news {
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 1001;
   max-width: initial;
    
    
}

.content-news {
	margin:0px auto;
	margin-top:120px;
	position:relative;
	padding:10px;
	width:720px;
	min-height:411px;
	border-radius:4px;
	background-color:#FFFFFF;
	box-shadow: 0 2px 5px #666666;
        background: url('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/newsletter.jpg') no-repeat;
}

.content-news h2 {
   color:#48484B;
   border-bottom: 1px solid #48484B;
   margin-top: 0;
   padding-bottom: 4px;
}

.news-overlay {
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
   /* height: 100%; */
    z-index: 999;
    display:none;
    background-color: #777777;
    cursor: pointer;
    opacity: 0.7;
    /*max-width: initial;*/
 
   
}

.close-news {
    position: absolute;
    right: 15px;
}



#hombre1 {
       background:url('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/news-hombre.png') no-repeat;
	
 -webkit-background-size: 100% 100%;           /* Safari 3.0 */
     -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
       -o-background-size: 100% 100%;           /* Opera 9.5 */
          background-size: 100% 100%;
}

#hombre1:hover
{
  
width:110px;
height:43px;
border:none;
    background:url('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/news-hombre-hover.png') no-repeat;
    -webkit-background-size: 100% 100%;           /* Safari 3.0 */
    -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
    -o-background-size: 100% 100%;           /* Opera 9.5 */
    background-size: 100% 100%;
    
}



#mujer1{
    background:url('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/news-mujer.png') no-repeat;
    -webkit-background-size: 100% 100%;           /* Safari 3.0 */
    -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
    -o-background-size: 100% 100%;           /* Opera 9.5 */
    background-size: 100% 100%;
}

#mujer1:hover
{
  
width:110px;
height:43px;
border:none;
    background:url('<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/news-mujer-hover.png') no-repeat;
    -webkit-background-size: 100% 100%;           /* Safari 3.0 */
    -moz-background-size: 100% 100%;           /* Gecko 1.9.2 (Firefox 3.6) */
    -o-background-size: 100% 100%;           /* Opera 9.5 */
    background-size: 100% 100%;
    
}


</style>

 <?php }?>   


<?php if (isset($_smarty_tpl->tpl_vars['newsletter']->value)&&!isset($_smarty_tpl->tpl_vars['iexplorerold']->value)){?>
                
  <script type="text/javascript">          
   
   
$(document).ready(function(){
  

		$('#news').fadeIn('slow');
		$('.news-overlay').fadeIn('slow');
		$('.news-overlay').height($(window).height());
		
	
	$('#close-news').click(function(){
		$('#news').fadeOut('slow');
		$('.news-overlay').fadeOut('slow');
		return false;
	});
});
</script>


<?php }elseif(isset($_smarty_tpl->tpl_vars['newsletter']->value)&&isset($_smarty_tpl->tpl_vars['iexplorerold']->value)){?>
    

    
 <script type="text/javascript">
 $(document).ready(function(){
        
        $('#close-news').click(function(){
		$('#news').fadeOut('slow');
		$('.news-overlay').fadeOut('slow');
		return false;
	});
});
        
 </script> 
    

    
<?php }?>

<?php }} ?>