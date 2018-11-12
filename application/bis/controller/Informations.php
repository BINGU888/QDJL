<?php

namespace app\bis\controller;
use think\Controller;
use think\Model;
use think\Request;
use app\bis\controller\Base;
/**
 * 商户资讯管理模块
 */
class Informations extends Base
{
    //资讯展示
    public function index()
    {
        $bid = session('bid');
        $res = Model('Informations')->where('bid',$bid)->select();
        $this->assign([
           'res'=>$res,
        ]);
        return view('informations/index');
    }
    //渲染资讯添加页
    public function add()
    {
        return view('informations/add');
    }
    //资讯添加逻辑
    public function adds(Request $request)
    {
        $data = input('post.');
        if (empty($data['title'])&&empty($data['content']))
        {
            return $this->error('请填写内容');
        }
        $data['bid'] = session('bid');
        $data['starttime'] = time();
        $res = Model('Informations')->insert($data);
        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }


    //修改页渲染
    public function exits(Request $request)
    {
        $id = input('id');
        $bid = session('bid');
        $res = Model('Informations')->where('id',$id)->where('bid',$bid)->find();
        $this->assign([
            'res'=>$res,
        ]);
        return view('informations/exits');
    }
    //修改逻辑
    public function update(Request $request)
    {
        $id = input('id');
        $bid =session('bid');
        $data = input('post.');
        $file = request()->file('image');
            if (empty($id)){
                return $this->error('非法操作');
            }
            if (empty($data['title'])&&empty($data['content']))
            {
                return $this->error('请填写内容');
            }

            if (!empty($file))
            {
                //存储封面图
                if ($file) {
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if ($info) {
                        $imag = $info->getSaveName();
                        $data['image'] = '/uploads/'.$imag;
                    } else {
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
            }
        $res = Model('Informations')->where('id',$id)->where('bid',$bid)->update($data);
        if ($res)
        {
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }
    }

    //删除逻辑
    public function delete(Request $request)
    {
        $id = input('id');
        $bid = input('bid');
        $res = Model('Honor')->where('id',$id)->where('bid',$bid)->delete();
        if ($res){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }
}