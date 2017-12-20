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
namespace app\announce\controller;

use app\common\controller\Adminbase;

/**
 * 系统公告管理
 * @author 御宅男  <530765310@qq.com>
 */
class Announce extends Adminbase
{
    /**
     * [系统公告列表]
     * @author 御宅男  <530765310@qq.com>
     */
    public function index()
    {
        return $this->fetch();

    }

}
