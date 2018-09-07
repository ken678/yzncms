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
// | 后台登录页
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\AdminUser as AdminUser_model;
use think\Controller;

class Login extends Controller
{
    //登录判断
    public function index()
    {
        $AdminUser_model = new AdminUser_model;
        if ($AdminUser_model->isLogin()) {
            $this->redirect('admin/index/index');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证数据
            $result = $this->validate($data, 'AdminUser.checklogin');
            if (true !== $result) {
                $this->error($result);
            }
            //验证码
            /*if (!captcha_check($data['captcha'])) {
            $this->error('验证码输入错误！');
            return false;
            }*/
            if ($AdminUser_model->login($data['username'], $data['password'])) {
                $this->success('恭喜您，登陆成功', url('admin/Index/index'));
            } else {
                $this->error($AdminUser_model->getError(), url('admin/Login/login'));
            }

        } else {
            return $this->fetch();
        }

    }

    //手动退出登录
    public function logout()
    {
        $AdminUser_model = new AdminUser_model;
        if ($AdminUser_model->logout()) {
            //手动登出时，清空forward
            //cookie("forward", NULL);
            $this->success('注销成功！', url("admin/login/index"));
        }
    }

}
