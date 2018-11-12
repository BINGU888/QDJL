<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
class Shipping extends Base
{
    //收货地址页
    public function index()
    {
        //查询当前用户的收货信息
        $id = session('id');
        //查询全部城市
        $city = Model('City')->select();
        $res = Model('Shipping')->where('uid',$id)->select();
        //三级分类数据
        $cateCity=getCitys();
        $cateRes=getTrees();
        $this->assign([
            'cateRes'=>$cateRes,
            'cateCity'=>$cateCity,
            'res'=>$res,
            'city'=>$city,
        ]);
        //显示视图
        return view('shipping/index');
    }
    public function add(Request $request)
    {
    	$data = input('post.');
        $id = session('id');
    	$data['uid'] = $id;
        $validate=validate("ShippingValidate"); //使用验证
        if(!$validate->scene("useraddress")->check($data)){
            $this->error($validate->getError());//内置错误返回
        }

    	//将目前用户所有收货地址更新不是默认地址
    	$date = Model('Shipping')->where('uid',$id)->where('status','1')->find();
        if (!empty($date))
        {
            Model('Shipping')->where('uid',$id)->where('status','1')->update(['status'=>'0']);
        }

    	$res = Model('Shipping')->insert($data);
    	if ($res)
    	{
            $url = cookie('Shipping');
            if (!empty($url)){
                return $this->redirect($url);
            }
    	    return $this->success('添加成功');
        }else{
    	    return $this->error('添加失败');
        }
    }
    //修改页面
    public function sexit(Request $request)
    {
        $id = input('id');
        $uid = session('id');
        $res = Model('Shipping')->where('id',$id)->where('uid',$uid)->find();
        //查询全部城市
        $city = Model('City')->select();
        //三级分类数据
        $cateCity=getCitys();
        $cateRes=getTrees();
        $this->assign([
            'cateRes'=>$cateRes,
            'cateCity'=>$cateCity,
            'res'=>$res,
            'city'=>$city,
        ]);
        return view('shipping/sexit');
    }
    //修改
    public function update(Request $request)
    {
        $uid = session('id');
        $id = input('id');
        $date = input('post.');
        $validate=validate("ShippingValidate"); //使用验证
        if(!$validate->scene("useraddress")->check($date)){
            $this->error($validate->getError());//内置错误返回
        }
        $res = Model('Shipping')->where('id',$id)->where('uid',$uid)->update($date);
        if ($res)
        {
            return $this->success('修改成功','Shipping/index');
        }else{
            return $this->error('更新失败');
        }
    }

    public function delete(Request $request)
    {
        $id = input('id');
        $uid = session('id');
        //直行删除操作
        $res = Model('Shipping')->where('id',$id)->where('uid',$uid)->delete();
        if ($res){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    //修改状态
    public function status(Request $request)
    {
        $id = input('id');
        $uid = session('id');
        $date = Model('Shipping')->where('status','1')->update(['status'=>'0']);
        $res = Model('Shipping')->where('id',$id)->where('uid',$uid)->update(['status'=>'1']);
        if ($res)
        {
            return $this->success('默认地址成功');
        }else{
            return $this->error('默认地址失败');
        }
    }

}
