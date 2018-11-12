<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
   //树形结构
use phpmailer\phpmailer;
 function getTree($items, $pid=0)
    {
        $tree = array();
        foreach($items as $k => $v)
        {
          if($v['pid'] == $pid)
          {       
           $v['child'] = getTree($items, $v['id']);
           $tree[] = $v;
           //unset($items[$k]);
          }
        }
          return $tree;
    }

  function getCity($items, $cid=0)
    {
        $tree = array();
        foreach($items as $k => $v)
        {
          if($v['cid'] == $cid)
          {       
           $v['child'] = getCity($items, $v['id']);
           $tree[] = $v;
           //unset($items[$k]);
          }
        }
          return $tree;
    }
  //城市数据
  function getCitys()
  {
      $cateCity = Model('City')->select();
      $cateCity = collection($cateCity)->toArray();
      return $cateCity=getCity($cateCity); 
  }


// 应用公共文件
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}
/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

 function curl_post($url, $params = array())
 {
     $data_string = json_encode($params);
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_HEADER, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
     curl_setopt(
         $ch, CURLOPT_HTTPHEADER,
         array(
             'Content-Type: application/json;',
         )
     );
     $data = curl_exec($ch);
     curl_close($ch);
     return ($data);
 }

 function curl_post1($url, $params = array())
 {
     $data_string = json_encode($params);
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_HEADER, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
     curl_setopt(
         $ch, CURLOPT_HTTPHEADER,
         array(
             'Content-Type: application/json; charset=utf-8',
             'Content-Length: ' . strlen($data_string),
         )
     );
     header("Content-type: image/jpeg");
     $data = curl_exec($ch);
     return $data;die;
     curl_close($ch);
     return $data;
 }

 function codesuccess($code,$msg)
{
    $date =['code'=>"$code",'msg'=>"$msg"];
    return json($date);
}

function codeerror($code,$msg)
{
    $date =['code'=>"$code",'msg'=>"$msg"];
    return json($date);
}

function ToKenid()
{
        $id = app\api\service\Token::getCurrentId();
        $date = Model('User')->where('id',$id)->find();
        if (empty($date)){
            return codeerror('10001','未注册');die;
        }
        return $id;
}



//邮箱发送

/**
* 发送邮箱
* @param type $data 邮箱队列数据 包含邮箱地址 内容
*/
function sendEmail($emails,$name,$content) {

  Vendor('phpmailer.phpmailer');
  $mail = new PHPMailer(); //实例化
  $mail->IsSMTP(); // 启用SMTP
  $mail->Host = 'smtp.126.com'; //SMTP服务器 以126邮箱为例子 
  $mail->Port = 465;  //邮件发送端口
  $mail->SMTPAuth = true;  //启用SMTP认证
  $mail->SMTPSecure = "ssl";   // 设置安全验证方式为ssl
  $mail->CharSet = "UTF-8"; //字符集
  $mail->Encoding = "base64"; //编码方式
  $mail->Username = 'bin36088842@126.com';  //你的邮箱 
  $mail->Password = '36088842@qq.com';  //你的密码 
  $mail->Subject = '测试邮箱标题'; //邮件标题  
  $mail->From = 'bin36088842@qq.com';  //发件人地址（也就是你的邮箱）
  $mail->FromName = '资源鸟';  //发件人姓名

  if($name && $content){



      $mail->AddAddress('1462263367@qq.com'); //添加收件人（地址，昵称）
      $mail->IsHTML(true); //支持html格式内容

      $mail->Body = $content; //邮件主体内容
      //发送成功就删除
      dump($mail->Send());die;
      if ($mail->Send()) {
        echo "发送成功";
      }else{
          echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息  
      }
    
  }           
}

function get_ip_city($ip)
{
$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
$ipinfo=json_decode(file_get_contents($url));
if($ipinfo->code=='1'){
return false;
}
$location = $ipinfo->data->city.'市';
return $location;
}

//三级分类
function getTrees(){
     $cateRes = Model('Catetree')->order('id')->select();
     $cateRes = collection($cateRes)->toArray();
     $cateRes=getTree($cateRes);
     return $cateRes;
}


//字体换行
/**
 * 自动给文字增加换行
 * @param int $str 字符串
 * @param int $num 字数 一个汉字算1位，2个字母或者数字为1位
 * @param string $line_break 换行符号 \n
 * @return string 返回字符串
 */
function break_string($str,$num){
    preg_match_all("/./u", $str, $arr);//将所有字符转成单个数组
  
  //print_r($arr);
  
    $strstr = '';
    $width = 0;
    $arr = $arr[0];
    foreach($arr as $key=>$string){
        $strlen = strlen($string);//计算当前字符的长度，一个字母的长度为1，一个汉字的长度为3
    //echo $strlen;
    
        if($strlen == 3){
      
            $width += 1;
      
        }else{
      
            $width += 0.5;
      
        }
    
        $strstr .= $string;
    
    //计算当前字符的下一个
        if(array_key_exists($key+1, $arr)){
            $_strlen = strlen($arr[$key+1]);
       if($_strlen == 3){
                $_width = 1;
            }else{
                $_width = 0.5;
            }
            if($width+$_width > $num){
                $width = 0;
                $strstr .= "\n";
            }
        }
 
    }
    return $strstr;
}

