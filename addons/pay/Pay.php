<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 支付插件
// +----------------------------------------------------------------------
namespace addons\pay;

use think\Addons;

class Pay extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/pay.payment/index",
            "status"    => 1,
            "title"     => "支付管理",
            "icon"      => "icon-money",
            "listorder" => 3,
            "child"     => [
                [
                    "name"  => "admin/pay.payment/pay_list",
                    "title" => "支付模块",
                    "child" => [
                        [
                            "name"  => "admin/pay.payment/edit",
                            "title" => "支付配置",
                        ],
                    ],
                ],
            ],
        ],
    );

    //安装
    public function install()
    {
        return true;
    }

    //卸载
    public function uninstall()
    {
        return true;
    }

    //或者run方法
    public function userSidenavAfter($content)
    {
        return $this->fetch('userSidenavAfter');

    }

}
