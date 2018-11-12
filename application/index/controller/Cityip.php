<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class Cityip extends Controller
{
	//获取当前用户所在城市
	public function cityip()
	{
		if (!session('cityid')) {
			# code...
		
		//获取当前ip
		// $ip = $request->ip();
		$city = get_ip_city('39.71.83.117');
		$res = Model('City')->where('name',$city)->find();
		if (empty($res)) {
			session('cityid','370100');
		}else{
			//存储当前城市id
			session('cityid',$res->id);
		}
		
		}

		return session('cityid');

	}
}