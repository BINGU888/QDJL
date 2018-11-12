<?php

namespace app\api\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;use app\api\controller\Bases;
/**
 * 用户接口
 * API：域名+api/user
 * 传入参参数：无
 * 返回状态码 无
 * 返回错误状态码 无
 * 返回全部用户数据 返回json格式
 */
class User extends Bases
{
    public function index()
    {
        $res = Model('User')->select();
        $data = json_encode($res,JSON_FORCE_OBJECT);
        return jsonp($data);
    }
}