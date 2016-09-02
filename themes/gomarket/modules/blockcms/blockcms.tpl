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
	</div>
	{* <div class="fondo-container">
		<img src="{$img_dir}footer/Linea_superior_footer.jpg" width="100%"/>
		<div class="footer-gris"></div>
		<div class="footer-security"></div>
	</div> *}
</div>

<!--Redes sociales-->
		{* <div class="social_logo_container">
			<p>Síguenos:</p>
			<a href="https://www.facebook.com/farmalistocolombia" alt="facebook farmalisto" target="blank">
				<img src="{$img_dir}footer/Fb.jpg" class="footer_social"/>
			</a>
			<a href="https://twitter.com/farmalistocol" alt="twitter farmalisto" target="blank">
				<img src="{$img_dir}footer/tw.jpg" class="footer_social"/>
			</a>
			<a href="https://plus.google.com/+FarmalistoColombia/about" alt="google+ farmalisto" target="blank">
				<img src="{$img_dir}footer/g+.jpg" class="footer_social"/>
			</a>
			<a href="https://www.youtube.com/farmalistocolombia" alt="youtube farmalisto" target="blank">
				<img src="{$img_dir}footer/yt.jpg" class="footer_social"/>
			</a>
			<a href="https://www.linkedin.com/company/farmalisto" alt="linkedIn farmalisto" target="blank">
				<img src="{$img_dir}footer/in.jpg" class="footer_social"/>
			</a>
			<a href="https://es.foursquare.com/v/farmalisto/52a5d642498edb2474373525" alt="foursquare farmalisto" target="blank">
				<img src="{$img_dir}footer/fs.jpg" class="footer_social"/>
			</a>
		</div> *}
<!--/Redes sociales-->


<div class="container_24">
	<div id="footer" class="grid_24 clearfix  omega alpha">


<div style="display:none">
		{if $block == 1}
			<!-- Block CMS module -->
			{foreach from=$cms_titles key=cms_key item=cms_title}
				<div id="informations_block_left_{$cms_key}" class="block informations_block_left">
					<h4 class="title_block">
						<a href="{$cms_title.category_link|escape:'html'}">
							{if !empty($cms_title.name)}
								{$cms_title.name}
							{else}
								{$cms_title.category_name}
							{/if}
						</a>
					</h4>
					<ul class="block_content">
						{foreach from=$cms_title.categories item=cms_page}
						asdasdasdasdasdasdasdasd
							{if isset($cms_page.link)}
								<li class="bullet">
									<b style="margin-left:2em;">
										<a href="{$cms_page.link|escape:'html'}" title="{$cms_page.name|escape:html:'UTF-8'}">{$cms_page.name|escape:html:'UTF-8'}</a>
									</b>
								</li>
							{/if}
						{/foreach}
						{foreach from=$cms_title.cms item=cms_page}
							{if isset($cms_page.link)}
								<li>
									<a href="{$cms_page.link|escape:'html'}" title="{$cms_page.meta_title|escape:html:'UTF-8'}">{$cms_page.meta_title|escape:html:'UTF-8'}</a>
								</li>
							{/if}
						{/foreach}
						{if $cms_title.display_store}
							<li>
								<a href="{$link->getPageLink('stores')|escape:'html'}" title="{l s='Our stores' mod='blockcms'}">{l s='Our stores' mod='blockcms'}</a>
							</li>
						{/if}
					</ul>
				</div>
			{/foreach}
			<!-- /Block CMS module -->

		{else}
			<!-- MODULE Block footer -->
			<div class="block_various_links" id="block_various_links_footer">
				<div class="block">
					<h4 class="title_block">
						{l s='Information' mod='blockcms'}
					</h4>
					<ul class="f_block_content">

						{*
						{ foreach from=$cmslinks item=cmslink}
							{if $cmslink.meta_title != ''}
								<li class="item">
									<a href="{$cmslink.link|addslashes}" title="{$cmslink.meta_title|escape:'htmlall':'UTF-8'}">{$cmslink.meta_title|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/foreach }
						{ if !$PS_CATALOG_MODE}
							<li class="first_item">
								<a href="{$link->getPageLink('prices-drop')}" title="{l s='Specials' mod='blockcms'}">{l s='Specials' mod='blockcms'}</a>
							</li>
						{/if }
						<li class="{if $PS_CATALOG_MODE}first_{/if}item">
							<a href="{$link->getPageLink('new-products')}" title="{l s='New products' mod='blockcms'}">{l s='New products' mod='blockcms'}</a>
						</li>
						{ if !$PS_CATALOG_MODE}
							<li class="item">
								<a href="{$link->getPageLink('best-sales')}" title="{l s='Best sellers' mod='blockcms'}">{l s='Best sellers' mod='blockcms'}</a>
							</li>
						{/if }
						*}

						<li class="item">
							<a href="{$base_uri}?id_cms=8&controller=cms" target="_self" title="{l s='About us' mod='blockcms'}">{l s='About us' mod='blockcms'}</a>
						</li>
						<li class="item">
							<a href="{$cmslinks.0_3.link|addslashes}" title="{$cmslinks.0_3.meta_title|escape:'htmlall':'UTF-8'}">{$cmslinks.0_3.meta_title|escape:'htmlall':'UTF-8'}</a>
						</li>
						<li class="item">
							<a href="{$cmslinks.0_1.link|addslashes}" title="{$cmslinks.0_1.meta_title|escape:'htmlall':'UTF-8'}">Horarios de Atención</a>
						</li>
						<li class="item">
							<a href="http://prensa.farmalisto.com" target="_blank" title="{l s='Prensa' mod='blockcms'}">{l s='Prensa' mod='blockcms'}</a>
						</li>
						{if $display_poweredby}
							<li class="last_item">
								{l s='Powered by' mod='blockcms'}
								<a class="_blank" href="http://www.prestashop.com">PrestaShop</a>
								&trade;
							</li>
						{/if}
					</ul>
				</div>

				<div class="block">
					<h4 class="title_block">
						{l s='Online store' mod='blockcms'}
					</h4>
					<ul class="f_block_content">
						{* 
						{if $display_stores_footer}
							<li class="item">
								<a href="{$link->getPageLink('stores')}" title="{l s='Our stores' mod='blockcms'}">{l s='Our stores' mod='blockcms'}</a>
							</li>
						{/if}
						<li class="item">
							<a href="{$link->getPageLink('manufacturer')}" title="{l s='Marcas' mod='blockcms'}">{l s='Marcas' mod='blockcms'}</a>
						</li>
						<li class="item">
							<a href="http://www.farmalisto.com.co/content/6-garantia-del-mejor-precio" target="_self" title="{l s='Garantía del mejor precio' mod='blockcms'}">{l s='Garantía del mejor precio' mod='blockcms'}</a>
						</li>
						*}
						<li class="item">
							<a href="{$cmslinks.0_1.link|addslashes}" title="{$cmslinks.0_1.meta_title|escape:'htmlall':'UTF-8'}">{$cmslinks.0_1.meta_title|escape:'htmlall':'UTF-8'}</a>
						</li>
						<li class="item">
							<a href="{$link->getPageLink($contact_url, true)}" title="{l s='Contact us' mod='blockcms'}">{l s='Contact us' mod='blockcms'}</a>
						</li>
						<li class="item">
							<a href="{$link->getPageLink('sitemap')}" title="{l s='Sitemap' mod='blockcms'}">{l s='Sitemap' mod='blockcms'}</a>
						</li>
					</ul>
				</div>
				{$footer_text}
			</div>
			<!-- /MODULE Block footer -->
		{/if}
</div>