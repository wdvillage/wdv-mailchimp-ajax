/* global wdvmailchimpmyajax */
(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

      	$( document ).ready( function() {
//************** Ajax ******************* 
            
        $('.mc-embedded-subscribe').click(function(e){ 
           e.preventDefault();
            subscribe_mc(e); 
           });


        var input = document.getElementById("wdv-email");

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
           var full_subscribe_mc_ID=$(form).parent().parent().parent()[0].id;
            var full='#'+full_subscribe_mc_ID;
            
var findemail=$(full).find("#wdv-email");
var email=findemail[0].value;

           //Check email (if email is valid)
          if(email.match(pattern)) { 
           var subscribe_mc_ID = full_subscribe_mc_ID.split('-').pop();
            
               jQuery.ajax({
               type: 'POST',
               url: wdvmailchimpmyajax.url,
               data: {
               action: 'wdv_mailchimp_ajaxcall',
               subscribeemail:email,
               widget_id:subscribe_mc_ID
               },

               success:function(data, textStatus, XMLHttpRequest){
                       var classname='#'+full_subscribe_mc_ID+' .'+'wdv-result';
                       data=data.slice(0, -1);
                       $(classname).append(data);
                        
                        
           $(classname).delay(10000).fadeOut("slow");
                               setTimeout(function () {
                                      $(classname).remove();
                               }, 10000);                        
                        
                        
                       resetvalues();
               },

               error: function(MLHttpRequest, textStatus, errorThrown){
                       var classname='#'+full_subscribe_mc_ID+' .'+'wdv-result';
                       $(classname).append('<div class="post-error"></div>');
                       $('.post-error').html('ERROR');
                       }

               });

          }
          ////Check email (if email is not valid)
          else {
           var resultfullclass="#"+full_subscribe_mc_ID+" .wdv-result";
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
            
            
            
        } );     
})( jQuery );

