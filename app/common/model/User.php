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
// | 会员模型
// +----------------------------------------------------------------------
namespace app\common\model;

use app\common\library\Auth;
use think\facade\Config;
use think\facade\Db;
use think\Model;

/**
 * 会员模型
 */
class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $createTime         = 'reg_time';

    protected function setRegIpAttr()
    {
        return request()->ip();
    }

    protected function setLastLoginIpAttr()
    {
        return request()->ip();
    }

    protected function setLastLoginTimeAttr()
    {
        return time();
    }

    protected function setBirthdayAttr($value)
    {
        return $value ? $value : null;
    }

    protected function getBirthdayAttr($value)
    {
        return $value ? $value : '';
    }

    public function getLastLoginTimeAttr($value, $data)
    {
        return time_format($data['last_login_time']);
    }

    /**
     * 获取会员的组别
     */
    public function getGroupAttr($value, $data)
    {
        return UserGroup::find($data['group_id']);
    }

    /**
     * 变更会员余额
     * @param int    $amount   余额
     * @param int    $user_id 会员ID
     * @param string $remark    备注
     */
    public static function amount($amount, $user_id, $remark)
    {
        Db::startTrans();
        try {
            $user = self::lock(true)->find($user_id);
            if ($user && $amount != 0) {
                $before = $user->amount;
                //$after = $user->amount + $amount;
                $after = function_exists('bcadd') ? bcadd($user->amount, $amount, 2) : $user->amount + $amount;
                //更新会员信息
                $user->save(['amount' => $after]);
                //写入日志
                AmountLog::create(['user_id' => $user_id, 'amount' => $amount, 'before' => $before, 'after' => $after, 'remark' => $remark]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
        }
    }

    /**
     * 变更会员积分
     * @param int    $point   积分
     * @param int    $user_id 会员ID
     * @param string $remark    备注
     */
    public static function point($point, $user_id, $remark)
    {
        Db::startTrans();
        try {
            $user = self::lock(true)->find($user_id);
            if ($user && $point != 0) {
                $before  = $user->point;
                $after   = $user->point + $point;
                $groupid = Auth::instance()->get_usergroup_bypoint($after);
                //更新会员信息
                $user->save(['point' => $after, 'groupid' => $groupid]);
                //写入日志
                PointLog::create(['user_id' => $user_id, 'point' => $point, 'before' => $before, 'after' => $after, 'remark' => $remark]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
        }
    }

    /**
     * 获取头像
     * @param $value
     * @param $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            switch (Config::get('yzn.user_avatar_mode')) {
                case 'letter':
                    $value = letter_avatar($data['nickname']);
                    break;
                case 'gravatar':
                    $value = "https://cravatar.cn/avatar/" . md5(strtolower(trim($data['email']))) . "?s=80";
                    break;
                default:
                    $value = config('upload.cdnurl') . '/assets/img/avatar.png';
                    break;
            }
        }
        return cdnurl($value, true);
    }
}
