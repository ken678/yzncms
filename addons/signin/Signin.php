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
// | 短信插件
// +----------------------------------------------------------------------
namespace addons\signin;

use app\addons\util\Addon;
use think\Db;
use util\File;

class Signin extends Addon
{
    //插件信息
    public $info = [
        'name' => 'signin',
        'title' => '会员签到插件',
        'description' => '会员签到插件',
        'status' => 1,
        'author' => '御宅男',
        'version' => '1.0.0',
    ];

    //安装
    public function install()
    {
        $prefix = config("database.prefix");
        Db::execute("DROP TABLE IF EXISTS {$prefix}signin;");
        Db::execute("
			CREATE TABLE IF NOT EXISTS `{$prefix}signin` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
			  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
			  `successions` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '连续签到次数',
			  `type` enum('normal','fillup') DEFAULT 'normal' COMMENT '签到类型',
			  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
			  PRIMARY KEY (`id`),
			  KEY `user_id` (`uid`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='签到表';
        ");
        //前台模板
        $installdir = ADDON_PATH . "signin" . DIRECTORY_SEPARATOR . "install" . DIRECTORY_SEPARATOR;
        if (is_dir($installdir . "template" . DIRECTORY_SEPARATOR)) {
            //拷贝模板到前台模板目录中去
            File::copy_dir($installdir . "template" . DIRECTORY_SEPARATOR, TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR);
        }
        return true;
    }

    //卸载
    public function uninstall()
    {
        $prefix = config("database.prefix");
        Db::execute("DROP TABLE IF EXISTS {$prefix}signin;");
        if (is_dir(TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'signin' . DIRECTORY_SEPARATOR)) {
            File::del_dir(TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'signin' . DIRECTORY_SEPARATOR);
        }
        return true;
    }

    /**
     * 会员中心边栏后
     * @return mixed
     * @throws \Exception
     */
    public function userSidenavAfter()
    {
        return $this->fetch('user_sidenav_after');
    }

}
