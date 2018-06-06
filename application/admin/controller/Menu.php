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
    //后台菜单首页
    public function index()
    {
        $tree = new \util\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = MenuModel::getList();
        $array = array();
        foreach ($result as $r) {
            $r['str_manage'] = '<a class="btn red" href="javascript:if(confirm(\'您确定要删除吗?\')){location.href=\'' . url("Menu/delete", array("id" => $r['id'])) . '\'};"><i class="icon iconfont icon-shanchu"></i>删除</a><span class="btn"><em><i class="icon iconfont icon-shezhi"></i>设置<i class="arrow"></i></em>
        <ul>
        <li><a href="' . url("Menu/edit", array("id" => $r['id'])) . '">编辑菜单</a></li>
        <li><a href="' . url("Menu/add", array("parentid" => $r['id'])) . '">添加子菜单</a></li>
        </ul>
        </span>';
            $r['status'] = $r['status'] ? "<span class='on'><i class='icon iconfont icon-xianshi'></i>显示</span>" : "<span class='off'><i class='icon iconfont icon-yincang'></i>隐藏</span>";
            $array[] = $r;
        }
        $str = "<tr>
        <td align='center' class='sort'><span alt='可编辑' column_id='\$id' fieldname='gc_sort' nc_type='inline_edit' class='editable itip'>\$listorder</span></td>
        <td align='center'>\$id</td>
        <td align='center' class='handle'>\$str_manage</td>
        <td align='left'>\$spacer\$title</td>
        <td align='center'>\$status</td>
        </tr>";
        $tree->init($array);
        $categorys = $tree->get_tree(0, $str);
        var_dump($categorys);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

}
