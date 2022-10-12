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
// | API文档插件
// +----------------------------------------------------------------------
namespace addons\apidoc;

use think\Addons;
use think\facade\Route;

class Apidoc extends Addons
{
    //安装
    public function install()
    {
        //复制配置文件
        $route_file = ADDON_PATH . str_replace("/", DS, "apidoc/install/apidoc.php");
        copy($route_file, ROOT_PATH . 'config' . DS . 'apidoc.php');
        return true;
    }

    //卸载
    public function uninstall()
    {
        //删除配置文件
        if (file_exists(ROOT_PATH . 'config' . DS . 'apidoc.php')) {
            unlink(ROOT_PATH . 'config' . DS . 'apidoc.php');
        }
        return true;
    }

    /**
     * 添加命名空间
     */
    public function appInit()
    {
        Route::group('apidoc', function () {
            Route::rule('', 'index/apidoc/Index');
            Route::rule('config', 'index/apidoc/getConfig');
            Route::rule('data', 'index/apidoc/getList');
            Route::rule('auth', 'index/apidoc/verifyAuth');
        });

    }
}
