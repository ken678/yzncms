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
            $result = Db::name('category')->order(array('listorder', 'id' => 'ASC'))->select();
            foreach ($result as $k => $v) {
                if (isset($models[$v['modelid']]['name'])) {
                    $result[$k]['modelname'] = $models[$v['modelid']]['name'];
                } else {
                    $result[$k]['modelname'] = '/';
                }
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
            if (empty($data)) {
                $this->error = '添加栏目数据不能为空！';
                return false;
            }
            switch ($data['type']) {
                //单页
                case 1:
                    $fields = ['parentid', 'catname', 'catdir', 'type', 'image', 'description', 'setting', 'listorder', 'letter', 'status'];
                    $scene = 'page';
                    break;
                //列表
                case 2:
                    $fields = ['parentid', 'catname', 'catdir', 'type', 'modelid', 'image', 'description', 'setting', 'listorder', 'letter', 'status'];
                    $scene = 'list';
                    break;
                //链接
                case 3:
                    $fields = ['parentid', 'catname', 'catdir', 'type', 'image', 'description', 'url', 'listorder', 'letter', 'status'];
                    $scene = 'link';
                    break;
                default:
                    return $this->error('栏目类型错误~');
            }
            $result = $this->validate($data, 'Category.' . $scene);
            if (true !== $result) {
                return $this->error($result);
            }
            $status = $this->Category_Model->addCategory($data, $fields);
            if ($status) {
                $this->success("添加成功！", url("Category/index"));
            } else {
                $error = $this->Category_Model->getError();
                $this->error($error ? $error : '栏目添加失败！');
            }
        } else {
            $parentid = $this->request->param('parentid/d', 0);
            if (!empty($parentid)) {
                $Ca = getCategory($parentid);
                if (empty($Ca)) {
                    $this->error("父栏目不存在！");
                }
            }
            //输出可用模型
            $modelsdata = cache("Model");
            $models = array();
            foreach ($modelsdata as $v) {
                if ($v['status'] == 1) {
                    $models[] = $v;
                }
            }
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

            //$this->assign('parentid_data', $Ca);

            $this->assign("category", $categorydata);
            $this->assign("models", $models);
            return $this->fetch();

        }

    }

    //编辑栏目
    public function edit()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id/d', 0);
            if (empty($id)) {
                $this->error('请选择需要修改的栏目！');
            }
            $data = $this->request->post();
            switch ($data['type']) {
                //单页
                case 1:
                    $data['modelid'] = 0;
                    $data['url'] = '';
                    $scene = 'page';
                    break;
                //列表
                case 2:
                    $data['url'] = '';
                    $scene = 'list';
                    break;
                //链接
                case 3:
                    $data['modelid'] = 0;
                    $scene = 'link';
                    break;
                default:
                    return $this->error('栏目类型错误~');
            }
            $result = $this->validate($data, 'Category.' . $scene);
            if (true !== $result) {
                return $this->error($result);
            }
            $status = $this->Category_Model->editCategory($data, ['parentid', 'catname', 'catdir', 'type', 'modelid', 'image', 'description', 'url', 'setting', 'listorder', 'letter', 'status']);
            if ($status) {
                $this->success("修改成功！", url("Category/index"));
            } else {
                $error = $this->Category_Model->getError();
                $this->error($error ? $error : '栏目修改失败！');
            }

        } else {
            $id = $this->request->param('id/d', 0);
            if (empty($id)) {
                $this->error('请选择需要修改的栏目！');
            }
            $data = getCategory($id);
            $setting = $data['setting'];

            //输出可用模型
            $modelsdata = cache("Model");
            $models = array();
            foreach ($modelsdata as $v) {
                if ($v['status'] == 1) {
                    $models[] = $v;
                }
            }

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
                $categorydata = $tree->get_tree(0, $str, $data['parentid']);
            } else {
                $categorydata = '';
            }

            $this->assign("tp_category", $this->tp_category);
            $this->assign("tp_list", $this->tp_list);
            $this->assign("tp_show", $this->tp_show);
            $this->assign("tp_page", $this->tp_page);

            $this->assign("data", $data);
            $this->assign("setting", $setting);
            $this->assign("category", $categorydata);
            $this->assign("models", $models);
            return $this->fetch();
        }

    }

    //删除栏目
    public function delete()
    {
        $catid = $this->request->param('id/d');
        if (!$catid) {
            $this->error("请指定需要删除的栏目！");
        }
        //这里需增加栏目条数item直接判断
        if (false == $this->Category_Model->deleteCatid($catid)) {
            $this->error("栏目含有信息，无法删除！");
        }
        $this->cache();
        $this->success("栏目删除成功！");
    }

    //更新栏目缓存并修复
    public function public_cache()
    {
        $this->repair();
        $this->cache();
        $this->success("更新缓存成功！", Url("cms/category/index"));

    }

    /**
     * 清除栏目缓存
     */
    protected function cache()
    {
        cache('Category', null);
    }

    /**
     * 修复栏目数据
     */
    private function repair()
    {
        $this->categorys = $categorys = array();
        $data = Db::name('Category')->order('listorder ASC, id ASC')->select();
        if (empty($data)) {
            //$this->cache();
            return true;
        }
        foreach ($data as $v) {
            $categorys[$v['id']] = $v;
        }
        $this->categorys = $categorys;
        if (is_array($categorys)) {
            foreach ($categorys as $catid => $cat) {
                //更新URL
                /*$url = self::buildUrl($cat['type'], $cat['id'], $cat['url']);
                if ($cat['url'] != $url) {
                Category_Model::update(['url' => $url], ['id' => $catid], true);
                }*/
                /*if ($cat['type'] == 3) {
                continue;
                }*/
                $arrparentid = $this->get_arrparentid($catid); //父栏目组
                $setting = unserialize($cat['setting']); //栏目配置
                $arrchildid = $this->get_arrchildid($catid); //子栏目组
                $child = is_numeric($arrchildid) ? 0 : 1; //是否有子栏目
                //检查所有父id 子栏目id 等相关数据是否正确，不正确更新
                if ($categorys[$catid]['arrparentid'] != $arrparentid || $categorys[$catid]['arrchildid'] != $arrchildid || $categorys[$catid]['child'] != $child) {
                    Category_Model::update(['arrparentid' => $arrparentid, 'arrchildid' => $arrchildid, 'child' => $child], ['id' => $catid], true);
                }
                getCategory($catid, '', true);
            }

        }
        return true;
    }

    /**
     *
     * 获取父栏目ID列表
     * @param integer $catid              栏目ID
     * @param array $arrparentid          父目录ID
     * @param integer $n                  查找的层次
     */
    private function get_arrparentid($catid, $arrparentid = '', $n = 1)
    {
        if ($n > 5 || !is_array($this->categorys) || !isset($this->categorys[$catid])) {
            return false;
        }

        $parentid = $this->categorys[$catid]['parentid']; //当前父栏目
        $arrparentid = $arrparentid ? $parentid . ',' . $arrparentid : $parentid; //父栏目组
        if ($parentid) {
            $arrparentid = $this->get_arrparentid($parentid, $arrparentid, ++$n);
        } else {
            $this->categorys[$catid]['arrparentid'] = $arrparentid;
        }
        $parentid = $this->categorys[$catid]['parentid'];
        return $arrparentid;
    }

    /**
     *
     * 获取子栏目ID列表
     * @param $catid 栏目ID
     */
    private function get_arrchildid($catid)
    {
        $arrchildid = $catid;
        if (is_array($this->categorys)) {
            foreach ($this->categorys as $id => $cat) {
                if ($cat['parentid'] && $id != $catid && $cat['parentid'] == $catid) {
                    $arrchildid .= ',' . $this->get_arrchildid($id);
                }
            }
        }
        return $arrchildid;
    }

    /**
     * 排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = Category_Model::update(['listorder' => $listorder], ['id' => $id], true);
        if ($rs) {
            $this->success("排序成功！");
        } else {
            $this->error("排序失败！");
        }
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
            //更新栏目缓存
            cache('Category', null);
            getCategory($id, '', true);
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

}
