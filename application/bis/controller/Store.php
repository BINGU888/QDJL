<?php
	
namespace app\bis\controller;
use think\Controller;
use think\Request;
use app\bis\controller\Base;
	/**
	* 商户店铺首页
	*/
	class Store extends Base
	{
		//店铺展示
		public function index()
		{
			
		}

		public function add(Request $request)
		{
			//店铺添加内容
			$id = session('bid');
			$res = Model('Store')->where('uid',$id)->find();
			if (empty($res)) {
				$res = null;
			}else{
				//图片拆分
				$images = explode(',',$res['images']);
			}

			if (empty($images)) {
				$images = null;
			}
			$this->assign([
				'res'=>$res,
				'images'=>$images,
			]);
			return view('store/index');
		}
		//处理业务逻辑
		public function adds(Request $request)
		{
			$id = session('bid');
			$ret = Model('Store')->where('uid',$id)->find();

			if (!empty($ret)) {
				$data = input('post.');
				$data['uid'] = $id;
				if (empty($data['images'])) {
					unset($data['images']);
				}

				$res = Model('Store')->where('uid',$id)->update($data);
				if ($res) {
					return $this->success('发布成功');
				}else{
					return $this->error('发布失败');
				}
			}else{
				$data = input('post.');
				$data['uid'] = $id;
				$res = Model('Store')->insert($data);
				if ($res) {
					return $this->success('发布成功');
				}else{
					return $this->error('发布失败');
				}
			}
		}

		/*
		*商户分类模块
		*
		*/
		//展示视图
		public function trees()
		{	
			$id = session('bid');
			//判断当前商户是否存在分类
			$res = Model('Storetree')->where('uid',$id)->select();
			if (empty($res)) {
				$tree = null;
			}else{
				$tree = Model('Catetree')->select();
			}
			//三级分类数据
			$cateRes = getTrees();
			$this->assign([
				'cateRes'=>$cateRes,
				'res' =>$res,
				'tree'=>$tree, 
			]);
			//分类
			return view('store/trees');
		}
		//添加分类
		public function tressadd(Request $request)
		{
			$onepid = input('onepid');
			$towpid = input('towpid');
			$threepid = input('threepid');
			$id = session('bid');
			$date = input('post.');
			
			if (empty($onepid) || $onepid=='undefined') {
				return $this->error('请选择一级分类');
			}
			if (empty($towpid) || $towpid=='undefined') {
				return $this->error('请选择二分类');
			}
			if (empty($threepid) || $threepid=='undefined') {
				return $this->error('请选择三级分类');
			}
			$date['uid'] = $id;
			//查询数据库判断商户是否已添加
			$data = Model('Storetree')->where('uid',$id)->where('onepid',$onepid)->where('towpid',$towpid)->where('threepid',$threepid)->find();
			if (!empty($data)) {
				return $this->success('该分类已存在');die;
			}else{

				$res=Model('Storetree')->insert($date);
				if ($res) {
					return $this->success('添加成功');
				}else{
					return $this->error('添加失败');
				}
			}

		}

		//状态修改 0 发布中 1未发布
		public function status(Request $request)
		{
			$id = input('id');
			if (empty($id)) {
				return $this->error('非法操作');
			}
			$date=Model('Storetree')->where('id',$id)->find();
			if ($date->status == 0) {
				$status['status'] = 1;
			}
			if ($date->status == 1) {
				$status['status'] = 0;
			}
	
			$res = Model('Storetree')->where('id',$id)->update($status);
			if ($res) {
				return $this->success('操作成功');
			}else{
				return $this->error('操作失败');
			}
		}

		//删除分类
		public function delete(Request $request)
		{
			$uid = session('bid');
			$id = input('id');
			$res = Model('Storetree')->where('uid',$uid)->where('id',$id)->delete();
			if ($res) {
				return $this->success('删除成功');
			}else{
				return $this->error('删除失败');
			}
		}
		//未推荐商品渲染
		public function goods()
		{
			$id = session('bid');
			$data = Model('Storetree')->where('uid',$id)->select();
			foreach ($data as $key => $value) {
				$pid[] = $value['threepid'];
			}
            if (!empty($pid)) {
                $res = Model('Goods')->where('uid', $id)->where('pid', 'in', $pid)->where('storestatus', '0')->select();
            }else
                {
                    $res =null;
                }
			$this->assign([
				'res'=>$res,
			]);
			return view('store/goodsindex');
		}
		//分类推荐
		public function recommended(Request $request)
		{
			$id = input('id');
			$status['storestatus'] = input('storestatus');
			
			$res = Model('Goods')->where('id',$id)->update($status);

			if ($res) {
				return $this->success('推荐成功');
			}else{
				return $this->error('推荐失败');
			}
		}
		// //促销商品
		// public function promotion(Request $request)
		// {
		// 	$id = input('id');
		// 	$status['storestatus'] = '2';
		// 	$res = Model('Goods')->where('id',$id)->update($status);
		// 	if ($res) {
		// 		return $this->success('推荐成功');
		// 	}else{
		// 		return $this->error('推荐失败');
		// 	}

		// }

		//已推荐
		public function goodsrecommended()
		{
			$id = session('bid');
			$data = Model('Storetree')->where('uid',$id)->select();
			foreach ($data as $key => $value) {
				$pid[] = $value['threepid'];
			}
           if (!empty($pid)){
			$res = Model('Goods')->where('uid',$id)->where('pid','in',$pid)->where('storestatus','>','0')->select();
           }else{
               $res =null;
           }
			$this->assign([
				'res'=>$res,
			]);
			return view('store/goodsrecommended');
		}



	}