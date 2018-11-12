<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
class Positioning extends Controller
{
	public function index()
	{
		//获取全部城市
		$city = Model('City')->select();
		$letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
		$data=[];
		foreach ($city as $key => $value) {
				foreach($letters as $k=>$v)
	
				if ($v ==$value['letter']) {
					$data[$v][$value['id']]['name']=[];
					$data[$v][$value['id']]['name'] = $value['name'];
					}	
				}


		//三级分类
		$cateRes = getTrees();
		$this->assign([
			'cateRes'=>$cateRes,
			'data' =>$data,
		]);
		return view('Positioning/index');
	}
}