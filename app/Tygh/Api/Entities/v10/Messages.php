<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-7-29
 * Time: 上午10:50
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Logger;

class Messages extends AEntity {


    public function index($id = 0, $params = array()){

        $user_id = $params['user_id'];

        $messages = array();

        if(!empty($params['msg_id'])){
            $messages[] = fn_get_message($params['msg_id']);
        }else{
            $messages = fn_get_message_list($user_id);
        }

        return array(
            'status' => Response::STATUS_OK,
            'data' => $messages
        );

    }

    /**
     * create question by post
     *
     * @param array $params
     * @return array|Response
     */
    public function create($params){

        fn_send_message($params['sender_id'], $params['receiver_id'], $params['content']);

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

    public function privileges(){
        return array(
            'index' => true,
            'create' => true,
            'update' => true
        );
    }

    private function trace($msg){
        $logger = Logger::instance();
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';

        $logger->write($msg);
    }

} 