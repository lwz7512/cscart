<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-8-1
 * Time: 下午12:02
 */


function fn_vendor_certificate_get_company_data_post($company_id, $lang_code, $extra, &$company_data) {

    $certificate = db_get_field("SELECT certificate FROM ?:vendor_quality WHERE vendor_id = ?i", $company_id);

    if(!empty($certificate)){
        $company_data['certificate'] = intval($certificate);
    } else {
        $company_data['certificate'] = 0;
    }

}