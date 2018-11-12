<?php
namespace app\common\model;

use think\Model;

class Goods extends Model
{
    public function goodres()
    {
        return $this->hasMany('ugoods','gid');
    }

}