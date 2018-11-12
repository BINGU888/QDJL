<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
use \think\File;
use app\common\Message;
class Registered extends Base
{
	/*
		注册模块
	*/
	public function index()
	{
		//逻辑
		//获取 三级分类
		$cateRes = getTrees();

		//显示视图 渲染
		$this->assign([
			'cateRes'=>$cateRes,
		]);
		return view('registered/index');
	}
	//注册逻辑
	public function add(Request $request)
	{	
		//获取表单传递过来的数据 进行校验
		$data = input('post.');
	    //判断当前用户名是否存在
        $name = Model('User')->where('name',$data['name'])->find();
        if (!empty($name))
        {
            return $this->error('用户名已存在');
        }

        $phone = Model('User')->where('phone',$data['phone'])->find();
        if (!empty($phone)){
            return $this->error('当前手机号已存在');
        }

      	//校验数据
     	$validate=validate("Merchantvalidate"); //使用验证
     		if(!$validate->scene("user")->check($data)){
                $this->error($validate->getError());//内置错误返回
            }

            //判断密码与确认密码是否相同
         	if ($data['passwords'] != $data['password']) {
      			return $this->error('密码或确认密码不正确');		
            }
            //验证码校验
			if (session('code') !=$data['code']) {
	        	return $this->error('验证码错误');
	      	}
	      	//密码加密
	      	$data['password'] = md5($data['password']);
          	unset($data['passwords'],$data['code'],$data['agree']);
          	$data['starttime'] = time();
          	$data['pointtime'] = time();
        	$res = Model('User')->insert($data);
			if ($res) {
				return $this->success('注册成功','login/index');
			}else{
				return $this->error('注册失败');
			}
	}
}