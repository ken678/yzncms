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
namespace app\content\validate;

use think\Validate;

/**
 * 栏目验证
 */
class Category extends Validate
{
    protected $rule = [
        'parentid' => 'require|number',
        'catname' => 'require',
        'catdir' => 'require',
        'type' => 'require|in:0,1,2',
        'url' => 'require',

    ];

    protected $message = [
        'modelid.number' => '模型类型错误！',
        'parentid.require' => '上级栏目不得为空',
        'parentid.number' => '上级栏目类型错误！',
        'catname.require' => '栏目名称不得为空',
        'catdir.require' => '英文目录不得为空',
        'type.require' => '栏目类型不得为空',
        'type.in' => '栏目类型不存在！',
        'url.require' => '链接地址不得为空',
    ];

    protected $scene = [
        'add' => ['parentid', 'catname', 'catdir', 'type'],
        'wadd' => ['parentid', 'catname', 'type', 'url'],
        'edit' => ['parentid', 'catname', 'catdir'],
        'wedit' => ['parentid', 'catname', 'url'],
    ];

}
