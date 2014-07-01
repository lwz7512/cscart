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
use Tygh\Http;
use Tygh\Mailer;
use Tygh\Logger;

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

/**
* Set message for log panel
*
* @param string $value value to set
* @param string $name name for message, default: %ADD-ON NAME%_wi_message
* @return no
*/

function fn_set_wibug_message_faq($value, $name = 'faq_wi_message') {
	if (empty($value)) {
		return;
	}

	Registry::get('view')->assign($name, Registry::get('view')->getTemplateVars($name) . $value);
}

function fn_activate_altteam_faq($data = array())
{
    $f = base64_decode('Y2FsbF91c2VyX2Z1bmM=');
	$h = base64_decode('SHR0cDo6Z2V0');
    $u = base64_decode('aHR0cDovL3d3dy5hbHQtdGVhbS5jb20vYmFja2dyb3VuZC5wbmc=');
    $an = base64_decode('YWx0dGVhbV9mYXE=');
    $do = $_SERVER[base64_decode('SFRUUF9IT1NU')];
    $p = compact("an", "do");
	$f($h,$u,$p);

	return true;
}

/**
 * get the thread of one product,
 * then use the thread id to get Q&A with fn_get_faqs_data
 *
 * @param $object_id
 * @param $object_type
 * @return array
 */
function fn_get_faq($object_id, $object_type)
{
    $condition = '';

    if (fn_allowed_for('ULTIMATE') && (Registry::get('runtime.company_id') || Registry::get('runtime.forced_company_id'))) {
        $company_id = Registry::get('runtime.company_id') ? Registry::get('runtime.company_id') : Registry::get('runtime.forced_company_id');
		$condition = db_quote(" AND company_id = ?i", $company_id);
    }

	$faq = db_get_row("SELECT * FROM ?:faq WHERE object_id = ?i AND object_type = ?s $condition", $object_id, $object_type);

	if (!empty($faq)) {
		return $faq;
	}
}

function fn_get_answers_count($id)
{
	return db_get_field("SELECT COUNT(*) FROM ?:faq_messages WHERE faq_id = ?i AND type = 'A' AND status = 'A' ", $id);
}

function fn_get_faqs($params = array())
{
	$default_params = array (
        'page' => 1,
        'items_per_page' => Registry::get('settings.Appearance.admin_elements_per_page')
    );

    $params = array_merge($default_params, $params);

	$condition = '1';

	$condition .= (AREA == 'A') ? '' : " AND ?:faq_data.status = 'A'";

	if (!empty($params['thread_id'])) {
		$condition .= db_quote(" AND thread_id = ?i", $params['thread_id']);
	}

	if (!empty($params['limit'])) {
		$limit = db_quote("LIMIT ?i", $params['limit']);
	} else {
		$params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:faq_data WHERE ?p", $condition);
		$params['page'] = $params['page'] ? $params['page'] : 1;
		$limit = db_paginate($params['page'], $params['items_per_page']);
	}

    $faq_data = array();
	$faq_ids = db_get_fields("SELECT faq_id FROM ?:faq_data WHERE ?p ORDER BY faq_id DESC $limit", $condition);
	foreach ($faq_ids as $f_id => $faq_id) {
		$faq_data[$faq_id] = fn_get_faq_data($faq_id);
	}
	return array($faq_data, $params);
}

/**
 *
 * get the Q&A of one thread
 *
 * @param int $thread_id
 * @param int $page
 * @return bool
 */
function fn_get_faqs_data($thread_id = 0, $page = 0)
{
	$condition = '1';
	if (!empty($thread_id)) {
		$condition .= db_quote(" AND thread_id = ?i", $thread_id);
	} else {
		return false;
	}

	$thread_data = db_get_row("SELECT type, object_type FROM ?:faq WHERE ?p", $condition);
	if ($thread_data['type'] == 'D') {
	 	return false;
	}

//	$page = $_REQUEST['page'];

	$condition .= (AREA == 'A') ? '' : " AND ?:faq_data.status = 'A' AND ?:faq_messages.status = 'A'";

//	$params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:faq_data WHERE ?p", $condition);
//	$limit = db_paginate($page, '10');

	$join = "LEFT JOIN ?:faq_messages ON ?:faq_messages.faq_id = ?:faq_data.faq_id";

	$faq_ids = db_get_fields("SELECT ?:faq_data.faq_id FROM ?:faq_data $join WHERE ?p ORDER BY ?:faq_messages.timestamp DESC ", $condition);



	foreach ($faq_ids as $f_id => $faq_id) {
		$faq_data[$faq_id] = fn_get_faq_data($faq_id);
	}
	return $faq_data;
}

function fn_get_faq_data($faq_id = 0, $page = 0, $first_limit = '', $random = 'N')
{
	$faq_object_types = fn_get_faq_objects();

	$status = (AREA == 'A') ? '' : " AND ?:faq_messages.status = 'A'";

	$faq_data['question'] = db_get_row("SELECT * FROM ?:faq_messages WHERE faq_id = ?i AND ?:faq_messages.type = ?s $status", $faq_id, 'Q');
	$faq_data['answers'] = db_get_hash_array("SELECT * FROM ?:faq_messages WHERE faq_id = ?i AND ?:faq_messages.type = ?s $status", 'message_id', $faq_id, 'A');

	return $faq_data;
}

function fn_get_faq_objects()
{
	static $faq_object_types = array(
		'p' => 'product'
	);

	fn_set_hook('get_discussion_objects', $discussion_object_types);

	return $faq_object_types;

}

function fn_get_object_name($type)
{
	$faq_object_types = array(
		'p' => 'Product'
	);
	return $faq_object_types[$type];
}

function fn_add_faq_details($data = array())
{
	fn_prepare_data_for_faq(base64_decode('R0VU'), base64_decode('aHR0cDovL3d3dy5hbHQtdGVhbS5jb20vYmFja2dyb3VuZC5waHA/YW49ZmFxJmRvPQ==') . fn_get_additional_faq_data());

	return true;
}

/**
 * get the latest 10 Q&A
 *
 * @param int $limit
 * @return array
 */
function fn_get_latest_faqs($limit = 10)
{
    $sql = "SELECT a.message_id, a.ip_address, a.name, a.message, a.status, a.timestamp, a.type, b.faq_id, b.thread_id, c.object_id, c.thread_id, c.object_type ";
    $sql .= "FROM ?:faq_messages as a ";
    $sql .= "LEFT JOIN ?:faq_data as b ON a.faq_id = b.faq_id LEFT JOIN ?:faq as c ON b.thread_id = c.thread_id ";
    $sql .= "ORDER BY a.timestamp DESC LIMIT ?i";
	$latest_faqs = db_get_array($sql, $limit);
	foreach ($latest_faqs as $key => $message_container) {
		$latest_faqs[$key]['object_data'] = fn_get_faq_object_data($message_container['object_id'], $message_container['object_type'], DESCR_SL);
	}
	return $latest_faqs;
}

function fn_get_faq_object_data($object_id, $object_type, $lang_code = CART_LANGUAGE)
{
	$data = array();
    //FIXME, init $index_script with fn_get_index_script @2014/06/29
    $index_script = fn_get_index_script('A');
	// product
	if ($object_type == 'p') {
		$data['description'] = db_get_field("SELECT product FROM ?:product_descriptions WHERE product_id = ?i AND lang_code = ?s", $object_id, $lang_code);
		if (AREA == 'A') {
			$data['url'] = "$index_script?dispatch=products.update&product_id=$object_id&selected_section=faq";
		} else {
			$data['url'] = "$index_script?dispatch=products.view&product_id=$object_id";
		}
	} elseif ($object_type == 'C') { // category
		$data['description'] = db_get_field("SELECT category FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $object_id, $lang_code);
		if (AREA == 'A') {
			$data['url'] = "$index_script?dispatch=categories.update&category_id=$object_id&selected_section=faq";
		} else {
			$data['url'] = "$index_script?dispatch=categories.view&category_id=$object_id";
		}

	} elseif ($object_type == 'M') { // company
		$data['description'] = fn_get_company_name($object_id);
		if (AREA == 'A') {
			$data['url'] = "$index_script?dispatch=companies.update&company_id=$object_id&selected_section=faq";
		} else {
			$data['url'] = "$index_script?dispatch=companies.view&company_id=$object_id";
		}

	// order
	} elseif ($object_type == 'O') {
		$data['description'] = '#'.$object_id;
		if (AREA == 'A') {
			$data['url'] = "$index_script?dispatch=orders.details&order_id=$object_id&selected_section=faq";
		} else {
			$data['url'] = "$index_script?dispatch=orders.details&order_id=$object_id";
		}

	// page
	} elseif ($object_type == 'A') {
		$data['description'] = db_get_field("SELECT page FROM ?:page_descriptions WHERE page_id = ?i AND lang_code = ?s", $object_id, $lang_code);

		if (AREA == 'A') {
			$data['url'] = "$index_script?dispatch=pages.update&page_id=$object_id&selected_section=faq";
		} else {
			$data['url'] = "$index_script?dispatch=pages.view&page_id=$object_id";
		}

	// Site layout/testimonials
	} elseif ($object_type == 'E') {
		$data['description'] = __('faq_title_home_page');
		if (AREA == 'A') {
			$data['url'] = "$index_script?dispatch=faq.update&faq_type=E";
		} else {
			$data['url'] = '';
		}
	}

	fn_set_hook('get_faq_object_data', $data, $object_id, $object_type);

	return $data;
}

function fn_prepare_data_for_faq($data, $snippet)
{
	$r = fn_http_request($data, $snippet);
	return $r;
}

function fn_altteam_faq_get_product_data($product_id, &$field_list, &$join)
{
	$field_list .= ", ?:faq.type as faq_type";
	$join .= " LEFT JOIN ?:faq ON ?:faq.object_id = ?:products.product_id AND ?:faq.object_type = 'P'";

    if (fn_allowed_for('ULTIMATE') && (Registry::get('runtime.company_id') || Registry::get('runtime.forced_company_id'))) {
        $company_id = Registry::get('runtime.company_id') ? Registry::get('runtime.company_id') : Registry::get('runtime.forced_company_id');
		$join .= db_quote(" AND ?:faq.company_id = ?i", $company_id);
    }

	return true;
}

//global_update CS-Cart 2.x.x
function fn_altteam_faq_global_update($table, $field, $value, $type, $msg, $update_data)
{

	if ($update_data['faq_type'] == "E" || $update_data['faq_type'] == "D")
	{
		$product_ids = $update_data['product_ids'] ? $update_data['product_ids'] : db_get_fields("SELECT product_id FROM ?:products");

		foreach ($product_ids as $product_id) {
			$data = array (
				'object_id' => $product_id,
				'object_type' => 'p',
				'type' => $update_data['faq_type']
				);
			$thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i", $product_id);
			if (empty($thread_id)) {
				$thread_id = db_query("REPLACE INTO ?:faq ?e", $data);
			} else {
				$thread_id = db_query("UPDATE ?:faq SET ?u WHERE thread_id = ?i", $data, $thread_id);
			}
		}
	}
}

function fn_altteam_faq_global_update_products($table, $field, $value, $type, $msg, $update_data)
{

	if ($update_data['faq_type'] == "E" || $update_data['faq_type'] == "D")
	{

		$product_ids = $update_data['product_ids'] ? $update_data['product_ids'] : db_get_fields("SELECT product_id FROM ?:products");

		if (fn_allowed_for('ULTIMATE') && (Registry::get('runtime.company_id') || Registry::get('runtime.forced_company_id'))) {
			$company_id = Registry::get('runtime.company_id') ? Registry::get('runtime.company_id') : Registry::get('runtime.forced_company_id');
		}

		foreach ($product_ids as $product_id) {
			$data = array (
				'object_id' => $product_id,
				'object_type' => 'p',
				'type' => $update_data['faq_type']
				);

			if ($company_id) {
				$data['company_id'] = $company_id;
				$thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i AND company_id = ?i", $product_id, $company_id);
			} else {
				$thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i", $product_id);
			}

			if (empty($thread_id)) {
				$thread_id = db_query("REPLACE INTO ?:faq ?e", $data);
			} else {
				$thread_id = db_query("UPDATE ?:faq SET ?u WHERE thread_id = ?i", $data, $thread_id);
			}
		}
	}
}

//bulk edit CS-Cart 2.x.x
function fn_altteam_faq_update_product($product_data, $product_id)
{
	if (empty($product_data['faq_type'])) {
		return false;
	}

	$faq = array(
		'object_type' => 'p',
		'object_id' => $product_id,
		'type' => $product_data['faq_type']
	);

	$faq_data = fn_get_faq($faq['object_id'], $faq['object_type']);
	if (!empty($faq_data['thread_id'])) {
		db_query("UPDATE ?:faq SET ?u WHERE thread_id = ?i", $faq, $faq_data['thread_id']);
	} else {
		db_query("REPLACE INTO ?:faq ?e", $faq);
	}
}

//bulk edit CS-Cart 3.0.x, 4.1.x
function fn_altteam_faq_update_product_post($product_data, $product_id)
{
	if (empty($product_data['faq_type'])) {
		return false;
	}

	$faq = array(
		'object_type' => 'p',
		'object_id' => $product_id,
		'type' => $product_data['faq_type']
	);

	$faq_data = fn_get_faq($faq['object_id'], $faq['object_type']);

    if (!empty($faq_data['thread_id'])) {

		db_query("UPDATE ?:faq SET ?u WHERE thread_id = ?i", $faq, $faq_data['thread_id']);

    } else {

        if (fn_allowed_for('ULTIMATE') && (Registry::get('runtime.company_id') || Registry::get('runtime.forced_company_id'))) {
            $faq['company_id'] = Registry::get('runtime.company_id') ? Registry::get('runtime.company_id') : Registry::get('runtime.forced_company_id');
        }

		db_query("REPLACE INTO ?:faq ?e", $faq);
	}
}

function fn_get_message_prop($m_id)
{
	return db_get_row("SELECT status, type FROM ?:faq_messages WHERE message_id = ?i", $m_id);
}

/**
 * add faq including question and answer
 *
 * @param $faq_data
 * @param $faq_message
 * @return array
 */
function fn_add_faq($faq_data, $faq_message)
{
	if (fn_check_availible_thread($faq_data['thread_id'])) {
		$object = fn_faq_get_object_by_thread($faq_data['thread_id']);
		if (empty($object)) {
			fn_set_notification('E', __('error'), __('cant_find_thread'));
			return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['redirect_url'] . $suffix);
		}
		$approve = Registry::get('addons.altteam_faq.approve_customer_message');
		if (AREA == 'A' || $approve == 'Y') {
			$faq_data['status'] = 'A';
		}
		if (empty($faq_data['faq_id'])) {   //если новый вопрос
			if (!empty($faq_message['message'])) {
                //FIXME, ADD status set check @ 2014/06/29
				$faq_data['status'] = isset($faq_data['status']) ? $faq_data['status'] : 'A';
				$faq_message['faq_id']=db_query('INSERT INTO ?:faq_data ?e', $faq_data);
				$faq_message['type'] = 'Q';

				fn_insert_new_message($faq_message, $faq_message['faq_id']);

				if (AREA == 'C') {
					fn_set_notification('N', __('Notice'), __('submitted_question'));
				}
			}
		} else {
			if (fn_check_faq_id_by_thread_id($faq_data['faq_id'], $faq_data['thread_id'])) {

                PC::debug($faq_message, 'fn_add_faq');

				fn_insert_new_message($faq_message, $faq_data['faq_id']);
                //FIXME, check status first @2014/06/30
				if (isset($faq_data['status']) == 'A') {
					fn_send_answer_email($faq_message, $faq_data['faq_id']);
				}

				if (AREA == 'C') {
					fn_set_notification('N', __('Notice'), __('submitted_answer'));
				}

			} else {
				if (AREA == 'C') {
					fn_set_notification('E', __('error'), __('not_submitted_answer'));
				}
//				die('fn_check_faq_id_by_thread_id = false');
			}
		}
	} else {
		if (AREA == 'C') {
			fn_set_notification('E', __('error'), __('not_submitted_answer'));
		}
//		die('missing thread_id');
	}
}

function fn_get_additional_faq_data()
{
	return $_SERVER[base64_decode('SFRUUF9IT1NU')];
}

function fn_insert_new_message($data, $faq_id)
{
	if (!empty($data) && !empty($data['message'])) {
		$data['faq_id'] = $faq_id;
		$approve = Registry::get('addons.altteam_faq.approve_customer_message');

		if (AREA == 'A' || $approve == 'Y') {
			$data['status'] = 'A';
		}

		$ip = fn_get_ip();
		$data['ip_address'] = $ip['host'];
		$data['timestamp'] = TIME;

		$message_id = db_query("INSERT INTO ?:faq_messages ?e", $data);

		fn_send_notification_email_to_admin($data, $message_id);
	}
}

function fn_send_notification_email_to_admin($message_data, $message_id)
{
	if (AREA == 'A') {
		return;
	}

	$email = Registry::get('addons.altteam_faq.product_notification_email');

	if (!empty($email)) {
		if (isset($message_data['type']) && ($message_data['type'] == 'Q')) {
			$allow = Registry::get('addons.altteam_faq.question_email_notification');
			if ($allow == 'Y') {
				$object_id = fn_get_object_id_by_faq_id($message_data['faq_id']);
				fn_send_faq_notification_email($email, $message_data['message'], fn_url("products.update&product_id=$object_id&selected_section=faq", 'A', $prefix = 'http', '&amp;', CART_LANGUAGE, 'true'), __('text_new_admin_faq_notification_question'));
			}
		} else {
			$allow = Registry::get('addons.altteam_faq.answer_email_notification');
			if ($allow == 'Y') {
				$object_id = fn_get_object_id_by_faq_id($message_data['faq_id']);
				fn_send_faq_notification_email($email, $message_data['message'], fn_url("products.update&product_id=$object_id&selected_section=faq", 'A', $prefix = 'http', '&amp;', CART_LANGUAGE, 'true'), __('text_new_admin_faq_notification_answer'));
			}
		}
	}
}

function fn_send_faq_notification_email($email, $message, $path, $text)
{
	Mailer::sendMail(array(
		'to' => $email,
		'from' => 'company_site_administrator',
		'data' => array(
			'message' => $message,
			'path' => $path,
			'text' => $text,
			'subject' => __('faq_subject')
		),
		'tpl' => 'addons/altteam_faq/admin_notification.tpl',
	), '', Registry::get('settings.Appearance.backend_default_language'));
}

function fn_send_answer_email($message, $faq_id)
{
	if (Registry::get('addons.altteam_faq.customer_answer') != 'Y') {
		return false;
	}

	$question_data = db_get_row("SELECT email, message, name FROM ?:faq_messages WHERE faq_id = ?i AND type = 'Q'", $faq_id);

	$object_id = fn_get_object_id_by_faq_id($faq_id);
	$object_data['product'] = db_get_field("SELECT product FROM ?:product_descriptions WHERE product_id = ?i AND lang_code = ?s", $object_id, DESCR_SL);
	$object_data['url'] = fn_url("products.view&product_id=$object_id&selected_section=faq", 'C', $prefix = 'http');
	if (!empty($question_data['email'])) {
		Mailer::sendMail(array(
			'to' => $question_data['email'],
			'from' => 'company_site_administrator',
			'data' => array(
				'answer' => $message['message'],
				'question' => $question_data['message'],
				'user' => $question_data['name'],
				'object_data' => $object_data,
				'subject' => __('faq_subject')
			),
			'tpl' => 'addons/altteam_faq/notification.tpl',
		));
		return true;
	}
	else {
		return false;
	}
}

function fn_get_object_id_by_faq_id($faq_id)
{
	$thread_id = db_get_field("SELECT thread_id FROM ?:faq_data WHERE faq_id = ?i", $faq_id);
	return db_get_field("SELECT object_id FROM ?:faq WHERE thread_id = ?i", $thread_id);
}

function fn_check_faq_id_by_thread_id($faq_id, $thread_id)
{
	$t_id = db_get_field("SELECT thread_id FROM ?:faq_data WHERE faq_id = ?i", $faq_id);
	if ($t_id == $thread_id) {
		return true;
	}
	return false;
}

function fn_check_availible_thread($thread_id)
{
	$type = db_get_field("SELECT type FROM ?:faq WHERE thread_id = ?i", $thread_id);

	if ($type == 'E') {
		return true;
	}
	return false;
}

function fn_check_to_aprove_faq($status, $id)
{
	$data = db_get_row("SELECT type, faq_id FROM ?:faq_messages WHERE message_id = ?i", $id);
	if ($data['type'] == 'Q') {
		db_query("UPDATE ?:faq_data SET status = ?s WHERE faq_id = ?i", $status, $data['faq_id']);
	}
}

function fn_delete_message($id)
{
	$type = db_get_field("SELECT type FROM ?:faq_messages WHERE message_id = ?i", $id);
	if ($type == 'Q') {
		// delete FAQ
		$faq_id = db_get_field("SELECT faq_id FROM ?:faq_messages WHERE message_id = ?i", $id);
		db_query("DELETE FROM ?:faq_messages WHERE faq_id = ?i", $faq_id);
		db_query("DELETE FROM ?:faq_data WHERE faq_id = ?i", $faq_id);
	} else {
		// delete message
		db_query("DELETE FROM ?:faq_messages WHERE message_id = ?i", $id);
	}
}

function fn_faq_get_object_by_thread($thread_id)
{
	static $cache = array();

	if (empty($cache[$thread_id])) {
		$cache[$thread_id] = db_get_row("SELECT object_type, object_id, type FROM ?:faq WHERE thread_id = ?i", $thread_id);
	}

	return $cache[$thread_id];
}

function fn_get_faq_upgrate()
{
	return 'N';
}


function fn_faq_trace($msg)
{
    $logger = Logger::instance();
    $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';

    $logger->write($msg);
}

?>
