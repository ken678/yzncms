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
use \think\Db;
use \think\Url;
use \think\Request;
use \think\Loader;
use app\common\controller\Adminbase;
/**
 * 后台栏目管理
 */
class Category extends Adminbase
{
	/**
	 * 栏目列表
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
        		$r['str_manage'] = '';
                $r['str_manage'] .= '<a class="btn red" href="javascript:if(confirm(\'您确定要删除吗?\')){location.href=\'' . Url::build("Category/delete", array("catid" => $r['catid'])) . '\'};"><i class="fa fa-trash-o"></i>删除</a>';
                $r['str_manage'] .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';
        		if ($r['type'] != 2) {
                    if ($r['type'] == 1) {
                        $r['str_manage'] .= '<li><a href="' . Url::build("Category/singlepage", array("parentid" => $r['catid'])) . '">添加下级栏目</a></li>';
                    } else {
                        $r['str_manage'] .= '<li><a href="' . Url::build("Category/add", array("parentid" => $r['catid'])) . '">添加下级栏目</a></li>';
                    }
                }
                $r['str_manage'] .= '<li><a href="' . Url::build("Category/edit", array("catid" => $r['catid'])) . '">修改栏目信息</a></li>';
                $r['str_manage'] .= '</ul></span>';

                $r['modelname'] = $models[$r['modelid']]['name'];
                $r['typename'] = $types[$r['type']];
                $r['help'] = '';
                if ($r['url']) {
                    $r['url'] = "<a href='" . $r['url'] . "' target='_blank'>访问</a>";
                } else {
                    $r['url'] = "<a href='" . Url::build("Category/public_cache") . "'><font color='red'>更新缓存</font></a>";
                }
                $categorys[$r['catid']] = $r;
        	}
        }
		$str = "<tr data-id='\$id'>
                <td class='sign'><i class='ico-check'></i></td>
                <td class='sort'><span title='可编辑' column_id='\$id' fieldname='gc_sort' nc_type='inline_edit' class='editable'>\$listorder</span></td>
                <td class='handle'>\$str_manage</td>
                <td>\$id</td>
                <td>\$spacer\$catname</td>
                <td>\$typename</td>
        		<td>\$modelname</td>
        		<td align='center'>\$url</td>
                <td>\$help</td>
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

    //删除栏目
    public function delete()
    {
        $catid = Request::instance()->param('catid/d');
        if (!$catid) {
            $this->error("请指定需要删除的栏目！");
        }
        //这里需增加栏目条数item直接判断
        if (false == Loader::model("Content/Category")->deleteCatid($catid)) {
            $this->error("栏目含有信息，无法删除！");
        }
        $this->success("栏目删除成功！", Url::build("Category/public_cache"));
    }

    /**
     * 栏目排序
     */
    public function listorder()
    {
      $id = Request::instance()->param('id/d',0);
      $listorder = Request::instance()->param('value/d',0);
      Db::name('category')->update(['listorder' => $listorder,'catid'=>$id]);
      //删除缓存
      getCategory($id,'',true);
      $this->cache();
      $return = 'true';
      exit(json_encode(array('result'=>$return)));
    }

    /**
     * 清除栏目缓存
     */
    protected function cache() {
        cache('Category', NULL);
    }

}
