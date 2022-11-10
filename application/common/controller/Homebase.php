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

use app\common\controller\Base;
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

    protected function fetch($template = '', $vars = [], $config = [], $renderContent = false)
    {
        $Theme        = empty(Config::get('site.theme')) ? 'default' : Config::get('site.theme');
        $viewPath     = TEMPLATE_PATH . $Theme . DIRECTORY_SEPARATOR . $this->request->module() . DIRECTORY_SEPARATOR;
        $templateFile = $viewPath . trim($template, '/') . '.' . Config::get('template.view_suffix');
        if ('default' !== $Theme && !is_file($templateFile)) {
            $viewPath = TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . $this->request->module() . DIRECTORY_SEPARATOR;
        }
        $this->view->config('view_path', $viewPath);
        return $this->view->fetch($template, $vars, $config, $renderContent);
    }
}
