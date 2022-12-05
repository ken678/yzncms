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
// | 会员组模型
// +----------------------------------------------------------------------
namespace app\admin\model\member;

use think\Model;

class MemberGroup extends Model
{
    protected $auto   = ['allowvisit' => 1, 'status' => 1];
    protected $insert = ['issystem' => 0];

    protected static function init()
    {
        self::afterWrite(function ($row) {
            cache('Member_Group', null);
        });
        self::beforeDelete(function ($row) {
            if ($row['issystem']) {
                throw new \Exception('系统用户组[' . $row['name'] . ']不能删除！');
            }
        });
        self::afterDelete(function ($row) {
            cache('Member_Group', null);
        });
    }
}
