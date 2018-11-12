<?php
	
namespace app\bis\controller;
use think\Controller;
use think\Request;
	/**
	* 公共Controller
	*/
	class Base extends controller
	{
		public function _initialize()
		{
			//判断用户是否登入
		if(!session('bid')){
            return $this->error('您没有登陆',url('Login/index'));
        }

		}
	}