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
// | vip管理
// +----------------------------------------------------------------------
namespace app\admin\controller\user;

use app\admin\model\user\UserVip;
use app\common\controller\Backend;

class Vip extends Backend
{
    protected $modelValidate = true;
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new UserVip;
    }

}
