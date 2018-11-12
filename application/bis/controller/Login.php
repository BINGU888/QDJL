<?php
	
namespace app\bis\controller;
use think\Controller;
use think\Request;
	/**
	* 商户登录模块
	*/
	class Login extends Controller
	{
		public function index()
		{
			return view('login/index');
		}
		public function login(Request $request)
		{
			//登录逻辑
			$name = input('name');
			$password =input('password');
			if (empty($name)) {
				return $this->error('用户名不可为空！');
			}
			if (empty($password)) {
				return $this->error('密码不可为空!');
			}
//			//判断当前验证码是否正确
//			$captcha = input('captcha');
//			if(!captcha_check($captcha)){
//			 return $this->error('验证码错误');
//			};

			$res = Model('Buser')->where('name',$name)->find();
			if (empty($res)) {
				//查看邮箱是否存在
				$res = Model('Buser')->where('email',$name)->find();
				if (empty($res)) {
					return $this->error('账号不存在');
				}
			}
			//判断密码是否正确
			$password = md5($password);
//            dump($res);die;

			if ($password != $res->password) {
				return $this->error('账号或密码错误');
			}

			//将当前用户id存储在session中
			
			session('bid',$res->id);

			return $this->success('登录成功','index/index');
			
		}
	}