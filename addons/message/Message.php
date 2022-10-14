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
// | 消息插件
// +----------------------------------------------------------------------
namespace addons\message;

use think\Addons;

class Message extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            //地址，[模块/]控制器/方法
            "name"      => "admin/message.message/index",
            "status"    => 1,
            "title"     => "短消息",
            "icon"      => "icon-systemprompt",
            "listorder" => 3,
            //子菜单列表
            "child"     => [
                [
                    "name"  => "admin/message.message/index",
                    "title" => "消息列表",
                ],
                [
                    "name"  => "admin/message.group/index",
                    "title" => "群发消息列表",
                ],
                [
                    "name"  => "admin/message.group/add",
                    "title" => "群发消息",
                ],
                [
                    "name"  => "admin/message.message/add",
                    "title" => "发消息",
                ],
                [
                    "name"  => "admin/message.message/del",
                    "title" => "删除短消息",
                ],
                [
                    "name"  => "admin/message.group/del",
                    "title" => "删除群发消息",
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

    //或者run方法
    public function userSidenavAfter($content)
    {
        return $this->fetch('userSidenavAfter');

    }
}
