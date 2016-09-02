{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<link rel="stylesheet" href="{$css_dir}order-confirmation.css" type="text/css" media="screen" charset="utf-8" />
{capture name=path}{l s='Order confirmation'}{/capture}
{*include file="$tpl_dir./breadcrumb.tpl"*}

<h1>{l s='Order confirmation'}</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{include file="$tpl_dir./errors.tpl"}

{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}

{literal}

<p class="titulo">
		¡Excelente, tu pedido en Farmalisto.com.co se ha registrado con éxito!
</p>
<p class="importante">
	NOTA IMPORTANTE:
</p>

<p class="parrafo">
	<span class="strong">Bogotá:</span> 
	Sí realizaste tu pedido entre 1:00 AM y las 08:00 AM lo recibirás en horas de la Mañana del mismo día, 
	si lo realizaste después de las 8:00 AM y antes de las 04:00 pm, lo recibirás el mismo día en horas de la mañana y tarde, 
	despues de 04:00pm la entrega esta sujeta a disponibilidad de inventario. Los pedidos será entregados de lunes a viernes entre 8:00 AM a 9:00 PM y el Sábado, los domingos y festivos de 08:00 AM a 6:00 PM, se realizan entregas sujetas a disponibilidad de inventario.
</p>

<div class="recuerda_div">
	<img title="Recuerda"
	 src="img/cms/Landing-Page/Icono_Gracias.jpg"
	 alt="Recuerda"
	 class="recuerda_img"/>
	<span class="recuerda_txt1">Recuerda:</span><br><br>
	<span class="recuerda_txt2">Para medicamentos formulados se debe presentar la receta médica en el momento de recibir el producto.</span>
</div>
<p class="parrafo" style="font-size: 14pt;">
	<span class="strong">Resto del país:</span> 
	Tus pedidos serán entregados en un plazo máximo de 48 horas hábiles a partir de la confirmación de tu pedido.
</p>

<img title="Confirmación pedido"
	 src="img/cms/Landing-Page/confirmacion.jpg"
	 alt="Confirmación pedido"
	 class="imagen"/>
{/literal}

{if !isset($smarty.cookies.validamobile)}

	{literal}
	<p class="text">
		<span class="follow">Síguenos:</span>
		<a href="https://www.facebook.com/farmalistocolombia">
			<img title="facebook"
				 src="img/cms/socialmedia/REDES_SOCIALES/FB.png"
				 alt="facebook"
				 width="62"
				 height="40" />
		</a>
		<a href="https://twitter.com/farmalistocol">
			<img title="twitter"
				 src="img/cms/socialmedia/REDES_SOCIALES/TW.png"
				 alt="twitter"
				 width="62"
				 height="40" />
		</a>
		<a href="https://www.youtube.com/farmalistocolombia">
			<img title="YouTube"
				 src="img/cms/socialmedia/REDES_SOCIALES/YT.png"
				 alt="YouTube"
				 width="62"
				 height="40" />
		</a>
		<a href="https://plus.google.com/+FarmalistoColombia/posts">
			<img title="Google+"
				 src="img/cms/socialmedia/REDES_SOCIALES/G+.png"
				 alt="Google+"
				 width="62"
				 height="40" />
		</a>
		<a href="http://www.linkedin.com/company/farmalisto">
			<img title="LinkedIn"
				 src="img/cms/socialmedia/REDES_SOCIALES/IN.png"
				 alt="LinkedIn"
				 width="62"
				 height="40" />
		</a>
		<a href="https://es.foursquare.com/v/farmalisto/52a5d642498edb2474373525">
			<img title="Foursquare"
				 src="img/cms/socialmedia/REDES_SOCIALES/FS.png"
				 alt="Foursquare"
				 width="62"
				 height="40" />
		</a>
	</p>
	{/literal}
	
{/if}

{literal}
<p class="text">
	Consulta el estado de tus pedidos 
	<span class="strong">
{/literal}

{if !isset($smarty.cookies.validamobile)}
	{if $is_guest}
		<a href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order}&email={$email}")|escape:'html'}" title="{l s='Follow my order'}" class="text">aquí</a>
	{else}
		<a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='Back to orders'}" class="text">aquí</a>
	{/if}
{/if}

{literal}
	</span>
</p>
<p class="text">
	<span class="strong">
		¿Tienes más preguntas? 
	</span>
	¡Quizá en la sección de preguntas frecuentes encuentres una respuesta inmediata!
</p>
{/literal}
{if !isset($smarty.cookies.validamobile)}
	{literal}
		<p class="text">
			<span class="strong">
				<a href="content/1-entregas" target="_blank" class="text">
					Clic aquí
				</a>
			</span>
		</p>
		<p class="text">
			<a href="/">
				<img title="Volver a la tienda"
					 src="img/cms/socialmedia/REDES_SOCIALES/perfil%20horarios-03.png"
					 alt="Volver a la tienda"
					 width="100"
					 height="101" />
			</a>
		</p>
		<p class="text">
			<a href="/" class="text">
				Volver a la tienda
			</a>
		</p>
	{/literal}
{/if}






{if isset($pse) && $pse!=false }

    <script type="text/javascript">

        function redireccionar(){
            window.location="{$bankdest2}";
        } 

        $(document).ready(function(){
            setTimeout ("redireccionar()", 1000); 
        });
    </script>
{/if}

<!--    eliminar cookie para validacion, aplicacion -> pagina web -->
{if isset($smarty.cookies.validamobile)}
    <script type="text/javascript">
        document.cookie = 'validamobile="true"; expires=Thu, 01 Jan 1970 00:00:01 UTC; path=/';
    </script>
{/if}

