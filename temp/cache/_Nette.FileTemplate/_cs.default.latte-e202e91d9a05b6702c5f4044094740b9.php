<?php //netteCache[01]000390a:2:{s:4:"time";s:21:"0.38796100 1329420030";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:68:"C:\xampp\htdocs\app\AdminModule\templates\GuestBook\cs\default.latte";i:2;i:1329419975;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\AdminModule\templates\GuestBook\cs\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '4eoo07x2qo')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbecbc53df87_head')) { function _lbecbc53df87_head($_l, $_args) { extract($_args)
?>    <script type="text/javascript" src="../../js/jquery.admin.js"></script>
<?php
}}

//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbdf6f3cd986_content')) { function _lbdf6f3cd986_content($_l, $_args) { extract($_args)
?>              
    <h2>Správa návštěvní knihy</h2>
        
<div id="<?php echo $_control->getSnippetId('form') ?>"><?php call_user_func(reset($_l->blocks['_form']), $_l, $template->getParameters()) ?>
</div>        
<div id="<?php echo $_control->getSnippetId('formResponse') ?>"><?php call_user_func(reset($_l->blocks['_formResponse']), $_l, $template->getParameters()) ?>
</div>               
<div id="<?php echo $_control->getSnippetId('list') ?>"><?php call_user_func(reset($_l->blocks['_list']), $_l, $template->getParameters()) ?>
</div><?php
}}

//
// block _form
//
if (!function_exists($_l->blocks['_form'][] = '_lbfa2affca8c__form')) { function _lbfa2affca8c__form($_l, $_args) { extract($_args); $_control->validateControl('form')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["editForm"], array()) ?>
         <table cellspacing="0">                
                <tr style="height: 15px;"><td></td></tr>
                <tr>
                    <td class="td_input_label">Status:</td>
<?php if ($status): ?>
                        <td class="td_menuItemPublished">Tato položka je poublikována.</td>                    
<?php else: ?>
                        <td class="td_menuItemNotPublished">Tato položka není poublikována.</td>                    
<?php endif ?>
                </tr>  
                <tr>                                        
                    <td class="td_input_label_guestb"><?php if ($_label = $_form["adminName"]->getLabel()) echo $_label->addAttributes(array()) ?> &nbsp</td>                    
                    <td class="td_input_guestb"><?php echo $_form["adminName"]->getControl()->addAttributes(array()) ?></td>
                </tr>
                <tr>                
                    <td></td>                    
                    <td class="td_input_guestb_submit"><?php echo $_form["submit"]->getControl()->addAttributes(array()) ?>
 &nbsp&nbsp <?php echo $_form["publish"]->getControl()->addAttributes(array()) ?></td>                    
                </tr>                                 
         </table>    
         <table cellspacing="0">                
                <tr style="height: 10px;"><td></td></tr>
         </table>
         <h2></h2>               
<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ;
}}

//
// block _formResponse
//
if (!function_exists($_l->blocks['_formResponse'][] = '_lbfb79f4d3ba__formResponse')) { function _lbfb79f4d3ba__formResponse($_l, $_args) { extract($_args); $_control->validateControl('formResponse')
?>    <div id="respondForm">
<?php Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = $_control["respondForm"], array()) ?>
         <table cellspacing="0">                
                <tr style="height: 8px;"><td></td></tr>
         </table>
         <h2></h2>
         <table cellspacing="0">                
                <tr style="height: 15px;"><td></td></tr>
                <tr>
                    <td class="td_input_label_guestb_answ"><?php if ($_label = $_form["answer"]->getLabel()) echo $_label->addAttributes(array()) ?></td>                    
                    <td class="td_input_guestb_answ"><?php echo $_form["answer"]->getControl()->addAttributes(array()) ?></td>
                </tr>                
         </table>         
         <h2></h2>
         <table cellspacing="0">                
                <tr>                            
                    <td class="td_input_submit_guestb_answ"><?php echo $_form["submitResponse"]->getControl()->addAttributes(array()) ?>
 &nbsp&nbsp <?php echo $_form["back"]->getControl()->addAttributes(array()) ?></td> 
                </tr>
         </table>                 
<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ?>
    </div>
<?php
}}

//
// block _list
//
if (!function_exists($_l->blocks['_list'][] = '_lb5f730d0273__list')) { function _lb5f730d0273__list($_l, $_args) { extract($_args); $_control->validateControl('list')
;if ($data): if ($countQuestions >= 5): ?>
            <div id="paginate">
<?php $_ctrl = $_control->getComponent("paginator"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
            </div>            
            <h2></h2>
            <table cellspacing="0">                
                <tr style="height: 12px;"><td></td></tr>
            </table>            
<?php else: ?>
            <table cellspacing="0">                
                <tr style="height: 15px;"><td></td></tr>
            </table>
<?php endif ?>
         
<?php $iterations = 0; foreach ($data as $dat): if ($dat[0]): ?>
                    <div id="comments">
                        <div class="comment">
                        <div class="contain">
                                <h4><span><?php echo Nette\Templating\Helpers::escapeHtml($dat[0]->name, ENT_NOQUOTES) ?></span></h4>
                                <em class="date"><?php echo Nette\Templating\Helpers::escapeHtml(date_format($dat[0]->dateTime, 'd.m.Y H:i:s'), ENT_NOQUOTES) ?></em>
                        </div>
                        <div class="content_q" id="quest-<?php echo htmlSpecialChars($dat[0]->id) ?>">
                            <?php echo $template->nl2br(Nette\Templating\Helpers::escapeHtml($dat[0]->question, ENT_NOQUOTES)) ?>

                            <div style="padding-top: 15px;"></div>
                            <div class="actions_q">
                                <span class="questAction ajax" id="questAction-<?php echo htmlSpecialChars($dat[0]->id) ?>
">Odpovědet</span>  |  <a href="<?php echo htmlSpecialChars($_control->link("deleteQuestion!", array($dat[0]->id))) ?>" class="questHref ajax">Smazat</a>
                            </div>                                    
                        </div>
                        </div> 
                    </div>
<?php endif ;if ($dat[1]): ?>
                    <div id="comments">
                        <div class="comment answ" id="answ-<?php echo htmlSpecialChars($dat[1]->id) ?>">
                        <div class="contain">
                                <h4><span><?php echo Nette\Templating\Helpers::escapeHtml($guestBookUserName, ENT_NOQUOTES) ?></span></h4>
                                <em class="date"><?php echo Nette\Templating\Helpers::escapeHtml(date_format($dat[1]->dateTime, 'd.m.Y H:i:s'), ENT_NOQUOTES) ?></em>
                        </div>
                        <div class="content_q">
                            <?php echo $template->nl2br(Nette\Templating\Helpers::escapeHtml($dat[1]->answer, ENT_NOQUOTES)) ?>

                            <div style="padding-top: 15px;"></div>
                            <div class="actions_q">
                                <a href="<?php echo htmlSpecialChars($_control->link("deleteAnswer!", array($dat[1]->id))) ?>" class="questHref ajax">Smazat</a>
                            </div>                                    
                        </div>
                        </div>         
                    </div>                        
<?php endif ;$iterations++; endforeach ?>
            
            <table cellspacing="0">                
                <tr style="height: 5px;"><td></td></tr>
            </table>
            <h2></h2>
<?php if ($countQuestions >= 5): ?>
            <div id="paginate_bottom">
<?php $_ctrl = $_control->getComponent("paginator"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
            </div>               
<?php endif ?>
        
<?php else: ?>
            
            <div id="paginate">
                Litujeme, ale momentálně nejsou k dispozici žádné příspěvky.
            </div>            
            <h2></h2>    
            
<?php endif ;
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
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars()) ; call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars())  ?>
   