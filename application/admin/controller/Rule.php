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
namespace app\admin\controller;

use app\admin\model\AuthRule as AuthRuleModel;
use app\common\controller\Adminbase;
use think\facade\Cache;
use util\Tree;

class Rule extends Adminbase
{
    protected $modelClass         = null;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AuthRuleModel;
    }

    //后台菜单首页
    public function index()
    {
        if ($this->request->isAjax()) {
	        $ruleList     = AuthRuleModel::order('listorder DESC,id ASC')->select()->toArray();
	        $tree       = new Tree();
	        $tree->icon = array('', '', '');
	        $tree->nbsp = '';
	        $tree->init($ruleList,'pid');
	        $list = $tree->getTreeArray(0);
            $total = count($list);
            $result = array("code" => 0, "count" => $total, "data" => $list);
            return json($result);
        }
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params) {
                $result = $this->validate($params,'app\admin\validate\AuthRule');
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
        $tree   = new Tree();
        $pid    = $this->request->param('parentid/d', 0);
        $ruleList     = AuthRuleModel::where('ismenu',1)->order('listorder DESC,id ASC')->select()->toArray();
        $tree->init($ruleList,'pid');
        $select_categorys = $tree->getTree(0, '', $pid);
        $this->assign("select_categorys", $select_categorys);
        return $this->fetch();
    }

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
                if ($params['pid'] == $row['id']) {
                    $this->error('父级不能是它自己');
                }
                if ($params['pid'] != $row['pid']) {
                    $childrenIds = Tree::instance()->init(AuthRuleModel::select()->toArray(),'pid')->getChildrenIds($row['id']);
                    if (in_array($params['pid'], $childrenIds)) {
                        $this->error('父级不能是它的子级');
                    }
                }
                $result = $this->validate($params,'app\admin\validate\AuthRule');
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
        $tree   = new Tree();
        $result     = AuthRuleModel::where('ismenu',1)->order('listorder DESC,id ASC')->select()->toArray();
        $ruleList = [];
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $row->pid ? 'selected' : '';
            $ruleList[]       = $r;
        }

        $tree->init($ruleList,'pid');
        $select_categorys = $tree->getTree(0, '', $row->pid);
        $this->assign("select_categorys", $select_categorys);
        $this->view->assign("data", $row);
        return $this->fetch();
    }
}