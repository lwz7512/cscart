<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-9-2
 * Time: 下午2:40
 */

if ( !defined('AREA') ) { die('Access denied'); }

fn_register_hooks(
    'update_product_post',
    'get_product_data_post',
    'delete_product_post'
);