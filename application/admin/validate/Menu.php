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

use \think\Validate;

/**
 * 用户验证器
 */
class Menu extends Validate
{
    //定义验证规则
    protected $rule = [
        'parentid|上级菜单' => 'require|number',
        'title|名称' => 'require',
        'app|模块' => 'require',
        'controller|控制器' => 'require',
        'action|方法' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'parentid.require' => '上级菜单不能为空',
        'parentid.number' => '上级菜单格式错误',
        'title.require' => '菜单名称不能为空',
        'app.require' => '模块不能为空',
        'controller.require' => '控制器不能为空',
        'action.require' => '方法不能为空',
    ];

    //定义验证场景
    protected $scene = [
        'add' => ['parentid', 'title', 'app', 'controller', 'action'],
        'edit' => ['parentid', 'title', 'app', 'controller', 'action'],
    ];
}
