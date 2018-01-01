<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

//PHP版本检查
header("Content-type: text/html; charset=utf-8");
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    die('require PHP > 5.6.0 !');
}

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

//定义模板目录
define('TEMPLATE_PATH', __DIR__ . '/../templates/');

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
