<?php
	
namespace app\bis\controller;
use think\Controller;
use think\Request;
use app\bis\controller\Base;
	/**
	* 商户首页
	*/
	class Index extends Base
	{
		public function index()
		{
			// if (empty(session('id'))) {
			// 	// return redirect('login/index');
			// }
			// sesions();

			return view('index/index');
		}
		public function desktop()
		{
			//桌面视图
			return view('public/welcome');
		}
	}