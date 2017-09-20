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
 * 单页验证
 */
class Page extends Validate
{
    protected $rule = [
        'catid' => 'require|number',
        'title' => 'require',
        'content' => 'require',
    ];

    protected $message = [
        'catid.require' => '栏目ID不得为空',
        'catid.number' => '栏目ID格式错误',
        'title.require' => '标题不得为空',
        'content.require' => '内容不得为空',

    ];

    protected $scene = [
        'add' => ['catid', 'title', 'content'],
        'edit' => ['title', 'content'],
    ];

}
