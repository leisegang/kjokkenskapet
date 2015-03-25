/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.language = 'no';
	// config.uiColor = '#AADC6E';
	
	config.toolbar = 'Full';
 
	config.toolbar_Full =
	[
    	['Bold','Italic','Underline','Strike'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    	['Link','Unlink'],
	    ['Image','Table','HorizontalRule','SpecialChar'],
    	'/',
	    ['Format','Font','FontSize'],
    	['TextColor','-','Templates'],
    	['Cut','Copy','Paste','PasteText','PasteFromWord','-','RemoveFormat'],
	    ['Source']
	];
};
