<?php //netteCache[01]000381a:2:{s:4:"time";s:21:"0.39443300 1330251789";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:59:"C:\xampp\htdocs\app\templates\Registration\cs\default.latte";i:2;i:1330251788;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\templates\Registration\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ip2mfj4715')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb8d4b7d53bd_content')) { function _lb8d4b7d53bd_content($_l, $_args) { extract($_args)
?>              
    <table cellspacing="0">
        <tr>            
            <td width="140" style="font-weight:bold;font-size:16px;padding-left:110px;">                
                1. Volba programu
            </td>
            <td>
                <img src="../images/main/man_fillin.png" width="50" />                            
            </td>
            <td width="120" style="font-weight:bold;font-size:16px;padding-left:45px;color:#666666;">                
                2. Osobní údaje                                
            </td>
            <td>
                <img src="../images/main/man_magnifier_nocolor.png" width="50" />                            
            </td>            
            <td width="100" style="font-weight:bold;font-size:16px;padding-left:45px;color:#666666;">                
                3. Potvrzení
            </td>
            <td>
                <img src="../images/main/man_send_nocolor.png" width="70" />                            
            </td>                        
        </tr>
        <tr height="15"></tr>
    </table>
    <h2></h2> 
    
<div id="<?php echo $_control->getSnippetId('formRegUser1') ?>"><?php call_user_func(reset($_l->blocks['_formRegUser1']), $_l, $template->getParameters()) ?>
</div>    
<?php
}}

//
// block _formRegUser1
//
if (!function_exists($_l->blocks['_formRegUser1'][] = '_lb7e671055d0__formRegUser1')) { function _lb7e671055d0__formRegUser1($_l, $_args) { extract($_args); $_control->validateControl('formRegUser1')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["regUserForm1"], array()) ?>

        <table cellspacing="0">          
                <tr style="height: 5px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="4">Volba programu</td>                                        
                </tr>    
                <tr>
                    <td width="20"></td>
                    <td style="background-color:#ffffff;padding-left:20px;width:685px;">
                        <div style="display:inline-table;height:60px;vertical-align:top;padding-top:45px;">
                            <?php echo Nette\Templating\Helpers::escapeHtml($form['program']->getControl('demo'), ENT_NOQUOTES) ?>

                        </div>
                        <div style="display:inline-table;padding-left:105px;">
                            <img src="../images/main/man_gift.png" width="90" />
                        </div>
                    </td>
                </tr>
                <tr style="height:5px;"><td></td></tr>
                <tr>
                    <td width="20"></td>
                    <td style="background-color:#ffffff;padding-left:20px;width:685px;">
                        <div style="display:inline-table;height:60px;vertical-align:top;padding-top:45px;">
                            <?php echo Nette\Templating\Helpers::escapeHtml($form['program']->getControl('basic'), ENT_NOQUOTES) ?>

                        </div>
                        <div style="display:inline-table;padding-left:160px;">
                            <img src="../images/main/man_basic.png" width="90" />
                        </div>
                    </td>
                </tr>   
                <tr style="height: 15px;"><td></td></tr>
         </table>   
         <h2></h2> 
	 <table cellspacing="0">            
                <tr style="height: 10px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="3">Smluvní podmínky</td>
                </tr>
                <tr>                    
                    <td colspan="3" style="padding-left:40px;">
                        Smluvní podmínky MUDRweb.cz naleznete na odkaze <a href="<?php echo htmlSpecialChars($_presenter->link(":Default:")) ?>" target=_blank">Smluvní podmínky.</a>
                    </td>                    
                </tr>
                <tr>
                    <td width="20"></td>
                    <td class="td_input_terms"><?php echo $_form["terms"]->getControl()->addAttributes(array()) ?> &nbsp; Pročetl jsem si smluvní podmínky, porozuměl jsem jim a akceptuji je! </td>
                </tr>      
                <tr style="height: 15px;"><td></td></tr>
         </table>           
         <h2></h2>         
         <table cellspacing="0">                
                <tr>                            
                    <td class="td_input_changePass"><?php echo $_form["submit1"]->getControl()->addAttributes(array()) ?> &nbsp;&nbsp; </td> 
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