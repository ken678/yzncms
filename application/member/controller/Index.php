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
// | 会员首页管理
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\member\model\Member as Member_Model;
use app\member\service\User;
use think\facade\Cookie;

class Index extends MemberBase
{

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Member_Model = new Member_Model;
    }

    //会员中心首页
    public function index()
    {
        return $this->fetch('/index');

    }

    //登录页面
    public function login()
    {
        $cookie_url = $_REQUEST['forward'] ? $_REQUEST['forward'] : Cookie::get('__forward__');
        if (!empty($this->userid)) {
            $this->success("您已经是登陆状态！", $forward ? $forward : url("Index/index"));
        }
        if ($this->request->isPost()) {
            //登录验证
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            $captcha = $this->request->param('captcha');
            $cookieTime = $this->request->param('cookieTime', 0);
            $userid = $this->Member_Model->loginLocal($username, $password, $cookieTime ? 86400 * 180 : 86400);
            if ($userid > 0) {
                if (!$cookie_url) {
                    $cookie_url = url('index');
                }
                $this->success('登录成功！', $cookie_url);
            } else {
                //登陆失败
                $this->error('账号或者密码错误！');
            }

        } else {
            $this->assign('forward', $forward);
            return $this->fetch('/login');
        }
    }

    //注册页面
    public function register()
    {
        $forward = $_REQUEST['forward'] ?: cookie("forward");
        cookie("forward", null);
        if ($this->userid) {
            $this->success("您已经是登陆状态，无需注册！", $forward ? $forward : url("index"));
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'member.register');
            if (true !== $result) {
                return $this->error($result);
            }
            $userid = $this->Member_Model->userRegister($data['username'], $data['password'], $data['email']);
            if ($userid > 0) {
                //注册登陆状态
                $this->Member_Model->loginLocal($data['username'], $data['password']);
                $this->success('会员注册成功！');
            } else {
                $this->error($this->Member_Model->getError() ?: '帐号注册失败！');
            }
        } else {
            return $this->fetch('/register');
        }
    }

    //手动退出登录
    public function logout()
    {
        if (User::instance()->logout()) {
            //手动登出时，清空forward
            cookie("forward", null);
            $this->success('注销成功！', url("index/login"));
        }
    }

}
