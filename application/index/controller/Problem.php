<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class Problem extends Controller
{
    //展示常见问题
    public function index()
    {
        $res = Model('Information')->where('status','1')->select();
        //三级分类数据
        $cateRes=getTrees();
        $this->assign([
           'res'=>$res,
           'cateRes'=>$cateRes,
        ]);
        return view('problem/index');
    }
    public function show(Request $request)
    {
        $id  = input('id');
        $res = Model('Information')->where('id')->find();
    }
}