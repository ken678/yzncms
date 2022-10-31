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
// | 邮件插件
// +----------------------------------------------------------------------
namespace addons\phpmailer;

use addons\phpmailer\library\Mailer;
use think\Addons;
use think\Loader;

class Phpmailer extends Addons
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
     * 邮箱发送行为
     * @param   Sms     $params
     * @return  boolean
     */
    public function emsSend($params)
    {
        $obj    = new Mailer($this->getAddonConfig());
        $result = $obj->to($params['email'])->subject('邮件验证')->message("你的邮件验证码是：{$params['code']}")->send();
        return $result;
    }

    /**
     * 邮箱发送通知
     * @param   array   $params
     * @return  boolean
     */
    public function emsNotice($params)
    {
        $obj    = new Mailer($this->getAddonConfig());
        $result = $obj->to($params['email'])->subject($params['title'])->message($params['msg'])->send();
        return $result;
    }

    /**
     * 检测验证是否正确
     * @param   Sms     $params
     * @return  boolean
     */
    public function emsCheck($params)
    {
        return true;
    }

    /**
     * 添加命名空间
     */
    public function appInit()
    {
        Loader::addNamespace('PHPMailer\\PHPMailer', ADDON_PATH . 'phpmailer' . DS . 'SDK' . DS . 'src' . DS);
    }

}
