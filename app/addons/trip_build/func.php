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

/**
 * process redundant tab in page: products.update
 *
 */
function fn_trip_build_dispatch_before_display()
{
    Registry::del('navigation.tabs.blocks');
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

    if(empty($product_data['address'])) return true;

    $trip_obj = array(
        'product_id' => $product_id,
        'vendor_id' => $product_data['company_id'],
        'product_name' => $product_data['product'],
        'product_features' => $product_data['characteristics'],
        'product_note' => $product_data['notes'],
        'product_mustknown' => $product_data['must_known'],
        'address' => $product_data['address'],
        'location' => '',
        'price' => $product_data['price'],
        'price_unit' => '',
        'favorite' => 0,
        'grade' => 0,
    );
    $result = db_query("REPLACE INTO ?:product_trip ?e", $trip_obj);
    if($result){
//        PC::debug('trip product insert success!', 'trip_build');
    } else {
//        PC::debug('trip product insert failure!', 'trip_build');
    }

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
function fn_trip_build_pre_place_order($cart)
{
    trip_build_trace('-------'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'-------');
//    foreach($cart as $k=>$v){
//        trip_build_trace('key/value:'.$k.'/'.gettype($v).'/'.strval(gettype($v)=='array'?'array':$v));
//        if(gettype($v) == 'array'){
//            trip_build_trace('array-length:'.count($v));//view length
//            foreach($v as $sub_k=>$sub_v){
//                if(gettype($sub_v) == 'array'){
//                    trip_build_trace('---->'.$sub_k.':'.gettype($sub_v));
//                }else{
//                    trip_build_trace('---->'.$sub_k.':'.$sub_v);
//                }
//            }
//        }
//    }

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
//    foreach($product_data as $k=>$v){
//        trip_build_trace('key/value:'.$k.'/'.gettype($v).'/'.strval(gettype($v)=='array'?'array':$v));
//        if(gettype($v) == 'array'){
//            trip_build_trace('array-length:'.count($v));//view length
//            foreach($v as $sub_k=>$sub_v){
//                if(gettype($sub_v) == 'array'){
//                    trip_build_trace('---->'.$sub_k.':'.gettype($sub_v));
//                }else{
//                    trip_build_trace('---->'.$sub_k.':'.$sub_v);
//                }
//            }
//        }
//    }

    trip_build_trace('--------dump $cart-----------');
//    foreach($cart as $k=>$v){
//        trip_build_trace('key/value:'.$k.'/'.gettype($v).'/'.strval(gettype($v)=='array'?'array':$v));
//        if(gettype($v) == 'array'){
//            trip_build_trace('array-length:'.count($v));//view length
//            foreach($v as $sub_k=>$sub_v){
//                if(gettype($sub_v) == 'array'){
//                    trip_build_trace('---->'.$sub_k.':'.gettype($sub_v));
//                }else{
//                    trip_build_trace('---->'.$sub_k.':'.$sub_v);
//                }
//            }
//        }
//    }

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