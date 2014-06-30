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

$schema['ct_faq.update'] = array(
	'func' => array('fn_get_ct_faq_category_name', '@category_id'),
	'text' => 'ct_faq'
);

return $schema;
