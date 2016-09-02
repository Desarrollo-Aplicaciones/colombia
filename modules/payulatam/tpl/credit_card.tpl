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
*  @version  Release: $Revision: 14011 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($error)}
    <p style="color:red">{l s='An error occured, please try again later.' mod='payulatam'}</p>
{else}
    <script src="{$js_dir}jquery.creditCardValidator.js"></script>      
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
        $(function(){ 
            var nombre = $("input#nombre");
            var codigo = $("input#codigot");
            if ( nombre.val() == '' ){
                codigo.prop("disabled", "true");
            }
            nombre.focusout(function(){
                if ( nombre.val() != '' ){
                    codigo.removeProp("disabled", "true"); 
                }          
                else {
                    codigo.prop("disabled", "true");
                    console.log("llegue fofio");
                }
            });
            
            codigo.focus(function(){
                codigo.prop("type", "password");
            });
            codigo.focusout(function(){
                if ( codigo.val() == '' ) {
                    codigo.removeProp("type", "password");
                    if ( nombre.val() != '' ){
                    codigo.removeProp("disabled", "true"); 
                    }  
                }
            });
        }); 
    </script>

    <script type="text/javascript">    
        var ruta = "{$modules_dir}payulatam/ajax_bines.php"; 
        var divbeneficio = '<div id="div_beneficio" class="div_beneficio" > <div class="div_img_beneficio" > <img id="img_beneficio" class="img_beneficio"  > </div> <div id="txt_beneficio" class="div_txt_beneficio"></div></div>';
        $(function(){
            {literal}
                $( "#numerot" ).change(function() {
                    // enviar solicitutd              
                    $.ajax(ruta, {
                        "type": "post", // usualmente post o get
                        "success": function(result) {
                            if(result!='0'){
                                if ($('#div_beneficio').length) {
                                    $( "#div_beneficio" ).remove();
                                }
                                var Array = result.split('|');
                                $('#formfiles').append(divbeneficio);
                                $("#img_beneficio").attr('src', '{/literal}{$img_dir}{literal}mediosp/bancos/'+Array[1]);
                                $('#txt_beneficio').append('<b>Descuento del '+Array[0]+'%.</b>');
                            }
                            else{
                                if ($('#div_beneficio').length) {
                                    $( "#div_beneficio" ).remove();
                                }
                            }
                        },
                        "error": function(result) {
                            console.log("Error ajaxbines -> "+result);
                        },
                        "data": {numerot: $( "#numerot" ).val(), accion: "ajax_bin"},
                        "async": true
                    });
                });
            {/literal}
            $('.date-picker').datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy/mm',
                /*yearRange: (new Date).getFullYear()+'2018'*/
                minDate:'m',
                maxDate:'+12Y',
                onClose: function(dateText, inst) { 
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            });
            var validator = $('#formPayU').validate({
                {literal}
                    wrapper: 'div',
                    errorPlacement: function (error, element) {
                        error.addClass("arrow")
                        error.insertAfter(element);
                    },
                {/literal}
                rules :{
                    numerot : {
                        required : true,
                        number : true,   //para validar campo solo numeros
                        minlength : 14 , //para validar campo con minimo 3 caracteres
                        maxlength : 16 //para validar campo con maximo 9 caracteres                                   
                    },
                    codigot : {
                        required : true,
                        number : true,   //para validar campo solo numeros
                        minlength : 3 , //para validar campo con minimo 3 caracteres
                        maxlength : 4 //para validar campo con maximo 9 caracteres                                   
                    },
                    nombre : {
                      required : true
                    },
                    datepicker : {
                      required : true
                    },
                    Month : {
                        required : true
                    },
                    Year : {
                        required : true
                      },
                    cuotas : {
                      required : true
                    }
                },
                messages: {
                    numerot: { 
                        required: "Campo Requerido.",
                        number : "Campo Requerido.",
                        minlength: "Campo Requerido.",
                        maxlength: "Campo Requerido.",
                    },
                    codigot: { 
                        required: "Campo Requerido.",
                        number : "Campo Requerido.",
                        minlength: "Campo Requerido.",
                        maxlength: "Campo Requerido.",
                    },
                    nombre : {
                        required : "El campo es requerido."
                    },
                    datepicker : {
                        required : "El campo es requerido."
                    },
                    Month : {
                    	required : "Mes requerido."
                    },
                    Year : {
                    	required : "A&ntilde;o requerido."
                    },
                    cuotas : {
                        required : "El campo es requerido."
                    }
                },
            });

            $("#formPayU").submit(function(event) {
                $('#numerot').validateCreditCard(function(result) {
                    if(result.card_type === null){
                        console.log("tarjeta no valida ");
                        $('#numerot').addClass('error');
                        $('#numerot').removeClass('valid');
                        event.preventDefault();
                        alert("El numero de tarjeta no es valido.");
                        //$("#tarjetainvalida").css("display: inline;");
                    }
                    else{
                        console.log("Valida ok");
                        $('#numerot').removeClass('error');
                        $('#numerot').addClass('valid');
                        //$("#tarjetainvalida").css("display: none;");
                    }
                });
            });
        });
        
        $(function($){
            $.datepicker.regional['es'] = {
                closeText: 'Ok',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi    \u00e9rcoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mi   \u00e9','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        });
        function cambiaFecha(){
    	   if(($('#año').val()) != "" && ($('#mes').val()) != ""){
    		  $('#datepicker').val($('#año').val()+"/"+$('#mes').val());
        	}
        	else{
        		$('#datepicker').val("");
        	}
        }
    </script>

    <style type="text/css">
        .ui-datepicker-calendar {
            display: none;
        }
            
        .div_beneficio{
            display: inline-block; width: 100%;
        }

        .div_img_beneficio{
            min-width: 49%;
            max-width: 100%;
            width: 49%;
            text-align: left;
            float: left;
            padding: 3px;
        }
          
        .div_txt_beneficio{
            float: left;  
            min-width: 49%;
            max-width: 100%; 
            margin: 28px 5px 0 0;
            color: #009207;
        }

        .img_beneficio{
            height: 80px;
        }
    </style>
<META http-equiv="Pragma" content="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="cache-control" content="no-cache" />
    <div class="pagocont">
        <form  method="POST" action="./modules/payulatam/credit_card.php" id="formPayU" autocomplete="off" >
            <div>
                <div id="formfiles" class="contend-form">
                    <div class="ctn-vlr-total-pedido">
                        El valor total de tu pedido es de <strong class="ctn-vlr-total-pedido-semibold">{displayPrice price=$total_price} (Impuestos incl.)</strong>
                    </div>
                    <div class="cardAttr">
                        {* <div class="textCard">Número de Tarjeta de Crédito<span class="purple">*</span>: </div> *}
                        <input type="text" name="numerot" autocomplete="off" data-openpay-card="card_number" id="numerot" placeholder="Número de Tarjeta de Crédito o Débito *"/>
                    </div>
                    <div class="cardAttr">
                        {* <div class="textCard">Nombre del Titular<span class="purple">*</span>: </div> *}
                        <input type="text" name="nombre" id="nombre" autocomplete="off" value="" placeholder="Nombre del Titular  *" />
                    </div>
                    <div class="cardAttr">
                        <div class="textCard">Fecha de vencimiento * 
                        </div>
                        <input type="hidden" id="datepicker" name="datepicker" class="date-picker" placeholder="mm/yyyy" >
                        <div class="cont_select">
                            {html_select_date prefix=NULL end_year="+15" month_format="%m" year_empty="año *" year_extra='id="año" class="select-fecha-tarjetas" onchange="cambiaFecha()"' month_empty="mes *" month_extra='id="mes" class="select-fecha-tarjetas" onchange="cambiaFecha()"' display_days=false field_order="DMY" time=NULL}
                        </div>
                    </div>
                    <div class="cardAttr">
                        {* <div class="textCard">Número de verificación<span class="purple">*</span>: </div> *}
                        <input name="codigot" id="codigot" autocomplete="off" placeholder="Número de verificación *" value="">
                    </div>
                    <div class="cardAttr">
                        {* <div class="textCard">Número de cuotas<span class="purple">*</span>: </div> *}
                        <select name="cuotas" id="cuotas" class="select-100">
                                <option value="" disabled selected>Número de cuotas *</option>
                            {for $foo=1 to 36}
                                <option value="{$foo|string_format:'%2d'}">{$foo|string_format:"%2d"}</option>
                            {/for}
                        </select>
                    </div>
                    <br/>
                    <div class="cont-trust-img">
                        <img class="trust_img" src="{$img_dir}authentication/seguridad.jpg" />
                    </div>
                    <div class="cont-trust-img">
                        <input type="button" onclick="$('#botoncitosubmit').click();" class="paymentSubmit boton-pagos-excep" value="PAGAR">
                        {* <input type="button" id="submit_btn" onclick="$('#botoncitosubmit').click();" class="paymentSubmit boton-pagos-excep" value="PAGAR"> *}
                    </div>
                </div>
            </div>
        </form>
    </div>
{/if}
