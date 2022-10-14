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
// | 采集插件
// +----------------------------------------------------------------------
namespace addons\collection;

use think\Addons;

class Collection extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/collection.node/index",
            "status"    => 1,
            "title"     => "采集任务",
            "tip"       => "",
            "listorder" => 0,
            "child"     => [
                [
                    "name"      => "admin/collection.node/add",
                    "status"    => 0,
                    "title"     => "添加",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/edit",
                    "status"    => 0,
                    "title"     => "编辑",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/del",
                    "status"    => 0,
                    "title"     => "删除",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/col_url_list",
                    "status"    => 0,
                    "title"     => "采集",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/publist",
                    "status"    => 0,
                    "title"     => "文章列表",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/show",
                    "status"    => 0,
                    "title"     => "详情",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/content_del",
                    "status"    => 0,
                    "title"     => "采集数据删除",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/export",
                    "status"    => 0,
                    "title"     => "导出",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.node/import",
                    "status"    => 0,
                    "title"     => "导入",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.program/index",
                    "status"    => 0,
                    "title"     => "采集方案",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.program/add",
                    "status"    => 0,
                    "title"     => "添加",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.program/edit",
                    "status"    => 0,
                    "title"     => "编辑",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.program/del",
                    "status"    => 0,
                    "title"     => "删除",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/collection.program/import_content",
                    "status"    => 0,
                    "title"     => "导入模型",
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
