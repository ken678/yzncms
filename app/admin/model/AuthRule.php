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

// +----------------------------------------------------------------------
// | 权限规则模型
// +----------------------------------------------------------------------
namespace app\admin\Model;

use think\facade\Cache;
use think\Model;

class AuthRule extends Model
{
    const RULE_URL  = 1;
    const RULE_MAIN = 2; //主菜单

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function onAfterWrite($row)
    {
        Cache::delete('__menu__');
    }
}
