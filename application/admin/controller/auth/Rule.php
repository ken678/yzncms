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

// +----------------------------------------------------------------------
// | 后台菜单管理
// +----------------------------------------------------------------------
namespace app\admin\controller\auth;

use app\admin\model\AuthRule as AuthRuleModel;
use app\common\controller\Adminbase;
use think\Db;
use think\facade\Cache;
use util\Tree;

class Rule extends Adminbase
{
    protected $modelClass = null;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AuthRuleModel;
    }

    //后台菜单首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $ruleList   = AuthRuleModel::order('listorder DESC,id ASC')->select()->toArray();
            $tree       = new Tree();
            $tree->icon = ['', '', ''];
            $tree->nbsp = '';
            $tree->init($ruleList);
            $list   = $tree->getTreeArray(0);
            $total  = count($list);
            $result = ["code" => 0, "count" => $total, "data" => $list];
            return json($result);
        }
        return $this->fetch();
    }

    //新增
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params) {
                if (!$params['ismenu'] && !$params['parentid']) {
                    $this->error('非菜单规则节点必须有父级');
                }
                $params['icon'] = $params['icon'] ? 'iconfont ' . $params['icon'] : '';
                $result         = $this->validate($params, 'app\admin\validate\AuthRule');
                if (true !== $result) {
                    $this->error($result);
                }
                try {
                    $result = $this->modelClass->save($params);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                $this->success('新增成功');
            }
            $this->error('参数不能为空');
        }
        $tree     = new Tree();
        $parentid = $this->request->param('parentid/d', 0);
        $ruleList = AuthRuleModel::where('ismenu', 1)->order('listorder DESC,id ASC')->select()->toArray();
        $tree->init($ruleList);
        $select_categorys = $tree->getTree(0, '', $parentid);
        $this->assign("select_categorys", $select_categorys);
        return $this->fetch();
    }

    //编辑
    public function edit()
    {
        $id  = $this->request->param('id/d', 0);
        $row = $this->modelClass->get($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params) {
                if (!$params['ismenu'] && !$params['parentid']) {
                    $this->error('非菜单规则节点必须有父级');
                }
                if ($params['parentid'] == $row['id']) {
                    $this->error('父级不能是它自己');
                }
                if ($params['parentid'] != $row['parentid']) {
                    $childrenIds = Tree::instance()->init(AuthRuleModel::select()->toArray())->getChildrenIds($row['id']);
                    if (in_array($params['parentid'], $childrenIds)) {
                        $this->error('父级不能是它的子级');
                    }
                }
                $params['icon'] = $params['icon'] ? 'iconfont ' . $params['icon'] : '';
                $result         = $this->validate($params, 'app\admin\validate\AuthRule');
                if (true !== $result) {
                    $this->error($result);
                }
                try {
                    $result = $row->save($params);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                $this->success('编辑成功');
            }
            $this->error('参数不能为空');
        }
        $tree     = new Tree();
        $result   = AuthRuleModel::where('ismenu', 1)->order('listorder DESC,id ASC')->select()->toArray();
        $ruleList = [];
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $row->parentid ? 'selected' : '';
            $ruleList[]    = $r;
        }

        $tree->init($ruleList);
        $select_categorys = $tree->getTree(0, '', $row->parentid);
        $this->assign("select_categorys", $select_categorys);
        $this->view->assign("data", $row);
        return $this->fetch();
    }

    //删除
    public function del()
    {
        if (false === $this->request->isPost()) {
            $this->error('未知参数');
        }
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = [0 => $ids];
        }
        if ($ids) {
            // 必须将结果集转换为数组
            $ruleList = \think\Db::name("auth_rule")->field('id,parentid')->order('listorder DESC,id ASC')->select();
            Tree::instance()->init($ruleList);
            $delIds = [];
            foreach ($ids as $k => $v) {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, true));
            }
            $delIds = array_unique($delIds);
            $count  = $this->modelClass->where('id', 'in', $delIds)->delete();
            if ($count) {
                Cache::rm('__menu__');
                $this->success("操作成功！");
            }
        }
        $this->error('没有数据删除！');
    }
}
