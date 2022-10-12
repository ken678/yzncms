<?php
return [
    [
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid"  => 0,
        //地址，[模块/]控制器/方法
        "route"     => "member/member/index2",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type"      => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status"    => 1,
        //名称
        "name"      => "会员",
        //图标
        "icon"      => "icon-user-line",
        //备注
        "remark"    => "",
        //排序
        "listorder" => 4,
        //子菜单列表
        "child"     => [
            [
                "route"  => "member/member/index1",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员管理",
                "icon"   => "icon-user-line",
                "child"  => [
                    [
                        "route"  => "member/setting/setting",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "会员设置",
                        "icon"   => "icon-user-settings-line",
                    ],
                    [
                        "route"  => "member/member/index",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "会员管理",
                        "icon"   => "icon-user-shared-2-line",
                    ],
                    [
                        "route"  => "member/member/userverify",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "审核会员",
                        "icon"   => "icon-user-star-line",
                    ],
                    [
                        "route"  => "member/member/add",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员添加",
                    ],
                    [
                        "route"  => "member/member/edit",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员编辑",
                    ],
                    [
                        "route"  => "member/member/del",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员删除",
                    ],
                    [
                        "route"  => "member/member/pass",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员审核",
                    ],
                ],
            ],
            [
                "route"  => "member/group/index1",
                "type"   => 1,
                "status" => 1,
                "name"   => "会员组",
                "icon"   => "icon-group-line",
                "child"  => [
                    [
                        "route"  => "member/group/index",
                        "type"   => 1,
                        "status" => 1,
                        "name"   => "会员组管理",
                        "icon"   => "icon-user-settings-line",
                    ],
                    [
                        "route"  => "member/group/add",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员组添加",
                    ],
                    [
                        "route"  => "member/group/edit",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员组编辑",
                    ],
                    [
                        "route"  => "member/group/del",
                        "type"   => 1,
                        "status" => 0,
                        "name"   => "会员组删除",
                    ],
                ],
            ],

        ],
    ],
];
