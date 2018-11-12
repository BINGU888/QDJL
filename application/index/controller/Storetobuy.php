<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
class Storetobuy extends Base
{
    //到店购买模块
    public function index(Request $request)
    {
        $id = input('id');
        $cateRes = getTrees();
        $this->assign([
           'id'=>$id,
           'cateRes'=>$cateRes,
        ]);
        return view('Storetobuy/index');
    }
    public function store(Request $request)
    {
        $data = input('post.');

        if (empty($data['name']))
        {
            return $this->error('请输入姓名');
        }
        if (empty($data['phone']))
        {
            return $this->error('请填写手机号');
        }
        $date = Model('Goods')->where('id',$data['gid'])->find();
        $data['uid'] = $date->uid;
        $data['starttime'] = time();
        $res = Model('message')->insert($data);
        if ($res){
            return $this->success('留言成功');
        }else{
            return $this->error('留言失败');
        }
    }
}