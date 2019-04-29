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
use app\common\model\Ems as Ems_Model;
use app\member\model\Member;
use think\facade\Validate;

class Ems extends Base
{
    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->Ems_Model = new Ems_Model();
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
        $last = $this->Ems_Model->where(['email' => $email, 'event' => $event])->order('id', 'DESC')->find();
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
        $ip = $this->request->ip();
        $ret = $this->Ems_Model->save(['event' => $event, 'email' => $email, 'code' => $code, 'ip' => $ip, 'create_time' => $time]);
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

    }

}
