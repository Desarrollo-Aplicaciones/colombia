<html>
<meta http-equiv="refresh" content="300">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {



        $.ajax({
								type: "POST",
								url:  "//www.farmalisto.com.co/admin8256/index.php?controller=AdminImages&token=866a09ddd815f0fd8ba08e24e0343e63",
								
								data: "type=products&format_products=all&submitRegenerateimage_type=Regenerar miniaturas",
								beforeSend: function(objeto){
									//alert("antes envio");
									//$('#loading_forms').fadeIn(500);
								},
								success: function(response) {
									//alert("enviado");
									},
								complete: function(objeto, exito){
									//alert("completado");
									//$('#loading_forms').fadeOut(1000);
								},
								error: function(jqXHR, textStatus, errorThrown) {
								
								}
							});
    });
</script>

</html>