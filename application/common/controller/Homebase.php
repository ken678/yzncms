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

use Config;

/**
 * 前台总控制器
 */
class Homebase extends Base
{
    //加载template动态配置
    public function __construct()
    {
// 获取入口目录
        $base_file = request()->baseFile();
        $base_dir = substr($base_file, 0, strripos($base_file, '/') + 1);
        $config['view_replace_str'] = [
            '__IMG_PATH__' => $base_dir . 'static/home/image',
            '__CSS_PATH__' => $base_dir . 'static/home/css',
            '__JS_PATH__' => $base_dir . 'static/home/js',
            '__HOME_PATH__' => $base_dir . 'static/home',
        ];
        $config['template'] = Config::get('template');
        $Theme = empty(self::$Cache["Config"]['theme']) ? 'default' : self::$Cache["Config"]['theme'];
        $config['template']['view_path'] = TEMPLATE_PATH . $Theme . '/content/'; //模板主题
        Config::set($config); //添加配置
        parent::__construct();
    }

}
