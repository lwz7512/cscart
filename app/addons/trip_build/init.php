<?php

if ( !defined('AREA') ) { die('Access denied'); }

fn_register_hooks(
	'dispatch_before_display',
    'update_product_post',
    'get_product_data_post'
);
