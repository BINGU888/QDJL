<?php

namespace app\bis\controller;
use think\Controller;
use think\Request;
use app\bis\controller\Base;
/**
 * 商户店铺首页
 */
class Withdrawal extends Base
{
    //提现展示
    public function index(Request $request)
    {
        $bid = session ('bid');
        $price = input('price');
        //查询当前可提现金额
        $total = '';
        $totals = Model('Usergoods')->where('pay','1')->where('bid',$bid)->where('status','2')->select();
            foreach ($totals as $key=>$value)
            {

                $total = $value['total']+$total;

            }
        //获取当前冻结中的体现金额
        $freeze = Model('Usergoods')->where('pay','1')->where('bid',$bid)->where('status','<','2')->select();
            $freezes = '';
            foreach ($freeze as $key=>$value)
            {
                $freezes = $value['total'] + $freezes;
            }
        //判断当前用户是否有提现记录
        $data = Model('Withdrawal')->where('bid',$bid)->find();
            if (!empty($data))
            {
               $total =  $total - $data->havetotal;
            }
            if (empty($total)){
                $total = 0;
            }
            if (empty($freezes))
            {
                $freezes = 0;
            }
            if (!empty($price)){
              $res = $total - $price;
              if ($price <'100'){
                    return $this->error('提现金额最少100元起提');
              }
              if ($res < '0'){
                    return $this->error('提现金额不可以大于可提现金额');
              }
              if (!empty($data)){
                  $havetotal = $price+$data->havetotal;
                  $starttime = time();
                  $pricedate = Model('Amount')->insert(['bid'=>$bid,'price'=>$price,'starttime'=>$starttime]);
                  $res = Model('Withdrawal')->where('bid',$bid)->update(['havetotal'=>$havetotal]);
                  if ($res){
                      return $this->success('提现成功');
                  }else{
                      return $this->error('提现失败');
                  }
              }else{
                  $starttime = time();
                  $pricedate = Model('Amount')->insert(['bid'=>$bid,'price'=>$price,'starttime'=>$starttime]);
                  $res = Model('Withdrawal')->insert(['bid'=>$bid,'havetotal'=>$price]);
                  if ($res)
                  {
                      return $this->success('提现成功');
                  }else{
                      return $this->error('提现失败');
                  }
              }
            }
            //渲染视图
            $this->assign([
                'total'=>$total,
                'freezes'=>$freezes,
            ]);
        return view('withdrawal/index');
    }
    //提现记录
    public function withdrawals(){
        $bid = session('bid');
        $res = Model('Amount')->where('bid',$bid)->select();
        $this->assign([
           'res'=>$res,
        ]);
        return view('withdrawal/withdrawals');
    }

    //提现设置
    public function setup(Request $request)
    {
        $bid = session('bid');
        //查询当前用户信息
        $res = Model('Withdrawal')->where('bid',$bid)->find();
        $this->assign([
            'res'=>$res,
        ]);
        return view('withdrawal/setup');
    }
    public function setups(Request $request)
    {
        $bid = session('bid');
        $name = input('name');
        $paytreasure = input('paytreasure');
        $bankcard = input('bankcard');
        $address = input('address');
            if (empty($name))
            {
                $this->error('姓名不可为空');
            }
            if (empty($paytreasure))
            {
                return $this->error('支付宝号不可为空');
            }
            if (empty($bankcard))
            {
                return $this->error('银行卡号不可为空');
            }
            if (empty($address))
            {
                return $this->error('开户行地址不可为空');
            }
            $data = input('post.');
            $date = Model('Withdrawal')->where('bid',$bid)->find();
            if ($date){
                $res = Model('Withdrawal')->where('bid',$bid)->update($data);
            }else{
                $data['bid'] = $bid;
                $res = Model('Withdrawal')->insert($data);
            }

            if ($res){
                return $this->success('设置成功');
            }else{
                return $this->error('设置失败');
            }
    }

}