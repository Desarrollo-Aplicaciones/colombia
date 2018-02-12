$(document).ready(function() {    
 
    //select all the a tag with name equal to modal  
    $('a[name=modal]').click(function(e) {  
        //Cancel the link behavior  
        e.preventDefault();  
        //Get the A tag  
        var id = $(this).attr('href');  
     
        //Get the screen height and width  
        var maskHeight = $(document).height();  
        var maskWidth = $(window).width();

        $('#mask').css("width", maskHeight);
 		$('#mask').css("height", maskWidth);

        //transition effect      
        $('#mask').fadeIn(1000);      
        $('#mask').fadeTo("slow",0.8);    
     
        //Get the window height and width  
        var winH = $(window).height();  
        var winW = $(window).width();  
               
        //Set the popup window to center  
        $(id).css('top',  winH/2-$(id).height()/2);  
        $(id).css('left', winW/2-$(id).width()/2);  
     
        //transition effect  
        $(id).fadeIn(2000);  
     
    });  
     
    //if close button is clicked  
    $('.window .close').click(function (e) {  
        //Cancel the link behavior  
        e.preventDefault();  
        $('#mask, .window').hide();  
    });      
     
    //if mask is clicked  
    $('#mask').click(function () {  
        $(this).hide();  
        $('.window').hide();  
    });   

    $(".name-provider-info").click(function() {
    	
    	var idList = $(this).attr("id-list");
    	if($("#info-"+idList).is(":visible")) {
    		$("#info-"+idList).hide();	
    	} else {
    		$("#info-"+idList).show();
    	}
    });     
     
});  
 