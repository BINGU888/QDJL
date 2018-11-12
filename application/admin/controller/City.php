<?php
	
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;
	/**
	* 城市模块
	*/
	class City extends Base
	{
		public function index()
		{
			$cateRes = Model('City')->select();
  	        $cateRes = collection($cateRes)->toArray();
			// $cate=new Catetree();
			$cateRes=getTrees($cateRes);
			// echo "<pre>";
			// print_r($cateRes);
	        $this->assign([
	            'cateRes'=>$cateRes,
	            ]);
	        echo '<pre>';
	        print_r($cateRes);die;
			return view('city/index');
		}
	}