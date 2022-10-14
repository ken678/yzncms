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
// | 支付模块管理
// +----------------------------------------------------------------------
namespace app\admin\controller\pay;

use app\admin\model\pay\Account as AccountModel;
use app\admin\model\pay\Payment as PaymentModel;
use app\admin\model\pay\Spend as SpendModel;
use app\common\controller\Adminbase;
use think\Db;

class Payment extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->PaymentModel = new PaymentModel;
        $this->modelClass   = new AccountModel;
        $this->SpendModel   = new SpendModel;

    }

    public function modify_deposit()
    {
        if ($this->request->isAjax()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'Account');
            if (true !== $result) {
                return $this->error($result);
            }
            $userinfo = Db::name('member')->where('username', trim($data['username']))->find();
            if ($userinfo) {
                if ($data['pay_unit']) {
                    //增加
                    $this->modelClass->_add($data['pay_type'], floatval($data['unit']), 'recharge', $userinfo['id'], $userinfo['username'], $data['usernote'], $this->auth->username);
                } else {
                    //减少
                    $this->SpendModel->_spend($data['pay_type'], floatval($data['unit']), $userinfo['id'], $userinfo['username'], '后台充值', $data['usernote']);
                }
                $this->success("充值成功！");
            } else {
                $this->error('用户不存在！');
            }

        } else {
            return $this->fetch();
        }
    }

    //支付模块列表
    public function pay_list()
    {
        if ($this->request->isAjax()) {
            $data = $this->PaymentModel->select();
            return json(["code" => 0, "data" => $data]);
        } else {
            return $this->fetch();
        }
    }

    //支付模块配置
    public function edit()
    {
        if ($this->request->isPost()) {
            $data           = [];
            $id             = $this->request->param('id/d', 0);
            $config         = $this->request->param('config/a');
            $data['status'] = $this->request->param('status/d', 0);
            $data['config'] = serialize($config);
            if ($this->PaymentModel->allowField(true)->save($data, ['id' => $id])) {
                cache('Pay_Config', null);
                $this->success("更新成功！", url('index'));
            } else {
                $this->success("更新失败！");
            }
        } else {
            $id   = $this->request->param('id/d', 0);
            $info = $this->PaymentModel->where('id', $id)->find();
            $this->assign('info', $info);
            return $this->fetch($info['name']);
        }
    }
}
