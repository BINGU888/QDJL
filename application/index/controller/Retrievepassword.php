<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
use \think\File;
use app\common\Message;
//找回密码模块
class Retrievepassword extends Base
{
    //显示找回页面
        public function index()
        {
            $cateRes = getTrees();
            $this->assign([
               'cateRes'=>$cateRes,
            ]);

            return view('retrievepassword/index');
        }
        public function update(Request $request)
        {
            $data = input('post.');
            //判断当用户是否输入手机号
            if (empty($data['phone']))
            {
                return $this->error('请填写手机号');
            }
            if (empty($data['password']) && empty($data['passwords']))
            {
                return $this->error('新密码或确认密码未填写');
            }
            if($data['password'] != $data['passwords'])
            {
                return $this->error('新密码与确认新密码不一致');
            }
            if(empty($data['code']))
            {
                return $this->error('验证码不可为空');
            }
            if (session('code') !=$data['code']) {
                return $this->error('验证码错误');
            }
            //根据手机号进行查询用户
            $date = Model('User')->where('phone',$data['phone'])->find();
            //判断用户输入的新密码与确认新密码是否一致
            //判断当前用户是否存在
            if (empty($date)){
                return $this->error('暂无当前用户');
            }
            //判断当前验证码是否一致


            //给当前用户输入的密码加密
            $password =  md5($data['password']);
            if ($password == $date['password'])
            {
                return $this->error('新密码不可与原密码一致!');
            }
            //修改当前用户密码
            $res = Model('User')->where('id',$date->id)->update(['password'=>$password]);
            if ($res){
                return $this->success('修改密码成功','Login/index');
            }else{
                return $this->error('修改密码失败');
            }
        }


}