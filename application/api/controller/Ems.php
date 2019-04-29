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
// | 邮箱验证码接口
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Base;
use app\common\model\Ems as ems_model;
use app\member\model\Member;
use think\facade\Validate;

class Ems extends Base
{
    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->ems_model = new ems_model();
        parent::initialize();
    }

    /**
     * 发送验证码
     */
    public function send()
    {
        $email = $this->request->request("email");
        if (!Validate::isEmail($email)) {
            $this->error('邮箱格式不正确！');
        }
        $event = $this->request->request("event");
        $event = $event ? $event : 'register';
        $last = $this->ems_model->where(['email' => $email, 'event' => $event])->order('id', 'DESC')->find();
        if ($last && time() - $last['create_time'] < 60) {
            $this->error('发送频繁');
        }
        if ($event) {
            $userinfo = Member::getByEmail($email);
            if ($event == 'register' && $userinfo) {
                $this->error('已被注册');
            } elseif (in_array($event, ['changeemail']) && $userinfo) {
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                $this->error('未注册');
            }
        }
        $code = is_null($code) ? mt_rand(1000, 9999) : $code;
        $time = time();
        $ip = request()->ip();
        $ems = $this->ems_model->save(['event' => $event, 'email' => $email, 'code' => $code, 'ip' => $ip, 'create_time' => $time]);
        if ($ems) {
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

    }

}
