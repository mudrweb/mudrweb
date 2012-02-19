<?php //netteCache[01]000393a:2:{s:4:"time";s:21:"0.17005600 1329255971";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:71:"C:\xampp\htdocs\app\AdminModule\templates\AdminDefault\cs\default.latte";i:2;i:1328615391;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\AdminModule\templates\AdminDefault\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '1f9by9zsj5')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb77aabcfaaf_content')) { function _lb77aabcfaaf_content($_l, $_args) { extract($_args)
?>              
    <h2>Registrace nového uživatele</h2>
    
<div id="<?php echo $_control->getSnippetId('formRegUser') ?>"><?php call_user_func(reset($_l->blocks['_formRegUser']), $_l, $template->getParameters()) ?>
</div>         
<?php
}}

//
// block _formRegUser
//
if (!function_exists($_l->blocks['_formRegUser'][] = '_lb7a118b8ad1__formRegUser')) { function _lb7a118b8ad1__formRegUser($_l, $_args) { extract($_args); $_control->validateControl('formRegUser')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["regUserForm"], array()) ?>

        <table cellspacing="0">          
                <tr style="height: 5px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="5">Přihlašovací údaje</td>
                    
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["username"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_username_regUserPanel"><?php echo $_form["username"]->getControl()->addAttributes(array()) ?></td>    
                    <td width="300" align="left" valign="top"><div id="usernameAvailableStatus"></div></td>
                </tr>                 
                <tr>
                    <td class="td_input_label_changePass"><?php if ($_label = $_form["subdomain"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_subdomain_regUserPanel"><?php echo $_form["subdomain"]->getControl()->addAttributes(array()) ?></td>
                    <td width="300" align="left" valign="top"><div id="subdomainAvailableStatus"></div></td>                    
                </tr>
                <tr>
                    <td class="td_input_label_changePass"><?php if ($_label = $_form["email"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel"><?php echo $_form["email"]->getControl()->addAttributes(array()) ?></td>
                </tr>
                <tr>
                    <td class="td_input_label_changePass"><?php if ($_label = $_form["newPassword"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_top_regUserPanel"><?php echo $_form["newPassword"]->getControl()->addAttributes(array()) ?></td>
                </tr>
<!--                <tr>
                    <td class="td_input_label_changePass">{ label newPassword1 /} &nbsp</td>                    
                    <td class="td_input_regUserPanel">{ input newPassword1}</td>
                </tr>     -->
                <tr>                    
                    <td class="td_input_generatePass" colspan="2">*Délka hesla musí být minimálně 8 znaků.                    
                        <br />
                        <a href="<?php echo htmlSpecialChars($_control->link("generatePass!")) ?>" class="ajax">Generovat heslo</a>
                        <div class="generatedPass"><div id="<?php echo $_control->getSnippetId('dispPass') ?>
"><?php call_user_func(reset($_l->blocks['_dispPass']), $_l, $template->getParameters()) ?>
</div></div>
                    </td>
                </tr>    
                <tr style="height: 15px;"><td></td></tr>
         </table>    
         <h2></h2>     
         <table cellspacing="0">          
                <tr style="height: 5px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="8">Osobní údaje</td>
                    
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["name"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["name"]->getControl()->addAttributes(array()) ?></td>                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["surname"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["surname"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["titleBefore"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["titleBefore"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["titleAfter"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["titleAfter"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>                
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["street"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["street"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["city"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["city"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["zip"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_regUserPanel_1"><?php echo $_form["zip"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>                
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["phone"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_phone_regUserPanel">+420 &nbsp;<?php echo $_form["phone"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>                                
                <tr style="height: 15px;"><td></td></tr>
         </table>    
         <h2></h2> 
         <table cellspacing="0">          
                <tr style="height: 5px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="6">Nastavení stránky</td>
                    
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["layouts"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_settings_regUserPanel"><?php echo $_form["layouts"]->getControl()->addAttributes(array()) ?></td>                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["header1"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_settings_regUserPanel"><?php echo $_form["header1"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["header2"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_settings_regUserPanel"><?php echo $_form["header2"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["pageTitle"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_settings_regUserPanel"><?php echo $_form["pageTitle"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["description"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_settings_regUserPanel"><?php echo $_form["description"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["keywords"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo_settings"><?php echo $_form["keywords"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>                                            
                <tr style="height: 15px;"><td></td></tr>
         </table>             
         <h2></h2>         
         <table cellspacing="0">                
                <tr>                            
                    <td class="td_input_changePass"><?php echo $_form["submit"]->getControl()->addAttributes(array()) ?> &nbsp&nbsp </td> 
                </tr>
         </table>
         <h2></h2>       
<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ?>
         
<script type="text/javascript">     
    
    $('#frmregUserForm-username').change(function(event) {
        if ($('#frmregUserForm-username').val().length >= 8) {
            $.getJSON(<?php echo Nette\Templating\Helpers::escapeJs($_control->link("availCheck!")) ?>,{'text':$('#frmregUserForm-username').val(), 'flag':'username'},function(payload) {                        
                if (payload.availCheck[0] == 'notok') {
                    $("#frmregUserForm-username").removeClass("object_ok"); // if necessary
                    $("#frmregUserForm-username").addClass("object_error");                
                    $("#usernameAvailableStatus").html('&nbsp;<span id="username_stats" class="icon-ui-red icon-ui-red-close"></span><span>Jméno je již použito, zvolte prosím jiné</span>');                
                } else {
                    $("#frmregUserForm-username").removeClass('form-control-error'); // if necessary
                    $("#frmregUserForm-username").removeClass('object_error'); // if necessary
                    $("#frmregUserForm-username").addClass("object_ok");                
                    $("#usernameAvailableStatus").html('&nbsp;<span id="username_stats" class="icon-ui-green icon-ui-green-tick"></span><span>Jméno je k dispozici</span>');
                }
            });
        }
    });         
    

    $('#frmregUserForm-subdomain').change(function(event) {
        if ($('#frmregUserForm-subdomain').val().length >= 6) {
            $.getJSON(<?php echo Nette\Templating\Helpers::escapeJs($_control->link("availCheck!")) ?>,{'text':$('#frmregUserForm-subdomain').val(), 'flag':'subdomain'},function(payload) {            
                if (payload.availCheck[0] == 'notok') {
                    $("#frmregUserForm-subdomain").removeClass("object_ok"); // if necessary
                    $("#frmregUserForm-subdomain").addClass("object_error");                
                    $("#subdomainAvailableStatus").html('&nbsp;<span class="icon-ui-red icon-ui-red-close"></span><span>Název stránky je již použit, zvolte prosím jiný</span>');                
                } else {
                    $("#frmregUserForm-subdomain").removeClass('form-control-error'); // if necessary
                    $("#frmregUserForm-subdomain").removeClass('object_error'); // if necessary
                    $("#frmregUserForm-subdomain").addClass("object_ok");                
                    $("#subdomainAvailableStatus").html('&nbsp;<span class="icon-ui-green icon-ui-green-tick"></span><span>Název stránky je k dispozici</span>');
                }
            });
        }
    });         

</script>                         
         
<?php
}}

//
// block _dispPass
//
if (!function_exists($_l->blocks['_dispPass'][] = '_lb1c2d9d99c6__dispPass')) { function _lb1c2d9d99c6__dispPass($_l, $_args) { extract($_args); $_control->validateControl('dispPass')
;echo Nette\Templating\Helpers::escapeHtml($passwordToDisplay, ENT_NOQUOTES) ;
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 