<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;
class Information extends Base
{
    //新闻咨询
    public function index()
    {
        //查询全部
        $res = Model('Information')->where('status','0')->select();
        //三级分类数据
        $cateRes=getTrees();
        $this->assign([
           'cateRes'=>$cateRes,
            'res'=>$res,
        ]);
        return view('information/index');
    }
    //资讯内容
    public function show(Request $request)
    {
        $id = input('id');
       $res = Model('Information')->where('id',$id)->find();
        //三级分类数据
        $cateRes=getTrees();
       $this->assign([
           'res'=>$res,
           'cateRes'=>$cateRes,
       ]);
       return view('information/show');
    }
}