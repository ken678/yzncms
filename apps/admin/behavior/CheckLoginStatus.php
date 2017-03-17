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
namespace app\admin\behavior;

/**
 * 检测是否登录 先判断不需要登录的路径
 */
class CheckLoginStatus{
    public function run(&$params){
    	$allowUrl = ['admin/index/login',
			         'admin/index/checklogin',
			         'admin/index/logout',
			         'admin/index/logout',
                     'admin/index/getverify'
			        ];
		$request = request();
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(!in_array($visit,$allowUrl)){
        	header("Location:".url('admin/index/login'));
        	exit();
        }
    }
}