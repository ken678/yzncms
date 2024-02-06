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
// | 后台用户管理
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;

class AdminUser extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    // 设置当前模型对应的完整数据表名称
    protected $name   = 'admin';
    protected $insert = ['status' => 1];

    public function getLastLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public static function init()
    {
        self::beforeWrite(function ($row) {
            $changed = $row->getChangedData();
            //如果修改了用户或或密码则需要重新登录
            if (isset($changed['username']) || isset($changed['password']) || isset($changed['salt'])) {
                $row->token = '';
            }
        });
    }
}
