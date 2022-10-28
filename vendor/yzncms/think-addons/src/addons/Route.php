<?php

namespace think\addons;

use think\exception\HttpException;
use think\facade\Config;
use think\facade\Hook;
use think\facade\Request;

/**
 * 插件执行默认控制器
 * @package think\addons
 */
class Route
{

    /**
     * 插件执行
     */
    public function execute($addon = null, $controller = null, $action = null)
    {
        $app     = app();
        $request = $app->request;
        // 是否自动转换控制器和操作名
        $convert = Config::get('url_convert');
        $filter  = $convert ? 'strtolower' : 'trim';

        $addon      = $addon ? trim(call_user_func($filter, $addon)) : '';
        $controller = $controller ? trim(call_user_func($filter, $controller)) : 'index';
        $action     = $action ? trim(call_user_func($filter, $action)) : 'index';

        Hook::listen('addon_begin', $request);
        if (!empty($addon) && !empty($controller) && !empty($action)) {
            $info = get_addon_info($addon);
            if (!$info) {
                throw new HttpException(404, '插件' . $addon . '找不到');
            }
            if (!$info['status']) {
                throw new HttpException(500, '插件' . $addon . '已禁用');
            }

            // 设置当前请求的控制器、操作
            $request->setController($controller)->setAction($action);

            // 监听addon_module_init
            Hook::listen('addon_module_init', $request);

            $class = get_addon_class($addon, 'controller', $controller);
            if (!$class) {
                throw new HttpException(404, '插件控制器' . parse_name($controller, 1) . '未找到');
            }

            $instance = new $class(app());

            $vars = [];
            if (is_callable([$instance, $action])) {
                // 执行操作方法
                $call = [$instance, $action];
            } elseif (is_callable([$instance, '_empty'])) {
                // 空操作
                $call = [$instance, '_empty'];
                $vars = [$action];
            } else {
                // 操作不存在
                throw new HttpException(404, '插件控制器方法' . get_class($instance) . '->' . $action . '()未找到');
            }
            Hook::listen('addon_action_begin', $call);

            return call_user_func_array($call, $vars);
        } else {
            abort(500, '插件不能为空');
        }
    }

}
