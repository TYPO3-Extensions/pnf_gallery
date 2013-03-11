<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_pnfgallery_pi1.php', '_pi1', 'list_type', 0);

include_once(t3lib_extMgm::extPath($_EXTKEY) . 'Classes/user_pnfgalleryOnCurrentPage.php'); // Conditions for JS and CSS including

tx_pnfgallery_sources_manager::subscribe('tx_pnfgallery_dam');
tx_pnfgallery_themes_manager::subscribe('tx_pnfgallery_simplegallery');
tx_pnfgallery_themes_manager::subscribe('tx_pnfgallery_widget');
tx_pnfgallery_themes_manager::subscribe('tx_pnfgallery_mobilegallery');

?>