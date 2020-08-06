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

use app\admin\model\AdminUser as Admin_User;
use app\admin\model\AuthGroup as AuthGroup_Model;
use app\admin\service\User;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 管理员管理
 */
class Manager extends Adminbase
{
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new Admin_User;

        $this->childrenAdminIds = User::instance()->getChildrenAdminIds(true);
        $this->childrenGroupIds = User::instance()->getChildrenGroupIds(true);

        $roles = User::instance()->getGroups();
        $this->assign("roles", $roles);
    }

    /**
     * 管理员管理列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            list($page, $limit, $where) = $this->buildTableParames();
            $this->AuthGroup_Model = new AuthGroup_Model();

            $count = $this->modelClass
                ->where($where)
                ->where('id', 'in', $this->childrenAdminIds)
                ->order(array('id' => 'ASC'))
                ->withAttr('roleid', function ($value, $data) {
                    return $this->AuthGroup_Model->getRoleIdName($value);
                })
                ->count();

            $_list = $this->modelClass
                ->where($where)
                ->where('id', 'in', $this->childrenAdminIds)
                ->order(array('id' => 'ASC'))
                ->withAttr('roleid', function ($value, $data) {
                    return $this->AuthGroup_Model->getRoleIdName($value);
                })
                ->page($page, $limit)
                ->select();
            $total = count($_list);
            $result = array("code" => 0, 'count' => $count, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 添加管理员
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            $result = $this->validate($data, 'AdminUser.insert');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->modelClass->createManager($data)) {
                $this->success("添加管理员成功！", url('admin/manager/index'));
            } else {
                $error = $this->modelClass->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            return $this->fetch();
        }
    }

    /**
     * 管理员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            $result = $this->validate($data, 'AdminUser.update');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->modelClass->editManager($data)) {
                $this->success("修改成功！");
            } else {
                $this->error($this->User->getError() ?: '修改失败！');
            }
        } else {
            $id = $this->request->param('id/d');
            $data = $this->modelClass->where(array("id" => $id))->find();
            if (empty($data)) {
                $this->error('该信息不存在！');
            }
            $this->assign("data", $data);
            return $this->fetch();
        }
    }

    /**
     * 管理员删除
     */
    public function del()
    {
        $id = $this->request->param('id/d');
        if ($this->modelClass->deleteManager($id)) {
            $this->success("删除成功！");
        } else {
            $this->error($this->modelClass->getError() ?: '删除失败！');
        }
    }

    //批量更新.
    public function multi()
    {
        // 管理员禁止批量操作
        $this->error();
    }

}
