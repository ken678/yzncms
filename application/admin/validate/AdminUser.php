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
        'username' => 'require',
        'password' => 'require',

    ];
    //定义验证提示
    protected $message = [
        'username.require' => '用户名不得为空',
        'password.require' => '密码不得为空',

    ];
    //定义验证场景
    protected $scene = [
        //登录
        'checklogin' => ['username', 'password'],
    ];

}
