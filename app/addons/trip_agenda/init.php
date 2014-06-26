<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-22
 * Time: 下午10:32
 */

if ( !defined('AREA') ) { die('Access denied'); }

fn_register_hooks(
    'get_product_data_post',
    'update_product_post',
    'delete_product_post',
    'pre_add_to_cart',
    'post_add_to_cart',
    'pre_place_order'
);
