<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-10
 * Time: 下午1:01
 */

use Tygh\Registry;

//PC::debug('current mode:'.$mode, 'products.post');

if($mode == 'add') {

    // Add new tab to page sections
    Registry::set('navigation.tabs.trip_build', array (
//        'title' => __('trip_page'),
        'title' => 'Trip page',
        'js' => true
    ));

} elseif ($mode == 'update') {

//    Add new tab to page sections
    Registry::set('navigation.tabs.trip_build', array (
//        'title' => __('trip_page'),
        'title' => 'Trip page',
        'js' => true
    ));

//    Remove redundant tabs
    Registry::del('navigation.tabs.options');
    Registry::del('navigation.tabs.shippings');
    Registry::del('navigation.tabs.files');
    Registry::del('navigation.tabs.subscribers');
//    Registry::del('navigation.tabs.features');
    Registry::del('navigation.tabs.product_tabs');

}

Registry::del('navigation.tabs.qty_discounts');

