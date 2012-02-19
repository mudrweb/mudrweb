<?php //netteCache[01]000386a:2:{s:4:"time";s:21:"0.63488200 1329253037";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:64:"C:\xampp\htdocs\app\AdminModule\templates\Login\cs\default.latte";i:2;i:1328617434;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\AdminModule\templates\Login\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ne562q2egw')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbc5f9ac251c_content')) { function _lbc5f9ac251c_content($_l, $_args) { extract($_args)
?>
<div class="grid_content_log_r">

    <div id="log_flashes_holder">
<div id="<?php echo $_control->getSnippetId('flashes_main') ?>"><?php call_user_func(reset($_l->blocks['_flashes_main']), $_l, $template->getParameters()) ?>
</div>    </div>        
    
    <h1>Přihlášení</h1>

    <br />
    
<?php Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["signInForm"], array()) ?>

<?php if ($form->hasErrors()): ?>        <div class="errors_log">
<?php $iterations = 0; foreach ($form->errors as $error): ?>            <div colspan="2"><?php echo Nette\Templating\Helpers::escapeHtml($error, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>
        </div>
<?php endif ?>

        <table cellspacing="0">            
                <tr>
                    <td class="td_input_label_log"><?php if ($_label = $_form["username"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_log"><?php echo $_form["username"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>                
                <tr>
                    <td class="td_input_label_log"><?php if ($_label = $_form["password"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_log"><?php echo $_form["password"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>                
                <tr>
                    <td></td>
                    <td class="td_input_log_remember"><?php echo $_form["remember"]->getControl()->addAttributes(array()) ?>
 <?php if ($_label = $_form["remember"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                </tr>      
                <tr>       
                    <td></td>
                    <td class="td_input_log"><?php echo $_form["send"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>                
        </table>

<?php call_user_func(reset($_l->blocks['forgot']), $_l, get_defined_vars())  ?>

<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ?>

</div>

<div class="grid_content_log_l">
    <div id="log_logo_holder">
        <a href="<?php echo htmlSpecialChars($_control->link(":Default:", array('lang'=>'cs'))) ?>"><img src="../../images/admin/logo.png" class="noborder" alt="Návrat na hlavní stránku mudrweb.cz" /></a>
    </div>
    <div id="log_notifier_holder">
        Pokud už jste naším klientem, do svého účtu se <strong>přihlásíte pomocí uživatelského jména a hesla</strong>.
    </div>
    <div id="log_text_wrapper">
        <div class="grid_header_log_l_text">
            <img src="../../images/admin/security.png" width="89" height="140" alt="Bezpečnost vašich dat na mudrweb.cz" />
        </div>        
        <div class="grid_header_log_r_text">
            <strong>Pamatujte:</strong> 
            Nikdy nebudeme žádat zaslání Vašich přihlašovacích údajů e-mailem nebou jakoukoliv 
            jinou formou.
            <br /><br />
            Odkaz pro přihlášení do Vašeho účtu nikdy neposíláme e-mailem. 
            Pokud jste se sem dostali jinak než z našich webových stránek, ujistěte se prosím, 
            že jste u nás správně.
            <br /><br />
            V kolonce s internetovou adresou byste vždy měli vidět <strong>mudrweb.cz/admin/login/</strong>.                                    
        </div>   
        <div class="clear">&nbsp;</div>
    </div>   
        
    <div id="log_separator">               
    </div>    
    
    <div id="log_register_holder">        
        <a href="http://mudrweb.cz/">
            <input id="register" class="button" type="submit" value="Chci si založit účet&nbsp;" name="register" /></input>
        </a>
    </div>    
</div>
<div class="clear">&nbsp;</div>

<?php
}}

//
// block _flashes_main
//
if (!function_exists($_l->blocks['_flashes_main'][] = '_lbadf5848d71__flashes_main')) { function _lbadf5848d71__flashes_main($_l, $_args) { extract($_args); $_control->validateControl('flashes_main')
;$iterations = 0; foreach ($flashes as $flash): ?>            <div class="flash <?php echo htmlSpecialChars($flash->type) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ;
}}

//
// block forgot
//
if (!function_exists($_l->blocks['forgot'][] = '_lb5a88d419a6_forgot')) { function _lb5a88d419a6_forgot($_l, $_args) { extract($_args)
?>        <div class="forgotten_password">    
            <a href="<?php echo htmlSpecialChars($_presenter->link(":Admin:ForgottenPassword:")) ?>" class="f_password">Zapomněl jsem jméno a heslo</a>
        </div>
<?php
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
$robots = 'noindex' ?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 