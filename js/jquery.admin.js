// default (site settings)
$("#frmeditForm-headerImage-0").live("click",function () {$("#headerImageId").hide(1000);}); 
$("#frmeditForm-headerImage-1").live("click",function () {$("#headerImageId").fadeIn(1000);});      
$("#frmeditForm-colourScheme-0").live("click",function () {$("#colourSchemeId").hide(1000);});       
$("#frmeditForm-colourScheme-1").live("click",function () {$("#colourSchemeId").fadeIn(1000);});  

var clicked = 0;var clicked_desc = 0;var clicked_keys = 0;$("#title_help_click").live("click", function () {                        
clicked++;$("#desc_help").hide();$("#keywords_help").hide();if (clicked == 1) {clicked_desc = 0;clicked_keys = 0;
$("#title_help").fadeIn(1000);} else {$("#title_help").fadeOut(1000);clicked = 0;}});                              
$("#desc_help_click").live("click", function () {clicked_desc++;$("#title_help").hide();$("#keywords_help").hide();if (clicked_desc == 1) {                                          
clicked = 0;clicked_keys = 0;$("#desc_help").fadeIn(1000);} else {$("#desc_help").fadeOut(1000);clicked_desc = 0;}});       
$("#keywords_help_click").live("click", function () {clicked_keys++;$("#title_help").hide();$("#desc_help").hide(1000);if (clicked_keys == 1) {                           
clicked = 0;clicked_desc = 0;$("#keywords_help").fadeIn(1000);} else {$("#keywords_help").fadeOut(1000);clicked_keys = 0;}});   

// guest book
$('span[id|="questAction"]').live("click", function () {var questionId = $(this).attr("id");var hyphen = questionId.indexOf('-');var id = questionId.substr(hyphen + 1, questionId.length);
$('#respondForm').hide().appendTo('#quest-' + id).show("drop", {direction: "left"}, 600);$('#frmrespondForm-questionId').val(id);$('#frmrespondForm-answer').val('');});   