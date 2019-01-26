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
// | CMS设置
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\common\controller\Adminbase;

class Setting extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
    }

    //cms设置
    public function index()
    {
        return $this->fetch();

    }

}
