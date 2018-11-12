<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;
use DB;
class Goodslist extends Base
{
	//商品分类列表页
	public function index(Request $request)
	{
		//获取三级分类
		$pid = input('pid');
		//搜索最大金额
		$big = input('big');
		//搜索
		$goods_name = input('goods_name');
		//最小金额
		$small = input('small');
		//获取当前城市
		$city = session('cityid');
		//判断当前是否存在商品查询条件
		if (!empty($goods_name)) {
			$resgoods = Model('Goods')->where('goods_name','like','%'.$goods_name.'%')->find();
			if ($resgoods) {
				$pid = $resgoods->pid;
			}
		}

		//根据当前三级分类查询会员id
		$vip = Model('Buservip')->where('threetree',$pid)->where('status','1')->where('pay','1')->select();
		foreach ($vip as $key => $value) {
			$uid[] = $value['uid'];
		}


		if (!empty($uid)) {
			# code...
		//获取vip商户信息
		$user = Model('Buser')->where('cityid',$city)->where('id','in',$uid)->select();
	
		//获取到商家id
		foreach ($user as $key => $value) {
			$uids[] = $value['id'];
		}

		//获取品牌
		$brand = Model('Brand')->where('cid',$pid)->select();
		//查找vip用户
		$vipuser = Model('Buservip')->where('pay','1')->where('uid','in',$uids)->where('threetree',$pid)->order('starttime')->order('status','desc')->limit('8')->select();

		$vips = Model('Store')->vips();
		$res = Model('Buser')->where('cityid',$city)->where('id','in',$vips)->select();

		foreach ($res as $key => $value) {
			$uidd[] = $value['id'];
		}

			//获取svipid
			$svipid = Model('Store')->svip();
			//获取vipid
			$vipid = Model('Store')->vip();
			//获取普通商户id
			$bid = Model('Store')->bid();
			if(!empty($big) || !empty($small))
			{
				//判断最大金额是否为空
				if (empty($big)) {
					$big = $small+10000000;
				}
				if (empty($small)) {
					$small = 0;
				}
				dump($big);
				dump($small);				//获取svip商品
				$svipgoods = Model('Goods')->where('uid','in',$svipid)->where('price','>=',$small)->where('price','<=',$big)->where('pid',$pid)->select();	
				$vipgoods = Model('Goods')->where('uid','in',$vipid)->where('price','>=',$small)->where('price','<=',$big)->where('pid',$pid)->select();
				//获取普通商品
				$bidgoods = Model('Goods')->where('uid','in',$bid)->where('price','>=',$small)->where('price','<=',$big)->where('pid',$pid)->select();

			}else{
			//获取svip商品
			
			$svipgoods = Model('Goods')->where('uid','in',$svipid)->where('pid',$pid)->select();
			
			//获取vip商品
			
			$vipgoods = Model('Goods')->where('uid','in',$vipid)->where('pid',$pid)->select();
			
			//获取普通商品
		
			$bidgoods = Model('Goods')->where('uid','in',$bid)->where('pid',$pid)->select();
			}
			//合并商品数据
			$goods = array_merge($svipgoods,$vipgoods,$bidgoods);

		}else{
			$user ='';
			$brand ='';
			$vipuser ='';
			$goods =null;
		}


		if($goods == null)
		{
			$limit = 0;
		}else{
			$limit = count($goods); 
		}


		$count = $limit/20;
	   	 $count = ceil($count)+1;
	   	 for ($i=1; $i <$count ; $i++) { 
	   	 	$num[] = $i;
	   	 }

	 	$pages = input('pages');
	
	 	if ($pages>=$count-1) {
	 		$pages = $count-1;
	 	}
	 	if ($pages<=0) {
	 		$pages = 1;
	 	}
	 	if (empty($pages)) {
	 		$pages=1;
	 	}
		if (!empty($goods)) {
			//判断商品数据不为空 

	 	if ($pages>1) {
	 		$page = 20*$pages;
	 		    foreach ($goods as $key => $value) {
	   			if ($key < $page && $key>$page-21) {
	   				$goodss[] = $value; 
	   				}
	    		}
	 	}else{
	 		$page = 20;
	 		    foreach ($goods as $key => $value) {
	   			if ($key < $page) {
	   				$goodss[] = $value; 
	   				}
	    		}
	 	}
	 		}else{
	 			$goodss='';
	 		}
	 
	 
		if (empty($pages)) {
	 		$pages=1;
	 	}
	 	 if (empty($num)) {
	 		$num=1;
	 	}

	 	// dump($goods);

		//三级分类数据
		 $cateRes=getTrees();
		$this->assign([
			'num'=>$num,
			'pages'=>$pages,
			'user'=>$user,
			'brand'=>$brand,
			'vipuser'=>$vipuser,
			'cateRes'=>$cateRes,
			'goods'=>$goodss,
			'pid'=>$pid,

		]);
		return view('goodslist/index');
	}
	//品牌商品列表
	public function list(Request $request)
	{

		//获取当前城市
		$city  = session('cityid');
		//获取品牌id
		$brandid = input('brandid');
		//获取金额条件
		$small = input('small');
		$big = input('big');
		//根据品牌查找vip充值
		$vip = Model('Buservip')->where('brandid',$brandid)->select();
		foreach ($vip as $key => $value) {
		 	$uid[] = $value['uid'];
		 }
		 if (!empty($uid)) {
		 //根据vip查询vip商户信息
		 $buser =  Model('Buser')->where('cityid',$city)->where('id','in',$uid)->select();
		 //查询符合条件的vip商户
		 foreach ($buser as $key => $value) {
		 	$uids[]= $value['id'];
		 }

			//推荐显示
		$vipuser = Model('Buservip')->where('uid','in',$uids)->where('status','1')->order('starttime')->limit('12')->count();
		if ($vipuser < 12) {
			$sumber = 12- $vipuser;
			$svip = Model('Buservip')->where('uid','in',$uids)->where('brandid',$brandid)->where('status','1')->order('starttime')->select();
			$vip = Model('Buservip')->where('uid','in',$uids)->where('status','0')->where('brandid',$brandid)->order('starttime')->limit($sumber)->select();
		}else{
			$vip= '';
			$svip = Model('Buservip')->where('uid','in',$uids)->where('status','1')->where('brandid',$brandid)->order('starttime')->limit('12')->select();
		}

		}else{
			$vipuser ='';
			$buser ='';
			$svip='';
		}



		 // $vipuser = Model('Buservip')->where('uid','in',$uids)->order('status','desc')->order('starttime')->limit('12')->select();
		//获取品牌名称
		//根据品牌id查找分类 根据三级分类查找当前属于所有三级分类的品牌
		//当前三级分类
		$threetree = Model('Brand')->where('id',$brandid)->find();
		//根据当前三级分类查找所有关联品牌
		$brand = Model('Brand')->where('cid',$threetree->cid)->select();
		//获取三级分类名称
	    $brandname = Model('Brand')->where('id',$brandid)->find();
	    //获取全部商户
	    $user = Model('Buser')->select();

	    // foreach ($user as $key => $value) {
	    // 	$buid[] = $value['id']; 
	    // }
	    //获取svip当中的商家id
	    if (!empty($svip)) {
	  
	    	foreach ($svip as $key => $value) {
	    		$svipuid[] =$value['uid']; 
	    		//获取属于svip的商品
	    		//判断是否有价格
	    		if (!empty($small) && !empty($big)) {
	    			dump($small);dump($big);
	    		$svipgoods = Model('Goods')->where('uid','in',$svipuid)->where('brandid',$brandid)->where('price','>=',$small)->where('price','<=',$big)->order('starttime')->select();
	    			
	    		}else{
	    			$svipgoods = Model('Goods')->where('uid','in',$svipuid)->where('brandid',$brandid)->order('starttime')->select();
	    			
	    		}

	    		
	    		$svipnum = Model('Goods')->where('uid','in',$svipuid)->where('brandid',$brandid)->order('starttime')->count();
	    	}
	    	//销毁svip获取普通用户
	    	foreach ($user as $key => $value) {
	    		foreach ($svipuid as $k => $v) {
	    			if ($value['id'] == $v) {

	    				unset($user[$key]);
	    			}
	    		}
	    	}
	    }else{
	    	$svipgoods ='';
	    	$svipnum=0;
	    }

	  
	    if (!empty($vip)) {
	    	foreach ($vip as $key => $value) {
	    		$vipuid[] = $value['uid'];
	    		//获取属于vip的商品

	    		//判断是否存在金额
	    		if (!empty($small) && !empty($big)) {

	    		$vipgoods = Model('Goods')->where('uid','in',$vipuid)->where('brandid',$brandid)
	    		->where('price','>=',$small)
	    		->where('price','<=',$big)
	    		->order('starttime')
	    		->select();

	    			}else{
	    				$vipgoods = Model('Goods')->where('uid','in',$vipuid)->where('brandid',$brandid)->order('starttime')->select();
	    			}
	    		
	    		$vipnum = Model('Goods')->where('uid','in',$vipuid)->where('brandid',$brandid)->order('starttime')->count();
	    	}

	    	//销毁vip获取普通用户
	    	foreach ($user as $key => $value) {
	    		foreach ($vipuid as $k => $v) {
	    			if ($value['id'] == $v) {
	    				
	    				unset($user[$key]);

	    			}
	    		}
	    	}
	    }else{
	    	$vipgoods = '';
	    	$vipnum ='';
	    }

	    if (!empty($user)) {
	    	foreach ($user as $key => $value) {
	    		$buserid[] = $value['id']; 
	    	}
	    	$goodsres = Model('Goods')->where('uid','in',$buserid)->where('brandid',$brandid)->order('starttime')->select();
	    	$goodsresnum = Model('Goods')->where('uid','in',$buserid)->where('brandid',$brandid)->order('starttime')->count();
	    }else{
	    	$goodsres = '';
	    	$goodsresnum = 0;
	    }
	   
	    //数据合并
	    // if (empty($vipgoods)) {
	    // 	$date = array_merge($svipgoods,$goodsres);
	    // }
	    // if (empty($svipgoods)) {
	    // 	$date = array_merge($vipgoods,$goodsres);
	    // }
	    // if (empty($goodsres)) {
	    // 	$date = array_merge($svipgoods,$vipgoods);
	    // }
	    

	 	$limit = (int)$svipnum+(int)$vipnum+(int)$goodsresnum;
	 	if ($limit !=0) {
	 		// 数据合并

	 		//【判断svip商品 vip商品 和 普通商户
	 	if (!empty($svipnum) && !empty($vipgoods) && !empty($goodsres)) {
	 		$date = array_merge($svipgoods,$vipgoods,$goodsres);
	 	}
	 	//判断svip vip商品不为空 并且普通商户为空
	 	if (!empty($svipnum) && !empty($vipgoods) && empty($goodsres)) {
	 		$date = array_merge($svipgoods,$vipgoods);
	 	}
	 	//判断svip 与普通商户不为空 vip为空
	    if (!empty($svipnum) && !empty($goodsres) && empty($vipgoods)) {
	 		$date = array_merge($svipgoods,$goodsres);
	 	}
	 	//判断vip 与普通商户不为空 svip为空
	    if (!empty($vipgoods) && !empty($goodsres && empty($svipgoods))) {
	 		$date = array_merge($vipgoods,$goodsres);
	 	}

	 	//判断svip vip商品为空 并且普通商户为空
	 	if (!empty($svipnum) && empty($vipgoods) && empty($goodsres)) {
	 		$date = array_merge($svipgoods);

	 	}
	 	//判断svip为空 与普通商户不为空 vip为空
	    if (empty($svipnum) && !empty($goodsres) && empty($vipgoods)) {
	 		$date = array_merge($goodsres);
	 	}
	 	//判断vip 与普通商户为空 svip为空
	    if (!empty($vipgoods) && empty($goodsres) && empty($svipgoods)) {
	 		$date = array_merge($vipgoods);
	 	}

	 	 //判断vip为空 与普通商户为空 svip为空
	    if (empty($vipgoods) && empty($goodsres) && empty($svipgoods)) {

	 		$date = '';
	 	}




	   	 $count = $limit/20;
	   	 $count = ceil($count)+1;
	   	 for ($i=1; $i <$count ; $i++) { 
	   	 	$num[] = $i;
	   	 }

	 	$pages = input('pages');
	
	 	if ($pages>=$count-1) {
	 		$pages = $count-1;
	 	}
	 	if ($pages<=0) {
	 		$pages = 1;
	 	}
	 	if (empty($pages)) {
	 		$pages=1;
	 	}
		if (!empty($date)) {
			//判断商品数据不为空
	
	 	if ($pages>1) {
	 		$page = 20*$pages;
	 		    foreach ($date as $key => $value) {
	   			if ($key < $page && $key>$page-21) {
	   				$dates[] = $value; 
	   				}
	    		}
	 	}else{
	 		$page = 20;
	 		    foreach ($date as $key => $value) {
	   			if ($key < $page) {
	   				$dates[] = $value; 
	   				}
	    		}
	 	}
	 		}else{
	 			$dates='';
	 		}
	 	}else{
	 			$dates='';
	 		}
		if (empty($pages)) {
	 		$pages=1;
	 	}
	 	 if (empty($num)) {
	 		$num=1;
	 	}
        $buserib = Model('Store')->bid();
	 	$buserres = Model('Buser')->where('id','in',$buserib)->limit('8')->select();
	    $busers = Model('Buser')->select();

		//三级分类数据
  	     $cateRes = Model('Catetree')->select();
  	     $cateRes = collection($cateRes)->toArray();
		 $cateRes=getTree($cateRes);
		 $this->assign([
		 	'cateRes'=>$cateRes,
		 	'vipuser'=>$vipuser,
		 	'buser'=>$buser,
		 	'svip'=>$svip,
		 	'vip'=>$vip,
		 	'brand'=>$brand,
		 	'brandname'=>$brandname,
		 	// 'svipgoods'=>$svipgoods,
		 	// 'vipgoods'=>$vipgoods,
		 	// 'goodsres'=>$goodsres,
		 	'pages'=>$pages,
		 	'num'=>$num,
		 	'dates'=>$dates,
		 	'busers'=>$busers,
            'buserres'=>$buserres,
		 ]);
		//展示视图


		return view('goodslist/list');
	}
	public function add()
	{
		for ($i=0; $i < 50; $i++) { 
					$data = ['goods_name'=>'老年人手机','pidd'=>'1','pids'=>'11','pid'=>'12','brandid'=>'4','price'=>'999','stock'=>'50','express'=>'圆通','singleton'=>'10','image'=>'/uploads/20181006\bb4724533efa43458b829419b123887d.jpg','image_url'=>'/goodsimage/20180929/38d83a8764e983feae23f303b802b4df.jpg','content'=>'测试测试测试','editorValue'=>'测试测试测试','starttime'=>'1','status'=>'0','uid'=>'1','multiplepiece'=>'5'];
			Model('goods')->insert($data);
		}


	}
	//搜索跳转
	public function brandlist()
	{
		$name = input('name');
		$res = Model('Brand')->where('brandname','like','%'.$name.'%')->find();
		if (empty($res)) {
			return $this->error('暂无该品牌');
		}
		return redirect('Goodslist/list',['brandid'=>$res->id]);
	}
}