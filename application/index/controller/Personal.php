<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;
use DB;

class Personal extends Base
{
    //个人中心
    public function index(Request $request)
    {
        $id = session('id');
        $pay = input('pay');
        $status = input('status');
        $name = input('name');

            if (empty($id))
            {
                return $this->error('请前往登录','Login/index');
            }
            //查询未支付的商品
            if ($pay == 0){
                $res = Model('Usergoods')->where('uid',$id)->where('pay',$pay)->select();
                $ars = '1';
            }
            //查询待发货的商品
            if ($status == '0')
            {
                $res = Model('Usergoods')->where('uid',$id)->where('pay','1')->where('status',$status)->select();
                $ars = '2';

            }
            //待收货的商品
            if ($status =='1')
            {
                $res = Model('Usergoods')->where('uid',$id)->where('status','1')->select();
                $ars = '3';
            }
            //查询已完成的商品
            if ($status == '2')
            {
                $res = Model('Usergoods')->where('uid',$id)->where('status','2')->select();
                $ars = '4';
            }
            if ($pay!='0' && empty($status) && $status !='0') {
                //查询当前用户全部商品
                $res = Model('Usergoods')->where('uid', $id)->where('status','<','3')->select();
                $ars = '0';

            }
            if (!empty($name)){
                $res = Model('Usergoods')->where('uid',$id)->where('order',$name)->select();
            }
            if (!empty($res)) {
                foreach ($res as $k => $v) {
                    $gid[] = $v['gid'];
                }
                //获取用户商品数据详情
                $goodsres = Model('Goods')->where('id', 'in', $gid)->select();
                //查询当前用户全部商品数据
                foreach ($goodsres as $k => $v) {
                    $uid[] = $v['uid'];
                }
                $buser = Model('Buser')->where('id', 'in', $uid)->select();
            }else{
                $goodsres =null;
                $buser = null;
            }

        //三级分类数据
        $cateRes=getTrees();
        $this->assign([
            'cateRes'=>$cateRes,
            'res'=>$res,
            'goodsres'=>$goodsres,
            'buser'=>$buser,
            'ars'=>$ars,
        ]);
        return view('personal/index');
    }
    //确认收货
    public function status(Request $request)
    {
        $status['status'] = input('status');
        $id = input('id');
        $res = Model('Usergoods')->where('id',$id)->update($status);
            if ($res)
            {
                return $this->success('确认成功');
            }else{
                return $this->error('确认失败');
            }
    }
    public function delete(Request $request)
    {
        $id = input('id');
        $uid = session('id');
        $res = Model('Usergoods')->where('id',$id)->where('uid',$uid)->update(['status'=>'3']);
            if ($res)
            {
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
    }

    //修改密码
    public function center()
    {
        $id = session('id');
        if(empty($id)){
            return $this->error('请前去登录','Login/index');
        }

        //三级分类数据
        $cateRes=getTrees();
        $this->assign([
            'cateRes'=>$cateRes,
        ]);
        return view('Personal/center');
    }
    public function passwordsave(Request $request)
    {
        //原始密码
        $passwor  = input('passwor');
        //新密码
        $password = input('password');
        //确认密码
        $passwords = input('passwords');
        $id = session('id');
        if(empty($id)){
            return $this->error('请前去登录','Login/index');
        }
            if (empty($passwor)){
                return $this->error('原始密码不可为空');
            }
            if (empty($password)){
                return $this->error('新密码不可为空');
            }
            if (empty($passwords)){
                return $this->error('确认密码不可以为空');
            }
            if ($password != $passwords)
            {
                return $this->error('新密码与确认密码不匹配');
            }

            $date = Model('User')->where('id',$id)->find();

            $passwor = md5($passwor);
            if ($passwor != $date->password)
            {
                return $this->error('原始密码不正确');
            }

            $password = md5($password);
            //更新此账号密码
            $res = Model('User')->where('id',$id)->update(['password'=>$password]);
            if ($res)
            {
                session('id',null);
                return $this->success('修改成功','Login/index');
            }else{
                return $this->error('修改失败');
            }
    }
    //获取当前用户是否是商户然后跳转到商户后台
    public function isbis()
    {
        $id = session('id');
        $res = Model('User')->where('id',$id)->find();
        if (empty($res->sid))
        {
            return $this->error('亲您暂无权限');
        }
        session('bid',$res->sid);
        return $this->redirect('bis/index/index');
    }

}