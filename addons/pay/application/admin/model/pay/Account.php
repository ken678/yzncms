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
namespace app\admin\model\pay;

use app\member\model\Member;
use think\Model;
use think\model\concern\SoftDelete;

class Account extends Model
{
    use SoftDelete;
    protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;

    protected $name = 'pay_account';
    // 定义时间戳字段名
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    public function getStatusAttr($value)
    {
        $status = ['succ' => '交易成功', 'failed' => '交易失败', 'error' => '交易错误', 'progress' => '交易处理中', 'timeout' => '交易超时', 'cancel' => '交易取消', 'waitting' => '等待付款', 'unpay' => '未付款'];
        return $status[$value];
    }

    public function getPayTypeAttr($value)
    {
        $status = [
            'recharge' => '后台充值',
            'wechat'   => '微信支付',
            'alipay'   => '支付宝支付',
        ];
        return $status[$value];
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
        throw new \Exception("充值失败！");
    }

}
