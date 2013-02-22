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
 * itemsProcFunc to add hook for extends data flexform
 *
 * @author	Emilie Sagniez <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgallery
 */
 class tx_pnfgallery_flex_itemsProcFunc {
	
	/**
	 * insert 'codes', found in the ['source'] array to the selector in the BE.
	 *
	 * @param	array		$config: extension configuration array
	 * @return	array		$config array with extra codes merged in
	 */
	function user_insertSources($config) {
		$items = array();
		$sources = tx_pnfgallery_sources_manager::getSubscribers();
		if (!empty($sources)) {
			$row = $config['row'];
			if ($row) {
				$flexData = (!empty($row['pi_flexform'])) ? (t3lib_div::xml2array($row['pi_flexform'])) : (array('data' => array()));
				if(empty($config['row']['pi_flexform']))
					$items[] = array('' , '');
				foreach ($sources as $key => $obj) {
					$result = $obj->flexform_addSource($config, $flexData);
					if (is_array($result) && !empty($result)) {
						$items[] = $result;
					}
				}
				if (!empty($items))
					$config['items'] = array_merge($config['items'], $items);
			}
		}
		return $config;
	}
	
	/**
	 * insert 'codes', found in the ['mode'] array to the selector in the BE.
	 *
	 * @param	array		$config: extension configuration array
	 * @return	array		$config array with extra codes merged in
	 */
	function user_insertModes($config) {
		$sources = tx_pnfgallery_sources_manager::getSubscribers();
		if (!empty($sources)) {
			$row = $config['row'];
			if ($row) {
				$flexData = (!empty($row['pi_flexform'])) ? (t3lib_div::xml2array($row['pi_flexform'])) : (array('data' => array()));
				foreach ($sources as $key => $obj) {
					$items = $obj->flexform_addModes($config, $flexData);
					if (is_array($items) && !empty($items)) {
						$config['items'] = array_merge($config['items'], $items);
					}
				}
			}
		}
		return $config;
	}
	
	/**
	 * insert 'codes', found in the ['theme'] array to the selector in the BE.
	 *
	 * @param	array		$config: extension configuration array
	 * @return	array		$config array with extra codes merged in
	 */
	function user_insertTheme($config) {
		$items = array();
		$themes = tx_pnfgallery_themes_manager::getSubscribers();
		if (!empty($themes)) {
			$row = $config['row'];
			if ($row) {
				$flexData = (!empty($row['pi_flexform'])) ? (t3lib_div::xml2array($row['pi_flexform'])) : (array('data' => array()));
				foreach ($themes as $key => $obj) {
					$result = $obj->flexform_addTheme($config, $flexData);
					if (is_array($result) && !empty($result)) {
						$items[] = $result;
					}
				}
				if (!empty($items))
					$config['items'] = array_merge($config['items'], $items);
			}
		}
		return $config;
	}
	
 }
 
 if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_flex_itemsProcFunc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_flex_itemsProcFunc.php']);
}
 ?>