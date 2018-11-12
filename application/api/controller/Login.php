<?php

namespace app\api\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;
/**
 * 登录接口
 * API：域名+api/login
 * 传入参数需传递给接口 name（必须） password（必须
 * 传入参参数：name('用户名|手机号|邮箱') password('密码')
 * 返回状态码 code 200   正常 表示登入成功
 * 返回错误状态码 code 1002  错误 账号名或密码错误
 */
class Login
{
    public function index(Request $request)
    {
        $name = input('name');
        $password = input('password');
        $data = ['name'=>$name,'password'=>$password];
        if (empty($data['name'])) {
            $code = ['code'=>'1002'];
            return json_encode($code);
        }

        //校验 是否存在当前用户账号 邮箱 用户名 手机号
        $res = Model('User')->where('name',$data['name'])->find();
        if (empty($res)) {
            $res = Model('User')->where('phone',$data['name'])->find();
            if (empty($res)) {
                $res = Model('User')->where('email',$data['name'])->find();
                if (empty($res)) {
                    $code = ['code'=>'1002'];
                    return json_encode($code);
                }
            }
        }
        //判断密码是否正确
        $data['password'] = md5($data['password']);
        if ($res->password != $data['password']) {
            $code = ['code'=>'1002'];
            return json_encode($code);
        }

        if (empty($res->token))
        {
            $re = Model('User')->where('id',$res->id)->update(['token'=>$this::token()]);
        }
        $data = Model('User')->where('id',$res->id)->find();
        //存储用户id
        session('id',$data->id);
        session('uname',$data->name);
        $code = ['token'=>$data->token];
        return json_encode($code);
    }


    //生成token
    public static function token()
    {
        $str = md5(uniqid(md5(microtime(true)),true));  //生成一个不会重复的字符串
        $str = sha1($str);  //加密
        return $str;
    }
}