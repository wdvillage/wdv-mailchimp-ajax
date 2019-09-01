/* 
/* Javascript file for widget
 */

var myajax;

( function($) {
    
    $(document).ready(function(){

        $('#mc-embedded-subscribe').click(function(e){ 
           e.preventDefault();
            subscribe_mc(e); 
           });


        var input = document.getElementById("email");

        if (input){
        input.onfocus = function() {

           if (document.getElementById("email-error")) {
           document.getElementById("email-error").remove();
           }
              if (document.getElementById("post-error")) {
           document.getElementById("post-error").remove();
           }
              if (document.getElementById("post-success")) {
           document.getElementById("post-success").remove();
           }
        }; 
        }

       function subscribe_mc(e){  

           var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/);

           var form=e.target;
           var email=document.getElementById('email').value;
           var full_subscribe_mc_ID=$(form).parent().parent()[0].id;
           //Check email (if email is valid)
          if(email.match(pattern))
          {
           arr_subscribe_mc_ID=full_subscribe_mc_ID.split('-');
           subscribe_mc_ID = arr_subscribe_mc_ID.pop();

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
               },

               error: function(MLHttpRequest, textStatus, errorThrown){
                       var classname='#'+full_subscribe_mc_ID+' .'+'result';
                       $(classname).append('<div class="post-error"></div>');
                       $('.post-error').html('ERROR');
                       }

               });

          }
          ////Check email (if email is not valid)
          else {
           var resultfullclass="#"+full_subscribe_mc_ID+" .result";
           var errorfullclass="#"+full_subscribe_mc_ID+" .email";
           $(errorfullclass).after( "<span id='email-error' class='error'>Invalid e-mail</span>" );
           resetvalues();

           $(errorfullclass).nextAll(".error").delay(2000).fadeOut("slow");
                               setTimeout(function () {
                                       $(errorfullclass).next(".error").remove();
                               }, 2000);
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

});
} )( jQuery );