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
// | 邮箱验证码接口
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems as Emslib;
use app\common\model\User;
use think\facade\Event;
use think\facade\Validate;

/**
 * 邮箱验证码接口
 */
class Ems extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    /**
     * 发送验证码
     */
    public function send()
    {
        $email = $this->request->param("email");
        $event = $this->request->param("event");
        $event = $event ? $event : 'register';

        if (!$email || !Validate::isEmail($email)) {
            $this->error('邮箱格式不正确！');
        }
        $last = Emslib::get($email, $event);
        if ($last && time() - $last['create_time'] < 60) {
            $this->error('发送频繁');
        }
        if ($event) {
            $userinfo = User::getByEmail($email);
            if ($event == 'register' && $userinfo) {
                $this->error('已被注册');
            } elseif (in_array($event, ['changeemail']) && $userinfo) {
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd', 'actemail']) && !$userinfo) {
                $this->error('未注册');
            }
        }
        if (!Event::hasListener('ems_send')) {
            $this->error('请在后台插件管理安装邮箱验证插件');
        }
        $ret = Emslib::send($email, null, $event);
        if ($ret) {
            $this->success('发送成功');
        } else {
            $this->error('发送失败');
        }
    }

    /**
     * 检测验证码
     */
    public function check()
    {
        $email   = $this->request->param("email");
        $event   = $this->request->param("event");
        $event   = $event ? $event : 'register';
        $captcha = $this->request->param("captcha");

        if ($event) {
            $userinfo = User::getByEmail($email);
            if ($event == 'register' && $userinfo) {
                //已被注册
                $this->error('已被注册');
            } elseif (in_array($event, ['changeemail']) && $userinfo) {
                //被占用
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                //未注册
                $this->error('未注册');
            }
        }
        $ret = Emslib::check($email, $captcha, $event);
        if ($ret) {
            $this->success('成功');
        } else {
            $this->error('验证码不正确');
        }
    }

}
