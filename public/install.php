<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | [ 应用入口文件 ]
// +----------------------------------------------------------------------
// 建议安装完成后移除此文件
namespace think;

if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    header("Content-type: text/html; charset=utf-8");
    die('PHP 7.0.0 及以上版本系统才可运行~ ');
}

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);

// 加载基础文件
require ROOT_PATH . 'thinkphp' . DIRECTORY_SEPARATOR . 'base.php';

// 执行应用并响应
Container::get('app')->bind('\app\admin\command\Install')->run()->send();

/*PS:如果你的网站空间不支持域名绑定目录
1.请将index.php放置根目录(不推荐)
2.第24行改成 define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
 */
