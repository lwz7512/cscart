<?php
/****************************************************************************
 *                                                                          *
 *    Copyright (c) 2012 CartTuning. All rights reserved.    	            *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  AT  THE *
 * http://www.carttuning.com/license-agreement.html                         *
 ****************************************************************************/

$schema['ct_faq'] = array(
        'controller' => 'ct_faq',
        'mode' => 'update',
        'type' => 'tpl_tabs',
        'params' => array(
            'object_id' => '@ct_faq_id',
            'object' => 'ct_faq'
        ),
        'table' => array(
            'name' => 'ct_faq',
            'key_field' => 'ct_faq_id',
        ),
        'request_object' => 'ct_faq',
        'have_owner' => true,
);

return $schema;
