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
namespace app\member\model;

use app\member\service\User;
use think\Model;

class Member extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $createTime         = 'reg_time';
    protected $insert             = ['reg_ip', 'last_login_ip', 'last_login_time'];
    // 追加属性
    protected $append = [
        'groupname',
    ];

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

    public function getLastLoginTimeAttr($value, $data)
    {
        return time_format($data['last_login_time']);
    }

    public function getGroupnameAttr($value, $data)
    {
        $group = cache("Member_Group");
        return isset($group[$data['groupid']]['name']) ? $group[$data['groupid']]['name'] : '';
    }

    /**
     * 变更会员余额
     * @param int    $amount   余额
     * @param int    $user_id 会员ID
     * @param string $remark    备注
     */
    public static function amount($amount, $user_id, $remark)
    {
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
        } catch (\Exception $e) {
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
        try {
            $user = self::lock(true)->find($user_id);
            if ($user && $point != 0) {
                $before  = $user->point;
                $after   = $user->point + $point;
                $groupid = User::instance()->get_usergroup_bypoint($after);
                //更新会员信息
                $user->save(['point' => $after, 'groupid' => $groupid]);
                //写入日志
                PointLog::create(['user_id' => $user_id, 'point' => $point, 'before' => $before, 'after' => $after, 'remark' => $remark]);
            }
        } catch (\Exception $e) {
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
            $value = config('public_url') . 'static/addons/member/img/avatar.png';
            //启用首字母头像，请使用
            //$value = letter_avatar($data['nickname']);
        }
        return $value;
    }
}
