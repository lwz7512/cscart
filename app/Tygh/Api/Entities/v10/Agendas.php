<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-28
 * Time: 下午2:51
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Logger;


class Agendas extends AEntity {

    private $fp = null;

    public function index($id = 0, $params = array()){

        $company_id = $id;

        $sql = "SELECT DISTINCT t.agenda_id as id, t.from_time as start, t.from_time as end, d.product as title ";
        $sql .= "FROM ?:trip_agenda t ";
        $sql .= "LEFT JOIN ?:product_descriptions d ON t.product_id=d.product_id ";
        $sql .= "WHERE t.company_id = ?i ORDER BY t.agenda_id ASC";


        $agendas = db_get_array($sql, $company_id);
        foreach($agendas as &$agenda){

            $finish_time = intval($agenda['end']);
            if($finish_time > TIME){//end time is ahead of now
                $agenda['class'] = 'event-info';
            }else{
                $agenda['class'] = 'event-warning';//has completed
            }

            $agenda['start'] .= '000';
            $agenda['end'] .= '000';

        }

        return array(
            'status' => Response::STATUS_OK,
            'data' => array(
                "success" => 1,
                "result" => $agendas
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
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/debug.log';

        $logger->write($msg);
    }


} 