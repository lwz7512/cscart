<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-8-1
 * Time: 下午6:16
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// ========================= FORM: POST method ===========================

// post method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(empty($_REQUEST['certificate_data']['certificate'])){
        $certificate = 0;
    }else{
        $certificate = 1;
    }
    $vendor_id = $_REQUEST['certificate_data']['vendor_id'];
    $u_id = $_REQUEST['u_id'];
    $grade = $_REQUEST['certificate_data']['grade'];

    $result = fn_update_certificate($u_id, $vendor_id, $certificate, $grade);

    if ($mode == 'add') {
        $suffix = '.add';
        $msg = 'Vendor Certificate Added!';
        fn_set_notification('N', __('notice'), $msg);
    }

    if ($mode == 'update') {
        $suffix = '.update?u_id='.$u_id;
    }

    if(!$result){
        $msg = 'Vendor already exist, please update it!';
        $suffix = '.manage';
        fn_set_notification('N', __('notice'), $msg);
    }


    return array(CONTROLLER_STATUS_OK, "certificate$suffix");

}//end of post

// ========================= URL: GET method ===========================

if ($mode == 'manage') {

    $certificates = fn_get_certificates();

    Registry::get('view')->assign('certificates', $certificates);

} elseif ($mode == 'update') {

    $u_id = $_REQUEST['u_id'];

    $certificate = fn_get_one_certificate($u_id);

    Registry::get('view')->assign('id', $u_id);
    Registry::get('view')->assign('certificate', $certificate);

} elseif ($mode == 'delete') {

    $u_id = $_REQUEST['u_id'];
    fn_delete_certificate($u_id);

    return array(CONTROLLER_STATUS_REDIRECT, "certificate.manage");

} elseif ($mode == 'update_status') {//AJAX CALL

    exit;
}//end of get


// ============================= Ajax content ============================

if ($mode == 'get_vendor_list') {

    // Check if we trying to get list by non-ajax
    if (!defined('AJAX_REQUEST')) {
        return array(CONTROLLER_STATUS_REDIRECT, fn_url());
    }

    $params = $_REQUEST;
    $pattern = !empty($params['pattern']) ? $params['pattern'] : '';
    $start = !empty($params['start']) ? $params['start'] : 0;
    $limit = (!empty($params['limit']) ? $params['limit'] : 10) + 1;

    /**
     * db_get_hash_array: need index field definition in second argument, e.g: 'value'
     * the field name come from select field name;
     */

    $sql = "SELECT t.company_id AS value, t.company AS name ";
    $sql .= "FROM ?:companies t ";
    $sql .= "WHERE 1 AND t.company LIKE ?l ORDER BY t.company_id DESC LIMIT ?i, ?i";

    $objects = db_get_hash_array($sql, 'value', $pattern . '%', $start, $limit);

    if (defined('AJAX_REQUEST') && sizeof($objects) < $limit) {
        Registry::get('ajax')->assign('completed', true);
    } else {
        array_pop($objects);
    }


    Registry::get('view')->assign('objects', $objects);
    Registry::get('view')->assign('id', $params['result_ids']);
    Registry::get('view')->display('common/ajax_select_object.tpl');

    exit;

}