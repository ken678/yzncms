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

use app\common\library\Ems;
use app\common\library\Sms;
use app\member\model\Member as Member_Model;
use think\facade\Cookie;
use think\facade\Hook;
use think\facade\Validate;

class Index extends MemberBase
{
    protected $noNeedLogin = ['login', 'register', 'logout', 'forget'];

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Member_Model = new Member_Model;

        $auth = $this->auth;

        //监听注册登录退出的事件
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = $this->request->post('keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
    }

    //会员中心首页
    public function index()
    {
        return $this->fetch('/index');

    }

    //登录页面
    public function login()
    {
        $forward = $this->request->request('forward', '', 'trim');
        if (!empty($this->auth->id)) {
            $this->success("您已经是登陆状态！", $forward ? $forward : url("index"));
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
            if (empty($verify) && $this->memberConfig['openverification']) {
                $this->error('验证码错误！');
            }
            if ($this->memberConfig['openverification'] && !captcha_check($verify)) {
                $this->error('验证码错误！');
            }
            $result = $this->validate($data, $rule);
            if (true !== $result) {
                $this->error($result, null, ['token' => $this->request->token()]);
            }
            $userInfo = $this->auth->loginLocal($account, $password);
            if ($userInfo) {
                $this->success('登录成功！', $forward ? $forward : url('index'));
            } else {
                //登陆失败
                $this->error($this->auth->getError() ?: '账号或者密码错误！', null, ['token' => $this->request->token()]);
            }
        } else {
            //判断来源
            $referer = $this->request->server('HTTP_REFERER');
            if (!$forward && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
                && !preg_match("/(index\/login|index\/register|index\/forget|index\/logout)/i", $referer)) {
                $forward = $referer;
            }
            $this->assign('forward', $forward);
            return $this->fetch('/login');
        }
    }

    //注册页面
    public function register()
    {
        if (empty($this->memberConfig['allowregister'])) {
            $this->error("系统不允许新会员注册！");
        }
        $forward = $this->request->request('url', '', 'trim');
        if ($this->auth->id) {
            $this->success("您已经是登陆状态，无需注册！", $forward ? $forward : url("index"));
        }
        if ($this->request->isPost()) {
            $extend             = [];
            $data               = $this->request->post();
            $extend['nickname'] = isset($data['nickname']) ? $data['nickname'] : "";
            //验证码
            if (!captcha_check($data['verify'])) {
                $this->error('验证码输入错误！');
                return false;
            }
            $rule = [
                'username|用户名' => 'unique:member|require|alphaDash|length:3,20',
                'nickname|昵称'  => 'chsDash|length:3,20',
                'mobile|手机'    => 'unique:member|mobile',
                'password|密码'  => 'require|length:3,20',
                'email|邮箱'     => 'unique:member|require|email',
                '__token__'    => 'require|token',
            ];
            if ($this->memberConfig['password_confirm']) {
                $rule['password|密码'] = "require|length:3,20|confirm";
            }
            if ($this->memberConfig['register_mobile_verify']) {
                $rule['captcha_mobile|手机验证码'] = "require";
            }
            if ($this->memberConfig['register_email_verify']) {
                $rule['captcha_email|邮箱验证码'] = "require";
            }
            $result = $this->validate($data, $rule);
            if (true !== $result) {
                $this->error($result, null, ['token' => $this->request->token()]);
            }
            if ($this->memberConfig['register_mobile_verify']) {
                $result = Sms::check($data['mobile'], $data['captcha_mobile'], 'register');
                if (!$result) {
                    $this->error('手机验证码错误！');
                }
                $extend['ischeck_mobile'] = 1;
            }
            if ($this->memberConfig['register_email_verify']) {
                $result = Ems::check($data['email'], $data['captcha_email'], 'register');
                if (!$result) {
                    $this->error('邮箱验证码错误！');
                }
                $extend['ischeck_email'] = 1;
            }
            if ($this->auth->userRegister($data['username'], $data['password'], $data['email'], $data['mobile'], $extend)) {
                $this->success('会员注册成功！', $forward ? $forward : url('index'));
            } else {
                $this->error($this->auth->getError() ?: '帐号注册失败！', null, ['token' => $this->request->token()]);
            }
        } else {
            //判断来源
            $referer = $this->request->server('HTTP_REFERER');
            if (!$forward && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
                && !preg_match("/(index\/login|index\/register|index\/logout)/i", $referer)) {
                $forward = $referer;
            }
            $this->assign('forward', $forward);
            return $this->fetch('/register');
        }
    }

    /**
     * 个人资料
     */
    public function profile()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //验证数据合法性
            $rule = [
                'nickname|昵称' => 'chsDash|length:3,20',
            ];
            $result = $this->validate($data, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            $userinfo = Member_Model::get($this->auth->id);
            if (empty($userinfo)) {
                $this->error('该会员不存在！');
            }
            if (!empty($data)) {
                //暂时只允许昵称，头像修改
                $this->Member_Model->allowField(['nickname', 'avatar'])->save($data, ["id" => $this->auth->id]);
            }
            $this->success("基本信息修改成功！");
        } else {
            return $this->fetch('/profile');
        }
    }

    /**
     * 更改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost()) {
            $oldPassword   = $this->request->post("oldpassword");
            $newPassword   = $this->request->post("newpassword");
            $renewPassword = $this->request->post("renewpassword");
            // 验证数据
            $data = [
                'oldpassword'   => $oldPassword,
                'newpassword'   => $newPassword,
                'renewpassword' => $renewPassword,
            ];
            $rule = [
                'oldpassword|旧密码'    => 'require|length:6,30',
                'newpassword|新密码'    => 'require|length:6,30',
                'renewpassword|确认密码' => 'require|length:6,30|confirm:newpassword',
            ];
            $result = $this->validate($data, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            $res = $this->auth->changepwd($newPassword, $oldPassword);
            if (!$res) {
                $this->error($this->auth->getError());
            }
            $this->success('修改成功！');
            //注销当前登陆
            $this->logout();
        }
    }

    /**
     * 修改邮箱
     */
    public function changeemail()
    {
        if ($this->request->isPost()) {
            $email   = $this->request->post('email');
            $captcha = $this->request->param('captcha');
            if (!$email || !$captcha) {
                $this->error('参数不得为空！');
            }
            if (!Validate::is($email, "email")) {
                $this->error('邮箱格式不正确！');
            }
            if ($this->Member_Model->where('email', $email)->where('id', '<>', $this->auth->id)->find()) {
                $this->error('邮箱已占用');
            }
            $result = Ems::check($email, $captcha, 'changeemail');
            if (!$result) {
                $this->error('验证码错误！');
            }
            //只修改邮箱
            $this->Member_Model->allowField(['ischeck_email', 'email'])->save(['email' => $email, 'ischeck_email' => 1], ['id' => 1]);
            Ems::flush($email, 'changeemail');
            $this->success();
        } else {
            return $this->fetch('/changeemail');
        }

    }

    /**
     * 修改手机号
     */
    public function changemobile()
    {
        if ($this->request->isPost()) {
            $mobile  = $this->request->param('mobile');
            $captcha = $this->request->param('captcha');
            if (!$mobile || !$captcha) {
                $this->error('参数不得为空！');
            }
            if (!Validate::isMobile($mobile)) {
                $this->error('手机号格式不正确！');
            }
            if ($this->Member_Model->where('mobile', $mobile)->where('id', '<>', $this->auth->id)->find()) {
                $this->error('手机号已占用');
            }
            $result = Sms::check($mobile, $captcha, 'changemobile');
            if (!$result) {
                $this->error('验证码错误！');
            }
            //只修改手机号
            $this->Member_Model->allowField(['ischeck_mobile', 'mobile'])->save(['mobile' => $mobile, 'ischeck_mobile' => 1], ['id' => 1]);
            Sms::flush($mobile, 'changemobile');
            $this->success();
        } else {
            return $this->fetch('/changemobile');
        }
    }

    /**
     * 激活邮箱
     */
    public function actemail()
    {
        if ($this->request->isPost()) {
            $captcha = $this->request->param('captcha');
            if (!$captcha) {
                $this->error('参数不得为空！');
            }
            $result = Ems::check($this->auth->email, $captcha, 'actemail');
            if (!$result) {
                $this->error('验证码错误！');
            }
            //只修改邮箱
            $this->Member_Model->save(['ischeck_email' => 1], ['id' => $this->auth->id]);
            Ems::flush($this->auth->email, 'actemail');
            $this->success('激活成功！');
        } else {
            return $this->fetch('/actemail');
        }
    }

    /**
     * 激活手机号
     */
    public function actmobile()
    {
        if ($this->request->isPost()) {
            $captcha = $this->request->param('captcha');
            if (!$captcha) {
                $this->error('参数不得为空！');
            }
            $result = Sms::check($this->auth->mobile, $captcha, 'actmobile');
            if (!$result) {
                $this->error('验证码错误！');
            }
            //只修改手机号
            $this->Member_Model->save(['ischeck_mobile' => 1], ['id' => $this->auth->id]);
            Sms::flush($this->auth->mobile, 'actmobile');
            $this->success('激活成功！');
        } else {
            return $this->fetch('/actmobile');
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
            $result = $this->validate($data, $rule);
            if (true !== $result) {
                $this->error($result, null, ['token' => $this->request->token()]);
            }
            if ($type == 'mobile') {
                $user = Member_Model::where('mobile', $mobile)->find();
                if (!$user) {
                    $this->error('用户不存在！', null, ['token' => $this->request->token()]);
                }
                $result = Sms::check($mobile, $captcha, 'resetpwd');
                if (!$result) {
                    $this->error('验证码错误！', null, ['token' => $this->request->token()]);
                }
            } elseif ($type == 'email') {
                $user = Member_Model::where('email', $email)->find();
                if (!$user) {
                    $this->error('用户不存在！', null, ['token' => $this->request->token()]);
                }
                $result = Ems::check($email, $captcha, 'resetpwd');
                if (!$result) {
                    $this->error('验证码错误！', null, ['token' => $this->request->token()]);
                }
            } else {
                $this->error('类型错误！', null, ['token' => $this->request->token()]);
            }
            $this->auth->direct($user->id);
            $res = $this->auth->changepwd($newpassword, '', true);
            if (!$res) {
                $this->error($this->auth->getError());
            }
            $this->success('重置成功！');
        } else {
            return $this->fetch('/forget');
        }
    }

    //会员组升级
    public function upgrade()
    {
        if (empty($this->memberGroup[$this->auth->groupid]['allowupgrade'])) {
            $this->error('此会员组不允许升级！');
        }
        if ($this->request->isPost()) {
            $groupid = $this->request->param("groupid/d", 0);
            if (empty($groupid) || in_array($groupid, [8, 1, 7])) {
                $this->error('会员组类型错误！');
            }
            $upgrade_type = $this->request->param("upgrade_type/d", 0);
            $upgrade_date = $this->request->param("upgrade_date/d", 1);
            if (0 >= intval($upgrade_date)) {
                $this->error('购买时限必须大于0！');
            }
            //消费类型，包年、包月、包日，价格
            $typearr = array($this->memberGroup[$groupid]['price_y'], $this->memberGroup[$groupid]['price_m'], $this->memberGroup[$groupid]['price_d']);
            //消费类型，包年、包月、包日，时间
            $typedatearr = array('366', '31', '1');
            //消费的价格
            $cost = $typearr[$upgrade_type] * $upgrade_date;
            //购买时间
            $buydate     = $typedatearr[$upgrade_type] * $upgrade_date * 86400;
            $overduedate = $this->auth->overduedate > time() ? ($this->auth->overduedate + $buydate) : (time() + $buydate);

            if ($this->auth->amount >= $cost) {
                $this->Member_Model->where('id', $this->auth->id)->update(['groupid' => $groupid, 'overduedate' => $overduedate, 'vip' => 1]);
                //消费记录
                $Spend_Model = new \app\pay\model\Spend;
                $Spend_Model->_spend(1, $cost, $this->auth->id, $this->auth->username, '升级用户组');
                $this->success('购买成功！', url('upgrade'));
            } else {
                $this->error('余额不足，请先充值！');
            }
        } else {
            $groupid    = $this->request->param("groupid/d", 0);
            $grouppoint = $this->memberGroup[$this->auth->groupid]['point'];
            unset($this->memberGroup[$this->auth->groupid]);
            $this->assign([
                'memberGroup' => $this->memberGroup,
                'groupid'     => $groupid,
                'grouppoint'  => $grouppoint,
            ]);
            return $this->fetch('/upgrade');
        }
    }

    //手动退出登录
    public function logout()
    {
        $this->auth->logout();
        $this->success('注销成功！', url("index/login"));
    }

}
