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
// | 数据库插件
// +----------------------------------------------------------------------
namespace addons\database;

use sys\Addons;

class Database extends Addons
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
            "title"     => "数据库备份",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "restore",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "备份还原",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "del",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "删除备份",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "repair",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "修复表",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "optimize",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "优化表",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "import",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "还原表",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "export",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "备份数据库",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
        ),
        array(
            //方法名称
            "action"    => "download",
            //附加参数 例如：a=12&id=777
            "data"      => "",
            //状态，1是显示，0是不显示
            "status"    => 0,
            //名称
            "title"     => "备份数据库下载",
            //备注
            "tip"       => "",
            //排序
            "listorder" => 0,
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
