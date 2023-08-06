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

class Rule extends Adminbase
{
    protected $modelClass         = null;
    protected $modelValidate      = true;
    protected $modelSceneValidate = true;

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
	        $tree       = new \util\Tree();
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
                /*if (!$params['ismenu'] && !$params['pid']) {
                    $this->error('非菜单规则节点必须有父级');
                }*/
                $result = $this->modelClass->validate()->save($params);
                if ($result === false) {
                    $this->error($this->model->getError());
                }
                Cache::rm('__menu__');
                $this->success('新增成功');
            }
            $this->error('参数不能为空');
        }
        $tree   = new \util\Tree();
        $pid    = $this->request->param('parentid/d', 0);
        $ruleList     = AuthRuleModel::where('ismenu',1)->order('listorder DESC,id ASC')->select()->toArray();
        $tree->init($ruleList,'pid');
        $select_categorys = $tree->getTree(0, '', $pid);
        $this->assign("select_categorys", $select_categorys);
        return $this->fetch();
    }
}