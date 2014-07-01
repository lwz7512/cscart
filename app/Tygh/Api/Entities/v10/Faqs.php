<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-30
 * Time: 下午8:04
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Logger;

class Faqs  extends AEntity {


    public function index($id = 0, $params = array()){

        $product_id = $id;

        if($id == 0){
            $faqs = fn_get_latest_faqs();
        }else{
            $thread = fn_get_faq($product_id, 'p');
            $faqs = fn_get_faqs_data($thread['thread_id']);
        }

        if(empty($faqs)){//FIXME, return [] when no faqs @2014/07/01
            $faqs = array();
        }

        return array(
            'status' => Response::STATUS_OK,
            'data' => $faqs
        );

    }

    /**
     * create question by post
     *
     * @param array $params
     * @return array|Response
     */
    public function create($params){
        $faq = array(
            'type' => 'E',
            'object_type' => 'p',
            'object_id' => $params['object_id']//product id
        );
        $faq_message = array(
            'name' => $params['name'],//user name
            'email' => $params['email'],
            'message' => $params['message'],
            'user_id' => $params['user_id']
        );

        $thread_id = db_get_field("SELECT thread_id FROM ?:faq WHERE object_id = ?i AND object_type = ?s", $params['object_id'], 'p');

        if (!empty($thread_id)) {//if the product has one question, then use this thread
            $faq['thread_id'] = $thread_id;
            fn_add_faq($faq, $faq_message);
        } else {//if the product has no question before
            $faq['thread_id'] = db_query('INSERT INTO ?:faq ?e', $faq);
            fn_add_faq($faq, $faq_message);
        }

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