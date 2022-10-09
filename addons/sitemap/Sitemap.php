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
// | 网站地图插件
// +----------------------------------------------------------------------
namespace addons\sitemap;

use think\Addons;

class Sitemap extends Addons
{
    //后台菜单
    public $admin_list = array(
        array(
            //方法名称
            "name"      => "admin/sitemap.admin/index",
            //状态，1是显示，0是不显示
            "status"    => 1,
            //名称
            "title"     => "网站地图",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
            "child"     => [],
        ),

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
