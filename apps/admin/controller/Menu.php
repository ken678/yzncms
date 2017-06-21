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
use app\common\controller\Adminbase;

/**
 * 后台菜单管理
 */
class Menu extends Adminbase
{

	/**
	 * 菜单首页
	 */
    public function index()
    {
    	$tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $result = model("Admin/Menu")->order(array("listorder" => "ASC"))->select()->toarray();
        $array = array();
		foreach($result as $r) {
			$r['str_manage'] = '<a class="btn red" href=""><i class="fa fa-trash-o"></i>删除</a><span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?act=goods_class&op=goods_class_edit&gc_id=1">编辑分类信息</a></li>
              <li><a href="index.php?act=goods_class&op=goods_class_add&gc_parent_id=1">新增下级分类</a></li>
            </ul>
            </span>';
            $r['status'] = $r['status'] ? "<span class='on'><i class='fa fa-toggle-on'></i>显示</span>" : "<span class='off'><i class='fa fa-toggle-off'></i>隐藏</span>";
			$array[] = $r;
		}
		$str  = "<tr data-id='1'>
		            <td class='sign'><i class='ico-check'></i></td>
		            <td class='sort'><span title='可编辑' column_id='1' fieldname='gc_sort' nc_type='inline_edit' class='editable'>\$listorder</span></td>
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






}
