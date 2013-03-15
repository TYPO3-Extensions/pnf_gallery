<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array(
	'tx_pnfgallerydamorder_order' => array(		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pnf_gallery_dam_order/locallang_db.xml:tx_dam.tx_pnfgallerydamorder_order',		
		'config' => array(
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'int,unique',
		)
	),
);


t3lib_div::loadTCA('tx_dam');
t3lib_extMgm::addTCAcolumns('tx_dam',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_dam','tx_pnfgallerydamorder_order;;;;1-1-1');
?>