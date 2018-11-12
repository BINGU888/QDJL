<?php
namespace app\common\model;

use think\Model;
use think\Controller;
class Buservip extends Model
{
    //获取根据分类svip
	public  function svip($pid,$status,$cityid)
    {
//        查询所有符合三级id的用户
        $res = Model('Buservip')->where('threetree',$pid)->where('pay','1')->where('status',$status)->select();

        foreach ($res as $key=>$value)
        {
            $id[] = $value['uid'];
        }
        if (empty($id)){
           return [];
        }
        $data = Model('Buser')->where('id','in',$id)->select();
        foreach($data as $key=>$value)
        {
            $value['level'] = $status;
        }
        return $data;
    }

    public function bid($pid,$cityid)
    {
        //查询所有符合三级id的用户
        $res = Model('Buservip')->where('threetree',$pid)->where('pay','1')->select();
        //获取所有商家用户
        $busers = Model('Buser')->select();
        foreach ($busers as $Key => $value)
        {
            foreach($res as $k =>$v)
            {
                //销毁所有商户属于svip vip的
                if ($value['id'] == $v['uid']){
                    unset($busers[$Key]);
                }

            }
        }

        foreach($busers as $key=>$value)
        {
            //获取普通商户id
            $uid[] = $value['id'];
            //查询商户商品
            $goodsres[] = Model('Goods')->where('uid','in',$uid)->select();
        }
        foreach ($goodsres as $k=>$v)
        {
            foreach($v as $key=>$value){
                foreach ($res as $kg =>$vg)
                    //判断当前普通商户的商品 是否与当前分类一致
                if ($value['pid'] == $vg['threetree'])
                {
                    //获取当前分类与当前一致的商户
                    $gid[] = $value['uid'];
                }
            }
        }
        if (empty($gid)){
            $gid=[];
        }
        //查询当前塞选好的普通商户信息
        $busers = Model('Buser')->where('id','in',$gid)->select();
        foreach($busers as $key=>$value)
        {
            //插入普通商户状态
            $value['level'] = 2;
        }
        //返回
        return $busers;
    }

    public function vips($brandid,$status)
    {
        //查询当前svip用户
        $svip = Model('Buservip')->where('brandid',$brandid)->where('status',$status)->where('pay',1)->select();
        foreach($svip as $key=>$value)
        {
            $uid = $value['uid'];
        }
        $res = Model('Buser')->where('id','in',$uid)->select();
        foreach ($res as $key=>$value)
        {
            $value['level'] = $status;
        }
        return $res;
    }
    //获取品牌普通用户
    public function bids($brandid)
    {
        //查询当前品牌属于svip vip 的用户
        $vip = Model('Buservip')->where('brandid',$brandid)->where('pay',1)->select();
        foreach($vip as $key=>$value)
        {
            $vipid[] = $value['uid'];
        }

        //查询所有商户
        $buser = Model('Buser')->select();
        foreach ($buser as $key=>$value)
        {
            foreach ($vipid as $k => $v)
            {
                if ($value['id'] == $v)
                {
                    unset($buser[$key]);
                }
            }
        }

        foreach($buser as $key =>$value)
        {
            //获取全部普通用户id
            $uid[] = $value['id'];
            //根据普通用户id 查找商品
            $goodsres[] = Model('Goods')->where('uid','in',$uid)->select();
        }
        $brandids = [1=>$brandid];
        //塞选当前普通商户已发布过 当前品牌的商品 将其id获取到
        foreach($goodsres as $key=>$value)
        {
            foreach ($value as $gk =>$gv)
            foreach($brandids as $k=>$v) {
                if ($gv['brandid'] == $v) {
                    $uids[] = $gv['uid'];
                }
            }
        }

        //根据赛选好的商户id进行查询
        $busers = Model('Buser')->where('id','in',$uid)->select();
        //插入当前塞选成功的商户状态
        foreach($busers as $key=>$value)
        {
            $value['level'] = 2;
        }

        return $busers;
    }
}