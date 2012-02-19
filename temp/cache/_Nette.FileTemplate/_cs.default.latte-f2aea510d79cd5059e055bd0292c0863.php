<?php //netteCache[01]000388a:2:{s:4:"time";s:21:"0.46899300 1329420047";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:66:"C:\xampp\htdocs\app\AdminModule\templates\Default\cs\default.latte";i:2;i:1329419960;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\AdminModule\templates\Default\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'rfjqgyy03h')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lb1a8fa2c5b3_head')) { function _lb1a8fa2c5b3_head($_l, $_args) { extract($_args)
?>    <script type="text/javascript" src="../../js/jquery.admin.js"></script>
<?php
}}

//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbc3279fe221_content')) { function _lbc3279fe221_content($_l, $_args) { extract($_args)
?>
    <h2>Základní nastavení stránky</h2>
    
<div id="<?php echo $_control->getSnippetId('form') ?>"><?php call_user_func(reset($_l->blocks['_form']), $_l, $template->getParameters()) ?>
</div>         
    <script type="text/javascript">     
//        $(document).ready(function()
//        {           
//           $('#frmeditForm-title').qtip({
//                content: {
//                    text: 'V názvu stránky použijte nejdůležitější klíčová slova nebo fráze\n\
//                    a dodržte délku 10 až 64 znaků včetně mezer. Doporučujeme:\n\
//                    - Snažte se minimalizovat použití spojky "a"              \n\
//                    - vyhněte se použití slov, které se na stránce nenacházejí.//',
//                    width: '200'
//                },
//                position: {
//                    my: 'bottom left',
//                    at: 'top left'
//                },
//                show: {
//                    event: 'mouseenter',
//                    ready: false                 
//                },
//                hide: {
//                    event: 'unfocus'
//                },                
//                style: {
//                    classes: 'ui-tooltip-shadow ui-tooltip-jtools'                    
//                }            
//           });            
//        });     
    function openKCFinder(div) {
        window.KCFinder = { callBack: function(url) { window.KCFinder = null; div.innerHTML = '<div style="margin:5px">Nahrávání obrázku...</div>'; var img = new Image(); img.src = url; img.onload = function() {
                    div.innerHTML = '<img id="img" src="' + url + '" />'; var img = document.getElementById('img'); var o_w = img.offsetWidth; var o_h = img.offsetHeight;   var f_w = div.offsetWidth; var f_h = div.offsetHeight; if ((o_w > f_w) || (o_h > f_h)) { if ((f_w / f_h) > (o_w / o_h)) f_w = parseInt((o_w * f_h) / o_h); else if ((f_w / f_h) < (o_w / o_h)) f_h = parseInt((o_h * f_w) / o_w); img.style.width = f_w + "px"; img.style.height = f_h + "px"; } else { f_w = o_w; f_h = o_h; }
                    img.style.marginLeft = parseInt((div.offsetWidth - f_w) / 2) + 'px'; img.style.marginTop = parseInt((div.offsetHeight - f_h) / 2) + 'px'; img.style.visibility = "visible"; var headerImageData = document.getElementById('frmeditForm-headerImageData'); headerImageData.value = url; var headerImage = document.getElementById('frmeditForm-headerImage-0');headerImage.checked = ''; headerImage = document.getElementById('frmeditForm-headerImage-1'); headerImage.checked = 'checked'; } }
        };
        window.open('../kcfinder/browse.php?type=images&dir=user_uploads/<?php echo $subdomain ?>',
            'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'directories=0, resizable=1, scrollbars=0, width=800, height=600'
            );
    }               
      
    var url = $("#frmeditForm-headerImageData").val();   
    if (url != "") { var image = document.getElementById('image'); image.innerHTML = '<div style="margin:5px">Nahrávání obrázku...</div>'; var img = new Image(); img.src = url; img.onload = function() {
       image.innerHTML = '<img id="img" src="' + url + '" />'; var img = document.getElementById('img');                     var o_w = img.offsetWidth; var o_h = img.offsetHeight; var f_w = image.offsetWidth; var f_h = image.offsetHeight;
       if ((o_w > f_w) || (o_h > f_h)) { if ((f_w / f_h) > (o_w / o_h)) f_w = parseInt((o_w * f_h) / o_h); else if ((f_w / f_h) < (o_w / o_h)) f_h = parseInt((o_h * f_w) / o_w); img.style.width = f_w + "px"; img.style.height = f_h + "px"; } else { f_w = o_w; f_h = o_h; }
       img.style.marginLeft = parseInt((image.offsetWidth - f_w) / 2) + 'px'; img.style.marginTop = parseInt((image.offsetHeight - f_h) / 2) + 'px'; img.style.visibility = "visible"; }
       $("#headerImageId").show(1000);          
    }
    var colourScheme = $("#frmeditForm-colourSchemeData").val(); if (colourScheme != "") { $("#colourSchemeId").show(1000); }              
    </script>
         
<?php
}}

//
// block _form
//
if (!function_exists($_l->blocks['_form'][] = '_lb5707fa80bb__form')) { function _lb5707fa80bb__form($_l, $_args) { extract($_args); $_control->validateControl('form')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["editForm"], array()) ?>
	 <table cellspacing="0">            
                <tr style="height: 10px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="2">Nastavení vzhledu</td>
                    
                    <td class="td_input_label_default">Aktivní vzhled:</td>                    
                    <td class="td_status"><?php echo Nette\Templating\Helpers::escapeHtml($layout_desc, ENT_NOQUOTES) ?></td>                    
                </tr>
                <tr>
                    <td class="td_input_label_default"><?php if ($_label = $_form["layouts"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_default_select"><?php echo $_form["layouts"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>    
                <tr style="height: 15px;"><td></td></tr>
         </table>         
         <h2></h2>         
         <table cellspacing="0">                
                <tr style="height: 15px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="6">Nastavení hlavičky</td>
                    
                    <td class="td_input_label_default"><?php if ($_label = $_form["header1"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_default"><?php echo $_form["header1"]->getControl()->addAttributes(array()) ?></td>
                </tr>
                <tr>
                    <td class="td_input_label_default"><?php if ($_label = $_form["header2"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_default"><?php echo $_form["header2"]->getControl()->addAttributes(array()) ?></td>
                </tr>
                <tr>
                    <td class="td_input_label_default"><?php if ($_label = $_form["headerImage"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_default"><?php echo $_form["headerImage"]->getControl()->addAttributes(array()) ?></td>
                </tr>                
                <tr id="headerImageId">                    
                    <td class="td_input_label_default" colspan="3">                    
                    <div id="image" onclick="openKCFinder(this)"><div id="image_blank" style="margin-left: 200px; margin-top: 90px; font-weight: bold;">
                        Vyberte prosím obrázek hlavičky z galerie <br /> 
                        &nbsp;&nbsp;&nbsp;(Optimální rozměry jsou 800x150 px)</div></div></td>
                </tr>
                <tr>
                    <td class="td_input_label_default"><?php if ($_label = $_form["colourScheme"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_default"><?php echo $_form["colourScheme"]->getControl()->addAttributes(array()) ?></td>
                </tr>                   
                <tr id="colourSchemeId">
                    <td></td>
                    <td class="td_input_label_color-picker"><?php if ($_label = $_form["colour1"]->getLabel()) echo $_label->addAttributes(array()) ?>
 &nbsp; <?php echo $_form["colour1"]->getControl()->addAttributes(array()) ?>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;</td>                                        
                </tr>
                <tr style="height: 20px;"><td></td></tr>
         </table>         
         <h2></h2>    
         <table cellspacing="0">                
                <tr style="height: 15px;"><td></td></tr>
                <tr>
                    <td class="td_verticalSeparator" rowspan="6">Nastavení stránky</td>
                    
                    <td class="td_input_label_default"><?php if ($_label = $_form["title"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_default"><?php echo $_form["title"]->getControl()->addAttributes(array()) ?></td>        
                    <td id="title_help_click" class="icon icon-help"></td>
                </tr>
                <tr id="title_help" class="title_help" colspan="2">                    
                    <td class="td_input_label_default"></td>
                    <td class="td_input_default">
                        <div class="title_help_content">
                            V názvu stránky použijte nejdůležitější klíčová slova nebo fráze
                            a dodržte délku 10 až 64 znaků včetně mezer. Doporučujeme:<br />
                            - minimalizovat použití spojky 'a',<br />
                            - vyhnout se použití slov, které se na stránce nevyskytují.
                        </div>
                        <div class="title_help_content_divider"></div>
                    </td>                    
                </tr>
                <tr>
                    <td class="td_input_label_default_desc"><?php if ($_label = $_form["description"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_default"><?php echo $_form["description"]->getControl()->addAttributes(array()) ?></td>                
                    <td id="desc_help_click" class="icon icon-help"></td>
                </tr>
                <tr id="desc_help" class="desc_help" colspan="2">                    
                    <td class="td_input_label_default"></td>
                    <td class="td_input_default">
                        <div class="desc_help_content">
                            Popis stránky představuje stručný opis, který by mněl nejenom 
                            pro účely vyhledávačů, ale především lidských návštevníků jednoznačně
                            popisovat účel stránky.<br />                            
                            Jeho délka musí být delší než 50 znaků a nesmí být delší než
                            149 znaků včetně mezer. 
                            Ujistěte se, že popis je relevantný k obsahu stránky, tzn. 
                            neobsahuje zbytečně slová nevyskytující se v obsahu
                            stránky.
                        </div>
                        <div class="desc_help_content_divider"></div>
                    </td>                    
                </tr>                
                <tr>
                    <td class="td_input_label_default"><?php if ($_label = $_form["keywords"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_default"><?php echo $_form["keywords"]->getControl()->addAttributes(array()) ?></td>                
                    <td id="keywords_help_click" class="icon icon-help"></td>
                </tr>                
                <tr id="keywords_help" class="keywords_help" colspan="2">                    
                    <td class="td_input_label_default"></td>
                    <td class="td_input_default">
                        <div class="keywords_help_content">
                            Klíčová slova představují seznam slov a fráz týkajících se
                            obsahu Vaší stránky, sloužící jako další zdroj textu pro vyhledávače.
                            Doporučený rozsah je 4 až 8 slov nebo fráz, které  
                            oddělíte použitím čárky ','.
                        </div>
                        <div class="keywords_help_content_divider"></div>
                    </td>                    
                </tr>                
                <tr style="height: 15px;"><td></td></tr>
         </table>
         <h2></h2>
         <table cellspacing="0">                
                <tr>                            
                    <td class="td_input_default_submit"><?php echo $_form["submit"]->getControl()->addAttributes(array()) ?> &nbsp&nbsp </td> 
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
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars()) ; call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 