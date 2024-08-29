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
// | 会员中心
// +----------------------------------------------------------------------
namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Ems;
use app\common\library\Sms;
use app\common\model\Attachment;
use app\common\model\User as UserModel;
use app\common\model\UserVip;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Db;
use think\facade\Event;

class User extends Frontend
{
    protected $noNeedLogin = ['login', 'register', 'forget', 'captcha'];
    protected $noNeedRight = ['*'];

    public function initialize()
    {
        parent::initialize();
        $auth = $this->auth;

        if (!Config::get('yzn.user_center')) {
            $this->error('会员中心已经关闭', '/');
        }

        //监听注册登录退出的事件
        Event::listen('user_login_successed', function ($user) use ($auth) {
            $expire = $this->request->post('keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Event::listen('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Event::listen('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Event::listen('user_logout_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->assign('title', '会员中心');
        return $this->fetch();
    }

    //登录页面
    public function login()
    {
        $forward = $this->request->request('forward', '', 'url_clean');
        if (!empty($this->auth->id)) {
            $this->success("您已经是登陆状态！", $forward ? $forward : url("user/index"));
        }
        if ($this->request->isPost()) {
            //登录验证
            $account  = $this->request->param('account');
            $password = $this->request->param('password');
            $verify   = $this->request->param('verify');
            $token    = $this->request->param('__token__');

            $rule = [
                'account|账户'  => 'require|length:3,30',
                'password|密码' => 'require|length:3,30',
                '__token__'   => 'require|token',
            ];
            $data = [
                'account'   => $account,
                'password'  => $password,
                '__token__' => $token,
            ];
            //验证码
            if (config::get("yzn.user_login_captcha") && !captcha_check($verify)) {
                $this->error('验证码错误！');
            }
            try {
                $this->validate($data, $rule);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError(), null, ['token' => $this->request->buildToken()]);
            }
            $userInfo = $this->auth->login($account, $password);
            if ($userInfo) {
                $this->success('登录成功！', $forward ?: url('user/index'));
            } else {
                //登陆失败
                $this->error($this->auth->getError() ?: '账号或者密码错误！', null, ['token' => $this->request->buildToken()]);
            }
        } else {
            //判断来源
            $referer = url_clean($this->request->server('HTTP_REFERER'));
            if (!$forward && $referer && !preg_match("/(user\/login|user\/register|user\/forget|user\/logout)/i", $referer)) {
                $forward = $referer;
            }
            $this->assign('title', '登录');
            $this->assign('forward', $forward);
            return $this->fetch();
        }
    }

    //注册页面
    public function register()
    {
        $forward = $this->request->request('forward', '', 'url_clean');
        if ($this->auth->id) {
            $this->success("您已经是登陆状态，无需注册！", $forward ?: url("user/index"));
        }
        if ($this->request->isPost()) {
            $extend = [];
            $data   = $this->request->post();
            //验证码
            if (!captcha_check($data['verify'])) {
                $this->error('验证码输入错误！');
            }
            $rule = [
                'username|用户名' => 'unique:user|require|alphaDash|length:3,20',
                'nickname|昵称'  => 'unique:user|length:3,20',
                'mobile|手机'    => 'require|unique:user|mobile',
                'password|密码'  => 'require|length:3,20',
                'email|邮箱'     => 'unique:user|require|email',
                '__token__'    => 'require|token',
            ];
            if (config::get('yzn.user_password_confirm')) {
                $rule['password|密码'] = "require|length:3,20|confirm";
            }
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
                $this->error($e->getError(), null, ['token' => $this->request->buildToken()]);
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
            if ($this->auth->register($data['username'], $data['password'], $data['email'], $data['mobile'], $extend)) {
                $this->success('会员注册成功！', $forward ? $forward : url('user/index'));
            } else {
                $this->error($this->auth->getError() ?: '帐号注册失败！', null, ['token' => $this->request->buildToken()]);
            }
        } else {
            //判断来源
            $referer = url_clean($this->request->server('HTTP_REFERER'));
            if (!$forward && $referer && !preg_match("/(user\/login|user\/register|user\/forget|user\/logout)/i", $referer)) {
                $forward = $referer;
            }
            $this->view->assign('title', '注册');
            $this->assign('forward', $forward);
            return $this->fetch();
        }
    }

    /**
     *忘记密码
     */
    public function forget()
    {
        if ($this->request->isPost()) {
            $type        = $this->request->param("type");
            $mobile      = $this->request->param("mobile");
            $email       = $this->request->param("email");
            $newpassword = $this->request->param("newpassword");
            $captcha     = $this->request->param("captcha");
            $token       = $this->request->param('__token__');

            // 验证数据
            $data = [
                'mobile'      => $mobile,
                'email'       => $email,
                'captcha'     => $captcha,
                'newpassword' => $newpassword,
                '__token__'   => $token,
            ];
            $rule = [
                'mobile|手机号'      => 'require|mobile',
                'email|邮箱'        => 'require|email',
                'captcha|验证码'     => 'require|number',
                'newpassword|新密码' => 'require|length:6,30',
                '__token__'       => 'require|token',
            ];
            if ($type == "mobile") {
                unset($rule['email|邮箱']);
            } else {
                unset($rule['mobile|手机号']);
            }
            try {
                $this->validate($data, $rule);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError(), null, ['token' => $this->request->buildToken()]);
            }
            if ($type == 'mobile') {
                $user = UserModel::where('mobile', $mobile)->find();
                if (!$user) {
                    $this->error('用户不存在！', null, ['token' => $this->request->buildToken()]);
                }
                $result = Sms::check($mobile, $captcha, 'resetpwd');
                if (!$result) {
                    $this->error('验证码错误！', null, ['token' => $this->request->buildToken()]);
                }
            } elseif ($type == 'email') {
                $user = UserModel::where('email', $email)->find();
                if (!$user) {
                    $this->error('用户不存在！', null, ['token' => $this->request->buildToken()]);
                }
                $result = Ems::check($email, $captcha, 'resetpwd');
                if (!$result) {
                    $this->error('验证码错误！', null, ['token' => $this->request->buildToken()]);
                }
            } else {
                $this->error('类型错误！', null, ['token' => $this->request->buildToken()]);
            }
            $this->auth->direct($user->id);
            $res = $this->auth->changepwd($newpassword, '', true);
            if (!$res) {
                $this->error($this->auth->getError());
            }
            $this->success('重置成功！');
        } else {
            $this->assign('title', '密码忘记');
            return $this->fetch();
        }
    }

    /**
     * 个人信息
     */
    public function profile()
    {
        $this->assign('title', '账户设置');
        return $this->fetch();
    }

    /**
     * 修改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost()) {
            $oldpassword   = $this->request->post("oldpassword", '', null);
            $newpassword   = $this->request->post("newpassword", '', null);
            $renewpassword = $this->request->post("renewpassword", '', null);
            $token         = $this->request->post('__token__');
            $rule          = [
                'oldpassword|旧密码'    => 'require|regex:\S{6,30}',
                'newpassword|新密码'    => 'require|regex:\S{6,30}',
                'renewpassword|确认密码' => 'require|regex:\S{6,30}|confirm:newpassword',
                '__token__'          => 'token',
            ];
            $msg = [
                'renewpassword.confirm' => '两次输入的密码不一致',
            ];
            $data = [
                'oldpassword'   => $oldpassword,
                'newpassword'   => $newpassword,
                'renewpassword' => $renewpassword,
                '__token__'     => $token,
            ];

            try {
                $this->validate($data, $rule, $msg);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                $this->error($e->getError(), null, ['token' => $this->request->buildToken()]);
            }

            $ret = $this->auth->changepwd($newpassword, $oldpassword);
            if ($ret) {
                $this->success('修改成功！', url('user/login'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->buildToken()]);
            }
        }
        $this->assign('title', '修改密码');
        return $this->fetch();
    }

    //会员组升级
    public function upgrade()
    {
        if (!Config::get('yzn.user_allow_vip')) {
            $this->error("系统不允许会员开通！");
        }
        if ($this->request->isPost()) {
            $id      = $this->request->param('id/d');
            $vipInfo = UserVip::find($id);
            if (!$vipInfo) {
                $this->error('VIP会员不存在');
            }
            //购买时间
            $buydate     = $vipInfo['days'] * 86400;
            $overduedate = $this->auth->overduedate > time() ? ($this->auth->overduedate + $buydate) : (time() + $buydate);

            if ($this->auth->amount < $vipInfo['amount']) {
                $this->error('余额不足，请先充值！');
            }
            try {
                Db::startTrans();
                UserModel::where('id', $this->auth->id)->update(['overduedate' => $overduedate, 'vip' => $vipInfo['level']]);
                //消费记录
                UserModel::amount(-$vipInfo['amount'], $this->auth->id, '购买VIP《' . $vipInfo['title'] . '》');
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error('购买失败！');
            }
            $this->success('购买成功！');

        }
        $vipList = UserVip::where('status', '1')->order('listorder', 'desc')->select();
        $this->assign('vipList', $vipList);
        $this->assign('title', '开通会员');
        return $this->fetch();

    }

    //手动退出登录
    public function logout()
    {
        $this->auth->logout();
        $this->success('注销成功！', url("user/login"));
    }

    //验证码
    public function captcha()
    {
        return \think\captcha\facade\Captcha::create();
    }

    //附件列表页
    public function attachment()
    {
        $this->request->filter('trim,strip_tags');
        if ($this->request->isAjax()) {
            $page    = $this->request->param('page', 1);
            $limit   = $this->request->param('limit', 15);
            $filters = $this->request->param('filter', '{}');
            $ops     = $this->request->param('op', '{}');
            $filters = (array) json_decode($filters, true);
            $ops     = (array) json_decode($ops, true);
            $where   = [];
            foreach ($filters as $key => $val) {
                $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
                switch (strtolower($op)) {
                    case '=':
                        $where[] = [$key, '=', $val];
                        break;
                    case '%*%':
                        $where[] = [$key, 'LIKE', "%{$val}%"];
                        break;
                    case '*%':
                        $where[] = [$key, 'LIKE', "{$val}%"];
                        break;
                    case '%*':
                        $where[] = [$key, 'LIKE', "%{$val}"];
                        break;
                    case 'range':
                        list($beginTime, $endTime) = explode(' - ', $val);
                        $where[]                   = [$key, '>=', strtotime($beginTime)];
                        $where[]                   = [$key, '<=', strtotime($endTime)];
                        break;
                    default:
                        $where[] = [$key, $op, "%{$val}"];
                };
            };

            $total = Attachment::where($where)
                ->where($where)
                ->where('user_id', $this->auth->id)
                ->order('id', 'desc')
                ->count();
            $list = Attachment::where($where)
                ->where($where)
                ->where('user_id', $this->auth->id)
                ->page($page, $limit)
                ->order('id', 'desc')
                ->select();
            $result = ["code" => 0, "count" => $total, "data" => $list];
            return json($result);
        }
        $mimetype = $this->request->param('mimetype', '');
        $mimetype = substr($mimetype, -1) === '/' ? $mimetype . '*' : $mimetype;
        $this->assignconfig('mimetype', $mimetype);
        return $this->fetch();
    }

}
