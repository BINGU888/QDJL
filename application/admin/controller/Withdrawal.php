<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
/**
 * 用户模块
 */
class Withdrawal extends Base
{
    //未打款渲染页面
    public function index()
    {
        $res = Model('Amount')->where('status','0')->select();
        $awal =Model('Withdrawal')->select();
        $buser = Model('Buser')->select();
        $this->assign([
            'res'=>$res,
            'awal'=>$awal,
            'buser'=>$buser,
        ]);
        return view('withdrawal/index');
    }
    //已打款页面渲染
    public function have(Request $request)
    {
        $res = Model('Amount')->where('status','1')->select();
        $awal =Model('Withdrawal')->select();
        $buser = Model('Buser')->select();
        $this->assign([
            'res'=>$res,
            'awal'=>$awal,
            'buser'=>$buser,
        ]);
        return view('withdrawal/have');
    }

    //已打款逻辑
    public function status(Request $request)
    {
        $id = input('id');
        $status['status']= input('status');
        $res = Model('Amount')->where('id',$id)->update($status);
        if ($res){
            return $this->success('更新成功');
        }else{
            return $this->error('更新失败');
        }
    }
}