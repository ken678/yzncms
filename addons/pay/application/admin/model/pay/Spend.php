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
namespace app\admin\model\pay;

use app\member\model\Member;
use think\Model;

class Spend extends Model
{
    protected $name = 'pay_spend';
    // 定义时间戳字段名
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    /**
     * 消费积分/金钱记录
     */
    public function _spend($type, $money, $uid = '', $username = '', $msg = '', $remarks = '')
    {
        $data             = [];
        $data['type']     = $type ? intval($type) : 0;
        $data['creat_at'] = date('YmdHis') . mt_rand(1000, 9999);
        $data['money']    = $money ? floatval($money) : 0;
        $data['uid']      = $uid ? intval($uid) : 0;
        $data['username'] = $username ? trim($username) : '';
        $data['msg']      = $msg ? trim($msg) : '';
        $data['remarks']  = $remarks ? trim($remarks) : '';
        $data['ip']       = request()->ip();
        //判断用户的金钱或积分是否足够。
        self::_check_user($data['uid'], $data['type'], $data['money']);

        if (self::create($data)) {
            if ($data['type'] == 1) {
                //金钱方式消费
                Member::amount(-$data['money'], $data['uid'], $data['remarks']);
            } else {
                //积分方式消费
                Member::point(-$data['money'], $data['uid'], $data['remarks']);
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
     */
    private static function _check_user($uid, $type, $value)
    {
        if ($user = Member::where('id', $uid)->find()) {
            if ($type == 1) {
                //金钱消费
                if ($user['amount'] < $value) {
                    throw new \Exception("余额不足，请先充值！");
                } else {
                    return true;
                }
            } elseif ($type == 2) {
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
