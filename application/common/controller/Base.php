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
// | 公共控制模块
// +----------------------------------------------------------------------
namespace app\common\controller;

use think\Controller;

class Base extends Controller
{
    public static $Cache = array(); //全局配置缓存

    //初始化
    protected function initialize()
    {
        $this->initSite();
    }

    //初始化站点配置信息
    protected function initSite()
    {
        $Config = cache("Config"); //获取所有配置名称和值
        self::$Cache['Config'] = $Config; //后端调用
        $this->assign("Config", $Config); //前端调用
    }

    //空操作
    public function _empty()
    {
        $this->error('该页面不存在！');
    }
}
