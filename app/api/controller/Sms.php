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
// | Original reference: https://gitee.com/karson/fastadmin
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 手机短信接口
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Sms as Smslib;
use app\common\model\User;
use think\facade\Event;
use think\facade\Validate;

/**
 * 手机短信接口
 */
class Sms extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    /**
     * 发送验证码
     */
    public function send()
    {
        $mobile = $this->request->param("mobile");
        $event  = $this->request->param("event");
        $event  = $event ? $event : 'register';

        if (!$mobile || !Validate::isMobile($mobile)) {
            $this->error('手机号不正确');
        }
        $last = Smslib::get($mobile, $event);
        if ($last && time() - $last['create_time'] < 60) {
            $this->error('发送频繁');
        }
        $ipSendTotal = \app\common\model\Sms::where(['ip' => $this->request->ip()])->whereTime('create_time', '-1 hours')->count();
        if ($ipSendTotal >= 5) {
            $this->error('发送频繁');
        }
        if ($event) {
            $userinfo = User::getByMobile($mobile);
            if ($event == 'register' && $userinfo) {
                $this->error('已被注册');
            } elseif (in_array($event, ['changemobile']) && $userinfo) {
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                $this->error('未注册');
            }
        }
        if (!Event::hasListener('sms_send')) {
            $this->error('请在后台插件管理安装短信验证插件');
        }
        $ret = Smslib::send($mobile, null, $event);
        if ($ret) {
            $this->success('发送成功');
        } else {
            $this->error('发送失败，请检查短信配置是否正确');
        }

    }

    /**
     * 检测验证码
     */
    public function check()
    {
        $mobile  = $this->request->param("mobile");
        $event   = $this->request->param("event");
        $event   = $event ? $event : 'register';
        $captcha = $this->request->param("captcha");

        if (!$mobile || !Validate::regex($mobile, "^1\d{10}$")) {
            $this->error('手机号不正确');
        }
        if ($event) {
            $userinfo = User::getByMobile($mobile);
            if ($event == 'register' && $userinfo) {
                //已被注册
                $this->error('已被注册');
            } elseif (in_array($event, ['changemobile']) && $userinfo) {
                //被占用
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                //未注册
                $this->error('未注册');
            }
        }
        $ret = Smslib::check($mobile, $captcha, $event);
        if ($ret) {
            $this->success('成功');
        } else {
            $this->error('验证码不正确');
        }
    }

}
