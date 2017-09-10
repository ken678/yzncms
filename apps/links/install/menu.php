<?php
return array(
    array(
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => NULL,
        //地址，[模块/]控制器/方法
        "route" => "Links/Links/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "友情链接",
        //备注
        "remark" => "友情链接！",
        //子菜单列表
        "child" => array(
            array(
                "route" => "Links/Links/add",
                "type" => 1,
                "status" => 0,
                "name" => "添加友情链接",
            ),
            array(
                "route" => "Links/Links/edit",
                "type" => 1,
                "status" => 0,
                "name" => "链接编辑",
            ),
            array(
                "route" => "Links/Links/delete",
                "type" => 1,
                "status" => 0,
                "name" => "链接删除",
            ),
            array(
                "route" => "Links/Links/terms",
                "type" => 1,
                "status" => 0,
                "name" => "分类管理",
            ),
            array(
                "route" => "Links/Links/addTerms",
                "type" => 1,
                "status" => 0,
                "name" => "分类新增",
            ),
            array(
                "route" => "Links/Links/termsedit",
                "type" => 1,
                "status" => 0,
                "name" => "分类修改",
            ),
            array(
                "route" => "Links/Links/termsdelete",
                "type" => 1,
                "status" => 0,
                "name" => "分类删除",
            ),
        ),
    ),
);
