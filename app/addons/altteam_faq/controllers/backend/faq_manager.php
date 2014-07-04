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

	return;
}

if ($mode == 'manage') {
	list($faq_data, $search) = fn_get_faqs_by_company($_REQUEST);
	$object_type = 'p';

	if (!empty($faq_data)) {
		foreach ($faq_data as $k => $v) {//$k is faq_id
			$thread_id = db_get_field("SELECT thread_id FROM ?:faq_data WHERE faq_id = ?i", $k);
			$object_id = db_get_field("SELECT object_id FROM ?:faq WHERE thread_id = ?i", $thread_id);
	 		$faq_data[$k]['object_data'] = fn_get_faq_object_data($object_id, $object_type, DESCR_SL);
            //FIXME, append object_id to result @2014/07/04
            $faq_data[$k]['object_id'] = $object_id;
	 	}
	}

	Registry::get('view')->assign('faq_data', $faq_data);
	Registry::get('view')->assign('search', $search);
}

/**
 * check faqs of one vendor
 *
 * @param array $params
 * @return array
 */
function fn_get_faqs_by_company($params = array()){
    $default_params = array (
        'page' => 1,
        'items_per_page' => Registry::get('settings.Appearance.admin_elements_per_page')
    );

    $params = array_merge($default_params, $params);

    $condition = '1';

//======== start company faq check =======================

    $company_id = Registry::get('runtime.company_id');
    $object_ids = array();
    if($company_id){
        //Get all the products of one company
        $product_ids = db_get_array("SELECT product_id FROM ?:products WHERE company_id = ?i", $company_id);
        foreach($product_ids as $product){
            //is it exist in faq table?
            $thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i", $product['product_id']);
            if(!empty($thread_id)){
                $object_ids[] = $thread_id;//save it
            }
        }
        $condition .= db_quote(" AND ?:faq_data.thread_id IN (?n)", $object_ids);
    }

//======== end of company faq check =======================

    $condition .= (AREA == 'A') ? '' : " AND ?:faq_data.status = 'A'";

    if (!empty($params['thread_id'])) {
        $condition .= db_quote(" AND thread_id = ?i", $params['thread_id']);
    }

    if (!empty($params['limit'])) {
        $limit = db_quote("LIMIT ?i", $params['limit']);
    } else {
        $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:faq_data WHERE ?p", $condition);
        $params['page'] = $params['page'] ? $params['page'] : 1;
        $limit = db_paginate($params['page'], $params['items_per_page']);
    }

    $faq_data = array();
    $faq_ids = db_get_fields("SELECT faq_id FROM ?:faq_data WHERE ?p ORDER BY faq_id DESC $limit", $condition);
    foreach ($faq_ids as $f_id => $faq_id) {
        $faq_data[$faq_id] = fn_get_faq_data($faq_id);
    }
    return array($faq_data, $params);
}