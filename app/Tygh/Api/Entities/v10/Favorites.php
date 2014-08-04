<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-8-4
 * Time: 下午4:10
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\AEntity;
use Tygh\Api\Response;
use Tygh\Registry;


class Favorites extends AEntity {


    public function index($id = 0, $params = array()){

        $user_id = $id;

        $favorites = array();

        return array(
            'status' => Response::STATUS_OK,
            'data' => $favorites
        );
    }


    /**
     * add data to wish list, data structure:
     *
     * 'user_id' => xxx,
     * 'product_id' => xxx,
     * 'amount' => xxx
     *
     * @param array $params
     * @return array|Response
     */
    public function create($params){

        $wish_list = array(
            'products' => array()
        );

        $product_data = array(
            $params['product_id'] => array(
                'amount' => $params['amount']
            )
        );
        $auth = array(
            'user_id' => $params['user_id']
        );

        fn_extract_cart_content($wish_list, $auth['user_id'], 'W');//fill original wishlist
        fn_add_product_to_wishlist($product_data, $wish_list, $auth);//add product to wishlist
        fn_save_cart_content($wish_list, $auth['user_id'], 'W');//save wishlist to database: user_session_products table

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
//            'index' => true,
            'create' => true,
            'delete' => true
        );
    }


    private function trace($msg)
    {
        $logger = Logger::instance();
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';

        $logger->write($msg);
    }


} 