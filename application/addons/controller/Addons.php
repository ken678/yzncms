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
// | 插件管理
// +----------------------------------------------------------------------
namespace app\addons\controller;

use app\addons\model\Addons as Addons_model;
use app\common\controller\Adminbase;
use think\Db;

class Addons extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->addons = new Addons_model;
    }

    //显示插件列表
    public function index()
    {
        $addons = $this->addons->getAddonList();
        var_dump($addons);

    }

    public function hooks()
    {
        var_dump(222);
    }

}
