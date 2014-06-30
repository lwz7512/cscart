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
if ($mode == 'view')
{
	$category_questions = fn_ct_faq_product_data($_REQUEST['product_id'], true);
	$global_questions = fn_ct_faq_get_product_global_data_front($_REQUEST['product_id']);

	if (!empty($category_questions) || !empty($global_questions)){
	
		$display_style_product = Registry::get('addons.ct_faq.display_product');

        Registry::get('view')->assign('display_style_product', $display_style_product);
        Registry::get('view')->assign('questions', $category_questions);
        Registry::get('view')->assign('global_questions', $global_questions);
	}
}
