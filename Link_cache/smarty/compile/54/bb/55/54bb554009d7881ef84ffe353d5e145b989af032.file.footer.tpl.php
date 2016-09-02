<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:37
         compiled from "/var/www/themes/gomarket/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1397351248534873251bdbe7-54009995%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '54bb554009d7881ef84ffe353d5e145b989af032' => 
    array (
      0 => '/var/www/themes/gomarket/footer.tpl',
      1 => 1396029442,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1397351248534873251bdbe7-54009995',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content_only' => 0,
    'settings' => 0,
    'page_name' => 0,
    'HOOK_RIGHT_COLUMN' => 0,
    'HOOK_CS_FOOTER_TOP' => 0,
    'HOOK_FOOTER' => 0,
    'HOOK_CS_FOOTER_BOTTOM' => 0,
    'PS_ALLOW_MOBILE_DEVICE' => 0,
    'link' => 0,
    'iexplorerold' => 0,
    'base_dir' => 0,
    'newsletter' => 0,
    'css_dir' => 0,
    'lightbox1' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_53487325224e53_09630143',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53487325224e53_09630143')) {function content_53487325224e53_09630143($_smarty_tpl) {?>

			<?php if (!$_smarty_tpl->tpl_vars['content_only']->value){?>
					</div><!-- /Center -->
			<?php if (isset($_smarty_tpl->tpl_vars['settings']->value)){?>
				<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index'){?>
					<?php if ((($_smarty_tpl->tpl_vars['settings']->value->column=='2_column_right'||$_smarty_tpl->tpl_vars['settings']->value->column=='3_column'))){?>
						<!-- Left -->
						<div id="right_column" class="<?php echo $_smarty_tpl->tpl_vars['settings']->value->right_class;?>
 omega">
							<?php echo $_smarty_tpl->tpl_vars['HOOK_RIGHT_COLUMN']->value;?>

						</div>
					<?php }?>
				<?php }?>
			<?php }?>
				</div><!--/columns-->
			</div><!--/container_24-->
			</div>
<!-- Footer -->
			
			<div class="mode_footer">
				<div class="container_24">
					<div id="footer" class="grid_24 clearfix  omega alpha">
						<?php if (isset($_smarty_tpl->tpl_vars['HOOK_CS_FOOTER_TOP']->value)&&$_smarty_tpl->tpl_vars['HOOK_CS_FOOTER_TOP']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_CS_FOOTER_TOP']->value;?>
<?php }?>
						<?php echo $_smarty_tpl->tpl_vars['HOOK_FOOTER']->value;?>

						<?php if (isset($_smarty_tpl->tpl_vars['HOOK_CS_FOOTER_BOTTOM']->value)&&$_smarty_tpl->tpl_vars['HOOK_CS_FOOTER_BOTTOM']->value){?><?php echo $_smarty_tpl->tpl_vars['HOOK_CS_FOOTER_BOTTOM']->value;?>
<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['PS_ALLOW_MOBILE_DEVICE']->value){?>
							<p class="center clearBoth"><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('index',true);?>
?mobile_theme_ok"><?php echo smartyTranslate(array('s'=>'Browse the mobile site'),$_smarty_tpl);?>
</a></p>
						<?php }?>
					</div>
				</div>
			</div>
			<div id="toTop">top</div>
		</div><!--/page-->
	<?php }?>
        
        
<?php if (isset($_smarty_tpl->tpl_vars['iexplorerold']->value)){?>

<div id="popup" style="display: none;">
    <div class="content-popup">
        <div class="close"><a href="#" id="close" onclick="closePopaUp()"><img src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/close.png"/></a></div>
        <div>
        	<h2>Actualiza tu navegador </h2>
                <p>Hemos detectado que utilizas una versi&oacute;n vieja de Internet Explorer, te recomendamos actualizar tu navegador para obtener la mejor experiencia de uso.</p>
                <p> Deseo actualizar mi navegador y obtener la mejor experiencia de uso. 
                <div style="text-align: center;"> <a href="http://windows.microsoft.com/es-es/internet-explorer/download-ie"><img src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/internet-explorer-11.png" style="width: 64px;"> </a></p>
                </div>
                
                <p> Ahora no quiero tener la mejor experiencia de uso, tal vez en otro momento.
                <div style="text-align: center;" > <a href="#" id="close2" onclick="closePopaUp()"><img src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
img/botonContinuar.gif" style="height: 48px;"> </a></div>
                </p>
               
                
            <div style="float:left; width:100%;">
    	
    </div>
        </div>
    </div>
</div>
<div class="popup-overlay" ></div>

 <?php }?> 
 
 
        
 <?php if (isset($_smarty_tpl->tpl_vars['newsletter']->value)){?>
   
 <div id="news" style="display: none;">
    <div class="content-news">
        <div class="close-news"><a href="#" id="close-news"><div style="height: 35px;  width: 35px;"></div></a></div>
        <div style="margin-top: 267px; margin-left: 67px">
            
        <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['css_dir']->value;?>
modules/blocktopmenu/css/opt-in.css" />
        <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
ajax_newsletter.php" >       
        
            <div >
        
          <div class="bloque">
              <input type="text" placeholder="Digita aqu&iacute; t&uacute; correo." name="mail" id="mail" style=" margin: 5px; width: 319px; height: 30px; font-size: 20px; text-align: left; " />
        </div>
                <div class="bloque"  style="margin: 0 0 0 -2px;" >
            <div class="celda"><input type="submit" name="hombre" id="hombre1" value=""  /></div>
            <div class="celda"><input type="submit" name="mujer" id="mujer1" value="" /></div>           
           </div>
            </div>
            
        </form>
        
    </div>
        </div>
    </div>       
        <div class="news-overlay" ></div>
       
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['lightbox1']->value)){?>
   
<?php }?> 
        
        <!-- Start Alexa Certify Javascript -->
        <script type="text/javascript">
            _atrk_opts = { atrk_acct:"J+pRi1a8Dy00yS", domain:"farmalisto.com.co",dynamic: true};
            (function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
        </script>
        <noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=J+pRi1a8Dy00yS" style="display:none" height="1" width="1" alt="" /></noscript>
        <!-- End Alexa Certify Javascript -->



  
	</body>
</html>
<?php }} ?>