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

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

fn_register_hooks(
	'get_product_data',
	'update_product',
	'global_update',
	'global_update_products',
	'update_product_post'
);

