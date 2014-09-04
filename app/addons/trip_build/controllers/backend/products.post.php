<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-10
 * Time: 下午1:01
 */

use Tygh\Registry;


if($mode == 'add') {

//    do nothing currently...

} elseif ($mode == 'update') {

//    Remove redundant tabs
    Registry::del('navigation.tabs.options');
    Registry::del('navigation.tabs.shippings');
    Registry::del('navigation.tabs.files');
    Registry::del('navigation.tabs.subscribers');
    Registry::del('navigation.tabs.features');
    Registry::del('navigation.tabs.product_tabs');

}

// Add new tab to product edit page both new or update mode
Registry::set('navigation.tabs.trip_build', array (
        'title' => __('trip_page'),
    'js' => true
));

Registry::del('navigation.tabs.qty_discounts');

