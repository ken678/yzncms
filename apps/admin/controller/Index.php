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
use app\admin\model\AuthGroup;

class Index extends Adminbase
{

	/**
	 * 后台首页
	 */
    public function index()
    {
		$this->assign('__MENU__', model("Admin/Menu")->getMenuList());
        $this->assign('role_name', model('Admin/AuthGroup')->getRoleIdName(is_login()));


        /*管理员收藏栏*/
        $AdminPanel = model("Admin/AdminPanel")->getAllPanel(is_login());
        if(!empty($AdminPanel)){
            foreach ($AdminPanel as $key =>$v){
                $AdminPanel_ids[]=$v['menuid'];
            }
            $this->assign('__ADMIN_PANEL_ID__', $AdminPanel_ids);
        }else{
            $this->assign('__ADMIN_PANEL_ID__',array());
        }
        $this->assign('__ADMIN_PANEL__', $AdminPanel);

        return $this->fetch();
    }


    /**
     * 设置常用菜单
     */
    public function common_operations() {
        $type  = input('type');
        $mid = input('mid');
        if (!in_array($type, array('add', 'del')) || empty($mid)) {
            echo false;exit;
        }
        $quicklink = db('menu')->where('id',$mid)->find();
        if (!$quicklink) {
            echo false;exit;
        }
        $info = array(
            'menuid' => $quicklink['id'],
            'userid' => is_login(),
            'name' => $quicklink['title'],
            'url' => "{$quicklink['app']}/{$quicklink['controller']}/{$quicklink['action']}",
        );
        if (model('Admin/AdminPanel')->addPanel($info)) {
            echo true;exit;
        } else {
            echo false;exit;
        }
    }

    /**
     * 获取验证码
     */
    public function getVerify()
    {
        GetVerify();
    }

    /**
     * 后台登陆界面
     */
    public function login()
    {
       if(request()->isPost()){
	       	$data = $this->request->post();
	        // 验证数据
	        $result = $this->validate($data, 'User.checklogin');
	        if(true !== $result){
	            $this->error($result);
	        }

	        if(!CheckVerify($data['captcha'])){
	            $this->error('验证码输入错误！');
	            return false;
	        }

	        $A = model('admin/user');
	        if($A->checkLogin($data['username'], $data['password'])){
	            $this->success('登录成功！', url('Index/index'));
	        }else{
	            $this->error($A->getError());
	        }

       }else{
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                return $this->fetch("/login");
            }
       }

    }

    /* 退出登录 */
    public function logout()
    {
        if(is_login()){
            session('user_auth', null);
            session('user_auth_sign', null);
            $this->success('退出成功！', url('login'));
        } else {
            $this->redirect('login');
        }
    }
}
