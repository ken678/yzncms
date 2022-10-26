<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 签到插件
// +----------------------------------------------------------------------
namespace addons\signin;

use think\Addons;

class Signin extends Addons
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
