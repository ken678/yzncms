<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 百度中文分词插件
// +----------------------------------------------------------------------
namespace addons\getwords;

use app\addons\util\Addon;

class Getwords extends Addon
{

    //插件信息
    public $info = [
        'name'        => 'getwords',
        'title'       => '中文分词',
        'description' => '百度自然语言处理',
        'status'      => 1,
        'author'      => '御宅男',
        'version'     => '1.0.0',
    ];

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
}
