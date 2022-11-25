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
// | 友情链接插件
// +----------------------------------------------------------------------
namespace addons\links;

use think\Addons;
use think\facade\Config;

class Links extends Addons
{
    //后台菜单
    public $admin_list = array(
        array(
            "name"      => "admin/links.admin/index",
            "status"    => 1,
            "title"     => "友情链接",
            "listorder" => 0,
            "child"     => [
                [
                    "name"   => "admin/links.admin/add",
                    "status" => 0,
                    "title"  => "添加友情链接",
                ],
                [
                    "name"   => "admin/links.admin/edit",
                    "status" => 0,
                    "title"  => "链接编辑",
                ],
                [
                    "name"   => "admin/links.admin/del",
                    "status" => 0,
                    "title"  => "链接删除",
                ],
                [
                    "name"   => "admin/links.admin/multi",
                    "status" => 0,
                    "title"  => "批量操作",
                ],
                [
                    "name"   => "admin/links.terms/index",
                    "status" => 0,
                    "title"  => "分类管理",
                ],
                [
                    "name"   => "admin/links.terms/add",
                    "status" => 0,
                    "title"  => "新增",
                ],
                [
                    "name"   => "admin/links.terms/edit",
                    "status" => 0,
                    "title"  => "修改",
                ],
                [
                    "name"   => "admin/links.terms/del",
                    "status" => 0,
                    "title"  => "删除",
                ],
            ],
        ),

    );

    /**
     * 应用初始化
     */
    public function appInit()
    {
        $taglib = Config::get('template.taglib_pre_load');
        Config::set('template.taglib_pre_load', ($taglib ? $taglib . ',' : '') . 'addons\\links\\taglib\\Links');
    }

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
