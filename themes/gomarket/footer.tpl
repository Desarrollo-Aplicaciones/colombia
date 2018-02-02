{*
* 2007-2012 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

            {if !$content_only}
                    </div><!-- /Center -->
            {if isset($settings)}
                {if $page_name != 'index'}
                    {if (($settings->column == '2_column_right' || $settings->column == '3_column'))}
                        <!-- Left -->
                        <div id="right_column" class="{$settings->right_class} omega">
                            {$HOOK_RIGHT_COLUMN}
                        </div>
                    {/if}
                {/if}
            {/if}
                </div><!--/columns-->
            </div><!--/container_24-->
        </div>



<!-- Footer -->
        {if !isset($smarty.cookies.validamobile)}
            {$HOOK_HOMEBOTCEN}
            <div class="mode_footer">
                <div class="ctn-gray-footer">
                    <div class="container_24">
                        {* <div id="footer" class="grid_24 clearfix  omega alpha"> *}
                            {include file="ctn-gray-footer.tpl"}
                            {if isset($HOOK_CS_FOOTER_TOP) && $HOOK_CS_FOOTER_TOP}{$HOOK_CS_FOOTER_TOP}{/if}
                            {$HOOK_FOOTER}
                            {* Este es el cms que carga el texto largo del footer *}
                            <div id="ctn-footer-display" style="display: none;">
                                {if isset($HOOK_CS_FOOTER_BOTTOM) && $HOOK_CS_FOOTER_BOTTOM}{$HOOK_CS_FOOTER_BOTTOM}{/if}
                                {if $PS_ALLOW_MOBILE_DEVICE}
                                    <p class="center clearBoth"><a href="{$link->getPageLink('index', true)}?mobile_theme_ok">{l s='Browse the mobile site'}</a></p>
                                {/if}
                            </div>
                        {* </div> *}
                    </div>
                </div>
                <div id="ctn-gray-end-footer"><a name="ctn-gray-end-footer"></a>
                </div>
            </div>
        {/if}
        <div id="toTop">top</div>
    </div><!--/page-->
    {/if}
        
        
{if isset($iexplorerold) && $iexplorerold eq true and  $lightboxshow eq 'si'}

<div id="popup" style="display: none;">
    <div class="content-popup">
        <div class="close"><a href="#" id="close" onclick="closePopaUp()"><img src="{$base_dir}img/close.png"/></a></div>
        <div>
            <h2>Actualiza tu navegador </h2>
                <p>Hemos detectado que utilizas una versi&oacute;n vieja de Internet Explorer, te recomendamos actualizar tu navegador para obtener la mejor experiencia de uso.</p>
                <p> Deseo actualizar mi navegador y obtener la mejor experiencia de uso. 
                <div style="text-align: center;"> <a href="http://windows.microsoft.com/es-es/internet-explorer/download-ie"><img src="{$base_dir}img/internet-explorer-11.png" style="width: 64px;"> </a></p>
                </div>
                
                <p> Ahora no quiero tener la mejor experiencia de uso, tal vez en otro momento.
                <div style="text-align: center;" > <a href="#" id="close2" onclick="closePopaUp()"><img src="{$base_dir}img/botonContinuar.gif" style="height: 48px;"> </a></div>
                </p>
               
                
            <div style="float:left; width:100%;">
        
    </div>
        </div>
    </div>
</div>
<div class="popup-overlay" ></div>

 {/if}
        
 {*if isset($newsletter) && $newsletter eq true and  $lightboxshow eq 'si'}
     
     <script type="text/javascript"> 
      
         
 $(function(){ 
     
 var count_options=0;    

        $('input:checkbox').change(function(){
    if($(this).is(":checked")) {
        count_options++;
        } else {
          count_options--;
        }
});


{literal}
// validación sexo         
 var sex=0;
 $( "#hombre1" ).click(function() {
  sex='M'; 
 });
  $( "#mujer1" ).click(function() {
  sex='F';  
 });
 
 // ajax
$( "#hombre1,#mujer1" ).click(function() {
   $.ajax(ruta, {
   "type": "post", // usualmente post o get
   "success": function(result) {
      
      if(result==='error5')
      {
         alert('Ingresa tu correo, para inscribirte a nuestro boletín.');
      }
      if(result==='error2')
      {
       alert('Ingresa un correo válido para inscribirte a nuestro boletín.');   
      }
      if(result==='ok')
      {
          alert('¡Felicitaciones!  Te has suscrito con éxito a nuestro boletín.');
          $('#correo').val('');
          
          $('#news').fadeOut('slow');
        $('.news-overlay').fadeOut('slow');
        return false;         
      }

   },
   "error": function(result) {
    console.log("Error AjaxLigthBox -> "+result);
   },
   "data": {mail: $( "#correo" ).val(), sex: sex,option1: $("#option1").is(':checked') ,option2: $("#option2").is(':checked') ,option3: $("#option3").is(':checked') ,option4: $("#option4").is(':checked') ,option5: $("#option5").is(':checked') ,option6: $("#option6").is(':checked')},
   "async": true
}); 

});

{/literal}

});
 
     </script> 
   
 <div id="news" style="display: none;">
    <div class="content-news">
        <div class="close-news"><a href="#" id="close-news"><img src="{$base_dir}img/close.png"/></a></div>
        <div style="margin-top: 267px; margin-left: 67px">
            
        <link rel="stylesheet" type="text/css" href="{$css_dir}modules/blocktopmenu/css/opt-in.css" />
       <!-- <form method="post" action="{$base_dir}ajax_newsletter.php" >     -->   
        
            <div >
                <div class="divTable">
                    <div class="divRow"> <div class="divCell"> <input type="checkbox" name="option1" id="option1" value="ON" /> </div> <div class="divCell"> <input type="checkbox" name="option2" id="option2" value="ON" /> </div> </div>  
                    <div class="divRow"> <div class="divCell"> <input type="checkbox" name="option3" id="option3" value="ON" /> </div> <div class="divCell"> <input type="checkbox" name="option4" id="option4" value="ON" /> </div> </div>  
                    <div class="divRow"> <div class="divCell"> <input type="checkbox" name="option5" id="option5" value="ON" /> </div> <div class="divCell"> <input type="checkbox" name="option6" id="option6" value="ON" /> </div> </div>  
                      
                </div>
        
          <div class="bloque">
              <input type="text" placeholder="&nbsp;&nbsp;Ingresa aqu&iacute; tu correo electr&oacute;nico " name="mail" id="correo" style=" margin: 8px 0px 0px 30px; width: 319px; height: 27px; width: 329px; font-size: 14px; text-align: left; " />
        </div>
                <div class="bloque"  style="margin: 0 0 0 0px;" >
                    <div class="celda"><input type="button" name="hombre" id="hombre1" value=""  /></div>
                    <div class="celda"><input type="button" name="mujer" id="mujer1" value="" /></div>           
           </div>
            </div>
            
      <!--  </form> -->
        
    </div>
        </div>
    </div>       
        <div class="news-overlay" ></div> 
       
{/if*}
</div>
<!--Lightbox container-->
    <div id="standard_lightbox">
        <div class="fog"></div>
        <div id="lightbox_content"></div>
        <div class="recent"></div>
    </div>
    <script>
        function lightbox_hide(){
            $('#standard_lightbox').fadeOut('slow');
            $('#page').removeClass("blurred");
            $('#'+($('#lightbox_content div').attr("id"))).appendTo( '#standard_lightbox .recent' );
            $('#lightbox_content').empty();
            }
        function standard_lightbox(id){
            $('#lightbox_content').empty();
            $('#'+id).appendTo( "#lightbox_content" );
            $('#lightbox_content #'+id).show();
            $('#standard_lightbox').fadeIn('slow');
            $('#page').addClass("blurred");
        }
        $('#standard_lightbox .fog').click(function(){
            lightbox_hide();
        });
    </script>
<!--/Lightbox container-->

<!-- redirection page -->
{if isset($redirection_countries) && $redirection_countries && !isset($smarty.cookies.CookRedirection) }
    
    {*
    <link href="{$base_dir}themes/gomarket/css/Lightbox_Redirection_Page.css" rel="stylesheet" type="text/css">
    
    <div class="contenedor container_24" id="pop-redirection-page">
            <div class="close_redirection" onclick="lightbox_hide(); "></div>
            <div class="block_title_redirection">
                Redirección
            </div>
            <div class="block_location_redirection">
                <div id="set_flag"><img id="flag_country" src="{$base_dir}img/flags/large-{$country_page_local}.jpg"/></div>
                <div id="set_text">Estás navegando en farmalisto {$country_page_local}.</div>
            </div>
            <div class="block_question_redirection">
                <label>¿Deseas buscar lo mismo en farmalisto {$country_page_redirect}?</label>
            </div>
            <div class="button_redirection" onclick="location.href = '{$url_page_redirection}';">
                <label>Ir a farmalisto {$country_page_redirect}</label>
            </div>
    </div>
    *}
    
    {* Script para abrir el lightbox de redireccion *}
    <script type="text/javascript">
        //standard_lightbox('pop-redirection-page');
        location.href = '{$url_page_redirection}';
    </script>
    <!--:Faber: Agrego script para el boton Mostrar/Ocultar-->

{/if}
<!-- /redirection page -->


    
<!-- INICIO POP-PUP DOWNLOAD APP FARMALISTO -->
{if $page_name == 'index'}

    {if isset($MobileDetected)}
        
        {assign var="imgapp" value="android"}
        {if $TypeMobileDetected == 'android'}
            {if $MobileDetected == 'tablet'}
                {assign var="imgapp" value="tablet-android"}
            {/if}
            {*assign var="urlapp" value="https://play.google.com/store/apps/details?id=com.kubo.farmalisto&hl=es"*}
            {assign var="urlapp" value="tel:0314926363"}
        {/if}
        {if $TypeMobileDetected == 'ios'}
            {*assign var="urlapp" value="https://itunes.apple.com/us/app/farmalisto/id899599402?mt=8"*}
            {assign var="urlapp" value="tel:0314926363"}
            {assign var="imgapp" value="iphone"}
        {/if}
        {if $TypeMobileDetected == 'ipad'}
            {assign var="urlapp" value="https://itunes.apple.com/co/app/compra-medicamentos-farmalisto/id911908649?mt=8"}
            {assign var="imgapp" value="ipad"}
        {/if}

        <div id="download-app">
            <div class="orderdownload">
                  <div class="close-content-download-app">
                      <a onclick="closeappdownload();" id="close-content-download-app">
                          <img src="{$base_dir}img/close.png"/>
                      </a>
                  </div>
                  <a href="{$urlapp}">
                      <img class="content-download-app" src="{$base_dir}img/cms/landing/{$imgapp}.png" />
                  </a>
            </div>
        </div>
        <div class="download-app-overlay" id="download-app-overlay" ></div>

        <script type="text/javascript">
            function closeappdownload() {
                document.getElementById('download-app').style.display = 'none';
                document.getElementById('download-app-overlay').style.display = 'none';
            }
        </script>

        {if $TypeMobileDetected == 'android' || $TypeMobileDetected == 'ios'}
            {if $MobileDetected == 'tablet'}
                <style type="text/css">
                    #download-app {
                        left: 0;
                        position: absolute;
                        top: 0;
                        width: 100%;
                        z-index: 1000;
                    }
                    .orderdownload {
                        margin-top: 15px;
                        width: 77%;
                        max-width: 837px;
                        max-height: 590px;
                    }
                    .content-download-app {
                        position:relative;
                        width: 100%;
                        max-width: 837px;
                        max-height: 590px;
                    }
                    .download-app-overlay {
                        left: 0;
                        position: absolute;
                        top: 0;
                        width: 100%;
                        height: 1100%;
                        z-index: 900;
                        background-color: #777777;
                        opacity: 0.7;
                    }
                    .close-content-download-app {
                        position: relative;
                        top: 45px;
                        z-index: 1100;
                        margin-left: 96%;
                    }
                    #toTop {
                      z-index: 1200;
                    }
                </style>
            {else}
                <style type="text/css">
                    #download-app {
                        left: 0;
                        position: absolute;
                        top: 0;
                        width: 100%;
                        z-index: 1000;
                        max-width: initial;
                    }
                    .orderdownload {
                        margin-top: 15px;
                        min-width: 280px;
                        width: 77%;
                        max-width: 310px;
                    }
                    .content-download-app {
                        position:relative;
                        width: 100%;
                        max-width: 377px;
                        max-height: 569px;
                    }
                    .download-app-overlay {
                        left: 0;
                        position: absolute;
                        top: 0;
                        width: 100%;
                        height: 1100%;
                        z-index: 900;
                        background-color: #777777;
                        opacity: 0.7;
                    }
                    .close-content-download-app {
                        position: relative;
                        top: 32px;
                        z-index: 1100;
                        margin-left: 93%;
                    }
                    #toTop {
                      z-index: 1200;
                    }
                </style>
            {/if}
        {else}
            <style type="text/css">
                #download-app {
                    left: 0;
                    position: absolute;
                    top: 0;
                    width: 100%;
                    z-index: 1000;
                }
                .orderdownload {
                    margin-top: 15px;
                    width: 77%;
                    max-width: 837px;
                    max-height: 590px;
                }
                .content-download-app {
                    position:relative;
                    width: 100%;
                    max-width: 837px;
                    max-height: 590px;
                }
                .download-app-overlay {
                    left: 0;
                    position: absolute;
                    top: 0;
                    width: 100%;
                    height: 1100%;
                    z-index: 900;
                    background-color: #777777;
                    opacity: 0.7;
                }
                .close-content-download-app {
                    position: relative;
                    top: 45px;
                    z-index: 1100;
                    margin-left: 96%;
                }
                #toTop {
                  z-index: 1200;
                }
            </style> 
        {/if}
    {/if}
{/if}

 </div>
{literal}


<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-5D8SLQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push(
{'gtm.start': new Date().getTime(),event:'gtm.js'}
);var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5D8SLQ');</script>
<!-- End Google Tag Manager -->
    

{/literal}
{*}
<!-- FIN POP-PUP DOWNLOAD APP FARMALISTO -->
        
        <!-- Start Alexa Certify Javascript  as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; -->
        <script type="text/javascript">
            _atrk_opts = { atrk_acct:"J+pRi1a8Dy00yS", domain:"farmalisto.com.co",dynamic: true};
            (function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "/js/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
        </script>
        <noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=J+pRi1a8Dy00yS" style="display:none" height="1" width="1" alt="" /></noscript>
        <!-- End Alexa Certify Javascript -->
  
    <!-- Begin: www.iperceptions.com --> <script type="text/javascript"> ;(function (w, d, s, l) { var r = 100, js, fjs = d.getElementsByTagName(s)[0], id = "IPerceptionsJS", a = "async", b = "defer",go = function() { if(d.getElementById(id)) { return; } js = d.createElement(s);js.src = w.location.protocol + "//ips-invite.iperceptions.com/webValidator.aspx?sdfc=97f7aad6-121125-674fabf3-23d8-46f3-980a-82ec2cef430c&lID=16&source=91787"; js.id = id; js.type = "text/javascript"; js[a] = a; js[b] = b; fjs.parentNode.insertBefore(js, fjs); }; if(r < Math.floor(Math.random() * 100)) { d.cookie = "IPE_S_121125=0;Path=/;" } else if(!(/(^|;)\s*IPE(_S_)?121125=/.test(d.cookie))){ if (w.addEventListener) { w.addEventListener(l, go, false); } else if (w.attachEvent) { w.attachEvent("on" + l, go); } } })(window, document, "script", "load");</script><!-- End: www.iperceptions.com -->
    
    

<!-- SessionCam Client Integration v6.0 -->
<script type="text/javascript">
//<![CDATA[
var scRec=document.createElement('SCRIPT');
scRec.type='text/javascript';
scRec.src="//d2oh4tlt9mrke9.cloudfront.net/Record/js/sessioncam.recorder.js";
document.getElementsByTagName('head')[0].appendChild(scRec);
//]]>
</script>
<!-- End SessionCam -->
{*}
{*}
<script type="text/javascript" src="{$base_dir}js/jail/jail.js"></script>
<script language="JavaScript">
            $(document).ready(function(){
                $('img.lazy').jail({
                });
            });
</script>
{*}

{*}
<script type="text/javascript" src="{$base_dir}js/jail/Concurrent.Thread-full.min.js"></script>
Experimental no borrar
<script>

function proceso(imgDefer){

if(imgDefer.getAttribute('data-src')) {
imgDefer.setAttribute('src',imgDefer.getAttribute('data-src'));
} 
   
  }


function init() {
var imgDefer = document.getElementsByTagName('img');
for (var i=0; i<imgDefer.length; i++) {
    Concurrent.Thread.create(proceso,imgDefer[i]); 
} }

window.onload = init;
</script>
{*}

<script>
function init() {
var imgDefer = document.getElementsByTagName('img');
for (var i=0; i<imgDefer.length; i++) {
if(imgDefer[i].getAttribute('data-src')) {
imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
} } }
window.onload = init;
</script>

{if isset($js_files_footer)}
	{foreach from=$js_files_footer item=js_uri}	
		{if isset($settings->column) && $settings->column == '1_column'}
			{if !strpos($js_uri,"blocklayered.js")}
				<script type="text/javascript" src='{$js_uri|replace:"http:":"https:"}'></script>
			{/if}
		{else}
			<script type="text/javascript" src='{$js_uri|replace:"http:":"https:"}'></script>
		{/if}
	{/foreach}
{/if}
</body>

<!-- Start of LiveChat (www.livechatinc.com) code -->
<script type="text/javascript">
window.__lc = window.__lc || {};
window.__lc.license = 6077601;
window.__lc.chat_between_groups = false;
(function() {
 var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
 lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>
<!-- End of LiveChat code -->

{*
<script>
{if $flagPopPup == 1 }
    var cumplepp = 1;
    {else}
    var cumplepp = 0;
{/if}
    
    if( cumplepp == 1 ){
        $(window).load(function(){
            standard_lightbox('care-lines');
        });
    }
</script>

<div id="care-lines" style="display:none;">
    <div class="lightbox_close" onclick="lightbox_hide();"></div>
    <div class="lightbox_title"></div>
    <div class="lightbox_resume">
        <img src="{$img_dir}21_de_Octubre.jpg">
    </div>
</div>
*}
{if isset($js_files_footer)}
	{foreach from=$js_files_footer item=js_uri}	
		{if isset($settings->column) && $settings->column == '1_column'}
			{if !strpos($js_uri,"blocklayered.js")}
				<script type="text/javascript" src='{$js_uri|replace:"http:":"https:"}'></script>
			{/if}
		{else}
			<script type="text/javascript" src='{$js_uri|replace:"http:":"https:"}'></script>
		{/if}
	{/foreach}
{/if}
</html>
