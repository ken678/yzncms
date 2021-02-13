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
// | 手机短信接口
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Sms as Sms_Model;
use app\member\model\Member;
use think\facade\Validate;

/**
 * @title 手机短信接口
 * @controller api\controller\Sms
 * @group base
 */
class Sms extends Api
{
    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->Sms_Model = new Sms_Model();
        parent::initialize();
    }

    /**
     * @title 发送验证码
     * @desc 最基础的接口注释写法
     * @author 御宅男
     * @url /api/Sms/send
     * @method GET
     * @tag 手机 验证码
     * @param name:mobile type:string require:1 desc:手机号
     * @param name:event type:string require:1 desc:事件名称
     * @return name:data type:array ref:definitions\dictionary
     */
    public function send()
    {
        $mobile = $this->request->request("mobile");
        $event  = $this->request->request("event");
        $event  = $event ? $event : 'register';

        if (!$mobile || !Validate::isMobile($mobile)) {
            $this->error('手机号不正确');
        }
        $last = $this->Sms_Model->get($mobile, $event);
        if ($last && time() - $last['create_time'] < 60) {
            $this->error('发送频繁');
        }
        $ipSendTotal = $this->Sms_Model->where(['ip' => $this->request->ip()])->whereTime('create_time', '-1 hours')->count();
        if ($ipSendTotal >= 5) {
            $this->error('发送频繁');
        }
        if ($event) {
            $userinfo = Member::getByMobile($mobile);
            if ($event == 'register' && $userinfo) {
                $this->error('已被注册');
            } elseif (in_array($event, ['changemobile']) && $userinfo) {
                $this->error('已被占用');
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                $this->error('未注册');
            }
        }
        if (!\think\facade\Hook::get('sms_send')) {
            $this->error('请在后台插件管理安装短信验证插件');
        }
        $ret = $this->Sms_Model->send($mobile, null, $event);
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
     * @url /api/Sms/check
     * @method GET
     * @tag 手机 验证码
     * @param name:mobile type:string require:1 desc:手机号
     * @param name:event type:string require:1 desc:事件名称
     * @param name:captcha type:string require:1 desc:验证码
     * @return name:data type:array ref:definitions\dictionary
     */
    public function check()
    {

    }

}
