<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-7-2
 * Time: 下午5:39
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Logger;


class Discussions  extends AEntity {


    public function index($id = 0, $params = array()){

        $posts = array();
        $object_id = $id;
        $discussion = fn_get_discussion($object_id, "P", true);
        if(!empty($discussion)){
            $posts = $discussion['posts'];
        }

        return array(
            'status' => Response::STATUS_OK,
            'data' => $posts
        );

    }

    /**
     * create comment and rate with 5 fields:
     * array('object_id'=>1, 'name'=>'lwz', 'message'=>'good', 'rating_value'=>'5', 'user_id'=>'1')
     *
     * @param array $params
     * @return array|Response
     */
    public function create($params){

        $object_id = $params['object_id'];
        $thread_id = db_get_field("SELECT thread_id FROM ?:discussion WHERE object_id = ?i", $object_id);
        if(empty($thread_id)){
            $discussion = array(
                'object_id' => intval($object_id),
                'object_type' => 'P',
                'type' => 'B'
            );
            db_query("INSERT INTO ?:discussion ?e", $discussion);
            $thread_id = db_get_field("SELECT thread_id FROM ?:discussion WHERE object_id = ?i", $object_id);
        }
        $ip = fn_get_ip();

        $post_data = array();
        $post_data['thread_id'] = $thread_id;
        $post_data['name'] = $params['name'];
        $post_data['message'] = $params['message'];
        $post_data['rating_value'] = $params['rating_value'];
        $post_data['ip_address'] = $ip['host'];
        $post_data['status'] = 'A';
        $post_data['timestamp'] = TIME;
        $post_data['user_id'] = $params['user_id'];
        $post_data['post_id'] = db_query("INSERT INTO ?:discussion_posts ?e", $post_data);

        db_query("REPLACE INTO ?:discussion_messages ?e", $post_data);
        db_query("REPLACE INTO ?:discussion_rating ?e", $post_data);

        return array(
            'status' => Response::STATUS_OK,
            'data' => array(
                'result' => 1
            )
        );
    }


    public function update($id, $params){
        return array(
            'status' => Response::STATUS_OK,
            'data' => array(
                'result' => 1
            )
        );
    }


    public function delete($id){
        return array(
            'status' => Response::STATUS_OK,
            'data' => array(
                'result' => 1
            )
        );
    }

    public function privileges()
    {
        return array(
            'index' => true,
            'create' => true
        );
    }

    private function trace($msg)
    {
        $logger = Logger::instance();
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';

        $logger->write($msg);
    }


} 