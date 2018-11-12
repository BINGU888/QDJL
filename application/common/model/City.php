<?php
namespace app\common\model;

use think\Model;

class City extends Model
{

    public function profile()
    {
        return $this->hasOne('Profile');
    }
}