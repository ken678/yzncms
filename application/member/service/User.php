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

use app\member\library\Token;
use app\member\model\Member as Member_Model;
use think\facade\Hook;
use think\facade\Validate;
use util\Random;

class User
{
    protected static $instance = null;
    protected $_error          = null;
    protected $_logined        = false;
    //当前登录会员详细信息
    protected $_user  = null;
    protected $_token = '';
    //Token默认有效时长
    protected $keeptime = 2592000;
    //默认配置
    protected $config      = [];
    protected $options     = [];
    protected $allowFields = ['id', 'username', 'nickname', 'mobile', 'avatar', 'point', 'amount'];

    public function __construct($options = [])
    {
        if ($config = cache("Member_Config")) {
            $this->config = array_merge($this->config, $config);
        }
        $this->options = array_merge($this->config, $options);
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
     * 获取User模型
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * 兼容调用user模型的属性
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_user ? $this->_user->$name : null;
    }

    /**
     * 兼容调用user模型的属性
     */
    public function __isset($name)
    {
        return isset($this->_user) ? isset($this->_user->$name) : false;
    }

    /**
     * 根据Token初始化
     *
     * @param string $token Token
     * @return boolean
     */
    public function init($token)
    {
        if ($this->_logined) {
            return true;
        }
        if ($this->_error) {
            return false;
        }
        $data = Token::get($token);
        if (!$data) {
            return false;
        }
        $user_id = intval($data['user_id']);
        if ($user_id > 0) {
            $user = Member_Model::get($user_id);
            if (!$user) {
                $this->setError('账户不存在');
                return false;
            }
            if ($user['status'] !== 1) {
                $this->setError('账户已经被锁定');
                return false;
            }
            $this->_user    = $user;
            $this->_logined = true;
            $this->_token   = $token;

            //初始化成功的事件
            Hook::listen("user_init_successed", $this->_user);
            return true;
        } else {
            $this->setError('你当前还未登录');
            return false;
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
            "email"    => $email,
            "encrypt"  => $passwordinfo['encrypt'],
            "amount"   => 0,
        );
        //新注册用户积分
        $data['point'] = $this->config['defualtpoint'] ? $this->config['defualtpoint'] : 0;
        //新会员注册默认赠送资金
        $data['amount'] = $this->config['defualtamount'] ? $this->config['defualtamount'] : 0;
        //新会员注册需要管理员审核
        $data['status'] = $this->config['registerverify'] ? 0 : 1;
        //计算用户组
        $data['groupid']    = $this->get_usergroup_bypoint($data['point']);
        $params             = array_merge($data, $extend);
        $params['password'] = $passwordinfo['password'];

        try {
            $model       = new Member_Model();
            $user        = $model->allowField(true)->save($params);
            $this->_user = $model->get($model->id);
            //设置Token
            $this->_token = Random::uuid();
            Token::set($this->_token, $model->id, $this->keeptime);
            //设置登录状态
            $this->_logined = true;
            //注册成功的事件
            Hook::listen("user_register_successed", $this->_user, $data);
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return $model->id;
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
        $field = Validate::is($account, 'email') ? 'email' : (Validate::regex($account, '/^1\d{10}$/') ? 'mobile' : 'username');
        $user  = Member_Model::get([$field => $account]);
        if (!$user) {
            $this->setError('账户不正确');
            return false;
        }

        if ($user->status !== 1) {
            $this->setError('账户已经被锁定');
            return false;
        }
        if ($user->password != encrypt_password($password, $user->encrypt)) {
            $this->setError('密码不正确');
            return false;
        }

        //直接登录会员
        $this->direct($user->id);

        return true;
    }

    /**
     * 删除一个指定会员
     * @param int $user_id 会员ID
     * @return boolean
     */
    public function delete($user_id)
    {
        $user = Member_Model::get($user_id);
        if (!$user) {
            return false;
        }
        try {
            // 删除会员
            Member_Model::destroy($user_id);
            // 删除会员指定的所有Token
            Token::clear($user_id);
            Hook::listen("user_delete_successed", $user);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 直接登录账号
     * @param int $user_id
     * @return boolean
     */
    public function direct($user_id)
    {
        $user = Member_Model::get($user_id);
        if ($user) {
            try {
                $ip   = request()->ip();
                $time = time();
                //vip过期，更新vip和会员组
                if ($user->overduedate < $time && intval($user->vip)) {
                    $user->vip = 0;
                }
                //检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作
                if ($user->point >= 0 && !in_array($user->groupid, array('1', '7', '8')) && empty($user->vip)) {
                    $grouplist = cache("Member_Group");
                    if (!empty($grouplist[$user->groupid]['allowupgrade'])) {
                        $check_groupid = $this->get_usergroup_bypoint($user->point);
                        if ($check_groupid != $user->groupid) {
                            $user->groupid = $check_groupid;
                        }
                    }
                }
                //记录本次登录的IP和时间
                $user->last_login_ip   = $ip;
                $user->last_login_time = $time;
                $user->login           = $user->login + 1;
                $user->save();

                $this->_user  = $user;
                $this->_token = Random::uuid();
                Token::set($this->_token, $user->id, $this->keeptime);
                $this->_logined = true;

                //登录成功的事件
                Hook::listen("user_login_successed", $this->_user);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改密码
     * @param string $newpassword       新密码
     * @param string $oldpassword       旧密码
     * @param bool   $ignoreoldpassword 忽略旧密码
     * @return boolean
     */
    public function changepwd($newpassword, $oldpassword = '', $ignoreoldpassword = false)
    {
        if (!$this->_logined) {
            $this->setError('你当前还未登录');
            return false;
        }
        //判断旧密码是否正确
        if ($this->_user->password == encrypt_password($oldpassword, $this->_user->encrypt) || $ignoreoldpassword) {
            try {
                $encrypt     = Random::alnum();
                $newpassword = encrypt_password($newpassword, $encrypt);
                $this->_user->save(['password' => $newpassword, 'encrypt' => $encrypt]);
                Token::delete($this->_token);
                //修改密码成功的事件
                Hook::listen("user_changepwd_successed", $this->_user);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
            return true;
        } else {
            $this->setError('密码不正确');
            return false;
        }
    }

    /**
     * 获取会员基本信息
     */
    public function getUserinfo()
    {
        $data        = $this->_user->toArray();
        $allowFields = $this->getAllowFields();
        $userinfo    = array_intersect_key($data, array_flip($allowFields));
        $userinfo    = array_merge($userinfo, Token::get($this->_token));
        return $userinfo;
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
            $point = $this->config['defualtpoint'] ? $this->config['defualtpoint'] : 0;
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
     * 检验用户是否已经登陆
     */
    public function isLogin()
    {
        if ($this->_logined) {
            return true;
        }
        return false;
    }

    /**
     * 获取允许输出的字段
     * @return array
     */
    public function getAllowFields()
    {
        return $this->allowFields;
    }

    /**
     * 设置允许输出的字段
     * @param array $fields
     */
    public function setAllowFields($fields)
    {
        $this->allowFields = $fields;
    }

    /**
     * 获取当前Token
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * 设置会话有效时间
     * @param int $keeptime 默认为永久
     */
    public function keeptime($keeptime = 0)
    {
        $this->keeptime = $keeptime;
    }

    /**
     * 注销登录状态
     * @return boolean
     */
    public function logout()
    {
        if (!$this->_logined) {
            $this->setError('你当前还未登录');
            return false;
        }
        //设置登录标识
        $this->_logined = false;
        //删除Token
        Token::delete($this->_token);
        //退出成功的事件
        Hook::listen("user_logout_successed", $this->_user);
        return true;
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return boolean
     */
    public function match($arr = [])
    {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower(request()->action()), $arr) || in_array('*', $arr)) {
            return true;
        }
        // 没找到匹配
        return false;
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 设置错误信息
     *
     * @param $error 错误信息
     * @return Auth
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return type
     */
    public function getError()
    {
        return $this->_error;
    }

}
