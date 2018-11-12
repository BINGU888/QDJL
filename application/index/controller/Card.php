<?php
namespace app\index\controller;
use think\Request;
use catetree\Catetree;
use app\index\controller\Base;
use app\index\controller\Cityip;
use app\index\controller\Pinyin;
class Card extends Base
{
	//渠道通 （名片）
	public function index()
	{	
		//获取当前用户
		$id = session('id');
		$res = Model('Card')->where('uid',$id)->find();
		if (empty($res)) {
			$res =null;
		}

		//获取城市分类
		$cateCity = getCitys();
		//查询全部城市
		$city = Model('City')->select();
		//三级分类数据
		 $cateRes=getTrees();
		 $this->assign([
		 	'cateRes'=>$cateRes,
		 	'cateCity'=>$cateCity,
		 	'res'=>$res,
		 	'city'=>$city,
		 ]);
		return view('card/index');
	}

	//渠道通数据添加
	public function add(Request $request)
	{
		$id = session('id');
			if (empty($id)) {
				return $this->success('请前去登录','login/index');
			}
		$data = input('post.');

		$validate=validate("Cardvalidate"); //使用验证
     	  if(!$validate->scene("card")->check($data)){
                $this->success($validate->getError());//内置错误返回
            }
        	unset($data['agree']);
            $data['starttime']=time();
            $data['uid'] = $id;	
		$date = Model('Card')->where('uid',$id)->find();
		if (!empty($date)) {
			$res = Model('Card')->where('uid',$id)->update($data);
			if ($res) {
				return $this->success('修改成功');
			}else{
				return $this->success('修改失败');
			}
		}
		//转换首字母
	    $pinyin = new Pinyin();
		$data['letter']=$pinyin->getFirstChar($data['company']);
		$res = Model('Card')->save($data);
			if ($res) {
				return $this->success('发布成功');
			}else{
				return $this->error('发布失败');
			}
	}

	//渠道通模块展示
	public function library(Request $request)
	{
		//获取渠道库数据
		//判断是否存在字母 根据字母进行查询
		$letter = input('letter');
			if (empty($letter)) {
				$res = Model('Card')->where('status','1')->select();
			}else{
				$res = Model('Card')->where('status','1')->where('letter',$letter)->select();
			}
		//26字母
		$letters = $this->letters();
		//查询全部城市
		$city = Model('City')->select();
		//获取城市分类
		$cateCity = getCitys();
		//三级分类数据
		 $cateRes=getTrees();
		 $this->assign([
		 	'cateRes'=>$cateRes,
		 	'cateCity'=>$cateCity,
		 	'letters'=>$letters,
		 	'city'=>$city,
		 	'res'=>$res,
		 	'letter'=>$letter,
		 ]);	
		return view('card/library');
	}
	 //塞选城市数据 关键词
	public function citys(Request $request)
	{
		 //省id
        $provinceid = input('provinceid');
        //市id
        $cityid = input('cityid');
        //区id
        $areaid = input('areaid');
        //name 搜索内容
        $company = input('name');
    
        //判断省id存在 市id 存在 区id存在 name搜索内容存在
        if (!empty($provinceid)&& !empty($cityid) && !empty($areaid) && !empty($company)) {
        	$res = Model('Card')->where('provinceid',$provinceid)->where('cityid',$cityid)->where('areaid',$areaid)->where('company','like','%'.$company.'%')->where('status','1')->select();
        }
         //判断省id存在 市id不存在  区id不存在 name搜索内容不存在
        if (!empty($provinceid)&& empty($cityid) && empty($areaid) && empty($company)) {
        	$res = Model('Card')->where('provinceid',$provinceid)->where('status','1')->select();
        }
         //判断省id不存在 市id存在  区id不存在 name搜索内容不存在
        if (empty($provinceid)&& !empty($cityid) && empty($areaid) && empty($company)) {
        	$res = Model('Card')->where('cityid',$cityid)->where('status','1')->select();
        }
         //判断省id不存在 市id不存在  区id存在 name搜索内容不存在
        if (empty($provinceid)&& empty($cityid) && !empty($areaid) && empty($company)) {
        	$res = Model('Card')->where('areaid',$areaid)->where('status','1')->select();
        }
          //判断省id不存在 市id不存在  区id不存在 name搜索内容存在
        if (empty($provinceid)&& empty($cityid) && empty($areaid) && !empty($company)) {
        	$res = Model('Card')->where('company','like','%'.$company.'%')->where('status','1')->select();
        }
           //判断省id存在 市id存在  区id不存在 name搜索内容不存在
        if (!empty($provinceid)&& !empty($cityid) && empty($areaid) && empty($company)) {
        	$res = Model('Card')->where('provinceid',$provinceid)->where('cityid',$cityid)->where('status','1')->select();
        }
           //判断省id存在 市id存在  区id存在 name搜索内容不存在
        if (!empty($provinceid)&& !empty($cityid) && !empty($areaid) && !empty($company)) {
        	$res = Model('Card')->where('provinceid',$provinceid)->where('cityid',$cityid)->where('areaid',$areaid)->where('status','1')->select();
        }
           //判断省id不存在 市id存在  区id存在 name搜索内容不存在
        if (empty($provinceid)&& !empty($cityid) && !empty($areaid) && empty($company)) {
        	$res = Model('Card')->where('cityid',$cityid)->where('areaid',$areaid)->where('status','1')->select();
        }
           //判断省不id存在 市id存在  区id存在 name搜索内容存在
        if (empty($provinceid)&& !empty($cityid) && !empty($areaid) && !empty($company)) {
        	$res = Model('Card')->where('cityid',$cityid)->where('areaid',$areaid)->where('company','like','%'.$company.'%')->where('status','1')->select();
        }
          //判断省id存在 市id不存在  区id不存在 name搜索内容存在
        if (!empty($provinceid) && empty($cityid) && empty($areaid) && !empty($company)) {
        	$res = Model('Card')->where('provinceid',$provinceid)->where('company','like','%'.$company.'%')->where('status','1')->select();
        }
           //判断省id不存在 市id 不存在 区id不存在 name搜索内容不存在
        if (empty($provinceid)&& empty($cityid) && empty($areaid) && empty($company)) {
        	$res = Model('Card')->where('status','1')->select();
        }

        if (empty($res)) {
        	$res = null;
        }

        $letter = null;
        //26字母
		$letters = $this->letters();
		//查询全部城市
		$city = Model('City')->select();
		//获取城市分类
		$cateCity = getCitys();
		//三级分类数据
		 $cateRes=getTrees();
		 $this->assign([
		 	'cateRes'=>$cateRes,
		 	'cateCity'=>$cateCity,
		 	'letters'=>$letters,
		 	'city'=>$city,
		 	'res'=>$res,
		 	'letter'=>$letter,
		 ]);	
		return view('card/library');

	}


	 //ajax渠道通弹框
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
        $data = Model('Card')->where('id',$id)->find();
        $provinceid = Model('City')->where('id',$data->provinceid)->find();
        $city = Model('City')->where('id',$data->cityid)->find();
        $data['province'] = $provinceid->name;
        $data['city'] = $city->name;
        return $data;
    }

	//26字母
    public function letters()
    {
      $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
      return $letters;
    }
}