<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:pnf_gallery/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:' . $_EXTKEY . '/flexform_ds_pi1.xml');
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'tx_pnfgallery_dynflex';

if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_pnfgallery_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_pnfgallery_pi1_wizicon.php';
}

t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/', 'PNF Gallery configuration');
t3lib_extMgm::addStaticFile($_EXTKEY,'themes/simplegallery/static/', 'PNF Gallery - simplegallery theme');
t3lib_extMgm::addStaticFile($_EXTKEY,'themes/widget/static/', 'PNF Gallery - widget theme');
t3lib_extMgm::addStaticFile($_EXTKEY,'themes/mobilegallery/static/', 'PNF Gallery - mobilegallery theme');
?>