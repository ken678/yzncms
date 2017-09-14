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

use think\Config;

/**
 * 前台总控制器
 */
class Homebase extends Base
{
    //加载template动态配置
    public function __construct()
    {
        $config['template'] = Config::get('template');
        $Theme = empty(self::$Cache["Config"]['theme']) ? 'default' : self::$Cache["Config"]['theme'];
        $config['template']['view_path'] = TEMPLATE_PATH . $Theme . '/content/'; //模板主题
        Config::set($config); //添加配置
        parent::__construct();
    }

    /**
     * 前台初始化
     */
    protected function _initialize()
    {
        parent::_initialize();

    }

}
