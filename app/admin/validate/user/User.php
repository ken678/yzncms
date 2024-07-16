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
// | 模型验证
// +----------------------------------------------------------------------
namespace app\admin\validate\user;

use think\Validate;

class User extends Validate
{
    //定义验证规则
    protected $rule = [
        'username|用户名' => 'unique:user|require|alphaDash|length:3,20',
        'nickname|昵称'  => 'unique:user|length:3,20',
        'password|密码'  => 'require|length:3,20',
        'email|邮箱'     => 'unique:user|require|email',
        'group_id|会员组' => 'require|number',
    ];

    public function sceneEdit()
    {
        return $this->remove('password', 'require');
    }

    public function sceneRegister()
    {
        return $this->remove('groupid', 'require');
    }

}
