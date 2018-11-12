<?php
namespace app\common\validate;
use think\Validate;
class  Goodsvalidate extends Validate{
       //每个字段对应一个规则，这是第一层
         protected $rule=[
           ["goods_name","require","商品名称不能为空"],
     //       ["pidd","require","请选择一级分类"],
     //       ["pids","require","请选择二级分类"],
		   // ["pid","require","请选择三级分类"],
		   ["brandid","require","请选择品牌"],
           ["price","require","请输入价格"],
           ["stock","require","请输入库存"],
           ["express","require","请选择快递"],
           // ["image","require","请上传封面图片"],
           ["content","require","商品描述必须填写"],
           ["editorValue","require","商品规格必须填写"],
         /*  ["id","number","必须是数字"],
           ["status","number|in:1,0,-1","必须是数字|必须是是0,-1,1"],*/
         ];
 
      //应用的场景，这是第二层
         protected $scene=[
          "save"=>["goods_name","pidd","pids","pid","brandid","price","stock","express","image","content","editorValue"],
          
         ];

       
      
     } 