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
namespace app\admin\controller\pay;

use app\admin\model\pay\Spend as SpendModel;
use app\common\controller\Adminbase;

class Spend extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new SpendModel;

    }
}
