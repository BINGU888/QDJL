<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;
class Index extends Base
{
    //商城首页
    public function index()
    {
        //当前定位城市
        $Cityip = new Cityip();
        $Cityip = $Cityip->Cityip();
        if (!empty($Cityip)) {
            $res = Model('City')->where('id', $Cityip)->find();
            $cityname = $res->name;
        } else {
            $cityname = '济南市';
        }

        //逻辑

        //三级分类
        $cateRes = getTrees();
        // foreach ($cateRes as $key => $value) {
        //     foreach ($value['child'] as $k => $v) {

        //     }
        // }
        //合成
        $arr = [];
        //查询全部分类
        $catetree = Model('Catetree')->select();
        //一级分类
        $onetree = Model('Catetree')->where('pid', '0')->select();
        //获取一级分类id
        foreach ($onetree as $key => $value) {

            {
                // $arr[$value['id']]['child']  = [];


            }

            //获取二级分类
            foreach ($catetree as $key => $value)

                foreach ($onetree as $k => $v) {

                    if ($value['pid'] == $v['id']) {
                        $arr[$v['id']]['child'][$value['id']]['data'] = [];
                        $arr[$v['id']]['child'][$value['id']]['company'] = [];
                        $cate = Model('Catetree')->where('pid', $value['id'])->select();
                        foreach ($cate as $key => $value) {
                            $threetreeid = $value['id'];
                        }
                        //获取到品牌id
                        if (empty($threetreeid)){
                            $threetreeid = null;
                        }
                        $Brand = Model('Brand')->where('cid', 'in', $threetreeid)->limit('8')->select();
                        foreach ($Brand as $bk => $bv) {
                            if ($bv['cid'] == $value['id']) {
                                $arr[$v['id']]['child'][$value['id']]['data'][$bv['id']]['image'] = [$bv['image']];

                                $brandid[] = $bv['id'];
                                //根据品牌查找svip
                                // dump($brandids);
                                $svip = Model('Buservip')->where('brandid', 'in', $brandid)->select();

                            }


                            foreach ($svip as $pk => $pv) {

                                if ($pv['brandid'] == $bv['id']) {
                                    // $arr[$v['id']]['child'][$value['id']]['company'][$pv['id']]=[$pv['uid']];
                                    $uid = $pv['uid'];

                                    $Buser = Model('Buser')->where('id', 'in', $uid)->select();
                                    foreach ($Buser as $uk => $uv) {
                                        if ($uv['id'] == $pv['uid']) {
                                            $arr[$v['id']]['child'][$value['id']]['company'][$uv['id']]['name'] = [$uv['enterprisename']];
                                        }
                                    }
                                }

                                $goods = Model('Goods')->where('uid', 'in', $uid)->limit('4')->select();
                                foreach ($goods as $gk => $gv) {
                                    if ($uv['id'] == $gv['uid']) {
                                        $arr[$v['id']]['child'][$value['id']]['company'][$uv['id']]['goods'][$gv['id']] = [$gv['goods_name']];
                                    }
                                }


                            }
                        }


                        // $arr[$v['id']]['child'][$value['id']]['child']=[];
                    }


                }
        }

        // ['child'][$value['id']]

        // echo '<pre>';
        // print_r($arr);


        // echo '<pre>';
        //   print_r($cateRes);
        $goods = Model('Goods')->limit('4')->select();
        //查询品牌
        $brand = Model('Brand')->limit('18')->select();
        //公告
        $announcement = Model('Information')->where('status','0')->limit('8')->select();
        //新闻热点
        $redian  = Model('Information')->where('status','2')->select();
//        dump($redian);die;

        $this->assign([
            'cateRes' => $cateRes,
            'brand' => $brand,
            'cityname' => $cityname,
            'arr' => $arr,
            'goods'=>$goods,
            'announcement'=>$announcement,
            'redian'=>$redian,
        ]);

        return view('index/index');
    }

    public function threetree(Request $request)
    {
//        光照汽配
        $pid = input('pid');
        // //通过二级分类查询下级的三级分类
        // $res = Model('Catetree')->where('pid',$id)->find();
        //获取当前城市id 根据当前城市id去找商户账号属于该城市的账号 通过商户账号查询商品 并且商品里面的三级分类要相同 然后根据商户等级查找
        //当前城市id

        $cityid = session('cityid');
        //查询vip用户
        $vips = Model('Buservip')->where('towtree', $pid)->where('status','1')->where('pay','1')->select();

        //获取 vip用户id
        foreach ($vips as $key => $value) {
            $vipid[] = $value['uid'];
        }


        //获取当前城市的商家
        // $Buser = Model('Buser')->where('cityid',$cityid)->where('towtree',$pid)->limit('8')->select();
        //查询属于当前vip商家的用户
        $Buser = Model('Buser')->where('cityid', $cityid)->where('id', 'in', $vipid)->limit('8')->select();

        return $Buser;


    }

    public function hoveimage(Request $request)
    {
        $pid = input('pid');

        // //通过二级分类查询下级的三级分类
        // $res = Model('Catetree')->where('pid',$id)->find();
        //获取当前城市id 根据当前城市id去找商户账号属于该城市的账号 通过商户账号查询商品 并且商品里面的三级分类要相同 然后根据商户等级查找
        //当前城市id
        $cityid = session('cityid');
        $catetree = Model('Catetree')->where('pid', $pid)->select();

        foreach ($catetree as $k => $v) {
            $cateid[] = $v['id'];
        }

        //获取一级
        $res = Model('Catetree')->where('pid','0')->select();
        foreach($res as $key=>$value)
        {
            $pidds[] = $value['id'];
        }
        $ress =  Model('Catetree')->where('pid','in',$pidds)->select();
        foreach($res as $key=>$value)
        {
            $threetree['threetree'][] = $value['id'];
        }


        //查询品牌
        $brand = Model('Brand')->where('cid', 'in', $cateid)->select();
        return $brand;
        //根据商家查找商品
    }

    public function goodsdata(Request $request)
    {
        $pid = input('pid');

        $cityid = session('cityid');
        //查询vip用户
        $vips = Model('Buservip')->where('towtree', $pid)->where('status','1')->where('pay','1')->select();

        //获取 vip用户id
        foreach ($vips as $key => $value) {
            $vipid[] = $value['uid'];
        }

        //查询当前用户商品分类与当前一致
        $goods = Model('Goods')->where('uid', 'in', $vipid)->where('pids', $pid)->limit('4')->select();

        // dump($goods);

        return $goods;
    }

    public function alipays()
    {

    //     //商户订单号，商户网站订单系统中唯一订单号，必填
//     $a = '123';
//    dump($a);
    // die;
    //     //订单名称，必填
    //     $subject = ['华硕电脑'];
    //     //付款金额，必填 
    //     $total_amount = '50';
    //     //商品描述，可空
    //     //$body = trim($_POST['body']);
    //     //构造参数
    //     $payRequestBuilder = new \alipay\AlipayTradePagePayContentBuilder();
    //     // $payRequestBuilder->setBody($body);
    //     $payRequestBuilder->setSubject($subject);
    //     $payRequestBuilder->setTotalAmount($total_amount);
    //     $payRequestBuilder->setOutTradeNo($out_trade_no);
    //     $aop = new \alipay\AlipayTradeService();
    //     /**
    //      *      * pagePay 电脑网站支付请求
    //      *      * @param $builder 业务参数，使用buildmodel中的对象生成。
    //      *      * @param $return_url 同步跳转地址，公网可以访问
    //      *      * @param $notify_url 异步通知地址，公网可以访问
    //      *      * @return $response 支付宝返回的信息
    //      *     */
    //     $response = $aop->pagePay($payRequestBuilder, config('alipay.return_url'), config('alipay.notify_url'));
    }
    //ajax返回一级分类
    public function onetree()
    {
        $res = Model('Catetree')->where('pid','0')->select();
        return $res;
    }

  
}
