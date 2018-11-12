<?php

namespace app\api\controller;
use app\admin\controller\Base;
use think\Controller;
use think\Request;
use catetree\Catetree;
use \think\File;
use think\Db;
use app\api\controller\Bases;

/**
 * 商家列表接口
 * API：域名+api/buser
 * 传入参数需传递给接口 cityid（必须） pid（必须） brandid（可为空） pages(默认为1)
 * 1传入参数 1.cityid('市级id') pid（三级分类id） brandid（品牌id） pages(分页+1传递)；
 * 2.返回状态码 无
 * 3.返回错误状态码 状态码 1001（'城市id未获取到'）;
 * 4.返回条件商户数据 以json格式返回 重点（’字段 level = 0 vip商户 level= 1 svip商户 ，level=2 普通商户‘）
 * 5.功能讲解 传入城市 cityid城市id 与pid三级分类id 会根据pages 会调取当前三级分类下的所有商户的
 *           传入城市 cityid城市id 与pid三级分类id ,brandid品牌id 会根据pages 会调取当前品牌下的所有商户的
 */
class Buser extends Bases
{
    //获取全部商户
    public function index(Request $request)
    {

        $cityid = input('cityid');
        $pid = input('pid');
        $brandid = input('brandid');
        $pages = input('pages');
        //判断token 是否存在
        $token = input('token');
        if (empty($token))
        {
            $code = ['code','10001'];
            return json_encode($code);
        }
        if (empty($pid))
        {
            $pid = 12;
        }

        if (empty($cityid))
        {
            $data= ['code'=>'1001'];
            return json_encode($data);
        }

        if (empty($pages))
        {
            $pages = 1;
        }
        if ($pages<1){
            $pages = 1;
        }

        //获取品牌
        $brand = Model('Brand')->where('cid',$pid)->select();
            if (empty($brandid))
            {

                //查找svip用户
                $svip = Model('Buservip')->svip($pid,1,$cityid);
                //查询vip用户
                $vip = Model('Buservip')->svip($pid,0,$cityid);
                //查询普通用户
                $bid = Model('Buservip')->bid($pid,$cityid);

                $res = array_merge($svip,$vip,$bid);

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
                  //将品牌插入
                    $dates['brand']= $brand;
                    $data = json_encode($dates,JSON_FORCE_OBJECT);
                    return jsonp($data);
            }
        if (!empty($brand))
        {
            //获取当前品牌下svip用户
            $svip = Model('Buservip')->vips($brandid,1,$cityid);
            //查询当前vip用户
            $vip =  Model('Buservip')->vips($brandid,0,$cityid);
            //获取当前普通用户
            $bid = Model('Buservip')->bids($brandid,$cityid);
            $res = array_merge($svip,$vip,$bid);
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
            $dates['brand']= $brand;
           $data = json_encode($dates,JSON_FORCE_OBJECT);
           return jsonp($data);

        }
    }

    public function test()
    {
//        $data =['name'=>'姓名','phone'=>'手机号','email'=>'邮箱','password'=>'123','contactname','x','enterprisename'=>'12','address'=>'asd','provinceid'];
    }




}