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
        'parentid' => 'require|number',
        'modelid' => 'require|number',
        'type' => 'require|in:1,2,3',
        'catname' => 'require|chsAlphaNum',
        'catdir' => 'require|alphaNum|unique:category',
        'image' => 'number',
        'listorder' => 'require|number',
        'status' => 'require|in:0,1',

    ];
    //定义验证提示
    protected $message = [
        'parentid.require' => '上级栏目不得为空',
        'parentid.number' => '上级栏目格式错误',
        'modelid.require' => '所属模型不得为空',
        'modelid.number' => '所属模型格式错误',
        'type.require' => '栏目类型不得为空',
        'type.in' => '栏目类型格式错误',
        'catname.require' => '栏目标题不得为空',
        'catname.chsAlphaNum' => '栏目标题只能是汉字、字母和数字',
        'catdir.require' => '唯一标识不得为空',
        'catdir.alphaNum' => '唯一标识为字母和数字',
        'catdir.unique' => '唯一标识已经存在',
        'image.number' => '栏目图片格式不正确',
        'listorder.require' => '栏目排序不得为空',
        'listorder.number' => '栏目排序格式不正确',
        'status.require' => '栏目状态不得为空',
        'status.in' => '栏目状态格式不正确',

    ];
    protected $scene = [
        'page' => ['parentid', 'type', 'catname', 'catdir', 'image', 'listorder', 'status'],
        'list' => ['parentid', 'modelid', 'type', 'catname', 'catdir', 'image', 'listorder', 'status'],
        'link' => ['parentid', 'type', 'catname', 'catdir', 'image', 'listorder', 'status'],
    ];
}
