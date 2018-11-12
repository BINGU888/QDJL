<?php

namespace app\bis\controller;
use think\Controller;
use think\Model;
use think\Request;
use app\bis\controller\Base;
/**
 * 商户首页
 */
class Order extends Base
{
    public function index()
    {
        //获取当前商户id
        $bid = session('bid');

        //所有订单
        $res = Model('Usergoods')->where('bid',$bid)->select();
        $goods = Model('Goods')->where('uid',$bid)->select();
        //渲染
        $this->assign([
            'res'=>$res,
            'goods'=>$goods,
        ]);
        return view('order/index');
    }
    //未支付
    public function notpay(Request $request)
    {
        $bid = session('bid');
        $res = Model('Usergoods')->where('pay','0')->where('bid',$bid)->select();
        $goods = Model('Goods')->where('uid',$bid)->select();
        //渲染
        $this->assign([
            'res'=>$res,
            'goods'=>$goods,
        ]);
        return view('order/notpay');
    }
    //未发货
    public function notdeliver()
    {

    }
    //发货中
    public function deliveryof(Request $request)
    {
        $bid = session('bid');
        $status = input('status');
        $res = Model('Usergoods')->where('bid',$bid)->where('pay','1')->where('status',$status)->select();
        $goods = Model('Goods')->where('uid',$bid)->select();
        $this->assign([
            'res'=>$res,
            'goods'=>$goods,
        ]);
        return view('order/deliveryof');
    }
    //发货完成
    public function deliverycompleted()
    {

    }

    //发货页面渲染
    public function delivery(Request $request)
    {
        $id = input('id');
        $res = Model('Usergoods')->where('id',$id)->find();
        $this->assign([
            'res'=>$res,
        ]);
        return view('order/delivery');
    }

    //发货
    public function deliverys(Request $request)
    {
        $id = input('id');
        $couriername = input('couriername');
        $courier = input('courier');
        if (empty($id))
        {
            return $this->error('非法操作');
        }
        if (empty($couriername))
        {
            return $this->error('请填写快递名');
        }
        if (empty($courier))
        {
            return $this->error('请填写订单号');
        }
        $date = input('post.');
        $date['status'] = '1';
        $res = Model('Usergoods')->where('id',$id)->update($date);

        if ($res){
            return $this->success('发货成功');
        }else{
            return $this->error('发货失败');
        }
    }
}