<?php

namespace addons\signin\library;

use addons\signin\model\Signin;
use app\member\service\User;
use think\Db;
use util\Date;

class Service
{
    /**
     * 获取排名信息
     *
     * @param int $uid
     * @return array
     */
    public static function getRankInfo($uid = null)
    {
        $uid      = is_null($uid) ? User::instance()->id : $uid;
        $rankList = Signin::with(["user"])
            ->where("create_time", ">", Date::unixtime('day', -1))
            ->field("uid,MAX(successions) AS days")
            ->group("uid,create_time")
            ->order("days DESC,create_time ASC")
            ->limit(10)
            ->select();
        foreach ($rankList as $index => $datum) {
            $datum->getRelation('user')->visible(['id', 'username', 'nickname', 'avatar']);
        }

        $ranking  = 0;
        $lastdata = Signin::where('uid', $uid)->order('create_time', 'desc')->find();
        //是否已签到
        $checked = $lastdata && $lastdata['create_time'] >= Date::unixtime('day') ? true : false;
        //连续登录天数
        $successions = $lastdata && $lastdata['create_time'] >= Date::unixtime('day', -1) ? $lastdata['successions'] : 0;
        if ($successions > 0) {
            //优先从列表中取排名
            foreach ($rankList as $index => $datum) {
                if ($datum->uid == $uid) {
                    $ranking = $index + 1;
                    break;
                }
            }
            if (!$ranking) {
                $prefix  = config('database.prefix');
                $ret     = Db::query("SELECT COUNT(*) nums FROM (SELECT uid,MAX(successions) days FROM `{$prefix}signin` WHERE create_time > " . Date::unixtime('day', -1) . " GROUP BY uid ORDER BY days) AS dd WHERE dd.days >= " . $successions);
                $ranking = $ret[0]['nums'] ?? 0;
            }
        }
        return [$rankList, $ranking, $successions, $checked];
    }
}
