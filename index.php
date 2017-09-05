<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

//PHP版本检查
header("Content-type: text/html; charset=utf-8");
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die('require PHP > 5.4.0 !');
}

// 定义应用目录
define('APP_PATH', __DIR__ . '/apps/');

//定义模板目录
define('TEMPLATE_PATH', __DIR__ . '/templates/');

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
