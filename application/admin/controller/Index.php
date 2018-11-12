<?php
	
namespace app\admin\controller;
use app\admin\controller\Base;
	/**
	* 首页模块
	*/
	class Index extends Base
	{
		public function index()
		{
			//显示视图
			return view('index/index');
		}
		public function desktop()
		{
			//桌面视图
			return view('public/welcome');
		}
	}