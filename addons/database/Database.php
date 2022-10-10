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
// | 数据库插件
// +----------------------------------------------------------------------
namespace addons\database;

use think\Addons;

class Database extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/database.admin/index",
            "status"    => 1,
            "title"     => "数据库备份",
            "tip"       => "",
            "listorder" => 0,
            "child"     => [
                [
                    "name"      => "admin/database.admin/restore",
                    "status"    => 0,
                    "title"     => "备份还原",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/database.admin/del",
                    "status"    => 0,
                    "title"     => "删除备份",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/database.admin/repair",
                    "status"    => 0,
                    "title"     => "修复表",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/database.admin/optimize",
                    "status"    => 0,
                    "title"     => "优化表",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/database.admin/import",
                    "status"    => 0,
                    "title"     => "还原表",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/database.admin/export",
                    "status"    => 0,
                    "title"     => "备份数据库",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/database.admin/download",
                    "status"    => 0,
                    "title"     => "备份数据库下载",
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
