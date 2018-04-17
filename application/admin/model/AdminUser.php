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
// | 后台用户管理
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\helper\Hash;
use think\Model;

class AdminUser extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__ADMIN__';
    protected $pk = 'userid';

    /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool|mixed
     */
    public function login($username = '', $password = '')
    {
        $username = trim($username);
        $password = trim($password);
        $map['username'] = $username;
        $userInfo = self::get($map);
        if (!$userInfo) {
            $this->error = '用户不存在！';
        } else {
            //密码判断
            if (!empty($password) && encrypt_password($password, $userInfo['encrypt']) != $userInfo['password']) {
                $this->error = '密码错误！';
            } else {
                $this->autoLogin((int) $userInfo['userid']);
                return true;
            }
        }
        return false;
    }

    /**
     * 自动登录用户
     */
    public function autoLogin($userInfo)
    {
        //记录行为
        //action_log('user_login', 'member', $userInfo['userid'], $userInfo['userid']);
        /* 更新登录信息 */
        $data = array(
            'uid' => $userInfo['userid'],
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(1),
        );
        $this->loginStatus((int) $userInfo['userid']);
        /* 记录登录SESSION和COOKIES */
        $auth = [
            'uid' => $userInfo['userid'],
            'username' => $userInfo['username'],
            'last_login_time' => $userInfo['last_login_time'],
        ];
        Session('admin_user_auth', $auth);
        Session('admin_user_auth_sign', data_auth_sign($auth));
    }

    /**
     * 更新登录状态信息
     * @param type $userId
     * @return type
     */
    public function loginStatus($userId)
    {
        $data = ['last_login_time' => time(), 'last_login_ip' => get_client_ip(1)];
        return $this->save($data, ['userid' => $userId]);
    }

}
