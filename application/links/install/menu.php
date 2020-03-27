<?php
return [
    [
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => 45,
        //地址，[模块/]控制器/方法
        "route" => "links/links/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "友情链接",
        //图标
        "icon" => "icon-lianjie",
        //备注
        "remark" => "友情链接！",
        //子菜单列表
        "child" => [
            [
                "route" => "links/links/add",
                "type" => 1,
                "status" => 0,
                "name" => "添加友情链接",
            ],
            [
                "route" => "links/links/edit",
                "type" => 1,
                "status" => 0,
                "name" => "链接编辑",
            ],
            [
                "route" => "links/links/delete",
                "type" => 1,
                "status" => 0,
                "name" => "链接删除",
            ],
            [
                "route" => "links/links/setstate",
                "type" => 1,
                "status" => 0,
                "name" => "链接状态",
            ],
            [
                "route" => "links/links/listorder",
                "type" => 1,
                "status" => 0,
                "name" => "链接排序",
            ],
            [
                "route" => "links/links/terms",
                "type" => 1,
                "status" => 0,
                "name" => "分类管理",
            ],
            [
                "route" => "links/links/addTerms",
                "type" => 1,
                "status" => 0,
                "name" => "分类新增",
            ],
            [
                "route" => "links/links/termsedit",
                "type" => 1,
                "status" => 0,
                "name" => "分类修改",
            ],
            [
                "route" => "links/links/termsdelete",
                "type" => 1,
                "status" => 0,
                "name" => "分类删除",
            ],
        ],
    ],
];
