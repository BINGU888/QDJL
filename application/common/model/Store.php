<?php
namespace app\common\model;

use think\Model;

class Store extends Model
{

    public function profile()
    {
        return $this->hasOne('Profile');
    }
    //svip vip 普通商户
    public function vips()
    {	//查询全部svip

    	$svipuser = Model('Buservip')->where('status','1')->order('starttime')->select();
    		foreach ($svipuser as $key => $value) {
        	#获取svip全部id
            $svipid[] = $value['uid'];
        	}
        //获取vip用户
        $vipuser  = Model('Buservip')->where('status','0')->order('starttime')->select();
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
          	if (!empty($vipid)){


          	//去除重复id
        foreach ($vipid as $vipk => $svipv) {
        	if ($value['id'] == $svipv) {
            	unset($vipid[$key]);
              }
            }
            }else{
          	    $vipid = null;
                }

        //获取普通商户id 
        foreach ($buser as $key => $value) {
        	$bid[] = $value['id'];
        }
        if (empty($bid)){
            $bid = null;
        }
            //合并当前用户
           $res = $vipid;
         	return $res;
        }
   	 }
    //svip vip 普通商户
    public function bid()
    {   
        $city = session('cityid');
        //查询所有商户
        $buser = Model('Buser')->where('cityid',$city)->select();
        //查询所有vip商户
        $vips = Model('Buservip')->where('pay','1')->select();
        //获取普通商户id
        foreach ($buser as $key => $value) {
            foreach ($vips as $k => $v) {
                if ($value['id'] == $v['uid']) {
                    unset($buser[$key]);
                }
            }
        }

        foreach ($buser as $key => $value) {
            $bid[] = $value['id'];
        }
        if (empty($bid)){
            $bid =null;
        }
        return $bid;
    }

        //svip 
    public function svip()
    {   
        $city = session('cityid');
        //查询全部商户
        $buser = Model('Buser')->where('cityid',$city)->select();
        //查询全部svip
        $svip =Model('Buservip')->where('status','1')->where('pay','1')->select();
     
        //得到vip用户
        foreach ($buser as $key => $value) {
            foreach ($svip as $k => $v) {
                if ($value['id']==$v['uid']) {
                    $svipid[] = $v;
                }
            }
        }

        foreach ($svipid as $key => $value) {
           $svipids[] = $value['uid'];
        }
            return $svipids;
    }

    public function vip()
    {   
        $city = session('cityid');
        //查询全部商户
        $buser = Model('Buser')->where('cityid',$city)->select();
        //查询全部svip
        $svip =Model('Buservip')->where('status','1')->where('pay','1')->select();
        //获取vip商户
        $vip = Model('Buservip')->where('status','0')->where('pay','1')->select();
        //得到vip用户
        foreach ($buser as $key => $value) {
            foreach ($svip as $k => $v) {
                if ($value['id']==$v['uid']) {
                    $svipid[] = $v;
                }
            }
        }

        foreach ($svipid as $key => $value) {
            foreach ($vip as $k => $v) {
               if ($value['uid'] == $v['uid']) {
                   unset($vip[$k]);
               }
            }
        }
        foreach ($vip as $key => $value) {
            $vips[] = $value['uid'];
        }
        if (empty($vips)){
            $vips = null;
        }
        return $vips;
           
    }
     
     

}