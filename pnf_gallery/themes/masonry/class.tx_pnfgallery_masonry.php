<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Plan Net <technique@in-cite.net>
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
 * Masonry theme for the 'pnf_gallery' extension.
 *
 * @author	Emilie Sagniez <emilie.sagniez@plan-net.fr>
 * @package	TYPO3
 * @subpackage	tx_pnfgallery
 */
 class tx_pnfgallery_masonry  implements tx_pnfgallery_theme{
 
	var $key = 'MASONRY';
	
	/**
	 *	Get unik key
	 *
	 *	@return string
	 */	 
	public function getKey() {
		return $this->key;
	}
	
	/**
	 *	Render gallery
	 *
	 *	@param	array	$conf: plugin configuration
	 *	@return html
	 */	
	public function renderGallery($elements, $conf, $piObj) {
		$flex = array();
		$piFlexForm = $piObj->cObj->data['pi_flexform'];
		$piObj->extractFlexformConfiguration($piFlexForm, $flex);
		$conf['width'] = $flex['width'];
		$conf['height'] = $flex['height']; // On ne veut pas de la valeur pas défaut, masonry étant responsive 
		
		$markersArray = $piObj->getMarkersBase();
		$confThemes = array(
			'limit' => $conf['limit'],
			'portraitwidth' => $conf['portraitwidth'],
			'portraitheight' => $conf['portraitheight'],
			'portraitcrop' => $conf['portraitcrop'],
			'landscapewidth' => $conf['landscapewidth'],
			'landscapeheight' => $conf['landscapeheight'],
			'landscapecrop' => $conf['landscapecrop'],
			'lightbox' => $conf['lightbox'] ? 1 : 0,
			'lightboxwidth' => $conf['lightboxwidth'],
			'lightboxheight' => $conf['lightboxheight'],
		);
		foreach ($confThemes as $key => $value) {
			$markersArray['###' . strtoupper($key) . '###'] = $value;
		}
		$inlinecObj = t3lib_div::makeInstance('tslib_cObj');
		$inlinecObj->start($conf, '');
		$markersArray['###INLINE_STYLE###'] = $inlinecObj->cObjGetSingle($conf['themes.']['masonry.']['inlineStyle'], $conf['themes.']['masonry.']['inlineStyle.']);
		$markersArray['###LINK_NEXTPAGE###'] = $inlinecObj->cObjGetSingle($conf['themes.']['masonry.']['nextPage'], $conf['themes.']['masonry.']['nextPage.']);
		
		$content = $piObj->getTemplate($conf['themes.']['masonry.']['template'], '###GALLERY###');
		$subpartElement = $this->cObj->getSubpart($content, '###FILES###');
		$outputElement = '';
		
		if ($confThemes['limit']) {
			$begin = $piObj->piVars['page'] ? ($piObj->piVars['page'] * $confThemes['limit']) : 0;
			$elements = array_slice($elements, $begin, $confThemes['limit'], true);
		}
		foreach ($elements as $element) {
			$elementConf = array_merge($element, $confThemes);
			$markers = $piObj->getMarkersBaseElement($elementConf, null, 'png,gif,jpg,jpeg');
			if ($markers) 
				$outputElement .= $this->cObj->substituteMarkerArray($subpartElement, $markers);
		}
		
		$subpartsArray = array(
			'###FILES###' => $outputElement,
		);
		return $this->cObj->substituteMarkerArrayCached($content, $markersArray, $subpartsArray);
	}
	
	/** 
	 *	BACKEND: possibility to add new style theme (only one) to plugin flexform configuration
	 *
	 *	@param	array	$flexConfig: flexform configuration
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	array	array : array ( 0 => label, 1 => value)
	 */
	public function flexform_addTheme($flexConfig, $row) {
		return array(
			0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery/themes/masonry/locallang_flexform.xml:theme.masonry'),
			1 => $this->getKey(),
		);
	}
	
	/** 
	 *	BACKEND: possibility to add news flexform configurations
	 *
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	string	xml flexform data
	 */
	public function flexform_addData($row) {
		$add = '';
		$theme = $row['data']['design']['lDEF']['theme']['vDEF'];
		if($theme == $this->getKey()) {
			$add .= file_get_contents(t3lib_div::getFileAbsFileName('EXT:pnf_gallery/themes/masonry/flexform.xml'));
		}
		return $add;
	}
	
 }
 
 if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/themes/masonry/class.tx_pnfgallery_masonry.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/themes/masonry/class.tx_pnfgallery_masonry.php']);
}

 ?>