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
// | 消费模块模型
// +----------------------------------------------------------------------
namespace app\pay\model;

use think\Db;
use \think\Model;

class Spend extends Model
{
    protected $name = 'pay_spend';
    // 定义时间戳字段名
    protected $autoWriteTimestamp = true;
    protected $createTime         = 'addtime';
    protected $updateTime         = false;

    /**
     * 按用户名、时间、标识查询是否有消费记录
     * @param integer $userid      用户名
     * @param integer $time        时间。  从指定时间到现在的时间范围内。
     * @param string $flag   标识
     */
    public static function spend_time($userid, $time, $flag)
    {
        return self::where(array('uid' => $userid, 'remarks' => $flag))->order('id DESC')->find();
        //return self::where(array('uid' => $userid, 'remarks' => $flag))->whereTime('addtime', '-' . $time . ' hours')->order('id DESC')->find();
    }

    /**
     * 消费积分/金钱记录
     */
    public function _spend($type, $money, $uid = '', $username = '', $msg = '', $remarks = '')
    {
        $data             = array();
        $data['type']     = isset($type) && intval($type) ? intval($type) : 0;
        $data['creat_at'] = date('YmdHis') . mt_rand(1000, 9999);
        $data['money']    = isset($money) && floatval($money) ? floatval($money) : 0;
        $data['uid']      = isset($uid) && intval($uid) ? intval($uid) : 0;
        $data['username'] = isset($username) ? trim($username) : '';
        $data['msg']      = isset($msg) ? trim($msg) : '';
        $data['remarks']  = isset($remarks) ? trim($remarks) : '';
        $data['ip']       = request()->ip();
        //判断用户的金钱或积分是否足够。
        self::_check_user($data['uid'], $data['type'], $data['money']);

        if (self::create($data)) {
            if ($data['type'] == 1) {
                //金钱方式消费
                Db::name('member')->where(['id' => $data['uid'], 'username' => $data['username']])->setDec('amount', $data['money']);
            } else {
                //积分方式消费
                Db::name('member')->where(['id' => $data['uid'], 'username' => $data['username']])->setDec('point', $data['money']);
            }
            return true;
        }
        throw new \Exception("充值失败！");
    }

/**
 * 判断用户的金钱、积分是否足够
 * @param integer $userid    用户ID
 * @param integer $type      判断（1：金钱，2：积分）
 * @param integer $value     数量
 * @param $db                数据库连接
 */
    private static function _check_user($uid, $type, $value)
    {
        if ($user = Db::name('member')->where('id', $uid)->find()) {
            if ($type == 1) {
                //金钱消费
                if ($user['amount'] < $value) {
                    throw new \Exception("余额不足，请先充值！");
                } else {
                    return true;
                }
            } elseif ($type == 0) {
                //积分
                if ($user['point'] < $value) {
                    throw new \Exception("积分不足，请先充值！");
                } else {
                    return true;
                }
            } else {
                throw new \Exception("充值异常！");
            }
        } else {
            throw new \Exception("此用户不存在！");
        }
    }

}
