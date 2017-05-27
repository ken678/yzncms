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
namespace app\admin\Controller;
use app\common\controller\Adminbase;

/**
 * 权限管理控制器
 */
class AuthManager extends Adminbase
{

	/**
	 * 权限管理首页
	 */
    public function index()
    {
		$list = $this->lists('AuthGroup',array('module'=>'admin'),'id asc');
		$this->assign( '_list', $list['data'] );
        return $this->fetch();
    }

    /**
     * 状态修改
     */
    public function changeStatus($method=null)
    {
        if ( empty(input('id/a')) ) {
            $this->error('请选择要操作的数据!');
        }
        switch ( strtolower($method) ){
            case 'forbidgroup':
                $this->forbid('AuthGroup');
                break;
            case 'resumegroup':
                $this->resume('AuthGroup');
                break;
            case 'deletegroup':
                $this->delete('AuthGroup');
                break;
            default:
                $this->error($method.'参数非法');
        }
    }






}