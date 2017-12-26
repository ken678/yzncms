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
use think\Db;

/**
 * 支付管理
 * @author 御宅男  <530765310@qq.com>
 */
class PayConfig extends Adminbase
{
    /**
     * [支付配置列表]
     * @author 御宅男  <530765310@qq.com>
     */
    public function index()
    {
        $res = Db::name('PayPayment')->select();
        $this->assign('_list', int_to_string($res));
        return $this->fetch();

    }
}
