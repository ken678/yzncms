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

class UserGroup extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|会员组名称'          => 'unique:user_group|require|chsDash|length:1,20',
        'point|积分'            => 'require|number',
        'starnum|星星数'         => 'require|number|between:0,99',
        'allowmessage|最大短消息数' => 'number',
        'allowpostnum|日最大投稿数' => 'number',
    ];
}
