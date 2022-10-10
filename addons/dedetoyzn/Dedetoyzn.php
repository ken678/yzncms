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
// | dedecmsv5.7数据转换插件
// +----------------------------------------------------------------------
namespace addons\dedetoyzn;

use think\Addons;

class Dedetoyzn extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/dedetoyzn.admin/index",
            "status"    => 1,
            "title"     => "数据转换",
            "tip"       => "",
            "listorder" => 0,
            "child"     => [
                [
                    "name"      => "admin/dedetoyzn.admin/init",
                    "status"    => 0,
                    "title"     => "初始化",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/dedetoyzn.admin/start",
                    "status"    => 0,
                    "title"     => "任务执行",
                    "tip"       => "",
                    "listorder" => 0,
                ],
            ],
        ],

    );

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
