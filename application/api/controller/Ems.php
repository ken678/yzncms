<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 邮箱验证码接口
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems as Emslib;
use app\member\model\Member;
use think\facade\Hook;
use think\facade\Validate;

/**
 * @title 邮箱验证码接口
 * @controller api\controller\Ems
 * @group base
 */
class Ems extends Api
{
    /**
     * @title 发送验证码
     * @desc 最基础的接口注释写法
     * @author 御宅男
     * @url /api/Ems/send
     * @method GET
     * @tag 邮箱 验证码
     * @param name:email type:string require:1 desc:邮箱
     * @param name:event type:string require:1 desc:事件名称
     * @return name:data type:array ref:definitions\dictionary
     */
    public function send()
    {
        $email = $this->request->request("email");
        $event = $this->request->request("event");
        $event = $event ? $event : 'register';

        if (!$email || !Validate::isEmail($email)) {
            $this->error('邮箱格式不正确！');
        }
        $last = Emslib::get($email, $event);
        if ($last && time() - $last['create_time'] < 60) {
            $this->error('发送频繁');
        }
        if ($event) {
            $userinfo = Member::getByEmail($email);
            if ($event == 'register' && $userinfo) {
                $this->error('已被注册');
            } elseif (in_array($event, ['changeemail']) && $userinfo) {
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd', 'actemail']) && !$userinfo) {
                $this->error('未注册');
            }
        }
        if (!Hook::get('ems_send')) {
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
     * @title 检测验证码
     * @desc 最基础的接口注释写法
     * @author 御宅男
     * @url /api/Ems/check
     * @method GET
     * @tag 邮箱 验证码
     * @param name:email type:string require:1 desc:邮箱
     * @param name:event type:string require:1 desc:事件名称
     * @param name:captcha type:string require:1 desc:验证码
     * @return name:data type:array ref:definitions\dictionary
     */
    public function check()
    {

    }

}
