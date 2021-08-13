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
// | 队列插件
// +----------------------------------------------------------------------
namespace addons\queue;

use sys\Addons;
use think\Loader;

class Queue extends Addons
{
    //安装
    public function install()
    {
        return true;
    }

    //卸载
    public function uninstall()
    {
        return true;
    }

    /**
     * 添加命名空间
     */
    public function appInit()
    {
        Loader::addNamespace('think', ADDON_PATH . 'queue' . DS . 'SDK' . DS . 'src' . DS);
        if (request()->isCli()) {
            \think\Console::addDefaultCommands([
                "think\\queue\\command\\Work",
                "think\\queue\\command\\Restart",
                "think\\queue\\command\\Listen",
                "think\\queue\\command\\Subscribe",
            ]);
        }
    }

}
