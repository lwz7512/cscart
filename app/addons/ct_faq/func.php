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

function fn_ct_faq_generate_sections($section)
{
    Registry::set('navigation.dynamic.sections', array('global_questions' => array('title' => fn_get_lang_var('ct_faq_global_questions'), 'href' => 'ct_faq.global_questions',), 'all_global_questions' => array('title' => fn_get_lang_var('ct_faq_all_global_questions'), 'href' => 'ct_faq.all_global_questions',)/*, 'import_questions' => array('title' => fn_get_lang_var('ct_faq_import_questions'), 'href' => 'ct_faq.import_questions',), 'export_questions' => array('title' => fn_get_lang_var('ct_faq_export_questions'), 'href' => 'ct_faq.export_questions',)*/));
    Registry::set('navigation.dynamic.active_section', $section);
    return true;
}

function fn_get_ct_faqs($params = array())
{
	$default_params = array (
		'items_per_page' => 0,
		'sort_by' => 'name',
	);

    $company_id = Registry::get('runtime.company_id');
    if ($company_id == 0) $company_id = 1;
    
	array_merge($default_params, $params);

	$sortings = array (
		'name' => '?:faq_categories_descriptions.name',
	);

    $directions = array (
        'asc' => 'asc',
        'desc' => 'desc'
    );

    $condition = $limit = '';

	if (!empty($params['limit'])) {
		$limit = db_quote(' LIMIT 0, ?i', $params['limit']);
	}

    if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {
        $params['sort_order'] = 'asc';
    }

    if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {
        $params['sort_by'] = 'name';
    }

    $sorting = $sortings[$params['sort_by']] . ' ' . $directions[$params['sort_order']];

	$condition = (AREA == 'A') ? '' : " AND ?:faq_categories.status = 'A' ";

	if (!empty($params['item_ids'])) {
		$condition .= db_quote(' AND ?:faq_categories.category_id IN (?n)', explode(',', $params['item_ids']));
	}

	$condition .= db_quote(' AND ?:faq_categories_descriptions.lang_code = ?s',DESCR_SL); 

    if (Registry::get('runtime.mode') == 'picker')
        $condition .= db_quote(' AND company_id=?i', $company_id);

	$faq_categories = db_get_array("SELECT * FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id WHERE 1 ?p ORDER BY ?p ?p", $condition, $sorting, $limit);
	
	if (!empty($params['item_ids'])) {
		$faq_categories = fn_sort_by_ids($faq_categories, explode(',', $params['item_ids']), 'category_id');
	}
	
	return array($faq_categories, $params);
}

function fn_get_ct_faq($params)
{

	$default_params = array (
		'items_per_page' => 0,
		'sort_by' => 'name',
	);

	$params = array_merge($default_params, $params);

	if(!empty($params['item_ids'])){
		return fn_get_ct_faqs($params);
	}	

	return array($icons, $params);
}

function fn_update_faq_category_questions($category_questions, $category_id, $lang_code = DESCR_SL)
{
	if (!empty($category_questions)) {
		$var_ids = array();
		foreach ($category_questions as $k => $v) {
            if (empty($v['position']) && $v['position'] != '0') {
                $v['position'] = db_get_field("SELECT max(position) FROM ?:faq_questions WHERE category_id = ?i", $category_id);
                $v['position'] = $v['position'] + 10;
            }
			if (empty($v['question'])) {
				continue;
			}
            if ($v['anchor'] == "") {
                $v['anchor'] = str_replace(" ","-", strtolower($v['question']));
            }else{
                $v['anchor'] = str_replace(" ","-", strtolower($v['anchor']));
            }
            $length = strlen($v['answer']);
            $temp_string = $v['answer'][0] . $v['answer'][1] . $v['answer'][2];
            $temp_string2 = $v['answer'][$length-3] . $v['answer'][$length-2] . $v['answer'][$length-1] . $v['answer'][$length];
            if ($temp_string != "<p>" && $temp_string2 != "</p>"){
                $v['answer'] = "<p>" . $v['answer'] . "</p>";
            }
			if (empty($v['question_id'])) {
				$v['question_id'] = '';
				$v['category_id'] = $category_id;
				$v['question_id'] = db_query("INSERT INTO ?:faq_questions ?e", $v);
				foreach ((array)Registry::get('languages') as $v['lang_code'] => $_v) {
					db_query("INSERT INTO ?:faq_questions_descriptions ?e", $v);
				}
			} else {
				$v['category_id'] = $category_id;
				db_query("UPDATE ?:faq_questions SET ?u WHERE question_id = ?i", $v, $v['question_id']);
				db_query("UPDATE ?:faq_questions_descriptions SET ?u WHERE question_id = ?i AND lang_code = ?s", $v, $v['question_id'], $lang_code);
				
			}
			$var_ids[$k] = $v['question_id'];
		}
		

		// Delete obsolete variants
		$deleted_variants = db_get_fields("SELECT question_id FROM ?:faq_questions WHERE category_id = ?i AND question_id NOT IN (?n)", $category_id, $var_ids);

		if (!empty($deleted_variants)) {
			db_query("DELETE FROM ?:faq_questions WHERE category_id = ?i AND question_id IN (?n)", $category_id, $deleted_variants);
		}
	}
	return true;
}

function fn_global_update_faq_questions ($global_questions, $ct_faq_products, $lang_code=DESCR_SL){
    foreach ((array)Registry::get('languages') as $key => $val) {

        foreach($global_questions as $k=>$v){
            $data['question'] = $v['question'];
            $length = strlen($v['answer']);
            $temp_string = $v['answer'][0] . $v['answer'][1] . $v['answer'][2];
            $temp_string2 = $v['answer'][$length-3] . $v['answer'][$length-2] . $v['answer'][$length-1] . $v['answer'][$length];
            if ($temp_string != "<p>" && $temp_string2 != "</p>"){
                $v['answer'] = "<p>" . $v['answer'] . "</p>";
            }
            if (empty($v['status'])) $v['status'] = 'A';
            $data['answer'] = $v['answer'];
            $data['status'] = $v['status'];
            $data['lang_code'] = $val['lang_code'];
            $product_ids = explode(",",$ct_faq_products);

            $question_id = db_query("INSERT INTO ?:faq_global_questions ?e", $data);
            if (!empty($ct_faq_products))
            foreach($product_ids as $key=>$value){
                $data_product['question_id'] = $question_id;
                $data_product['product_id'] = $value;
                db_query("INSERT INTO ?:faq_global_questions_products_ids ?e", $data_product);
            }
        }
    }
    fn_set_notification('N', "", fn_get_lang_var('text_changes_saved'));
}

function fn_get_global_questions($lang_code = DESCR_SL, $params){
    $params['page'] = empty($params['page']) ? 1 : $params['page'];
    $total = db_get_field("SELECT COUNT(?:faq_global_questions.question_id) FROM ?:faq_global_questions WHERE lang_code=?s",$lang_code);;
    $limit = db_paginate($params['page'], $total, (AREA == 'A')? Registry::get('settings.Appearance.admin_elements_per_page') : Registry::get('settings.Appearance.elements_per_page'));

    $global_questions = db_get_array("SELECT * FROM ?:faq_global_questions WHERE lang_code=?s ?p",$lang_code,$limit);
    foreach ($global_questions as $k=>$v){
        $questions = db_get_array("SELECT * FROM ?:faq_global_questions_products_ids WHERE question_id=?i",$v['question_id']);
        $global_questions[$k]['count'] = count($questions);
    }

    return $global_questions;
}

function fn_update_faq_category($category_id, $description, $position = 0, $status = 'A', $lang_code = DESCR_SL, $company_id){
	$data = array(
		'name' => $description, 
		'position' => $position, 
		'status' => $status,
        'company_id' => $company_id
	);
	$faq_category_data = db_get_field("SELECT * FROM ?:faq_categories WHERE category_id=?i", $category_id);
	if (!empty($faq_category_data)) {
		db_query('UPDATE ?:faq_categories SET ?u WHERE category_id = ?i', $data, $category_id);
		db_query('UPDATE ?:faq_categories_descriptions SET ?u WHERE category_id = ?i AND lang_code = ?s', $data, $category_id, $lang_code);
	}
}

function fn_delete_selected_faq_category($category_ids=array())
{
	foreach($category_ids as $category_id)
	{
		$faq_category_data = db_get_field("SELECT * FROM ?:faq_categories WHERE category_id=?i", $category_id);
		if (!empty($faq_category_data)) {
            db_query("DELETE FROM ?:faq_categories_descriptions WHERE category_id = ?i", $category_id);
			db_query("DELETE FROM ?:faq_categories WHERE category_id = ?i", $category_id);
		}
	}
}
function fn_update_selected_faq_category($categories_data=array())
{
	foreach($categories_data as $category_id => $position)
	{
		$faq_category_data = db_get_row("SELECT * FROM ?:faq_categories WHERE category_id=?i", $category_id);
		if($faq_category_data['position']!=$position['position'])
		{
			db_query('UPDATE ?:faq_categories SET ?u WHERE category_id = ?i', $position, $category_id);
		}
	}
}

function fn_add_faq_category($description, $position = '', $status = 'A', $lang_code = DESCR_SL, $company_id){
    if (empty($position) && $position != '0') {
            $position = db_get_field("SELECT max(position) FROM ?:faq_categories");
            $position = $position + 10;
    }

    if (empty($company_id)) $company_id = 0;
	$data = array(
		'position' => $position,
		'status' => $status,
        'company_id' => $company_id
	);

	$id = db_query("INSERT INTO ?:faq_categories ?e", $data);
	$data['category_id'] = $id;
    $data['name']= $description;
	foreach ((array)Registry::get('languages') as $data['lang_code'] => $_v) {
		db_query("INSERT INTO ?:faq_categories_descriptions ?e", $data);
	}	
	
}

function fn_get_faq_category_data($category_id, $lang_code = DESCR_SL){

	$faq_category_data = db_get_row("SELECT * FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id AND ?:faq_categories_descriptions.lang_code = ?s WHERE ?:faq_categories_descriptions.category_id = ?i", $lang_code, $category_id);	
	
	return $faq_category_data;
}

function fn_delete_faq_category($category_id){
	$faq_category_data = db_get_field("SELECT * FROM ?:faq_categories WHERE category_id=?i", $category_id);
	if (!empty($faq_category_data)) {
		db_query("DELETE FROM ?:faq_categories WHERE category_id = ?i", $category_id);
		db_query("DELETE FROM ?:faq_categories_descriptions WHERE category_id = ?i", $category_id);
	}
}

function fn_delete_global_question($question_id){
    db_query("DELETE FROM ?:faq_global_questions_products_ids WHERE question_id = ?i", $question_id);
    db_query("DELETE FROM ?:faq_global_questions WHERE question_id = ?i", $question_id);
}

function fn_delete_product_global_question($question_id, $product_id){
    db_query("DELETE FROM ?:faq_global_questions_products_ids WHERE question_id = ?i AND product_id=?i", $question_id, $product_id);
}

function fn_get_global_question($question_id){
    $question = db_get_row("SELECT * FROM ?:faq_global_questions WHERE question_id=?i",$question_id);
    $product_ids = db_get_array("SELECT product_id FROM ?:faq_global_questions_products_ids WHERE question_id=?i",$question_id);
    foreach ($product_ids as $k=>$v){
        $ids []=$v['product_id'];
    }
    $question['item_ids'] = $ids;
    return $question;
}

function fn_global_update_faq_question($global_question,$global_question_id,$ct_faq_products,$lang_code = DESCR_SL){
    $data['question'] = $global_question['question'];
    $data['answer'] = $global_question['answer'];
    $length = strlen($data['answer']);
    $temp_string = $data['answer'][0] . $data['answer'][1] . $data['answer'][2];
    $temp_string2 = $data['answer'][$length-3] . $data['answer'][$length-2] . $data['answer'][$length-1] . $data['answer'][$length];
    if ($temp_string != "<p>" && $temp_string2 != "</p>"){
        $data['answer'] = "<p>" . $data['answer'] . "</p>";
    }
    $data['status'] = $global_question['status'];
    $data['lang_code'] = $lang_code;
    db_query("UPDATE ?:faq_global_questions SET ?u WHERE question_id = ?i",$data,$global_question_id);
    $ids = explode(",",$ct_faq_products);
    db_query("DELETE FROM ?:faq_global_questions_products_ids WHERE question_id = ?i", $global_question_id);
    foreach($ids as $k=>$v){
        $data_product['question_id'] = $global_question_id;
        $data_product['product_id'] = $v;
        db_query("INSERT INTO ?:faq_global_questions_products_ids ?e", $data_product);
    }
}

function fn_ct_faq_get_product_global_data($product_id,$lang_code = DESCR_SL){
    $questions = array();
    $ids = db_get_array("SELECT question_id FROM ?:faq_global_questions_products_ids WHERE product_id IN (?i)",$product_id);
    foreach($ids as $k=>$v){
        $question = db_get_row("SELECT * FROM ?:faq_global_questions WHERE question_id=?i AND lang_code=?s",$v['question_id'],$lang_code);
        if (!empty($question)) $questions[] = $question;
    }
    return $questions;
}

function fn_ct_faq_get_product_global_data_front($product_id){
    $questions = array();
    $ids = db_get_array("SELECT question_id FROM ?:faq_global_questions_products_ids WHERE product_id IN (?i)",$product_id);

    foreach($ids as $k=>$v){
        $question = db_get_row("SELECT * FROM ?:faq_global_questions WHERE question_id=?i AND status='A' AND lang_code=?s",$v['question_id'],DESCR_SL);
        if (!empty($question)) $questions[] = $question;
    }
    return $questions;
}

function fn_ct_faq_product_update_global($product_id, $global_questions,$lang_code = DESCR_SL){
    foreach ($global_questions as $k=>$v){
        $questions =  db_get_row("SELECT * FROM ?:faq_global_questions WHERE question_id=?i",$v['question_id']);

        $length = strlen($v['answer']);
        $temp_string = $v['answer'][0] . $v['answer'][1] . $v['answer'][2];
        $temp_string2 = $v['answer'][$length-3] . $v['answer'][$length-2] . $v['answer'][$length-1] . $v['answer'][$length];
        if ($temp_string != "<p>" && $temp_string2 != "</p>"){
            $v['answer'] = "<p>" . $v['answer'] . "</p>";
        }

        if ($v['question'] != $questions['question'] || $v['answer'] != $questions['answer'] ) {
            db_query("DELETE FROM ?:faq_global_questions_products_ids WHERE question_id = ?i AND product_id=?i", $v['question_id'], $product_id);
            $data['product_id'] = $product_id;
            $data['status'] = "A";
            $data['position'] = db_get_field("SELECT max(position) FROM ?:faq_product ") + 10;
            $question_id = db_query("INSERT INTO ?:faq_product ?e", $data);
            $data_description['question_id'] = $question_id;
            $data_description['answer'] = $v['answer'];
            $data_description['question'] = $v['question'];
            $data_description['lang_code'] = $lang_code;
            db_query("INSERT INTO ?:faq_product_descriptions ?e", $data_description);
        }
    }
}

function fn_get_faq_categories($lang_code = DESCR_SL){

	$data = db_get_array("SELECT * FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id AND ?:faq_categories_descriptions.lang_code = ?s ORDER BY position ASC", $lang_code);
	
	$category_tree = array();
	foreach($data as $category)
	{
		$count_questions=db_get_field('SELECT COUNT(*) FROM ?:faq_questions WHERE category_id = ?i', $category['category_id']);
		$category_tree[$category['category_id']] = array(
			'category_id' => $category['category_id'],
			'parent_id' => 0,
			'id_path' => $category['category_id'],
			'category' => $category['name'],
			'position' => $category['position'],
			'status' => $category['status'],
			'product_count' => $count_questions,
			'age_verification' => "N",
			'age_limit' => 0,
			'age_warning_message' => "",
			'category_path' => $category['name'],
			'level' => 0,
            'company_id' => $category['company_id']
		);
	}
	return $category_tree;
}

function fn_get_all_faq_categories($status = false){

	$category = db_get_array("SELECT * FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id WHERE status = 'A' AND ?:faq_categories_descriptions.lang_code = ?s ORDER BY position ASC", DESCR_SL);
	
	foreach($category as $key => $value){	
		$questions = fn_get_faq_all_questions($value['category_id'], DESCR_SL);	
		if(!empty($questions)){
			$category[$key]['questions']= $questions;
		} else {
			unset($category[$key]);
		}
	}
	return $category;
}

function fn_get_all_faq_categories_company($status = false, $company_id){

    $category = db_get_array("SELECT * FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id WHERE status = 'A' AND company_id=?i AND ?:faq_categories_descriptions.lang_code = ?s ORDER BY position ASC", $company_id, DESCR_SL);

    foreach($category as $key => $value){
        $questions = fn_get_faq_all_questions($value['category_id'], DESCR_SL);
        if(!empty($questions)){
            $category[$key]['questions']= $questions;
        } else {
            unset($category[$key]);
        }
    }
    return $category;
}

function fn_get_faq_all_questions($category_id, $lang_code)
{
	$questions = db_get_array("SELECT * FROM ?:faq_questions LEFT JOIN ?:faq_questions_descriptions ON ?:faq_questions_descriptions.question_id = ?:faq_questions.question_id WHERE ?:faq_questions.category_id = ?i AND ?:faq_questions_descriptions.lang_code = ?s AND status = 'A' AND ?:faq_questions_descriptions.answer != '' ORDER BY position ASC", $category_id, $lang_code);
	return $questions;
}

function fn_get_faq_category_questions($category_id, $lang_code)
{
	$category_questions = db_get_array("SELECT * FROM ?:faq_questions LEFT JOIN ?:faq_questions_descriptions ON ?:faq_questions_descriptions.question_id = ?:faq_questions.question_id WHERE ?:faq_questions.category_id = ?i AND ?:faq_questions_descriptions.lang_code = ?s ORDER BY position ASC", $category_id, $lang_code);
	return $category_questions;
}

function fn_get_ct_faq_category_name($category_id=0){

	$catigory_name = db_get_field("SELECT name FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id WHERE ?:faq_categories.category_id = ?i AND ?:faq_categories_descriptions.lang_code = ?s ", $category_id, DESCR_SL);
	return $catigory_name;

}
function fn_ct_faq_product_update($product_id, $category_questions){
		foreach($category_questions as $question){
            if (empty($question['position']) && $question['position'] != '0') {
                $question['position'] = db_get_field("SELECT max(position) FROM ?:faq_product WHERE product_id = ?i", $product_id);
                $question['position'] = $question['position'] + 10;
            }
            $length = strlen($question['answer']);
            $temp_string = $question['answer'][0] . $question['answer'][1] . $question['answer'][2];
            $temp_string2 = $question['answer'][$length-3] . $question['answer'][$length-2] . $question['answer'][$length-1] . $question['answer'][$length];
            if ($temp_string != "<p>" && $temp_string2 != "</p>"){
                $question['answer'] = "<p>" . $question['answer'] . "</p>";
            }
			$data = array(
				'answer' => $question['answer'],
				'question' => $question['question'],
				'position' => $question['position'],
				'product_id' => $product_id
			);
			if(!empty($question['question'])){
				if(!empty($question['question_id'])){
					$data['question_id'] = $question['question_id'];
					db_query("UPDATE ?:faq_product SET ?u WHERE question_id = ?i", $data, $data['question_id']);
					db_query("UPDATE ?:faq_product_descriptions SET ?u WHERE question_id = ?i AND lang_code = ?s", $data, $data['question_id'], DESCR_SL);	
				} else {
                    if (empty($question['status'])) $question['status']="A";
					$data['status'] = $question['status'];
					$data['question_id'] = db_query("INSERT INTO ?:faq_product ?e", $data);
					foreach ((array)Registry::get('languages') as $data['lang_code'] => $_v) {
						db_query("INSERT INTO ?:faq_product_descriptions ?e", $data);
					}
				}
				$question_ids[] = $data['question_id'];
			}
		}
		
		$deleted_variants = db_get_fields("SELECT question_id FROM ?:faq_product WHERE product_id = ?i AND question_id NOT IN (?n)", $product_id, $question_ids);

		if (!empty($deleted_variants)) {
			db_query("DELETE FROM ?:faq_product WHERE product_id = ?i AND question_id IN (?n)", $product_id, $deleted_variants);
			db_query("DELETE FROM ?:faq_product_descriptions WHERE question_id IN (?n)", $deleted_variants);
		}	
}

function fn_ct_faq_product_data($product_id, $front = false){
    $condition = '';
	if($front){
		$condition .= db_quote(' AND ?:faq_product.status = ?s', 'A');
		$condition .= db_quote(' AND ?:faq_product_descriptions.answer != ""');
	}
	$questions = db_get_array("SELECT * FROM ?:faq_product LEFT JOIN ?:faq_product_descriptions ON ?:faq_product_descriptions.question_id = ?:faq_product.question_id WHERE ?:faq_product.product_id = ?i AND ?:faq_product_descriptions.lang_code = ?s $condition ORDER BY position ASC", $product_id, DESCR_SL);
	return $questions;
}

function fn_ct_faq_delete_global_questions($ids){
    if (!empty($ids))
        foreach($ids as $k=>$v){
            db_query("DELETE FROM ?:faq_global_questions_products_ids WHERE question_id = ?i", $v);
            db_query("DELETE FROM ?:faq_global_questions WHERE question_id = ?i", $v);
        }
}

function fn_ct_faq_export(){
    $name ='var/database/backup/faq_' . time(). ".xml";
    $fp =  fopen($name,"a+");
    $data = "<root>\n";
    $categories = db_get_array("SELECT * FROM ?:faq_categories LEFT JOIN ?:faq_categories_descriptions ON ?:faq_categories_descriptions.category_id = ?:faq_categories.category_id");

    foreach($categories as $category){
        $data .= "\t<category name=\"" . $category['name'] ."\" lang=\"". $category['lang_code'] ."\" position=\"". $category['position'] ."\" status=\"". $category['status'] ."\">\n";
        $questions = db_get_array("SELECT * FROM ?:faq_questions LEFT JOIN ?:faq_questions_descriptions ON ?:faq_questions_descriptions.question_id = ?:faq_questions.question_id WHERE ?:faq_questions.category_id=?i AND ?:faq_questions_descriptions.lang_code=?s", $category['category_id'], $category['lang_code']);
        foreach($questions as $question){
            $data .= "\t\t<questions visible=\"" . $question['visible'] . "\" position=\"" . $question['position'] . "\" lang=\"" . $question['lang_code'] . "\" status=\"" . $question['status'] . "\">\n";
            $data .= "\t\t\t<question>" . $question['question'] . "</question>\n";
            $data .= "\t\t\t<answer>" . $question['answer'] . "</answer>\n";
            $data .= "\t\t\t<anchor>" . $question['anchor'] . "</anchor>\n";
            $data .= "\t\t</questions>\n";
        }
        $data .= "\t</category>\n";
    }

    $local_questions = db_get_array("SELECT * FROM ?:faq_product LEFT JOIN ?:faq_product_descriptions ON ?:faq_product_descriptions.question_id = ?:faq_product.question_id");
    $data .= "\t<local>\n";
    foreach($local_questions as $local_question){
        $data .= "\t\t<questions position=\"" . $local_question['position'] . "\" lang=\"" . $local_question['lang_code'] . "\" status=\"" . $local_question['status'] . "\">\n";
        $data .= "\t\t\t<question>" . $local_question['question'] . "</question>\n";
        $data .= "\t\t\t<answer>" . $local_question['answer'] . "</answer>\n";
        $data .= "\t\t\t<product>" . $local_question['product_id'] . "</product>\n";
        $data .= "\t\t</questions>\n";
    }
    $data .= "\t</local>\n";

    $global_questions = db_get_array("SELECT * FROM ?:faq_global_questions");
    $data .= "\t<global>\n";
    foreach($global_questions as $global_question){
        $ids = fn_ct_faq_get_product_ids($global_question['question_id']);
        $data .= "\t\t<questions lang=\"" . $global_question['lang_code'] . "\" status=\"" . $global_question['status'] . "\">\n";
        $data .= "\t\t\t<question>" . $global_question['question'] . "</question>\n";
        $data .= "\t\t\t<answer>" . $global_question['answer'] . "</answer>\n";
        $data .= "\t\t\t<products>" . $ids . "</products>\n";
        $data .= "\t\t</questions>\n";
    }
    $data .= "\t</global>\n";

    $data .= "</root>";

    fwrite($fp,$data);
    fclose($fp);
    fn_set_notification('N', "", fn_get_lang_var('text_exim_data_exported'));
    fn_get_file($name);
}

function fn_ct_faq_get_product_ids($question_id){
    $ids ="";
    $product_ids = db_get_array("SELECT product_id FROM ?:faq_global_questions_products_ids WHERE question_id=?i",$question_id);
    foreach ($product_ids as $k=>$v){
        $ids .=$v['product_id'] . ",";
    }
    $ids = substr_replace($ids,"",strlen($ids)-1);
    return $ids;
}

function fn_ct_faq_import($files){
    if (!file_exists('var/database/backup')) mkdir('var/database/backup');
    if(is_uploaded_file($files["file_csv_file"]["tmp_name"][0]))
    {
        move_uploaded_file($files["file_csv_file"]["tmp_name"][0], "var/database/backup/".$files["file_csv_file"]["name"][0]);
        fn_set_notification('N', "", "Loading file successful");
    } else {
        fn_set_notification('W', "", "Loading file error");
    }
    if (!empty($files["file_csv_file"]["name"][0])){
        $xml = simplexml_load_file("var/database/backup/".$files["file_csv_file"]["name"][0]);
        foreach ($xml->xpath('/root/category') as $category) {
            $data = array(
                'position' => $category['position'],
                'status' => $category['status']
            );
            $category_id = db_get_array("SELECT category_id FROM ?:faq_categories_descriptions WHERE name= ?s", $category['name']);
            $category_id = $category_id[0]['category_id'];
            if (empty($category_id)){
                $category_id = db_query("INSERT INTO ?:faq_categories ?e", $data);
            }
            $data_descr = array(
                'category_id' => $category_id,
                'name' => $category['name'],
                'lang_code' => $category['lang'],
            );
            $checking = db_get_field("SELECT * FROM ?:faq_categories_descriptions WHERE category_id=?i AND name=?s AND lang_code=?s", $category_id,$category['name'],$category['lang']);
            if (empty($checking)){
                db_query("INSERT INTO ?:faq_categories_descriptions ?e", $data_descr);
                foreach($category as $cat){
                    $data_question = array (
                        'category_id' => $category_id,
                        'position' => $cat['position'],
                        'status' => $cat['status']
                    );
                    $question_id = db_get_array("SELECT question_id FROM ?:faq_questions_descriptions WHERE question= ?s AND answer=?s AND anchor=?s", $cat->question, "<p>" . $cat->answer->p . "</p>", $cat->anchor);
                    $question_id = $question_id[0]['question_id'];
                    if (empty($question_id)){
                        $question_id = db_query("INSERT INTO ?:faq_questions ?e", $data_question);
                    }
                    $data_question_descr = array (
                        'question_id' => $question_id,
                        'question' => $cat->question,
                        'answer' => "<p>" . $cat->answer->p . "</p>",
                        'anchor' => $cat->anchor,
                        'visible' => $cat['visible'],
                        'lang_code' => $cat['lang']
                    );
                    $checking_question = db_get_field("SELECT * FROM ?:faq_questions_descriptions WHERE question_id=?i AND question=?s AND answer=?s AND anchor=?s AND lang_code=?s", $question_id,$cat->question,"<p>" . $cat->answer->p . "</p>",$cat->anchor,$cat['lang']);
                    if (empty($checking_question))
                        db_query("INSERT INTO ?:faq_questions_descriptions ?e", $data_question_descr);
                }
            }
        }
        foreach ($xml->xpath('/root/local') as $local) {
            foreach($local as $l){
                $data_faq_product = array (
                    'product_id' => $l->product,
                    'position' => $l['position'],
                    'status' => $l['status']
                );
                $q_id = db_get_array("SELECT question_id FROM ?:faq_product_descriptions WHERE question= ?s AND answer=?s", $l->question, "<p>" . $l->answer->p . "</p>");
                $q_id = $q_id[0]['question_id'];
                if (empty($q_id)){
                    $q_id = db_query("INSERT INTO ?:faq_product ?e", $data_faq_product);
                }
                $data_faq_product_descr = array (
                    'question_id' => $q_id,
                    'answer' => "<p>" . $l->answer->p . "</p>",
                    'question' => $l->question,
                    'lang_code' => $l['lang']
                );
                $checking_product = db_get_field("SELECT * FROM ?:faq_product_descriptions WHERE question_id=?i AND question=?s AND answer=?s AND lang_code=?s", $q_id,$l->question,"<p>" . $l->answer->p . "</p>",$l['lang']);
                if (empty($checking_product))
                    db_query("INSERT INTO ?:faq_product_descriptions ?e", $data_faq_product_descr);
            }

        }
        foreach ($xml->xpath('/root/global') as $global) {
            foreach($global as $g){
                $data_faq_product_global = array (
                    'answer' => "<p>" . $g->answer->p . "</p>",
                    'question' => $g->question,
                    'lang_code' => $g['lang'],
                    'status' => $g['status']
                );
                $checking_global_product = db_get_field("SELECT * FROM ?:faq_global_questions WHERE  question=?s AND answer=?s AND lang_code=?s",$g->question,"<p>" . $g->answer->p . "</p>",$g['lang']);
                if (empty($checking_global_product)){
                    $q_ids = db_query("INSERT INTO ?:faq_global_questions ?e", $data_faq_product_global);
                    $products = explode(",",$g->products);
                    foreach($products as $product){
                        if ($product != ""){
                            $data_product = array (
                                'question_id' => $q_ids,
                                'product_id' => $product
                            );
                            db_query("INSERT INTO ?:faq_global_questions_products_ids ?e", $data_product);
                        }
                    }
                }

            }
        }
    }
}

function get_files($path, $order = 0, $mask = '*')
{
    if (!file_exists('var/database/backup')) mkdir('var/database/backup');
    $sdir = array();
    if (false !== ($files = scandir($path, $order)))
    {

        foreach ($files as $i => $entry)
        {
            $file = preg_match($mask, $entry);

            if ($entry != '.' && $entry != '..' && $file == 1)
            {
                $sdir[$i]['name'] = $entry;
                $sdir[$i]['size'] = filesize($path . "/" . $entry);
            }
        }
    }
    return ($sdir);
}

function fn_ct_faq_clean_blocks()
{
    $block_array = db_get_array("SELECT block_id FROM ?:bm_blocks WHERE type = 'ct_faq'");
    foreach ($block_array as $k=>$id) {
        db_query("DELETE FROM ?:bm_blocks WHERE block_id = ?i", $id['block_id']);
        db_query("DELETE FROM ?:bm_blocks_content WHERE block_id = ?i", $id['block_id']);
        db_query("DELETE FROM ?:bm_blocks_descriptions WHERE block_id = ?i", $id['block_id']);
        db_query("DELETE FROM ?:bm_snapping WHERE block_id = ?i", $id['block_id']);
    }
}
?>