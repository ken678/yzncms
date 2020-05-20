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
// | 短信息钩子
// +----------------------------------------------------------------------
namespace app\cms\behavior;

use sys\Hooks as _Hooks;

class Hooks extends _Hooks
{

    //或者run方法
    public function userSidenavAfter($content)
    {
        return $this->fetch('userSidenavAfter');
    }

    public function appInit()
    {
        //此函数需要全局调用
        if (is_file(APP_PATH . 'cms' . DS . 'function.php')) {
            include_once APP_PATH . 'cms' . DS . 'function.php';
        }
    }

}
