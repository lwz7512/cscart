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
 *          title:
 *          x(activity sequence):
 *              detail_desc:
 *              location:
 *              simple_desc:
 *              time:
 *              type:
 *          x(activity sequence):
 *              ...
 *      x(day sequence):
 *          title:
 *          x:
 *          x:
 *          ...
 *
 * @param $product_data
 * @param $product_id
 * @param $lang_code
 * @param $can_update
 */
function fn_trip_itinerary_update_product_post($product_data, $product_id, $lang_code, $can_update){

    $itinerary = $product_data['itinerary'];

    //operate main table
    $itinerary_id = db_get_field("SELECT u_id FROM ?:itinerary_product WHERE p_id = ?i", $product_id);
    if($itinerary_id){//update
        db_query("UPDATE ?:itinerary_product SET it_title = ?s, it_time_in_day = ?i WHERE p_id = ?i",
            $itinerary['title'], $itinerary['days'], $product_id);
    }else{//insert
        $itinerary_id = db_query("INSERT INTO ?:itinerary_product ?e", array(
            'p_id' => $product_id,
            'it_title' => $itinerary['title'],
            'it_time_in_day' => $itinerary['days']
        ));
    }

    //clear day table and activity table;
    db_query("DELETE FROM ?:itinerary_day WHERE it_id = ?i", $itinerary_id);
    db_query("DELETE FROM ?:itinerary_activity WHERE it_id = ?i", $itinerary_id);

    //then insert several day
    foreach($itinerary as $k => $v){
        if(!is_numeric($k)) continue;

        //add day record
        db_query("INSERT INTO ?:itinerary_day ?e", array(//insert day
            'it_id' => $itinerary_id,
            'day_sequence' => $k,
            'day_title' => $v['title']
        ));

        //add activity details record
        foreach($v as $act_k => $act_v){
            if(!is_numeric($act_k)) continue;

            if(strlen($act_v['simple_desc'])>255) {
                $act_v['simple_desc'] = substr_cut($act_v['simple_desc'], 255);
            }

            db_query("INSERT INTO ?:itinerary_activity ?e", array(
                'it_id' => intval($itinerary_id),
                'it_day' => intval($k),
                'act_sequence' => $act_k,
                'time_to_do' => $act_v['time'],
                'activity_type' => $act_v['type'],
                'location' => $act_v['location'],
                'simple_desc' => $act_v['simple_desc'],
                'detail_desc' => $act_v['detail_desc']
            ));
        }

    }

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

    $itinerary_data = db_get_row("SELECT u_id as it_id, it_title as title, it_time_in_day as days FROM ?:itinerary_product WHERE p_id = ?i", $product_data['product_id']);

    if(!$itinerary_data) return;//no itinerary data

    $itinerary_days = db_get_array("SELECT day_title as title, day_sequence as seq FROM ?:itinerary_day WHERE it_id =?i ORDER BY day_sequence", $itinerary_data['it_id']);

    $itinerary_acts = db_get_array("SELECT * FROM ?:itinerary_activity WHERE it_id =?i", $itinerary_data['it_id']);

    $itinerary_data['children'] = array();
    foreach($itinerary_days as $day){

        $itinerary_data['children'][] = array(
            'title' => $day['title'],
            'children' => fn_get_activities_detail_by($day['seq'], $itinerary_acts)
        );

    }

    $product_data['itinerary'] = $itinerary_data;//save the itinerary data to client use

    PC::debug($itinerary_data, 'itinerary');
}

/**
 * get one day's activities
 *
 * @param $day
 * @param $itinerary_acts
 * @return array
 */
function fn_get_activities_detail_by($day, $itinerary_acts){
    $acts = array();
    foreach($itinerary_acts as $act){
        if($day == $act['it_day']){
            $acts[] = $act;
        }
    }
    //order by act_sequence
    $act_size = count($acts);
    for($i=0;$i<$act_size;$i++){
        for($j=0;$j<$act_size-1-$i;$j++){
            if(intval($acts[$j]['act_sequence']) > intval($acts[$j+1]['act_sequence'])){
                $temp = $acts[$j];
                $acts[$j]=$acts[$j+1];
                $acts[$j+1]=$temp;//larger in back
            }
        }
    }

    return $acts;
}


/**
 * @param $product_id
 * @param $product_deleted
 */
function fn_trip_ininerary_delete_product_post($product_id, $product_deleted){



}

/*
------------------------------------------------------
参数：
$str_cut    需要截断的字符串
$length     允许字符串显示的最大长度
程序功能：截取全角和半角（汉字和英文）混合的字符串以避免乱码
------------------------------------------------------
*/
function substr_cut($str_cut,$length)
{
    if (strlen($str_cut) > $length)
    {
        for($i=0; $i < $length; $i++)
            if (ord($str_cut[$i]) > 128)    $i++;
        $str_cut = substr($str_cut,0,$i)."..";
    }
    return $str_cut;
}