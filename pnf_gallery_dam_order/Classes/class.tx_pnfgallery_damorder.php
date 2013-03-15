<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Plan.Net France <typo3@plan-net.fr>
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
 * Extend 'pnf_gallery' DAM Source to add order.
 *
 * @author	Emilie Sagniez <emilie.sagniez@plan-net.fr>
 * @package	TYPO3
 * @subpackage	tx_pnfgallerydamorder
 */
 class tx_pnfgallery_damorder {
		
	/**
	 *	Modify records select query to add video file 
	 *	Use 'tx_pnfgallery_dam' source hook
	 *
	 */
	public function recordsQuery($queryArray, $addWhere, $directory, $uidList, $piObj) {
		$queryArray['SELECT'] .= ',
				`tx_dam`.`tx_pnfgallerydamorder_order`,
				IF(tx_pnfgallerydamorder_order = 0, 999999999, tx_pnfgallerydamorder_order) AS `tx_pnfgallerydamorder_order_tmp`
			';
		
		$queryArray['ORDER'] = '`tx_pnfgallerydamorder_order_tmp`';
		if ($piObj->conf['dam_orderby']) {
			switch ($piObj->conf['dam_orderby']) {
				case 'uid':
					$queryArray['ORDER'] .= ', `tx_dam`.`uid`';
					break;
				case 'crdate':
					$queryArray['ORDER'] .= ', `tx_dam`.`date_cr` DESC';
					break;
				case 'rand':
					$queryArray['ORDER'] .= ', rand()';
					break;
			}
		}
		return $queryArray;
	}
	
	
 }
 
 ?>