<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;
use app\extra\alipay\aop\AopClient;
class Store extends Base
{
	/*
	*店铺模块
	*/



	//商家店铺首页
    public function index(Request $request)
    {
      	//逻辑
      	//获取当前店铺id
    		$id = input('id');
        // dump($id);die;
    		//获取商铺分类
    		$trees = Model('Storetree')->where('uid',$id)->select();
    		
    		//获取促销商品
    		$promotion = Model('Goods')->where('uid',$id)->where('storestatus','2')->select();
    		//推荐商品
    		//获取分类根据分类查询商品 在查找品牌
    		if (!empty($trees)) {
    		foreach ($trees as $key => $value) {
    			$pid[] = $value['threepid'];
    		}
    		//查询品牌
   		$brand = Model('Brand')->where('cid','in',$pid)->select();
   		//查询客户选中分类
   		$showtree = Model('Catetree')->where('id','in',$pid)->paginate('10');
   		//查询推荐商品
   		$recommended = Model('Goods')->where('uid',$id)->where('pid','in',$pid)->where('storestatus','>','0')->select();
      $buser = Model('Buser')->where('id',$id)->find();
      if (empty($buser)){
          $buser = null;
      }
   		//查询当前商家店铺的信息
   		$bstore = Model('Store')->where('uid',$id)->find();
      if (empty($bstore)) {
          $bstore = '';
      }
      //轮播图
      $images = explode(',',$bstore->images);
   		// dump($showtree);
    		//勿碰！！ 商家分类排序
    		$all = $this->storetree($trees);	
   	  	}else{
    		$all = '';
    		$promotion = '';
    		$brand = '';
    		$showtree = '';
    		$recommended = null;
        $images ='';
        $bstore = '';
        $buser = Model('Buser')->where('id',$id)->find();
        $bstore = null;

    		}
      	//三级分类
      	$cateRes=getTrees();
      	$this->assign([
      		'cateRes'=>$cateRes,
      		'all'=>$all,
      		'promotion'=>$promotion,
      		'brand'=>$brand,
      		'showtree'=>$showtree,
      		'recommended'=>$recommended,
      		'bstore'=>$bstore,
          'buser'=>$buser,
          'images'=>$images,
      	]);

      	//渲染商家首页视图
      	return view('store/index');
    }
    //商品列表
    public function list(Request $request)
    {
      	//获取当前商家店铺id
      	$id = input('id');
        // dump($id);die;
      	if (empty($id)) {
      		return $this->error('非法操作');
      	}
      	//获取分类id
      	$pid = input('pid');
      	if (empty($pid)) {
      		return $this->error('非法请求');
      	}
      	//查询商品
      	$res = Model('Goods')->where('uid',$id)->where('pid',$pid)->paginate();
      	//查询属于当前三级分类的品牌
      	$brand = Model('Brand')->where('cid',$pid)->select();
        //查询当前商家店铺的信息
        $bstore = Model('Store')->where('uid',$id)->find();
        //查找商户信息 
        $buser = Model('Buser')->where('id',$id)->find();
      	//获取商铺分类
    		$trees = Model('Storetree')->where('uid',$id)->select();
        //查询当前分类
        $tree = Model('Catetree')->where('id',$pid)->find();
        //查询所有分类
        $treeres = Model('Catetree')->select();
        $twotree = Model('Catetree')->where('id',$tree->pid)->find();

    		$all = $this->storetree($trees);
      	//三级分类数据
      	$cateRes=getTrees();
      	$this->assign([
      		'all'=>$all,
      		'res'=>$res,
      		'cateRes'=>$cateRes,
          'brand'=>$brand,
          'bstore'=>$bstore,
          'buser'=>$buser,
          'tree'=>$tree,
          'twotrees'=>$twotree,
          'treeres'=>$treeres,
      	]);

      	return view('store/list');
      }

    //商品详情
    public function show(Request $request)
    { 

        //获取当前商家id
        $id = input('id');

        //获取分类id
        $pid = input('pid');
        //获取当前品牌id
        $brandid = input('brandid');
        //根据当前商家id 品牌 商家分类 查找商品 
        $res = Model('Goods')->where('uid',$id)->where('pid',$pid)->where('brandid',$brandid)->select();
        //查询当前品牌
        $brand = Model('Brand')->where('id',$brandid)->find();
        if (empty($brand)) {
          $brand =null;
        }
        //查询当前分类
        $tree = Model('Catetree')->where('id',$pid)->find();
        //查询当前商家店铺的信息
        $bstore = Model('Store')->where('uid',$id)->find();
        //查找商户信息 
        $buser = Model('Buser')->where('id',$id)->find();
        //获取店铺分类
        $trees = Model('Storetree')->where('uid',$id)->select();
        $all = $this->storetree($trees);
        //三级分类数据
        $cateRes=getTrees();
        $this->assign([
          'res'=>$res,
          'all'=>$all,
          'buser'=>$buser,
          'cateRes'=>$cateRes,
          'brand'=>$brand,
          'tree'=>$tree,
          'bstore'=>$bstore,
        ]);
        return view('store/show');
    }
    //商品促销详情页
    public function promotion(Request $request)
    {
        $id = input('id');
        //查询商品促销
        $res = Model('Goods')->where('uid',$id)->where('storestatus','2')->paginate();
        if (empty($res)) {
            $res =null;
        }
        //获取商户信息
        $buser = Model('Buser')->where('id',$id)->find();
        //获取商铺分类
        $trees = Model('Storetree')->where('uid',$id)->select();
        $all = $this->storetree($trees);
        //查询当前商家店铺的信息
        $bstore = Model('Store')->where('uid',$id)->find();
        //三级分类数据
        $cateRes=getTrees();
       
        $this->assign([
          'res'=>$res,
          'cateRes'=>$cateRes,
          'all'=>$all,
          'buser'=>$buser,
          'bstore'=>$bstore,
        ]);

        return view('store/promotion');

    }
    //商户简介
    public function introduction(Request $request)
    {
        $id = input('id');
        //获取商户信息
        $buser = Model('Buser')->where('id',$id)->find();
        //获取分类
        //获取商铺分类
        $trees = Model('Storetree')->where('uid',$id)->select();
        $all = $this->storetree($trees);
        //查询当前商家店铺的信息
        $bstore = Model('Store')->where('uid',$id)->find();
        //三级分类数据
        $cateRes=getTrees();

        //营业执照
         $images = explode(',',$buser->image_url);
         $images = $images['0'];
        $this->assign([
          'all'=>$all,
          'bstore'=>$bstore,
          'cateRes'=>$cateRes,
          'buser'=>$buser,
          'images'=>$images,
        ]);
        return view('store/introduction');
    }

    //商家库展示
    public function library()
    {
        //查询svip商户
        $svipuser = Model('Buservip')->where('status','1')->select();
          foreach ($svipuser as $key => $value) {
           #获取svip全部id
              $svipid[] = $value['uid'];
          }
        $vipuser  = Model('Buservip')->where('status','0')->select();
          foreach ($vipuser as $key => $value) {
              $vipid[] = $value['uid'];
          }
        //获取普通商户
        $buser = Model('Buser')->select();
          foreach ($buser as $key => $value) {
            #获取普通商户
            foreach ($svipid as $k => $v) {
              #把svip去掉
              if($value['id'] == $v)
              {
                  unset($buser[$key]);
              }
                
            }
            //去除重复id
            foreach ($vipid as $vipk => $svipv) {
                if ($value['id'] == $svipv) {
                 unset($vipid[$key]);
                }
              }
          
          }

          //判断vip里面存在svip商户id则销毁
          foreach ($vipid as $key => $value) {
                foreach ($svipid as $k => $v) {
                    if ($value == $v) {
                      unset($vipid[$key]);
                    }
                }
          }

        //获取svip的用户信息
        $svipuser =  Model('Buser')->where('id','in',$svipid)->order('starttimes')->select();

        $vipuser = Model('Buser')->where('id','in',$vipid)->order('starttimes')->select();
    
        $allbuser = array_merge($svipuser,$vipuser,$buser);
        //查询所有城市
        $city = Model('City')->select();
        //26字母
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
         //三级分类
        $cateRes = getTrees();
        //城市分类
        $cateCity = Model('City')->select();
        $cateCity = collection($cateCity)->toArray();
        $cateCity=getCity($cateCity);
      
        $this->assign([
          'cateRes'=>$cateRes,
          'allbuser'=>$allbuser,
          'city'=>$city,
          'letters'=>$letters,
          'cateCity'=>$cateCity,
        ]);
        //渲染视图
        return view('store/library');
    }
    //ajax商户详情弹框
    public function details(Request $request)
    {
        //ajax获取当前商品信息
        $id = input('id');
        if (empty(session('id'))) {
            return $data = '1';
            die;
        }
        //判断当前用户点击次数是否达到50次
        
        $uid = session('id');
        //查询当前商户信息
        $data = Model('Buser')->where('id',$id)->find();
        $provinceid = Model('City')->where('id',$data->provinceid)->find();
        $city = Model('City')->where('id',$data->cityid)->find();
        $data['province'] = $provinceid->name;
        $data['city'] = $city->name;
        return $data;
    }
    //商户字母搜索
    public function letter(Request $request)
    {
        //获取字母
        $letter = input('letter');
        if (empty($letter)) {
            return $this->error('非法请求');
        }

        //获取vip规则排序id
        $vips = Model('Store')->vips();
        //查寻当前字母的商户 $vips规则
        $allbuser =  Model('Buser')->where('id','in',$vips)->where('letter',$letter)->select();

        //调用数据
        //三级分类
        $cateRes = getTrees();
        //26字母
        $letters = $this->letters();
        //查询所有城市
        $city = Model('City')->select();
        //城市分类
        $cateCity = Model('City')->select();
        $cateCity = collection($cateCity)->toArray();
        $cateCity=getCity($cateCity);
        //视图渲染
        $this->assign([
          'allbuser'=>$allbuser,
          'cateRes'=>$cateRes,
          'city'=>$city,
          'letters'=>$letters,
          'cateCity'=>$cateCity,
        ]);
        return view('store/library');
    }
    //搜索商家
    public function citys(Request $request)
    {

        //省id
        $provinceid = input('provinceid');
        //市id
        $cityid = input('cityid');
        //区id
        $areaid = input('areaid');
        //name 搜索内容
        $name = input('name');
        //获取vip规则排序id
        $vips = Model('Store')->vips();
        //调用数据
        //三级分类
        $cateRes = getTrees();
        //26字母
        $letters = $this->letters();

        //判断省id 市id 区id 搜索内容 为空
        if (empty($provinceid) && empty($cityid) && empty($areaid) && empty($name)) {
              return redirect('store/library');
        }
         //判断省id不为空 市id 区id 搜索内容 为空
        if (!empty($provinceid) && empty($cityid) && empty($areaid) && empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)->where('id','in',$vips)->select();

        }

        //判断省id不为空 市id不为空 区id 搜索内容 为空
        if (!empty($provinceid) && !empty($cityid) && empty($areaid) && empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)->where('cityid',$cityid)->where('id','in',$vips)->select();

        }

          //判断省id不为空 市id不为空 区id不为空 搜索内容 为空
        if (!empty($provinceid) && !empty($cityid) && !empty($areaid) && empty($name)) {
  
          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)->where('cityid',$cityid)->where('areaid',$areaid)->where('id','in',$vips)->select();

        }

          //判断省id不为空 市id不为空 区id不为空 搜索内容 不为空
        if (!empty($provinceid) && !empty($cityid) && !empty($areaid) && !empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)
                                    ->where('cityid',$cityid)
                                    ->where('areaid',$areaid)
                                    ->where('id','in',$vips)
                                    ->where('enterprisename','like','%'.$name.'%')
                                    ->select();
        }

          //判断省id为空 市id不为空 区id不为空 搜索内容 不为空
        if (empty($provinceid) && !empty($cityid) && !empty($areaid) && !empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('cityid',$cityid)
                                    ->where('areaid',$areaid)
                                    ->where('id','in',$vips)
                                    ->where('enterprisename','like','%'.$name.'%')
                                    ->select();
        }
          //判断省id不为空 市id为空 区id不为空 搜索内容 不为空
        if (!empty($provinceid) && empty($cityid) && !empty($areaid) && !empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)
                                    ->where('areaid',$areaid)
                                    ->where('id','in',$vips)
                                    ->where('enterprisename','like','%'.$name.'%')
                                    ->select();
        }

                  //判断省id不为空 市id不为空 区id为空 搜索内容 不为空
        if (!empty($provinceid) && !empty($cityid) && empty($areaid) && !empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)
                                    ->where('cityid',$cityid)
                                    ->where('id','in',$vips)
                                    ->where('enterprisename','like','%'.$name.'%')
                                    ->select();
        }

                          //判断省id不为空 市id不为空 区id不为空 搜索内容 为空
        if (!empty($provinceid) && !empty($cityid) && !empty($areaid) && empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)
                                    ->where('cityid',$cityid)
                                    ->where('id','in',$vips)
                                    ->where('areaid',$areaid)
                                    ->select();
        }

            //判断省id不为空 市id为空 区id为空 搜索内容 为空
        if (!empty($provinceid) && empty($cityid) && empty($areaid) && empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)->where('id','in',$vips)->select();
        }
          //判断省id为空 市id不为空 区id为空 搜索内容 为空
        if (empty($provinceid) && !empty($cityid) && empty($areaid) && empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('cityid',$cityid)->where('id','in',$vips)->select();
                                    
        }

          //判断省id为空 市id为空 区id不为空 搜索内容 为空
        if (empty($provinceid) && empty($cityid) && !empty($areaid) && empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('id','in',$vips)
                                    ->where('areaid',$areaid)
                                    ->select();
        }

          //判断省id为空 市id为空 区id为空 搜索内容不为空
        if (empty($provinceid) && empty($cityid) && empty($areaid) && !empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('id','in',$vips)
                                    ->where('enterprisename','like','%'.$name.'%')
                                    ->select();
        }

          //判断省id不为空 市id 区id 搜索内容 为空
        if (!empty($provinceid) && empty($cityid) && empty($areaid) && !empty($name)) {

          //查询符合逻辑的商户
          $allbuser = Model('Buser')->where('provinceid',$provinceid)->where('id','in',$vips)->where('enterprisename','like','%'.$name.'%')->select();

        }

        //查询所有城市
        $city = Model('City')->select();
          //视图渲染
          $this->assign([
            'allbuser'=>$allbuser,
            'cateRes'=>$cateRes,
            'cateCity'=>$city,
            'city'=>$city,
            'letters'=>$letters,
          ]);
          return view('store/library');
    }


    //26字母
    public function letters()
    {
      $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
      return $letters;
    }

    public function alipay()
    {
        $c =  new AopClient();
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = "app_id";
        $c->rsaPrivateKey = '请填写开发者私钥去头去尾去回车，一行字符串' ;
        $c->format = "json";
        $c->charset= "GBK";
        $c->signType= "RSA2";
        $c->alipayrsaPublicKey = '请填写支付宝公钥，一行字符串';
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.open.public.template.message.industry.modify
        $request = new AlipayOpenPublicTemplateMessageIndustryModifyRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        //此次只是参数展示，未进行字符串转义，实际情况下请转义
        $request->bizContent = "{" .
        "    \"primary_industry_name\":\"IT科技/IT软件与服务\"," .
        "    \"primary_industry_code\":\"10001/20102\"," .
        "    \"secondary_industry_code\":\"10001/20102\"," .
        "    \"secondary_industry_name\":\"IT科技/IT软件与服务\"" .
        " }";
        $response= $c->execute($request);
    }
    //荣誉资质
    public function honor(Request $request)
    {
        //获取商户id
        $bid = input('bid');
        if (empty($bid))
        {
            return $this->error('非法操作');
        }
        //根据商户id 查询其荣誉资质
        $res = Model('Honor')->where('bid',$bid)->paginate();
        $cateRes = getTrees();
        //获取商家
        $buser = Model('Buser')->where('id',$bid)->find();
        //获取商家详情
        $bstore = Model('Store')->where('uid',$bid)->find();
        //获取商铺分类
        $trees = Model('Storetree')->where('uid',$bid)->select();
        $all = $this->storetree($trees);
        $this->assign([
            'res'=>$res,
            'cateRes'=>$cateRes,
            'buser'=>$buser,
            'bstore'=>$bstore,
            'all'=>$all,
        ]);
        return view('store/honor');
    }
    //荣誉资质详情页面
    public function honorshow(Request $request)
    {
        $bid = input('bid');
        $id = input('id');
        //查询当前荣誉详情
        $res = Model('Honor')->where('id',$id)->where('bid',$bid)->find();
        $cateRes = getTrees();
        //获取商家
        $buser = Model('Buser')->where('id',$bid)->find();
        //获取商家详情
        $bstore = Model('Store')->where('uid',$bid)->find();
        //获取商铺分类
        $trees = Model('Storetree')->where('uid',$bid)->select();
        $all = $this->storetree($trees);
        $this->assign([
            'res'=>$res,
            'cateRes'=>$cateRes,
            'buser'=>$buser,
            'bstore'=>$bstore,
            'all'=>$all,
        ]);
        return view('store/honorshow');
    }
    //资讯
    public function information(Request $request)
    {
        $bid = input('bid');
        if (empty($bid))
        {
            return $this->error('非法操作');
        }
        //查询当前商户全部资讯
        $res = Model('Informations')->where('bid',$bid)->select();
        $cateRes = getTrees();
        //获取商家
        $buser = Model('Buser')->where('id',$bid)->find();
        //获取商家详情
        $bstore = Model('Store')->where('uid',$bid)->find();
        //获取商铺分类
        $trees = Model('Storetree')->where('uid',$bid)->select();
        $all = $this->storetree($trees);
        $this->assign([
            'res'=>$res,
            'cateRes'=>$cateRes,
            'buser'=>$buser,
            'bstore'=>$bstore,
            'all'=>$all,
        ]);

        return view('store/information');
    }
    //资讯详情页
    public function informations(Request $request)
    {
        $bid = input('bid');
        $id = input('id');
        $res = Model('Informations')->where('id',$id)->where('bid',$bid)->find();
        $cateRes = getTrees();
        //获取商家
        $buser = Model('Buser')->where('id',$bid)->find();
        //获取商家详情
        $bstore = Model('Store')->where('uid',$bid)->find();
        //获取商铺分类
        $trees = Model('Storetree')->where('uid',$bid)->select();
        $all = $this->storetree($trees);
        $this->assign([
            'res'=>$res,
            'cateRes'=>$cateRes,
            'buser'=>$buser,
            'bstore'=>$bstore,
            'all'=>$all,
        ]);
        return view('store/informations');
    }
}