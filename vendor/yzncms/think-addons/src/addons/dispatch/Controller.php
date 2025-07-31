<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2025 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\addons\dispatch;

use think\App;
use think\exception\ClassNotFoundException;
use think\exception\HttpException;
use think\helper\Str;
use think\route\Dispatch;

class Controller extends Dispatch
{
    /**
     * 控制器名
     * @var string
     */
    protected $controller;

    /**
     * 操作名
     * @var string
     */
    private $actionName;

    /**
     * 插件名称
     * @var string
     */
    private $addonName;

    /**
     * 插件目录
     * @var string
     */
    private $addonPath;

    /**
     * 命名空间
     * @var string
     */
    private $namespace;

    public function init(App $app)
    {
        $this->app = $app;
        $this->doRouteAfter();
        $this->parseDispatch();
    }

    protected function parseDispatch()
    {

        //插件名
        $this->addonName = strip_tags($this->request->route('addon'));
        // 插件目录
        $this->addonPath = $this->app->addons->getAddonPath();
        if (!is_dir($this->addonPath)) {
            throw new HttpException(404, 'addons not exists:' . $this->addonName);
        }

        // 命名空间
        $this->namespace = $this->app->addons->getNamespace();

        $action     = $this->request->route('action') ?: $this->rule->config('default_action');
        $controller = $this->request->route('controller') ?: $this->rule->config('default_controller');

        // 获取控制器名和分层（目录）名
        if (str_contains($controller, '.')) {
            $pos        = strrpos($controller, '.');
            $controller = substr($controller, 0, $pos) . '.' . Str::studly(substr($controller, $pos + 1));
        } else {
            $controller = Str::studly($controller);
        }

        $this->actionName = strip_tags($action);
        $this->controller = strip_tags($controller);

        // 设置插件命名空间
        $this->app->setNamespace($this->namespace . '\\' . $this->addonName);

        // 加载应用
        $this->load();

        // 设置当前请求的控制器、操作
        $this->request
            ->setController($this->controller)
            ->setAction($this->actionName);
    }

    public function exec()
    {
        try {
            // 实例化控制器
            $instance = $this->controller($this->controller);
        } catch (ClassNotFoundException $e) {
            throw new HttpException(404, 'controller not exists:' . $e->getClass());
        }

        return $this->responseWithMiddlewarePipeline($instance, $this->actionName);
    }

    /**
     * 实例化访问控制器
     * @access public
     * @param string $name 资源地址
     * @return object
     * @throws ClassNotFoundException
     */
    public function controller(string $name)
    {
        $suffix = $this->rule->config('controller_suffix') ? 'Controller' : '';

        $controllerLayer = $this->rule->config('controller_layer') ?: 'controller';
        $emptyController = $this->rule->config('empty_controller') ?: 'Error';

        $class = $this->app->parseClass($controllerLayer, $name . $suffix);

        if (class_exists($class)) {
            return $this->app->make($class, [], true);
        } elseif ($emptyController && class_exists($emptyClass = $this->app->parseClass($controllerLayer, $emptyController . $suffix))) {
            return $this->app->make($emptyClass, [], true);
        }

        throw new ClassNotFoundException('class not exists:' . $class, $class);
    }

    /**
     * 加载应用文件
     */
    public function load()
    {
        if (is_file($this->addonPath . 'common.php')) {
            include_once $this->addonPath . 'common.php';
        }
        if (is_file($this->addonPath . 'event.php')) {
            $this->app->loadEvent(include $this->addonPath . 'event.php');
        }
        if (is_file($this->addonPath . 'middleware.php')) {
            $this->app->middleware->import(include $this->addonPath . 'middleware.php', 'route');
        }
        if (is_file($this->addonPath . 'provider.php')) {
            $this->app->bind(include $this->addonPath . 'provider.php');
        }
    }
}
