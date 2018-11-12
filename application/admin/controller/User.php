<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
/**
 * 用户模块
 */
class User extends Base
{
    //显示
    public function index(Request $request)
    {
        $res = Model('User')->where('status','0')->select();
        $this->assign([
            'res'=>$res,
        ]);
        return view('user/index');
    }
    //渲染修改页面
    public function exits(Request $request)
    {
        $id = input('id');
        $res = Model('User')->where('id',$id)->find();
        $this->assign([
            'res'=>$res,
        ]);
        return view('user/exits');
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
        $res = Model('User')->where('id',$id)->update($data);
            if ($res){
                return $this->success('修改成功');
            }else{
                return $this->error('修改失败');
            }

    }
    public function delete(Request $request)
    {
        $id = input('id');
        if (empty($id))
        {
            return $this->error('非法操作');
        }
        $res = Model('User')->where('id',$id)->update(['status'=>'1']);
        if ($res){
            return $this->success('封号成功');
        }else{
            return $this->error('封号失败');
        }
    }
    //展示已删除的用户
    public function delet(Request $request)
    {
        $res = Model('User')->where('status','1')->select();
        $this->assign([
            'res'=>$res,
        ]);
        return view('user/delet');
    }
    //解封用户
    public function status(Request $request)
    {
        $id = input('id');
        $status = input('status');
        $res = Model('User')->where('id',$id)->update(['status'=>$status]);
        if ($res){
            return $this->success('解封成功');
        }else{
            return $this->error('解封失败');
        }
    }

}