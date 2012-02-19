<?php //netteCache[01]000373a:2:{s:4:"time";s:21:"0.64365300 1329332537";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:51:"C:\xampp\htdocs\app\templates\@layout_kardio1.latte";i:2;i:1329332488;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\templates\@layout_kardio1.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'yhrha7gyg0')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbed9148afb5_head')) { function _lbed9148afb5_head($_l, $_args) { extract($_args)
;
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

	<meta name="description" content="<?php echo htmlSpecialChars($description) ?>" />
        <meta name="keywords" content="<?php echo htmlSpecialChars($keywords) ?>" />
	<meta name="robots" content="index,follow" />
        <meta name="author" content="HTML code: MUDRweb.cz; e-mail: support@mudrweb.cz" />            

	<title><?php echo Nette\Templating\Helpers::escapeHtml($title, ENT_NOQUOTES) ?></title>
	
	<link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo htmlSpecialChars($sharedBasePath) ?>/css/layout_kardio1/layout_kardio1.css" type="text/css" />
<?php if ($pathToHeaderCSS): ?>
            <link rel="stylesheet" href="<?php echo htmlSpecialChars($basePath) ?>/header.css" type="text/css" />
<?php endif ;if ($pathToColourSchemeCSS): ?>
            <link rel="stylesheet" href="<?php echo htmlSpecialChars($basePath) ?>/colour_scheme.css" type="text/css" />
<?php endif ?>
	<script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/jquery.nette.js"></script>
        <script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/jquery.livequery.js"></script>
        <script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/jquery.ajaxform.js"></script>        
        <script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/live-form-validation.js"></script>
        <script type="text/javascript" src="<?php echo htmlSpecialChars($sharedBasePath) ?>/js/jquery.pstrength.js"></script>        
	<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

</head>

<body>        
    <div id="topspace">
    </div>   
    
    <div id="header">
    <h1><?php echo Nette\Templating\Helpers::escapeHtml($header, ENT_NOQUOTES) ?>

    </h1>
    <h2><?php echo Nette\Templating\Helpers::escapeHtml($underHeader, ENT_NOQUOTES) ?>

    </h2>
    </div>
    
    <div id="menu"> 
<?php $_ctrl = $_control->getComponent("navigation"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
    </div>
    <div id="main">
        <div id="content">
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
        </div>        
    </div>
    
    <div id="foot"> 
        
    </div>
</body>
</html>
