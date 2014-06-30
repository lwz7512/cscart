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
	$suffix = '';
	fn_trusted_vars (
		'faq_category_data',
		'categories_data',
		'category_questions'
	);
	if ($mode == 'add_category'){
        $company_id = $_REQUEST['faq_company_id'];
		$name = $_REQUEST['faq_category_data']['name'];
		$position = $_REQUEST['faq_category_data']['position'];
		$status = $_REQUEST['faq_category_data']['status'];
		if(!empty($name)&&$faq_id==0){
			fn_add_faq_category($name, $position, $status, DESCR_SL, $company_id);
		}		
		$suffix = '.manage';
	}
	if ($mode == 'update_category'){
        $company_id = $_REQUEST['faq_company_id'];
		$category_id = $_REQUEST['faq_category_data']['category_id'];
		$name = $_REQUEST['faq_category_data']['name'];
		$position = $_REQUEST['faq_category_data']['position'];
		$status = $_REQUEST['faq_category_data']['status'];
		if(!empty($name)&&$faq_id==0){
			fn_update_faq_category($category_id, $name, $position, $status, DESCR_SL, $company_id);
		}		
		$suffix = '.manage';
	}
	
	if ($mode == 'm_delete') {
		$category_ids = $_REQUEST['category_ids'];
		fn_delete_selected_faq_category($category_ids);
		$suffix = '.manage';
	}
	if ($mode == 'm_update') {
		$categories_data = $_REQUEST['categories_data'];
		fn_update_selected_faq_category($categories_data);
		$suffix = '.manage';
	}
	if ($mode == 'm_update_question') {
		$category_id = $_REQUEST['category_id'];
		$category_questions = $_REQUEST['category_questions'];
		fn_update_faq_category_questions($category_questions, $category_id, DESCR_SL);
		$suffix = '.manage_questions&category_id='.$category_id;
	}
    if ($mode == "add_group_of_questions"){
        $global_questions = $_REQUEST['global_questions'];
        $ct_faq_products = $_REQUEST['ct_faq_products']['product_ids'];

        fn_global_update_faq_questions($global_questions, $ct_faq_products, DESCR_SL);
        $suffix = '.global_questions';
    }
    if ($mode == "update_global_question"){
        $global_question_id = $_REQUEST['question_id'];
        $global_question=  $_REQUEST['global_question'];
        $ct_faq_products = $_REQUEST['ct_faq_products']['product_ids'];
        fn_global_update_faq_question($global_question,$global_question_id,$ct_faq_products,DESCR_SL);
        $suffix = '.update_global_question&question_id=' . $global_question_id;
    }
    if ($mode == "delete_selected_global_questions")
    {
        fn_ct_faq_delete_global_questions($_REQUEST['ct_loaded_faq_ids']);
        $suffix = ".all_global_questions";
    }
    /*
    if ($mode == "export_questions"){
        fn_ct_faq_export();
        $suffix = ".export_questions";
    }
    if ($mode == "import_questions"){
        fn_ct_faq_import($_FILES);

        $suffix = ".import_questions";
    }*/
	return array(CONTROLLER_STATUS_OK, "ct_faq$suffix");
}

//---VIEW MODE---

if ($mode == 'manage') {
	$categories_tree = fn_get_faq_categories();
    Registry::get('view')->assign('categories_tree', $categories_tree);
}
if ($mode == 'picker') {
	list($faq_categories, ) = fn_get_ct_faqs(array());
    Registry::get('view')->assign('faq_categories', $faq_categories);
    Registry::get('view')->display('addons/ct_faq/pickers/ct_faq/picker_contents.tpl');
	exit;
}

if ($mode == 'manage_questions') {
	$category_id = $_REQUEST['category_id'];
	$category_questions = fn_get_faq_category_questions($category_id, DESCR_SL);
    $category_name = fn_get_ct_faq_category_name($category_id);
    Registry::get('view')->assign('category_name', $category_name);
    Registry::get('view')->assign('category_questions', $category_questions);
    Registry::get('view')->assign('category_id',$category_id);
    fn_add_breadcrumb(fn_get_lang_var('ct_faq_categories'), "ct_faq.manage");
    fn_add_breadcrumb($category_name,"ct_faq.manage_questions&category_id=".$category_id);
}

if ($mode == 'delete'){
	$suffix = '.manage';
	$category_id = $_REQUEST['category_id'];
	fn_delete_faq_category($category_id);
	return array(CONTROLLER_STATUS_OK, "ct_faq$suffix");
}

if ($mode == "delete_global_question"){
    $suffix = '.all_global_questions';
    $question_id = $_REQUEST['question_id'];
    fn_delete_global_question($question_id);
    return array(CONTROLLER_STATUS_OK, "ct_faq$suffix");
}

if ($mode == 'update_category') {
    Registry::set('navigation.tabs', array (
        'detailed' => array (
            'title' => fn_get_lang_var('general'),
            'js' => true
        )
    ));
    Registry::get('view')->assign('faq_category_data', fn_get_faq_category_data($_REQUEST['category_id'], DESCR_SL));
}

if ($mode == 'global_questions'){
    Registry::set('navigation.tabs', array (
        'questions' => array (
            'title' => fn_get_lang_var('ct_faq_questions'),
            'js' => true
        ),
        'products' => array (
            'title' => fn_get_lang_var('products'),
            'js' => true
        )
    ));
    fn_ct_faq_generate_sections('global_questions');
}

if ($mode == 'all_global_questions'){
    fn_ct_faq_generate_sections('all_global_questions');
    $global_questions = fn_get_global_questions(DESCR_SL,$_REQUEST);
    Registry::get('view')->assign('global_questions',$global_questions);
}

if ($mode == 'import_questions') {
    fn_ct_faq_generate_sections("import_questions");
}
if ($mode == 'export_questions') {
    fn_ct_faq_generate_sections("export_questions");
    $files = get_files('var/database/backup',1,'/(faq)(_)(\\d+)(\\.)(xml)/is');
    Registry::get('view')->assign('files',$files);
}
if ($mode == 'update_global_question'){
    Registry::set('navigation.tabs', array (
        'questions' => array (
            'title' => fn_get_lang_var('ct_faq_questions'),
            'js' => true
        ),
        'products' => array (
            'title' => fn_get_lang_var('products'),
            'js' => true
        )
    ));
    fn_add_breadcrumb("All global questions", "ct_faq.all_global_questions");
    $question_id = $_REQUEST['question_id'];
    $global_question  = fn_get_global_question($question_id);

    Registry::get('view')->assign('global_question',$global_question);
}
if ($mode == 'delete_file'){
    $file_name = $_REQUEST['filename'];
    $file="var/database/backup/$file_name";
    unlink($file);
    return array(CONTROLLER_STATUS_OK, "ct_faq.export_questions");
}
if ($mode == 'get_file'){
    $file_name = $_REQUEST['filename'];
    $file="var/database/backup/$file_name";
    fn_get_file($file);
    return array(CONTROLLER_STATUS_OK, "ct_faq.export_questions");
}
?>