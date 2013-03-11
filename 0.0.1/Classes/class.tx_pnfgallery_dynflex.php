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
class tx_pnfgallery_dynflex {

	var $prefixId = 'pnf_gallery_pi1';
	
	function getSingleField_preProcess($table, $field, array &$row, $altName, $palette, $extra, $pal, t3lib_TCEforms $tce)
	{
		if (($table != 'tt_content') || ($field != 'pi_flexform') || ($row['CType'] != 'list') || ($row['list_type'] != $this->prefixId))
			return;
		t3lib_div::loadTCA($table);
		$conf = &$GLOBALS['TCA'][$table]['columns'][$field];
		$this->id = $row['pid'];
		$flexData = (!empty($row['pi_flexform'])) ? (t3lib_div::xml2array($row['pi_flexform'])) : (array('data' => array()));
		
		$flexSource = '';
		$flexTheme = '';
		$sources = tx_pnfgallery_sources_manager::getSubscribers();
		foreach ($sources as $key => $obj) {
			$result = $obj->flexform_addData($flexData);
			if ($result) {
				$flexSource .= $result;
			}
		}
		$themes = tx_pnfgallery_themes_manager::getSubscribers();
		foreach ($themes as $key => $obj) {
			$result = $obj->flexform_addData($flexData);
			if ($result) {
				$flexTheme .= $result;
			}
		}
		if ($flexSource || $flexTheme) { 
			$conf['config']['ds'][$this->prefixId . ',list'] = str_replace(
				array('<!-- ###ADDITIONAL FLEX DATA MODE### -->', '<!-- ###ADDITIONAL FLEX DATA THEME### -->'),
				array($flexSource, $flexTheme), 
				file_get_contents(t3lib_div::getFileAbsFileName('EXT:pnf_gallery/flexform_ds_pi1.xml'))
			);
		}
	}
	
	function emptyControl() {
		return '';
	}
}

 if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_dynflex.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/class.tx_pnfgallery_dynflex.php']);
}
?>