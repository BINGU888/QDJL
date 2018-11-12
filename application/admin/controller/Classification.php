<?php
	
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
	/**
	* 商品分类模块
	*/
	class Classification extends Base
	{
		public function index()
		{
			//逻辑
			$cateRes  =  Model('Catetree')->order('id')->select();
			$cate=new Catetree();
			$cateRes=$cate->catetree($cateRes);
			$cates  =  Model('Catetree')->count();

	        $this->assign([
	            'cateRes'=>$cateRes,
	            'cates'=>$cates,

	            ]);
			//显示分类视图
			return view('classification/index');
		}
		//添加商品分类
		public function add()
		{
			//逻辑
			$cateRes  =  Model('Catetree')->select();
			$cate=new Catetree();
			$cateRes=$cate->catetree($cateRes);
		
	        $this->assign([
	            'cateRes'=>$cateRes,
	            ]);

			//视图
			return view('classification/add');
		}
		public function adds(Request $request)
		{
			//获取当前分类id
			$pid = input('pid');
			$name = input('name');

			if (empty($pid)) {
				if ($pid !='0') {
					return $this->error('请选择分类');
				}
				
			}
			if (empty($name)) {
				return $this->error('请填写分类名称');
			}
			$data = ['pid'=>$pid,'name'=>$name];
			$res = Model('Catetree')->insert($data);
			if ($data) {
				return $this->success('添加分类成功');
			}else{
				return $this->error('添加失败');
			}
		}

		public function exitse(Request $request)
		{
			$id = input('id');
			$cateRes  =  Model('Catetree')->select();
			$cate=new Catetree();
			$cateRes=$cate->catetree($cateRes);
			$cates = Model('Catetree')->where('id',$id)->find();
	        $this->assign([
	            'cateRes'=>$cateRes,
	            'cates'=>$cates,
	            ]);
	        return view('classification/exit');

		}

		public function exits(Request $request)
		{
			$id = input('id');
			$name = input('name');
			$pid = input('pid');

			if (empty($pid)) {
				if ($pid !='0') {
					return $this->error('请选择分类');
				}	
			}
			if (empty($name)) {
				return $this->error('请填写分类名称');
			}
			if (empty($id)) {
				return $this->error('非法操作');
			}
			//判断当前用户选择分类不是当前分类下面的子类
            $cateid = Model('Catetree')->where('id',$pid)->find();

			if (!empty($cateid)) {

			    if ($cateid->pid == $id) {
                    return $this->error('不可将当前分类放到其子类下面');
                }
			}
			$data = ['pid'=>$pid,'name'=>$name];
//            dump($data);dump($id);die;
			$res = Model('Catetree')->where('id',$id)->update($data);
			if ($res) {
				return $this->success('修改成功');
			}else{
				return $this->error('修改失败');
			}
		}

		public function delete(Request $request)
		{
			$id = input('id');
			if (empty($id)) {
				return $this->error('非法操作');
			}
			$date = Model('Catetree')->where('pid',$id)->find();

			if (!empty($date)) {
				return $this->error('该分类下有子类，不可删除');
			}
			$res = Model('Catetree')->where('id',$id)->delete();
			if ($res) {
				return $this->success('删除成功');
			}else{
				return $this->error('删除失败');
			}

		}

	}