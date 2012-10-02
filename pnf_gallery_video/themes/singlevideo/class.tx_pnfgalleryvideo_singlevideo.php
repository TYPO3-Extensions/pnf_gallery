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
 * Singlevideo theme for the 'pnf_gallery' extension.
 *
 * @author	Emilie Sagniez <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgallery
 */
 class tx_pnfgalleryvideo_singlevideo implements tx_pnfgallery_theme {
 
	var $key = 'SINGLEVIDEO';
	var $prefixId = 'tx_pnfgalleryvideo_singlevideo';
	var $xajax;
	
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
		$content = $piObj->getTemplate($conf['themes.']['singlevideo.']['template'], '###GALLERY###');
		// xajax
		$subpartXajax = $this->cObj->getSubpart($content, '###XAJAX###');
		if ($conf['xajaxCall'])
			$content = $subpartXajax;
		else 
			$content = $this->cObj->substituteSubpart($content, '###XAJAX###', $subpartXajax);
			
		$subpartBrowser = $this->cObj->getSubpart($content, '###BROWSER###');
		$subpartElement = $this->cObj->getSubpart($content, '###FILES###');
		$markersArray = $piObj->getMarkersBase();
		$subpartsArray = array(
			'###FILES###' => '',
			'###BROWSER###' => '',
		);
		
		// Browser
		$indexCurrent = $piObj->piVars['index'] ? $piObj->piVars['index'] : 0;
		$indexCurrent = ($conf['xajaxCall'] && $conf['xajaxIndex']) ? $conf['xajaxIndex'] : $indexCurrent;
		$indexMax = count($elements);
		if ($indexMax > 1) {
			$prevIndex = $indexCurrent ? ($indexCurrent - 1) : ($indexMax - 1);
			$nextIndex = (($indexCurrent + 1) == $indexMax) ? 0 : ($indexCurrent + 1);
			$markersBrowser = array(
				'###PREV###' => $GLOBALS['TSFE']->sL('LLL:EXT:pnf_gallery_video/themes/singlevideo/locallang.xml:previous'),
				'###NEXT###' => $GLOBALS['TSFE']->sL('LLL:EXT:pnf_gallery_video/themes/singlevideo/locallang.xml:next'),
				'###PREV_LINK###' => $piObj->pi_linkTP_keepPIvars_url(array('index' => $prevIndex)),
				'###NEXT_LINK###' => $piObj->pi_linkTP_keepPIvars_url(array('index' => $nextIndex)),
				'###PREV_INDEX###' => $prevIndex,
				'###NEXT_INDEX###' => $nextIndex,
			);
			$markersBrowser = array_merge($markersArray, $markersBrowser);
			
			$subpartsBrowser = array(
				'###XAJAX_PREV###' => '',
				'###XAJAX_NEXT###' => '',
			);
			// ajax browser
			if ($this->initAjax($piObj)) {
				$subpartXajaxPrev = $this->cObj->getSubpart($subpartBrowser, '###XAJAX_PREV###');
				$subpartXajaxNext = $this->cObj->getSubpart($subpartBrowser, '###XAJAX_NEXT###');
				$subpartsBrowser['###XAJAX_PREV###'] = $this->cObj->substituteMarkerArray($subpartXajaxPrev, $markersBrowser);
				$subpartsBrowser['###XAJAX_NEXT###'] = $this->cObj->substituteMarkerArray($subpartXajaxNext, $markersBrowser);
			}
			$subpartsArray['###BROWSER###'] = $this->cObj->substituteMarkerArrayCached($subpartBrowser, $markersBrowser, $subpartsBrowser);
		}
		
		// Current element
		$element = $elements[$indexCurrent];
		if ($element) {
			$markersElement = $piObj->getMarkersBaseElement(
				$element, 
				array('tx_pnfgalleryvideo_time', 'tx_pnfgalleryvideo_accessibility_link','tx_pnfgalleryvideo_thumbnail'), 
				'png,gif,jpg,jpeg,flv'
			);
			if ($markersElement) {
				$subpartsArray['###FILES###'] .= $this->cObj->substituteMarkerArray($subpartElement, $markersElement);
			}
		}
		return $this->cObj->substituteMarkerArrayCached($content, $markersArray, $subpartsArray);
	}
	
	private static $xajaxInitialized = false;
	
	/**
	 *	Init ajax function if xajax extension is loaded
	 *
	 *	@return void
	 */
	function initAjax($piObj){
		if (t3lib_extMgm::isLoaded('xajax') && !self::$xajaxInitialized) {
			self::$xajaxInitialized = true;
			$this->piObj = $piObj;
			require_once (t3lib_extMgm::extPath('xajax') . 'class.tx_xajax.php');
			$this->xajax = t3lib_div::makeInstance('tx_xajax');
			$url = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
			$this->xajax->setRequestURI($url);
			$this->xajax->decodeUTF8InputOn();
			$this->xajax->setCharEncoding('utf-8');
			$this->xajax->setWrapperPrefix($this->prefixId);
			$this->xajax->statusMessagesOn();
			$this->xajax->debugOff();
			$this->xajax->registerFunction(array('processChangeElement', &$this, 'processChangeElement'));
			$this->xajax->processRequests();
			$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId.'_ajax'] = $this->xajax->getJavascript(t3lib_extMgm::siteRelPath('xajax'));
		} 
		return self::$xajaxInitialized;
	}
	
	/**
	 *	Ajax function if xajax extension is loaded
	 *
	 */
	function processChangeElement($cEid, $index, $outerDiv) {
		// $conf = $this->piObj->getContentConfiguration($cEid);
		$conf = $this->piObj->conf;
		$conf['xajaxCall'] = '1';
		$conf['xajaxIndex'] = $index;
		
		$pi1 = t3lib_div::makeInstance('tx_pnfgallery_pi1');
		$pi1->cObj = $this->piObj->cObj;
		$content = $pi1->main('', $conf);
		
		$objResponse = new tx_xajax_response();		
		if($content != '') {
			$objResponse->addAssign($outerDiv, 'innerHTML', $content);
			$objResponse->addScriptCall('loadVideoAjax', $outerDiv);
		}
				
		return $objResponse->getXML();
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
			0 => $GLOBALS['LANG']->sL('LLL:EXT:pnf_gallery_video/themes/singlevideo/locallang_flexform.xml:theme.singlevideo'),
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
		return '';
	}
	
 }
 
 if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery_video/themes/singlevideo/class.tx_pnfgalleryvideo_singlevideo.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery_video/themes/singlevideo/class.tx_pnfgalleryvideo_singlevideo.php']);
}

 ?>