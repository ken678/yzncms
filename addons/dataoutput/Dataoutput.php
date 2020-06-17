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
// | 数据导出插件
// +----------------------------------------------------------------------
namespace addons\dataoutput;

use app\addons\util\Addon;
use think\Db;

class Dataoutput extends Addon
{
    //插件信息
    public $info = [
        'name' => 'dataoutput',
        'title' => '通用数据导出',
        'description' => '数据导出excel必备插件',
        'status' => 1,
        'author' => '御宅男',
        'version' => '1.0.0',
        'has_adminlist' => 1,
    ];

    //安装
    public function install()
    {
        $prefix = config("database.prefix");
        Db::execute("DROP TABLE IF EXISTS {$prefix}dataoutput;");
        Db::execute("
        CREATE TABLE IF NOT EXISTS `{$prefix}dataoutput` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(20) NOT NULL COMMENT '任务名',
        `table` varchar(20) NOT NULL COMMENT '表',
        `data` mediumtext NOT NULL,
        `create_time` int(10) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
        return true;
    }

    //卸载
    public function uninstall()
    {
        $prefix = config("database.prefix");
        Db::execute("DROP TABLE IF EXISTS {$prefix}dataoutput;");
        return true;
    }

}
