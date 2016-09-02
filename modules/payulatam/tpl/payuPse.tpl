{if isset($error)}
<p style="color:red">{l s='An error occured, please try again later.' mod='payulatam'}</p>
{else}

{literal}




<script>
function validar_texto(e){
  tecla = (document.all) ? e.keyCode : e.which;
    //Tecla de retroceso para borrar, siempre la permite
    if ((tecla==8)||(tecla==0)){
      return true;
  }
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
</script>
<script>
function pulsar(e) {
    tecla = (document.all) ? e.keyCode :e.which;
    return (tecla!=13);
}


</script>

{/literal}

<script type="text/javascript">

var ruta = "{$modules_dir}payulatam/ajax_bines.php"; 
var divbeneficio = '<div id="div_beneficio" class="div_beneficio" > <div class="div_img_beneficio" > <img id="img_beneficio" class="img_beneficio"  > </div> <div id="txt_beneficio" class="div_txt_beneficio"></div></div>';

$(function(){

    {literal}
    $( "#pse_bank" ).change(function() {

  // enviar solicitutd              
  $.ajax(ruta, {
   "type": "post", // usualmente post o get
   "success": function(result) {

    if(result!='0')
    {          

      if ($('#div_beneficio').length) {

        $( "#div_beneficio" ).remove();

    }

    var Array = result.split('|');
    $('#pse_contend_form').append(divbeneficio);
    $("#img_beneficio").attr('src', '{/literal}{$img_dir}{literal}mediosp/bancos/'+Array[1]);
    $('#txt_beneficio').append('<b>Descuento del '+Array[0]+'%.</b>');
}else{

   if ($('#div_beneficio').length) {

      $( "#div_beneficio" ).remove();

  }

}
},
"error": function(result) {
  console.log("Error ajaxbines -> "+result);
},
"data": {entidad: $( "#pse_bank" ).val(), accion: "ajax_bin_pse"},
"async": true
});    


});

    {/literal}
     

        var id_sel_b = $('#pse_bank').val();
        if (id_sel_b==""){  
          $('#pse_bank').html('<option value="" selected="selected">Cargando Listado de Bancos</option>');          
          $.ajax({
            type: "post",
            url: "{$base_dir}modules/payulatam/ajax_listado_b.php",
            data: {
              "id_state":id_sel_b
          },
          success: function(response){
              var json = $.parseJSON(response);                        
              $('#pse_bank').html('<option value="" selected="selected">Seleccione una entidad</option>'+json.results);
          }
      });
      }




    $('#formPayUPse').validate({
      {literal}
      wrapper: 'span',
      errorPlacement: function (error, element) {
        error.css({'padding-left':'10px','margin-right':'20px','padding-bottom':'2px'});
        error.addClass("arrow")
        error.insertAfter(element);
    },
    {/literal}            
    rules :{                
        pse_bank : {
          required : true                                                
      },                
      pse_tipoCliente : {
          required : true                     
      },
      pse_docType : {
          required : true
      },
      pse_docNumber : {
          required : true,                    
                    minlength : 5 , //para validar campo con minimo 3 caracteres
                    maxlength : 16 //para validar campo con maximo 9 caracteres      
                }
            },
            messages: {
              pse_bank: { 
                required: "Campo requerido."
            },
            pse_tipoCliente: { 
                required: "Campo requerido."
            },
            pse_docType : {
                required: "Campo requerido."
            },
            pse_docNumber : {
                required : "Campo requerido.",
                minlength: "Por favor ingrese m&iacute;nimo 5 caracteres.",
                maxlength: "Por favor ingrese m&aacute;ximo 16 caracteres.",
            }
        },
    });    
});





function bank()
{
   //alert($("#pse_bank :selected").text());    
   $("#name_bank").val($("#pse_bank :selected").text()); 
}




</script>

<div class="pagocont">
    <form  method="POST" action="./modules/payulatam/payuPse.php" id="formPayUPse" name="formPayUPse" autocomplete="off" >
        <div>
            <div id="pse_contend_form" class="contend-form" >
                
                <div class="cardAttr">
                    <select id="pse_bank" name="pse_bank" onchange="bank()" class="select-100">
                        <option value="" disabled selected>Banco *</option>
                    </select>
                    <input type="hidden" value="" name="name_bank" id="name_bank"/>
                    <label class="error" for="pse_bank"></label>
                </div>

                <div class="cardAttr">
                    <select id="pse_tipoCliente" autocomplete="off"  name="pse_tipoCliente" class="select-100">
                        <option value="" autocomplete="off"  disabled selected>Tipo de cliente *</option>
                        <option id="pse_tipoCliente" name="pse_tipoCliente" value="N">Natural</option>
                        <option id="pse_tipoCliente" name="pse_tipoCliente" value="J">Juridico</option>
                    </select>

                    {* <div class="tipclien">
                        <input type="radio" id="pse_tipoCliente" name="pse_tipoCliente" checked="checked" value="N">&nbsp;Natural&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" id="pse_tipoCliente" name="pse_tipoCliente" value="J" >&nbsp;Juridico
                    </div> *}
                    <label class="error" for="pse_tipoCliente"></label>
                </div>

                <div class="cardAttr">
                    <select id="pse_docType" name="pse_docType" class="select-100">
                        <option value="" disabled selected>Tipo de documento *</option>
                        <option value="CC">Cédula de ciudadanía.</option>
                        <option value="CE">Cédula de extranjería.</option>
                        <option value="NIT">NIT, en caso de ser una empresa.</option>
                        <option value="TI">Tarjeta de Identidad.</option>
                        <option value="PP">Pasaporte.</option>
                        <option value="IDC">Identificador único de cliente, para el caso de ID’s únicos de clientes/usuarios de servicios públicos.</option>
                        <option value="CEL">Número Móvil, en caso de identificar a través de la línea del móvil.</option>
                        <option value="RC">Registro civil de nacimiento.</option>
                        <option value="DE">Documento de identificación Extranjero.</option>
                    </select>
                    <label class="error" for="pse_docType"></label>
                </div>

                <div class="cardAttr">
                    <input type="text" autocomplete="off"  id="pse_docNumber" name="pse_docNumber" value="" placeholder="Número de documento *">
                    <label class="error" for="pse_docNumber"></label>
                </div>

                <div class="ctn-txt-informativo">
                    Recuerda tener habilitada tu cuenta corriente/ahorros para realizar compras  vía  internet. <br> No  olvides desbloquear las ventanas emergentes de tu navegador para evitar inconvenientes a la hora de realizar el pago.
                </div>
                
                <div class="cont-trust-img">
                    <input type="button" onclick="$('#botoncitosubmit').click();" class="paymentSubmit boton-pagos-excep" value="PAGAR">
                </div>
                                
                <input type="hidden" id="PaymentMethodForm_parameter_PagosOnlinePayment_Pse_pse_userAgent" name="PaymentMethodForm[parameter][PagosOnlinePayment_Pse][pse_userAgent]" value="Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0">
                
                <input type="hidden" id="PaymentMethodForm_parameter_PagosOnlinePayment_Pse_pse_sessionId" name="PaymentMethodForm[parameter][PagosOnlinePayment_Pse][pse_sessionId]" value="ldtp5nkml2ive4a9745hjt59k0">

            </div>
        </div>
    </form>
</div>
{/if}

