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
namespace app\common\controller;
use think\Controller;

/**
 * 公用控制器 前后端继承 共用方法 写在此控制器中
 */
class Base extends Controller
{
    public static $Cache = array();//全局配置缓存

	//初始化
    protected function _initialize()
    {
        define('WEB_PATH',  request()->root(). '/');
    	$this->initSite();
    }


    //初始化站点配置信息
    protected function initSite()
    {
    	$Config = cache("Config");//获取所有配置名称和值
        self::$Cache['Config'] = $Config;//后端调用
    	$this->assign("Config", $Config);//前端调用

    }


    //空操作
    public function _empty()
    {
        $this->error('该页面不存在！');
    }
}