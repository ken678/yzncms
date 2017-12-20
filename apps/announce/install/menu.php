<?php
return array(
    array(
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => null,
        //地址，[模块/]控制器/方法
        "route" => "announce/announce/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "系统公告",
        //备注
        "remark" => "系统公告！",
        //子菜单列表
        "child" => array(
            array(
                "route" => "announce/announce/add",
                "type" => 1,
                "status" => 0,
                "name" => "添加公告",
            ),
            array(
                "route" => "announce/announce/edit",
                "type" => 1,
                "status" => 0,
                "name" => "编辑公告",
            ),
            array(
                "route" => "announce/announce/delete",
                "type" => 1,
                "status" => 0,
                "name" => "公告删除",
            ),
            array(
                "route" => "announce/announce/listorder",
                "type" => 1,
                "status" => 0,
                "name" => "链接排序",
            ),
        ),
    ),
);
