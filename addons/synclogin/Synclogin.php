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
use util\File;

class Synclogin extends Addons
{
    //安装
    public function install()
    {
        //前台模板
        $installdir = ADDON_PATH . "synclogin" . DIRECTORY_SEPARATOR . "install" . DIRECTORY_SEPARATOR;
        if (is_dir($installdir . "template" . DIRECTORY_SEPARATOR)) {
            //拷贝模板到前台模板目录中去
            File::copy_dir($installdir . "template" . DIRECTORY_SEPARATOR, TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR);
        }
        return true;
    }

    //卸载
    public function uninstall()
    {
        if (is_dir(TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'synclogin' . DIRECTORY_SEPARATOR)) {
            File::del_dir(TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'synclogin' . DIRECTORY_SEPARATOR);
        }
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
                    $token = Db::name('sync_login')->where(array('type' => strtolower($v), 'uid' => User::instance()->id))->find();
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
