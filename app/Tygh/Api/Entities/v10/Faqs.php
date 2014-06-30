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

        return array(
            'status' => Response::STATUS_OK,
            'data' => $faqs
        );

    }

    public function create($params){
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
            'index' => true
        );
    }

    private function trace($msg)
    {
        $logger = Logger::instance();
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/debug.log';

        $logger->write($msg);
    }


} 