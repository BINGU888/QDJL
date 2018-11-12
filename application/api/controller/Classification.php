<?php

namespace app\api\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;
use app\api\controller\Bases;
/**
 * 分类接口
 * API：域名+api/classification
 * 传入参数需传递给接口 无
 * 传入参参数：无
 * 返回状态码 无
 * 返回错误状态码 无
 * 返回全部分类 以json格式返回
 */
class Classification extends Bases
{
    public function index(){
        $cateRes = getTrees();
        $data =  json_encode($cateRes, JSON_FORCE_OBJECT);
        return jsonp($data);
    }
}