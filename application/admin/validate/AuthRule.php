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
 * 菜单验证器
 */
class AuthRule extends Validate
{
    //定义验证规则
    protected $rule = [
        'pid|上级菜单'  => 'require|number',
        'name|规则'       => 'require|unique:AuthRule',
        'title|标题'       => 'require',
    ];

}
