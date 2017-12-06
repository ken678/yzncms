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

namespace app\member\model;

use app\user\api\UserApi;
use think\Model;

/**
 * 模块模型
 * @package app\admin\model
 */
class Member extends Model
{
    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid)
    {
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($uid);
        if (!$user) {
            /* 在当前应用中注册用户 */
            $Api = new UserApi();
            $info = $Api->info($uid);
            $map = ['uid' => $uid, 'nickname' => $info[1], 'email' => $info[2], 'status' => 1];
            if (!$this->create($map)) {
                $this->error = '前台用户信息注册失败，请重试！';
                return false;
            }
            $user = $this->field(true)->find($uid);
        } elseif (1 != $user['status']) {
            $this->error = '用户未激活或已禁用！'; //应用级别禁用
            return false;
        }
        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user)
    {
        //记录行为
        action_log('user_login', 'member', $user['uid'], $user['uid']);
        /* 更新登录信息 */
        $data = array(
            'uid' => $user['uid'],
            'login' => array('exp', '`login`+1'),
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(1),
        );
        $this->where('uid', 'eq', $user['uid'])->update($data);
        /* 记录登录SESSION和COOKIES */
        $auth = [
            'uid' => $user['uid'],
            'username' => get_username($user['uid']),
            'last_login_time' => $user['last_login_time'],
        ];

        session('home_user_auth', $auth);
        session('home_user_auth_sign', data_auth_sign($auth));

    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout()
    {
        session('home_user_auth', null);
        session('hone_user_auth_sign', null);
    }

    //会员配置缓存
    public function member_cache()
    {
        $data = unserialize(db('Module')->where(array('module' => 'Member'))->column('setting'));
        cache("Member_Config", $data);
        $this->member_model_cahce();
        return $data;
    }

    //会员模型缓存
    public function member_model_cahce()
    {
        $data = model('content/Models')->getModelAll(2);
        cache("Model_Member", $data);
        return $data;
    }

}
