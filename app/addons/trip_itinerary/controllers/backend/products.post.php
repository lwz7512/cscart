<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-9-2
 * Time: 下午2:57
 */

use Tygh\Registry;

// Add new tab to product edit page both new or update mode
Registry::set('navigation.tabs.trip_itinerary', array (
    'title' => __('trip_itinerary'),
    'js' => true
));


