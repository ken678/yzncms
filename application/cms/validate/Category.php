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
// | 栏目验证
// +----------------------------------------------------------------------
namespace app\cms\validate;

use think\Validate;

class Category extends Validate
{

    //定义验证规则
    protected $rule = [
        'modelid' => 'require|number',
        'type' => 'require|number',
        'catname' => 'require|chsAlphaNum',

    ];
    //定义验证提示
    protected $message = [
        'modelid.require' => '所属模型不得为空',
        'modelid.number' => '所属模型格式错误',
        'type.require' => '栏目类型不得为空',
        'type.number' => '栏目类型格式错误',
        'catname.require' => '栏目标题不得为空',
        'catname.chsAlphaNum' => '只能是汉字、字母和数字',

    ];

    protected $scene = [
        'page' => ['type', 'catname'],
        'list' => ['modelid', 'type', 'catname'],
        'link' => ['type', 'catname'],
    ];
}
