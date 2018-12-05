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
// | 空控制器
// +----------------------------------------------------------------------
namespace app\addons\controller;

use think\Request;

class Error
{
    public function index(Request $request)
    {
        $controller = $request->controller();
        $action = $request->action();
        $object = app("\\addons\\" . $controller . "\\controller\\Admin");
        $object = $object->$action();
    }

}
