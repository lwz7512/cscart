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

namespace Tygh\Api\Entities\v10;

use Tygh\Logger;
use Tygh\Api\Response;

class Orders extends \Tygh\Api\Entities\Orders
{
    public function index($id = 0, $params = array())
    {
        foreach($params as $k=>$v){

        }


        if (!$id && !isset($params['items_per_page'])) {
            $params['items_per_page'] = 0;
        }

        $result = parent::index($id, $params);

        if (!$id) {
            $result['data'] = $result['data']['orders'];
        }

        return $result;
    }

    /**
     * place order
     *
     * @param array $params:
     * user_id => 1,
     * payment_id => 1,
     * products =>array("1"=>array('product_id'=>1, 'amount'=1, 'agenda'=>1)),
     *
     * option param:
     * payment_info => array()
     *
     * @return array
     */
    public function create($params){
        $this->trace('<======='.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'========>');

        $cart = array();
        $cart['products'] = array();
        $cart['recalculate'] = false;
        $cart['tax_subtotal'] = 0;
        $cart['discount'] = 0;
        $cart['total'] = 0;//total expense all the cart before this order
        $cart['amount'] = 0;//total amount of products before this order
        $cart['original_subtotal'] = 0;
        $cart['display_subtotal'] = 0;
        $cart['subtotal'] = 0;//total expense all the cart before this order
        $cart['use_discount'] = false;
        $cart['shipping_required'] = false;
        $cart['company_shipping_failed'] = false;
        $cart['shipping_failed'] = false;
        $cart['stored_taxes'] = 'N';
        $cart['shipping_cost'] = 0;
        $cart['display_shipping_cost'] = 0;
        $cart['coupons'] = array();
        $cart['free_shipping'] = array();
        $cart['options_style'] = 'F';
        $cart['no_promotions'] = false;
        $cart['promotions'] = array();
        $cart['subtotal_discount'] = 0;
        $cart['has_coupons'] = true;
        $cart['applied_promotions'] = array();
        $cart['product_groups'] = array();
        $cart['shipping'] = array();
        $cart['chosen_shipping'] = array();
        $cart['taxes'] = array();
        $cart['tax_summary'] = array();
        $cart['calculate_shipping'] = false;
        $cart['payment_surcharge'] = 0;

        $data = array();
        $valid_params = true;
        $status = Response::STATUS_BAD_REQUEST;

        fn_clear_cart($cart, true);
        if (!empty($params['user_id'])) {
            $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
            $cart['user_data'] = fn_get_user_info($params['user_id']);
        } elseif (!empty($params['user_data'])) {
            $cart['user_data'] = $params['user_data'];
        }

        if (empty($params['user_id']) && empty($params['user_data'])) {
            $data['message'] = __('api_required_field', array(
                '[field]' => 'user_id/user_data'
            ));
            $valid_params = false;

        } elseif (empty($params['payment_id'])) {
            $data['message'] = __('api_required_field', array(
                '[field]' => 'payment_id'
            ));
            $valid_params = false;
        }

        if ($valid_params) {

            $cart['payment_id'] = $params['payment_id'];

            $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
            $customer_auth = fn_fill_auth($cart['user_data']);

            $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
            fn_add_product_to_cart($params['products'], $cart, $customer_auth);

            $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
            fn_calculate_cart_content($cart, $customer_auth);

            if (!empty($cart['product_groups']) && !empty($params['shipping_ids'])) {
                foreach ($cart['product_groups'] as $key => $group) {
                    foreach ($group['shippings'] as $shipping_id => $shipping) {
                        if ($params['shipping_ids'] == $shipping['shipping_id']) {
                            $cart['chosen_shipping'][$key] = $shipping_id;
                            break;
                        }
                    }
                }
            }
            $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
            $cart['calculate_shipping'] = true;
            fn_calculate_cart_content($cart, $customer_auth);

            if (empty($cart['shipping_failed']) || empty($params['shipping_ids'])) {
                $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
                fn_update_payment_surcharge($cart, $customer_auth);

                $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
                list($order_id, ) = fn_place_order($cart, $customer_auth, 'save', $this->auth['user_id']);

                if (!empty($order_id)) {
                    $status = Response::STATUS_CREATED;
                    $data = array(
                        'order_id' => $order_id,
                    );
                }else{
                    $this->trace('create order failure!');
                }
            }
        }
        $this->trace('<---'.basename(__FILE__, '.php').':'.__FUNCTION__.':'.__LINE__.'--->');
        return array(
            'status' => $status,
            'data' => $data
        );
    }


    private function trace($msg)
    {
        $logger = Logger::instance();
        $logger->logfile = $_SERVER['DOCUMENT_ROOT'].'/logs'.'/running.log';
        $logger->write($msg);
    }


}
