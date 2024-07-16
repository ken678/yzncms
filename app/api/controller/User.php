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
// | 会员接口
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems;
use app\common\library\Sms;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Validate;

class User extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = '*';

    public function initialize()
    {
        parent::initialize();

        if (!Config::get('yzn.user_center')) {
            $this->error('会员中心已经关闭');
        }

    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->success('', ['welcome' => $this->auth->nickname]);
    }

    /**
     * 会员登录
     */
    public function login()
    {
        $account  = $this->request->param('account');
        $password = $this->request->param('password');
        $rule     = [
            'account|账户'  => 'require|length:3,30',
            'password|密码' => 'require|length:3,30',

        ];
        $data = [
            'account'  => $account,
            'password' => $password,

        ];
        try {
            $this->validate($data, $rule);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $this->error($e->getError());
        }
        $ret = $this->auth->login($account, $password);
        if ($ret) {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success('登录成功！', $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 注册会员
     */
    public function register()
    {
        $extend = [];
        $data   = $this->request->post();
        $rule   = [
            'username|用户名' => 'unique:user|require|alphaDash|length:3,20',
            'nickname|昵称'  => 'unique:user|length:3,20',
            'mobile|手机'    => 'require|unique:user|mobile',
            'password|密码'  => 'require|length:3,20',
            'email|邮箱'     => 'unique:user|require|email',
        ];
        if (config::get('yzn.user_mobile_verify')) {
            $rule['captcha_mobile|手机验证码'] = "require";
        }
        if (config::get('yzn.user_email_verify')) {
            $rule['captcha_email|邮箱验证码'] = "require";
        }
        try {
            $this->validate($data, $rule);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $this->error($e->getError());
        }
        if (config::get('yzn.user_mobile_verify')) {
            $result = Sms::check($data['mobile'], $data['captcha_mobile'], 'register');
            if (!$result) {
                $this->error('手机验证码错误！');
            }
            $extend['ischeck_mobile'] = 1;
        }
        if (config::get('yzn.user_email_verify')) {
            $result = Ems::check($data['email'], $data['captcha_email'], 'register');
            if (!$result) {
                $this->error('邮箱验证码错误！');
            }
            $extend['ischeck_email'] = 1;
        }
        $ret = $this->auth->register($data['username'], $data['password'], $data['email'], $data['mobile'], $extend);
        if ($ret) {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success('会员注册成功！', $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    //手动退出登录
    public function logout()
    {
        if (!$this->request->isPost()) {
            $this->error('参数不正确');
        }
        $this->auth->logout();
        $this->success('注销成功！', url("index/login"));
    }

    /**
     * 修改会员个人信息
     */
    public function profile()
    {

        $data = $this->request->only(['id', 'avatar', 'username', 'nickname', 'gender', 'birthday', 'motto']);
        //验证数据合法性
        $rule = [
            'nickname|昵称' => 'length:3,20',
            'birthday'    => 'date',
        ];

        try {
            $this->validate($data, $rule);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $this->error($e->getError());
        }

        $user = $this->auth->getUser();
        if (isset($data['nickname'])) {
            $exists = \app\common\model\User::where('nickname', $data['nickname'])->where('id', '<>', $this->auth->id)->find();
            if ($exists) {
                $this->error('昵称已存在');
            }
        }
        $user->save($data);
        $this->success("基本信息修改成功！");
    }

    /**
     * 修改邮箱
     */
    public function changeemail()
    {
        $user    = $this->auth->getUser();
        $email   = $this->request->post('email');
        $captcha = $this->request->param('captcha');
        if (!$email || !$captcha) {
            $this->error('参数不得为空！');
        }
        if (!Validate::is($email, "email")) {
            $this->error('邮箱格式不正确！');
        }
        if (\app\common\model\User::where('email', $email)->where('id', '<>', $user->id)->find()) {
            $this->error('邮箱已占用');
        }
        $result = Ems::check($email, $captcha, 'changeemail');
        if (!$result) {
            $this->error('验证码错误！');
        }
        //只修改邮箱
        $user->ischeck_email = 1;
        $user->email         = $email;
        $user->save();
        Ems::flush($email, 'changeemail');
        $this->success();
    }

    /**
     * 修改手机号
     */
    public function changemobile()
    {

        $user    = $this->auth->getUser();
        $mobile  = $this->request->param('mobile');
        $captcha = $this->request->param('captcha');
        if (!$mobile || !$captcha) {
            $this->error('参数不得为空！');
        }
        if (!Validate::isMobile($mobile)) {
            $this->error('手机号格式不正确！');
        }
        if (\app\common\model\User::where('mobile', $mobile)->where('id', '<>', $user->id)->find()) {
            $this->error('手机号已占用');
        }
        $result = Sms::check($mobile, $captcha, 'changemobile');
        if (!$result) {
            $this->error('验证码错误！');
        }
        //只修改手机号
        $user->mobile         = $mobile;
        $user->ischeck_mobile = 1;
        $user->save();
        Sms::flush($mobile, 'changemobile');
        $this->success();

    }

    /**
     * 激活邮箱
     */
    public function actemail()
    {
        $user    = $this->auth->getUser();
        $captcha = $this->request->param('captcha');
        if (!$captcha) {
            $this->error('参数不得为空！');
        }
        $result = Ems::check($user->email, $captcha, 'actemail');
        if (!$result) {
            $this->error('验证码错误！');
        }
        $user->ischeck_email = 1;
        $user->save();
        Ems::flush($user->email, 'actemail');
        $this->success('激活成功！');
    }

    /**
     * 激活手机号
     */
    public function actmobile()
    {
        $user    = $this->auth->getUser();
        $captcha = $this->request->param('captcha');
        if (!$captcha) {
            $this->error('参数不得为空！');
        }
        $result = Sms::check($user->mobile, $captcha, 'actmobile');
        if (!$result) {
            $this->error('验证码错误！');
        }
        $user->ischeck_mobile = 1;
        $user->save();
        Sms::flush($user->mobile, 'actmobile');
        $this->success('激活成功！');
    }

}
