<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

if ( !defined('AREA') ) { die('Access denied'); }


use Tygh\Registry;
use Tygh\Logger;
use Tygh\ClientInfo;

use GeoIp2\Database\Reader;


/**
 * process redundant tab in page: products.update
 *
 */
function fn_trip_build_dispatch_before_display()
{
    Registry::del('navigation.tabs.blocks');

    $obj = new ClientInfo();
    $ip = $obj->GetIP(); //获取访客IP地址。
//    PC::debug($ip, 'client ip');

    // This creates the Reader object, which should be reused across lookups.
    $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-City.mmdb');

    try{
        $record = $reader->city($ip);
        $location = $record->location->longitude.','.$record->location->latitude;

//        PC::debug($record->country->isoCode . "\n", "geoip"); // 'US'
//        PC::debug($record->country->name . "\n", "geoip"); // 'United States'
//        PC::debug($record->country->names['zh-CN'] . "\n", "geoip"); // '美国'
//
//        PC::debug($record->mostSpecificSubdivision->name . "\n", "geoip"); // 'Minnesota'
//        PC::debug($record->mostSpecificSubdivision->isoCode . "\n", "geoip"); // 'MN'
//
//        PC::debug($record->city->name . "\n", "geoip"); // 'Minneapolis'
//
//        PC::debug($record->postal->code . "\n", "geoip"); // '55455'
//
//        PC::debug($record->location->latitude . "\n", "geoip"); // 44.9733
//        PC::debug($record->location->longitude . "\n", "geoip"); // -93.2323


        Registry::get('view')->assign('client_location', $location);//client location

    }catch (Exception $e){
//        PC::debug("ip not exist in database!", 'geoip_error');
        Registry::get('view')->assign('client_location', '');//client location

    }





}


/**
 * append trip data to original product data
 *
 * @param $product_data
 * @param $auth
 * @param $preview
 * @param $lang_code
 * @return bool
 */
function fn_trip_build_get_product_data_post(&$product_data, $auth, $preview, $lang_code)
{
    $product_id = $product_data['product_id'];
    $trip_data = db_get_row("SELECT * FROM ?:product_trip WHERE product_id = ?i", $product_id);

    if($trip_data){
        $product_data['trip'] = array(
            'characteristics' => $trip_data['product_features'],
            'notes' => $trip_data['product_note'],
            'must_known' => $trip_data['product_mustknown'],
            'address' => $trip_data['address'],
            'location' => $trip_data['location'],
        );
    }

    return true;
}

/**
 * process submitted product data including trip properties
 *
 * @param $product_data
 * @param $product_id
 * @param $lang_code
 * @param $can_update
 * @return bool
 */
function fn_trip_build_update_product_post($product_data, $product_id, $lang_code, $can_update)
{

    $company_id = Registry::get('runtime.company_id');

    if($company_id && ACCOUNT_TYPE == 'vendor'){//when vendor submit the product
        $sql = "UPDATE ?:products SET status = 'D' WHERE product_id = ?i";//deactive the product
        db_query($sql, $product_id);
    }

    if(empty($product_data['address'])) return true;
    if(empty($product_data['company_id'])) return true;

    $trip_obj = array(
        'product_id' => $product_id,
        'vendor_id' => $product_data['company_id'],
        'product_name' => $product_data['product'],
        'product_features' => $product_data['characteristics'],
        'product_note' => $product_data['notes'],
        'product_mustknown' => $product_data['must_known'],
        'address' => $product_data['address'],
        'location' => $product_data['location'],
        'price' => $product_data['price'],
        'price_unit' => '',
        'favorite' => 0,
        'grade' => 0,
    );
    db_query("REPLACE INTO ?:product_trip ?e", $trip_obj);

    return true;
}

/**
 * delete trip record while delete product
 *
 * @param $product_id
 * @param $product_deleted
 * @return bool
 */
function fn_trip_build_delete_product_post($product_id, $product_deleted)
{
    if($product_deleted){
        db_query("DELETE FROM ?:product_trip WHERE product_id = ?i", $product_id);
    }

    return true;
}

/**
 * detect $cart value
 *
 * @param $cart
 */
function fn_trip_build_pre_place_order($cart){

    return true;
}

/**
 * detect product data added to cart
 *
 * @param $product_data
 * @param $cart
 * @param $auth
 * @param $update
 * @return bool
 */
function fn_trip_build_pre_add_to_cart($product_data, $cart, $auth, $update)
{
    trip_build_trace('<======='.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'========>');

    trip_build_trace('--------dump $product_data-----------');

    trip_build_trace('--------dump $cart-----------');

    return true;
}


/**
 * product added to cart
 *
 * @param $product_data
 * @param $cart
 * @param $auth
 * @param $update
 */
function fn_trip_build_post_add_to_cart($product_data, $cart, $auth, $update)
{
    trip_build_trace('>>> trip_build: product added to cart!');
}


function trip_build_trace($msg)
{
    $logger = Logger::instance();
    $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';
    $logger->write($msg);
}