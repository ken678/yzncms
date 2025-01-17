<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace think;

use app\common\middleware\CommonInit;
use think\facade\Cache;
use think\facade\Config;
use think\helper\Str;
use think\Route;
use think\Service;

class AddonsService extends Service
{
    public function register()
    {
        !defined('ADDON_PATH') && define('ADDON_PATH', $this->app->getRootPath() . 'addons' . DS);

        // 如果插件目录不存在则创建
        if (!is_dir(ADDON_PATH)) {
            @mkdir(ADDON_PATH, 0755, true);
        }
        //注册插件事件
        $this->addon_event();
        //插件初始化
        $this->app->event->trigger('addon_init');
    }

    /**
     * 注册插件事件.
     */
    private function addon_event()
    {
        $hooks = $this->app->isDebug() ? [] : Cache::get('hooks', []);
        if (empty($hooks)) {
            $hooks = (array) Config::get('addons.hooks');
            // 初始化钩子
            foreach ($hooks as $key => $values) {
                if (is_string($values)) {
                    $values = explode(',', $values);
                } else {
                    $values = (array) $values;
                }
                $hooks[$key] = array_filter(array_map(function ($v) use ($key) {
                    return [get_addon_class($v), Str::camel($key)];
                }, $values));
            }
            Cache::set('hooks', $hooks);
        }
        $this->app->event->listenEvents($hooks);
        //如果在插件中有定义app_init，则直接执行
        if (isset($hooks['app_init'])) {
            foreach ($hooks['app_init'] as $k => $v) {
                $this->app->invoke($v);
            }
        }
    }

    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            $execute = '\\think\\addons\\Route::execute';
            $route->rule("addons/:addon/[:controller]/[:action]", $execute)->middleware([CommonInit::class]);

            //注册路由
            $routeArr = (array) Config::get('addons.route');
            foreach ($routeArr as $k => $v) {
                if (is_array($v)) {
                    $domain = $v['domain'];
                    $drules = [];
                    foreach ($v['rule'] as $m => $n) {
                        [$addon, $controller, $action] = explode('/', $n);
                        $drules[$m]                    = [
                            'addon'    => $addon, 'controller' => $controller, 'action' => $action,
                            'indomain' => 1,
                        ];
                    }
                    $route->domain($domain, function () use ($drules, $route, $execute) {
                        // 动态注册域名的路由规则
                        foreach ($drules as $k => $rule) {
                            $route->rule($k, $execute)
                                ->middleware([CommonInit::class])
                                ->name($k)
                                ->completeMatch(true)
                                ->append($rule);
                        }
                    });
                } else {
                    if (!$v) {
                        continue;
                    }
                    [$addon, $controller, $action] = explode('/', $v);
                    $route->rule($k, $execute)
                        ->middleware([CommonInit::class])
                        ->name($k)
                        ->completeMatch(true)
                        ->append(['addon' => $addon, 'controller' => $controller, 'action' => $action]);
                }
            }
        });
    }
}
