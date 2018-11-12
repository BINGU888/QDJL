<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
use \think\File;
use app\common\Message;
use alipay\AlipayTradePagePayContentBuilder;
class Settlement extends Base
{	/*
	*结算页
	*/
	//显示视图
	public function index(Request $request)
	{	
		//获取商品id
		$goodsid = input('id');
		$num = input('num');
		$total = input('total');

		if (empty($goodsid)) {
			return $this->error('非法操作！');
		}
		//判断当前用户是否登入
		$id = session('id');
		if (empty($id)) {
			cookie('settlements', "index/settlement/index?id=$goodsid", 3600);
			return $this->error('请先登录','Login/index');
		}

		//查询当前用户收货地址
		$shipping = Model('Shipping')->where('uid',$id)->select();
		$goods = Model('Goods')->where('id',$goodsid)->find();
        if (!empty($num) && !empty($total))
        {
            $goods['num'] = $num;
            $goods['total']= $total;
            $goods['goodsprice'] =$total - $goods->singleton*$num;
        }else{
            $goods['num'] = '1';
            $goods['total']= $goods->singleton + $goods->price;
            $goods['goodsprice'] =$goods->price;
        }

		//三级分类数据
		$cateRes = getTrees();
		$this->assign([
			'cateRes'=>$cateRes,
			'goods'=>$goods,
			'shipping'=>$shipping,
		]);
		//展示视图
		return view('settlement/index');
	}

	public function pay(Request $request)
    {
        $id = input('id');
    	//判断当前用户是否已添加收货人信息
    	$uid = session('id');
    	//判断当前用户是否存在收货地址。
    	$shipping = Model('Shipping')->where('uid',$uid)->find();
    	if (empty($shipping)) {

            cookie('Shipping',"index/settlement/index?id=$id");
    		return $this->error('请添加收货地址');
    	}
    	//判断当前用户是否存在默认地址
        $shippings = Model('Shipping')->where('status','1')->where('uid',$uid)->find();
        if (empty($shippings))
        {
            return $this->error('请设置默认地址');
        }
    	if (empty($id)) {
    		return $this->error('非法操作！');
    	}
    	$num = (int)input('num');
    	if (empty($num)) {
    		return $this->error('非法操作！');
    	}
    	
    	$goods = Model('Goods')->where('id',$id)->find();
    	if (empty($goods)) {
    		return $this->error('该商品已下架');
    	}
    	$total = (int)$goods->price * $num + (int)$goods->singleton*$num;
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no =$this->number();
        //订单名称，必填
        $total_amount = '0.01';
        $time = time();
        $sid = Model('Goods')->where('id',$id)->find();
        $data= ['uid'=>$uid,'gid'=>$id,'num'=>$num,'total'=>$total,'order'=>$out_trade_no,'starttime'=>$time,'bid'=>$sid->uid];
        //判断当前用户是否已存在此商品
        $res = Model('Usergoods')->where('uid',$uid)->where('gid',$id)->where('pay','0')->find();
        if ($res){
            $res = Model('Usergoods')->where('id',$res->id)->update($data);
        }else{
            $res = Model('Usergoods')->insert($data);
        }

        
         //订单名称，必填
    	$subject = $goods->goods_name;
       //商品描述，可空
        $body = '';
        //构造参数
         $payRequestBuilder =new AlipayTradePagePayContentBuilder();

         $payRequestBuilder->setBody($body);
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
        $response = $aop->pagePay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));


    }

    //生成订单号
    public function number()
    {
    	$number = time().rand(100000,999999);
    	return $number;
    }

    public function notify_url()
    {
        $arr = $_POST;
        $alipaySevice = new \alipay\AlipayTradeService();
        $alipaySevice->writeLog(var_export($_POST, true));
        $result = $alipaySevice->check($arr);
       /* 实际验证过程建议商户添加以下校验。
         1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
         2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
         3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
         4、验证app_id是否为该商户本身。
       */
if ($result) {//验证成功
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
    trace($result,'error');
            $out_trade_no = $_POST['out_trade_no'];
            $order = Model('Usergoods')->where('order',$out_trade_no)->where('pay','0')->find();
    trace($order,'error');

            Model('Usergoods')->where('id',$order->id)->update(['pay'=>'1']);
            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];
            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";//请不要修改或删除
        } else {
            //验证失败
                echo "fail";

                }
    }

    public function notify_urls()
    {
        $arr = $_POST;
        $alipaySevice = new \alipay\AlipayTradeService();
        $alipaySevice->writeLog(var_export($_POST, true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
          1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
          2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
          3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
          4、验证app_id是否为该商户本身。
        */
        if ($result) {//验证成功
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            trace($result,'error');
            $out_trade_no = $_POST['out_trade_no'];
            $order = Model('Buservip')->where('order',$out_trade_no)->where('pay','0')->find();

            Model('Buservip')->where('id',$order->id)->update(['pay'=>'1']);

            //支付宝交易号
            $trade_no = $_POST['trade_no'];


            //交易状态
            $trade_status = $_POST['trade_status'];
            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";//请不要修改或删除
        } else {
            //验证失败
            echo "fail";

        }


    }


    public function upgrade()
    {
        $arr = $_POST;
        $alipaySevice = new \alipay\AlipayTradeService();
        $alipaySevice->writeLog(var_export($_POST, true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
          1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
          2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
          3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
          4、验证app_id是否为该商户本身。
        */
        if ($result) {//验证成功
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            trace($result,'error');
            $out_trade_no = $_POST['out_trade_no'];
            $order = Model('Buservip')->where('order',$out_trade_no)->update(['status'=>'1']);

            //支付宝交易号
            $trade_no = $_POST['trade_no'];


            //交易状态
            $trade_status = $_POST['trade_status'];
            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";//请不要修改或删除
        } else {
            //验证失败
            echo "fail";

        }


    }


}