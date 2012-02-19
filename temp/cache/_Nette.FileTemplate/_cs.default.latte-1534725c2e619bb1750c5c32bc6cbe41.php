<?php //netteCache[01]000388a:2:{s:4:"time";s:21:"0.74292900 1329255898";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:66:"C:\xampp\htdocs\app\AdminModule\templates\Profile\cs\default.latte";i:2;i:1328615026;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\AdminModule\templates\Profile\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'pp1newt71e')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb2a79f19dcf_content')) { function _lb2a79f19dcf_content($_l, $_args) { extract($_args)
?>              
    <h2>Nastavení uživatelského profilu</h2>
    
<div id="<?php echo $_control->getSnippetId('formPass') ?>"><?php call_user_func(reset($_l->blocks['_formPass']), $_l, $template->getParameters()) ?>
</div>
<div id="<?php echo $_control->getSnippetId('formPInfo') ?>"><?php call_user_func(reset($_l->blocks['_formPInfo']), $_l, $template->getParameters()) ?>
</div>         
<?php
}}

//
// block _formPass
//
if (!function_exists($_l->blocks['_formPass'][] = '_lb2291ee1934__formPass')) { function _lb2291ee1934__formPass($_l, $_args) { extract($_args); $_control->validateControl('formPass')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["editPassForm"], array()) ?>
	 <table cellspacing="0">          
                <tr style="height: 5px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="4">Změna hesla</td>
                    
                    <td class="td_input_label_changePass"><?php if ($_label = $_form["oldPassword"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_actualPass"><?php echo $_form["oldPassword"]->getControl()->addAttributes(array()) ?></td>                                      
                </tr>
                <tr>
                    <td class="td_input_label_changePass"><?php if ($_label = $_form["newPassword"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_top"><?php echo $_form["newPassword"]->getControl()->addAttributes(array()) ?></td>
                </tr>
                <tr>
                    <td class="td_input_label_changePass"><?php if ($_label = $_form["newPassword1"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_actualPass"><?php echo $_form["newPassword1"]->getControl()->addAttributes(array()) ?></td>
                </tr>     
                <tr>                    
                    <td class="td_input_changePassDesc" colspan="2">*Délka nového hesla musí být minimálně 8 znaků.</td>
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
<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ;
}}

//
// block _formPInfo
//
if (!function_exists($_l->blocks['_formPInfo'][] = '_lbf848e459cc__formPInfo')) { function _lbf848e459cc__formPInfo($_l, $_args) { extract($_args); $_control->validateControl('formPInfo')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["editPInfoForm"], array()) ?>
	 <table cellspacing="0">          
                <tr style="height: 5px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="9">Změna osobních údajů</td>
                    
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["name"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["name"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["surname"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["surname"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["titleBefore"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["titleBefore"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["titleAfter"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["titleAfter"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["street"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["street"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["city"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["city"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["zip"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["zip"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>                
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["phone"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                                        
                    <td class="td_input_pinfo_phone">+420 &nbsp<?php echo $_form["phone"]->getControl()->addAttributes(array()) ?></td>                                                      
                </tr>        
                <tr>
                    <td class="td_input_label_pinfo"><?php if ($_label = $_form["email"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_pinfo"><?php echo $_form["email"]->getControl()->addAttributes(array()) ?></td>                                                    
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
<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ;
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