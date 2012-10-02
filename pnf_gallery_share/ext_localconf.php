<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// --- Hook 
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['addMarkersBaseElement'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgalleryshare_markers.php:tx_pnfgalleryshare_markers';


?>