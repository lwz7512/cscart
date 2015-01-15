<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-9-12
 * Time: 上午10:59
 */

namespace Tygh\Api\Entities\v10;


use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Registry;


class Itinerary extends AEntity {


    public function index($id = 0, $params = array()){
        $product_id = $id;

        $itinerary_data = db_get_row("SELECT u_id as it_id, it_title as title, it_time_in_day as days FROM ?:itinerary_product WHERE p_id = ?i", $product_id);

        if(!empty($itinerary_data)){
            $itinerary_days = db_get_array("SELECT day_title as title, day_sequence as seq FROM ?:itinerary_day WHERE it_id =?i ORDER BY day_sequence", $itinerary_data['it_id']);
            $itinerary_acts = db_get_array("SELECT * FROM ?:itinerary_activity WHERE it_id =?i", $itinerary_data['it_id']);

            $itinerary_data['children'] = array();
            foreach($itinerary_days as $day){
                $itinerary_data['children'][] = array(
                    'title' => $day['title'],
                    'children' => fn_get_activities_detail_by($day['seq'], $itinerary_acts)
                );

            }
        }else{
            $itinerary_data = array(
                'it_id' => '',
                'children' => []
            );
        }

        return array(
            'status' => Response::STATUS_OK,
            'data' => $itinerary_data
        );
    }

    public function create($params){

    }

    public function update($id, $params){

    }

    public function delete($id){

    }

    public function privileges(){
        return array(
            'index' => true
        );
    }



} 