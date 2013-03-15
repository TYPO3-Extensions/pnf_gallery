<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// --- Hook 
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['tx_pnfgallery_dam']['recordsQuery'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damorder.php:tx_pnfgallery_damorder';

?>