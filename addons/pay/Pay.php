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
                    "name"  => "admin/pay.payment/add",
                    "title" => "在线充值",
                ],
                [
                    "name"  => "admin/pay.payment/del",
                    "title" => "删除",
                ],
                [
                    "name"  => "admin/pay.payment/recyclebin",
                    "title" => "回收站",
                ],
                [
                    "name"  => "admin/pay.payment/restore",
                    "title" => "还原",
                ],
                [
                    "name"  => "admin/pay.payment/destroy",
                    "title" => "销毁",
                ],
                [
                    "name"  => "admin/pay.spend/index",
                    "title" => "消费记录",
                ],
                [
                    "name"  => "admin/pay.spend/del",
                    "title" => "删除",
                ],
            ],
        ],
    );

    //安装
    public function install()
    {
        $info = get_addon_info('member');
        if (!$info || $info['status'] != 1) {
            throw new \think\Exception("请在后台插件管理中安装《会员插件》并启用后再尝试");
        }
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
