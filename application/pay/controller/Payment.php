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
namespace app\pay\controller;

use app\common\controller\Adminbase;
use app\pay\model\Account as Account_Model;
use app\pay\model\Payment as Payment_Model;

class Payment extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Payment = new Payment_Model;
        $this->Account_Model = new Account_Model;

    }

    //入账列表
    public function pay_list()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 1);

            $total = $this->Account_Model->order('id', 'desc')->count();
            $data = $this->Account_Model->page($page, $limit)->select();
            return json(["code" => 0, "count" => $total, "data" => $data]);
        } else {
            return $this->fetch();
        }
    }

    //支付模块列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->Payment->select();
            return json(["code" => 0, "data" => $data]);
        } else {
            return $this->fetch();
        }
    }

    //模块配置
    public function edit()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id/d', 0);
            $config = $this->request->param('config/a');
            $data['config'] = serialize($config);
            if ($this->Payment->allowField(true)->save($data, ['id' => $id])) {
                cache('Pay_Config', null);
                $this->success("更新成功！");
            } else {
                $this->success("更新失败！");
            }
        } else {
            $id = $this->request->param('id/d', 0);
            $info = $this->Payment->where('id', $id)->find();
            $this->assign('info', $info);
            return $this->fetch($info['name']);
        }

    }

}
