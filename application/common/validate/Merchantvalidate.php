<?php
namespace app\common\validate;
use think\Validate;
class  Merchantvalidate extends Validate{
       //每个字段对应一个规则，这是第一层
         protected $rule=[
           ["name","require","用户名不能为空"],
           ["email","require|email","邮箱不能为空"],
           ["password","require","密码不能为空"],
           ["passwords","require","确认密码不能为空"],
           ["phone","require","手机号不能为空"],
           ["code","require","验证码不可为空"],
           ["provinceid","require","请选择省"],
           ["cityid","require","请选择市"],
           ["areaid","require","请选择区"],
           ["address","require","请输入您的地址"],
           ["agree","require","请同意平台注册协议"],
           ["contactname","require","请填写姓名"],
           ['contactphone',"require","请填写手机号"],
           ['contactqq',"require","请填写QQ号"]
  
         /*  ["id","number","必须是数字"],
           ["status","number|in:1,0,-1","必须是数字|必须是是0,-1,1"],*/
         ];
 
      //应用的场景
         protected $scene=[
          //商户入驻注册校验
          "save"=>["email","provinceid","contactname","contactphone","contactqq","cityid","areaid","address","agree"],
          //前台普通用户注册校验
          "user"=>["name","email","password","passwords","phone","code","agree"],
         ];
         
       
      
     } 