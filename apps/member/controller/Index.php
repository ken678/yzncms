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

/**
 * 会员中心首页
 */
class Index extends Memberbase
{
    //会员中心首页
    public function index()
    {
        return $this->fetch();

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
            if (empty($username)) {
                $this->showMessage(10005, array(), 'error');
            }
            if (empty($password)) {
                $this->showMessage(10006, array(), 'error');
            }
            /* 调用接口登录用户 */
            $User = new UserApi;
            $uid = $User->login($username, $password, 1);
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

    public function register($username = '', $password = '', $repassword = '', $email = '', $verify = '')
    {
        if (!empty($this->userid)) {
            $this->success("您已经是登陆状态！", url("Index/index"));
        }
        if ($this->request->isPost()) {
            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $password, $email);
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

    public function profile()
    {
        if ($this->request->isPost()) {
            $this->error('登录成功！', url('/'));

        } else {
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
