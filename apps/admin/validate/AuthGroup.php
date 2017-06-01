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
namespace app\admin\validate;
use think\Validate;

/**
 * 用户组验证
 */
class AuthGroup extends Validate
{
    //定义验证规则
    protected $rule = [
       'title|权限组' => 'require',
       'description|描述'  => 'length:0,80',

    ];

    //定义验证提示
    protected $message = [
       'title.require' => '权限组不得为空',
       'description.length'  => '描述最多80字符',
    ];

}