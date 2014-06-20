<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-18
 * Time: 下午9:04
 */

namespace Tygh\Api\Entities\v10;

use Tygh\Api\Response;

class Auth extends \Tygh\Api\Entities\Auth {


    public function create($params){

        $user_id = '';
        $result = 0;


        if(isset($params['email'])){
            $email = $params['email'];
        }

        if(isset($params['password'])){
            $password = $params['password'];
        }

        if(!empty($email) && !empty($password)){
            $user_data = db_get_row("SELECT * FROM ?:users WHERE email = ?s", $email);

            if(empty($user_data)){//no user found
                return array(
                    'status' => Response::STATUS_OK,
                    'data' => array(
                        'result' => $result,
                        'user_id' => $user_id
                    )
                );
            }


            $salt = $user_data['salt'];
            //password validate
            $valid = (fn_generate_salted_password($password, $salt) == $user_data['password']);
            if(!empty($user_data) && $valid){
                $result = 1;
                $user_id = $user_data['user_id'];
            }else{
                $result = 0;
            }
        }

        return array(
            'status' => Response::STATUS_OK,
            'data' => array(
                'result' => $result,
                'user_id' => $user_id
            )
        );

    }


} 