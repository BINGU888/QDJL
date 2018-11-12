<?php
namespace app\common;
use think\Request;
/**
 * 短信接口
 */
class Message
{

    public static function send($phone, $code)
    {

        header('content-type:text/html;charset=utf-8');

        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL

        $smsConf = array(
            'key' => '5b32e41aa66cf1f351d7ea14d3477db7', //您申请的APPKEY
            'mobile' => "$phone", //接受短信的用户手机号码
            'tpl_id' => "101917", //您申请的短信模板ID，根据实际情况修改
            'tpl_value' => "#code#=$code" //您设置的模板变量，根据实际情况修改
        );

        $content = juhecurl($sendUrl, $smsConf, 1); //请求发送短信

        if ($content) {
            $result = json_decode($content, true);
            $error_code = $result['error_code'];
            if ($error_code != 0) {
                //状态为0，说明短信发送成功
                echo "短信发送成功,短信ID：" . $result['result']['sid'];
                //返回内容异常，以下可根据业务逻辑自行修改
                $data = ['err' => 1001, 'error' => '验证码请求失败,请重新发送'];
                return join($data);
            }

        }
    }
}