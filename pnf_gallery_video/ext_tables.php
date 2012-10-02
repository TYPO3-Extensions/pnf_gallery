<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_pnfgalleryvideo_time' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pnf_gallery_video/locallang_db.xml:tx_dam.tx_pnfgalleryvideo_time',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
	'tx_pnfgalleryvideo_thumbnail' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pnf_gallery_video/locallang_db.xml:tx_dam.tx_pnfgalleryvideo_thumbnail',		
		'config' => array (
			'type' => 'group',
			'internal_type' => 'file',
			'allowed' => 'gif,png,jpeg,jpg',	
			'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],	
			'show_thumbs' => 1,	
			'size' => 1,	
			'minitems' => 0,
			'maxitems' => 1,
		)
	),
	'tx_pnfgalleryvideo_accessibility_link' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pnf_gallery_video/locallang_db.xml:tx_dam.tx_pnfgalleryvideo_accessibility_link',		
		'config' => array (
			'type'     => 'input',
			'size'     => '15',
			'max'      => '255',
			'checkbox' => '',
			'eval'     => 'trim',
			'wizards'  => array(
				'_PADDING' => 2,
				'link'     => array(
					'type'         => 'popup',
					'title'        => 'Link',
					'icon'         => 'link_popup.gif',
					'script'       => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				)
			)
		)
	),
);


t3lib_div::loadTCA('tx_dam');
t3lib_extMgm::addTCAcolumns('tx_dam',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_dam','--div--;LLL:EXT:pnf_gallery_video/locallang_db.xml:tx_dam.tx_pnfgalleryvideo.tab,tx_pnfgalleryvideo_time;;;;1-1-1, tx_pnfgalleryvideo_thumbnail, tx_pnfgalleryvideo_accessibility_link', 4);


t3lib_extMgm::addStaticFile($_EXTKEY,'static/', 'PNF Gallery - add video');
t3lib_extMgm::addStaticFile($_EXTKEY,'themes/singlevideo/static/', 'PNF Gallery - singlevideo theme');

?>