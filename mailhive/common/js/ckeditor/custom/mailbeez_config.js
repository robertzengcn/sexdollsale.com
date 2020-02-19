/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
};

CKEDITOR.editorConfig = function (config) {
    config.toolbar = 'MailBeez';
    config.toolbar_MailBeez =
        [
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            { name: 'document', items: [ 'Source'] },
            { name: 'editing', items: [ 'Find', 'Replace' ] },
            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
            '/',
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'TextColor', 'BGColor', '-', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv',
                '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
            { name: 'links', items: [  'Image', 'Link', 'Unlink', 'Table', 'HorizontalRule', 'SpecialChar' ] },
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] }
        ];

//    config.extraPlugins = 'codemirror';

    // avoid <p> (see http://internations.github.com/antwort)
    config.enterMode = CKEDITOR.ENTER_BR;
    /* Enter key means br not p */
//    config.shiftEnterMode = CKEDITOR.ENTER_P;
    /* Paragraphs are now made by pressing shift and enter together instead */
    config.extraPlugins = 'placeholder';

    config.extraPlugins = 'templates';

    config.extraPlugins = 'wordcount';
    config.wordcount = {

        // Whether or not you want to show the Word Count
        showWordCount: true,

        // Whether or not you want to show the Char Count
        showCharCount: true,

        // Whether or not to include Html chars in the Char Count
        countHTML: false,

        // Option to limit the characters in the Editor
        charLimit: 'unlimited',

        // Option to limit the words in the Editor
        wordLimit: 'unlimited'
    };

    config.allowedContent = true;
    config.protectedSource = [];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';

    // disable http://ckeditor.com/addon/autosave
    config.autosave_NotOlderThen = 0;
};
CKEDITOR.config.templates_replaceContent = false;

/*

 config.toolbar_Full =
 [
 { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
 { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
 { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
 { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
 'HiddenField' ] },
 '/',
 { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
 { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
 '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
 { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
 { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
 '/',
 { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
 { name: 'colors', items : [ 'TextColor','BGColor' ] },
 { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
 ];

 */