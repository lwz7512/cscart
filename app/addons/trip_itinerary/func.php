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


/**
 * save the itinerary data
 *
 * itinerary data structure:
 *  product_data['itinerary']
 *      title,
 *      days
 *      x(day sequence):
 *          x(activity sequence):
 *              detail_desc:
 *              location:
 *              simple_desc:
 *              time:
 *              type:
 *          x(activity sequence):
 *      x(day sequence):
 *          x:
 *          x:
 *
 * @param $product_data
 * @param $product_id
 * @param $lang_code
 * @param $can_update
 */
function fn_trip_itinerary_update_product_post($product_data, $product_id, $lang_code, $can_update){

    PC::debug($product_data['itinerary'], 'itinerary');
    $itinerary = $product_data['itinerary'];

    //operate main table
    $itinerary_exist = db_get_field("SELECT COUNT(*) FROM ?:itinerary_product WHERE p_id = ?i", $product_id);
    if($itinerary_exist){//update
        db_query("UPDATE ?:itinerary_product SET it_title = ?s, it_time_in_day = ?i WHERE p_id = ?i",
            $itinerary['title'], $itinerary['days'], $product_id);
    }else{//insert
        db_query("INSERT INTO ?:itinerary_product ?e", array(
            'p_id' => $product_id,
            'it_title' => $itinerary['title'],
            'it_time_in_day' => $itinerary['days']
        ));
    }

    //TODO, operate other table...


}

/**
 *
 *
 * @param $product_data
 * @param $auth
 * @param $preview
 * @param $lang_code
 */
function fn_trip_itinerary_get_product_data_post(&$product_data, $auth, $preview, $lang_code){

    $itinerary_data = fn_get_session_data('itinerary');

    $product_data['itinerary'] = $itinerary_data;
}

/**
 * @param $product_id
 * @param $product_deleted
 */
function fn_trip_ininerary_delete_product_post($product_id, $product_deleted){



}