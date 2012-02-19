<?php //netteCache[01]000365a:2:{s:4:"time";s:21:"0.88324600 1329420315";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:43:"C:\xampp\htdocs\app\templates\@layout.latte";i:2;i:1329420201;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '94u8dq63z8')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lb212e1b35b2_head')) { function _lb212e1b35b2_head($_l, $_args) { extract($_args)
;
}}

//
// block _flashes
//
if (!function_exists($_l->blocks['_flashes'][] = '_lbcde7b3c1d8__flashes')) { function _lbcde7b3c1d8__flashes($_l, $_args) { extract($_args); $_control->validateControl('flashes')
;$iterations = 0; foreach ($flashes as $flash): ?>        <div class="flash <?php echo htmlSpecialChars($flash->type) ?>
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

	<meta name="description" content="" />
        <meta name="keywords" content="" />            
	<meta name="robots" content="index,follow" />        
        <meta name="author" content="HTML code: MUDRweb.cz; e-mail: support@mudrweb.cz" />        

	<title>MUDRweb.cz - Místo pro Váš web</title>

	<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="../css/main.css" type="text/css" />
<!--        <link rel="stylesheet" href="../css/jquery-ui-1.8.16.custom.css" type="text/css">        -->
        
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="../js/jquery.nette.js"></script>
        <script type="text/javascript" src="../js/jquery.livequery.js"></script>
        <script type="text/javascript" src="../js/jquery.ajaxform.js"></script>        
        <script type="text/javascript" src="../js/live-form-validation.js"></script>
        <script type="text/javascript" src="../js/jquery.pstrength.js"></script>    
      
 	<script type="text/javascript" src="../js/slides.min.jquery.js"></script>
	<script type="text/javascript">
                 $(document).ready(function() { $('#slides').slides({ preload: true, preloadImage: '../images/ajax-loader.gif', play: 6000, pause: 1, hoverPause: true });
                 $('.password').pstrength(); $('.password_regUserPanel').pstrength(); });
	</script>                   
	<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

</head>

<body>

<div id="wrapper">


    <div id="header">
        <div class="container">
            <div class="grid_header_l">                                         
                <a href="<?php echo htmlSpecialChars($_control->link(":Admin:Default:")) ?>" class="login" title="Přihlášení"><span></span>Přihlášení do Admin panelu</a> &nbsp;&nbsp;                                                 
            </div>                
                <a href="<?php echo htmlSpecialChars($_control->link(":Registration:")) ?>" class="registration" title="Registrace"><span></span>Registrace</a>            
            <div class="grid_header_r">
                <a href="<?php echo htmlSpecialChars($_control->link(":Default:", array('lang'=>'cs'))) ?>" class="lang" title="Český jazyk"><span></span></a>                 
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>           

<div id="<?php echo $_control->getSnippetId('flashes') ?>"><?php call_user_func(reset($_l->blocks['_flashes']), $_l, $template->getParameters()) ?>
</div>    <div id="main" class="container">  
        <div class="grid_main_content">
            <div id="content">                


                <div class="grid_main_header_l">
                    <a href="<?php echo htmlSpecialChars($_control->link(":Default:")) ?>"><img src="../images/logo.png" class="noborder" alt="MUDRweb.cz - Místo pro Váš web" /></a>
                </div>  <!--grid_header_l-->              
                <div class="grid_main_header_r">
                    <div class="mainmenu">  
                        <div class="mainmenu_firstbtn"></div>
                        <!--menu items-->
                        <div class="mainmenu_btn">Ukázky WEBů</div>
                        <div class="mainmenu_btn">Cenník služeb</div>
                        <div class="mainmenu_lastbtn">Kontakt</div>
                    </div> <!--end of mainmenu-->   
                </div> <!--end of grid_header_r--> 
                <div class="clear">&nbsp;</div>                                    
                
                <div class="space"><hr /></div>                                
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
            </div>
        </div>                   
        <div class="clear">&nbsp;</div>
    </div>


    <div id="footer">
        <div class="container">
            <div class="grid_footer_l">
                © Copyright 2012
            </div>
            <div class="grid_footer_r">
                Domů | Cenník | Kontakty
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>            

        
</div> 
    
</body>
</html>
