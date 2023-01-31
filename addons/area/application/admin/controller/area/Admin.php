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
// | 地区管理
// +----------------------------------------------------------------------
namespace app\admin\controller\area;

use app\admin\model\area\Area as AreaModel;
use app\common\controller\Adminbase;

class Admin extends Adminbase
{
    protected $modelValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AreaModel;
    }

}
