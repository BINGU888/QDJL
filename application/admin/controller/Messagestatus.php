<?php
	
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
	/**
	* 商户审核模块
	*/
	class Messagestatus extends Base
	{
		public function index()
		{
			//逻辑层
			//获取当前所有入驻审核中的商户
			$res = Model('Buser')->where('state','0')->select();
			//显示视图
			return view('messagestatus/index',['res'=>$res]);
		}
		public function details(Request $request)
		{
			//查看商户审核详情
			$id = input('id');
			if (empty($id)) {
				return error('非法操作');
			}

			$res = Model('Buser')->where('id',$id)->find();
			if (!empty($res)) {
				$province = Model('City')->where('id',$res['provinceid'])->find();
				$city = Model('City')->where('id',$res['cityid'])->find();
				$area = Model('City')->where('id',$res['areaid'])->find();
			}
			$image = explode(',',$res['image_url']);
			$this->assign([
					'res'=>$res,
					'province'=>$province,
					'city'=>$city,
					'area'=>$area,
					'image'=>$image,
				]);
			return view('messagestatus/details');
		}

		public function adopt(Request $request)
		{
			//入驻用户通过审核
			$id = input('id');
			$emai = Model('Buser')->where('id',$id)->find();
			$res = Model('Buser')->where('id',$id)->update(['state'=>'1']);
			// $res = '1';
			if ($res) {
				// $data = ['user_email'=>'亲你好','content'=>'内容内容'];
				        // 发送邮件

				// dump($emai->email);die;
        // $url = request()->domain().url('bis/register/waiting', ['id'=>$bisId]);
        $title = "o2o入驻申请通知";
        $content = "您提交的入驻申请需等待平台方审核，您可以通过点击链接<a href= target='_blank'>查看链接</a> 查看审核状态";
       $email = \phpmailer\Email::send($emai->email,$title, $content);  // 线上关闭 发送邮件服务
    			if ($email == true) {
    				 return $this->redirect('messagestatus/index');
    			}else{
    				return $this->error('审核通过失败');
    			}
			}
		}
		public function notadopt(Request $request)
		{
			//入驻用户审核失败
			$id = input('id');
			$res = Model('Buser')->where('id',$id)->update(['state'=>'4']);
			if ($res) {
				return $this->redirect('messagestatus/index');
			}else{
				return $this->error('审核不通过失败');
			}
		}
	}
