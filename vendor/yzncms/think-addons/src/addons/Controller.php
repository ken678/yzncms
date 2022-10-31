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
// | 前台插件类
// +----------------------------------------------------------------------
namespace think\addons;

use think\App;
use think\facade\Config;

class Controller extends \think\Controller
{
    // 当前插件操作
    protected $addon      = null;
    protected $controller = null;
    protected $action     = null;

    /**
     * 架构函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(App $app)
    {
        $this->request = app()->request;

        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        // 是否自动转换控制器和操作名
        $convert = Config::get('url_convert');

        $filter = $convert ? 'strtolower' : 'trim';
        // 处理路由参数
        // 处理路由参数
        $param    = $this->request->param();
        $dispatch = $this->request->dispatch()->getParam();
        $var      = array_merge($param, $dispatch);

        $addon      = isset($var['addon']) ? $var['addon'] : '';
        $controller = isset($var['controller']) ? $var['controller'] : '';
        $action     = isset($var['action']) ? $var['action'] : '';

        $this->addon      = $addon ? call_user_func($filter, $addon) : '';
        $this->controller = $controller ? call_user_func($filter, $controller) : 'index';
        $this->action     = $action ? call_user_func($filter, $action) : 'index';
        // 父类的调用必须放在设置模板路径之后
        parent::__construct($app);
    }

    protected function initialize()
    {
        // 检测IP是否允许
        if (function_exists("check_ip_allowed")) {
            check_ip_allowed();
        }
        $site = Config::get("site.");
        $this->assign('site', $site);
    }

    protected function fetch($template = '', $vars = [], $config = [], $renderContent = false)
    {
        $Theme        = empty(Config::get('theme')) ? 'default' : Config::get('theme');
        $viewPath     = TEMPLATE_PATH . $Theme . DIRECTORY_SEPARATOR . $this->addon . DIRECTORY_SEPARATOR;
        $templateFile = $viewPath . trim($template, '/') . '.' . Config::get('template.view_suffix');
        if ('default' !== $Theme && !is_file($templateFile)) {
            $viewPath = TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR . $this->request->module() . DIRECTORY_SEPARATOR;
        }
        $this->view->config('view_path', $viewPath);
        return $this->view->fetch($template, $vars, $config, $renderContent);
    }
}
