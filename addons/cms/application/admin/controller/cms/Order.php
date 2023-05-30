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
// | 订单管理
// +----------------------------------------------------------------------
namespace app\admin\controller\cms;

use app\admin\model\cms\Order as OrderModel;
use app\common\controller\Adminbase;

class Order extends Adminbase
{
    protected $modelClass = null;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new OrderModel;
    }

}
