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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Gallery' for the 'pnf_gallery' extension.
 *
 * @author	Emilie Sagniez <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgallery
 */
class tx_pnfgallery_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_pnfgallery_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_pnfgallery_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'pnf_gallery';	// The extension key.
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj = 1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
		
		$content='';
		$errors = $this->init();
		if (!empty($errors)) {
			foreach ($errors as $error) {
				$content .= $this->printError($error);
			}
		} else {
			$elements = $this->getElements();
			if (empty($elements)) 
				$content .= $this->printError($this->pi_getLL('gallery_empty'));
			else
				$content .= $this->renderGallery($elements);
		}
		return $this->pi_wrapInBaseClass($content);
	}
	
	/**
	 * Render error message
	 *
	 * @param	string	$error
	 * @return	html
	 */
	function printError($error) {
		return '<p class="error">' . $error . '</p>';
	}
	
	/**
	 * Init configuration
	 *
	 * @return	void
	 */
	function init() {
		$errors = array();
		// Flexform
		$this->pi_initPIflexForm();
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$this->extractFlexformConfiguration($piFlexForm, $this->conf);
			
		if (!$this->conf['source']) {
			$errors[] = $this->pi_getLL('configuration_error_source');
		}
		if (!$this->conf['mode']) {
			$errors[] = $this->pi_getLL('configuration_error_mode');
		}
		if (!$this->conf['theme']) {
			$errors[] = $this->pi_getLL('configuration_error_theme');
		}
		
		$this->conf['width'] = $this->conf['width'] ? intval($this->conf['width']) : 400;
		$this->conf['height'] = $this->conf['height'] ? intval($this->conf['height']) : 300;
		return $errors;
	}
	
	/**
	 * Get gallery elements
	 *
	 * @return	array
	 */
	function getElements() {
		$elements = array();
		$sources = tx_pnfgallery_sources_manager::getSubscribers();
		
		$obj = $sources[$this->conf['source']];
		if ($obj) {
			$sourceElements = $obj->getElements($this->conf);
			if ($sourceElements && is_array($sourceElements)) {
				$elements = array_merge($elements, $sourceElements);
			}
		}
		return $elements;
	}
	
	/**
	 * Render gallery
	 *
	 * @param array $elements: gallery elements
	 * @return html
	 */
	function renderGallery($elements) {
		$content = '';
		$themes = tx_pnfgallery_themes_manager::getSubscribers();
		$obj = $themes[$this->conf['theme']];
		if ($obj) {
			$outputTheme = $obj->renderGallery($elements, $this->conf, $this);
			if ($outputTheme)
				$content .= $outputTheme;
		}
		return $content;
	}
	
	/**
	 * Render gallery file
	 *
	 * @param	array	$fileArray: data element
	 * @param	mixed	$onlyExtensions: list of file extension authorized
	 * @return	html
	 */
	function renderFile($fileArray, $onlyExtensions = null) {
		$outputFile = '';
		$informations = t3lib_div::split_fileref($fileArray['file']);
		
		if ($onlyExtensions && !is_array($onlyExtensions))
			$onlyExtensions = t3lib_div::trimExplode(',', $onlyExtensions, true);
			
		if (is_array($onlyExtensions) && empty($onlyExtensions))
			unset($onlyExtensions);
		
		if (!$onlyExtensions || ($onlyExtensions && in_array($informations['fileext'], $onlyExtensions))) {
			switch ($informations['fileext']) {
				case 'png':
				case 'gif':
				case 'jpeg':
				case 'jpg':
					$fileArray['width'] = $this->conf['width'];
					$fileArray['height'] = $this->conf['height'];
					$cObj = t3lib_div::makeInstance('tslib_cObj');
					$cObj->start($fileArray, 'tx_dam');
					$outputFile .= $cObj->cObjGetSingle($this->conf['renderFile.']['image'], $this->conf['renderFile.']['image.']);
					break;
				default:
					// Hook 
					if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['renderNewExtensionFile'])) {
						$renderFile = '';
						foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['renderNewExtensionFile'] as $_classRef) {
							$_procObj = & t3lib_div::getUserObj($_classRef);
							$html = $_procObj->renderNewExtensionFile($fileArray, $informations, $this);
							if ($html)
								$renderFile = $html;
						}
						$outputFile .= $renderFile;
					}
					break;
			}
		}
		return $outputFile;
	}
	
	/**
	 *	Get element markers not dependent on source, mode and theme
	 *
	 * @param	array	$elementArray: data element
	 * @param	array	$addFields: add new element fields
	 * @param	mixed	$onlyExtensions: list of file extension authorized
	 * @return array
	 */
	function getMarkersBaseElement($elementArray, $addFields = array(), $onlyExtensions = null) {	
		$markers = false;	
		$file = $this->renderFile($elementArray, $onlyExtensions);
		if ($file) {
			$markers = array('###FILE###' => $file);
			$fields = array('description', 'alt', 'title', 'tstamp', 'crdate');
			if (is_array($addFields))
				$fields = array_merge($fields, $addFields);
								
			foreach ($fields as $field) {
				$markers['###' . strtoupper($field) . '###'] = '';
				if ($elementArray[$field]) {
					if (is_array($this->conf['renderFile.'][$field . '.'])) {
						$markers['###' . strtoupper($field) . '###'] = $this->cObj->stdWrap($elementArray[$field], $this->conf['renderFile.'][$field . '.']);
					} else
						$markers['###' . strtoupper($field) . '###'] = $elementArray[$field];
				}
			}
			
			// Hook 
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addMarkersBaseElement'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addMarkersBaseElement'] as $_classRef) {
					$_procObj = & t3lib_div::getUserObj($_classRef);
					$markers = $_procObj->addMarkersBaseElement($markers, $elementArray, $this);
				}
			}
		}
		return $markers;
	}
	
	/**
	 *	Get markers not dependent on source, mode and theme
	 *
	 * @return array
	 */
	function getMarkersBase() {
		$markers = array(
			'###CEID###' => $this->cObj->data['uid'],
			'###WIDTH###' => $this->conf['width'],
			'###HEIGHT###' => $this->conf['height'],
		);
		// Hook 
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addMarkersBase'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addMarkersBase'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$markers = $_procObj->addMarkersBase($markers, $elementArray, $this);
			}
		}
		return $markers;
	}
	
	/**
	 *	Get records tt_content configuration
	 *
	 *	@param	int $cEid: tt_content uid
	 *	@return array
	 */
	function getContentConfiguration($cEid) {
		// Typoscript
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_pnfgallery_pi1.'];
		// Flexform
		$records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'`tt_content`.`pi_flexform`', 
			'`tt_content`', 
			'`tt_content`.`uid` = ' . $cEid . ' ' . $this->cObj->enableFields('tt_content'), 
			'', '', 1);
		if (is_array($records) && !empty($records)) {
			$this->extractFlexformConfiguration($records[0]['pi_flexform'], $conf);
		}
		return $conf;
	}
	
	/**
	 *	Extract configuration to flexform
	 *
	 *	@param	mixed $piFlexForm: flexform configuration
	 *	@param	array $conf: configuration array
	 *	@return void
	 */
	function extractFlexformConfiguration($piFlexForm, &$conf) {
		// $this->cObj->readFlexformIntoConf($piFlexForm, $conf);
		if (!is_array($piFlexForm))
			$piFlexForm = t3lib_div::xml2array($piFlexForm, 'T3');
		if (is_array($piFlexForm['data'])) {
			foreach ($piFlexForm['data'] as $sheet => $flex) {
				if (is_array($flex)) {
					foreach ($flex as $lang => $value) {
						if (is_array($value)) {
							foreach ($value as $key => $val) {
								if(!is_null($this->pi_getFFvalue($piFlexForm, $key, $sheet)) && $this->pi_getFFvalue($piFlexForm, $key, $sheet) != '') {
									$conf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
								}
							}
						}
					}
				}
			}
		}	
	}
	
	/**
	 * Get html template
	 *
	 * @params	string	$template: file template
	 * @params	string	$name: subpart name
	 * @return html
	 */
	public function getTemplate($template, $name) {
		$template = $this->cObj->fileResource($template);
		$subpart = $this->cObj->getSubpart($template, $name);
		return $subpart;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/pi1/class.tx_pnfgallery_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pnf_gallery/pi1/class.tx_pnfgallery_pi1.php']);
}

?>