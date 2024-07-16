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
// | 权限管理控制器
// +----------------------------------------------------------------------
namespace app\admin\Controller\auth;

use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule as AuthRuleModel;
use app\common\controller\Backend;
use think\exception\ValidateException;
use think\facade\Db;
use util\Tree;

class Group extends Backend
{
    //当前登录管理员所有子组别
    protected $childrenGroupIds = [];
    //当前组别列表数据
    protected $grouplist = [];
    protected $groupdata = [];

    //无需要权限判断的方法
    protected $noNeedRight = ['roletree'];

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AuthGroupModel;

        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

        $groupList = AuthGroupModel::where('id', 'in', $this->childrenGroupIds)->select()->toArray();

        Tree::instance()->init($groupList);
        $groupList = [];
        if ($this->auth->isAdministrator()) {
            $groupList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        } else {
            $groups   = $this->auth->getGroups();
            $groupIds = [];
            foreach ($groups as $m => $n) {
                if (in_array($n['id'], $groupIds) || in_array($n['parentid'], $groupIds)) {
                    continue;
                }
                $groupList = array_merge($groupList, Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['parentid']), 'title'));
                foreach ($groupList as $index => $item) {
                    $groupIds[] = $item['id'];
                }
            }
        }

        $groupName = [];
        foreach ($groupList as $k => $v) {
            $groupName[$v['id']] = $v['title'];
        }
        $this->grouplist = $groupList;
        $this->groupdata = $groupName;
        $this->assign('groupdata', $this->groupdata);
    }

    //权限管理首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $list   = $this->grouplist;
            $total  = count($list);
            $result = ["code" => 0, "count" => $total, "data" => $list];
            return json($result);
        } else {
            $this->assignconfig("admin", ['roleid' => $this->auth->roleid]);
            return $this->fetch();
        }
    }

    //创建管理员用户组
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a", [], 'strip_tags');
            try {
                $this->validate($params, 'app\admin\validate\AuthGroup');
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            }
            if (!in_array($params['parentid'], $this->childrenGroupIds)) {
                $this->error('父组别超出权限范围');
            }
            $parentmodel = AuthGroupModel::find($params['parentid']);
            if (!$parentmodel) {
                $this->error('父组别未找到');
            }
            if ($params) {
                $this->modelClass->create($params);
                $this->success('新增成功');
            }
            $this->error('参数不能为空');
        }
        return $this->fetch();

    }

    //编辑管理员用户组
    public function edit()
    {
        $id = $this->request->param('id/d');
        if (!in_array($id, $this->childrenGroupIds)) {
            $this->error('你没有权限访问!');
        }
        $row = $this->modelClass->find($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a", [], 'strip_tags');
            //父节点不能是非权限内节点
            if (!in_array($params['parentid'], $this->childrenGroupIds)) {
                $this->error('父组别超出权限范围');
            }
            // 父节点不能是它自身的子节点或自己本身
            if (in_array($params['parentid'], Tree::instance()->getChildrenIds($row->id, true))) {
                $this->error('父角色不能是自身！');
            }
            $params['rules'] = explode(',', $params['rules']);

            $parentmodel = AuthGroupModel::find($params['parentid']);
            if (!$parentmodel) {
                $this->error('父组别未找到');
            }

            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            // 当前组别的规则节点
            $currentrules = $this->auth->getRuleIds();
            $rules        = $params['rules'];
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules           = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);
            if ($params) {
                Db::startTrans();
                try {
                    $row->save($params);
                    $children_auth_groups = $this->modelClass->whereIn('id', implode(',', (Tree::instance()->getChildrenIds($row->id))))->select();
                    $childparams          = [];
                    foreach ($children_auth_groups as $key => $children_auth_group) {
                        $childparams[$key]['id']    = $children_auth_group->id;
                        $childparams[$key]['rules'] = implode(',', array_intersect(explode(',', $children_auth_group->rules), $rules));
                    }
                    $this->modelClass->saveAll($childparams);
                    Db::commit();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                $this->success('编辑成功');
            }
            $this->error('参数不能为空');
        }
        $this->assign("data", $row);
        return $this->fetch();
    }

    /**
     * 读取角色权限树
     *
     * @internal
     */
    public function roletree()
    {

        $model             = new AuthGroupModel;
        $id                = $this->request->post("id");
        $pid               = $this->request->post("parentid");
        $parentGroupModel  = $model->find($pid);
        $currentGroupModel = null;
        if ($id) {
            $currentGroupModel = $model->find($id);
        }
        if (($pid || $parentGroupModel) && (!$id || $currentGroupModel)) {
            $id       = $id ? $id : null;
            $ruleList = AuthRuleModel::order('listorder', 'desc')->order('id', 'asc')->select()->toArray();

            //读取父类角色所有节点列表
            $parentRuleList = [];
            if (in_array('*', explode(',', $parentGroupModel->rules))) {
                $parentRuleList = $ruleList;
            } else {
                $parentRuleIds = explode(',', $parentGroupModel->rules);
                foreach ($ruleList as $k => $v) {
                    if (in_array($v['id'], $parentRuleIds)) {
                        $parentRuleList[] = $v;
                    }
                }
            }
            $ruleTree  = new Tree();
            $groupTree = new Tree();
            //当前所有正常规则列表
            $ruleTree->init($parentRuleList);
            //角色组列表
            $groupTree->init(AuthGroupModel::where('id', 'in', $this->childrenGroupIds)->select()->toArray());

            //读取当前角色下规则ID集合
            $adminRuleIds = $this->auth->getRuleIds();
            //是否是超级管理员
            $superadmin = $this->auth->isAdministrator();
            //当前拥有的规则ID集合
            $currentRuleIds = $id ? explode(',', $currentGroupModel->rules) : [];

            if (!$id || !in_array($pid, $this->childrenGroupIds) || !in_array($pid, $groupTree->getChildrenIds($id, true))) {
                $parentRuleList = $ruleTree->getTreeList($ruleTree->getTreeArray(0), 'name');
                $hasChildrens   = [];
                foreach ($parentRuleList as $k => $v) {
                    if ($v['haschild']) {
                        $hasChildrens[] = $v['id'];
                    }
                }
                $parentRuleIds = array_map(function ($item) {
                    return $item['id'];
                }, $parentRuleList);
                $nodeList = [];
                foreach ($parentRuleList as $k => $v) {
                    if (!$superadmin && !in_array($v['id'], $adminRuleIds)) {
                        continue;
                    }
                    if ($v['parentid'] && !in_array($v['parentid'], $parentRuleIds)) {
                        continue;
                    }
                    $state      = ['selected' => in_array($v['id'], $currentRuleIds) && !in_array($v['id'], $hasChildrens)];
                    $nodeList[] = ['id' => $v['id'], 'parent' => $v['parentid'] ? $v['parentid'] : '#', 'text' => $v['title'], 'type' => 'menu', 'state' => $state];
                }
                $this->success('', null, $nodeList);
            } else {
                $this->error('父组别不能是它的子组别');
            }
        } else {
            $this->error('组别未找到');
        }
    }

    //批量更新
    public function multi()
    {
        // 管理员禁止批量操作
        $this->error();
    }

}
