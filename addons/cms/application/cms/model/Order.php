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
// | 订单模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use app\member\model\Member;
use app\member\service\User;
use think\Model;

class Order extends Model
{
    protected $name = "cms_order";
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //提交订单
    public static function submitOrder($catid, $id, $pay_type = 'wechat', $method = 'web', $notifyurl = '', $returnurl = '')
    {
        //获取栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            throw new \Exception('栏目不存在！');
        }
        $modelid   = $category['modelid'];
        $modelInfo = cache('Model')[$modelid];
        if (empty($modelInfo)) {
            throw new \Exception('模型不存在！');
        }

        $info = (new Cms)->getContent($modelid, ['catid' => $catid, 'id' => $id], false, '*');
        if (!$info) {
            throw new \Exception('支付内容不存在或未审核!');
        }
        $paytype   = $info['paytype'] ?? 1; // 1钱 2积分
        $readpoint = $info['readpoint'] ?? 0; //金额
        if ($paytype == 2 && $pay_type != 'balance') {
            //TODO 暂不支持积分使用微信支付宝
            throw new \Exception('积分付费只能选择余额支付!');
        }

        $order = self::where(['catid' => $catid, 'contentid' => $id])->order('id', 'desc')->find();
        if ($order && $order['status'] == 'succ') {
            throw new \Exception('订单已支付');
        }
        $auth     = User::instance();
        $trade_sn = date("Ymdhis") . sprintf("%08d", $auth->id) . mt_rand(1000, 9999);

        if (!$order) {
            //无订单创建
            $data = [
                'trade_sn'  => $trade_sn,
                'catid'     => $catid,
                'contentid' => $id,
                'user_id'   => $auth->id,
                'title'     => "付费阅读:" . substr($info['title'], 0, 60),
                'pay_price' => $readpoint,
                'pay_type'  => $pay_type,
                'method'    => $method,
                'ip'        => request()->ip(),
                'useragent' => substr(request()->server('HTTP_USER_AGENT'), 0, 255),
                'status'    => 'unpay',
                'remark'    => '',
            ];
            $order = self::create($data);
        } else {
            //已存在未支付订单更新
            $order->trade_sn  = $trade_sn;
            $order->pay_price = $readpoint;
            $order->pay_type  = $pay_type;
            $order->method    = $method;
            $order->save();
        }

        //使用余额支付
        if ($pay_type == 'balance') {
            $paytype = $paytype == 1 ? "amount" : "point";
            if ($auth->{$paytype} < $readpoint) {
                throw new \Exception(($paytype == "amount" ? "余额" : "积分") . '不足，请先充值!');
            }
            try {
                Member::{$paytype}(-$readpoint, $auth->id, '购买付费文档:' . $info['title']);
                self::settle($order->trade_sn, $readpoint);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
            return true;
        }

        $info = get_addon_info('pay');
        if ($info && $info['status'] > 0) {
            $notifyurl = $notifyurl ? $notifyurl : request()->root(true) . '/cms/index/epay/type/notify/pay_type/' . $pay_type;
            $returnurl = $returnurl ? $returnurl : request()->root(true) . '/cms/index/epay/type/return/pay_type/' . $pay_type . '/trade_sn/' . $order->trade_sn;

            $pay_price = sprintf("%.2f", $order->pay_price);
            try {
                //扫码支付后跳转链接
                \think\facade\Session::set('jumpUrl', buildContentUrl($catid, $id, '', true, true));
                //调用支付插件支付接口
                \addons\pay\library\Service::submitOrder($pay_price, $order->trade_sn, $pay_type, "支付{$pay_price}元", $notifyurl, $returnurl, $method);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            throw new \Exception("请先在后台安装支付插件");
        }
    }

    //订单是否支付
    public static function check_payment($info)
    {
        $auth = User::instance();
        if ($auth->id && $auth->id == $info['uid']) {
            return true;
        }
        return self::where([
            'catid'     => $info['catid'],
            'contentid' => $info['id'],
            'status'    => 'succ',
        ])->find() ? true : false;
    }

    //订单结算
    public static function settle($trade_sn, $pay_price)
    {
        $order = self::getByTradeSn($trade_sn);
        if (!$order) {
            return false;
        }
        if ($order['status'] != 'paid') {
            if ($pay_price != $order->pay_price) {
                throw new \Exception("订单金额异常");
                return false;
            }
            try {
                $order->pay_time = time();
                $order->status   = 'succ';
                $order->save();
            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }
}
