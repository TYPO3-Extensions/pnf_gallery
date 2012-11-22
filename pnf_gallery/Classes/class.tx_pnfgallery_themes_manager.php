<?
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


class tx_pnfgallery_themes_manager {
	
	private static $tempThemes = array();
	private static $themes;
	
	function subscribe($class) {
		self::$tempThemes[] = $class;
	}
	
	function getSubscribers() {
		if (!is_array(self::$themes)) self::initThemes();
		return self::$themes;
	}
	
	protected function initThemes() {
		if (is_array(self::$tempThemes)) {
			self::$themes = array();
			foreach (self::$tempThemes as $class) {
				if ($class && class_exists($class)) {
					$obj = t3lib_div::makeInstance($class);
					if (TYPO3_MODE == 'FE') $obj->cObj = t3lib_div::makeInstance('tslib_cObj');
					$key = $obj->getKey();
					self::$themes[$key] = $obj;
				}
			}
		}
	}
}

?>