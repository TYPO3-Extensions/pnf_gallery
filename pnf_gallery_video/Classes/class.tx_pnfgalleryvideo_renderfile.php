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
 * Extend 'pnf_gallery' plugin to render video file.
 *
 * @author	Emilie Sagniez <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgalleryvideo
 */
class tx_pnfgalleryvideo_renderfile {
	
	var $videoExtensions = array('flv');
	
	function renderNewExtensionFile($fileArray, $informations, $piObj) {
		if (!in_array($informations['fileext'], $this->videoExtensions))
			return false;
			
		$fileArray['width'] = $piObj->conf['width'];
		$fileArray['height'] = $piObj->conf['height'];
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$cObj->start($fileArray, 'tx_dam');
		return $cObj->cObjGetSingle($piObj->conf['renderFile.']['video'], $piObj->conf['renderFile.']['video.']);
	}
}

?>