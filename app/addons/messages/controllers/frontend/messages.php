<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-7-28
 * Time: 下午3:22
 */

use Tygh\Registry;

if ($mode == 'view') {//display single message

    $msg_id = $_REQUEST['msg_id'];

    $message = fn_get_message($msg_id);

    Registry::get('view')->assign('message', $message);

    fn_add_breadcrumb('Message Box', 'messages.list');

    fn_add_breadcrumb('Message Details');

} elseif ($mode == 'list') {//display all the messages

    $user_id = $auth['user_id'];
    if($user_id){
        $messages = fn_get_message_list($user_id);
        Registry::get('view')->assign('messages', $messages);
    }

    fn_add_breadcrumb('Message Box');

} elseif ($mode == 'count') {//ajax call

    if (defined('AJAX_REQUEST')) {
        $user_id = $auth['user_id'];
        $count_unread = fn_get_message_unread_count($user_id);
        Registry::get('ajax')->assign('count_unread', $count_unread);

        exit();
    }

}