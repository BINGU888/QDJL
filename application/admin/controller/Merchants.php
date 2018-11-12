<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
/**
 * 商家模块
 */
class Merchants extends Base
{
    public function index(Request $request)
    {
        //全部商家
        $res = Model('Buser')->where('status','0')->select();
        $this->assign([
            'res'=>$res,
        ]);
        return view('merchants/index');
    }
    public function titles()
    {
        //封号中的商家
        $res = Model('Buser')->where('status','1')->select();
        $this->assign([
            'res'=>$res,
        ]);
        return view('merchants/titles');
    }
    //渲染修改页
    public function exits(Request $request)
    {
        $id = input('id');
        $res = Model('Buser')->where('id',$id)->find();
        $this->assign([
            'res'=>$res,
        ]);
        return view('merchants/exits');
    }
    //修改逻辑
    public function update(Request $request)
    {
        $id = input('id');
        $data = input('post.');
        if (empty($data['password'])) {
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        $res = Model('Buser')->where('id',$id)->update($data);
        if ($res){
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }

    }

    //封号
    public function delete(Request $request)
    {
        $id = input('id');
        if (empty($id))
        {
            return $this->error('非法操作');
        }
        $res = Model('Buser')->where('id',$id)->update(['status'=>'1']);
        if ($res){
            return $this->success('封号成功');
        }else{
            return $this->error('封号失败');
        }
    }


    //解封用户
    public function status(Request $request)
    {
        $id = input('id');
        $status = input('status');
        $res = Model('Buser')->where('id',$id)->update(['status'=>$status]);
        if ($res){
            return $this->success('解封成功');
        }else{
            return $this->error('解封失败');
        }
    }


}