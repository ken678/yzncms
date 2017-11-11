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
namespace app\admin\service;

class User
{
    //存储用户uid的Key
    const userUidKey = 'yzn_userid';
    //超级管理员角色id
    const administratorRoleId = 1;

    //当前登录会员详细信息
    private static $userInfo = array();

    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     * @return \Admin\Service\Cache
     */
    public static function getInstance()
    {
        static $handier = null;
        if (empty($handier)) {
            $handier = new User();
        }
        return $handier;
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
     * 获取当前登录用户资料
     * @return array
     */
    public function getInfo()
    {
        if (empty(self::$userInfo)) {
            self::$userInfo = $this->getUserInfo($this->isLogin());
        }
        return !empty(self::$userInfo) ? self::$userInfo : false;
    }

    /**
     * 管理员登录检测
     * @param string $username 用户名
     * @param string $password 密码
     */
    public function checkLogin($username = '', $password = '')
    {
        if (empty($username) || empty($password)) {
            return false;
        }
        $condition['username'] = trim($username);
        $condition['password'] = trim($password);
        //验证
        $userInfo = $this->getUserInfo($condition['username'], $condition['password']);
        if (false == $userInfo) {
            //记录登录日志
            return false;
        }
        $this->autoLogin($userInfo);
        return true;
    }

    /**
     * 自动登录用户
     */
    public function autoLogin($userInfo)
    {
        //记录行为
        action_log('user_login', 'member', $userInfo['userid'], $userInfo['userid']);

        /* 更新登录信息 */
        /*$data = array(
        'last_login_time' => time(),
        'last_login_ip' => get_client_ip(1),
        );
        $this->where(array('userid' => $userInfo['userid']))->update($data);*/
        //写入session
        session(self::userUidKey, \util\Encrypt::authcode((int) $userInfo['userid'], ''));
    }

    /**
     * 检查当前用户是否超级管理员
     * @return boolean
     */
    public function isAdministrator()
    {
        $userInfo = $this->getInfo();
        if (!empty($userInfo) && $userInfo['roleid'] == self::administratorRoleId) {
            return true;
        }
        return false;
    }

    /**
     * 检验用户是否已经登陆
     * @return boolean 失败返回false，成功返回当前登陆用户基本信息
     */
    public function isLogin()
    {
        $userId = \util\Encrypt::authcode(session(self::userUidKey), 'DECODE');
        if (empty($userId)) {
            return false;
        }
        return (int) $userId;
    }

    /**
     * 注销登录状态
     * @return boolean
     */
    public function logout()
    {
        \think\Session::clear();
        \think\Session::destroy();
        return true;
    }

    /**
     * 获取用户信息
     * @param type $identifier 用户名或者用户ID
     * @return boolean|array
     */
    private function getUserInfo($identifier, $password = null)
    {
        if (empty($identifier)) {
            return false;
        }
        return model('admin/User')->getUserInfo($identifier, $password);
    }

}
