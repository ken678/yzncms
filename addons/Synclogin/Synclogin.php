<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace addons\Synclogin;

use app\common\controller\Addon;
use think\Db;

/**
 * 同步登陆插件
 */
class Synclogin extends Addon
{
    public $info = array(
        'name' => 'Synclogin',
        'title' => '同步登陆',
        'description' => '同步登陆',
        'status' => 1,
        'author' => '御宅男',
        'version' => '1.0.0',
    );

    public function install()
    {
        Db::execute("DROP TABLE IF EXISTS yzn_sync_login;");
        Db::execute("
        CREATE TABLE IF NOT EXISTS `yzn_sync_login` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uid` int(11) NOT NULL,
        `type_uid` varchar(255) NOT NULL,
        `type` varchar(255) NOT NULL,
        `oauth_token` varchar(255) NOT NULL,
        `oauth_token_secret` varchar(255) NOT NULL,
        `is_sync` tinyint(4) NOT NULL,
        `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态描述',
        `open_id` varchar(255) NOT NULL COMMENT '微信公众号open_id',
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
        return true;
    }

    public function uninstall()
    {
        Db::execute("DROP TABLE IF EXISTS yzn_sync_login;");
        return true;
    }

    //登录按钮钩子
    public function syncLogin($param)
    {
        $this->assign($param);
        $config = $this->getAddonConfig();
        $this->assign('config', $config);
        return $this->fetch('login');
    }

}
