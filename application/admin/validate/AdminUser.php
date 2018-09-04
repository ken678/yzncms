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
// | 登录验证
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class AdminUser extends Validate
{

    //定义验证规则
    protected $rule = [
        'username' => 'unique:admin|require|alphaDash|length:3,15',
        'password' => 'require|length:4,20|confirm',
        'email' => 'email',
        'roleid' => 'require',

    ];
    //定义验证提示
    protected $message = [
        'username.unique' => '用户名已经存在！',
        'username.require' => '用户名不能为空！',
        'username.alphaDash' => '用户名格式不正确！',
        'username.length' => '用户名长度3-15位！',
        'password.require' => '密码不能为空！',
        'password.length' => '密码长度4-20位！',
        'password.confirm' => '两次输入的密码不一样！',
        'email.email' => '邮箱地址有误！',
        'roleid.require' => '请选择一个权限组！',

    ];

    // 登录验证场景定义
    public function sceneChecklogin()
    {
        return $this->only(['username', 'password'])
            ->remove('username', 'unique')
            ->remove('password', 'confirm');
    }

    // 登录验证场景定义
    public function sceneUpdate()
    {
        return $this->only(['username', 'password', 'email', 'roleid'])
            ->remove('password', 'require');
    }

    //定义验证场景
    protected $scene = [
        'insert' => ['username', 'password', 'email', 'roleid'],

    ];

}
