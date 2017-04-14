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
namespace app\admin\model;
use think\Model;

/**
 * 管理员模型
 */
class User extends Model {
    // 设置当前模型对应的完整数据表名称
    protected $table = '__ADMIN__';

	/**
	 * 管理员登录检测
     * @param string $username 用户名
     * @param string $password 密码
	 */
	public function checkLogin($username = '', $password = ''){
        $condition['username'] = trim($username);
        $condition['password'] = trim($password);
        $admin_info = $this->where(['username'=>$condition['username']]) ->find();
        if($admin_info)$admin_info =$admin_info ->toArray();
        if(is_array($admin_info)){
            /* 验证用户密码 */
            if($admin_info['password']==sha1($condition['password'].$admin_info['encrypt'])){
                /* 密码正确 自动登录用户 */
                $this->autoLogin($admin_info);
                return true;
            }
        }
        $this->error ='账号密码不正确！';
        return false;
	}



    /**
     * 自动登录用户
     */
    private function autoLogin($user){
    	/* 更新登录信息 */
        $data = array(
            'last_login_time' => time(),
            'last_login_ip'   => get_client_ip(1),
        );
        $this->where(array('userid'=>$user['userid']))->update($data);
    	/* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['userid'],
            'username'        => $user['nickname'],
            'last_login_time' => $user['last_login_time'],
        );

        session('last_login_time',$user['last_login_time']);
        session('last_login_ip',$user['last_login_ip']);

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));//签名

    }















}