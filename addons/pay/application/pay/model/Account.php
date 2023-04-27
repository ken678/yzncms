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
// | 支付模块模型
// +----------------------------------------------------------------------
namespace app\pay\model;

use app\member\model\Member;
use app\member\service\User as home_user;
use think\Model;

class Account extends Model
{
    protected $name = 'pay_account';
    // 定义时间戳字段名
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    // 追加属性
    protected $append = [
        'pay_type_text',
        'status_text',
    ];

    public function getStatusTextAttr($value, $data)
    {
        $value  = $value ? $value : $data['status'];
        $status = ['succ' => '交易成功', 'failed' => '交易失败', 'error' => '交易错误', 'progress' => '交易处理中', 'timeout' => '交易超时', 'cancel' => '交易取消', 'waitting' => '等待付款', 'unpay' => '未付款'];
        return $status[$value] ?? '';
    }

    public function getPayTypeTextAttr($value, $data)
    {
        $value  = $value ? $value : $data['pay_type'];
        $status = [
            'recharge' => '后台充值',
            'wechat'   => '微信',
            'alipay'   => '支付宝',
        ];
        return $status[$value] ?? '';
    }

    /**
     * 发起订单支付
     * @param float  $money
     * @param string $paytype
     */
    public function submitOrder($money, $pay_type = 'wechat')
    {
        $uid      = home_user::instance()->isLogin() ? home_user::instance()->id : 0;
        $username = home_user::instance()->isLogin() ? home_user::instance()->username : '未知';
        $data     = [
            'trade_sn'  => date("Ymdhis") . sprintf("%08d", $uid) . mt_rand(1000, 9999),
            'uid'       => $uid,
            'username'  => $username,
            'type'      => 1,
            'money'     => $money,
            'payamount' => 0,
            'pay_type'  => $pay_type,
            'ip'        => request()->ip(),
            'status'    => 'unpay',
        ];
        $order     = self::create($data);
        $notifyurl = request()->root(true) . '/pay/index/epay/type/notify/pay_type/' . $pay_type;
        $returnurl = request()->root(true) . '/pay/index/epay/type/return/pay_type/' . $pay_type;
        \addons\pay\library\Service::submitOrder($money, $order->trade_sn, $pay_type, "充值{$money}元", $notifyurl, $returnurl);
        exit;

    }

    /**
     * 添加积分/金钱记录
     */
    public function _add($type, $money, $pay_type, $uid, $username, $usernote = '', $adminnote = '', $status = 'succ')
    {
        $data              = array();
        $data['type']      = isset($type) && intval($type) ? intval($type) : 0;
        $data['trade_sn']  = date("Ymdhis") . sprintf("%08d", $uid) . mt_rand(1000, 9999);
        $data['uid']       = isset($uid) && intval($uid) ? intval($uid) : 0;
        $data['username']  = isset($username) ? trim($username) : '';
        $data['money']     = isset($money) && floatval($money) ? floatval($money) : 0;
        $data['pay_time']  = time();
        $data['usernote']  = $usernote;
        $data['pay_type']  = isset($pay_type) ? trim($pay_type) : 'selfincome';
        $data['ip']        = request()->ip();
        $data['adminnote'] = isset($adminnote) ? trim($adminnote) : '';
        $data['status']    = isset($status) ? trim($status) : 'succ';
        if (self::create($data)) {
            if ($data['type'] == 1) {
                //金钱方式充值
                Member::amount($data['money'], $data['uid'], $usernote);
            } else {
                //积分方式充值
                Member::point($data['money'], $data['uid'], $usernote);
            }
            return true;
        }
        return false;
    }

    /**
     * 订单结算
     * @param int    $orderid
     * @param string $payamount
     * @param string $memo
     * @return bool
     */
    public function settle($orderid, $payamount = null, $memo = '')
    {
        $order = self::getByTradeSn($orderid);
        if (!$order) {
            return false;
        }
        if ($order->getData('status') != 'succ') {
            $order->money    = $payamount ? $payamount : $order->money;
            $order->pay_time = time();
            $order->status   = 'succ';
            $order->save();
            // 更新会员余额
            Member::amount($order->money, $order->uid, '充值');
        }
        return true;
    }

}
