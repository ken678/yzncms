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
class Manager extends Adminbase {

    protected function _initialize() {
        $this->assign('__GROUP_MENU__', $this->get_group_menu());
    }

    /**
     * 管理员管理列表
     */
    public function index() {
        $where = array();
        $list   = $this->lists('Admin', $where);
        $this->assign('_list', $list);
        return $this->fetch();
    }

    /**
     * 添加管理员
     */
    public function add() {
        if (request()->isPost()) {
            $this->User = model('Admin/User');
            if ($this->User->createManager(input('post.'))) {
                $this->success("添加管理员成功！", url('manager/index'));
            } else {
                $error = $this->User->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    //管理员删除
    public function delete() {
        $id= input('param.id');
        //执行删除
        if (model('Admin/User')->deleteUser($id)) {
            $this->success("删除成功！");
        } else {
            $this->error(model('Admin/User')->getError()? : '删除失败！');
        }
    }









}