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
namespace addons\saiyousms;

use addons\saiyousms\lib\Sms;
use app\addons\util\Addon;

class Saiyousms extends Addon
{
    //插件信息
    public $info = [
        'name' => 'saiyousms',
        'title' => '短信插件',
        'description' => '短信插件-by赛邮',
        'status' => 1,
        'author' => '御宅男',
        'version' => '1.0.0',
    ];

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
     * 短信发送行为
     * @param   Sms     $params
     * @return  boolean
     */
    public function smsSend($params)
    {
        $sms = new Sms();
        $result = $sms->mobile($params['mobile'])
            ->msg("你的短信验证码是：{$params['code']}")
            ->send();
        return $result;
    }

    /**
     * 短信发送通知
     * @param   array   $params
     * @return  boolean
     */
    public function smsNotice($params)
    {
        $sms = new Sms();
        $result = $sms->mobile($params['mobile'])
            ->msg($params['msg'])
            ->send();
        return $result;
    }

    /**
     * 检测验证是否正确
     * @param   Sms     $params
     * @return  boolean
     */
    public function smsCheck($params)
    {
        return true;
    }

}
