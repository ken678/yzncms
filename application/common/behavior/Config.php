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
// | 初始化配置
// +----------------------------------------------------------------------
namespace app\common\behavior;

use think\facade\Config as systemConfig;

/**
 * 初始化配置信息行为
 * 将系统配置信息合并到本地配置
 * @package app\common\behavior
 */
class Config
{

    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params  行为参数
     * @return void
     */
    public function run($params)
    {
        if (systemConfig::get('app_debug')) {
            // 如果是开发模式那么将异常模板修改成官方的
            systemConfig::set('exception_tmpl', \think\facade\Env::get('think_path') . 'tpl/think_exception.tpl');
        }
        // 读取系统配置
        $system_config = cache("Config");

        if (empty($system_config)) {
            exit("配置读取错误！！！！");
        }

        // 设置配置信息
        if (!empty($system_config)) {
            foreach ($system_config as $key => $value) {
                systemConfig::set($key, $value);
            }
        }
    }

}
