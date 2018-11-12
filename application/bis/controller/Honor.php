<?php

namespace app\bis\controller;
use think\Controller;
use think\Request;
use app\bis\controller\Base;
use catetree\Catetree;
use \think\File;
/**
 * 荣誉管理
 */
class Honor extends Base
{
    //荣誉展示
    public function index()
    {
        //查询当前用户的所有展示
        $bid = session('bid');
        $res = Model('Honor')->where('bid',$bid)->select();
        $this->assign([
            'res'=>$res,
        ]);
        return view('honor/index');
    }

    //荣誉添加
    public function add()
    {
        return view('honor/add');
    }
    //添加逻辑
    public function adds(Request $request)
    {
        $data  = input('post.');
        $file = request()->file('image');

        if (empty($data['title']))
        {
            return $this->error('请填写标题');
        }
        if (empty($data['content'])){
            return $this->error('请填写内容');
        }
        if (empty($file))
        {
            return $this->error('请上传封面图');
        }
        //存储封面图
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $imag =  $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        $data['image'] = '/uploads/'.$imag;
        $data['bid'] = session('bid');
        $data['starttime'] = time();
        $res = Model('Honor')->insert($data);
        if ($res){
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }

    //修改操作
    public function exits(Request $request)
    {
        $id = input('id');
        $bid = session('bid');
        $res = Model('Honor')->where('id',$id)->where('bid',$bid)->find();
        $this->assign([
           'res'=>$res,
        ]);
        return view('Honor/exits');
    }
    //修改逻辑
    public function update(Request $request)
    {
        $id = input('id');
        $bid = session('bid');
            if (empty($id))
            {
                return $this->error('非法操作');
            }
            $data = input('post.');
            if (empty($data['title']))
            {
                return $this->error('标题不可为空');
            }
            $file = request()->file('image');

            if (empty($data['title']))
            {
                return $this->error('请填写标题');
            }
            if (empty($data['content'])){
                return $this->error('请填写内容');
            }
            //删除原有图片
            $images = Model('Honor')->where('id',$id)->find();
            if (!empty($images->image)){
                unlink('.'.$images->image);
            };
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

            $res = Model('Honor')->where('id',$id)->where('bid',$bid)->update($data);
            if ($res){
                return $this->success('修改成功');
            }else{
                return $this->error('修改失败');
            }
    }

    public function delete(Request $request)
    {
        $id = input('id');
        $bid = input('bid');
        $images = Model('Honor')->where('id',$id)->find();
        if (!empty($images->image)){
            unlink('.'.$images->image);
        };
        $res = Model('Honor')->where('id',$id)->where('bid',$bid)->delete();
        if ($res){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

}