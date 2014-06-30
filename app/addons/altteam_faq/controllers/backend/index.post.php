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

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	return;
}
	
if ($mode == 'update_faq_status') {

	$new_status = ($_REQUEST['status'] === 'A') ? 'A' : 'D';
	$id = $_REQUEST['id'];
	$result =  db_query("UPDATE ?:faq_messages SET status = ?s WHERE message_id = ?i", $new_status, $_REQUEST['id']);

	if ($result) {
		fn_set_notification('N', __('notice'), __('status_changed'));
		fn_check_to_aprove_faq($new_status, $id);
	} else {
		fn_set_notification('E', __('error'), __('error_status_not_changed'));
	}

	return array(CONTROLLER_STATUS_OK, "$index_script");
}

if ($mode == 'delete_message' && defined('AJAX_REQUEST')) {
	fn_delete_message($_REQUEST['message_id']);
	return array(CONTROLLER_STATUS_OK, "$index_script");
}
?>
