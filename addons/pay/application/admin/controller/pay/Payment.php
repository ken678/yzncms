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
use app\admin\model\pay\Spend as SpendModel;
use app\common\controller\Adminbase;
use think\Db;

class Payment extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AccountModel;
        $this->SpendModel = new SpendModel;

    }

    //在线充值
    public function add()
    {
        if ($this->request->isAjax()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'app\admin\validate\pay\Account');
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
}
