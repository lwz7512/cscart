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


class Products extends \Tygh\Api\Entities\Products
{

    public function index($id = 0, $params = array())
    {
        $result = parent::index($id, $params);
        $sql_1 = "SELECT thread_id FROM ?:discussion WHERE object_id = ?i";
        $sql_2 = "SELECT AVG(rating_value) FROM ?:discussion_rating WHERE thread_id = ?i";

        if (!empty($id)) {
            $product = &$result['data'];

            if(isset($product['main_pair'])){
                $product['image_path'] = $product['main_pair']['detailed']['image_path'];
            }else{
                $product['image_path'] = "";
            }

            unset($product['min_items_in_box']);
            unset($product['max_items_in_box']);
            unset($product['image_pairs']);
            unset($product['main_pair']);
            unset($product['box_length']);
            unset($product['box_width']);
            unset($product['box_height']);
            unset($product['product_code']);
            unset($product['product_type']);
            unset($product['approved']);
            unset($product['weight']);
            unset($product['length']);
            unset($product['width']);
            unset($product['height']);
            unset($product['shipping_freight']);
            unset($product['low_avail_limit']);
            unset($product['usergroup_ids']);
            unset($product['is_edp']);
            unset($product['edp_shipping']);
            unset($product['unlimited_download']);
            unset($product['tracking']);
            unset($product['free_shipping']);
            unset($product['feature_comparison']);
            unset($product['zero_price_action']);
            unset($product['is_pbp']);
            unset($product['is_op']);
            unset($product['is_oper']);
            unset($product['is_returnable']);
            unset($product['return_period']);
            unset($product['avail_since']);
            unset($product['out_of_stock_actions']);
            unset($product['localization']);
            unset($product['min_qty']);
            unset($product['max_qty']);
            unset($product['qty_step']);
            unset($product['list_qty_count']);
            unset($product['tax_ids']);
            unset($product['age_verification']);
            unset($product['age_limit']);
            unset($product['options_type']);
            unset($product['exceptions_type']);
            unset($product['details_layout']);
            unset($product['shipping_params']);
            unset($product['facebook_obj_type']);
            unset($product['lang_code']);
            unset($product['meta_keywords']);
            unset($product['meta_description']);
            unset($product['search_words']);
            unset($product['page_title']);
            unset($product['age_warning_message']);
            unset($product['promo_text']);
            unset($product['category_ids']);
            unset($product['faq_type']);
            unset($product['discussion_type']);
            unset($product['main_category']);
            unset($product['product_features']);
            unset($product['detailed_params']);

            $product_id = $product['product_id'];
            $thread_id = db_get_field($sql_1, $product_id);
            $avg_rate = db_get_field($sql_2, $thread_id);

            if(empty($avg_rate)) $avg_rate = 0;

            $product['avg_rate'] = floatval($avg_rate);

            return $result;
        }

        foreach($result['data']['products'] as &$product){

            $product_id = $product['product_id'];
            $thread_id = db_get_field($sql_1, $product_id);
            $avg_rate = db_get_field($sql_2, $thread_id);

            if(empty($avg_rate)) $avg_rate = 0;

            $product['avg_rate'] = floatval($avg_rate);

            if(isset($product['main_pair'])){
                $product['image_path'] = $product['main_pair']['detailed']['image_path'];
            }else{
                $product['image_path'] = "";
            }

            unset($product['product_code']);
            unset($product['main_pair']);
            unset($product['product_type']);
            unset($product['qty_content']);
            unset($product['detailed_params']);
            unset($product['shipping_params']);
            unset($product['discounts']);
            unset($product['discounts']);
            unset($product['approved']);
            unset($product['shipping_freight']);
            unset($product['weight']);
            unset($product['length']);
            unset($product['width']);
            unset($product['height']);
            unset($product['shipping_freight']);
            unset($product['low_avail_limit']);
            unset($product['usergroup_ids']);
            unset($product['is_edp']);
            unset($product['edp_shipping']);
            unset($product['unlimited_download']);
            unset($product['feature_comparison']);
            unset($product['zero_price_action']);
            unset($product['is_pbp']);
            unset($product['is_op']);
            unset($product['is_oper']);
            unset($product['is_returnable']);
            unset($product['return_period']);
            unset($product['avail_since']);
            unset($product['out_of_stock_actions']);
            unset($product['localization']);
            unset($product['min_qty']);
            unset($product['max_qty']);
            unset($product['qty_step']);
            unset($product['list_qty_count']);
            unset($product['tax_ids']);
            unset($product['age_verification']);
            unset($product['age_limit']);
            unset($product['options_type']);
            unset($product['exceptions_type']);
            unset($product['details_layout']);
            unset($product['facebook_obj_type']);
            unset($product['category_ids']);
            unset($product['position']);
            unset($product['main_category']);
            unset($product['base_price']);
            unset($product['selected_options']);
            unset($product['has_options']);
            unset($product['product_options']);
        }

        $result['data'] = $result['data']['products'];

        return $result;
    }

}
