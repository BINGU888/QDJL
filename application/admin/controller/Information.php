<?php

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
/**
 * 商家模块
 */
class Information extends Base
{
    //展示咨询
    public function index()
    {
        $res = Model('Information')->select();
        $this->assign([
            'res'=>$res,
        ]);
        return view('information/index');
    }

    //发布资讯
    public function add(){
        return view('information/add');
    }
    //发布逻辑
    public function adds(Request $request)
    {
        $data = input('post.');
        if (empty($data['name']) &&empty($data['content'])&& $data['content'] ='')
        {
            return $this->error('信息填写不完整');
        }
        $file = request()->file('image');
        if (!empty($file))
        {
            //存储封面图
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $imag = $info->getSaveName();
                    $data['image'] = '/uploads/'.$imag;
                    $data['status'] = '2';
                } else {
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
        }
        $data['starttime'] = time();
        $res = Model('Information')->insert($data);
        if ($res){
            return $this->success('添加新闻成功');
        }else{
            return $this->error('添加新闻失败');
        }
    }
    //修改页面
    public function exits(Request $request)
    {
        $id = input('id');
        if (empty($id)){
            return $this->error('非法操作');
        }
        $res = Model('Information')->where('id',$id)->find();
        $this->assign([
            'res'=>$res,
        ]);
        return view('information/exits');
    }
    //修改逻辑
    public function update(Request $request)
    {
        $id = input('id');
        if (empty($id))
        {
            return $this->error('非法操作');
        }

        $data = input('post.');
        if (empty($data['name']))
        {
            return $this->error('信息不完整');
        }
        //查询当前用户信息

        $file = request()->file('image');
        if (!empty($file))
        {
            //删除原有图片
            $images = Model('Information')->where('id',$id)->find();
            if (!empty($images->image)){
                unlink('.'.$images->image);
            };
            //存储封面图
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $imag = $info->getSaveName();
                    $data['image'] = '/uploads/'.$imag;

                    $data['status'] = '2';
                } else {
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
        }

        $res = Model('Information')->where('id',$id)->update($data);
        if ($res){
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }
    }

    //删除新闻
    public function delete(Request $request)
    {
        $id = input('id');
        if (empty($id))
        {
            return $this->error('非法操作');
        }
        //删除原有图片
        $images = Model('Information')->where('id',$id)->find();
        if (!empty($images->image)){
            unlink('.'.$images->image);
        };
        $res = Model('Information')->where('id',$id)->delete();
        if ($res){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

}