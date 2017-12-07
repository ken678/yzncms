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
namespace app\user\validate;

use think\Validate;

/**
 *  UC验证模型
 */
class UcenterMember extends Validate
{
    // 验证规则
    protected $rule = [
        ['username', 'require|unique:UcenterMember|length:6,30', '用户名必须|用户已存在|用户名长度6-30'],
        ['email', 'require|unique:UcenterMember|email|length:1,32', '邮箱必须|邮箱已存在|邮箱格式不正确|邮箱长度不合法'],
        ['mobile', 'unique', '手机号已存在'],
        ['password', 'require|length:6,30', '密码必须|密码长度6-30'],
    ];

}
