<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
use think\App;

// 判断是否安装
if (!is_file(__DIR__ . '/../app/admin/command/Install/install.lock')) {
    header("location:./install.php");
    exit;
}
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http     = (new App())->http;
$response = $http->run();
$response->send();
$http->end($response);
