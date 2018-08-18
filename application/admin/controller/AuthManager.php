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
namespace app\admin\Controller;

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 权限管理控制器
 */
class AuthManager extends Adminbase
{

    /**
     * 权限管理首页
     */
    public function index()
    {
        $list = Db::name('AuthGroup')->where(['module' => 'admin'])->order(['id' => 'DESC'])->select();
        $list = int_to_string($list);
        $this->assign('_list', $list);
        return $this->fetch();
    }

}
