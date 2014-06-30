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

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	return;
}

if ($mode == 'manage') {
	list($faq_data, $search) = fn_get_faqs($_REQUEST);
	$object_type = 'p';

	if (!empty($faq_data)) {
		foreach ($faq_data as $k => $v) {
			$thread_id = db_get_field("SELECT thread_id FROM ?:faq_data WHERE faq_id = ?i", $k);
			$object_id = db_get_field("SELECT object_id FROM ?:faq WHERE thread_id = ?i", $thread_id);
	 		$faq_data[$k]['object_data'] = fn_get_faq_object_data($object_id, $object_type, DESCR_SL);
	 	}
	}
	Registry::get('view')->assign('faq_data', $faq_data);
	Registry::get('view')->assign('search', $search);
}

?>
