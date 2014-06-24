<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-24
 * Time: 下午4:20
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// Ajax content
if ($mode == 'get_vendor_product_list') {

    // Check if we trying to get list by non-ajax
    if (!defined('AJAX_REQUEST')) {
        return array(CONTROLLER_STATUS_REDIRECT, fn_url());
    }

    $params = $_REQUEST;
    $condition = $params['company_id'];
    $pattern = !empty($params['pattern']) ? $params['pattern'] : '';
    $start = !empty($params['start']) ? $params['start'] : 0;
    $limit = (!empty($params['limit']) ? $params['limit'] : 10) + 1;

//    PC::debug('company_id: '.$condition, 'AJAX');
//    PC::debug('limit: '.$limit, 'AJAX');

    /**
     * db_get_hash_array: need index field definition in second argument, e.g: 'value'
     * the field name come from select field name;
     */
    if ($condition) {
        $objects = db_get_hash_array("SELECT DISTINCT t.product_id as value, d.product AS name FROM ?:products t LEFT JOIN ?:product_descriptions d ON t.product_id=d.product_id WHERE t.company_id=?i AND product LIKE ?l ORDER BY t.product_id DESC LIMIT ?i, ?i", 'value', $condition, $pattern . '%', $start, $limit);
    } else {
        $objects = db_get_hash_array("SELECT DISTINCT t.product_id as value, d.product AS name FROM ?:products t LEFT JOIN ?:product_descriptions d ON t.product_id=d.product_id WHERE 1 AND product LIKE ?l ORDER BY t.product_id DESC LIMIT ?i, ?i", 'value', $pattern . '%', $start, $limit);
    }

//    PC::debug('objects:'.count($objects), 'AJAX');

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
