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

use app\admin\model\Module as ModuleModel;
use think\Controller;

class Module extends Controller
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $ModuleModel = new ModuleModel();
        $result = $ModuleModel->getAll();
    }

    //本地模块
    public function index()
    {
        return $this->fetch();
    }

}
