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
// | 地区插件
// +----------------------------------------------------------------------
namespace addons\area;

use think\Addons;

class Area extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"   => "admin/area.admin/index",
            "status" => 1,
            "title"  => "地区管理",
            "child"  => [
                [
                    "name"   => "admin/area.admin/add",
                    "status" => 0,
                    "title"  => "添加",
                ],
                [
                    "name"   => "admin/area.admin/edit",
                    "status" => 0,
                    "title"  => "编辑",
                ],
                [
                    "name"   => "admin/area.admin/del",
                    "status" => 0,
                    "title"  => "删除",
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
