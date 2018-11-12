<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
use \think\File;
use app\common\Message;
class Login extends Base
{
	//用户登入模块
	public function index()
	{
		//逻辑
		//获取三级分类
		$cateRes = getTrees();
		//渲染视图

		$this->assign([
			'cateRes'=>$cateRes,
		]);
		return view('login/index');
	}
	public function login(Request $request)
	{
		$data = input('post.');
		//验证码校验
		// $captcha = input('captcha');
		// 	if(!captcha_check($captcha)){
		// 		 return $this->error('验证码错误');
		// 	};
			if (empty($data['name'])) {
				return $this->error('请输入账号');
			}
		//校验 是否存在当前用户账号 邮箱 用户名 手机号
		$res = Model('User')->where('name',$data['name'])->find();
			if (empty($res)) {
				$res = Model('User')->where('phone',$data['name'])->find();
				if (empty($res)) {
					$res = Model('User')->where('email',$data['name'])->find();
					if (empty($res)) {
						return $this->error('用户名或密码错误！');
					}
				}
			}
			//判断密码是否正确
		$data['password'] = md5($data['password']);
			if ($res->password != $data['password']) {
				return $this->error('账号或密码错误！');
			}


		//存储用户id
		session('id',$res->id);
		session('uname',$res->name);
		//跳转首页
		$settlement = cookie('settlements');

		if (!empty($settlement)) {
			return $this->redirect($settlement);
		}
		return $this->success('登入成功','Index/index');
	}

}