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
namespace app\member\validate;

use think\Validate;

/**
 * 模型验证
 */
class UcenterMember extends Validate
{
    protected $rule = [
        'username' => 'require|unique:UcenterMember|alphaDash|length:3,15',
        'password' => 'require|confirm:pwdconfirm|length:6,30',
        'email' => 'require|unique:UcenterMember|email|length:1,32',
        'mobile' => 'unique:UcenterMember',
        'groupid' => 'checkGroupid:',
        'modelid' => 'checkModelid:',
    ];

    protected $message = [
        'username.require' => '用户名不得为空',
        'username.unique' => '用户名已经存在',
        'username.alphaDash' => '用户名不合法',
        'username.length' => '用户名长度3-15',
        'password.require' => '密码不能为空',
        'password.confirm' => '两次输入的密码不一样！',
        'password.length' => '密码长度6-30',
        'email.require' => '邮箱不得为空',
        'email.unique' => '邮箱已存在',
        'email.email' => '邮箱格式不正确',
        'email.length' => '邮箱长度不合法',
        'mobile.unique' => '手机号码已经存在',
        'groupid.checkGroupid' => '该会员组不存在！',
        'modelid.checkModelid' => '该会员模型不存在！',
    ];

    protected $scene = [
        'add' => ['username', 'password', 'email', 'mobile', 'groupid', 'modelid'],
        'edit' => [],
    ];

    //检查会员组
    public function checkGroupid($value)
    {
        $Member_group = cache('Member_group');
        if (!$Member_group[$value]) {
            return '该会员组不存在！';
        }
        return true;
    }

    //检查会员模型
    public function checkModelid($value)
    {
        $Model_Member = cache("Model_Member");
        if (!$Model_Member[$value]) {
            return '该会员模型不存在！';
        }
        return true;
    }

}
