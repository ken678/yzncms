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
// |  前台用户服务
// +----------------------------------------------------------------------
namespace app\member\service;

use app\member\model\Member as Member_Model;
use think\facade\Session;
use think\facade\Validate;

class User
{
    protected static $instance = null;
    //当前登录会员详细信息
    protected static $userInfo = array();
    //错误信息
    protected $error = null;

    public function __construct()
    {
        $this->memberConfig = cache("Member_Config");
    }

    /**
     * 获取示例
     * @param array $options 实例配置
     * @return static
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($options);
        }

        return self::$instance;
    }

    /**
     * 魔术方法
     * @param type $name
     * @return null
     */
    public function __get($name)
    {
        //从缓存中获取
        if (isset(self::$userInfo[$name])) {
            return self::$userInfo[$name];
        } else {
            $userInfo = $this->getInfo();
            if (!empty($userInfo)) {
                return $userInfo[$name];
            }
            return null;
        }
    }

    /**
     * 注册一个新用户
     * @param $username 用户名
     * @param $password 密码
     * @param string $email 邮箱
     * @param string $mobile 手机号
     * @param array $extend 扩展参数
     * @return bool|mixed
     */
    public function userRegister($username, $password, $email = '', $mobile = '', $extend = [])
    {
        $passwordinfo = encrypt_password($password); //对密码进行处理
        $data         = array(
            "mobile"   => $mobile,
            "username" => $username,
            "password" => $passwordinfo['password'],
            "email"    => $email,
            "encrypt"  => $passwordinfo['encrypt'],
            "amount"   => 0,
        );
        $user = new Member_Model();
        $res  = $user->save($data);
        if ($res) {
            $data   = array_merge($data, $extend);
            $userid = $user->id;

            unset($data['username'], $data['password'], $data['email'], $data['mobile']);
            //新注册用户积分
            $data['point'] = $this->memberConfig['defualtpoint'] ? $this->memberConfig['defualtpoint'] : 0;
            //新会员注册默认赠送资金
            $data['amount'] = $this->memberConfig['defualtamount'] ? $this->memberConfig['defualtamount'] : 0;
            //新会员注册需要邮件验证
            if ($this->memberConfig['enablemailcheck']) {
                $data['groupid'] = 7;
                $data['status']  = 0;
            } else {
                //新会员注册需要管理员审核
                if ($this->memberConfig['registerverify']) {
                    $data['status'] = 0;
                } else {
                    $data['status'] = 1;
                }
                //计算用户组
                $data['groupid'] = $this->get_usergroup_bypoint($data['point']);
            }
            if (false !== $user->save($data, ['id' => $userid])) {
                $_user = $user::get($userid);
                //注册成功的事件
                hook("user_register_successed", $_user);
                //注册登陆状态
                $this->loginLocal($username, $password);
                return $userid;
            } else {
                //删除
                $this->Member_Model->userDelete($userid);
            }
        }
        $this->error = '注册失败！';
        return false;
    }

    /**
     * 会员登录
     * @param $account 账户
     * @param $password 明文密码，填写表示验证密码
     * @param $is_remember_me cookie有效期
     * @return boolean
     */
    public function loginLocal($account, $password = null, $is_remember_me = 604800)
    {
        $userinfo = $this->getLocalUser($account);
        if (empty($userinfo)) {
            return false;
        }
        //是否需要进行密码验证
        if (!empty($password)) {
            $password = encrypt_password($password, $userinfo["encrypt"]);
            if ($password != $userinfo['password']) {
                $this->error = '密码错误！';
                return false;
            }
        }
        $this->autoLogin($userinfo);
        return $userinfo;
    }

    /**
     * 直接登录账号
     * @param int $uid
     * @return boolean
     */
    public function direct($uid)
    {
        $userinfo = Member_Model::get($uid);
        if ($userinfo) {
            $this->autoLogin($userinfo);
            return $userinfo;
        } else {
            $this->error = '登录失败！';
            return false;
        }
    }

    /**
     * 获取用户信息
     * @param $account 账户
     * @param $password 明文密码，填写表示验证密码
     */
    public function getLocalUser($account, $password = null)
    {
        if (empty($account)) {
            $this->error = '参数为空！';
            return false;
        }
        if (Validate::isEmail($account)) {
            $field = 'email';
        } elseif (Validate::isMobile($account)) {
            $field = 'mobile';
        } else {
            $field = 'username';
        }
        $userinfo = Member_Model::where([$field => $account])->find();
        if (empty($userinfo)) {
            $this->error = '该用户不存在！';
            return false;
        }
        //是否需要进行密码验证
        if (!empty($password)) {
            //对明文密码进行加密
            $password = encrypt_password($password, $userinfo["encrypt"]);
            if ($password != $userinfo['password']) {
                $this->error = '用户密码错误！';
                //密码错误
                return false;
            }
        }
        return $userinfo;
    }

    /**
     * 自动登录用户
     * @param $userInfo
     */
    public function autoLogin($userInfo)
    {
        $data = [
            'last_login_time' => time(),
            'last_login_ip'   => request()->ip(),
            'login'           => $userInfo['login'] + 1,
        ];
        //vip过期，更新vip和会员组
        if ($userInfo['overduedate'] < time() && intval($userInfo['vip'])) {
            $data['vip'] = 0;
        }
        //检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作
        if ($userInfo['point'] >= 0 && !in_array($userInfo['groupid'], array('1', '7', '8')) && empty($userInfo['vip'])) {
            $grouplist = cache("Member_Group");
            if (!empty($grouplist[$userInfo['groupid']]['allowupgrade'])) {
                $check_groupid = $this->get_usergroup_bypoint($userInfo['point']);
                if ($check_groupid != $userInfo['groupid']) {
                    $data['groupid'] = $check_groupid;
                }
            }
        }
        $user = new Member_Model();
        $user->save($data, ['id' => $userInfo['id']]);
        /* 记录登录SESSION和COOKIES */
        $auth = [
            'uid'             => $userInfo['id'],
            'username'        => $userInfo['username'],
            'last_login_time' => $userInfo['last_login_time'],
        ];
        Session('user_auth', $auth);
        Session('user_auth_sign', data_auth_sign($auth));
    }

    /**
     * 根据积分算出用户组
     * @param int $point 积分数
     * @return int|string|null
     */
    public function get_usergroup_bypoint($point = 0)
    {
        $groupid = 2;
        if (empty($point)) {
            //新会员默认点数
            $point = $this->memberConfig['defualtpoint'] ? $this->memberConfig['defualtpoint'] : 0;
        }
        //获取会有组缓存
        $grouplist = cache("Member_Group");
        foreach ($grouplist as $k => $v) {
            $grouppointlist[$k] = $v['point'];
        }
        //对数组进行逆向排序
        arsort($grouppointlist);
        //如果超出用户组积分设置则为积分最高的用户组
        if ($point > max($grouppointlist)) {
            $groupid = key($grouppointlist);
        } else {
            $tmp_k = key($grouppointlist);
            foreach ($grouppointlist as $k => $v) {
                if ($point >= $v) {
                    $groupid = $tmp_k;
                    break;
                }
                $tmp_k = $k;
            }
        }
        return $groupid;
    }

    /**
     * 获取当前登录用户资料
     * @return array
     */
    public function getInfo()
    {
        if (empty(self::$userInfo)) {
            self::$userInfo = Member_Model::where('id', $this->isLogin())->find();
        }
        return !empty(self::$userInfo) ? self::$userInfo : false;
    }

    /**
     * 检验用户是否已经登陆
     * @return boolean 失败返回false，成功返回当前登陆用户基本信息
     */
    public function isLogin()
    {
        $user = Session::get('user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return Session::get('user_auth_sign') == data_auth_sign($user) ? (int) $user['uid'] : 0;
        }
    }

    /**
     * 注销登录状态
     * @return boolean
     */
    public function logout()
    {
        Session::set('user_auth', null);
        Session::set('user_auth_sign', null);
        return true;
    }

    /**
     * 获取错误信息
     * @return type
     */
    public function getError()
    {
        return $this->error;
    }

}
