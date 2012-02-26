<?php //netteCache[01]000371a:2:{s:4:"time";s:21:"0.67969100 1330252928";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:49:"C:\xampp\htdocs\app\templates\@layout_admin.latte";i:2;i:1329772509;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\templates\@layout_admin.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'u9tyg2hngs')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbfcb3e1ddd1_head')) { function _lbfcb3e1ddd1_head($_l, $_args) { extract($_args)
;
}}

//
// block _flashes
//
if (!function_exists($_l->blocks['_flashes'][] = '_lb946c119a41__flashes')) { function _lb946c119a41__flashes($_l, $_args) { extract($_args); $_control->validateControl('flashes')
;$iterations = 0; foreach ($flashes as $flash): ?>                <div class="flash <?php echo htmlSpecialChars($flash->type) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ;
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="cs" />

	<meta name="description" content="Admin panel mudrweb.cz" />
        <meta name="robots" content="noindex" />
        
	<title>MUDRweb.cz - Admin panel</title>
	        	
        <link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="../../css/admin.css" type="text/css" />        
        
	<script type="text/javascript" src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.nette.js"></script>
        <script type="text/javascript" src="../../js/jquery.livequery.js"></script>
        <script type="text/javascript" src="../../js/jquery.ajaxform.js"></script>        
        <script type="text/javascript" src="../../js/live-form-validation.js"></script>
        <script type="text/javascript" src="../../js/jquery.pstrength.js"></script>

        <script type="text/javascript">
            $(function() { $('.password').pstrength(); $('.password_regUserPanel').pstrength(); $(".color-picker").miniColors({ letterCase: 'uppercase' }); });                    
        </script>
        
        <script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>        

        <script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>        
        <script type="text/javascript" src="../../js/jquery.dataTables.editable.js"></script>        
        <script type="text/javascript" src="../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../js/jquery.jeditable.masked.js"></script>   

        <script type="text/javascript" src="../../js/jquery.maskedinput-1.3.js"></script>   
        <script type="text/javascript" src="../../js/jquery.jeditable.datepicker.js"></script>   
        <script type="text/javascript" src="../../js/jquery.validate.js"></script>   
        <script type="text/javascript" src="../../js/additional-methods.js"></script>           
        
        <script type="text/javascript" src="../../js/jquery.miniColors.js"></script>                   
	<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

</head>

    <body>	
        <div id="wrapper">
            <div id="header">
                <div class="container">
                    <div class="grid_header_l">
<?php if ($user->isLoggedIn() && $userRole != 'admin'): ?>
                        <a href="<?php echo htmlSpecialChars($_control->link(":Default:default")) ?>
">Návrat na hlavní stránku</a> <span>|</span> <a href="<?php echo htmlSpecialChars($_control->link(":Admin:Default:")) ?>
">Admin panel</a> <?php if ($user->isLoggedIn()): ?> <span>|</span> <a href="<?php echo htmlSpecialChars($preview) ?>
" target="_blank">Náhled stránky</a><?php endif ?>

<?php elseif ($user->isLoggedIn() && $userRole == 'admin'): ?>
                        <a href="<?php echo htmlSpecialChars($_control->link(":Admin:AdminDefault:")) ?>">Admin panel</a>
<?php else: ?>
                        <a href="<?php echo htmlSpecialChars($_control->link(":Default:default")) ?>">Návrat na hlavní stránku</a>
<?php endif ?>
                    </div>                
                    <div class="grid_header_r">
                    <?php if ($user->isLoggedIn()): ?><span class="icon-ui-black icon-ui-black-user"></span>Jste přihlášen jako <?php echo Nette\Templating\Helpers::escapeHtml($userName, ENT_NOQUOTES) ?>
 (<?php echo Nette\Templating\Helpers::escapeHtml($userRole, ENT_NOQUOTES) ?>) <span>|</span> <a href="<?php echo htmlSpecialChars($_presenter->link(":Admin:Login:logout")) ?>
">Odhlásit</a><?php endif ?>

                    </div>
                    <div class="clear">&nbsp;</div>
                </div>
            </div>

<?php if ($user->isLoggedIn()): ?>
            <div id="nav-container"> 
                <div class="container_nav">    
<?php $_ctrl = $_control->getComponent("navigation"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
                </div>    
            </div>
            <div class="clear">&nbsp;</div>
<div id="<?php echo $_control->getSnippetId('flashes') ?>"><?php call_user_func(reset($_l->blocks['_flashes']), $_l, $template->getParameters()) ?>
</div><?php endif ?>

            <div id="main" class="container">
<?php if ($user->isLoggedIn()): ?>
                <div class="grid_content">
                    <div id="content">                
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
                    </div>
                </div>                   
<?php elseif ($presenter_name === 'ForgottenPassword'): ?>
                <div class="grid_content_fpass">                                                                           
                    <div id="content_fpass">                            
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
                    </div>                        
                </div>       
<?php else: ?>
                <div class="grid_content_log">                                                                        
                    <div id="content_log">                            
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
                    </div>                        
                </div>
<?php endif ?>
                <div class="clear">&nbsp;</div>
            </div>	        

            <div id="footer">   
                <div class="container">
                    <div class="grid_footer_l">
                        Optimalizováno pro prohlížeče IE9+, Firefox 8+ a Google Chrome 16+<br />
<?php if ($user->isLoggedIn()): ?>
                        Aktualizováno dne 16.02.2012; verze beta1 
<?php endif ?>
                    </div>
                    <div class="grid_footer_r">            
                    <?php if ($user->isLoggedIn()): ?><span class="icon-ui icon-ui-key"></span><span>Poslední úspěšné přihlášení: <?php echo Nette\Templating\Helpers::escapeHtml($lastSuccessfulLogin, ENT_NOQUOTES) ?>
</span><?php endif ?>

                    </div>
                    <div class="clear">&nbsp;</div>
                </div>
            </div>
        </div>                
    </body>
    
</html>
