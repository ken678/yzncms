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
// | 后台欢迎页
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\service\AdminUser;
use app\common\controller\Adminbase;

class Main extends Adminbase
{
    //欢迎首页
    public function index()
    {
        $this->assign('userInfo', AdminUser::getInstance()->getInfo());
        return $this->fetch();
    }

}
