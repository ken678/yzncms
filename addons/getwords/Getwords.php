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
// | 百度中文分词插件
// +----------------------------------------------------------------------
namespace addons\getwords;

use think\Addons;

class Getwords extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/getwords.admin/getkeywords",
            "status"    => 0,
            "title"     => "中文分词",
            "tip"       => "",
            "listorder" => 0,
            "child"     => [],
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
}
