<?php

namespace app\api\controller;
use think\Request;
use catetree\Catetree;
use \think\File;
use app\api\controller\Bases;
/**
 * 城市接口
 * API：域名+api/city
 * 传入参数需传递给接口 无
 * 传入参参数：无
 * 返回状态码 无
 * 返回错误状态码 无
 * 返回全部城市数据 以json格式返回
 */

/*
 * token 返回错误代码
 * 10001 未接受到token
 * 10002 无token此用户
 * */
class City extends Bases
{
    public function index(Request $request)
    {
         $cateCity=getCitys();
        $callBack = input('callBack');
//        return json_encode($cateCity,JSON_FORCE_OBJECT);
//       return $this->ajaxReturn (json_encode($cateCity,JSON_FORCE_OBJECT),'JSONP');
        $data =  json_encode($cateCity);
        return jsonp($data,$callBack);
    }
}