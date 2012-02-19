<?php //netteCache[01]000386a:2:{s:4:"time";s:21:"0.47825800 1329420150";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:64:"C:\xampp\htdocs\app\AdminModule\templates\Item2\cs\default.latte";i:2;i:1329420096;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\AdminModule\templates\Item2\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'sr9ttcdbp8')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb01b6004a44_content')) { function _lb01b6004a44_content($_l, $_args) { extract($_args)
?>
    <h2>Editace položky v menu</h2>
    <br />
    
<div id="<?php echo $_control->getSnippetId('form') ?>"><?php call_user_func(reset($_l->blocks['_form']), $_l, $template->getParameters()) ?>
</div>
    <script type="text/javascript">        
        jQuery(document).ready(function() { CKEDITOR.replace('frmeditForm-text', { contentsCss : '<?php echo $sharedBasePath ?>
/css/<?php echo $pathToEditorCSS ?>', extraPlugins : 'autogrow' }); });       
    </script>
    
<?php
}}

//
// block _form
//
if (!function_exists($_l->blocks['_form'][] = '_lb4ccdff16ab__form')) { function _lb4ccdff16ab__form($_l, $_args) { extract($_args); $_control->validateControl('form')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["editForm"], array()) ?>
	 <table cellspacing="0">
                <tr>
                    <td class="td_input_label">Status:</td>
<?php if ($status): ?>
                        <td class="td_menuItemPublished">Tato položka je poublikována.</td>                    
<?php else: ?>
                        <td class="td_menuItemNotPublished">Tato položka není poublikována.</td>                    
<?php endif ?>
                </tr>                
                <tr>
                    <td class="td_input_label"><?php if ($_label = $_form["itemName"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input"><?php echo $_form["itemName"]->getControl()->addAttributes(array()) ?> &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td class="td_input_label"><?php if ($_label = $_form["text"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input"><?php echo $_form["text"]->getControl()->addAttributes(array()) ?></td>
                </tr>
                <tr>
                    <td></td>                    
                    <td class="td_input"><?php echo $_form["submit"]->getControl()->addAttributes(array()) ?>
 &nbsp&nbsp <?php echo $_form["publish"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>
         </table>
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