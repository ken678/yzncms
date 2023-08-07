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
namespace app\admin\Controller\auth;

use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule;
use app\common\controller\Adminbase;
use think\Db;
use util\Tree;

/**
 * 权限管理控制器
 */
class Group extends Adminbase
{
    //当前登录管理员所有子组别
    protected $childrenGroupIds = [];
    //当前组别列表数据
    protected $groupdata = [];

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass       = new AuthGroupModel;
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

        $groupList = AuthGroupModel::where('id', 'in', $this->childrenGroupIds)->select()->toArray();
        Tree::instance()->init($groupList);
        $result = [];
        if ($this->auth->isAdministrator()) {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        } else {
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n) {
                $result = array_merge($result, Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['parentid']), 'title'));
            }
        }

        $groupName = [];
        foreach ($result as $k => $v) {
            $groupName[$v['id']] = $v['title'];
        }
        $this->groupdata = $groupName;
        $this->assign('groupdata', $this->groupdata);
    }

    //权限管理首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $result    = AuthGroupModel::all(array_keys($this->groupdata))->toArray();
            $groupList = [];
            foreach ($result as $k => $v) {
                $groupList[$v['id']] = $v;
            }
            $result = [];
            foreach ($this->groupdata as $k => $v) {
                if (isset($groupList[$k])) {
                    $groupList[$k]['title'] = $v;
                    $result[]               = $groupList[$k];
                }
            }
            $result = ["code" => 0, "data" => $result];
            return json($result);
        } else {
            return $this->fetch();
        }
    }

    //创建管理员用户组
    public function add()
    {
        if (empty($this->auth_group)) {
            //清除编辑权限的值
            $this->assign('auth_group', ['title' => null, 'id' => null, 'parentid' => null, 'description' => null, 'rules' => null, 'status' => 1]);
        }
        return $this->fetch('edit_group');

    }

    //编辑管理员用户组
    public function edit()
    {
        $id = $this->request->param('id/d');
        if (!in_array($id, $this->childrenGroupIds)) {
            $this->error('你没有权限访问!');
        }
        $auth_group = Db::name('AuthGroup')->where(['module' => 'admin', 'type' => AuthGroupModel::TYPE_ADMIN])->find($id);
        $this->assign('auth_group', $auth_group);
        return $this->fetch('edit_group');
    }

    //管理员用户组数据写入/更新
    public function writeGroup()
    {
        $this->token();
        $data           = $this->request->post();
        $data['module'] = 'admin';
        $data['type']   = AuthGroupModel::TYPE_ADMIN;
        $parentmodel    = AuthGroupModel::get($data['parentid']);
        if (!$parentmodel) {
            $this->error('父角色不存在!');
        }
        if (isset($data['id']) && !empty($data['id'])) {
            if (!in_array($data['parentid'], $this->childrenGroupIds)) {
                $this->error('父角色超出权限范围!');
            }
            if (in_array($data['parentid'], Tree::instance()->getChildrenIds($data['id'], true))) {
                $this->error('父角色不能是自身！');
            }
            if (isset($data['rules'])) {
                $parentrules   = explode(',', $parentmodel->rules);
                $currentrules  = $this->auth->getRuleIds();
                $rules         = explode(',', $data['rules']);
                $rules         = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
                $rules         = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
                $data['rules'] = implode(',', $rules);
            }
            //更新
            $r = $this->modelClass->allowField(true)->save($data, ['id' => $data['id']]);
        } else {
            $result = $this->validate($data, 'AuthGroup');
            if (true !== $result) {
                return $this->error($result);
            }
            if (!in_array($data['parentid'], $this->childrenGroupIds)) {
                $this->error('父角色超出权限范围!');
            }
            $r = $this->modelClass->allowField(true)->save($data);
        }
        if ($r === false) {
            $this->error('操作失败' . $this->modelClass->getError());
        } else {
            $this->success('操作成功!');
        }
    }

    //访问授权页面
    public function access()
    {
        $group_id = $this->request->param('group_id/d');
        if (!in_array($group_id, $this->childrenGroupIds)) {
            $this->error('你没有权限访问!');
        }

        $result = model('admin/Menu')->returnNodes(false);

        $rules = Db::name('AuthGroup')
            ->where('status', '<>', 0)
            ->where('id', '=', $group_id)
            ->where(['type' => AuthGroupModel::TYPE_ADMIN])
            ->find();

        $map        = ['status' => 1];
        $main_rules = Db::name('AuthRule')->where($map)->column('name,id');
        $json       = [];
        foreach ($result as $rs) {
            $data = [
                'nid'      => $rs['id'],
                'checked'  => $rs['id'],
                'parentid' => $rs['parentid'],
                'name'     => $rs['title'],
                'id'       => $main_rules[$rs['url']],
                'checked'  => $this->isCompetence($main_rules[$rs['url']], $rules['rules']) ? true : false,
            ];
            $json[] = $data;
        }
        $this->assign('rules', $rules);
        $this->assign('json', json_encode($json));
        return $this->fetch('managergroup');
    }

    public function isCompetence($id, $ids)
    {
        $ids  = explode(',', $ids);
        $info = in_array($id, $ids);
        if ($info) {
            return true;
        } else {
            return false;
        }
    }


    //批量更新
    public function multi()
    {
        // 管理员禁止批量操作
        $this->error();
    }

}
