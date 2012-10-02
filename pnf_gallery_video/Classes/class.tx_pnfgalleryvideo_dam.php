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
 * Extend 'pnf_gallery' DAM Source to add video file.
 *
 * @author	Emilie Sagniez <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgalleryvideo
 */
 class tx_pnfgalleryvideo_dam {
	
	/**
	 *	Modify records select query to add video file 
	 *	Use 'tx_pnfgallery_dam' source hook
	 *
	 */
	function recordsQuery($queryArray, $addWhere, $directory, $uidList, $piObj) {
		$addWhere = array();
		// $addWhere[] = '`tx_dam`.`file_mime_type` = \'image\'';
		$addWhere[] = '`tx_dam`.`media_type` IN (2, 4)'; // => image ou video
		if ($directory) {
			$lastChar = substr($directory, -1);
			if ($lastChar != '/')
				$directory .= '/';
			$addWhere[] = '`tx_dam`.`file_path` like \'' . $directory . '\'';
		}
		if ($uidList)
			$addWhere[] = '`tx_dam`.`uid` in (' . $uidList . ')';
		$queryArray['WHERE'] =  implode(' AND ', $addWhere) . ' ' . $piObj->cObj->enableFields('tx_dam');	
		
		$queryArray['SELECT'] .= ',
			`tx_dam`.`tx_pnfgalleryvideo_time`,
			`tx_dam`.`tx_pnfgalleryvideo_thumbnail`,
			`tx_dam`.`tx_pnfgalleryvideo_accessibility_link`
			';
			
		return $queryArray;
	}
	
 }
 
 ?>