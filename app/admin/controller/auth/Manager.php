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
// | Original reference: https://gitee.com/karson/fastadmin
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 管理员管理
// +----------------------------------------------------------------------
namespace app\admin\controller\auth;

use app\admin\model\AdminUser as Admin_User;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\common\controller\Backend;
use think\exception\ValidateException;
use util\Tree;

class Manager extends Backend
{
    protected $searchFields     = 'id,username,nickname';
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new Admin_User;

        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

        $groupList = AuthGroupModel::where('id', 'in', $this->childrenGroupIds)->select()->toArray();
        Tree::instance()->init($groupList);
        $groupdata = [];
        if ($this->auth->isAdministrator()) {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
            foreach ($result as $k => $v) {
                $groupdata[$v['id']] = $v['title'];
            }
        } else {
            $result = [];
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n) {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']), 'title');
                foreach ($childlist as $k => $v) {
                    $groupdata[$v['id']] = $v['title'];
                }
            }
        }
        $this->assign('groupdata', $groupdata);
        $this->assignconfig("admin", ['id' => $this->auth->id]);
    }

    /**
     * 管理员管理列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

            $childrenGroupIds = $this->childrenGroupIds;
            $groupName        = AuthGroupModel::where('id', 'in', $childrenGroupIds)
                ->column('title', 'id');

            $list = $this->modelClass
                ->where($where)
                ->where('id', 'in', $this->childrenAdminIds)
                ->withoutField('password', 'salt', 'token')
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $k => &$v) {
                $v['groups'] = $groupName[$v['roleid']] ?? '未知';
            }
            unset($v);
            $result = ["code" => 0, 'count' => $list->total(), "data" => $list->items()];
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
            $this->token();
            $params = $this->request->param('row/a');
            try {
                $this->validate($params, 'AdminUser.insert');
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            }
            $passwordinfo       = encrypt_password($params['password']); //对密码进行处理
            $params['password'] = $passwordinfo['password'];
            $params['encrypt']  = $passwordinfo['encrypt'];

            if (!in_array($params['roleid'], $this->childrenGroupIds)) {
                $this->error('没有权限操作！');
            }
            try {
                $this->modelClass->save($params);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success("添加成功！", url('index'));
        }
        return $this->fetch();
    }

    /**
     * 管理员编辑
     */
    public function edit()
    {
        $id  = $this->request->param('id/d', 0);
        $row = $this->modelClass->find($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if (!in_array($row->id, $this->childrenAdminIds)) {
            $this->error('没有权限操作！');
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->param('row/a');
            try {
                $this->validate($params, 'AdminUser.update');
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            }
            if (!in_array($params['roleid'], $this->childrenGroupIds)) {
                $this->error('没有权限操作！');
            }
            //密码为空，表示不修改密码
            if (isset($params['password']) && $params['password']) {
                $passwordinfo       = encrypt_password($params['password']); //对密码进行处理
                $params['encrypt']  = $passwordinfo['encrypt'];
                $params['password'] = $passwordinfo['password'];

            } else {
                unset($params['password'], $params['encrypt']);
            }
            try {
                $row->save($params);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success("修改成功！");
        }
        $this->assign("data", $row);
        return $this->fetch();
    }

    /**
     * 管理员删除
     */
    public function del()
    {
        if (false === $this->request->isPost()) {
            $this->error('未知参数');
        }
        $id = $this->request->param('id/a');
        if (empty($id)) {
            $this->error('请指定需要删除的用户ID！');
        }
        $ids = array_intersect($this->childrenAdminIds, array_filter($id));

        $adminList = $this->modelClass->where('id', 'in', $ids)->where('roleid', 'in', $this->childrenGroupIds)->select();
        if ($adminList) {
            $deleteIds = [];
            foreach ($adminList as $k => $v) {
                $deleteIds[] = $v->id;
            }
            $deleteIds = array_values(array_diff($deleteIds, [$this->auth->id]));
            if ($deleteIds) {
                try {
                    $this->modelClass->destroy($deleteIds);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                $this->success("删除成功！");
            }
        }
        $this->error('没有权限删除！');
    }

    //批量更新.
    public function multi()
    {
        // 管理员禁止批量操作
        $this->error();
    }

}
