<?php //netteCache[01]000374a:2:{s:4:"time";s:21:"0.13169000 1330262293";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:52:"C:\xampp\htdocs\app\components\Navigation\menu.phtml";i:2;i:1327757479;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"013c8ee released on 2012-02-03";}}}?><?php

// source file: C:\xampp\htdocs\app\components\Navigation\menu.phtml

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'n5pil40v4w')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block menu
//
if (!function_exists($_l->blocks['menu'][] = '_lb38fc51951a_menu')) { function _lb38fc51951a_menu($_l, $_args) { extract($_args)
;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($children) as $item): ?>	<li>
<?php if ($item->isCurrent): ?>
		<span class="active"><strong><?php echo Nette\Templating\Helpers::escapeHtml($item->label, ENT_NOQUOTES) ?></strong></span>
<?php else: if ($item->isPublished): ?>
                        <a href="<?php echo htmlSpecialChars($item->url) ?>" class="isPublished"><?php echo Nette\Templating\Helpers::escapeHtml($item->label, ENT_NOQUOTES) ?></a>
<?php else: ?>
                        <a href="<?php echo htmlSpecialChars($item->url) ?>" class="isNotPublished"><?php echo Nette\Templating\Helpers::escapeHtml($item->label, ENT_NOQUOTES) ?></a>
<?php endif ;endif ?>
		
<?php if ($renderChildren && count($item->getComponents()) > 0): ?>
		<ul>
<?php call_user_func(reset($_l->blocks['menu']), $_l, array('children' => $item->getComponents()) + get_defined_vars()) ?>
		</ul>
<?php endif ?>
	</li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;
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
<ul class="menu">
<?php if ($useHomepage): ?>	<li>
<?php if ($homepage->isCurrent): ?>
		<span class="active"><strong><?php echo Nette\Templating\Helpers::escapeHtml($homepage->label, ENT_NOQUOTES) ?></strong></span>
<?php else: ?>
		<a href="<?php echo htmlSpecialChars($homepage->url) ?>"><?php echo Nette\Templating\Helpers::escapeHtml($homepage->label, ENT_NOQUOTES) ?></a>
<?php endif ?>
	</li>
<?php endif ?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['menu']), $_l, get_defined_vars())  ?>
</ul>
