<?php
namespace app\common\model;

use think\Model;

class Usergoods extends Model
{
    public function goodres()
    {
        return $this->hasMany('ugoods');
    }
}