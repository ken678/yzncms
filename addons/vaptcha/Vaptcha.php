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
// | 智能人机验证插件
// +----------------------------------------------------------------------
namespace addons\vaptcha;

use addons\vaptcha\library\Vaptcha as VaptchaLib;
use sys\Addons;
use think\Validate;

class Vaptcha extends Addons
{
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

    /**
     * 自定义captcha验证事件
     */
    public function actionBegin()
    {
        Validate::extend('captcha', function ($value, $id = "") {
            //return true;
            $request = request();
            $token   = $request->post('vaptcha_token/s');
            $scene   = $request->post('scene/d', 1);
            if (!$token) {
                Validate::setTypeMsg('captcha', '请先完成验证！');
                return false;
            }
            $config = get_addon_config('vaptcha');
            if (!$config['appvid'] || !$config['appkey']) {
                Validate::setTypeMsg('captcha', '请先在后台中配置vaptcha验证的参数信息');
                return false;
            }
            $VaptchaLib = new VaptchaLib($config['appvid'], $config['appkey']);
            $result     = $VaptchaLib->validate($token, $scene);
            $result     = json_decode($result, true);
            if ($result['success'] == 0) {
                Validate::setTypeMsg('captcha', '请先完成验证！');
                return false;
            }
            return true;
        });
    }

    public function adminLoginForm()
    {
        $config = get_addon_config('vaptcha');
        $this->assign('config', $config);
        return $this->fetch('vaptcha');
    }
}
