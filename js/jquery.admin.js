// default (site settings)
$("#frmeditForm-headerImage-0").live("click",function () { $("#headerImageId").animate({ opacity: "hide"}, 800); }); 
$("#frmeditForm-headerImage-1").live("click",function () { $("#headerImageId").animate({ opacity: "show"}, 800); });      
$("#frmeditForm-colourScheme-0").live("click",function () { $("#colourSchemeId").animate({ opacity: "hide"}, 800); $("#colourSchemeId1").animate({ opacity: "hide"}, 800); });       
$("#frmeditForm-colourScheme-1").live("click",function () { $("#colourSchemeId").animate({ opacity: "show"}, 800); $("#colourSchemeId1").animate({ opacity: "show"}, 800); });  

var clicked = 0;var clicked_desc = 0;var clicked_keys = 0;$("#title_help_click").live("click", function () {                        
clicked++;$("#desc_help").hide();$("#desc_help1").hide();$("#keywords_help").hide();$("#keywords_help1").hide();if (clicked == 1) {clicked_desc = 0;clicked_keys = 0;
$("#title_help").animate({ opacity: "show"}, 800); $("#title_help1").animate({ opacity: "show"}, 800); } else {$("#title_help").animate({ opacity: "hide"}, 800);$("#title_help1").animate({ opacity: "hide"}, 800);clicked = 0;}});                              
$("#desc_help_click").live("click", function () {clicked_desc++;$("#title_help").hide();$("#title_help1").hide();$("#keywords_help").hide();$("#keywords_help1").hide();if (clicked_desc == 1) {                                          
clicked = 0;clicked_keys = 0;$("#desc_help").animate({ opacity: "show"}, 800); $("#desc_help1").animate({ opacity: "show"}, 800); } else {$("#desc_help").animate({ opacity: "hide"}, 800); $("#desc_help1").animate({ opacity: "hide"}, 800);clicked_desc = 0;}});       
$("#keywords_help_click").live("click", function () {clicked_keys++;$("#title_help").hide();$("#title_help1").hide();$("#desc_help").hide();$("#desc_help1").hide();if (clicked_keys == 1) {                           
clicked = 0;clicked_desc = 0;$("#keywords_help").animate({ opacity: "show"}, 800); $("#keywords_help1").animate({ opacity: "show"}, 800); } else {$("#keywords_help").animate({ opacity: "hide"}, 800); $("#keywords_help1").animate({ opacity: "hide"}, 800);clicked_keys = 0;}});   

// guest book
$('span[id|="questAction"]').live("click", function () {var questionId = $(this).attr("id");var hyphen = questionId.indexOf('-');var id = questionId.substr(hyphen + 1, questionId.length);
$('#respondForm').hide().appendTo('#quest-' + id).show("drop", {direction: "left"}, 600);$('#frmrespondForm-questionId').val(id);$('#frmrespondForm-answer').val('');});   