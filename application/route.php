<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

    Route::rule('goods/apy','Settlement/notify_url');
    Route::rule('service/pay','Settlement/notify_urls');
    Route::rule('upgrade/pay','Settlement/upgrade');

    /****
     *
     * 渠道精灵api
     *
     ****/
    //登录接口
    Route::rule('api/login','api/Login/index');
    //商家接口
    Route::rule('api/buser','api/Buser/index');
    //城市接口
    Route::rule('api/city','api/City/index');
    //分类接口
    Route::rule('api/classification','api/Classification/index');
    //搜索接口
    Route::rule('api/search','api/classification/index');
    //获取全部用户接口
    Route::rule('api/user','api/User/index');