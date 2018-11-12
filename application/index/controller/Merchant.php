<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use catetree\Catetree;
use \think\Validate;
use \think\File;
use app\common\Message;
class Merchant extends Base
{
	//商家入驻注册模块 
    public function index()
    {
        $id = session('id');
        $car = Model('User')->where('id',$id)->find();

        if (!empty($car->sid))
        {
            return $this->error('亲您已认证');
        }
    	//逻辑代码
      //获取全国省级城市
         $cateCity=getCitys(); 
         $cateRes=getTrees(); 
      
		    $this->assign([
			     'cateCity'=>$cateCity,
           'cateRes'=>$cateRes,
				]);
        //展示商家入驻页面

        return view('merchant/index');
    }
    //执行添加数据
    public function add()
    {
        $id = session('id');
        if (empty($id)){
            return $this->error('请登录');
        }
     	//添加数据
    	$data = input('post.');
        $buser = Model('User')->where('id',$id)->find();
        $data['name'] = $buser->name;
        $data['phone'] = $buser->phone;
        $data['password'] = $buser->password;
     	$validate=validate("Merchantvalidate"); //使用验证
     	  if(!$validate->scene("save")->check($data)){
                $this->error($validate->getError());//内置错误返回
            }

          unset($data['passwords'],$data['code'],$data['agree']);
          $data['starttime'] = time();

        $res = Model('Buser')->insert($data);
        $Buser = Model('Buser')->where('phone',$data['phone'])->find();
        $date = Model('User')->where('id',$id)->update(['sid'=>$Buser->id]);
        return $this->success('申请成功','bis/login/index');
    }
    //获取市级
    public function provinceid(Request $request)
    {
      $id = input('provinceid');
      $data = Model('City')->where('cid',$id)->select();
      if (empty($data)) {
        $data = ['0'=>'暂无该城市'];
      }
      return $data;
    }

    public function cityid(Request $request)
    {
      $id = input('cityid');
      $data = Model('City')->where('cid',$id)->select();

      if (empty($data)) {
        $data = ['0'=>'暂无该区'];
      }
      return $data;
    }

    public function imageupload(Request $request)
    {
          $file = request()->file('file');

       // 移动到框架应用根目录/public/uploads/ 目录下
          $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
          if($info){
               // 成功上传后 获取上传信息
               // 输出 jpg

                $data['path']=str_replace('\\',"/","/uploads/".$info->getSaveName()); 

                return json_encode($data['path']);//把路径ajax用json方式返回到视图中

           }else{
               // 上传失败获取错误信息
               echo $file->getError();
           }
    }


    public function send(Request $request)
    {
      $phone = input('phone');

      if (empty($phone)) {
        return $this->error('请输入手机号');die;
      }
      if(!preg_match("/^1[345678]{1}\d{9}$/",$phone)){
            return $this->error('手机号格式错误');die;
           
        }
       $Message = new Message();
       // $code = rand(100000,999999);
       $code = 123456;
       session('code',$code);
      $Message->send($phone,$code);
      return ['msg'=>'发送成功'];

    }
}
