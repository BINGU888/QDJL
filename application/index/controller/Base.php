<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use catetree\Catetree;
class Base extends Controller
{
    //获取当前城市
    public function _initialize()
    {
        if (!session('cityid')) {
            # code...

            //获取当前ip
            // $ip = $request->ip();
            $city = get_ip_city('39.71.83.117');
            $res = Model('City')->where('name',$city)->find();
            if (empty($res)) {
                session('cityid','370100');
            }else{
                //存储当前城市id
                session('cityid',$res->id);
            }

        }

          session('cityid');


    }



	//商家店铺分类塞选
	public function storetree($trees)
	{

		$all =[];
 		
 		foreach ($trees as $k => $v) {
 			//判断 $all 键是否存在当前$v['onepid']
 			if (!array_key_exists($v['onepid'],$all) ) {
 				$all[$v['onepid']] = [];
 				$all[$v['onepid']]['child']=[];
 				$onename = Model('Catetree')->where('id',$v['onepid'])->find();
 				$all[$v['onepid']]['data']=['name'=>$onename->name];
 			}

 			if (!array_key_exists($v['towpid'], $all[$v['onepid']]['child'])) {
 				$towname = Model('Catetree')->where('id',$v['towpid'])->find();
 				$all[$v['onepid']]['child'][$v['towpid']]['data'] = ['name'=>$towname->name];
 				$all[$v['onepid']]['child'][$v['towpid']]['child'] = [];
 			}
 			$threename = Model('Catetree')->where('id',$v['threepid'])->find();
 			$all[$v['onepid']]['child'][$v['towpid']]['child'][$v['threepid']] = ['name' => $threename->name];
  		}

  		return $all;
	}
}