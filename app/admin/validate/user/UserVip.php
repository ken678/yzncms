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
namespace app\admin\validate\user;

use think\Validate;

class UserVip extends Validate
{
    //定义验证规则
    protected $rule = [
        'level|vip等级' => 'unique:user_vip|require|number|between:1,9',
        'title|vip名称' => 'unique:user_vip|require|chsDash',
        'days|天数'     => 'require|number',
        'amount|价格'   => 'require',
        'equity|权益'   => 'require',
    ];
}
