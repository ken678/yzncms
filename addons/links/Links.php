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

use sys\Addons;
use think\facade\Config;

class Links extends Addons
{
    //后台菜单
    public $admin_list = array(
        array(
            //方法名称
            "action"    => "index",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 1,
            //名称
            "title"     => "友情链接",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
            "child"     => [
                [
                    "action"    => "add",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "添加友情链接",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "edit",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "链接编辑",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "del",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "链接删除",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "multi",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "批量操作",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "terms",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "分类管理",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "addTerms",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "分类新增",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "termsedit",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "分类修改",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "action"    => "termsdelete",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "分类删除",
                    "tip"       => "",
                    "listorder" => 0,
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
