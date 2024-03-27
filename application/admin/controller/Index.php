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
// | 后台首页
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Adminlog;
use app\common\controller\Adminbase;
use think\addons\Service;
use think\facade\Cache;
use think\facade\Hook;
use util\File;

class Index extends Adminbase
{
    protected $noNeedLogin = ['login'];
    protected $noNeedRight = ['index', 'cache', 'logout'];

    //初始化
    protected function initialize()
    {
        parent::initialize();
        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');
    }

    //后台首页
    public function index()
    {
        if ($this->request->isPost()) {
            return json($this->auth->getSidebar());
        }
        return $this->fetch();
    }

    //登录判断
    public function login()
    {
        $url = $this->request->get('url', 'admin/index/index', 'url_clean');
        if ($this->auth->isLogin()) {
            $this->success("你已经登录，无需重复登录", $url);
        }
        //保持会话有效时长，单位:小时
        $keeyloginhours = 24;
        if ($this->request->isPost()) {
            $data      = $this->request->post();
            $keeplogin = $this->request->post('keeplogin');
            $rule      = [
                'verify|验证码'   => 'require|captcha',
                'username|用户名' => 'require|alphaDash|length:3,20',
                'password|密码'  => 'require|length:3,20',
                '__token__'    => 'require|token',
            ];
            $result = $this->validate($data, $rule);
            Adminlog::setTitle('登录');
            if (true !== $result) {
                $this->error($result, $url, ['token' => $this->request->token()]);
            }
            if ($this->auth->login($data['username'], $data['password'], $keeplogin ? $keeyloginhours * 3600 : 0)) {
                Hook::listen("admin_login_after", $this->request);
                $this->success('恭喜您，登陆成功', $url);
            } else {
                $msg = $this->auth->getError();
                $msg = $msg ?: '用户名或者密码错误!';
                $this->error($msg, $url, ['token' => $this->request->token()]);
            }
        }
        if ($this->auth->autologin()) {
            $this->redirect($url);
        }
        Hook::listen("admin_login_init", $this->request);
        $this->assign('keeyloginhours', $keeyloginhours);
        return $this->fetch();
    }

    //手动退出登录
    public function logout()
    {
        if ($this->auth->logout()) {
            Hook::listen("admin_logout_after", $this->request);
            $this->success('注销成功！', url("admin/index/login"));
        }
    }

    //缓存更新
    public function cache()
    {
        try {
            $type = $this->request->request("type");
            switch ($type) {
                case 'all':
                case 'data':
                    File::del_dir(ROOT_PATH . 'runtime' . DS . 'cache');
                    Cache::clear();
                    if ($type == 'data') {
                        break;
                    }

                case 'template':
                    File::del_dir(ROOT_PATH . 'runtime' . DS . 'temp');
                    if ($type == 'template') {
                        break;
                    }
                case 'addons':
                    // 插件缓存
                    Service::refresh();
                    if ($type == 'addons') {
                        break;
                    }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        Hook::listen("wipecache_after");
        $this->success('清理缓存');
    }
}
