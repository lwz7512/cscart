<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2012 CartTuning. All rights reserved.    	           *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  AT  THE *
* http://www.carttuning.com/license-agreement.html                         *
****************************************************************************/
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }
if ($_SERVER['REQUEST_METHOD']	== 'POST') {
    fn_trusted_vars (
		'faq_category_data',
		'categories_data',
		'category_questions'
	);
    if ($mode == 'update')
    {
		$category_questions = $_REQUEST['category_questions'];
        $global_questions = $_REQUEST['global_questions'];
        $product_id = $_REQUEST['product_id'];
        if (!empty($category_questions))
		    fn_ct_faq_product_update($product_id, $category_questions);
        if (!empty($global_questions))
            fn_ct_faq_product_update_global($product_id, $global_questions, DESCR_SL);
    }
}

if ($mode == 'delete'){
	$category_questions = array();
	$product_id = $_REQUEST['product_id'];
	fn_ct_faq_product_update($product_id, $category_questions);
}

if ($mode == 'update'){
	$tabs = Registry::get('navigation.tabs');
	$tabs['faq'] = array (
		'title' => fn_get_lang_var('faq'),
		'js' => true
	);
	Registry::set('navigation.tabs', $tabs);	
	$category_questions = fn_ct_faq_product_data($_REQUEST['product_id']);
    $global_questions = fn_ct_faq_get_product_global_data($_REQUEST['product_id'],DESCR_SL);
    Registry::get('view')->assign('category_questions', $category_questions);
    Registry::get('view')->assign('global_questions', $global_questions);
}

if ($mode == "delete_product_global_question"){
    $product_id = $_REQUEST['product_id'];
    $question_id = $_REQUEST['question_id'];
    fn_delete_product_global_question($question_id, $product_id);
    return array(CONTROLLER_STATUS_OK, "products.update&product_id=" . $product_id);
}