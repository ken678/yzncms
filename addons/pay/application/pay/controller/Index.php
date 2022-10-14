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
use app\pay\model\Spend as Spend_Model;

class Index extends MemberBase
{
    protected $noNeedLogin = ['epay'];
    protected $Payment_Model;
    protected $Account_Model;
    protected $Spend_Model;
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Payment_Model = new Payment_Model;
        $this->Account_Model = new Account_Model;
        $this->Spend_Model   = new Spend_Model;
    }

    //充值
    public function pay()
    {
        if ($this->request->isPost()) {
            $money    = $this->request->request('money/f');
            $pay_type = $this->request->request('pay_type/s');
            if (!$money || $money < 0) {
                $this->error("支付金额必须大于0");
            }
            if (!$pay_type || !in_array($pay_type, ['alipay', 'wechat'])) {
                $this->error("支付类型不能为空");
            }
            //验证码
            if (!captcha_check($this->request->post('verify/s', ''))) {
                $this->error('验证码输入错误！');
                return false;
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

    //入账记录
    public function pay_list()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page  = $this->request->param('page/d', 1);

            $_list  = $this->Account_Model->where('uid', $this->auth->id)->page($page, $limit)->order('id DESC')->select();
            $total  = $this->Account_Model->where('uid', $this->auth->id)->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);

        } else {
            return $this->fetch('/pay_list');
        }
    }

    //入账记录
    public function spend_list()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page  = $this->request->param('page/d', 1);

            $_list  = $this->Spend_Model->where('uid', $this->auth->id)->page($page, $limit)->order('id DESC')->select();
            $total  = $this->Spend_Model->where('uid', $this->auth->id)->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);

        } else {
            return $this->fetch('/spend_list');
        }
    }

    //积分兑换
    public function change_credit()
    {
        if ($this->request->isPost()) {
            $money = $this->request->param('money/d', 0);
            if (!$money || $money < 0) {
                $this->error("兑换金额必须大于0");
            }
            $point = $money * $this->memberConfig['rmb_point_rate'];

            try {
                //扣除金钱
                $this->Spend_Model->_spend(1, floatval($money), $this->auth->id, $this->auth->username, '积分兑换');
                //增加积分
                \think\Db::name('member')->where(['id' => $this->auth->id, 'username' => $this->auth->username])->setInc('point', $point);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success("兑换成功！");
        } else {
            return $this->fetch('/change_credit');
        }
    }

    //企业支付通知和回调
    public function epay()
    {
        $type     = $this->request->param('type');
        $pay_type = $this->request->param('pay_type');
        if ($type == 'notify') {
            $pay = \app\pay\library\Service::checkNotify($pay_type);
            if (!$pay) {
                echo '签名错误';
                return;
            }
            try {
                $data      = $pay->verify();
                $payamount = $pay_type == 'alipay' ? $data['total_amount'] : $data['total_fee'] / 100;
                $this->Account_Model->settle($data['out_trade_no'], $payamount);
            } catch (Exception $e) {
                //写入日志
                // $e->getMessage();
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
