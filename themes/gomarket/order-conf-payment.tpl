{if $order->payment=='COD-Efectivo'}
    <div class="payment-detail">
        <div class="payment-logo"><img src="{$img_dir}ThankYouPage-PagoEfectivo.jpg"/></div>
        <div class="payment-desc"><b>Seleccionaste pago contra entrega <span style="color: #67b164">en efectivo.</span></b>
            <br/> Ya estamos <b>preparando</b> tu pedido
        </div>
    </div>
{elseif $order->payment=='COD-Tarjeta'}
    <div class="payment-detail">
        <div class="payment-logo"><img src="{$img_dir}ThankYouPage-Datafono.jpg"/></div>
        <div class="payment-desc"><b>Seleccionaste pago contra entrega <span style="color: #67b164">con datáfono.</span></b>
            <br/> Ya estamos <b>preparando</b> tu pedido
        </div>
    </div>
{elseif $order->payment=='Tarjeta_credito'}
    <div class="payment-detail">
        <div class="payment-logo"><img src="{$img_dir}ThankYouPage-PagoTarjeta.jpg"/></div>
        <div class="payment-desc"><b> Has seleccionado pago por<span style="color: #67b164"> tarjeta de crédito</span></b>
                En cúanto sea <b>confirmada la transacción te informaremos.</b></div>

    </div>
{elseif $order->payment=='Baloto'}
    <div style="    font-weight: bold; font-size: 18px; margin-bottom: 18px;">Estos son los datos para realizar tu pago vía Baloto</div>
    <div class="payment-detail">
        <div class="payment-logo"><img src="{$img_dir}ThankYouPage-Logo_Baloto.jpg"/></div>
        <div class="payment-desc">
            Convenio Baloto: <b>950110</b> <br/>
            Número de pago: <b>{$order->numPago} </b> <br/>
            Fecha expiración: <b>{$order->fechaCadu } </b> <br/>
        </div>
    </div>
{elseif $order->payment=='Efecty'}
    <div style="    font-weight: bold; font-size: 18px; margin-bottom: 18px;">Estos son los datos para realizar tu pago vía Efecty</div>
    <div class="payment-detail">
        <div class="payment-logo"><img src="{$img_dir}ThankYouPage-Logo_Efecty.jpg"/></div>
        <div class="payment-desc">
            Convenio Efecty: <b>110528</b> <br/>
            Número de pago: <b>{$order->numPago} </b> <br/>
            Fecha expiración: <b>{$order->fechaCadu } </b> <br/>
        </div>
    </div>
{elseif $order->payment=='Pse'}
    <div class="payment-detail">
        <div class="payment-logo"><img src="{$img_dir}ThankYouPage-PagoTarjeta.jpg"/></div>
        <div class="payment-desc"><b>Seleccionaste pago  <span style="color: #67b164">con Pse.</span></b>
            <br/> Ya estamos <b>preparando</b> tu pedido
        </div>
    </div>
{/if}

