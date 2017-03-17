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
use think\Controller;

class Base extends Controller {
	/**
	 * 后台初始化
	 */
	public function _initialize(){
        

	}

    /**
     * 获取验证码
     */
	public function getVerify(){
		GetVerify();
	}





}
