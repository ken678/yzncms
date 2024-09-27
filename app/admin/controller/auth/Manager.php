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

use app\admin\model\AdminUser;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use app\common\controller\Backend;
use think\exception\ValidateException;
use think\facade\Db;
use util\Tree;

class Manager extends Backend
{
    protected $searchFields     = 'id,username,nickname';
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];
    //无需要权限判断的方法
    protected $noNeedRight = ['get_group_list'];

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AdminUser;

        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

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

            $childrenGroupIds = $this->childrenGroupIds;
            $groupName        = AuthGroup::where('id', 'in', $childrenGroupIds)
                ->column('title', 'id');
            $authGroupList = AuthGroupAccess::where('group_id', 'in', $childrenGroupIds)
                ->field('uid,group_id')
                ->select();
            $adminGroupName = [];
            foreach ($authGroupList as $k => $v) {
                if (isset($groupName[$v['group_id']])) {
                    $adminGroupName[$v['uid']][$v['group_id']] = $groupName[$v['group_id']];
                }
            }
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n) {
                $adminGroupName[$this->auth->id][$n['id']] = $n['title'];
            }
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

            $list = $this->modelClass
                ->where($where)
                ->where('id', 'in', $this->childrenAdminIds)
                ->withoutField('password', 'salt', 'token')
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $k => &$v) {
                $groups           = isset($adminGroupName[$v['id']]) ? $adminGroupName[$v['id']] : [];
                $v['groups']      = implode(',', array_keys($groups));
                $v['groups_text'] = implode(',', array_values($groups));
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
            Db::startTrans();
            try {
                $this->validate($params, 'AdminUser.insert');

                $passwordinfo       = encrypt_password($params['password']); //对密码进行处理
                $params['password'] = $passwordinfo['password'];
                $params['encrypt']  = $passwordinfo['encrypt'];

                /*if (!in_array($params['roleid'], $this->childrenGroupIds)) {
                throw new \Exception('没有权限操作！');
                }*/
                $this->modelClass->save($params);
                Db::commit();
            } catch (ValidateException | \Exception $e) {
                Db::rollback();
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
            Db::startTrans();
            try {
                $this->validate($params, 'AdminUser.update');
                /*if (!in_array($params['roleid'], $this->childrenGroupIds)) {
                $this->error('没有权限操作！');
                }*/
                //密码为空，表示不修改密码
                if (isset($params['password']) && $params['password']) {
                    $passwordinfo       = encrypt_password($params['password']); //对密码进行处理
                    $params['encrypt']  = $passwordinfo['encrypt'];
                    $params['password'] = $passwordinfo['password'];

                } else {
                    unset($params['password'], $params['encrypt']);
                }
                $row->save($params);
                Db::commit();
            } catch (ValidateException | \Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            $this->success("修改成功！");
        }

        $groupIds = $this->auth->getGroupIds($row['id']);
        $this->assign("data", $row);
        $this->view->assign("groupids", implode(',', $groupIds));
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

    public function get_group_list()
    {
        $keyValue = $this->request->param('keyValue');
        $where    = [];
        if ($keyValue) {
            $groupList = AuthGroup::where('id', 'in', $keyValue)->select()->toArray();
            foreach ($groupList as $k => $v) {
                $groupdata[] = ['id' => $v['id'], 'name' => $v['title']];
            }
            return $result = ['count' => count($groupdata), 'data' => $groupdata];
        }
        $groupList = AuthGroup::where('id', 'in', $this->childrenGroupIds)->select()->toArray();
        Tree::instance()->init($groupList);
        $groupdata = [];
        if ($this->auth->isAdministrator()) {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
            foreach ($result as $k => $v) {
                $groupdata[] = ['id' => $v['id'], 'name' => $v['title']];
            }
        } else {
            $result = [];
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n) {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']), 'title');
                foreach ($childlist as $k => $v) {
                    $groupdata[] = ['id' => $v['id'], 'name' => $v['title']];
                }
            }
        }
        return $result = ['count' => count($groupdata), 'data' => $groupdata];
    }

}
