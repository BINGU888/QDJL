<?php

namespace app\api\controller;
use think\Request;
use think\Controller;

class Bases extends Controller
{
    /*
 * 判断当前是否存在token
 * token 不存在则返回状态码
 * token 返回错误代码
 * 10001 未接受到token
 * 10002 无token此用户
 * */
    public $user;

    public function __construct()
    {
        self::token();
    }

    public static function token()
    {
        //判断当前是否存在token
//        $request = Request::instance();

        $token = input('token');

        if (empty($token))
        {

            print_r('10001');die;

        }
        if (!empty($token)){
            $data = Model('User')->where('token',$token)->find();
            if (empty($data))
            {
                $code = ['code'=>'10002'];
                print_r('10002');die;
            }
        }
    }

}