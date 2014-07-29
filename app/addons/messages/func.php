<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-7-28
 * Time: 下午3:23
 */

use Tygh\Registry;

if ( !defined('AREA') ) { die('Access denied'); }


/**
 * only in homepage to check message unread
 *
 * @return bool
 */
function fn_messages_dispatch_before_display() {
    $user_id = $_SESSION['auth']['user_id'];

    if($user_id && Registry::get('runtime.controller') == 'index'){
        $message_account = fn_get_message_unread_count($user_id);
        if($message_account){
            fn_set_notification('N', __('notice'), "You have ".$message_account." unread messages!");
        }
    }

    return true;
}

/**
 * send message from user/admin to customer
 *
 * @param $sender_id
 * @param $receiver_id
 * @param $content
 */
function fn_send_message($sender_id, $receiver_id, $content) {

    $message_data = array(
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'content' => $content,
        'timestamp' => TIME,
        'status' => 0
    );
    db_query("INSERT INTO ?:messages ?e", $message_data);

}

/**
 * get messages of one customer
 *
 * @param $user_id
 * @return array
 */
function fn_get_message_list($user_id) {

    $sql = "SELECT m.msg_id, m.content, m.status, m.timestamp, u.firstname as sender_first_name, u.lastname as sender_last_name ";
    $sql .= "FROM ?:messages m ";
    $sql .= "LEFT JOIN ?:users u ON m.sender_id=u.user_id ";
    $sql .= "WHERE m.receiver_id = ?i ";
    $sql .= "ORDER BY m.timestamp DESC ";

    $message_data = db_get_array($sql, $user_id);

    return $message_data;
}

/**
 * get one message details
 *
 * @param $msg_id
 * @return array
 */
function fn_get_message($msg_id) {

    $message_data = db_get_row("SELECT * FROM ?:messages WHERE msg_id = ?i", $msg_id);

    fn_update_message_status($msg_id);

    return $message_data;
}

/**
 * update message status to read, default is unread
 *
 * @param $msg_id
 */
function fn_update_message_status($msg_id) {

    db_query("UPDATE ?:messages SET status = 1 WHERE msg_id = ?i", $msg_id);

}

/**
 * get unread message amount
 *
 * @param $user_id
 * @return array
 */
function fn_get_message_unread_count($user_id) {

    $amount = db_get_field("SELECT count(*) FROM ?:messages WHERE receiver_id = ?i AND status = 0", $user_id);

    return $amount;
}