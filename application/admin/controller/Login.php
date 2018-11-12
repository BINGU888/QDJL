<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Controller;
use think\Request;
/**
 * 登录模块
 */
class Login extends Controller
{
    //登录
    public function index()
    {
        return view('login/index');
    }
    public function logins(Request $request)
    {
        $name = input('name');
        $password = md5(input('password'));
        $res = Model('Auth')->where('name',$name)->find();
            if(empty($res))
            {
                return $this->error('账号或密码错误！');
            }

            if ($password != $res->password)
            {
                return $this->error('账号或密码错误！');
            }
        session('aid',$res->id);
        return $this->success('登入成功','Index/index');
    }
}