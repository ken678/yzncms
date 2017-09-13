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

/**
 * 前台总控制器
 */
class Homebase extends Base
{
    public function __construct()
    {
        $Theme = empty(self::$Cache["Config"]['theme']) ? 'default' : self::$Cache["Config"]['theme'];
        $config['template']['view_path'] = TEMPLATE_PATH . $Theme . '/content/'; //模板主题
        config($config); //添加配置
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
