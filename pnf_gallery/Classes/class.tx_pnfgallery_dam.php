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
		$this->conf = $conf;
		switch ($conf['mode']) {
			case 'DAM_DIRECTORY':
				if ($conf['dam_directory']) 
					$records = $this->getRecordsDam($conf['dam_directory'], $conf['dam_subdirectories'], '', $conf['dam_orderby']);
				break;
			case 'DAM_RECORDS':
				if ($conf['dam_records']) {
					$uidList = t3lib_div::trimExplode(',', $conf['dam_records'], true);
					array_walk($uidList, array('tx_pnfgallery_dam','cutPrefixRecords'));
					$uidList = implode(',', $uidList);
					$records = $this->getRecordsDam('', false, $uidList, $conf['dam_orderby']);
				}
				break;
			default:
				// Hook 
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['getElements'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['getElements'] as $_classRef) {
						$_procObj = & t3lib_div::getUserObj($_classRef);
						$recordsTmp = $_procObj->getElements($conf, $this);
						if (is_array($recordsTmp))
							$records = $recordsTmp;
					}
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
	public function getRecordsDam($directory = '', $subdirectories = false, $uidList = '', $order = '') {		
		$queryArray = array(
			'SELECT' => '',
			'FROM' => '',
			'WHERE' => '',
			'GROUPBY' => '',
			'ORDER' => '',
			'LIMIT' => '',
		);
		switch ($order) {
			case 'uid':
				$queryArray['ORDER'] = '`tx_dam`.`uid`';
				break;
			case 'crdate':
				$queryArray['ORDER'] = '`tx_dam`.`date_cr` DESC';
				break;
			case 'rand':
				$queryArray['ORDER'] = 'rand()';
				break;
		}
		$addWhere = array();
		// $addWhere[] = '`tx_dam`.`file_mime_type` = \'image\'';
		$addWhere[] = '`tx_dam`.`media_type` = 2'; // => image
		if ($directory) {
			$lastChar = substr($directory, -1);
			if ($lastChar != '/')
				$directory .= '/';
			if ($subdirectories)
				$directory .= '%';
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
		$queryArray['WHERE'] =  implode(' AND ', $addWhere) . ' AND sys_language_uid=0 ' . $this->cObj->enableFields('tx_dam');
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
			$queryArray['GROUPBY'],
			$queryArray['ORDER'],
			$queryArray['LIMIT']
		);
		return $this->getLanguageRecords('tx_dam', $rows);
	}
	
	/**
	 * get translate record, if exist, each field are merged
	 *
	 * @param	string	$table: database tablename
	 * @param	array	$rows: records to translate
	 * @return 	array
	 */	
	function getLanguageRecords($table, $rows) {
		$rowsLanguage = array();
		
		if (is_array($rows)) {
			if ($GLOBALS['TSFE']->sys_language_content) {
				foreach ($rows as $row) {
					// $OLmode = ($this->sys_language_mode == 'strict' ? 'hideNonTranslated' : '');
					// $row = $GLOBALS['TSFE']->sys_page->getRecordOverlay($table, $row, $GLOBALS['TSFE']->sys_language_content, '');
					$records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
						'`' . $table . '`.*', 
						'`' . $table . '`', 
						'`' . $table . '`.`l18n_parent` = ' . $row['uid'] . '  AND `' . $table . '`.`sys_language_uid` = ' . $GLOBALS['TSFE']->sys_language_content . ' ' .$this->cObj->enableFields($table)
					);
					if (is_array($records) && !empty($records)) {
						$translate = $records[0];
						
						foreach ($row as $field => &$value) {
							if ($field != 'uid' && $translate[$field]) 
								$row[$field] = $translate[$field];
						}
					}
					$rowsLanguage[] = $row;
				}
			} else {
				$rowsLanguage = $rows;
			}
		}
		return $rowsLanguage;
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
				0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery/locallang_dam.xml:mode.dam.directory'),
				1 => 'DAM_DIRECTORY',
			);					
			$add[] = array(
				0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery/locallang_dam.xml:mode.dam.records'),
				1 => 'DAM_RECORDS',
			);
			// Hook 
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['addModes'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['addModes'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$_procObj->addModes($add, $flexConfig, $row, $this);
				}
			}
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
		// Hook 
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['addDataFlexform'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_dam']['addDataFlexform'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$_procObj->addDataFlexform($add, $row, $mode, $this);
			}
		}
		return $add;
	}
	
	
 }
 
 if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_dam.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_dam.php']);
}
 ?>