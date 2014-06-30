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

if ($mode == 'view') {
    $company_id = Registry::get('runtime.company_id');
	fn_add_breadcrumb('FAQ');
	$categories = fn_get_all_faq_categories_company(true, $company_id);

	$display_faq = Registry::get('addons.ct_faq.display_faq');
    Registry::get('view')->assign('display_faq', $display_faq);
    Registry::get('view')->assign('categories', $categories);
	
}


?>
