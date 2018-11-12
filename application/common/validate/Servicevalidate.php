<?php
namespace app\common\validate;
use think\Validate;
class  Servicevalidate extends Validate{
    //每个字段对应一个规则，这是第一层
    protected $rule=[
        ["onetree","require","请选择一级分类"],
        ["towtree","require","请选择二级分类"],
        ["threetree","require","请选择三级分类"],
        ["brandid","require","请选择品牌"],
        ["status","require","请选择服务"],
        /*  ["id","number","必须是数字"],
          ["status","number|in:1,0,-1","必须是数字|必须是是0,-1,1"],*/
    ];

    //商户开通vip服务
    protected $scene=[
        "save"=>["onetree","towtree","threetree","brandid","status"],

    ];



}