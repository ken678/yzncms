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
// | 后台插件类
// +----------------------------------------------------------------------
namespace app\addons\util;

use app\common\controller\Adminbase;

class Adminaddon extends Adminbase
{
    //插件标识
    public $addonName = null;
    //插件路径
    protected $addonPath = null;
    protected function initialize()
    {
        parent::initialize();
        $this->addonName = $this->request->controller();
        $this->addonPath = model('addons/addons')->getAddonsPath() . $this->addonName . '/';
    }

}
