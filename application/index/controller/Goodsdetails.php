<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;

class Goodsdetails extends Base
{
	//商品详情
	public function index(Request $request)
	{
		//获取商品id
		$goodsid = input('goodsid');
		//根据商品id查询商品信息
		$goodsinformation = Model('Goods')->where('id',$goodsid)->find();
		//图片拆分
		$images = explode(',',$goodsinformation['image_url']);
		// //三级分类
		// foreach ($goodsinformation as $key => $value) {
		// echo  break_string($value['goods_name'],1,'\\n');
		// }
		//获取当前商户信息
		$buser = Model('Buser')->where('id',$goodsinformation->uid)->find();
		//获取当前相关产品
		$goods = Model('Goods')->where('uid',$goodsinformation->uid)->where('brandid',$goodsinformation->brandid)->limit('8')->select();
		$cateRes = getTrees();
		$this->assign([
			'goodsinformation'=>$goodsinformation,
			'cateRes'=>$cateRes,
			'images'=>$images,
			'buser'=>$buser,
			'goods'=>$goods,
		]);
		return view('goodsdetails/index');
	}

}