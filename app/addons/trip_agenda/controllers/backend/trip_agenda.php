<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-23
 * Time: 下午12:23
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// ========================= POST method ===========================

// post method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'add') {

        $suffix = '.add';

        $product_id = $_REQUEST['agenda_data']['product_id'];
        $time_from = $_REQUEST['agenda_time_from'];
        $time_to = $_REQUEST['agenda_time_to'];

        fn_update_trip_agenda($auth, $product_id, $time_from, $time_to);
    }

    if ($mode == 'update') {

        $suffix = '.update';

//        TODO, update agenda...

    }

    $msg = 'agenda saved!';

    fn_set_notification('N', __('notice'), $msg);

    return array(CONTROLLER_STATUS_OK, "trip_agenda$suffix");

}//end of post



// ========================= GET method ===========================
if ($mode == 'manage') {

//    list($companies, $search) = fn_get_companies($_REQUEST, $auth, Registry::get('settings.Appearance.admin_elements_per_page'));
//
//    Registry::get('view')->assign('agendas', $companies);

} elseif ($mode == 'update') {



} elseif ($mode == 'delete') {
//    fn_delete_company($_REQUEST['company_id']);

    return array(CONTROLLER_STATUS_REDIRECT, "trip_agenda.manage");

} elseif ($mode == 'update_status') {

//    $notification = !empty($_REQUEST['notify_user']) && $_REQUEST['notify_user'] == 'Y';
//
//    if (fn_companies_change_status($_REQUEST['id'], $_REQUEST['status'], '', $status_from, false, $notification)) {
//        fn_set_notification('N', __('notice'), __('status_changed'));
//    } else {
//        fn_set_notification('E', __('error'), __('error_status_not_changed'));
//        Registry::get('ajax')->assign('return_status', $status_from);
//    }

    exit;

}