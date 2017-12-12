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
class Member extends Validate
{
    protected $rule = [
        'nickname' => 'require|unique:Member,nickname^uid|length:3,15',
    ];

    protected $message = [
        'nickname.require' => '昵称不得为空',
        'nickname.unique' => '昵称已经存在',
        'nickname.length' => '昵称长度3-20',
    ];

    protected $scene = [
        'edit' => ['nickname'],
    ];

}
