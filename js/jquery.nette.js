/**
 * AJAX Nette Framwork plugin for jQuery
 *
 * @copyright   Copyright (c) 2009 Jan Marek
 * @license     MIT
 * @link        http://nettephp.com/cs/extras/jquery-ajax
 * @version     0.2
 */

jQuery.extend({
    nette: {
        updateSnippet: function (id, html) {
            $("#" + id).html(html);
        },

        success: function (payload) {            
            // redirect
            if (payload.redirect) {                
                window.location.href = payload.redirect;
                return;
            }

            // snippets
            if (payload.snippets) {                                
                for (var i in payload.snippets) {                        
                    jQuery.nette.updateSnippet(i, payload.snippets[i]);
                    jQuery("#" + i + ' form').each(function(i, val) {
                        Nette.initForm(val);
                    });
                }                
            } 
                        
            if (payload.snippets && payload.snippets["snippet--dispPass"]) {
                if (typeof payload.snippets["snippet--dispPass"] == "string") {                                                         
                } else {
                    $('.password_regUserPanel').pstrength(); 
                }                        
            }
        }
    }
});

jQuery.ajaxSetup({
	success: jQuery.nette.success,
	dataType: "json"
});

$(function () {
	$('<div id="ajax-spinner"></div>').hide().ajaxStart(function () {
		$(this).show();
	}).ajaxStop(function () {
		$(this).hide();
	}).appendTo("body");
        
        $("a.ajax, .paginator a").live("click", function (event) {
                event.preventDefault();
                $.get(this.href);
        });

        $("form.ajax").live("submit", function () {  
            var error_counter = 0;
            $(":text, textarea").each(function() {
                if($(this).val() === "")
                    error_counter++;
            });

            if (error_counter == 0){
                $(this).ajaxSubmit();	    
                return false;
            }
        });

        $("form.ajax :submit").live("click", function () {
            var error_counter = 0;
            $(":text, textarea").each(function() {
                if($(this).val() === "")
                    error_counter++;
            });           

            if (error_counter == 0){
                $('#stats').show("slow").delay(2000);
                $('#stats').hide("slow");
                $(this).ajaxSubmit();
                return false;	
            }      	      		
        });             

//        $("form.ajax").live("submit", function () {
//                $(this).ajaxSubmit();
//                return false;
//        });
//        
//        $("form.ajax :submit").live("click", function () {
//                $(this).ajaxSubmit();
//                return false;
//        });
    
//        $("form.ajax :submit").live("click",function (e) {
//                e.preventDefault();
//                var form = $(this).parents("form");
//                form.triggerHandler("submit");
//                if(typeof form.get(0).onsubmit == "function"){
//                    if(form.get(0).onsubmit()===false){
//                        return false;
//                    }
//                }
//                form.ajaxSubmit();
//                return false;
//        });        
});  