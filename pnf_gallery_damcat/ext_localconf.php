<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// --- Hook 
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['tx_pnfgallery_dam']['addModes'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damcat.php:tx_pnfgallery_damcat';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['tx_pnfgallery_dam']['addDataFlexform'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damcat.php:tx_pnfgallery_damcat';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['tx_pnfgallery_dam']['getElements'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damcat.php:tx_pnfgallery_damcat';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['tx_pnfgallery_dam']['recordsQuery'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damcat.php:tx_pnfgallery_damcat';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['addMarkersBase'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damcat.php:tx_pnfgallery_damcat';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pnf_gallery']['addMarkersBaseElement'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_pnfgallery_damcat.php:tx_pnfgallery_damcat';

?>