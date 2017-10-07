<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;

class Index extends Adminbase
{

    /**
     * 后台首页
     */
    public function index()
    {
        $this->assign('__MENU__', model("common/Menu")->getMenuList());
        $this->assign('role_name', model('admin/AuthGroup')->getRoleIdName(is_login()));

        /*管理员收藏栏*/
        $this->assign('__ADMIN_PANEL__', model("admin/AdminPanel")->getAllPanel(is_login()));
        return $this->fetch();
    }

    /**
     * 获取验证码
     */
    public function getVerify()
    {
        $captcha = new \think\captcha\Captcha(config('captcha'));
        return $captcha->entry();
    }

    /**
     * 设置常用菜单
     */
    public function common_operations()
    {
        $type = input('type');
        $menuid = input('menuid');
        if (!in_array($type, array('add', 'del')) || empty($menuid)) {
            echo false;exit;
        }
        $quicklink = db('menu')->where('id', $menuid)->find();
        if (empty($quicklink)) {
            echo false;exit;
        }
        $info = array(
            'menuid' => $quicklink['id'],
            'userid' => is_login(),
            'name' => $quicklink['title'],
            'url' => "{$quicklink['app']}/{$quicklink['controller']}/{$quicklink['action']}",
        );
        if ($type == 'add') {
            $result = model('Admin/AdminPanel')->createPanel($info);
        } else {
            unset($info['name'], $info['url']);
            $result = model('Admin/AdminPanel')->deletePanel($info);
        }
        if ($result) {
            echo true;exit;
        } else {
            echo false;exit;
        }
    }

    /**
     * 后台登陆界面
     */
    public function login()
    {
        if (request()->isPost()) {
            $data = $this->request->post();
            // 验证数据
            $result = $this->validate($data, 'User.checklogin');
            if (true !== $result) {
                $this->error($result);
            }

            if (!captcha_check($data['captcha'])) {
                $this->error('验证码输入错误！');
                return false;
            }

            $A = model('admin/user');
            if ($A->checkLogin($data['username'], $data['password'])) {
                $this->success('登录成功！', url('Index/index'));
            } else {
                $this->error($A->getError());
            }

        } else {
            if (is_login()) {
                $this->redirect('Index/index');
            } else {
                return $this->fetch("/login");
            }
        }

    }

    /* 手动退出登录 */
    public function logout()
    {
        if (is_login()) {
            model('Admin/User')->logout();
            session('[destroy]'); //自动退出
            $this->success('退出成功！', url('login'));
        } else {
            $this->redirect('login');
        }
    }
}
