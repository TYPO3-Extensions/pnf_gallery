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
 * Function user_ttcontentVideoLinkOnCurrentPage() checks if a gallery plugin is inserted on current page
 * Based of powermail
 *
 * @return	boolean		0/1
 */
function user_ttcontentVideoLinkOnCurrentPage() {
	$result = FALSE;

	if (TYPO3_MODE == 'FE') {
		$ttContentWhere = 'AND deleted = 0 AND hidden = 0';
		if (is_array($GLOBALS['TCA']['tt_content']) && method_exists($GLOBALS['TSFE']->sys_page, 'enableFields')) {
			$ttContentWhere = $GLOBALS['TSFE']->sys_page->enableFields('tt_content');
		}

		$pid = $GLOBALS['TSFE']->id;
		$where = 'pid = ' . intval($pid) . ' AND (CType = "textpic" OR CType = "image") ' . $ttContentWhere;
		$orderBy = 'CType';
		$result = false;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ('uid, pid, CType, image_link', 'tt_content', $where, '', $orderBy, '');
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($row['image_link']) {
				$links = t3lib_div::trimExplode(chr(10), $row['image_link'], true);
				if (is_array($links)) {
					$obj = t3lib_div::makeInstance('tx_pnfgalleryvideo_userfunc');
					foreach ($links as $link) {
						if ($obj->isVideoFile($link, array())) {
							$result = true;
							break;
						}
					}
				}
				if ($result === TRUE) {
					break;
				}
			}
		}
	}
	return $result;
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery_video/Classes/user_ttcontentVideoLinkOnCurrentPage.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery_video/Classes/user_ttcontentVideoLinkOnCurrentPage.php']);
}
?>