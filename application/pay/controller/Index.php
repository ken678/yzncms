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
// | 会员支付前台
// +----------------------------------------------------------------------
namespace app\pay\controller;

use app\member\controller\MemberBase;
use app\pay\model\Account as Account_Model;
use app\pay\model\Payment as Payment_Model;

class Index extends MemberBase
{
    protected $noNeedLogin = ['epay'];
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Payment_Model = new Payment_Model;
        $this->Account_Model = new Account_Model;
    }

    //充值
    public function pay()
    {
        if ($this->request->isPost()) {
            $money = $this->request->request('money');
            $pay_type = $this->request->request('pay_type');
            if ($money <= 0) {
                $this->error('充值金额不正确');
            }
            try {
                $this->Account_Model->submitOrder($money, $pay_type ? $pay_type : 'wechat');
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }

        } else {
            $paytypeList = $this->Payment_Model->where('status', 1)->select();
            $this->assign('paytypeList', $paytypeList);
            return $this->fetch('/pay');
        }
    }

    /**
     * 企业支付通知和回调
     * @throws \think\exception\DbException
     */
    public function epay()
    {
        $type = $this->request->param('type');
        $pay_type = $this->request->param('pay_type');
        if ($type == 'notify') {
            $pay = \app\pay\library\Service::checkNotify($pay_type);
            if (!$pay) {
                echo '签名错误';
                return;
            }
            $data = $pay->verify();
            try {
                $payamount = $paytype == 'alipay' ? $data['total_amount'] : $data['total_fee'] / 100;
                $this->Account_Model->settle($data['out_trade_no'], $payamount);
            } catch (Exception $e) {
                //写入日志
            }
            return $pay->success()->send();
        } else {
            $pay = \app\pay\library\Service::checkReturn($pay_type);
            if (!$pay) {
                $this->error('签名错误');
            }
            //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
            $this->success("恭喜你！充值成功!", url("member/index/index"));
        }
        return;
    }

}
