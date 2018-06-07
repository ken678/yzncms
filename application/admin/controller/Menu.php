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
            $r['str_manage'] = '<a class="layui-btn layui-btn-xs ajax-jump layui-btn-danger" url=' . url("Menu/delete", array("id" => $r['id'])) . '>删除</a><a class="layui-btn layui-btn-xs ajax-jump" url=' . url("Menu/edit", array("id" => $r['id'])) . '>编辑</a><a class="layui-btn layui-btn-xs ajax-jump layui-btn-normal" url=' . url("Menu/add", array("parentid" => $r['id'])) . '>添加</a>';
            $r['status'] = $r['status'] ? "<span class='on'><i class='icon iconfont icon-xianshi'></i>显示</span>" : "<span class='off'><i class='icon iconfont icon-yincang'></i>隐藏</span>";
            $array[] = $r;
        }
        $str = "<tr>
        <td>\$listorder</td>
        <td>\$id</td>
        <td>\$str_manage</td>
        <td>\$spacer\$title</td>
        <td>\$status</td>
        </tr>";
        $tree->init($array);
        $categorys = $tree->get_tree(0, $str);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

}
