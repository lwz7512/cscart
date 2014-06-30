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
//	if (AREA == 'A') {
//		fn_trusted_vars('faq_data');
//	}
	
	if ($mode == 'add_faq') {
		if (AREA == 'C') {
            if (fn_image_verification('use_for_discussion', $_REQUEST) == false) {
                fn_save_post_data('faq_message');

                return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['redirect_url'] . $suffix);
            }

			if (Registry::get('addons.altteam_faq.links_filter') == 'Y' && (empty($_REQUEST['faq_message']['message']) || preg_match("/(http:\/\/|www)/", $_REQUEST['faq_message']['message']))) {
				fn_set_notification('E', __('error'), __('don_t_post_link'));
				return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['redirect_url']);
			}
        }

		$suffix = 'faq';
		$faq_data = $_REQUEST['faq_data'];
		$faq_message = $_REQUEST['faq_message'];
		$faq_message['user_id'] = $auth['user_id'];
		if (empty($faq_data['thread_id'])) {
            if(isset($_REQUEST['selected_ids'])){//faq_manager.manage/add faq
                $object_ids = explode(',', $_REQUEST['selected_ids']);
                foreach ($object_ids as $object_id) {
                    $thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i AND object_type = ?s", $object_id, 'p');
                    if (!empty($thread_id)) {
                        $faq_data['thread_id'] = $thread_id;
                        fn_add_faq($faq_data, $faq_message);
                    } else {
                        $faq = array();
                        $faq['type'] = "E";
                        $faq['object_type'] = "p";
                        $faq['object_id'] = $object_id;
                        $faq_data['thread_id'] = db_query('INSERT INTO ?:faq ?e', $faq);
                        fn_add_faq($faq_data, $faq_message);
                    }
                }
            }else{//FIXME, frontend product details/new post from blank
                $object_id = $_REQUEST['object_id'];
                $faq = array();
                $faq['type'] = "E";
                $faq['object_type'] = "p";
                $faq['object_id'] = $object_id;
                $faq_data['thread_id'] = db_query('INSERT INTO ?:faq ?e', $faq);
                fn_add_faq($faq_data, $faq_message);
            }
		} else {//frontend product details / new post after an already faq
//            PC::debug($faq_data, 'new_faq');
			fn_add_faq($faq_data, $faq_message);
		}
	}
	
	if ($mode == 'update_faqs') {

		if (AREA == 'A' && !empty($_REQUEST['faq_data']) && is_array($_REQUEST['faq_data'])) {

			$messages_exist = db_get_fields("SELECT message_id FROM ?:faq_messages WHERE message_id IN (?n)", array_keys($_REQUEST['faq_data']['message']));

			foreach ($_REQUEST['faq_data']['message'] as $p_id => $data) {
				if (empty($data['message'])) {
					fn_delete_message($p_id);
				} elseif (in_array($p_id, $messages_exist)) {
					$prop = fn_get_message_prop($p_id);
					db_query("UPDATE ?:faq_messages SET ?u WHERE message_id = ?i", $data, $p_id);
                    //FIXME, fix status undefined bug in previous php @2014/06/30
					$data['status'] = isset($data['status']) ? $data['status'] : 'A';
					fn_check_to_aprove_faq($data['status'], $p_id);
				}
			}
			foreach ($_REQUEST['faq_data']['add_message'] as $f_id => $data) {
                //FIXME, fix status undefined bug in previous php @2014/06/30
				$data['status'] = isset($data['status']) ? $data['status'] : 'A';
				$data['user_id'] = $auth['user_id'];
				fn_insert_new_message($data, $f_id);
				if (!empty($data) && !empty($data['message'])) {
					fn_send_answer_email($data, $f_id);
				}
			}
		}
	}
	
	if ($mode == 'delete_faqs') {
		if (AREA == 'A' && !empty($_REQUEST['delete_faqs']) && is_array($_REQUEST['delete_faqs'])) {
			foreach ($_REQUEST['delete_faqs'] as $p_id => $v) {
				fn_delete_message($p_id);
			}
		}
	}
	
	return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['redirect_url']);
}

if ($mode == 'upgrade_db') {
	fn_print_r('Updrade database mode');
	db_query("ALTER TABLE ?:faq_data RENAME ?:faq_data_old");
	fn_print_r('table renamed');

	$faq_data = db_get_array("SELECT faq_id, thread_id, status FROM ?:faq_data_old");
	
	db_query("CREATE TABLE ?:faq_data (
	  `faq_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	  `thread_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	  `status` char(1) NOT NULL DEFAULT 'D',
	  PRIMARY KEY (`faq_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");
	foreach ($faq_data as $data) {
	db_query('INSERT INTO ?:faq_data ?e', $data);
	}

	fn_print_r('table faq_data created and updated');


	$faq_messages = db_get_array("SELECT * FROM ?:faq_data_old");

	db_query("CREATE TABLE ?:faq_messages (
	`message_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`faq_id` mediumint(8) unsigned NOT NULL,
	`message` mediumtext NOT NULL,
	`name` varchar(128) NOT NULL DEFAULT '',
	`timestamp` int(11) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`email` varchar(128) NOT NULL DEFAULT '',
	`ip_address` varchar(15) NOT NULL DEFAULT '',
	`type` char(1) NOT NULL DEFAULT 'A',
	`status` char(1) NOT NULL DEFAULT 'D',
	PRIMARY KEY (`message_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;");

	foreach ($faq_messages as $message) {
		$question['faq_id'] = $message['faq_id'];
		$question['message'] = $message['question'];
		$question['name'] = $message['question_name'];
		$question['timestamp'] = $message['question_timestamp'];
		$question['user_id'] = $message['question_user_id'];
		$question['ip_address'] = $message['question_ip_address'];
		$question['type'] = 'Q';
		$question['status'] = $message['status'];

		$answer['faq_id'] = $message['faq_id'];
		$answer['message'] = $message['answer'];
		$answer['name'] = $message['answer_name'];
		$answer['timestamp'] = $message['answer_timestamp'];
		$answer['user_id'] = $message['answer_user_id'];
		$answer['ip_address'] = $message['answer_ip_address'];
		$answer['type'] = 'A';
		$answer['status'] = $message['status'];

		db_query('INSERT INTO ?:faq_messages ?e', $question);
		db_query('INSERT INTO ?:faq_messages ?e', $answer);
	}

	fn_print_r('table faq_messages created and updated');

	fn_print_r('job is done');
} elseif ($mode == 'delete') {
	if (AREA == 'A' && !empty($_REQUEST['message_id'])) {
		fn_delete_message($_REQUEST['message_id']);
	}
}
?>
