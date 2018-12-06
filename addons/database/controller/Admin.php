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
// | 数据库管理
// +----------------------------------------------------------------------
namespace addons\database\Controller;

use app\addons\util\Adminaddon;

class Admin extends Adminaddon
{
    protected function initialize()
    {
        parent::initialize();
    }

    //数据库备份
    public function index()
    {
        return $this->fetch('../addons/database/view/index.html');
    }

}
