<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 powermail development team (details on http://forge.typo3.org/projects/show/extension-powermail)
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
 * Function user_pnfgalleryOnCurrentPage() checks if a gallery plugin is inserted on current page
 * Based of powermail
 *
 * @return	boolean		0/1
 */
function user_pnfgalleryOnCurrentPage($theme = '') {
	$result = FALSE;

	if (TYPO3_MODE == 'FE') {
		$ttContentWhere = 'AND deleted = 0 AND hidden = 0';
		if (is_array($GLOBALS['TCA']['tt_content']) && method_exists($GLOBALS['TSFE']->sys_page, 'enableFields')) {
			$ttContentWhere = $GLOBALS['TSFE']->sys_page->enableFields('tt_content');
		}

		$pid = $GLOBALS['TSFE']->id;
		$where = 'pid = ' . intval($pid) . ' AND ((CType = "list" AND list_type = "pnf_gallery_pi1")  OR CType = "shortcut")' . $ttContentWhere;
		$orderBy = 'CType';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ('uid, pid, CType, records, list_type, pi_flexform', 'tt_content', $where, '', $orderBy, '');
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($row['CType'] == 'list' && $row['list_type'] == 'pnf_gallery_pi1') {
				$result = controlThemePnfgallery($theme, $row);
			} elseif ($row['CType'] == 'shortcut') {
				$recordUids = array();
				$records = t3lib_div::trimExplode(',', $row['records'], TRUE);
				foreach ($records as $record) {
					$recordInfo = t3lib_BEfunc::splitTable_Uid($record);
					if ($recordInfo[0] === 'tt_content') {
						$recordUids[] = $recordInfo[1];
					}
				}
				$recordUids = $GLOBALS['TYPO3_DB']->cleanIntList(implode(',', $recordUids));

				if(!$recordUids) {
					break;
				}

				$where = 'uid IN ( ' . $recordUids . ' ) AND CType = "pnf_gallery_pi1"' . $ttContentWhere;
				$shortcutRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tt_content', $where, '', '', 1);
				$countRow = $GLOBALS['TYPO3_DB']->sql_num_rows($shortcutRes);
				$result = ($countRow > 0) ? true : false;
			}
			if ($result === TRUE) {
				break;
			}
		}
	}
	return $result;
}

function controlThemePnfgallery($theme, $row) {
	if (TYPO3_MODE != 'FE')
		return false;
	if (!$theme)
		return true;
	$piTheme = '';
	$theme = trim($theme);
	
	// Flexform
	$flexData = (!empty($row['pi_flexform'])) ? (t3lib_div::xml2array($row['pi_flexform'])) : (array('data' => array()));
	if ($flexData['data']['design']['lDEF']['theme']['vDEF']) {
		$piTheme = trim($flexData['data']['design']['lDEF']['theme']['vDEF']);
	}
	
	// Typoscript
	if (!$piTheme) {
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_pnfgallery_pi1.'];
		if (is_array($conf) && $conf['theme'])
			$piTheme = $conf['theme'];
	}
	
	return (strtolower($piTheme) == strtolower($theme));
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/user_pnfgalleryOnCurrentPage.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/Classes/user_pnfgalleryOnCurrentPage.php']);
}
?>