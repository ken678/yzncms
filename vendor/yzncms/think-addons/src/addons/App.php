<?php

namespace think\addons;

use think\App as BaseApp;
use think\facade\Cache;
use think\facade\Config;
use think\helper\Str;

class App
{
    /**
     * @var \think\App
     */
    protected $app;

    /**
     * 插件目录
     * @var string
     */
    public $addonPath;

    public function __construct(BaseApp $app)
    {
        $this->app       = $app;
        $this->addonPath = $this->getRootPath();
    }

    /**
     * 自动加载
     * @return array|mixed
     * @throws \ReflectionException
     */
    public function autoload()
    {
        $this->loadEvents();
    }

    /**
     * 加载事件服务
     */
    public function loadEvents()
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

    /**
     * 获取当前应用名称
     * @return string
     */
    public function getAddonName(): string
    {
        return $this->app->request->route('addon') ?: '';
    }

    /**
     * 当前插件访问路径
     * @return string
     */
    public function getAddonPath(): string
    {
        $rootPath  = $this->getRootPath();
        $addonName = $this->getAddonName();
        return $rootPath . $addonName . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        $addonsPath = $this->app->getRootPath() . $this->getNamespace() . DIRECTORY_SEPARATOR;
        if (!is_dir($addonsPath)) {
            @mkdir($addonsPath, 0755, true);
        }
        return $addonsPath;
    }

    /**
     * 获取插件 namespace
     * @return string
     */
    public function getNamespace(): string
    {
        return Config::get('addons.addon_namespace') ?: 'addons';
    }

}
