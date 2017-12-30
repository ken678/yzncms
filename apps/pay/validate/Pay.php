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
namespace app\pay\validate;

use think\Validate;

class Pay extends Validate
{
    protected $rule = [
        'pay_type' => 'require|number|in:1,2',
        'username' => 'require',
        'pay_unit' => 'require|number|in:1,0',
        'unit' => 'require|number',
        'usernote' => 'require',
    ];
    protected $message = [
        'pay_type.require' => '充值类型必须',
        'pay_type.number' => '充值类型格式错误',
        'pay_type.in' => '充值类型范围错误',
        'username.require' => '用户名必须',
        'pay_unit.require' => '充值方法必须',
        'pay_unit.number' => '充值方法格式错误',
        'pay_unit.in' => '充值方法范围错误',
        'unit.require' => '充值额度必须',
        'unit.number' => '充值额度必须数字',
        'usernote.require' => '交易备注必须',
    ];

}
