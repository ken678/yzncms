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
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Db;

/**
 * 管理员管理
 */
class Manager extends Adminbase
{

    /**
     * 管理员管理列表
     */
    public function index()
    {
        $User = Db::name("admin")->order(array('userid' => 'DESC'))->select();
        $this->assign("Userlist", $User);
        return $this->fetch();
    }

}
