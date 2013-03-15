<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "pnf_gallery".
 *
 * Auto generated 15-03-2013 15:32
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Gallery',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Plan.Net France',
	'author_email' => 'typo3@plan-net.fr',
	'shy' => '',
	'dependencies' => 'dam',
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
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'dam' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:111:{s:9:"ChangeLog";s:4:"0dc7";s:16:"ext_autoload.php";s:4:"9855";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"1903";s:14:"ext_tables.php";s:4:"f060";s:16:"flexform_dam.xml";s:4:"6b58";s:19:"flexform_ds_pi1.xml";s:4:"782c";s:13:"locallang.xml";s:4:"bd6a";s:17:"locallang_dam.xml";s:4:"4684";s:16:"locallang_db.xml";s:4:"cd00";s:26:"locallang_flexform_pi1.xml";s:4:"c8ee";s:10:"README.txt";s:4:"ee2d";s:35:"Classes/class.tx_pnfgallery_dam.php";s:4:"3d86";s:39:"Classes/class.tx_pnfgallery_dynflex.php";s:4:"4f0c";s:50:"Classes/class.tx_pnfgallery_flex_itemsProcFunc.php";s:4:"2e44";s:47:"Classes/class.tx_pnfgallery_sources_manager.php";s:4:"c67c";s:46:"Classes/class.tx_pnfgallery_themes_manager.php";s:4:"052a";s:42:"Classes/interface.tx_pnfgallery_source.php";s:4:"70b8";s:41:"Classes/interface.tx_pnfgallery_theme.php";s:4:"ae0d";s:40:"Classes/user_pnfgalleryOnCurrentPage.php";s:4:"e1a2";s:19:"doc/wizard_form.dat";s:4:"fdb0";s:20:"doc/wizard_form.html";s:4:"98f5";s:14:"pi1/ce_wiz.gif";s:4:"02b6";s:31:"pi1/class.tx_pnfgallery_pi1.php";s:4:"f0d0";s:39:"pi1/class.tx_pnfgallery_pi1_wizicon.php";s:4:"a076";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.xml";s:4:"204b";s:24:"pi1/static/constants.txt";s:4:"3321";s:20:"pi1/static/setup.txt";s:4:"720d";s:12:"res/left.png";s:4:"acf3";s:28:"res/pnf_gallery_lightbox.css";s:4:"8e77";s:27:"res/pnf_gallery_lightbox.js";s:4:"7c85";s:33:"res/pnf_gallery_lightbox_bckup.js";s:4:"2428";s:13:"res/right.png";s:4:"48f2";s:46:"themes/masonry/class.tx_pnfgallery_masonry.php";s:4:"c12d";s:27:"themes/masonry/flexform.xml";s:4:"214a";s:37:"themes/masonry/locallang_flexform.xml";s:4:"e9c2";s:29:"themes/masonry/screenshot.jpg";s:4:"5b54";s:40:"themes/masonry/res/jquery.masonry.min.js";s:4:"a6b7";s:28:"themes/masonry/res/style.css";s:4:"85ff";s:32:"themes/masonry/res/template.html";s:4:"1c05";s:35:"themes/masonry/static/constants.txt";s:4:"efa3";s:31:"themes/masonry/static/setup.txt";s:4:"05cc";s:58:"themes/mobilegallery/class.tx_pnfgallery_mobilegallery.php";s:4:"dd75";s:33:"themes/mobilegallery/flexform.xml";s:4:"d41d";s:43:"themes/mobilegallery/locallang_flexform.xml";s:4:"896a";s:38:"themes/mobilegallery/res/template.html";s:4:"7cd3";s:51:"themes/mobilegallery/res/template_jQueryMobile.html";s:4:"c2da";s:46:"themes/mobilegallery/res/photoswipe/change.log";s:4:"bed5";s:60:"themes/mobilegallery/res/photoswipe/code.photoswipe-3.0.5.js";s:4:"3fcb";s:64:"themes/mobilegallery/res/photoswipe/code.photoswipe-3.0.5.min.js";s:4:"8e77";s:67:"themes/mobilegallery/res/photoswipe/code.photoswipe.jquery-3.0.5.js";s:4:"f5a5";s:71:"themes/mobilegallery/res/photoswipe/code.photoswipe.jquery-3.0.5.min.js";s:4:"34b4";s:45:"themes/mobilegallery/res/photoswipe/error.gif";s:4:"ba74";s:45:"themes/mobilegallery/res/photoswipe/icons.png";s:4:"fd94";s:48:"themes/mobilegallery/res/photoswipe/icons@2x.png";s:4:"feaa";s:46:"themes/mobilegallery/res/photoswipe/loader.gif";s:4:"37e2";s:51:"themes/mobilegallery/res/photoswipe/MIT-license.txt";s:4:"1b43";s:50:"themes/mobilegallery/res/photoswipe/photoswipe.css";s:4:"7f58";s:45:"themes/mobilegallery/res/photoswipe/README.md";s:4:"f1d9";s:52:"themes/mobilegallery/res/photoswipe/lib/klass.min.js";s:4:"08b0";s:74:"themes/mobilegallery/res/photoswipe/noutil/code.photoswipe.noutil-3.0.5.js";s:4:"6ee3";s:78:"themes/mobilegallery/res/photoswipe/noutil/code.photoswipe.noutil-3.0.5.min.js";s:4:"41ee";s:81:"themes/mobilegallery/res/photoswipe/noutil/code.photoswipe.noutil.jquery-3.0.5.js";s:4:"6ee3";s:85:"themes/mobilegallery/res/photoswipe/noutil/code.photoswipe.noutil.jquery-3.0.5.min.js";s:4:"41ee";s:41:"themes/mobilegallery/static/constants.txt";s:4:"e0b2";s:37:"themes/mobilegallery/static/setup.txt";s:4:"5d1f";s:58:"themes/simplegallery/class.tx_pnfgallery_simplegallery.php";s:4:"df44";s:33:"themes/simplegallery/flexform.xml";s:4:"71d6";s:43:"themes/simplegallery/locallang_flexform.xml";s:4:"fe43";s:35:"themes/simplegallery/screenshot.jpg";s:4:"7a09";s:34:"themes/simplegallery/res/script.js";s:4:"d41d";s:34:"themes/simplegallery/res/style.css";s:4:"d41d";s:38:"themes/simplegallery/res/template.html";s:4:"12df";s:66:"themes/simplegallery/res/galleria-1.2.7/galleria/galleria-1.2.7.js";s:4:"7c44";s:70:"themes/simplegallery/res/galleria-1.2.7/galleria/galleria-1.2.7.min.js";s:4:"cb6c";s:56:"themes/simplegallery/res/galleria-1.2.7/galleria/LICENSE";s:4:"93a1";s:80:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/flickr-demo.html";s:4:"3edd";s:81:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/flickr-loader.gif";s:4:"1dbb";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/galleria.flickr.js";s:4:"2cb4";s:86:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/galleria.flickr.min.js";s:4:"166d";s:74:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/flickr/loader.gif";s:4:"0b0f";s:84:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/history/galleria.history.js";s:4:"9968";s:88:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/history/galleria.history.min.js";s:4:"e2c0";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/history/history-demo.html";s:4:"eca8";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/galleria.picasa.js";s:4:"4ba8";s:86:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/galleria.picasa.min.js";s:4:"d2a3";s:74:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/loader.gif";s:4:"0b0f";s:80:"themes/simplegallery/res/galleria-1.2.7/galleria/plugins/picasa/picasa-demo.html";s:4:"e3a7";s:81:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/classic-demo.html";s:4:"2df1";s:82:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/classic-loader.gif";s:4:"0b0f";s:79:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/classic-map.png";s:4:"e554";s:84:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/galleria.classic.css";s:4:"ea3e";s:83:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/galleria.classic.js";s:4:"76be";s:87:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/classic/galleria.classic.min.js";s:4:"a67f";s:96:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/galleria.simplegallery.css";s:4:"10c8";s:95:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/galleria.simplegallery.js";s:4:"c965";s:93:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/simplegallery-demo.html";s:4:"d594";s:94:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/simplegallery-loader.gif";s:4:"2a66";s:91:"themes/simplegallery/res/galleria-1.2.7/galleria/themes/simplegallery/simplegallery-map.png";s:4:"8a6b";s:41:"themes/simplegallery/static/constants.txt";s:4:"4174";s:37:"themes/simplegallery/static/setup.txt";s:4:"ab23";s:44:"themes/widget/class.tx_pnfgallery_widget.php";s:4:"14d6";s:26:"themes/widget/flexform.xml";s:4:"3b72";s:36:"themes/widget/locallang_flexform.xml";s:4:"b1b2";s:28:"themes/widget/screenshot.jpg";s:4:"2bc9";s:31:"themes/widget/res/ico_moins.png";s:4:"ff69";s:27:"themes/widget/res/style.css";s:4:"6c94";s:31:"themes/widget/res/template.html";s:4:"9c90";s:34:"themes/widget/static/constants.txt";s:4:"1bdd";s:30:"themes/widget/static/setup.txt";s:4:"d2d8";}',
	'suggests' => array(
	),
);

?>