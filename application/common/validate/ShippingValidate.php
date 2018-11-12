<?php
namespace app\common\validate;
use think\Validate;
class  ShippingValidate extends Validate{
    //每个字段对应一个规则，这是第一层
    protected $rule=[
        ["name","require","收货人姓名不能为空"],
        ["phone","require","手机号不能为空"],
        ["provinceid","require","请选择省"],
        ["cityid","require","请选择市"],
        ["areaid","require","请选择区"],
        ["address","require","请输入您的详细地址"],
        /*  ["id","number","必须是数字"],
          ["status","number|in:1,0,-1","必须是数字|必须是是0,-1,1"],*/
    ];

    //应用的场景
    protected $scene=[
        //用户添加收货地址
        "useraddress"=>["name","provinceid","cityid","areaid","phone","address"],
    ];



}