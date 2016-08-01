/* 
/* Javascript file for widget
 */
( function($) {
    
 var btnreset = document.getElementById("mc-embedded-subscribe");  
 if (btnreset){
 btnreset.onclick = function(){ 
     var mail = document.getElementById("email").value;
     subscribe_mc(btnreset,mail); 
 };   
 }
 var input = document.getElementById("email");
 if (input){
 input.onfocus = function() {
         if (document.getElementById("email-error")) {
    document.getElementById("email-error").remove();
    }
 }; 
 }
    
function subscribe_mc(btn,email)
{   
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/);
    
    if ($('.post-error')){
        $( ".post-error" ).remove();
    }
    if ($('.post-success')){
        $( ".post-success" ).remove();
    }
    
    console.log("this id",btn);
    //Place new id for <input type="button" ...>
    btn.setAttribute("id", "id_you_have_clicked_here");

    //Get subscribe mailchimp widget id
    var full_subscribe_mc_ID;
    var element=$("#id_you_have_clicked_here");
    full_subscribe_mc_ID = element.parent().parent().attr("id");
    console.log("full_subscribe_mc_ID",full_subscribe_mc_ID);
    
    console.log('EMAIL=');
    console.log(email);
    //Check email (if email is valid)
   if(email.match(pattern))
   {
    arr_subscribe_mc_ID=full_subscribe_mc_ID.split('-');
    console.log("arr_subscribe_mc_ID",arr_subscribe_mc_ID);
    subscribe_mc_ID = arr_subscribe_mc_ID.pop();
    console.log("subscribe_mc_ID",subscribe_mc_ID);
    //Return old id for <input type="button" ...>
    btn.setAttribute("id", "mc-embedded-subscribe");
    
	jQuery.ajax({
	type: 'POST',
	url: myajax.url,
	data: {
	action: 'wdv_mailchimp_ajaxcall',
        subscribeemail:email,
        widget_id:subscribe_mc_ID
	},

	success:function(data, textStatus, XMLHttpRequest){
                var classname='#'+full_subscribe_mc_ID+' .'+'result';
                data=data.slice(0, -1);
                $(classname).append(data);
		resetvalues();
                console.log('SUCCESS');
                console.log(data);
	},

	error: function(MLHttpRequest, textStatus, errorThrown){
                var classname='#'+full_subscribe_mc_ID+' .'+'result';
                $(classname).append('<div class="post-error"></div>');
                $('.post-error').html('ERROR');
                console.log('ERROR');
                console.log(textStatus);
		}

	});
        
   }
   ////Check email (if email is not valid)
   else {
    //alert("Invalid e-mail");
    console.log('ELSE');
    var resultfullclass="#"+full_subscribe_mc_ID+" .result";
    console.log(resultfullclass);
    var errorfullclass="#"+full_subscribe_mc_ID+" .email";
    //$(emailfullclass).addClass("error");
    //$(emailfullclass).removeClass("email");
    //$("input#email").css({"background": "red"});
    //$(resultfullclass).css({"color": "red"});
    $(errorfullclass).after( "<span id='email-error' class='error'></span>" );
    resetvalues();
    }
}

function resetvalues()
{
        var x = document.getElementsByClassName("email");
        var i;
        for (i = 0; i < x.length; i++) {
            x[i].value = "";
        }
}


} )( jQuery );