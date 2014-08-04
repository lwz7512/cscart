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

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_wishlist_fill_user_fields(&$exclude)
{
    $exclude[] = 'wishlist';
}

//
//
//
function fn_wishlist_get_gift_certificate_info(&$_certificate, &$certificate, &$type)
{
    if ($type == 'W' && is_numeric($certificate)) {
        $_certificate = fn_array_merge($_SESSION['wishlist']['gift_certificates'][$certificate], array('gift_cert_wishlist_id' => $certificate));
    }
}

/**
 * reload wishlist
 *
 * @param $auth
 * @param $user_info
 * @param $first_init
 * @return bool
 */
function fn_wishlist_user_init(&$auth, &$user_info, &$first_init)
{
//    FIXME, remove first_init flag....for realtime load wishlist from database;

//    if ($first_init == true) {
        $user_id = $auth['user_id'];
        $user_type = 'R';
        if (empty($user_id) && fn_get_session_data('cu_id')) {
            $user_id = fn_get_session_data('cu_id');
            $user_type = 'U';
        }

        if(class_exists("PC")){
            PC::debug("user_init...", "wishlist");
        }

        fn_extract_cart_content($_SESSION['wishlist'], $user_id, 'W', $user_type);

        return true;
//    }

//    return false;
}

/**
 * called by login
 *
 * @param $sess_data
 * @param $user_id
 * @return bool
 */
function fn_wishlist_init_user_session_data(&$sess_data, &$user_id)
{
    if (AREA == 'C') {
        if (empty($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array(
                'products' => array()
            );
        }
        fn_extract_cart_content($sess_data['wishlist'], $user_id, 'W');
        fn_save_cart_content($_SESSION['wishlist'], $user_id, 'W');

    }

    return true;
}

function fn_wishlist_sucess_user_login($udata, $auth)
{
    if (AREA == 'C') {
        if ($cu_id = fn_get_session_data('cu_id')) {
            fn_clear_cart($cart);
            fn_save_cart_content($cart, $cu_id, 'W', 'U');
        }
    }
}

function fn_wishlist_pre_add_to_cart(&$product_data, &$cart, &$auth, &$update)
{
    $wishlist = & $_SESSION['wishlist'];

    if (!empty($wishlist['products'])) {
    foreach ($wishlist['products'] as $key => $product) {
        if (!empty($product['extra']['custom_files'])) {
        foreach ($product['extra']['custom_files'] as $option_id => $files) {
            if (!empty($files)) {
            foreach ($files as $file) {
                $product_data['custom_files']['uploaded'][] = array(
                    'product_id' => $key,
                    'option_id' => $option_id,
                    'path' => $file['path'],
                    'name' => $file['name'],
                );
            }
            }
        }
        }
    }
    }
}

//
// Add possibility to retrieve the wishlist products form user_sessions_products
//
// @param array $type_restrictions allowed types
// @return no return value
//
function fn_wishlist_get_carts(&$type_restrictions)
{
    if (is_array($type_restrictions)) {
        $type_restrictions[] = 'W';
    }
}

function fn_wishlist_get_additional_information(&$product, &$products_data)
{
    $_product = reset($products_data['product_data']);
    if (isset($product['product_id']) && isset($_product['product_id']) && $product['product_id'] == $_product['product_id'] && isset($_product['object_id'])) {
        $product['product_id'] = $product['object_id'] = $_product['object_id'];
    }
}

/**
 * Gets wishlist items count
 *
 * @return int wishlist items count
 */
function fn_wishlist_get_count()
{
    $wishlist = array();
    $result = 0;

    if (!empty($_SESSION['wishlist'])) {
        $wishlist = & $_SESSION['wishlist'];
        $result = !empty($wishlist['products']) ? count($wishlist['products']) : 0;
    }

    /**
     * Changes wishlist items count
     *
     * @param array $wishlist wishlist data
     * @param int   $result   wishlist items count
     */
    fn_set_hook('wishlist_get_count_post', $wishlist, $result);

    return empty($result) ? -1 : $result;
}

/**
 * Add product to wishlist
 *
 * @param array $product_data array with data for the product to add)(product_id, price, amount, product_options, is_edp)
 * @param array $wishlist wishlist data storage
 * @param array $auth user session data
 * @return mixed array with wishlist IDs for the added products, false otherwise
 */
function fn_add_product_to_wishlist($product_data, &$wishlist, &$auth)
{
    // Check if products have cusom images
    list($product_data, $wishlist) = fn_add_product_options_files($product_data, $wishlist, $auth, false, 'wishlist');

    fn_set_hook('pre_add_to_wishlist', $product_data, $wishlist, $auth);

    if (!empty($product_data) && is_array($product_data)) {
        $wishlist_ids = array();
        foreach ($product_data as $product_id => $data) {
            if (empty($data['amount'])) {
                $data['amount'] = 1;
            }
            if (!empty($data['product_id'])) {
                $product_id = $data['product_id'];
            }

            if (empty($data['extra'])) {
                $data['extra'] = array();
            }

            // Add one product
            if (!isset($data['product_options'])) {
                $data['product_options'] = fn_get_default_product_options($product_id);
            }

            // Generate wishlist id
            $data['extra']['product_options'] = $data['product_options'];
            $_id = fn_generate_cart_id($product_id, $data['extra']);

            $_data = db_get_row('SELECT is_edp, options_type, tracking FROM ?:products WHERE product_id = ?i', $product_id);
            $data['is_edp'] = $_data['is_edp'];
            $data['options_type'] = $_data['options_type'];
            $data['tracking'] = $_data['tracking'];

            // Check the sequential options
            if (!empty($data['tracking']) && $data['tracking'] == 'O' && $data['options_type'] == 'S') {
                $inventory_options = db_get_fields("SELECT a.option_id FROM ?:product_options as a LEFT JOIN ?:product_global_option_links as c ON c.option_id = a.option_id WHERE (a.product_id = ?i OR c.product_id = ?i) AND a.status = 'A' AND a.inventory = 'Y'", $product_id, $product_id);

                $sequential_completed = true;
                if (!empty($inventory_options)) {
                    foreach ($inventory_options as $option_id) {
                        if (!isset($data['product_options'][$option_id]) || empty($data['product_options'][$option_id])) {
                            $sequential_completed = false;
                            break;
                        }
                    }
                }

                if (!$sequential_completed) {
                    fn_set_notification('E', __('error'), __('select_all_product_options'));
                    // Even if customer tried to add the product from the catalog page, we will redirect he/she to the detailed product page to give an ability to complete a purchase
                    $redirect_url = fn_url('products.view?product_id=' . $product_id . '&combination=' . fn_get_options_combination($data['product_options']));
                    $_REQUEST['redirect_url'] = $redirect_url; //FIXME: Very very very BAD style to use the global variables in the functions!!!

                    return false;
                }
            }

            $wishlist_ids[] = $_id;
            $wishlist['products'][$_id]['product_id'] = $product_id;
            $wishlist['products'][$_id]['product_options'] = $data['product_options'];
            $wishlist['products'][$_id]['extra'] = $data['extra'];
            $wishlist['products'][$_id]['amount'] = $data['amount'];
        }

        return $wishlist_ids;
    } else {
        return false;
    }
}

/**
 * Delete product from the wishlist
 *
 * @param array $wishlist wishlist data storage
 * @param int $wishlist_id ID of the product to delete from wishlist
 * @return mixed array with wishlist IDs for the added products, false otherwise
 */
function fn_delete_wishlist_product(&$wishlist, $wishlist_id)
{
    fn_set_hook('delete_wishlist_product', $wishlist, $wishlist_id);

    if (!empty($wishlist_id)) {
        unset($wishlist['products'][$wishlist_id]);
    }

    return true;
}
