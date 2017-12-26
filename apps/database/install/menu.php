<?php
return array(
    array(
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => null,
        //地址，[模块/]控制器/方法
        "route" => "database/index/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "数据库",
        //备注
        "remark" => "数据库备份还原",
        //子菜单列表
        "child" => array(
            array(
                "route" => "database/index/repair_list",
                "type" => 1,
                "status" => 1,
                "name" => "数据库恢复",
            ),
            array(
                "route" => "database/index/optimize",
                "type" => 1,
                "status" => 0,
                "name" => "优化表",
            ),
            array(
                "route" => "database/index/repair",
                "type" => 1,
                "status" => 0,
                "name" => "修复表",
            ),
            array(
                "route" => "database/index/downfile",
                "type" => 1,
                "status" => 0,
                "name" => "下载表",
            ),
            array(
                "route" => "database/index/del",
                "type" => 1,
                "status" => 0,
                "name" => "删除表",
            ),
            array(
                "route" => "database/index/import",
                "type" => 1,
                "status" => 0,
                "name" => "还原表",
            ),
        ),
    ),
);
