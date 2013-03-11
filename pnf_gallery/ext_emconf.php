<?php

########################################################################
# Extension Manager/Repository config file for ext "pnf_gallery".
#
# Auto generated 01-10-2012 10:20
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Gallery',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Plan Net',
	'author_email' => 'technique@in-cite.net',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:63:{s:9:"ChangeLog";s:4:"d236";s:16:"ext_autoload.php";s:4:"6c89";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"e166";s:14:"ext_tables.php";s:4:"1721";s:16:"flexform_dam.xml";s:4:"9299";s:19:"flexform_ds_pi1.xml";s:4:"782c";s:13:"locallang.xml";s:4:"bd6a";s:17:"locallang_dam.xml";s:4:"5517";s:16:"locallang_db.xml";s:4:"cd00";s:26:"locallang_flexform_pi1.xml";s:4:"66d6";s:10:"README.txt";s:4:"ee2d";s:35:"Classes/class.tx_pnfgallery_dam.php";s:4:"9729";s:39:"Classes/class.tx_pnfgallery_dynflex.php";s:4:"4f0c";s:50:"Classes/class.tx_pnfgallery_flex_itemsProcFunc.php";s:4:"a288";s:47:"Classes/class.tx_pnfgallery_sources_manager.php";s:4:"ab83";s:46:"Classes/class.tx_pnfgallery_themes_manager.php";s:4:"2e45";s:42:"Classes/interface.tx_pnfgallery_source.php";s:4:"70b8";s:41:"Classes/interface.tx_pnfgallery_theme.php";s:4:"ae0d";s:40:"Classes/user_pnfgalleryOnCurrentPage.php";s:4:"e1a2";s:19:"doc/wizard_form.dat";s:4:"fdb0";s:20:"doc/wizard_form.html";s:4:"98f5";s:14:"pi1/ce_wiz.gif";s:4:"02b6";s:31:"pi1/class.tx_pnfgallery_pi1.php";s:4:"b076";s:39:"pi1/class.tx_pnfgallery_pi1_wizicon.php";s:4:"a076";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.xml";s:4:"204b";s:24:"pi1/static/constants.txt";s:4:"3321";s:20:"pi1/static/setup.txt";s:4:"720d";s:58:"themes/simplegallery/class.tx_pnfgallery_simplegallery.php";s:4:"df44";s:33:"themes/simplegallery/flexform.xml";s:4:"71d6";s:43:"themes/simplegallery/locallang_flexform.xml";s:4:"fe43";s:34:"themes/simplegallery/res/script.js";s:4:"d41d";s:34:"themes/simplegallery/res/style.css";s:4:"d41d";s:38:"themes/simplegallery/res/template.html";s:4:"6c0f";s:66:"themes/simplegallery/res/galleria-1.2.7/galleria/galleria-1.2.7.js";s:4:"7c44";s:70:"themes/simplegallery/res/galleria-1.2.7/galleria/galleria-1.2.7.min.js";s:4:"cb6c";s:56:"themes/simplegallery/res/galleria-1.2.7/galleria/LICENSE";s:4:"93a1";s:80:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/flickr-demo.html";s:4:"3edd";s:81:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/flickr-loader.gif";s:4:"1dbb";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/galleria.flickr.js";s:4:"2cb4";s:86:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/galleria.flickr.min.js";s:4:"166d";s:74:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/loader.gif";s:4:"0b0f";s:84:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/history/galleria.history.js";s:4:"9968";s:88:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/history/galleria.history.min.js";s:4:"e2c0";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/history/history-demo.html";s:4:"eca8";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/galleria.picasa.js";s:4:"4ba8";s:86:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/galleria.picasa.min.js";s:4:"d2a3";s:74:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/loader.gif";s:4:"0b0f";s:80:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/picasa-demo.html";s:4:"e3a7";s:81:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/classic-demo.html";s:4:"2df1";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/classic-loader.gif";s:4:"0b0f";s:79:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/classic-map.png";s:4:"e554";s:84:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/galleria.classic.css";s:4:"ea3e";s:83:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/galleria.classic.js";s:4:"76be";s:87:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/galleria.classic.min.js";s:4:"a67f";s:96:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/galleria.simplegallery.css";s:4:"10c8";s:95:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/galleria.simplegallery.js";s:4:"c965";s:93:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/simplegallery-demo.html";s:4:"d594";s:94:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/simplegallery-loader.gif";s:4:"2a66";s:91:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/simplegallery-map.png";s:4:"8a6b";s:41:"themes/simplegallery/static/constants.txt";s:4:"4174";s:37:"themes/simplegallery/static/setup.txt";s:4:"4d75";}',
	'suggests' => array(
	),
);

?>