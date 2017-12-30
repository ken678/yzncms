<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\pay\controller;

use app\common\controller\Adminbase;
use app\pay\model\Pay as PayModel;
use app\user\api\UserApi;

/**
 * 支付管理
 * @author 御宅男  <530765310@qq.com>
 */
class Index extends Adminbase
{
    public function _initialize()
    {
        parent::_initialize();
        $this->PayModel = new PayModel;
    }

    /**
     * [支付列表]
     * @author 御宅男  <530765310@qq.com>
     */
    public function pay_list()
    {
        return $this->fetch();
    }

    /**
     * [后台充值]
     * @author 御宅男  <530765310@qq.com>
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $result = $this->validate($data, 'pay');
            if (true !== $result) {
                $this->error($result);
            }
            $UserApi = new UserApi;
            $userinfo = $UserApi->info($data['username'], true);
            if (0 < $userinfo) {
                $func = $data['pay_type'] == '1' ? 'amount' : 'point';
                $this->PayModel->$func($data['unit'], $userinfo[0], $userinfo[1], create_sn(), 'offline', '后台充值', $this->_userinfo['username'], $status = 'succ', $data['usernote']);
                //$this->success('操作成功');
            } else {
                $this->error('会员不存在');
            }
        } else {
            return $this->fetch();
        }

    }

}
