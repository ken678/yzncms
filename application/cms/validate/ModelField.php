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

class ModelField extends Validate
{

    //定义验证规则
    protected $rule = [
        'name' => 'require|alphaNum',
        'title' => 'require|chsAlpha',
        'type' => 'require|alphaDash',
        'define' => 'require',
        'value' => 'chsAlphaNum',

    ];
    //定义验证提示
    protected $message = [
        'name.require' => '字段名称不得为空',
        'name.alphaNum' => '字段名称只能为字母和数字',
        'title.require' => '字段标题不得为空',
        'title.chsAlpha' => '字段标题只能为只能是汉字和字母',
        'type.require' => '字段类型不得为空',
        'type.alphaDash' => '字段类型格式错误',
        'define.require' => '字段定义不得为空',
        'value.chsAlphaNum' => '字段默认值格式错误',

    ];
}
