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

{if count($categoryProducts) > 0 && $categoryProducts !== false}

<div class="clearfix blockproductscategory" id="textosseo">
    <h2 class="productscategory_h2">{$categoryProducts|@count} {l s='other products in the same category:' mod='productscategory'}</h2>
	<div id="{if count($categoryProducts) > 5}productscategory{else}productscategory_noscroll{/if}" id="contenedor1">
            
            <div id="productscategory_list" class="list_carousel responsive">
			<ul id="carousel-productscategory" {if count($categoryProducts) > 5}style="width: {math equation="width * nbImages" width=107 nbImages=$categoryProducts|@count}px"{/if}>
				
                            {foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
				<li id="elei">
                                    
                                  <div class="center_block" id="contenedor2">
                                    <div class="image" id="contenedor3">
					<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="lnk_img product_img_link" title="{$categoryProduct.name|htmlspecialchars}">
{assign var="image_rx" value=false}
{foreach from=$categoryProduct.features item=feature}
	{if $feature.id_feature == "11" && ($feature.value|upper == "SI" || $feature.value|upper == "SÍ")}
		{$image_rx = true}
	{/if}
{/foreach}		
					{if $image_rx == false}		
					<img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')}" alt="{$categoryProduct.name|htmlspecialchars}" style="max-width:150px;max-height:100px"/>
					{else}
					<img src="{$base_dir}img/p/image_rx.jpg"  alt="{$categoryProduct.name|htmlspecialchars}" style="max-width:150px;max-height:100px" />
					{/if}					
					</a>
				  
                                    </div>
					<img id="imagenn" src="{$img_dir}resum_product_line.png">
                                
                                
                                <div class="name_product" id="contenedorProducto">
                                            <div id="tituloProduc" >
                                            	<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" title="{$categoryProduct.name|htmlspecialchars}">
                                            		{$categoryProduct.name|truncate:45:'...'|lower|capitalize}
                                            	</a>
                                            </div> 
                                            <div class="price" id="PrecioPrice">{convertPrice price=$categoryProduct.displayed_price}</div>
                                                                                     

								</div>
					<!--p class="desription">{$categoryProduct.description_short|strip_tags:'UTF-8'|truncate:90:'...'}</p-->
					{if $ProdDisplayPrice AND $categoryProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                            
                                            <p class="price_display" style=" "></p>
					{/if}
					{if ($categoryProduct.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $categoryProduct.available_for_order && !isset($restricted_country_mode) && $categoryProduct.minimal_quantity <= 1 && $categoryProduct.customizable != 2 && !$PS_CATALOG_MODE}
					{if ($categoryProduct.allow_oosp || $categoryProduct.quantity > 0)}
						{if isset($static_token)}

	{*if $image_rx == false*}
	<div id="botonComprar">
     <a class="botonComprar" rel="ajax_id_product_{$categoryProduct.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$categoryProduct.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Comprar' mod='productscategory'}"> 
       {l s='COMPRAR' mod='productscategory'}
   	 </a>
    </div>
    {*/if*}
						{else}
							<a class="button ajax_add_to_cart_button exclusive" style="  " rel="ajax_id_product_{$categoryProduct.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$categoryProduct.id_product|intval}", false)}" title="{l s='Agregar al carrito' mod='productscategory'}">{l s='Agregar al carrito' mod='productscategory'}</a>
						{/if}						
					{else}
                                            <div id="botonAgotado">
						<span style="float: left;margin-top: 11px;margin-left: 44px;color: #fff;">{l s='AGOTADO' mod='productscategory'}</span>
                                            </div>
					{/if}
				{/if}
				</div>
                                
				</li>
				{/foreach}
			</ul>
                        <div class="cclearfix" id="textoSeo">{literal}En Farmalisto no tienes que saber donde comprar {/literal}<label id="tituloCategoryProd"></label>{literal} y preguntar por el precio en Colombia. ¿Quieres saber el valor de tus medicamentos? ¡Esta es la página indicada para comprar, no solo en Bogotá, sino en todo el país, de manera fácil y amigable, busca tus medicamentos en frasco, jarabe, tabletas, pastillas siempre al mejor precio!
En el año todos podemos prevenir los síntomas del virus Chikungunya / Chikunguña y del virus zika producido por mosquitos portadores Aedes: No dejes tu piel expuesta al ambiente y utiliza repelentes de larga duración. En caso de presentar síntomas como dolor articular o muscular, fiebre alta y en algunos casos erupciones cutáneas, recomendamos tomar acetaminofén en caso de efectos secundarios consulta al médico. Adquiere tu repelente y acetaminofén al mejor precio del mercado comprando en Farmalisto.com.co sí los síntomas persisten acércate a un centro de salud.<br>
¿No puedes pedir tus medicamentos? ¿No tienes una Farmacia cerca?
{/literal}</div> 


			<a id="prev-productscategory" class="btn prev" href="#">&lt;</a>
			<a id="next-productscategory" class="btn next" href="#">&gt;</a>
		</div>
	</div>
	<script type="text/javascript">
		$(window).load(function(){
			//	Responsive layout, resizing the items
			$('#carousel-productscategory').carouFredSel({
				responsive: true,
				auto: false,
				height : 'variable',
				prev: '#prev-productscategory',
				next: '#next-productscategory',
				swipe: {
					onTouch : true
				},
				items: {
					width: 200,
					height : 'variable',					
					visible: {
                            //  este valores indican el minimo yl maximo de productos que debe mostar el slide, cuando las resolucion es minima o maxima    
						min: 1,
						max: 5
					}
				},
				scroll: {
					items : 1 ,       //  The number of items scrolled.
					direction : 'left',    //  The direction of the transition.
					duration  : 500   //  The duration of the transition.
				}
			});
		});
	</script>
</div>
{/if}
