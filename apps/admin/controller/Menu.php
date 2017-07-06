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
namespace app\admin\controller;
use \think\Request;
use \think\Loader;
use app\common\controller\Adminbase;
use app\admin\logic\Menu as MenuLogic;
use app\admin\model\Menu as MenuModel;

/**
 * 后台菜单管理
 */
class Menu extends Adminbase
{
	protected function _initialize()
    {
        parent::_initialize();
        $this->Menu = new MenuLogic();
    }

	/**
	 * 菜单首页
	 */
    public function index()
    {
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = MenuModel::getList();
        $array = array();
        foreach($result as $r) {
        $r['str_manage'] = '<a class="btn red" href="javascript:if(confirm(\'您确定要删除吗?.\')){location.href=\''.url("Menu/delete",array("id" => $r['id'])).'\'};"><i class="fa fa-trash-o"></i>删除</a><span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul>
              <li><a href="'.url("Menu/edit", array("id" => $r['id'])).'">编辑菜单</a></li>
              <li><a href="'.url("Menu/add",array("parentid" => $r['id'])).'">添加子菜单</a></li>
            </ul>
            </span>';
            $r['status'] = $r['status'] ? "<span class='on'><i class='fa fa-toggle-on'></i>显示</span>" : "<span class='off'><i class='fa fa-toggle-off'></i>隐藏</span>";
        $array[] = $r;
        }
        $str  = "<tr data-id='\$id'>
                <td class='sign'><i class='ico-check'></i></td>
                <td class='sort'><span title='可编辑' column_id='\$id' fieldname='gc_sort' nc_type='inline_edit' class='editable'>\$listorder</span></td>
                <td>\$id</td>
                <td>\$spacer\$title</td>
                <td>\$status</td>
        	<td class='handle'>\$str_manage</td>
        	<td></td>
        </tr>";
        $tree->init($array);
        $categorys = $tree->get_tree(0, $str);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    /**
     * 新增菜单
     */
   public function add()
   {
   	   if(Request::instance()->isPost()){
   	   	    $data = Request::instance()->param();
  			if ($this->Menu->add($data)) {
  			    $this->success("添加成功！", url("Menu/index"));
  			} else {
  			    $error = $this->Menu->getError();
                $this->error($error ? $error : '添加失败！');
  			}
   	   }else{
   	   	    $tree = new \Tree();
   	   	    $parentid = Request::instance()->param('parentid/d','');
            $result = MenuModel::getList();
   	   	    $array = array();
   	   	    foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str  = "<option value='\$id' \$selected>\$spacer \$title</option>";
      			$tree->init($array);
      			$select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
   	   	    return $this->fetch();
   	   }
   }

    /**
     * 编辑菜单
     */
   public function edit()
   {
   	   if(Request::instance()->isPost()){
   	   	    $data = Request::instance()->param();
  			if ($this->Menu->edit($data)) {
  			    $this->success("编辑成功！", url("Menu/index"));
  			} else {
  			    $error = $this->Menu->getError();
                $this->error($error ? $error : '编辑失败！');
  			}
   	   }else{
   	   	    $tree = new \Tree();
   	   	    $id = Request::instance()->param('id/d','');
            $rs = MenuModel::getInfo(array("id" => $id));
            $result = MenuModel::getList();
   	   	    $array = array();
   	   	    foreach ($result as $r) {
                $r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
                $array[] = $r;
            }
            $str  = "<option value='\$id' \$selected>\$spacer \$title</option>";
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
        $id = Request::instance()->param('id/d');
        $result = MenuModel::getInfo(array("parentid" => $id));
        if ($result) {
            $this->error("含有子菜单，无法删除！");
        }
        if (MenuModel::remove($id) !== false) {
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
      $id = Request::instance()->param('id/d',0);
      $listorder = Request::instance()->param('value/d',0);
      MenuModel::edit(['listorder' => $listorder,'id'=>$id]);
      $return = 'true';
      exit(json_encode(array('result'=>$return)));
    }






}
