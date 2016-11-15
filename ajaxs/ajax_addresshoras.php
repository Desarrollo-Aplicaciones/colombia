
<?php>
	{
		$.ajax({
			type:"POST",
			url: "Address.php",
			dataType: "json",
			data : {
				ajax: "1",				
				action: "updatedate",
				
				
				},
			success : function(res)
			{
				displaySummary(res);
			}
		});
	
?>

