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

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\common\controller\Adminbase;
use think\Db;

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
        if ($this->request->isAjax()) {
            $_list = Db::name('AuthGroup')->where(['module' => 'admin'])->order(['id' => 'ASC'])->select();
            $total = count($_list);
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 访问授权页面
     */
    public function access()
    {
        $this->updateRules(); //更新节点

        $result = model('admin/Menu')->returnNodes(false);
        $group_id = $this->request->param('group_id/d');
        $rules = Db::name('AuthGroup')
            ->where('status', '<>', 0)
            ->where('id', '=', $group_id)
            ->where(['type' => AuthGroup::TYPE_ADMIN])
            ->value('rules');

        $map = array('module' => 'admin', 'status' => 1);
        $main_rules = Db::name('AuthRule')->where($map)->column('name,id');

        $json = array();
        foreach ($result as $rs) {
            $data = array(
                'nid' => $rs['id'],
                'checked' => $rs['id'],
                'parentid' => $rs['parentid'],
                'name' => $rs['title'],
                'id' => $main_rules[$rs['url']],
                'checked' => $this->isCompetence($main_rules[$rs['url']], $rules) ? true : false,
            );
            $json[] = $data;
        }
        $this->assign('group_id', $group_id);
        $this->assign('json', json_encode($json));
        return $this->fetch('managergroup');
    }

    public function isCompetence($id, $ids)
    {
        $ids = explode(',', $ids);
        $info = in_array($id, $ids);
        if ($info) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 创建管理员用户组
     */
    public function createGroup()
    {
        if (empty($this->auth_group)) {
            //清除编辑权限的值
            $this->assign('auth_group', array('title' => null, 'id' => null, 'description' => null, 'rules' => null));
        }
        return $this->fetch('edit_group');

    }

    /**
     * 编辑管理员用户组
     */
    public function editGroup()
    {
        $id = $this->request->param('id/d');
        $auth_group = Db::name('AuthGroup')->where(array('module' => 'admin', 'type' => AuthGroup::TYPE_ADMIN))->find($id);
        $this->assign('auth_group', $auth_group);
        return $this->fetch();

    }

    /**
     * 删除管理员用户组
     */
    public function deleteGroup()
    {
        $roleid = $this->request->param('id/d');
        //判断是否可删除
        if (empty($roleid) || $roleid == 1) {
            $this->error('超级管理员角色不能被删除!');
        }
        $res = Db::name("admin")->where('roleid', $roleid)->find();
        if ($res) {
            $this->error('删除失败，当前角色含有用户');
        }
        $AuthGroup = new AuthGroup();
        $r = $AuthGroup->destroy($roleid);
        if ($r === false) {
            $this->error('操作失败' . $AuthGroup->getError());
        } else {
            $this->success('操作成功!');
        }
    }

    /**
     * 管理员用户组数据写入/更新
     */
    public function writeGroup()
    {
        $data = $this->request->post();
        $data['module'] = 'admin';
        $data['type'] = AuthGroup::TYPE_ADMIN;
        $AuthGroup = new AuthGroup;
        if (isset($data['id']) && !empty($data['id'])) {
            //更新
            $r = $AuthGroup->allowField(true)->save($data, ['id' => $data['id']]);
        } else {
            $result = $this->validate($data, 'AuthGroup');
            if (true !== $result) {
                return $this->error($result);
            }
            $r = $AuthGroup->allowField(true)->save($data);
        }
        if ($r === false) {
            $this->error('操作失败' . $AuthGroup->getError());
        } else {
            $this->success('操作成功!');
        }

    }

    /**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     */
    public function updateRules()
    {
        //需要新增的节点必然位于$nodes
        $nodes = model("admin/Menu")->returnNodes(false);
        $AuthRule = model('AuthRule');
        //需要更新和删除的节点必然位于$rules
        $rules = $AuthRule->where('type', 'in', '1,2')->order('name')->select();
        //构建insert数据
        $data = array(); //保存需要插入和更新的新节点
        foreach ($nodes as $value) {
            $temp['name'] = $value['url'];
            $temp['title'] = $value['title'];
            $temp['module'] = $value['app'];
            if ($value['parentid'] > 0) {
                $temp['type'] = AuthRule::RULE_URL;
            } else {
                $temp['type'] = AuthRule::RULE_MAIN;
            }
            $temp['status'] = 1;
            $data[strtolower($temp['name'] . $temp['module'] . $temp['type'])] = $temp; //去除重复项
        }
        $update = array(); //保存需要更新的节点
        $ids = array(); //保存需要删除的节点的id
        foreach ($rules as $index => $rule) {
            $key = strtolower($rule['name'] . $rule['module'] . $rule['type']);
            if (isset($data[$key])) {
                //如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id']; //为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]); //排除已存在的
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']] = $rule;
            } elseif ($rule['status'] == 1) {
                $ids[] = $rule['id'];
            }
        }
        if (count($update)) {
            foreach ($update as $k => $row) {
                if ($row != $diff[$row['id']]) {
                    $AuthRule->where(array('id' => $row['id']))->update($row);
                }
            }
        }
        if (count($ids)) {
            $AuthRule->where(array('id' => array('IN', implode(',', $ids))))->update(array('status' => -1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if (count($data)) {
            $AuthRule->insertAll(array_values($data));
        }
        if ($AuthRule->getError()) {
            trace('[' . __METHOD__ . ']:' . $AuthRule->getError());
            return false;
        } else {
            return true;
        }

    }

}
