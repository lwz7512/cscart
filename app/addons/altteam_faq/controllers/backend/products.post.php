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
	if ($mode == 'update') {
		if (fn_allowed_for('ULTIMATE')) {
            if (Registry::get('runtime.company_id')) {
                $faq_data['company_id'] = Registry::get('runtime.company_id');
            } else {
                return;
            }
		}

		$faq_data['type']=$_REQUEST['product_data']['faq_type'];
		$faq_data['object_type']="p";
		$faq_data['object_id']=$_REQUEST['product_id'];

		if ($faq_data['company_id']) {
			$thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i AND company_id = ?i", $faq_data['object_id'], $faq_data['company_id']);
		} else {
			$thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i", $faq_data['object_id']);
		}

		if (empty($thread_id)) {
			$thread_id = db_query("REPLACE INTO ?:faq ?e", $faq_data);
		} else {
			$thread_id = db_query("UPDATE ?:faq SET ?u WHERE thread_id = ?i", $faq_data, $thread_id);
		}

        if (!empty($_REQUEST['faq_data']) && is_array($_REQUEST['faq_data']['message'])) {

			$messages_exist = db_get_fields("SELECT message_id FROM ?:faq_messages WHERE message_id IN (?n)", array_keys($_REQUEST['faq_data']['message']));

			foreach ($_REQUEST['faq_data']['message'] as $p_id => $data) {
				if (empty($data['message'])) {
					fn_delete_message($p_id);
				} elseif (in_array($p_id, $messages_exist)) {
					$prop = fn_get_message_prop($p_id);
					db_query("UPDATE ?:faq_messages SET ?u WHERE message_id = ?i", $data, $p_id);
					if ($prop['status'] == 'D' && $data['status'] == 'A' && $prop['type'] == 'A') {
						$faq_id = db_get_field("SELECT faq_id FROM ?:faq_messages WHERE message_id = ?i", $p_id);
						fn_send_answer_email($data, $faq_id);
					}
					$data['status'] = $data['status'] ? $data['status'] : 'A';
					fn_check_to_aprove_faq($data['status'], $p_id);
				}
			}
			foreach ($_REQUEST['faq_data']['add_message'] as $f_id => $data) {
				$data['user_id'] = $auth['user_id'];
				fn_insert_new_message($data, $f_id);
				if (!empty($data) && !empty($data['message'])) {
					fn_send_answer_email($data, $f_id);
				}
			}
		}
	}

	return;
}

if ($mode == 'update') {
	$faq = fn_get_faq($_REQUEST['product_id'], 'P');
	if (!empty($faq) && $faq['type'] != 'D') {
		if (fn_allowed_for('MULTIVENDOR') || fn_allowed_for('ULTIMATE') && Registry::get('runtime.company_id')) {
			Registry::set('navigation.tabs.faq', array (
				'title' => __('faq_title_product'),
				'js' => true
			));

			Registry::get('view')->assign('faq', $faq);
		}
	}

} elseif  ($mode == 'manage') {
	if (fn_allowed_for('MULTIVENDOR') || fn_allowed_for('ULTIMATE') && (Registry::get('runtime.company_id') || Registry::get('runtime.forced_company_id'))) {
		$selected_fields = Registry::get('view')->getTemplateVars('selected_fields');

		$selected_fields[] = array(
			'name' => '[data][faq]',
			'text' => __('faq')
		);

		Registry::get('view')->assign('selected_fields', $selected_fields);
	}

} elseif ($mode == 'm_update') {
	if (fn_allowed_for('MULTIVENDOR') || fn_allowed_for('ULTIMATE') && (Registry::get('runtime.company_id') || Registry::get('runtime.forced_company_id'))) {
		$selected_fields = $_SESSION['selected_fields'];

		$field_groups = Registry::get('view')->getTemplateVars('field_groups');
		$filled_groups = Registry::get('view')->getTemplateVars('filled_groups');
		$field_names = Registry::get('view')->getTemplateVars('field_names');
		if (isset($field_names['faq'])) {
			unset($field_names['faq']);
		}

		$field_groups['S']['faq_type'] = array(
			'name' => 'products_data',
				'variants' => array (
					'D' => __('disabled'),
					'E' => __('enabled'),
				)
		);

		$filled_groups['S']['faq_type'] = __('faq_title_product');

		Registry::get('view')->assign('field_groups', $field_groups);
		Registry::get('view')->assign('filled_groups', $filled_groups);
		Registry::get('view')->assign('field_names', $field_names);
	}

} elseif ($mode == 'enable_faq_for_all_products') {
	$product_ids = db_get_fields("SELECT product_id FROM ?:products");
	foreach ($product_ids as $product_id) {
		$product_data = array(
			'faq_type' => 'E'
		);
		fn_altteam_faq_update_product($product_data, $product_id);
		fn_echo("Faq has been enabled for #$product_id. <br />");
	}
	fn_echo("Faq has been enabled for all products.");
	exit;
}



?>
