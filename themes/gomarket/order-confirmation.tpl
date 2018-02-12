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
<link rel="stylesheet" href="{$css_dir}order-confirmation.css" type="text/css" media="screen" charset="utf-8"/>
{capture name=path}{l s='Order confirmation'}{/capture}
{*include file="$tpl_dir./breadcrumb.tpl"*}

{*<h1>{l s='Order confirmation'}</h1>*}

{assign var='current_step' value='payment'}
{*include file="$tpl_dir./order-steps.tpl"*}

{include file="$tpl_dir./errors.tpl"}

{$HOOK_ORDER_CONFIRMATION}


<div id="order-container"">

    <div class="titlebox">
        <img src="{$img_dir}ThankYouPage-Check.jpg" />
    </div>

    <div id="order-title" class="conf-block">
        Gracias por comprar en <b>Farmalisto</b> <br/>
        el número de tu pedido es: <span class="price"><b>{{$order->id}}</b></span> <br/>
        <b>por valor de: {displayPrice price=$order->total_paid_tax_incl currency=$order->id_currency}</b>
    </div>

    <div id="order-return" class="conf-block shadow">
        {* Desactivamos Hook Payment ya que todos los modulos están dentro del mismo y no tienen Order Return *}
        {* $HOOK_PAYMENT_RETURN *}

        {include file="$tpl_dir./order-conf-payment.tpl" order=$order}
    </div>

    <div id="order-comprobante" class="conf-block">
        <a href=""><img src="{$img_dir}ThankYouPage-Imprimir.jpg" /> <b>Imprimir</b> <span style="color: #6dc3a5">comprobante</span></a>
    </div>

    <div id="order-factura" class="conf-block shadow">
        <div class="formula-icon">
            <img src="{$img_dir}ThankYouPage-Alerta.jpg" />
        </div>
        <div class="formula-desc">
        Uno o más de tus productos requiere <b>fórmula médica</b>, debes <b>presentarla</b> al momento de <b>recibir tu
            pedido</b>.
        </div>
    </div>

    <div id="datos-pedido" class="conf-block shadow">
        Pedido: <b>{{$order->id}}</b>
        <div id="desglose-datos">
            <table>
                <tr>
                    <td>Fecha</td>
                    <td>{$order->date_add|date_format:"%d de %B de %Y"}</td>
                </tr>

                <tr>
                    <td>Identificacion</td>
                    <td>{$address->dni}</td>
                </tr>

                <tr>
                    <td>Nombres y Apellidos</td>
                    <td>{$name_customer}</td>
                </tr>

                <tr>
                    <td>Dirección</td>
                    <td>{$address->address1}</td>
                </tr>

                <tr>
                    <td>Ciudad</td>
                    <td>{$address->city}</td>
                </tr>

                <tr>
                    <td>Comentarios</td>
                    <td></td>
                </tr>
            </table>
        </div>

    </div>

    <div id="resumen" class="shadow">
        Resumen

        <table>
            <tbody>
            {foreach key=key item=product from=$order->getProducts()}
            <tr>
                <td>{$product.product_name}</td>
                <td>{$product.product_quantity}</td>
                <td>{displayPrice price=$product.total_price_tax_incl currency=$order->id_currency}</td>
            </tr>
            {/foreach}
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td>Subtotal</td>
                <td>{displayPrice price=$order->total_products_wt currency=$order->id_currency}</td>
            </tr>
            {*<tr>
                <td></td>
                <td>Iva</td>
                <td>$ 23232</td>
            </tr>*}
            <tr>
                <td></td>
                <td>Descuentos</td>
                <td>{displayPrice price=$order->total_discounts_tax_incl currency=$order->id_currency}</td>
            </tr>
            <tr>
                <td></td>
                <td>Domicilio</td>
                <td>{displayPrice price=$order->total_shipping_tax_incl currency=$order->id_currency}</td>
            </tr>
            <tr class="total">
                <td></td>
                <td>TOTAL</td>
                <td>{displayPrice price=$order->total_paid_tax_incl currency=$order->id_currency}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div id="buttons">
        <a href="{$link->getPageLink('history', true)|escape:'html'}">Mis pedidos</a>
        <a href="/">Seguir comprando</a>
    </div>


</div>


<div id="prefooter">
    <p>Si deseas mayor información acerca del estado de la transacción puedes comunicarte a nuestras <b> líneas de atención
            al cliente en: </b></p>

    <div id="sedes">
        <div class="sede">
            Bogotá: <br/> <b>(57 1) 492 6263 </b>
        </div>

        <div class="sede">
            Cali: <br/> <b> (+57 2) 3860083 </b>
        </div>

        <div class="sede">
            Medellín: <br/>  <b>(+57 4) 283 61 50 </b>
        </div>

        <div class="sede">
            Barranquilla: <br/> <b> (+57 5) 3197970 </b>
        </div>

        <div class="sede">
            Línea nacional: <br/> <b> 01 800 913 3830</b>
        </div>

    </div>

    <p>o enviar tus inquietudes al correo electrónico <b> contacto@farmalisto.com.co </b></p>
</div>


<script type="text/javascript">
    {if isset($id_product) && ($id_product == 39473 || $id_product == 39474 || $id_product == 39493 || $id_product == 39494)}
    var datos = {};
    datos['formId'] = "a775a964-524c-4eba-b050-9a97d41c4ffd";
    datos['lang'] = "en";
    datos['testAxId'] = "";
    datos['smActionUrl'] = "/form/7ymgpvwlezxrq1wn/";
    datos['validationToken'] = "4f979a0287ee4c0aaea99a44a7d3b9f5";

    datos['sm-form-name'] = "{$name_customer}";
    datos['sm-cst.idcustomer'] = "{$id_customer}";
    datos['sm-form-email'] = "{$email_customer}";
    datos['sm-form-phone'] = "{$phone}";
    datos['sm-cst.producto'] = "{$product}";
    datos['sm-cst.segmento'] = "{$segmento}";
    var link = "https://app2.emlgrid.com/form/7ymgpvwlezxrq1wn/contact.htm";

    $.ajax({
        url: link,
        type: "post",
        data: datos,
        success: function (respuesta) {
            console.log(respuesta);
        }
    }).always(function () {
//window.location.href = "aqui-pagina-redireccion";
//alert("aqui-alerta-si-se-necesita");
    })

    {/if}
</script>


{if isset($pse) && $pse!=false }
    <script type="text/javascript">

        function redireccionar() {
            window.location = "{$bankdest2}";
        }

        $(document).ready(function () {
            setTimeout("redireccionar()", 1000);
        });
    </script>
{/if}

<!--    eliminar cookie para validacion, aplicacion -> pagina web -->
{if isset($smarty.cookies.validamobile)}
    <script type="text/javascript">
        document.cookie = 'validamobile="true"; expires=Thu, 01 Jan 1970 00:00:01 UTC; path=/';
    </script>
{/if}

