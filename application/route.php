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

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

use think\Route;

//Route::rule('路由表达式', '路由地址', '请求类型', '路由参数（数组）', '变量控制（数组）');

Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

Route::get('api/:version/theme/', 'api/:version.Theme/getSimpleList');

//Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');

Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');