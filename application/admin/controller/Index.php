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

use app\common\controller\Adminbase;
use think\facade\Cache;

class Index extends Adminbase
{
    protected $noNeedLogin = [
        'admin/index/login',
        'admin/index/logout',
    ];
    protected $noNeedRight = [
        'admin/index/index',
        'admin/index/cache',
    ];

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
        $this->assign("SUBMENU_CONFIG", json_encode(model("admin/Menu")->getMenuList()));
        return $this->fetch();
    }

    //登录判断
    public function login()
    {
        $url = $this->request->get('url', 'index/index');
        if ($this->auth->isLogin()) {
            $this->redirect('admin/index/index');
        }
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
            if (true !== $result) {
                $this->error($result, $url, ['token' => $this->request->token()]);
            }
            if ($this->auth->login($data['username'], $data['password'], $keeplogin ? 86400 : 0)) {
                $this->success('恭喜您，登陆成功', url('admin/Index/index'));
            } else {
                $msg = $this->auth->getError();
                $msg = $msg ? $msg : '用户名或者密码错误!';
                $this->error($msg, $url, ['token' => $this->request->token()]);
            }
        } else {
            if ($this->auth->autologin()) {
                $this->redirect('admin/index/index');
            }
            return $this->fetch();
        }
    }

    //手动退出登录
    public function logout()
    {
        if ($this->auth->logout()) {
            //手动登出时，清空forward
            //cookie("forward", NULL);
            $this->success('注销成功！', url("admin/index/login"));
        }
    }

    //缓存更新
    public function cache()
    {
        try {
            $type = $this->request->request("type");
            switch ($type) {
                case 'data' || 'all':
                    \util\File::del_dir(ROOT_PATH . 'runtime' . DIRECTORY_SEPARATOR . 'cache');
                    Cache::clear();
                    if ($type == 'data') {
                        break;
                    }

                case 'template' || 'all':
                    \util\File::del_dir(ROOT_PATH . 'runtime' . DIRECTORY_SEPARATOR . 'temp');
                    if ($type == 'template') {
                        break;
                    }
                case 'addons':
                    // 插件缓存
                    \sys\AddonService::refresh();
                    if ($type == 'addons') {
                        break;
                    }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('清理缓存');
    }
}
