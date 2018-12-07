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
    public function _empty(Request $request)
    {
        $controller = \think\Loader::parseName($request->controller());
        $action = $request->action();
        $object = \think\Container::get("\\addons\\" . $controller . "\\controller\\Admin");
        return $object->$action();
    }

}
