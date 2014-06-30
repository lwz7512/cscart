<?php
/*****************************************************************************
 * This is a commercial software, only users who have purchased a  valid
 * license and accepts the terms of the License Agreement can install and use  
 * this program.
 *----------------------------------------------------------------------------
 * @copyright  LCC Alt-team: http://www.alt-team.com
 * @module     "Alt-team: FAQ"
 * @version    faq_4_1.1.2
 * @license    http://www.alt-team.com/addons-license-agreement.html
 ****************************************************************************/

use Tygh\Registry;

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

if ($mode == 'view') {
	$faq = fn_get_faq($_REQUEST['product_id'], 'P');
	if (!empty($faq)) {
		if ($faq['type'] != 'D') {
			Registry::get('view')->assign('faq_d', $faq);
		} 
	}

}

?>
