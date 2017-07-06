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
namespace app\content\controller;
use app\common\controller\Adminbase;

class Category extends Adminbase
{
	/**
	 * 管理栏目首页
	 */
    public function index()
    {
        $models = cache('Model');
        $result = cache('Category');
        $categorys = array();
        $types = array(0 => '内部栏目', 1 => '<font color="blue">单网页</font>', 2 => '<font color="red">外部链接</font>');
        if (!empty($result)) {
        	foreach($result as $r) {
        		$r = getCategory($r['catid']);
        		$r['modelname'] = $models[$r['modelid']]['name'];
        		$r['str_manage'] = '';
        		if ($r['type'] != 2) {
                    if ($r['type'] == 1) {
                        $r['str_manage'] .= '<a href="' . url("Category/singlepage", array("parentid" => $r['catid'])) . '">添加子栏目</a> | ';
                    } else {
                        $r['str_manage'] .= '<a href="' . url("Category/add", array("parentid" => $r['catid'])) . '">添加子栏目</a> | ';
                    }
                }
                $r['typename'] = $types[$r['type']];
                $r['help'] = '';
                $r['str_manage'] .= '<a href="' . url("Category/edit", array("catid" => $r['catid'])) . '">修改</a> | <a class="J_ajax_del" href="' . url("Category/delete", array("catid" => $r['catid'])) . '">删除</a>';

                if ($r['url']) {
                    $r['url'] = "<a href='" . $r['url'] . "' target='_blank'>访问</a>";
                } else {
                    $r['url'] = "<a href='" . url("Category/public_cache") . "'><font color='red'>更新缓存</font></a>";
                }
                $categorys[$r['catid']] = $r;
        	}
        }
		$str = "<tr data-id='\$id'>
                <td class='sign'><i class='ico-check'></i></td>
                <td class='sort'><span title='可编辑' column_id='\$id' fieldname='gc_sort' nc_type='inline_edit' class='editable'>\$listorder</span></td>
                <td>\$id</td>
                <td>\$spacer\$catname</td>
                <td>\$typename</td>
        		<td>\$modelname</td>
        		<td align='center'>\$url</td>
                <td>\$help</td>
        		<td align='center' >\$str_manage</td>
                <td></td>
        		</tr>";
		if (!empty($categorys) && is_array($categorys)) {
			$tree = new \Tree();
		    $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		    $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		    $tree->init($categorys);
		    $categorydata = $tree->get_tree(0, $str);
		} else {
		    $categorydata = '';
		}
        $this->assign('categorys', $categorydata);
        return $this->fetch();
    }

}
