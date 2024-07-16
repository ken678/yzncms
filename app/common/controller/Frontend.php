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
// | Original reference: https://gitee.com/karson/fastadmin
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 前台控制器基类
// +----------------------------------------------------------------------
namespace app\common\controller;

use app\common\library\Auth;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Event;

class Frontend extends BaseController
{
    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];

    /**
     * 权限Auth
     * @var Auth
     */
    protected $auth = null;

    public function initialize()
    {
        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');

        $modulename     = app()->http->getName();
        $controllername = parse_name($this->request->controller(true));
        $actionname     = strtolower($this->request->action());

        $this->auth = Auth::instance();

        // token
        $token = $this->request->server('HTTP_TOKEN', (string) $this->request->request('token', Cookie::get('token')));

        $path = str_replace('.', '/', $controllername) . '/' . $actionname;
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
                    $this->error('VIP已过期，请重新登录', 'user/login');
                }
            }
        } else {
            // 如果有传递token才验证是否登录状态
            if ($token) {
                $this->auth->init($token);
            }
        }

        $this->assign('user', $this->auth->getUser());

        $site   = Config::get("site");
        $upload = \app\common\model\Config::upload();
        // 配置信息
        $config = [
            'site'           => array_intersect_key($site, array_flip(['name', 'cdnurl', 'version'])),
            'upload'         => $upload,
            'modulename'     => $modulename,
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'jsname'         => 'frontend/' . str_replace('.', '/', $controllername),
            'moduleurl'      => rtrim(url("/{$modulename}", [], false), '/'),
        ];

        $config = array_merge($config, Config::get("view.tpl_replace_string"), ...Event::trigger("config_init"));

        // 加载当前控制器语言包
        $this->assign('site', $site);
        $this->assign('config', $config);
    }

    /**
     * 渲染配置信息
     * @param mixed $name  键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
    }

    //刷新Token
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
