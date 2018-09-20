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
// | 后台控制模块
// +----------------------------------------------------------------------
namespace app\common\controller;

use app\admin\model\AdminUser as AdminUser_model;

class Adminbase extends Base
{
    public $_userinfo; //当前登录账号信息
    //初始化
    protected function initialize()
    {
        parent::initialize();
        //验证登录
        //过滤不需要登陆的行为
        $allowUrl = ['admin/index/login',
            'admin/index/logout',
        ];
        $rule = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
        if (in_array($rule, $allowUrl)) {

        } else {
            $this->AdminUser_model = new AdminUser_model;
            if (defined('UID')) {
                return;
            }
            define('UID', (int) $this->AdminUser_model->isLogin());
            if (false == $this->competence()) {
                //跳转到登录界面
                $this->error('请先登陆', url('admin/index/login'));
            } else {
                //是否超级管理员
                if (!$this->AdminUser_model->isAdministrator()) {
                    //检测访问权限
                    if (!$this->checkRule($rule, [1, 2])) {
                        $this->error('未授权访问!');
                    }
                }

            }
        }

    }

    //验证登录
    private function competence()
    {
        //检查是否登录
        $uid = (int) $this->AdminUser_model->isLogin();
        if (empty($uid)) {
            return false;
        }
        //获取当前登录用户信息
        $userInfo = $this->AdminUser_model->getUserInfo($uid);
        if (empty($userInfo)) {
            $this->AdminUser_model->logout();
            return false;
        }
        $this->_userinfo = $userInfo;
        //是否锁定
        /*if (!$userInfo['status']) {
        User::getInstance()->logout();
        $this->error('您的帐号已经被锁定！', url('admin/index/login'));
        return false;
        }*/
        return $userInfo;

    }

    /**
     * 操作错误跳转的快捷方法
     */
    final public function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        model('admin/Adminlog')->record($msg, 0, UID, $this->_userinfo['username']);
        parent::error($msg, $url, $data, $wait, $header);
    }

    /**
     * 操作成功跳转的快捷方法
     */
    final public function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        model('admin/Adminlog')->record($msg, 1, UID, $this->_userinfo['username']);
        parent::success($msg, $url, $data, $wait, $header);
    }

    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type = AuthRule::RULE_URL, $mode = 'url')
    {
        static $Auth = null;
        if (!$Auth) {
            $Auth = new \libs\Auth();
        }
        if (!$Auth->check($rule, UID, $type, $mode)) {
            return false;
        }
        return true;
    }

}
