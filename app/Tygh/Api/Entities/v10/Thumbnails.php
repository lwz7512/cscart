<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-18
 * Time: 下午3:10
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Registry;
use Tygh\Logger;

class Thumbnails extends AEntity {


    public function index($id = 0, $params = array()){
        foreach($params as $k => $v){
            $this->trace($k.':'.$v);
        }

        $this->trace('id:'.$id);

        return array(
            'status' => Response::STATUS_OK,
            'data' => array(
                'result' => 1
            )
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
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';
        $logger->write($msg);
    }

}