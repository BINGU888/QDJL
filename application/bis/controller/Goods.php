<?php
    
namespace app\bis\controller;
use think\Controller;
use think\Request;
use app\bis\controller\Base;
use catetree\Catetree;
use \think\File;
    /**
    * 商户首页
    */
    class Goods extends Base
    {   
        public function index()
        {
          
            return view('goods/index');
        }
        public function desktop()
        {
            //桌面视图
            self::issession();
            return view('public/welcome');
        }

        public function add(Request $request)
        {
            $id = session('bid');
          
           
            //发布商品
            //分类
            $cateRes = getTrees();
            $this->assign([
                'cateRes'=>$cateRes,
                
                ]);

            return view('goods/add');
        }
        public function adds(Request $request)
        {

            //添加商品业务处理逻辑
            $data = input('post.');
            // 校验
            $validate=validate("Goodsvalidate"); //使用验证
          if(!$validate->scene("save")->check($data)){
                $this->error($validate->getError());//内置错误返回
            }
            if (empty($data['pidd'])) {
                echo "<script> alert('请选择分类');history.go(-1)</script>";die;
            }
            if (empty($data['pids'])){
                echo "<script> alert('请选择分类');history.go(-1)</script>";die;
            }
            if (empty($data['pid'])) {
                echo "<script> alert('请选择分类');history.go(-1)</script>";die;
            }
            if (empty($data['image_url'])) {
                return $this->error('请上传商品展示图');
            }

            $file = request()->file('image');
            if (empty($file)) {
                return $this->error('请上传封面图');
            }
            // dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 成功上传后 获取上传信息
  
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    $imag = $info->getSaveName();
                    $data['image']= '/uploads/'.$imag;
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
            
            // dump($data['image']);die;

        
            $id = session('bid');
            $data['uid'] = $id;
             unset($data['file']);
            $data['starttime'] = time();
           $res = Model('Goods')->insert($data);
           if ($res) {
               return $this->success('发布成功');
           }else{
               return $this->error('发布失败');
           }

        }




        //二级分类
        public function towtree(Request $request)
        {
                   //逻辑
            $onepid = input('onepid');

            if (!empty($onepid)) {
             //判断当前用户是否选择的一级分类
                //查询一级分类下的二级分类
                $towpid = Model('catetree')->where('pid',$onepid)->select();
            }else{
                $towpid = ['0'=>['name'=>'无分类']];
            }
            return $towpid;
        }
        //三级分类
         public function threetree(Request $request)
        {
                   //逻辑
            $towpid = input('towpid');
         $towpid = Model('catetree')->where('pid',$towpid)->select();
            if (!empty($towpid)) {

            }else{
                $towpid = ['0'=>['name'=>'无分类']];
            }
            return $towpid;
        }

        public function brand(Request $request)
        {
            $pid = input('towpid');
            $brand = Model('Brand')->where('cid',$pid)->select();
            
            if (!empty($brand)) {
             
            }else{
                $brand = ['0'=>['brandname'=>'暂无品牌']];
            }
             return $brand;

        }

        public function imageupload(Request $request)
        {
          $file = request()->file('file');

          // 移动到框架应用根目录/public/uploads/ 目录下
          $info = $file->move(ROOT_PATH . 'public' . DS . 'goodsimage');
          if($info){
               // 成功上传后 获取上传信息
               // 输出 jpg

                $data['path']=str_replace('\\',"/","/goodsimage/".$info->getSaveName()); 

                return json_encode($data['path']);//把路径ajax用json方式返回到视图中

           }else{
               // 上传失败获取错误信息
               echo $file->getError();
           }
        }


        //出售中的商品
        public function sell()
        {
            $id = session('bid');

            $res = Model('Goods')->where('status','0')->where('uid',$id)->select();
            $this->assign([
                'res'=>$res,
            ]);
            return view('goods/sell');
        }
        //商品下架操作
        public function down(Request $request)
        {
            $id = input('id');
            $data =  ['status'=>'1'];
            $res = Model('Goods')->where('id',$id)->update($data);
            if ($res) {
                return $this->success('下架成功');
            }else{
                return $this->error('下架失败');
            }
        }
        //修改商品
        public function exit(Request $request)
        {
           $id = input('id');
            $cateRes = Model('Catetree')->select();
            $cateRes = collection($cateRes)->toArray();
            $cateRes=getTree($cateRes);
            $res = Model('Goods')->where('id',$id)->find();
            //查询当前二级所有分类
            $towpid = Model('catetree')->where('pid',$res->pidd)->select();
            //查询当前三级所有分类
            $threeid = Model('catetree')->where('pid',$res->pids)->select();

            //查询当前三级分类下所有品牌
            $brand = Model('Brand')->where('cid',$res->pid)->select();
            $images = explode(',',$res['image_url']);
            // dump($images);die;
            $this->assign([
                'res'=>$res,
                'cateRes'=>$cateRes,
                'towpid'=>$towpid,
                'threeid'=>$threeid,
                'brand'=>$brand,
                'images'=>$images,
            ]);
            return view('goods/exit');
        }
        //修改商品
        public function update(Request $request)
        {
            //获取当前修改的商品id
             $id = input('id');
            //修改商品业务处理逻辑
            $data = input('post.');
            // 校验
            $validate=validate("Goodsvalidate"); //使用验证
          if(!$validate->scene("save")->check($data)){
                $this->error($validate->getError());//内置错误返回
            }
            if (empty($data['pidd'])) {
                echo "<script> alert('请选择分类');history.go(-1)</script>";die;
            }
            if (empty($data['pids'])){
                echo "<script> alert('请选择分类');history.go(-1)</script>";die;
            }
            if (empty($data['pid'])) {
                echo "<script> alert('请选择分类');history.go(-1)</script>";die;
            }

            $file = request()->file('image');
            $imagess = Model('Goods')->where('id', $id)->find();
            if (empty($data['image_url'])) {
                unset($data['image_url']);
            }else {
                $images = explode(',', $imagess['image_url']);
                if (!empty($images)){
                    foreach ($images as $value)
                    {
//                        dump('.'.$value);
                        unlink('.'.$value);
                    }
                }

            }

            if (!empty($file)) {
                if (!empty($imagess->image)){
                    unlink('.'.$imagess->image);
                };
            // dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 成功上传后 获取上传信息
  
                    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    $imag = $info->getSaveName();
                    $data['image']= '/uploads/'.$imag;
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
              }
            }else{
               unset($data['image']);
            }
            
             unset($data['file']);
           $res = Model('Goods')->where('id',$id)->update($data);
           if ($res) {
               return $this->success('发布成功');
           }else{
               return $this->error('发布失败');
           }




        }
        //仓库下架商品
        public function warehouse()
        {
            $id = session('bid');
            $res = Model('Goods')->where('status','1')->where('uid',$id)->select();
            $this->assign([
              'res'=>$res,
            ]);
            return view('goods/warehouse');
        }
        //上架商品
        public function shelves(Request $request)
        {
            $id = input('id');
            $res = Model('Goods')->where('id',$id)->update(['status'=>'0']);
            if ($res) {
              return $this->success('上架成功');
            }else{
              return $this->error('上架失败');
            }
        }
        //删除商品
        public function delete(Request $request)
        {
            $id = input('id');
            $imagess = Model('Goods')->where('id', $id)->find();
                if (!empty($imagess)) {
                    $images = explode(',', $imagess['image_url']);
                    if (!empty($images)) {
                        foreach ($images as $value) {
                            unlink('.' . $value);
                        }
                    }
                    if (!empty($imagess->image)) {
                        unlink('.' . $imagess->image);
                    };
                }else{
                    return $this->error('非法操作');
                }

            $res  = Model('Goods')->where('id',$id)->delete();
            if ($res) {
                return $this->success('删除成功');
            }else{
              return $this->error('删除失败');
            }
        }



        
    }