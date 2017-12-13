<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\user\api\UserApi;
use think\Cookie;
use think\Db;
use think\Loader;

/**
 * 会员中心首页
 */
class Index extends Memberbase
{
    protected $UserApi = null;

    protected function _initialize()
    {
        parent::_initialize();
        $this->UserApi = new UserApi;
    }

    //会员中心首页
    public function index()
    {
        $memberinfo = $this->userinfo;
        $this->assign("memberinfo", $memberinfo);
        return $this->fetch();
    }

    //会员设置界面
    public function profile()
    {
        //====基本资料表单======
        $modelid = $this->userinfo['modelid'];
        //会员模型数据表名
        $tablename = $this->memberModel[$modelid]['tablename'];
        //相应会员模型数据
        $modeldata = Db::name(ucwords($tablename))->where(array("userid" => $this->userid))->find();
        if (!is_array($modeldata)) {
            $modeldata = array();
        }
        $data = array_merge($this->userinfo, $modeldata);
        $this->assign("userinfo", $data);
        return $this->fetch();
    }

    //保存基本信息
    public function account_manage_info()
    {
        if ($this->request->isPost()) {
            //获取用户信息
            $userinfo = $this->UserApi->info($this->userid);
            if ($userinfo && !isset($userinfo[1])) {
                $this->error('该会员不存在！');
            }
            $post = $this->request->param();
            //验证信息
            $validate = Loader::validate('member/Member');
            if (!$validate->scene('edit')->check($post)) {
                $this->error($validate->getError());
                return false;
            }
            $res = model('Member')->isUpdate(true)->allowField(['nickname'])->save($post);
            //昵称更新需要同步更新COOKIES
            model('Member')->login($this->userid);
            $this->success("修改成功");
        }
    }

    //密码修改
    public function account_manage_password()
    {
        if ($this->request->isPost()) {
            $post = $this->request->param();
            //验证密码
            $validate = Loader::validate('member/UcenterMember');
            if (!$validate->scene('password')->check($post)) {
                $this->error($validate->getError());
                return false;
            }
            //旧密码
            $oldpassword = $post['oldpassword'];
            $password = $post['password'];
            //根据当前密码取得用户资料
            $userinfo = $this->UserApi->info($this->userid, false, $oldpassword);
            if ($userinfo && !isset($userinfo[1])) {
                $this->showMessage(20022);
            }
            $res = $this->UserApi->updateInfo($this->userid, $oldpassword, $password);
            if ($res['status']) {
                //注销
                model('Member')->logout();
                $this->success('重置密码成功！', url('member/Index/login'));
            } else {
                $this->error($res['info']);
            }

        }

    }

    //登录页面
    public function login()
    {
        if (!empty($this->userid)) {
            $this->success("您已经是登陆状态！", url("Index/index"));
        }
        if ($this->request->isPost()) {
            //登录验证
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            $captcha = $this->request->param('captcha');
            if (empty($captcha) && $this->memberConfig['openverification']) {
                $this->showMessage(20031, array(), 'error');
            }
            if ($this->memberConfig['openverification'] && !captcha_check($captcha)) {
                $this->showMessage(20031, array(), 'error');
            }
            if (empty($username)) {
                $this->showMessage(10005, array(), 'error');
            }
            if (empty($password)) {
                $this->showMessage(10006, array(), 'error');
            }
            /* 调用接口登录用户 */
            $uid = $this->UserApi->login($username, $password, 1);
            if (0 < $uid) {
                //UC登录成功
                $Member = model('Member');
                if ($Member->login($uid)) {
                    //登录用户
                    //TODO:跳转到登录前页面
                    //if (!$cookie_url = Cookie::get('__forward__')) {
                    $cookie_url = url('member/Index/index');
                    //}
                    $this->success('登录成功！', $cookie_url);
                } else {
                    $this->error($Member->getError());
                }
            } else {
                //登录失败
                switch ($uid) {
                    case -1:$error = '用户不存在或被禁用！';
                        break; //系统级别禁用
                    case -2:$error = '密码错误！';
                        break;
                    default:$error = '未知错误！';
                        break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);

            }

        } else {
            return $this->fetch();
        }
    }

    public function register($username = '', $password = '', $repassword = '', $email = '', $captcha = '')
    {
        if (empty($this->memberConfig['allowregister'])) {
            $this->error("系统不允许新会员注册！");
        }
        if (!empty($this->userid)) {
            $this->success("您已经是登陆状态！", url("Index/index"));
        }
        if ($this->request->isPost()) {
            if (!captcha_check($captcha)) {
                $this->error('验证码错误，请重新输入！');
            }
            /* 调用注册接口注册用户 */
            $uid = $this->UserApi->register($username, $password, $email);
            if (0 < $uid) {
                //注册成功
                //TODO: 发送验证邮件
                $this->success('注册成功！', url('member/index/login'));
            } else {
                //注册失败，显示错误信息
                $this->error($uid);
            }
        } else {
            return $this->fetch();
        }

    }

    //忘记密码界面
    public function lostpassword()
    {
        return $this->fetch();
    }

    //发送重置密码
    public function public_forget_password()
    {
        if ($this->request->isPost()) {
            $email = $this->request->param('email');
            $captcha = $this->request->param('captcha');
            if (!captcha_check($captcha)) {
                $this->error('验证码错误，请重新输入！');
            }
            //取得用户资料
            $userInfo = Db::name('UcenterMember')->where(['email' => $email])->find();
            if (!empty($userInfo['email'])) {
                $email = $userInfo['email'];
            } else {
                $this->error('用户邮箱不存在');
            }
            //邮箱找回信息发送
            $forgetpassword = $this->memberConfig['forgetpassword'];
            if (empty($forgetpassword)) {
                $forgetpassword = 'Hi，{$username}:

你申请了重设密码，请在24小时内点击下面的链接，然后根据页面提示完成密码重设：

<a href="{$url}" target="_blank">{$url}</a>

如果链接无法点击，请完整拷贝到浏览器地址栏里直接访问。

邮件服务器自动发送邮件请勿回信 {$date}';
            };

            $LostPassUrl = url('member/index/public_reset_password', ['key' => think_encrypt(implode('|', $userInfo), '', 86400)], true, true);
            $forgetpassword = str_replace(array(
                '{$username}',
                '{$userid}',
                '{$email}',
                '{$url}',
                '{$date}',
            ), array(
                $userInfo['username'],
                $userInfo['id'],
                $userInfo['email'],
                $LostPassUrl,
                date('Y-m-d H:i:s'),
            ), \util\Input::nl2Br($forgetpassword));

            $send = send_email($email, '密码找回', $forgetpassword);
            if ($send['status'] == 1) {
                $this->success('发送成功，注意查收!', url('member/index/login'));
            } else {
                $this->error($send['msg']);
            }

        } else {
            return $this->fetch();
        }

    }

    //重置密码
    public function public_reset_password()
    {
        //提交新密码
        if ($this->request->isPost()) {
            //密码重置
            $postKey = $this->request->param('key');
            $key = think_decrypt($postKey);
            if (empty($key)) {
                $this->error('本次请求已经失效，请从新提交密码找回申请');
            }
            $userinfo = explode('|', $key);
            //密码
            $password = $this->request->param('password');
            $repassword = $this->request->param('repassword');
            $captcha = $this->request->param('captcha');
            if (!captcha_check($captcha)) {
                $this->error('验证码错误，请重新输入！');
            }
            if (empty($password)) {
                $this->error('请输入新密码！');
            }
            //密码确认
            if ($password != $repassword) {
                $this->error('两次输入密码不相同，请从新输入！');
            }
            $res = $this->UserApi->updateInfo($userinfo[0], $userinfo[2], $password);
            if ($res['status']) {
                $this->success('重置密码成功！', url('member/Index/login'));
            } else {
                $this->error($res['info']);
            }

        } else {
            //设置新密码界面
            $getKey = $this->request->param('key');
            if ($getKey) {
                $getKey = str_replace(array('+', '%23', '%2F', '%3F', '%26', '%3D', '%2B'), array(' ', '#', '/', '?', '&', '=', '+'), $getKey);
            }
            $key = think_decrypt($getKey);
            if (empty($key)) {
                $this->error('验证失败，请重新提交密码找回申请！', url('index/lostpassword'));
            }
            $userinfo = explode('|', $key);
            $this->assign('userinfo', array(
                'id' => $userinfo[0],
                'username' => $userinfo[1],
                'email' => $userinfo[3],
            ));
            $this->assign('key', $getKey);
            return $this->fetch('resetpassword');
        }

    }

    //退出
    public function logout()
    {
        if (is_login()) {
            model('Member')->logout();
            $this->success('退出成功！', url('member/Index/login'));
        } else {
            $this->redirect('member/Index/login');
        }
    }

}
