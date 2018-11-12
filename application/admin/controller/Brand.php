<?php
	
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;
	/**
	* 品牌模块
	*/
	class Brand extends Base
	{
		public function index()
		{
			//逻辑
			$res = Model('Brand')->select();
			$this->assign([
				'res'=>$res,
				]);
			//显示视图
			return view('brand/index');
		}

		public function add()
		{
			//逻辑
		    $cateRes  =  Model('Catetree')->select();
		    $cate=new Catetree();
		    $cateRes=$cate->catetree($cateRes);
	            $this->assign([
	            'cateRes'=>$cateRes,
	            ]);

			return view('brand/add');
		}

		//添加品牌
		public function save(Request $request)
		{
			//校验
			if (Request::instance()->isPost()) {
					$data = input('post.');

				 	$file = request()->file('image');

					if (empty($data['brandname'])) {
						return $this->error('请输入品牌名称');
					}
					if (empty($data['cid'])) {
						return $this->error('请选择分类');
					}
					if (empty($data['content'])) {
						return $this->error('请添加描述');
					}

					if (empty($file)) {
				 		return $this->error('请上传品牌logo');
				 	}
				     // 移动到框架应用根目录/public/uploads/ 目录下
				    if($file){
				        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				        if($info){
				            // 成功上传后 获取上传信息
				            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
				            $image =  $info->getSaveName();
				         // 20180920\157347264a073b7208724604ed60e105.jpg
				        }else{
				            // 上传失败获取错误信息
				            echo $file->getError();
				        }
				    }
				    $images = '/uploads/'.$image;
					$data['image'] = $images;
					//逻辑
					$res = Model('Brand')->insert($data);
					if ($res) {
						return $this->success('添加成功');
					}else{
						return $this->error('添加失败');
					}

			}
			
		}

		// 品牌视图
		public function exits (Request $request)
		{

			$cateRes  =  Model('Catetree')->select();
		    $cate=new Catetree();
		    $cateRes=$cate->catetree($cateRes);
	            $this->assign([
	            'cateRes'=>$cateRes,
	            ]);

			$id = input('id');
			$res = Model('Brand')->where('id',$id)->find();

			return view('brand/exit',['res'=>$res]);
		}
		//修改品牌
		public function update(Request $request)
		{
			//校验
			if (Request::instance()->isPost()) {
					$data = input('post.');
					$id = input('id');

				 	$file = request()->file('image');

					if (empty($data['brandname'])) {
						return $this->error('请输入品牌名称');
					}
					if (empty($data['cid'])) {
						return $this->error('请选择分类');
					}
					if (empty($data['content'])) {
						return $this->error('请添加描述');
					}
                //删除原有图片
                $images = Model('Brand')->where('id',$id)->find();
                if (!empty($images->image)){
                    unlink('.'.$images->image);
                };

					if (!empty($file)) {
				 		
				 
				     // 移动到框架应用根目录/public/uploads/ 目录下
				    if($file){
				        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				        if($info){
				            // 成功上传后 获取上传信息
				            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
				            $image =  $info->getSaveName();
				         // 20180920\157347264a073b7208724604ed60e105.jpg
				        }else{
				            // 上传失败获取错误信息
				            echo $file->getError();
				        }
				    }
				    $images = '/uploads/'.$image;
					$data['image'] = $images;
						}
					//逻辑
					$res = Model('Brand')->where('id',$id)->update($data);
					if ($res) {
						return $this->success('修改成功');
					}else{
						return $this->error('修改失败');
					}

			}
		}
		//删除品牌
		public function delete(Request $request)
		{
			$id = input('id');
			if (empty($id)) {
				return $this->error('非法操作');
			}
            //删除原有图片
            $images = Model('Brand')->where('id',$id)->find();
            if (!empty($images->image)){
                unlink('.'.$images->image);
            };
			$res = Model('Brand')->where('id',$id)->delete();
			if ($res) {
				return $this->success('删除品牌成功');
			}else{
				return $this->error('删除品牌失败');
			}
		}


	}