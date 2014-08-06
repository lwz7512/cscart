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

        $wish_list = array(
            'products' => array()
        );

        fn_extract_cart_content($wish_list, $user_id, 'W');//fill original wishlist
        $products = !empty($wish_list['products']) ? $wish_list['products'] : array();

        $favorites = array();

        if (!empty($products)) {
            foreach ($products as $k => $v) {

                $auth = array(
                    'usergroup_ids' => array()
                );

                $product_name = fn_get_product_name($v['product_id']);
                $product_main_pair = fn_get_image_pairs($v['product_id'], 'product', 'M');
                $product_price = fn_get_product_price($v['product_id'],1, $auth);

                $products[$k]['product'] = $product_name;
                $products[$k]['image_path'] = $product_main_pair['detailed']['image_path'];
                $products[$k]['display_subtotal'] = $products[$k]['price'] * $v['amount'];
                $products[$k]['display_amount'] = $v['amount'];
                $products[$k]['cart_id'] = $k;
                $products[$k]['price'] = $product_price;

                unset($products[$k]['user_id']);
                unset($products[$k]['type']);
                unset($products[$k]['user_type']);
                unset($products[$k]['item_id']);
                unset($products[$k]['item_type']);
                unset($products[$k]['session_id']);
                unset($products[$k]['ip_address']);
                unset($products[$k]['product_options']);
                unset($products[$k]['extra']);

                $favorites[] = $products[$k];
            }
        }

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
            $params['product_id'] => array()
        );
        $auth = array(
            'user_id' => $params['user_id']
        );

        fn_extract_cart_content($wish_list, $auth['user_id'], 'W');//fill original wishlist
        $wishlist_ids = fn_add_product_to_wishlist($product_data, $wish_list, $auth);//add product to wishlist
        fn_save_cart_content($wish_list, $auth['user_id'], 'W');//save wishlist to database: user_session_products table

        $status = Response::STATUS_BAD_REQUEST;
        $result = 0;

        if(!empty($wishlist_ids)){
            $status = Response::STATUS_OK;
            $result = $wishlist_ids[0];
        }

        return array(
            'status' => $status,
            'data' => array(
                'result' => $result
            )
        );
    }

    /**
     * delete selected product
     *
     * @param int $id
     * @param array $params
     * @return array|Response
     */
    public function update($id, $params){

        $wish_list = array(
            'products' => array()
        );

        fn_extract_cart_content($wish_list, $params['user_id'], 'W');//fill original wishlist

        fn_delete_wishlist_product($wish_list, $id);

        fn_save_cart_content($wish_list, $params['user_id'], 'W');

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


    private function trace($msg)
    {
        $logger = Logger::instance();
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';

        $logger->write($msg);
    }


} 