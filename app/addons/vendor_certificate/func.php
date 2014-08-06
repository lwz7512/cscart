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

/**
 * get all the certificates
 *
 * @return array
 */
function fn_get_certificates() {

    $sql = "SELECT t.u_id, t.vendor_id, c.company, t.certificate, t.grade, t.credits, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.create_time),'%m/%d/%Y') as create_time, ";
    $sql .= "DATE_FORMAT(FROM_UNIXTIME(t.timestamp),'%m/%d/%Y') as timestamp ";
    $sql .= "FROM ?:vendor_quality t ";
    $sql .= "LEFT JOIN ?:companies c ON t.vendor_id = c.company_id ";
    $sql .= "ORDER BY t.timestamp DESC ";

    $certificates = db_get_array($sql);

    return $certificates;

}

/**
 * get one vendor certificate to update
 *
 * @param $u_id
 * @return array
 */
function fn_get_one_certificate($u_id) {
    $sql = "SELECT t.u_id, t.vendor_id, c.company as vendor_name, t.certificate, t.grade, t.credits ";
    $sql .= "FROM ?:vendor_quality t ";
    $sql .= "LEFT JOIN ?:companies c ON t.vendor_id = c.company_id ";
    $sql .= "WHERE t.u_id = ?i ";

    return db_get_row($sql, $u_id);
}

/**
 * add/update certificate
 *
 * @param int $u_id
 * @param $vendor_id
 * @param $certificate
 * @param $grade
 * @return bool
 */
function fn_update_certificate($u_id = 0, $vendor_id, $certificate, $grade, $credits=1, $create_time=TIME) {

    if(empty($u_id)){

        //add certificate already
        $exist_vendor = db_get_field("SELECT count(*) FROM ?:vendor_quality WHERE vendor_id = ?i", $vendor_id);

        if($exist_vendor > 0){
            return false;
        }

        db_query("INSERT INTO ?:vendor_quality ?e", array(
            'vendor_id' => intval($vendor_id),
            'certificate' => intval($certificate),
            'grade' => intval($grade),
            'credits' => $credits,
            'create_time' => $create_time,
            'timestamp' => TIME
        ));

        return true;
    }else{

        db_query("REPLACE INTO ?:vendor_quality ?e", array(
            'u_id' => intval($u_id),
            'vendor_id' => intval($vendor_id),
            'certificate' => intval($certificate),
            'grade' => intval($grade),
            'credits' => $credits,
            'create_time' => $create_time,
            'timestamp' => TIME
        ));

        return true;
    }

}

/**
 * delete one certificate
 *
 * @param $u_id
 * @return bool
 */
function fn_delete_certificate($u_id){
    $sql = "DELETE FROM ?:vendor_quality WHERE u_id = ?i";

    db_query($sql, $u_id);

    return true;
}