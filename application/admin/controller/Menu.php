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

use app\admin\model\Menu as MenuModel;
use app\common\controller\Adminbase;

class Menu extends Adminbase
{

    protected $modelClass         = null;
    protected $modelValidate      = true;
    protected $modelSceneValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new MenuModel;
    }

    //后台菜单首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $tree       = new \util\Tree();
            $tree->icon = array('', '', '');
            $tree->nbsp = '';
            $result     = MenuModel::order(array('listorder', 'id' => 'DESC'))->select()->toArray();

            $tree->init($result);
            $_list  = $tree->getTreeList($tree->getTreeArray(0), 'title');
            $total  = count($_list);
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();

    }

    //添加后台菜单
    public function add()
    {
        $tree   = new \util\Tree();
        $pid    = $this->request->param('parentid/d', 0);
        $result = MenuModel::order(array('listorder', 'id' => 'DESC'))->select()->toArray();
        $tree->init($result);
        $select_categorys = $tree->getTree(0, '', $pid);
        $this->assign("select_categorys", $select_categorys);
        return parent::add();
    }

    /**
     *编辑后台菜单
     */
    public function edit()
    {
        $tree   = new \util\Tree();
        $id     = $this->request->param('id/d', 0);
        $rs     = MenuModel::where(["id" => $id])->find();
        $result = MenuModel::order(array('listorder', 'id' => 'DESC'))->select()->toArray();
        $array  = array();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
            $array[]       = $r;
        }
        $tree->init($array);
        $select_categorys = $tree->getTree(0, '', $rs['parentid']);
        $this->assign("select_categorys", $select_categorys);
        return parent::edit();
    }

    /**
     * 菜单删除
     */
    public function del()
    {
        if (false === $this->request->isPost()) {
            $this->error('未知参数');
        }
        $id = $this->request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $result = MenuModel::where(["parentid" => $id])->find();
        if ($result) {
            $this->error("含有子菜单，无法删除！");
        }
        if (MenuModel::destroy($id) !== false) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除失败！");
        }
    }

}
