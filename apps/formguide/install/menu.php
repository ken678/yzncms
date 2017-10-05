<?php
return array(
    array(
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => null,
        //地址，[模块/]控制器/方法
        "route" => "Formguide/Formguide/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "表单管理",
        //备注
        "remark" => "自定义表单管理！",
        //子菜单列表
        "child" => array(
            array(
                "route" => "Formguide/Formguide/add",
                "type" => 1,
                "status" => 1,
                "name" => "添加表单",
            ),
            array(
                "route" => "Formguide/Formguide/edit",
                "type" => 1,
                "status" => 0,
                "name" => "编辑",
            ),
            array(
                "route" => "Formguide/Formguide/delete",
                "type" => 1,
                "status" => 0,
                "name" => "删除",
            ),
            array(
                "route" => "Formguide/Formguide/status",
                "type" => 1,
                "status" => 0,
                "name" => "禁用",
            ),
            array(
                "route" => "Formguide/Info/index",
                "type" => 1,
                "status" => 0,
                "name" => "信息列表",
                "child" => array(
                    array(
                        "route" => "Formguide/Info/delete",
                        "type" => 1,
                        "status" => 0,
                        "name" => "信息删除",
                    ),
                ),
            ),
            array(
                "route" => "Formguide/Field/index",
                "type" => 1,
                "status" => 0,
                "name" => "管理字段",
                "child" => array(
                    array(
                        "route" => "Formguide/Field/add",
                        "type" => 1,
                        "status" => 0,
                        "name" => "添加字段",
                    ),
                    array(
                        "route" => "Formguide/Field/edit",
                        "type" => 1,
                        "status" => 0,
                        "name" => "编辑字段",
                    ),
                    array(
                        "route" => "Formguide/Field/delete",
                        "type" => 1,
                        "status" => 0,
                        "name" => "删除字段",
                    ),
                ),
            ),
        ),
    ),
);
