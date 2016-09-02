  
$(document).ready(function(){
	$("#id_type").change(function(){
	    if ( $("#id_type").val() == 4 ){
	    	document.getElementById("groupBox_4").checked = true;
	    }
	    else
	    	document.getElementById("groupBox_4").checked = false;
	});
});
