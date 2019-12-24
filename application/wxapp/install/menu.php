<?php
return [
    [
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => 0,
        //地址，[模块/]控制器/方法
        "route" => "wxapp/index/init",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "小程序",
        //图标
        "icon" => "icon-shiyongwendang",
        //备注
        "remark" => "",
        //排序
        "listorder" => 4,
        //子菜单列表
        "child" => [
            [
                "route" => "wxapp/wxapp/index",
                "type" => 1,
                "status" => 1,
                "name" => "模板管理",
                "icon" => "icon-neirongguanli",
                "child" => [
                    [
                        "route" => "wxapp/wxapp/tabbar",
                        "type" => 1,
                        "status" => 1,
                        "name" => "底部导航",
                        "icon" => "icon-trash",
                    ],
                ],
            ],

        ],
    ],
];
