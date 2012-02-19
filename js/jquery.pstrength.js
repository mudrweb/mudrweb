/* Function is derived from Ajax Password Strength Meter Script under MIT-licensed code: http://www.opensource.org/licenses/mit-license.php */
(function(A){A.extend(A.fn,{pstrength:function(B)
        {var B=A.extend({verdects:["Příliš slabé","Slabé","Středně silné","Silné","Velmi silné"],
        colors:["#f00","#c06","#f60","#3c0","#3f0"],scores:[10,15,30,40],
        common:["password","sex","god","123456","123","liverpool","letmein","qwerty","monkey"],
        minchar:8},B);return this.each(function(){var C=A(this).attr("id");
        
//        A(this).after("<div class=\"pstrength-minchar\" id=\""+C+"_minchar\">\n\
//        Délka nového hesla musí být minimálně //"+B.minchar+" znaků</div>");
//        A(this).after("<div class=\"pstrength-info\" id=\""+C+"_text\"></div>");
    
//        // change password - user profile
//        $('.td_input_top').after("<div class=\"pstrength-info\" id=\"frmeditPassForm-newPassword_text\"></div>");    
//        // user registration - admin panel
//        $('.td_input_top_regUserPanel').after("<div class=\"pstrength-info\" id=\"frmregUserForm-newPassword_text\"></div>");        

        // change password - user profile
        A(this).after("<div class=\"pstrength-info\" id=\"frmeditPassForm-newPassword_text\"></div>");    
        // user registration - admin panel
        A(this).after("<div class=\"pstrength-info\" id=\"frmregUserForm-newPassword_text\"></div>");        

        A(this).after("<div class=\"pstrength-bar\" id=\""+C+"_bar\" \n\
        style=\"border: 1px solid #E4F4F5; font-size: 1px; height: 5px; width: 0px;\"></div>");
        
        A(this).keyup(function(){A.fn.runPassword(A(this).val(),C,B)})})},runPassword:function(D,F,C)
        {nPerc=A.fn.checkPassword(D,C);var B="#"+F+"_bar";var E="#"+F+"_text";if(nPerc==-200)
        {strColor="#f00";strText="Nebezpečné heslo!";A(B).css({width:"0%"})}else
        {if(nPerc<0&&nPerc>-199){strColor="#ccc";strText="Příliš krátké";A(B).css({width:"5%"})}else
        {if(nPerc<=C.scores[0]){strColor=C.colors[0];strText=C.verdects[0];A(B).css({width:"10%"})}
        else{if(nPerc>C.scores[0]&&nPerc<=C.scores[1]){strColor=C.colors[1];strText=C.verdects[1];A(B).css({width:"25%"})}
        else{if(nPerc>C.scores[1]&&nPerc<=C.scores[2]){strColor=C.colors[2];strText=C.verdects[2];A(B).css({width:"50%"})}
        else{if(nPerc>C.scores[2]&&nPerc<=C.scores[3]){strColor=C.colors[3];strText=C.verdects[3];A(B).css({width:"75%"})}
        else{strColor=C.colors[4];strText=C.verdects[4];A(B).css({width:"99%"})}}}}}}A(B).css({backgroundColor:strColor});
        A(E).html("<span style='color: "+strColor+";'>"+strText+"</span>")},checkPassword:function(C,B)
        {var F=0;var E=B.verdects[0];if(C.length<B.minchar){F=(F-100)}
        else{if(C.length>=B.minchar&&C.length<=(B.minchar+2)){F=(F+6)}
        else{if(C.length>=(B.minchar+3)&&C.length<=(B.minchar+4)){F=(F+12)}
        else{if(C.length>=(B.minchar+5)){F=(F+18)}}}}if(C.match(/[a-z]/)){F=(F+1)}if(C.match(/[A-Z]/)){F=(F+5)}if(C.match(/\d+/)){F=(F+5)}if(C.match(/(.*[0-9].*[0-9].*[0-9])/)){F=(F+7)}if(C.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)){F=(F+5)}if(C.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)){F=(F+7)}if(C.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)){F=(F+2)}if(C.match(/([a-zA-Z])/)&&C.match(/([0-9])/)){F=(F+3)}if(C.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/)){F=(F+3)}for(var D=0;D<B.common.length;D++){if(C.toLowerCase()==B.common[D]){F=-200}}return F}})})(jQuery)