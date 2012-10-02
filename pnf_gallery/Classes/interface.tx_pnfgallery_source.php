<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 In Cite Solution <technique@in-cite.net>
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
 * Interface of data sources for the 'pnf_gallery' extension.
 *
 * @author	Emilie SAGNIEZ <emilie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_pnfgallery
 */
interface tx_pnfgallery_source {
	
	/**
	 *	Get unik key
	 *
	 *	@return string
	 */	 
	function getKey();
	
	/**
	 *	Get source galery elements
	 *
	 *	@param	array	$conf: plugin configuration
	 *	@return array: array ( 0 => array('file' => '', 'description' => '', 'alt' => '', 'title' => '', 'tstamp' => '', 'crdate' => '', 'personnalData' => ..., 'personnalData2' => ....))
	 */	
	function getElements($conf);
	
	/** 
	 *	BACKEND: possibility to add new source records (only one) to plugin flexform configuration
	 *
	 *	@param	array	$flexConfig: flexform configuration
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	array	array : array ( 0 => label, 1 => value)
	 */
	function flexform_addSource($flexConfig, $row);
	
	/** 
	 *	BACKEND: possibility to add news modes view (one or more) to plugin flexform configuration
	 *
	 *	@param	array	$flexConfig: flexform configuration
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	array	array of news items : array ( 0 => array(0 => label, 1 => value), 1 => array(0 => label, 1 => value))
	 */
	function flexform_addModes($flexConfig, $row);
	
	/** 
	 *	BACKEND: possibility to add news flexform configurations
	 *
	 *	@param	array	$row: current plugin flexform configuration
	 *	@return	string	xml flexform data
	 */
	function flexform_addData($row);
	
}

?>