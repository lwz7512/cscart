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

$schema['ct_faq'] = array (
		'content' => array (
			'type_display' => array (
				'type' => 'selectbox',
				'values' => array (
					'side' => 'ct_faq_side',
					'static' => 'ct_faq_static',
					'grey' => 'ct_faq_grey',
					'blue' => 'ct_faq_blue',
                    'line' => 'ct_faq_line',
                    'shadow' => 'ct_faq_shadow',
                    'bubble' => 'ct_faq_bubble',
                    'slide' => 'ct_faq_slide',
				),
				'default_value' => 'side'
			),
			'items' => array (
				'type' => 'enum',
				'object' => 'ct_faq',
				'remove_indent' => true,
				'hide_label' => true,			
				'fillings' => array (
					'manually' => array (
						'picker' => 'addons/ct_faq/pickers/ct_faq/picker.tpl',
						'picker_params' => array (
							'type' => 'links',
						),
					),
				),
			),
		),
		'templates' => array (
			'addons/ct_faq/blocks/ct_faq.tpl' => array(),
		),
		'wrappers' => 'blocks/wrappers',
		'icon' => '/images/block_manager/block_icons/4.png',
);
return $schema;
