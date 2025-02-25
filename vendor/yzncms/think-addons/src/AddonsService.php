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
use think\addons\App;
use think\facade\Config;
use think\Route;
use think\Service;

class AddonsService extends Service
{
    public function register()
    {
        $this->app->bind(['addons' => App::class]);
        //TODO 后续删除
        !defined('ADDON_PATH') && define('ADDON_PATH', $this->app->getRootPath() . 'addons' . DIRECTORY_SEPARATOR);
        // 自动加载
        $this->app->addons->autoload();
        //插件初始化
        $this->app->event->trigger('addon_init');
    }

    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            $dispatch = \think\addons\dispatch\Controller::class;
            $route->rule("addons/:addon/[:controller]/[:action]", $dispatch)->middleware([CommonInit::class]);

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
                    $route->domain($domain, function () use ($drules, $route, $dispatch) {
                        // 动态注册域名的路由规则
                        foreach ($drules as $k => $rule) {
                            $route->rule($k, $dispatch)
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
                    $route->rule($k, $dispatch)
                        ->middleware([CommonInit::class])
                        ->name($k)
                        ->completeMatch(true)
                        ->append(['addon' => $addon, 'controller' => $controller, 'action' => $action]);
                }
            }
        });
    }
}
