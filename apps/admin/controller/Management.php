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

/**
 * 管理员管理
 */
class Management extends Adminbase {

    //管理员列表
    public function manager() {
    	$this->assign('__GROUP_MENU__', $this->get_group_menu());



    	
        $where = array();
        $list   = $this->lists('Admin', $where);
        $this->assign('_list', $list);
        return $this->fetch();
    }








}