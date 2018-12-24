<?php
return [
    [
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => 0,
        //地址，[模块/]控制器/方法
        "route" => "cms/index/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "内容",
        //图标
        "icon" => "icon-article",
        //备注
        "remark" => "",
        //排序
        "listorder" => 3,
        //子菜单列表
        "child" => [
            [
                "route" => "cms/cms/index",
                "type" => 1,
                "status" => 1,
                "name" => "内容管理",
                "icon" => "icon-neirongguanli",
                "child" => [
                    [
                        "route" => "cms/cms/index",
                        "type" => 1,
                        "status" => 1,
                        "name" => "管理内容",
                        "icon" => "icon-neirongguanli",
                    ],
                ],
            ],
            [
                "route" => "cms/category/index",
                "type" => 1,
                "status" => 1,
                "name" => "相关设置",
                "icon" => "icon-zidongxiufu",
                "child" => [
                    [
                        "route" => "cms/category/index",
                        "type" => 1,
                        "status" => 1,
                        "name" => "栏目列表",
                        "icon" => "icon-liebiao",
                    ],
                    [
                        "route" => "cms/models/index",
                        "type" => 1,
                        "status" => 1,
                        "name" => "模型管理",
                        "icon" => "icon-moxing",
                        "child" => [
                            [
                                "route" => "cms/field/index",
                                "type" => 1,
                                "status" => 1,
                                "name" => "字段管理",
                            ],
                            [
                                "route" => "cms/models/add",
                                "type" => 1,
                                "status" => 1,
                                "name" => "添加模型",
                            ],
                            [
                                "route" => "cms/models/edit",
                                "type" => 1,
                                "status" => 1,
                                "name" => "修改模型",
                            ],
                            [
                                "route" => "cms/models/delete",
                                "type" => 1,
                                "status" => 1,
                                "name" => "删除模型",
                            ],
                            [
                                "route" => "cms/models/setstate",
                                "type" => 1,
                                "status" => 1,
                                "name" => "设置模型状态",
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
