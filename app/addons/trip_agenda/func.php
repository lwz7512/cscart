<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-22
 * Time: 下午10:40
 */

function fn_update_trip_agenda($auth, $product_id, $from_time, $to_time, $agenda_id = 0){


    if (!$agenda_id) {//add new
        $agenda = array(
            'product_id' => intval($product_id),
            'from_time' => strtotime($from_time),
            'to_time' => strtotime($to_time),
            'timestamp' => TIME,
            'author' => $auth['user_id']
        );

        db_query("INSERT INTO ?:trip_agenda ?e", $agenda);

    }

    if ($agenda_id) {//update

    }

}