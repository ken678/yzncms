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

use app\cms\model\Category as Category_Model;
use app\common\controller\Adminbase;
use think\Db;

class Category extends Adminbase
{

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Category_Model = new Category_Model;
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
            $models = cache('Model');
            $tree = new \util\Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = Db::name('category')->order(array('listorder', 'id' => 'DESC'))->select();
            foreach ($result as $k => $v) {
                $result[$k]['modelname'] = $models[$v['modelid']]['name'];
            }
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
            $data = $this->request->post();
            switch ($data['type']) {
                //单页
                case 1:
                    $scene = 'page';
                    break;
                //列表
                case 2:
                    $scene = 'list';
                    break;
                //链接
                case 3:
                    $scene = 'link';
                    break;
                default:
                    return $this->error('栏目类型错误~');
            }
            $result = $this->validate($data, 'Category.' . $scene);
            if (true !== $result) {
                return $this->error($result);
            }
            $catid = $this->Category_Model->addCategory($data);
            if ($catid) {
                $this->success("添加成功！", url("Category/index"));
            } else {
                $error = $this->Category_Model->getError();
                $this->error($error ? $error : '栏目添加失败！');
            }
        } else {
            $parentid = $this->request->param('parentid/d', 0);
            //输出可用模型
            $models = cache("Model");

            //栏目列表 可以用缓存的方式
            $array = cache("Category");
            foreach ($array as $k => $v) {
                $array[$k] = getCategory($v['id']);
            }
            if (!empty($array) && is_array($array)) {
                $tree = new \util\Tree();
                $tree->icon = array('&nbsp;&nbsp;│ ', '&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;└─ ');
                $tree->nbsp = '&nbsp;&nbsp;';
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

    /**
     * 状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('status/s') === 'true' ? 1 : 0;
        if (Category_Model::update(['status' => $status], ['id' => $id])) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

}
