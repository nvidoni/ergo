ProcessWire Ergo Admin Template
===========================================================================

USAGE

1. Create folder named "templates-admin" under /site/ folder
2. Copy all files (including folders) into newly created folder
3. ProcessWire will load the new admin template automatically

EXTRA

If you would like to use custom TinyMCE theme created for this template, follow this steps:

1. Download TinyMCE Ergo theme from https://github.com/nvidoni/ergo-tinymce
2. Copy "ergo" folder to /wire/modules/Inputfield/InputfieldTinyMCE/tinymce-3.4.7/themes/advanced/skins/ folder
3. Open /site/templates-admin/default.php and change in line 86 "default" to "ergo"