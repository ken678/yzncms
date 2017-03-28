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

class Index extends Base {
    public function index(){

    }

    /**
     * 后台登陆界面
     */
    public function login(){
    	return $this->fetch("/login");
    }

    /**
     * 登录验证
     */
    public function checkLogin(){
        $A = model('Admin');
        if($A->checkLogin()){
            $this->success('登录成功！', url('Index/index'));
        }else{
            $this->error($A->getError());
        }
    }



}
