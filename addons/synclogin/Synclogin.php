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
// | 第三方登录插件
// +----------------------------------------------------------------------
namespace addons\synclogin;

use app\member\service\User;
use think\Addons;
use think\Db;

class Synclogin extends Addons
{
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
                $arr[$v]['is_bind'] = $this->check_is_bind_account(User::instance()->id, strtolower($v));
                if ($arr[$v]['is_bind']) {
                    $token = Db::name('sync_login')->where(array('platform' => strtolower($v), 'uid' => User::instance()->id))->find();
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
        $check = Db::name('sync_login')->where(array('uid' => $uid, 'platform' => $type))->count();
        if ($check > 0) {
            return true;
        }
        return false;
    }

}
