<?php
return [
    [
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid"  => 45,
        //地址，[模块/]控制器/方法
        "route"     => "message/message/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type"      => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status"    => 1,
        //名称
        "name"      => "短消息",
        //图标
        "icon"      => "icon-systemprompt",
        //备注
        "remark"    => "",
        //排序
        "listorder" => 3,
        //子菜单列表
        "child"     => [
            [
                "route"  => "message/message/index",
                "type"   => 1,
                "status" => 1,
                "name"   => "消息列表",
            ],
            [
                "route"  => "message/message/group",
                "type"   => 1,
                "status" => 1,
                "name"   => "群发消息列表",
            ],
            [
                "route"  => "message/message/message_send",
                "type"   => 1,
                "status" => 1,
                "name"   => "群发消息",
            ],
            [
                "route"  => "message/message/send_one",
                "type"   => 1,
                "status" => 1,
                "name"   => "发消息",
            ],
            [
                "route"  => "message/message/del",
                "type"   => 1,
                "status" => 1,
                "name"   => "删除短消息",
            ],
            [
                "route"  => "message/message/delete_group",
                "type"   => 1,
                "status" => 1,
                "name"   => "删除群发消息",
            ],
        ],
    ],
];
