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
// | 前台控制模块
// +----------------------------------------------------------------------
namespace app\common\controller;

use think\facade\Config;
use think\facade\Hook;

class Homebase extends Base
{
    protected $modulename     = null;
    protected $controllername = null;
    protected $actionname     = null;

    //初始化
    protected function initialize()
    {
        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        parent::initialize();
        $this->modulename     = $this->request->module();
        $this->controllername = parse_name($this->request->controller());
        $this->actionname     = strtolower($this->request->action());

        $site   = Config::get("site.");
        $config = [
            'modulename'     => $this->modulename,
            'controllername' => $this->controllername,
            'actionname'     => $this->actionname,
        ];
        //监听插件传入的变量
        $site = array_merge($site, $config, Hook::listen("config_init")[0] ?? []);
        $this->assign('site', $site);
    }

    /**
     * 渲染配置信息
     * @param mixed $name  键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->site = array_merge($this->view->site ? $this->view->site : [], is_array($name) ? $name : [$name => $value]);
    }

    protected function fetch($template = '', $vars = [], $config = [], $renderContent = false)
    {
        $Theme    = empty(Config::get('site.theme')) ? 'default' : Config::get('site.theme');
        $viewPath = TEMPLATE_PATH . $Theme . DS . $this->modulename . DS;

        $tempPath     = TEMPLATE_PATH . $Theme . DS . $this->modulename . DS . ($this->modulename === 'index' ? $this->controllername : '') . DS;
        $templateFile = $tempPath . trim($template, '/') . '.' . Config::get('template.view_suffix');
        if ('default' !== $Theme && !is_file($templateFile)) {
            $viewPath = TEMPLATE_PATH . 'default' . DS . $this->modulename . DS;
        }

        $this->view->config('view_path', $viewPath);
        return $this->view->fetch($template, $vars, $config, $renderContent);
    }
}
