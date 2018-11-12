<?php
namespace app\common\validate;
use think\Validate;
class  Cardvalidate extends Validate{
       //每个字段对应一个规则，这是第一层
         protected $rule=[
         ["name","require","用户名不能为空"],
           ["company","require","公司名不能为空"],
           ["phone","require","手机号不能为空"],
           ["qq","require","qq号不能为空"],
           ["provinceid","require","请选择省"],
           ["cityid","require","请选择市"],
           ["areaid","require","请选择区"],
           ["address","require","请填写公司地址"],
           ["content","require","请填写公司主营业务"],
           ["agree","require","请同意平台注册协议"],
         ];
 
      //应用的场景
         protected $scene=[
          //渠道库
          "card"=>["name","email","password","passwords","phone","code","provinceid","cityid","areaid","address","agree"],
         ];
      
     } 