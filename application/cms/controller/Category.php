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
// | 栏目管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\common\controller\Adminbase;
use think\Db;

class Category extends Adminbase
{

    //栏目列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $tree = new \util\Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = Db::name('category')->order(array('listorder', 'catid' => 'DESC'))->select();

            $tree->init($result);
            $_list = $tree->getTreeList($tree->getTreeArray(0), 'title');
            $total = count($_list);
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
        /*$models = cache('Model');
    $result = cache('Category');
    $categorys = array();
    $types = array(0 => '内部栏目', 1 => '<font color="blue">单网页</font>', 2 => '<font color="red">外部链接</font>');
    if (!empty($result)) {
    foreach ($result as $r) {
    $r = getCategory($r['catid']);
    $r['str_manage'] = '';
    $r['str_manage'] .= '<a class="btn red" href="javascript:if(confirm(\'您确定要删除吗?\')){location.href=\'' . Url::build("Category/delete", array("catid" => $r['catid'])) . '\'};"><i class="icon iconfont icon-shanchu"></i>删除</a>';
    $r['str_manage'] .= '<span class="btn"><em><i class="icon iconfont icon-shezhi"></i>设置<i class="arrow"></i></em><ul>';
    if ($r['type'] != 2) {
    if ($r['type'] == 1) {
    $r['str_manage'] .= '<li><a href="' . Url::build("Category/singlepage", array("parentid" => $r['catid'])) . '">添加下级栏目</a></li>';
    } else {
    $r['str_manage'] .= '<li><a href="' . Url::build("Category/add", array("parentid" => $r['catid'])) . '">添加下级栏目</a></li>';
    }
    }
    $r['str_manage'] .= '<li><a href="' . Url::build("Category/edit", array("catid" => $r['catid'])) . '">编辑栏目信息</a></li>';
    $r['str_manage'] .= '</ul></span>';

    $r['modelname'] = $models[$r['modelid']]['name'];
    $r['typename'] = $types[$r['type']];
    $r['display_icon'] = $r['ismenu'] ? '' : '&nbsp;<i class="icon iconfont icon-shezhi itip" alt="不在导航显示"></i>';
    $r['help'] = '';
    if ($r['url']) {
    $r['url'] = "<a href='" . $r['url'] . "' target='_blank'>访问</a>";
    } else {
    $r['url'] = "<a href='" . Url::build("Category/public_cache") . "'><font color='red'>更新缓存</font></a>";
    }
    $categorys[$r['catid']] = $r;
    }
    }
    $str = "<tr>
    <td width='50' align='center' class='sort'><span alt='可编辑' column_id='\$id' fieldname='gc_sort' nc_type='inline_edit' class='itip editable'>\$listorder</span></td>
    <td width='150' align='center'>\$str_manage</td>
    <td width='60' align='center'>\$id</td>
    <td width='250' align='left'>\$spacer\$catname\$display_icon</td>
    <td width='70' align='center'>\$typename</td>
    <td width='70' align='center'>\$modelname</td>
    <td width='100' align='center' align='center'>\$url</td>
    <td width='100' align='center'>\$help</td>
    </tr>";
    if (!empty($categorys) && is_array($categorys)) {
    $tree = new \Tree();
    $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
    $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
    $tree->init($categorys);
    $categorydata = $tree->get_tree(0, $str);
    } else {
    $categorydata = '';
    }
    $this->assign('categorys', $categorydata);
    return $this->fetch();*/
    }

    //新增栏目
    public function add()
    {
        if ($this->request->isPost()) {

        } else {
            //栏目列表 可以用缓存的方式
            $array = cache("Category");
            /*foreach ($array as $k => $v) {
            $array[$k] = getCategory($v['catid']);
            }*/
            if (!empty($array) && is_array($array)) {
                $tree = new \util\Tree();
                $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
                $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
                $str = "<option value='\$catid' \$selected>\$spacer \$catname</option>";
                $tree->init($array);
                $categorydata = $tree->get_tree(0, $str, $parentid);
            } else {
                $categorydata = '';
            }
            $this->assign("category", $categorydata);
            return $this->fetch();

        }

    }

    //编辑栏目
    public function edit()
    {
        return $this->fetch();

    }

}
