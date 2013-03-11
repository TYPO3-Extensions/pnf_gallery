<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Plan Net <technique@in-cite.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */
 
 /**
 * DAM Source for the 'pnf_gallery' extension.
 *
 * @author	Emilie Sagniez <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgallery
 */
 class tx_pnfgallery_dam implements tx_pnfgallery_source {
	
	var $extKey        = 'pnf_gallery';	// The extension key.
	var $key = 'DAM';
	
	/**
	 *	Get unik key
	 *
	 *	@return string
	 */	 
	public function getKey() {
		return $this->key;
	}
	
	/**
	 *	Get source galery elements
	 *
	 *	@param	array	$conf: plugin configuration
	 *	@return array
	 */	
	public function getElements($conf) {
		$elements = array();
		$records = null;
		
		switch ($conf['mode']) {
			case 'DAM_DIRECTORY':
				if ($conf['dam_directory']) 
					$records = $this->getRecordsDam($conf['dam_directory']);
				break;
			case 'DAM_RECORDS':
				if ($conf['dam_records']) {
					$uidList = t3lib_div::trimExplode(',', $conf['dam_records'], true);
					array_walk($uidList, array('tx_pnfgallery_dam','cutPrefixRecords'));
					$uidList = implode(',', $uidList);
					$records = $this->getRecordsDam('', $uidList);
				}
				break;
		}
		
		if ($records) {
			foreach ($records as $record) {
				$requiredDataArray = array(
					'file' => $record['file_path'] . $record['file_name'],
					'alt' => $record['alt_text'],
				);
				
				$elements[] = array_merge($requiredDataArray, $record);
			}
		}
		return $elements;
	}
	
	/**
	 *	Get DAM records
	 *
	 *	@param	string $directory	: directory path
	 *	@param	string $uidList	: records uids 
	 *	@return request results
	 */
	private function getRecordsDam($directory = '', $uidList = '') {
		$queryArray = array(
			'SELECT' => '',
			'FROM' => '',
			'WHERE' => '',
			'ORDER' => '',
			'LIMIT' => '',
		);
		$addWhere = array();
		// $addWhere[] = '`tx_dam`.`file_mime_type` = \'image\'';
		$addWhere[] = '`tx_dam`.`media_type` = 2'; // => image
		if ($directory) {
			$lastChar = substr($directory, -1);
			if ($lastChar != '/')
				$directory .= '/';
			$addWhere[] = '`tx_dam`.`file_path` like \'' . $directory . '\'';
		}
		if ($uidList) {
			$addWhere[] = '`tx_dam`.`uid` in (' . $uidList . ')';
			$queryArray['ORDER'] = 'FIELD(uid,' . $uidList . ')';
		}
		
		$queryArray['SELECT'] = '`tx_dam`.`uid`,
			`tx_dam`.`title`,
			`tx_dam`.`file_mime_type`,
			`tx_dam`.`file_type`,
			`tx_dam`.`file_name`,
			`tx_dam`.`file_path`,
			`tx_dam`.`file_size`,
			`tx_dam`.`description`,
			`tx_dam`.`media_type`,
			`tx_dam`.`alt_text`,
			`tx_dam`.`caption`,
			`tx_dam`.`date_mod` as tstamp,
			`tx_dam`.`date_cr` as crdate,
			`tx_dam`.`hpixels`,
			`tx_dam`.`vpixels`
			';
		$queryArray['FROM'] = '`tx_dam`';
		$queryArray['WHERE'] =  implode(' AND ', $addWhere) . ' ' . $this->cObj->enableFields('tx_dam');
		// Hook 
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['recordsQuery'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['recordsQuery'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$queryArray = $_procObj->recordsQuery($queryArray, $addWhere, $directory, $uidList, $this);
			}
		}
		
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$queryArray['SELECT'], 
			$queryArray['FROM'],
			$queryArray['WHERE'],
			'',
			$queryArray['ORDER'],
			$queryArray['LIMIT']
		);
		return is_array($rows) ? $rows : array();
	}
	
	/**
	 *	Remove records prefix
	 */
	static function cutPrefixRecords(&$value, $key){
		$value = str_replace('tx_dam_', '', $value);
	}
	
	/** 
	 *	BACKEND: possibility to add new source records (only one) to plugin flexform configuration
	 *
	 *	@param	array	$flexConfig: flexform configuration
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	array	array : array ( 0 => label, 1 => value)
	 */
	public function flexform_addSource($flexConfig, $row) {
		return array(
			0 => 'DAM Sources',
			1 => $this->getKey(),
		);
	}
	
	/** 
	 *	BACKEND: possibility to add news modes view (one or more) to plugin flexform configuration
	 *
	 *	@param	array	$flexConfig: flexform configuration
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	array	array of news items : array ( 0 => array(0 => label, 1 => value), 1 => array(0 => label, 1 => value))
	 */
	public function flexform_addModes($flexConfig, $row) {
		$add = array();
		if ($row['data']['general']['lDEF']['source']
			&& is_array($row['data']['general']['lDEF']['source'])
			&& $row['data']['general']['lDEF']['source']['vDEF'] == $this->getKey()) {
							
			$add[] = array(
				0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery/locallang_flexform_pi1.xml:mode.dam.directory'),
				1 => 'DAM_DIRECTORY',
			);					
			$add[] = array(
				0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery/locallang_flexform_pi1.xml:mode.dam.records'),
				1 => 'DAM_RECORDS',
			);
		}
		return $add;
	}
	
	/** 
	 *	BACKEND: possibility to add news flexform configurations
	 *
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	string	xml flexform data
	 */
	public function flexform_addData($row) {
		$add = '';
		$mode = $row['data']['general']['lDEF']['mode']['vDEF'];
		if($mode == 'DAM_RECORDS' || $mode == 'DAM_DIRECTORY') {
			$add .= file_get_contents(t3lib_div::getFileAbsFileName('EXT:pnf_gallery/flexform_dam.xml'));
		}
		return $add;
	}
	
	
 }
 
 if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_dam.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_dam.php']);
}
 ?>