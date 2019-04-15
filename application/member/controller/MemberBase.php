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
// | 前台会员管理
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\common\controller\Base;
use app\member\service\User;
use think\facade\Config;

class MemberBase extends Base
{
    //用户信息
    protected $userinfo = array();
    //初始化
    protected function initialize()
    {
        parent::initialize();
        //登陆检测
        $this->check_member();
    }

    /**
     * 检测用户是否已经登陆
     */
    final public function check_member()
    {
        $this->userinfo = User::instance()->id;
        if (substr($this->request->module(), 0, 7) == 'public_') {
            //所有以public_开头的方法都无需检测是否登陆
            return true;
        }
        //该类方法不需要验证是否登陆
        if ($this->request->module() == 'member' && $this->request->controller() == 'Index' && in_array($this->request->action(), array('login', 'register', 'logout', 'lostpassword'))) {
            return true;
        };
        if ($this->userid) {
            //  获取用户信息
            /*$this->userinfo = Db::name('Member')->find($this->userid);
        //  判断用户是否被锁定
        if ($this->userinfo['status'] !== 1) {
        $this->error("您的帐号已经被锁定！", url('/'));
        }
        return true;*/
        } else {
            // 还没登录 跳转到登录页面
            $this->redirect('member/index/login');
        }
    }

    protected function fetch($template = '', $vars = [], $config = [])
    {
        $Theme = empty(Config::get('theme')) ? 'default' : Config::get('theme');
        $this->view->config('view_path', TEMPLATE_PATH . $Theme . DIRECTORY_SEPARATOR . 'member' . DIRECTORY_SEPARATOR);
        return $this->view->fetch($template, $vars, $config);
    }

}
