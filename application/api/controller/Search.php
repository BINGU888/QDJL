<?php

namespace app\api\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;use app\api\controller\Bases;
/**
 * 搜索商家接口
 * API：域名+api/search
 * 传入参数需传递给接口 name（必填) pages(默认为1)
 * 传入参参数：name('公司名称|关键词') pages(分页数)；
 * 返回状态码
 * 返回错误状态码 code 1001 错误 内容为空
 */
class Search extends Bases
{
    public function index(Request $request)
    {
        $name = input('name');
        $pages = input('pages');
        if (empty($name))
        {
            $data = ['code'=>'1001'];
            return json_encode($data,JSON_FORCE_OBJECT);
        }
        $res = Model('Buser')->where('enterprisename','like','%'.$name.'%')->select();

        if (empty($res))
        {
            $res = Model('Buser')->where('content','like','%'.$name.'%')->select();

        }

        if (empty($pages))
        {
            $pages = 1;
        }
        if ($pages<1){
            $pages = 1;
        }
        $limit =ceil(count($res)/20);
        //分页不可大过总页
        if ($pages>$limit)
        {
            $pages = $limit;
        }
        if ($pages>1) {
            $page = 20 * $pages;
            foreach ($res as $key => $value) {
                if ($key < $page && $key > $page - 21) {

                    $dates[] = $value;
                }
            }
        }else{
            $page = 20;
            foreach ($res as $k => $value) {
                if ($k < $page) {

                    $dates[] = $value;

                }
            }
        }

        if (empty($dates))
        {
            $dates = [];
        }
        $data = json_encode($dates,JSON_FORCE_OBJECT);
        return jsonp($data);
    }
}