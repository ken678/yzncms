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

use app\common\controller\BaseController;
use app\common\library\Auth;
use think\App;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Lang;
use think\facade\View;

class Controller extends BaseController
{
    // 当前插件操作
    protected $addon      = null;
    protected $controller = null;
    protected $action     = null;

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = ['*'];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = ['*'];

    /**
     * 权限Auth
     * @var Auth
     */
    protected $auth = null;

    /**
     * 架构函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(App $app)
    {
        $this->request = $app->request;

        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        // 是否自动转换控制器和操作名
        $convert = Config::get('url_convert');
        $filter  = $convert ? 'strtolower' : 'trim';
        // 处理路由参数
        $var = $this->request->param();

        $addon      = isset($var['addon']) ? $var['addon'] : '';
        $controller = isset($var['controller']) ? $var['controller'] : '';
        $action     = isset($var['action']) ? $var['action'] : '';

        $this->addon      = $addon ? call_user_func($filter, $addon) : '';
        $this->controller = $controller ? call_user_func($filter, $controller) : 'index';
        $this->action     = $action ? call_user_func($filter, $action) : 'index';

        Config::set(['view_path' => ADDON_PATH . $this->addon . DS . 'view' . DS], 'view');

        // 父类的调用必须放在设置模板路径之后
        parent::__construct($app);
    }

    protected function initialize()
    {
        // 检测IP是否允许
        if (function_exists("check_ip_allowed")) {
            check_ip_allowed();
        }

        // 渲染配置到视图中
        $config = get_addon_config($this->addon);
        $this->assign("config", $config);

        $lang = $this->app->lang->getLangSet();
        $lang = preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $lang) ? $lang : 'zh-cn';

        // 加载系统语言包
        Lang::load([
            ADDON_PATH . $this->addon . DS . 'lang' . DS . $lang . '.php',
        ]);

        // 设置替换字符串
        $cdnurl = Config::get('site.cdnurl');

        $this->auth = Auth::instance();

        $tpl_replace_string              = Config::get('view.tpl_replace_string');
        $tpl_replace_string['__ADDON__'] = $cdnurl . "/assets/addons/" . $this->addon;
        Config::set(['tpl_replace_string' => $tpl_replace_string], 'view');

        // token
        $token = $this->request->server('HTTP_TOKEN', (string) $this->request->request('token', Cookie::get('token')));

        $path = 'addons/' . $this->addon . '/' . str_replace('.', '/', $this->controller) . '/' . $this->action;
        // 设置当前请求的URI
        $this->auth->setRequestUri($path);
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin)) {
            //初始化
            $this->auth->init($token);
            //检测是否登录
            if (!$this->auth->isLogin()) {
                $this->error('请登录后操作', 'index/user/login');
            }
            // 判断是否需要验证权限
            if (!$this->auth->match($this->noNeedRight)) {
                // 判断控制器和方法判断是否有对应权限
                /*if (!$this->auth->check($path)) {
            $this->error('你没有权限访问');
            }*/
            }
            //判断一下vip是否过期
            if ($this->auth->vip) {
                if ($this->auth->overduedate < time()) {
                    $this->auth->logout();
                    $this->error('VIP已过期，请重新登录', 'index/user/login');
                }
            }
        } else {
            // 如果有传递token才验证是否登录状态
            if ($token) {
                $this->auth->init($token);
            }
        }

        $this->assign('user', $this->auth->getUser());

        $site = Config::get("site");
        $this->assign('site', $site);
    }

    /**
     * 加载模板输出.
     *
     * @param string $template 模板文件名
     * @param array  $vars     模板输出变量
     * @param array  $replace  模板替换
     * @param array  $config   模板参数
     *
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $controller = parse_name($this->controller);
        if ('think' == strtolower(Config::get('view.type')) && $controller && 0 !== strpos($template, '/')) {
            $depr     = Config::get('view.view_depr');
            $template = str_replace(['/', ':'], $depr, $template);
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                $template = str_replace('.', DS, $controller) . $depr . $this->action;
            } elseif (false === strpos($template, $depr)) {
                $template = str_replace('.', DS, $controller) . $depr . $template;
            }
        }

        return View::fetch($template, $vars);
    }

    /**
     * 刷新Token
     */
    protected function token()
    {
        $check = $this->request->checkToken('__token__');
        // 刷新token
        $token = $this->request->buildToken();
        if ($this->request->isAjax()) {
            header('__token__: ' . $token);
        }
        if (false === $check) {
            $this->error('令牌错误！', '', ['__token__' => $token]);
        }
    }
}
