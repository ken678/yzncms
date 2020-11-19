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
// | 第三方插件
// +----------------------------------------------------------------------
namespace addons\synclogin;

use app\member\service\User;
use sys\Addons;
use think\Db;

class Synclogin extends Addons
{
    //安装
    public function install()
    {
        $prefix = config("database.prefix");
        Db::execute("DROP TABLE IF EXISTS {$prefix}sync_login;");
        Db::execute("
        CREATE TABLE IF NOT EXISTS `{$prefix}sync_login` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uid` int(11) NOT NULL,
        `type_uid` varchar(255) NOT NULL,
        `type` varchar(255) NOT NULL,
        `oauth_token` varchar(255) NOT NULL,
        `oauth_token_secret` varchar(255) NOT NULL,
        `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态描述',
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
        return true;
    }

    //卸载
    public function uninstall()
    {
        $prefix = config("database.prefix");
        Db::execute("DROP TABLE IF EXISTS {$prefix}sync_login;");
        return true;
    }

    public function syncLogin($param)
    {
        $this->assign($param);
        $config = $this->getAddonConfig();
        $this->assign('config', $config);
        return $this->fetch('login');
    }

    public function userConfig($param)
    {
        $this->assign($param);
        $config = $this->getAddonConfig();
        $this->assign('config', $config);
        $arr  = array();
        $type = explode(',', $config['type']);
        if (is_array($type)) {
            foreach ($type as &$v) {
                $arr[$v]['name']    = strtolower($v);
                $arr[$v]['is_bind'] = $this->check_is_bind_account(User::instance()->isLogin(), strtolower($v));
                if ($arr[$v]['is_bind']) {
                    $token = Db::name('sync_login')->where(array('type' => strtolower($v), 'uid' => User::instance()->isLogin()))->find();
                    //$user_info = \addons\synclogin\ThinkSDK\GetInfo::getInstance($arr[$v]['name'], array('access_token' => $token['oauth_token'], 'openid' => $token['oauth_token_secret']));
                    //$arr[$v]['info'] = $user_info;
                }
            }
            unset($v);
            $this->assign('list', $arr);
            $this->assign('addon_config', $config);
        }
        return $this->fetch('syncbind');
    }

    protected function check_is_bind_account($uid = 0, $type = '')
    {
        $check = Db::name('sync_login')->where(array('uid' => $uid, 'type' => $type))->count();
        if ($check > 0) {
            return true;
        }
        return false;
    }

}
