<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// --- Hook 
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['tx_pnfgallery_dam']['recordsQuery'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgalleryvideo_dam.php:tx_pnfgalleryvideo_dam';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['renderNewExtensionFile'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgalleryvideo_renderfile.php:tx_pnfgalleryvideo_renderfile';

tx_pnfgallery_themes_manager::subscribe('tx_pnfgalleryvideo_singlevideo');

include_once(t3lib_extMgm::extPath($_EXTKEY) . 'Classes/user_ttcontentVideoLinkOnCurrentPage.php'); // Conditions for JS and CSS including
?>