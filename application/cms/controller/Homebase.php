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
// | 前台控制模块
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\common\controller\Base;
use think\facade\Config;

class Homebase extends Base
{
    protected function fetch($template = '', $vars = [], $config = [])
    {
        $Theme = empty(Config::get('theme')) ? 'default' : Config::get('theme');
        $this->view->config('view_path', TEMPLATE_PATH . $Theme . DIRECTORY_SEPARATOR . 'cms' . DIRECTORY_SEPARATOR);
        return $this->view->fetch($template, $vars, $config);
    }
}
