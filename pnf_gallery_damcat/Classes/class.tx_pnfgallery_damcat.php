<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Plan.Net France <typo3@plan-net.fr>
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
 * Extend 'pnf_gallery' DAM Source to add categories.
 *
 * @author	Emilie Sagniez <emilie.sagniez@plan-net.fr>
 * @package	TYPO3
 * @subpackage	tx_pnfgallerydamcat
 */
 class tx_pnfgallery_damcat {
	
	var $extKey        = 'pnf_gallery';	// The extension key.
	
	/**
	 *	Get images to damcat mode
	 *	Use 'tx_pnfgallery_dam' source hook
	 *
	 */
	public function getElements($conf, $piObj) {
		$records = null;
		if ($conf['mode'] == 'DAMCAT_RECORDS' && $conf['damcat_records']) {
			$uidList = t3lib_div::trimExplode(',', $conf['damcat_records'], true);
			array_walk($uidList, array('tx_pnfgallery_damcat','cutPrefixRecords'));
			$uidList = implode(',', $uidList);
			
			$piObj->damcatRecord = $uidList;
			$records = $piObj->getRecordsDam('', false, '', $conf['dam_orderby']);
			
			// Control multiple categories
			$indexRecords = array();
			foreach ($records as $index => &$record) {
				$indexR = $indexRecords[$record['uid']];
				if (isset($indexR)) {
					// More damcat
					$records[$indexR]['damcat_uid'] .= ',' . $record['damcat_uid'];
					unset($records[$index]);
				} else {
					$indexRecords[$record['uid']] = $index;
				}
			}			
		}
		return $records;
	}
	
	/**
	 *	Modify records select query to add video file 
	 *	Use 'tx_pnfgallery_dam' source hook
	 *
	 */
	public function recordsQuery($queryArray, $addWhere, $directory, $uidList, $piObj) {
		if ($piObj->damcatRecord) {
			// Current
			$params = t3lib_div::_GP('tx_pnfgallery_damcat');
			if ($params['uid']) {
				$piObj->damcatRecord = $params['uid'];
			}
			
			// Subcategories
			$tmp = array();
			$level = 0;
			$categories = explode(',', $piObj->damcatRecord);
			$this->cObj = $piObj->cObj;
			$this->getCategoriesRecursive($tmp, $piObj->damcatRecord, $level, $categories);
			$categories = implode(',', $categories);
			
			$queryArray['SELECT'] .= ',
				`tx_dam_mm_cat`.`uid_foreign` as damcat_uid
			';
			$queryArray['FROM'] .= '
				INNER JOIN `tx_dam_mm_cat` 
				ON `tx_dam_mm_cat`.`uid_local` = `tx_dam`.`uid`
				AND `tx_dam_mm_cat`.`uid_foreign` IN (' . $categories . ')';
		}
		return $queryArray;
	}
	
	/**
	 *	Remove records prefix
	 */
	static function cutPrefixRecords(&$value, $key){
		$value = str_replace('tx_dam_cat_', '', $value);
	}
	
	/**
	 *	Add categories mode to flexform plugin
	 *	Use 'tx_pnfgallery_dam' source hook
	 *
	 */
	public function addModes(&$add, $flexConfig, $row, $piObj) {
		$add[] = array(
			0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery_damcat/locallang.xml:mode.damcat.records'),
			1 => 'DAMCAT_RECORDS',
		);
	}
	
	/**
	 *	Add configurations to flexform if damcat_records source has selected
	 *	Use 'tx_pnfgallery_dam' source hook
	 *
	 */
	public function addDataFlexform(&$add, $row, $mode, $piObj) {
		if($mode == 'DAMCAT_RECORDS') {
			$add .= file_get_contents(t3lib_div::getFileAbsFileName('EXT:pnf_gallery_damcat/flexform.xml'));
		}
	}
	
	public function addMarkersBaseElement($markers, $elementArray, $piObj) {
		if (is_array($piObj->conf['renderFile.']['damcat_uid.']))
			$markers['###DAMCAT_UID###'] = $piObj->cObj->stdWrap($elementArray['damcat_uid'], $piObj->conf['renderFile.']['damcat_uid.']);
		else
			$markers['###DAMCAT_UID###'] = $elementArray['damcat_uid'];
		return $markers;
	}
	
	public function addMarkersBase($markers, $elementArray, $piObj) {
		if ($piObj->conf['mode'] == 'DAMCAT_RECORDS' && $piObj->conf['damcat_records']) {
			$this->cObj = $piObj->cObj;
			$categories = t3lib_div::trimExplode(',', $piObj->conf['damcat_records'], true);
			array_walk($categories, array('tx_pnfgallery_damcat','cutPrefixRecords'));
			$categories = implode(',', $categories);
			$markers['###DAMCAT_FILTER###'] = $this->getCategoriesFilter($categories, $piObj);
		}
		return $markers;
	}
	
	private function getCategoriesFilter($catuids, $piObj) {
		$categories = array();
		$this->getCategoriesRecursive($categories, $catuids);
		$content = $piObj->getTemplate($piObj->conf['renderGallery.']['damcat_filter.']['template'], '###FILTER###');
		$output_categories = '';
		$output_category = '';
		$subpart_categories = $this->cObj->getSubpart($content, '###SUBPART_CATEGORIES###');
		$subpart_category = $this->cObj->getSubpart($content, '###SUBPART_CATEGORY###');
		$subpart_subcategories = $this->cObj->getSubpart($content, '###SUBPART_SUBCATEGORIES###');
		$subpart_subcategory = $this->cObj->getSubpart($content, '###SUBPART_SUBCATEGORY###');
		
		if (is_array($categories) && !empty($categories)) {
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_damcat']['sortCategories'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['tx_pnfgallery_damcat']['sortCategories'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$_procObj->sortCategories($categories, $conf, $this);
				}
			}
			$tscObj = t3lib_div::makeInstance('tslib_cObj');
			foreach ($categories as $category) {
				$tscObj->start($category, '');
		
				$cMarkers = array(
					'###CATEGORY_UID###' => $category['uid'],
					'###CATEGORY_TITLE###' => $tscObj->cObjGetSingle($piObj->conf['renderGallery.']['damcat_filter.']['title'], $piObj->conf['renderGallery.']['damcat_filter.']['title.']),
					'###HAS_SUBCATEGORIES###' => (is_array($category['children']) && !empty($category['children'])) ? $piObj->conf['renderGallery.']['damcat_filter.']['subcategories'] : '',
					'###CATEGORY_LINK###' => $tscObj->cObjGetSingle($piObj->conf['renderGallery.']['damcat_filter.']['link'], $piObj->conf['renderGallery.']['damcat_filter.']['link.']),
				);
				$cSubparts = array(
					'###SUBPART_SUBCATEGORIES###' => $this->renderSubcategories($category['children'], $subpart_subcategories, $subpart_subcategory, $piObj, $tscObj),
				);
				$output_category .= $this->cObj->substituteMarkerArrayCached($subpart_category, $cMarkers, $cSubparts);
			}
			$output_categories = $this->cObj->substituteSubpart($subpart_categories, '###SUBPART_CATEGORY###', $output_category);
		}
		
		$sMarkers = array(
			'###CEID###' => $this->cObj->data['uid'],
			'###DELETE_FILTER###' => $this->cObj->cObjGetSingle($piObj->conf['renderGallery.']['damcat_filter.']['delete'], $piObj->conf['renderGallery.']['damcat_filter.']['delete.']),
		);
		$sSubparts = array('###SUBPART_CATEGORIES###' => $output_categories);
		$content = $this->cObj->substituteMarkerArrayCached($content, $sMarkers, $sSubparts);
		return $content;
	}
	
	private function renderSubcategories($subcategories, $subpart_subcategories, $subpart_subcategory, $piObj, $tscObj) {
		$output = '';
		if (is_array($subcategories) && !empty($subcategories)) {
			$output_sub = '';
			foreach ($subcategories as $subcategory) {
				$tscObj->start($subcategory, '');
				$sMarkers = array(
					'###SUBCATEGORY_UID###' => $subcategory['uid'],
					'###SUBCATEGORY_TITLE###' => $subcategory['title'],
					'###SUBCATEGORY_LINK###' =>  $tscObj->cObjGetSingle($piObj->conf['renderGallery.']['damcat_filter.']['link'], $piObj->conf['renderGallery.']['damcat_filter.']['link.']),
					'###SUBCATEGORY_CHILDREN###' => $this->renderSubcategories($subcategory['children'], $subpart_subcategories, $subpart_subcategory, $piObj, $tscObj),
				);
				$output_sub .= $this->cObj->substituteMarkerArray($subpart_subcategory, $sMarkers);
			}
			$output = $this->cObj->substituteSubpart($subpart_subcategories, '###SUBPART_SUBCATEGORY###', $output_sub);
		}
		return $output;
	}
	
	private function getCategoriesRecursive(&$categories, $catuids, &$level = 0, &$all = array()) {
		$queryArray = array(
			'SELECT' => '',
			'FROM' => '',
			'WHERE' => '',
			'ORDER' => '',
			'LIMIT' => '',
		);
		
		$queryArray['SELECT'] = '`tx_dam_cat`.`uid`,
			`tx_dam_cat`.`title`,
			`tx_dam_cat`.`description`,
			`tx_dam_cat`.`parent_id`
			';
		$queryArray['FROM'] = '`tx_dam_cat`';
		$where = array();
		if (!$level)
			$where[] = '`tx_dam_cat`.`uid` IN (' . $catuids . ')';
		else
			$where[] = '`tx_dam_cat`.`parent_id` IN (' . $catuids . ')';
		$queryArray['WHERE'] =  implode(' AND ', $where) . ' ' . $this->cObj->enableFields('tx_dam_cat');
		
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$queryArray['SELECT'], 
			$queryArray['FROM'],
			$queryArray['WHERE'],
			'',
			$queryArray['ORDER'],
			$queryArray['LIMIT']
		);
		if (is_array($rows) && !empty($rows)) {
			$rows = $this->getLanguageRecords('tx_dam_cat', $rows);
			$categories = $rows;
			$level++;
			foreach ($rows as $key => $row) {
				$categories[$key]['children'] = array();
				$all[] = $row['uid'];
				$this->getCategoriesRecursive($categories[$key]['children'], $row['uid'], $level, $all);
			}
		}
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
	
	
 }
 
 ?>