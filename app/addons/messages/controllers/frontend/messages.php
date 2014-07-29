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

} elseif ($mode == 'list') {//display all the messages

    $user_id = $auth['user_id'];
    if($user_id){
        $messages = fn_get_message_list($user_id);
        Registry::get('view')->assign('messages', $messages);
    }

}