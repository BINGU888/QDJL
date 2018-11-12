<?php

namespace app\bis\controller;
use think\Controller;
use think\Model;
use think\Request;
use app\bis\controller\Base;
/**
 * 商户首页
 */
//svip vip服务模块
class service extends Base
{
    public function index()
    {
        $cateRes = getTrees();
        //渲染vip服务开通页面
        $this->assign([
            'cateRes'=>$cateRes,

        ]);
        return view('service/index');
    }
    public function add(Request $request)
    {
        $data = input('post.');
        $uid = session('bid');
        $data['uid'] = $uid;

        $validate=validate("Servicevalidate"); //使用验证
          if(!$validate->scene("save")->check($data)){
              $this->error($validate->getError());//内置错误返回
          }
        //插入品牌图片
        $brand = Model('Brand')->where('id',$data['brandid'])->find();
        $data['image'] = $brand->image;
        $data['starttime'] = time();
        $data['order'] = $this->number();
        $res = Model('Buservip')->where('brandid',$data['brandid'])->where('uid',$uid)->find();
//        dump($res);die;
          if (!empty($res)){
                if ($res->pay == 0){
                    Model('Buservip')->where('id',$res->id)->update($data);
                }else{
                    return $this->error('当前品牌已开通');
                }
          }else{
              Model('Buservip')->insert($data);
          }

          if($data['status'] == '0'){
              $date['name'] = 'VIP服务';
              $date['price']="0.01";
          }else{
              $date['name'] = 'SVIP服务';
              $date['price']="0.01";
          }

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $data['order'];
        //订单名称，必填
        $subject = $date['name'];
        //付款金额，必填 
        $total_amount = $date['price'];
        //商品描述，可空
        $body = '';
        //构造参数
        $payRequestBuilder = new \alipay\AlipayTradePagePayContentBuilder();
        // $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $aop = new \alipay\AlipayTradeService();
        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
    $response = $aop->pagePay($payRequestBuilder,config('alipay.return_urls'),config('alipay.notify_urls'));
    }

    //生成订单号
    public function number()
    {
        $number = time().rand(100000,999999);
        return $number;
    }
    //服务详情页
    public function see()
    {
        $uid = session('bid');
        $res = Model('Buservip')->where('uid',$uid)->where('pay','1')->select();
//        dump($res);die;
        $this->assign([
        'res'=>$res,
        ]);
        return view('service/see');
    }
    //升级服务
    public function status(Request $request)
    {
        $id = input('id');
        $res = Model('Buservip')->where('id',$id)->find();
        if (empty($res))
        {
            return $this->error('非法操作');
        }
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $res->order;
        //订单名称，必填
        $subject = 'SVIP';
        //付款金额，必填 
        $total_amount = '0.01';
        //商品描述，可空
        $body = '';
        //构造参数
        $payRequestBuilder = new \alipay\AlipayTradePagePayContentBuilder();
        // $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $aop = new \alipay\AlipayTradeService();
        /**
             * pagePay 电脑网站支付请求
             * @param $builder 业务参数，使用buildmodel中的对象生成。
             * @param $return_url 同步跳转地址，公网可以访问
             * @param $notify_url 异步通知地址，公网可以访问
             * @return $response 支付宝返回的信息
            */
        $response = $aop->pagePay($payRequestBuilder,config('alipay.return_urls'),config('alipay.upgrade'));

    }
}