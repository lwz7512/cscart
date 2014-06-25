<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-22
 * Time: 下午10:40
 */

use Tygh\Registry;




/**
 * create/update trip agenda
 *
 * @param $auth
 * @param $product_id
 * @param $from_time
 * @param $to_time
 * @param int $agenda_id
 */

function fn_update_trip_agenda($auth, $product_id, $from_time, $to_time, $agenda_id = 0){

    $company_id = Registry::get('runtime.company_id');

    if (!$agenda_id) {//add new
        $agenda = array(
            'product_id' => intval($product_id),
            'company_id' => $company_id,
            'from_time' => strtotime($from_time),//second to formatted date
            'to_time' => strtotime($to_time),//second to formatted date
            'timestamp' => TIME,
            'author' => $auth['user_id'],
            'status' => 'A'
        );

        db_query("INSERT INTO ?:trip_agenda ?e", $agenda);

    }

    if ($agenda_id) {//update

        $agenda = array(
            'agenda_id' => $agenda_id,
            'product_id' => intval($product_id),
            'company_id' => $company_id,
            'from_time' => strtotime($from_time),//second to formatted date
            'to_time' => strtotime($to_time),//second to formatted date
            'timestamp' => TIME,
            'author' => $auth['user_id']
        );

        db_query("REPLACE INTO ?:trip_agenda ?e", $agenda);

    }

}

/**
 * get agenda by company
 *
 * @param $company_id
 * @return array
 */
function fn_get_trip_agendas($company_id){

    $sql = "SELECT DISTINCT t.agenda_id, t.product_id, d.product, t.status, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.from_time),'%m/%d/%Y') as from_time, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.to_time),'%m/%d/%Y') as to_time, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.timestamp),'%m/%d/%Y') as timestamp ";
    $sql .= "FROM ?:trip_agenda t ";
    $sql .= "LEFT JOIN ?:product_descriptions d ON t.product_id=d.product_id ";
    $sql .= "WHERE t.company_id = ?i";

    $agendas = db_get_array($sql, $company_id);

    return $agendas;

}

/**
 * get the agenda by id
 *
 * @param $agenda_id
 * @return object
 */
function fn_get_one_agenda($agenda_id){

    $sql = "SELECT DISTINCT t.agenda_id, t.product_id, d.product, t.status, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.from_time),'%m/%d/%Y') as from_time, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.to_time),'%m/%d/%Y') as to_time, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.timestamp),'%m/%d/%Y') as timestamp ";
    $sql .= "FROM ?:trip_agenda t ";
    $sql .= "LEFT JOIN ?:product_descriptions d ON t.product_id=d.product_id ";
    $sql .= "WHERE t.agenda_id = ?i";

    $agenda = db_get_row($sql, $agenda_id);

    return $agenda;

}

/**
 * delete agenda by id
 *
 * @param $agenda_id
 * @return bool
 */
function fn_delete_one_agenda($agenda_id){
    $sql = "DELETE FROM ?:trip_agenda WHERE agenda_id = ?i";

    db_query($sql, $agenda_id);

    return true;
}

/**
 * upate agenda status
 *
 * @param $agenda_id
 * @param $status
 * @return bool
 */
function fn_update_agenda_status($agenda_id, $status){

    $sql = "UPDATE ?:trip_agenda SET status = ?s WHERE agenda_id = ?i";

    db_query($sql, $status, $agenda_id);

    return true;
}