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
namespace app\admin\model\user;

use think\Model;

class UserGroup extends Model
{
    protected $autoWriteTimestamp = true;

    public static function onBeforeInsert($row)
    {
        $maxPoint = self::max('point');
        if ($row->point <= $maxPoint) {
            throw new \Exception("积分必须大于当前最大积分({$maxPoint})");
        }
    }

    public static function onBeforeUpdate($row)
    {
        $prevPoint = self::where('id', '<', $row->id)
            ->order('point', 'desc')
            ->value('point');
        if ($prevPoint && $row->point <= $prevPoint) {
            throw new \Exception("积分必须大于上一个等级积分({$prevPoint})");
        }

        $nextPoint = self::where('id', '>', $row->id)
            ->order('point', 'asc')
            ->value('point');
        if ($nextPoint && $row->point >= $nextPoint) {
            throw new \Exception("积分必须小于下一个等级积分({$nextPoint})");
        }
    }

    public static function onbeforeDelete($row)
    {
        if ($row['issystem']) {
            throw new \Exception('系统用户组[' . $row['name'] . ']不能删除！');
        }
    }

    public function user()
    {
        return $this->belongsTo('User', 'id', 'group_id');
    }
}
