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
class Admin extends Model {
	/**
	 * 管理员登录检测
	 */
	public function checkLogin(){
		$condition['username'] = input("post.username");
        $condition['password'] = input("post.password");
        $condition['captcha']  = input("post.captcha");
        /*if(!CheckVerify($captcha)){
            $this->error ='验证码输入错误！';
            return false;
        }*/
        if(!empty($condition['username']) && !empty($condition['password'])){
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
        }else{
            $this->error ='账号密码不得为空！';
            return false;
        }
        $this->error ='账号密码不正确！';
        return false;
	}



    /**
     * 自动登录用户
     */
    private function autoLogin($user){

    }















}