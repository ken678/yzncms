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

use app\admin\model\Menu as Menu_Model;
use app\common\controller\Adminbase;
use think\Db;

class Menu extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Menu = new Menu_Model;
    }

    //后台菜单首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $tree = new \util\Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();

            $tree->init($result);
            $_list = $tree->getTreeList($tree->getTreeArray(0), 'title');
            $total = count($_list);
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();

    }

    //添加后台菜单
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            if ($this->Menu->add($data)) {
                $this->success("添加成功！", url("Menu/index"));
            } else {
                $error = $this->Menu->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            $tree = new \util\Tree();
            $parentid = $this->request->param('parentid/d', '');
            $result = Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
            $array = array();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            return $this->fetch();
        }
    }

    /**
     *编辑后台菜单
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            if ($this->Menu->edit($data)) {
                $this->success("编辑成功！", url("Menu/index"));
            } else {
                $error = $this->Menu->getError();
                $this->error($error ? $error : '编辑失败！');
            }
        } else {
            $tree = new \util\Tree();
            $id = $this->request->param('id/d', '');
            $rs = Db::name('menu')->where(["id" => $id])->find();
            $result = Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
            $array = array();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("data", $rs);
            $this->assign("select_categorys", $select_categorys);
            return $this->fetch();
        }

    }

    /**
     * 菜单删除
     */
    public function delete()
    {
        $id = $this->request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $result = Db::name('menu')->where(["parentid" => $id])->find();
        if ($result) {
            $this->error("含有子菜单，无法删除！");
        }
        if ($this->Menu->del($id) !== false) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     * 菜单排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = $this->Menu->allowField(['listorder'])->isUpdate(true)->save(['id' => $id, 'listorder' => $listorder]);
        if ($rs) {
            $this->success("菜单排序成功！");
        } else {
            $this->error("菜单排序失败！");
        }
    }

    /**
     * 菜单状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $r = $this->Menu->where((array('id' => $id)))->value('status');
        $status = $r == '1' ? '0' : '1';
        $this->Menu->where((array('id' => $id)))->update(array('status' => $status));
        $this->success("操作成功！");
    }

}
