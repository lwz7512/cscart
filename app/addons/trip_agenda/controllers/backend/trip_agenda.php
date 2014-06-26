<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-23
 * Time: 下午12:23
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// ========================= FORM: POST method ===========================

// post method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $product_id = $_REQUEST['agenda_data']['product_id'];
    $time_from = $_REQUEST['agenda_time_from'];
    $time_to = $_REQUEST['agenda_time_to'];
    $agenda_id = $_REQUEST['agenda_id'];

    if ($mode == 'add') {
        $suffix = '.add';
    }

    if ($mode == 'update') {
        $suffix = '.update?agenda_id='.$agenda_id;
    }

    fn_update_trip_agenda($auth, $product_id, $time_from, $time_to, $agenda_id);

    $msg = 'agenda saved!';

    fn_set_notification('N', __('notice'), $msg);

    return array(CONTROLLER_STATUS_OK, "trip_agenda$suffix");

}//end of post



// ========================= URL: GET method ===========================
if ($mode == 'manage') {

    $company_id = Registry::get('runtime.company_id');
    $agendas = fn_get_trip_agendas_by_company($company_id);

    Registry::get('view')->assign('agendas', $agendas);

} elseif ($mode == 'update') {

    if(isset($_REQUEST['agenda_id'])){
        $agenda_id = $_REQUEST['agenda_id'];
    }else{
        $agenda_id = 0;
    }
//    PC::debug('to update: '.$agenda_id, 'trip_agenda');
    $agenda = fn_get_one_agenda($agenda_id);

    Registry::get('view')->assign('id', $agenda_id);
    Registry::get('view')->assign('agenda', $agenda);

} elseif ($mode == 'delete') {
//    fn_delete_company($_REQUEST['company_id']);

    $agenda_id = $_REQUEST['agenda_id'];

    fn_delete_one_agenda($agenda_id);

    $msg = 'agenda deleted!';

    fn_set_notification('N', __('notice'), $msg);

    return array(CONTROLLER_STATUS_REDIRECT, "trip_agenda.manage");

} elseif ($mode == 'update_status') {//AJAX CALL

    $agenda_id = $_REQUEST['id'];
    $status = $_REQUEST['status'];

    fn_update_agenda_status($agenda_id, $status);

    fn_set_notification('N', __('notice'), __('status_changed'));

    exit;

}