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

    //初始化
    protected function initialize()
    {
        parent::initialize();
        //取得当前内容模型模板存放目录
        $this->filepath = TEMPLATE_PATH . (empty(config('theme')) ? "default" : config('theme')) . DIRECTORY_SEPARATOR . "cms" . DIRECTORY_SEPARATOR;
        //取得栏目频道模板列表
        $this->tp_category = str_replace($this->filepath . DIRECTORY_SEPARATOR, '', glob($this->filepath . DIRECTORY_SEPARATOR . 'category*'));
        //取得栏目列表模板列表
        $this->tp_list = str_replace($this->filepath . DIRECTORY_SEPARATOR, '', glob($this->filepath . DIRECTORY_SEPARATOR . 'list*'));
        //取得内容页模板列表
        $this->tp_show = str_replace($this->filepath . DIRECTORY_SEPARATOR, '', glob($this->filepath . DIRECTORY_SEPARATOR . 'show*'));
        //取得单页模板
        $this->tp_page = str_replace($this->filepath . DIRECTORY_SEPARATOR, '', glob($this->filepath . DIRECTORY_SEPARATOR . 'page*'));
    }

    //栏目列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $tree = new \util\Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = Db::name('category')->order(array('listorder', 'id' => 'DESC'))->select();
            $tree->init($result);
            $_list = $tree->getTreeList($tree->getTreeArray(0), 'catname');
            $total = count($_list);
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    //新增栏目
    public function add()
    {
        if ($this->request->isPost()) {
            $Category = model("cms/Category");
            $catid = $Category->addCategory($this->request->post());
            if ($catid) {
                $this->success("添加成功！", url("Category/index"));
            } else {
                $error = $Category->getError();
                $this->error($error ? $error : '栏目添加失败！');
            }
        } else {
            //输出可用模型
            $models = cache("Model");

            //栏目列表 可以用缓存的方式
            $array = cache("Category");
            /*foreach ($array as $k => $v) {
            $array[$k] = getCategory($v['catid']);
            }*/
            if (!empty($array) && is_array($array)) {
                $tree = new \util\Tree();
                $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
                $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
                $str = "<option value='\$id' \$selected>\$spacer \$catname</option>";
                $tree->init($array);
                $categorydata = $tree->get_tree(0, $str, $parentid);
            } else {
                $categorydata = '';
            }

            $this->assign("tp_category", $this->tp_category);
            $this->assign("tp_list", $this->tp_list);
            $this->assign("tp_show", $this->tp_show);
            $this->assign("tp_page", $this->tp_page);

            $this->assign("category", $categorydata);
            $this->assign("models", $models);
            return $this->fetch();

        }

    }

    //编辑栏目
    public function edit()
    {
        return $this->fetch();

    }

}
