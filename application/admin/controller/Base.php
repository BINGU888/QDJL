<?php
	
namespace app\admin\controller;
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
            if(!session('aid')){
                return $this->error('您没有登陆','Login/index');
            }

        }
	}