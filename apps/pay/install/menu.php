<?php
return array(
    array(
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => null,
        //地址，[模块/]控制器/方法
        "route" => "pay/index/lists",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "在线充值",
        //备注
        "remark" => "在线充值！",
        //子菜单列表
        "child" => array(
            array(
                "route" => "pay/index/add",
                "type" => 1,
                "status" => 1,
                "name" => "充值入帐",
            ),
            array(
                "route" => "pay/pay_config/index",
                "type" => 1,
                "status" => 1,
                "name" => "支付配置",
            ),
        ),
    ),
);
