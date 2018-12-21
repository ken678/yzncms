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
namespace app\cms\validate;

use think\Validate;

class Models extends Validate
{

    //定义验证规则
    protected $rule = [
        'name' => 'require|max:30|unique:model',
        'tablename' => 'require|max:20|unique:model|alpha',
        'type' => 'in:1,2',
    ];
    //定义验证提示
    protected $message = [
        'name.require' => '模型名称不得为空',
        'name.max' => '模型名称长度错误',
        'name.unique' => '模型名称已存在',
        'tablename.require' => '表键名不得为空',
        'tablename.max' => '表键名长度错误',
        'tablename.alpha' => '表键名只支持字母',
        'tablename.unique' => '表键名已存在',
        'type.in' => '模型类型类型错误',
    ];
}
