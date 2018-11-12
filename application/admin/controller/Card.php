<?php
	
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\File;
	/**
	* 渠道通模块
	*/
class Card extends Base
{
	//未通过审核的名片
	public function index()
	{
		//查询未审核中的渠道信息
		$res = Model('Card')->where('status','0')->select();
		$this->assign([
			'res'=>$res,
		]);
		return view('card/index');
	}
	//通过审核的名片
	public function through()
	{
		//查询审核的渠道信息
		$res = Model('Card')->where('status','1')->select();
		$this->assign([
			'res'=>$res,
		]);
		return view('card/through');
	}
		//审核失败的名片
	public function notthrough()
	{
		//查询失败审核的渠道信息
		$res = Model('Card')->where('status','0')->select();
		$this->assign([
			'res'=>$res,
		]);
		return view('card/notthrough');
	}
	public function status(Request $request)
	{
		//修改状态
		$id = input('id');
		$status['status'] = input('status');
		$res = Model('Card')->where('id',$id)->update($status);
		if ($res) {
			return $this->success('操作成功');
		}else{
			return $this->error('操作失败');
		}
	}
	public function delete(Request $request)
	{
		//删除操作
		$id = input('id');
		$res = Model('Card')->where('id',$id)->delete();
		if ($res) {
			return $this->success('删除成功');
		}else{
			return $this->error('删除失败');
		}
	}

}