/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here.
        config.skin = 'kama';
        config.language = 'cs';
        config.uiColor = '#6EADC2';
        config.width = '800';
        config.resize_minWidth = '800';
        config.resize_maxWidth = '800';
        config.format_tags = 'p;h1;h2;h3';
        config.toolbar = 
        [
            { name: 'document',		items : [ 'Source','Find','-','NewPage','DocProps','Paste','-','Undo','Redo' ] },            
            { name: 'styles',		items : [ 'Format' ] },
            { name: 'colors',		items : [ 'TextColor','BGColor' ] },
            { name: 'basicstyles',	items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
            '/',
            { name: 'paragraph',	items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', ] },
            { name: 'links, insert',	items : [ 'Link','Unlink','Image','Table','HorizontalRule','SpecialChar','Iframe', ] },            
        ];

        // KCFinder configuration.
        config.filebrowserBrowseUrl = '../../kcfinder/browse.php?type=dokumenty';
        config.filebrowserImageBrowseUrl = '../../kcfinder/browse.php?type=galerie';
        config.filebrowserFlashBrowseUrl = '../../kcfinder/browse.php?type=flash';
        config.filebrowserUploadUrl = '../../kcfinder/upload.php?type=dokumenty';
        config.filebrowserImageUploadUrl = '../../kcfinder/upload.php?type=galerie';
        config.filebrowserFlashUploadUrl = '../../kcfinder/upload.php?type=flash';
        config.filebrowserWindowWidth = '1000';
 	config.filebrowserWindowHeight = '600';
};
