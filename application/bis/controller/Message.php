<?php

namespace app\bis\controller;
use think\Controller;
use think\Request;
use app\bis\controller\Base;
use catetree\Catetree;
use \think\File;
/**
 * 商户留言
 */
class Message extends Base
{
    public function index()
    {
       $bid = session('bid');
       $res = Model('Message')->where('uid',$bid)->select();
       foreach($res as $key=>$value)
       {
           $gid = $value['gid'];
       }
       $goods= Model('Goods')->where('id','in',$gid)->select();
       $this->assign([
           'res'=>$res,
           'goods'=>$goods,
       ]);
       return view('message/index');
    }
    public function delete(Request $request)
    {
        $id = input('id');
        $uid = session('bid');

        $res = Model('Message')->where('id',$id)->where('uid',$uid)->delete();
        if ($res){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

}