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
// | cms管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\ModelField as Model_Field;
use app\cms\model\Page as Page_Model;
use app\common\controller\Adminbase;
use think\Db;

class Cms extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->modelfield = new Model_Field;
    }

    public function index()
    {
        return $this->fetch();
    }

    //栏目信息列表
    public function classlist()
    {
        $catid = $this->request->param('catid/d', 0);
        $limit = $this->request->param('limit/d', 10);
        $page = $this->request->param('page/d', 10);
        if ($this->request->isAjax()) {
            //当前栏目信息
            $catInfo = getCategory($catid);
            if (empty($catInfo)) {
                $this->error('该栏目不存在！');
            }
            //栏目所属模型
            $modelid = $catInfo['modelid'];
            //检查模型是否被禁用
            if (!getModel($modelid, 'status')) {
                $this->error('模型被禁用！');
            }
            $modelCache = cache("Model");
            $tableName = $modelCache[$modelid]['tablename'];
            $total = Db::name(ucwords($tableName))->count();
            $list = Db::name(ucwords($tableName))->page($page, $limit)->where('catid', $catid)->withAttr('updatetime', function ($value, $data) {
                return date('Y-m-d H:i:s', $value);
            })->order(['listorder', 'id' => 'desc'])->select();
            $result = array("code" => 0, "count" => $total, "data" => $list);
            return json($result);
        }
        $this->assign('catid', $catid);
        return $this->fetch();

    }

    //添加栏目
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $catid = intval($data['modelField']['catid']);
            if (empty($catid)) {
                $this->error("请指定栏目ID！");
            }
            $category = getCategory($catid);
            if (empty($category)) {
                $this->error('该栏目不存在！');
            }
            if ($category['type'] == 2) {
                $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
                try {
                    $this->modelfield->addModelData($data['modelField'], $data['modelFieldExt']);
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
            } else if ($category['type'] == 1) {
                $Page_Model = new Page_Model;
                if (!$Page_Model->savePage($data['modelField'])) {
                    $error = $Page_Model->getError();
                    $this->error($error ? $error : '操作失败！');
                }
            }
            $this->success('操作成功！');
        } else {
            $catid = $this->request->param('catid/d', 0);
            $category = getCategory($catid);
            if (empty($category)) {
                $this->error('该栏目不存在！');
            }
            if ($category['type'] == 2) {
                $modelid = $category['modelid'];
                $position = cache('Position');
                $array = array();
                foreach ($position as $_key => $_value) {
                    //如果有设置模型，检查是否有该模型
                    if ($_value['modelid'] && $modelid !== $_value['modelid']) {
                        continue;
                    }
                    //如果设置了模型，又设置了栏目
                    if ($_value['modelid'] && $_value['catid'] && $catid !== $_value['catid']) {
                        continue;
                    }
                    //如果设置了栏目
                    if ($_value['catid'] && $catid !== $_value['catid']) {
                        continue;
                    }
                    $array[$_key] = $_value['name'];
                }
                $fieldList = $this->modelfield->getFieldList($modelid);
                $this->assign([
                    'position' => $array,
                    'catid' => $catid,
                    'fieldList' => $fieldList,
                ]);
                return $this->fetch();
            } else if ($category['type'] == 1) {
                $Page_Model = new Page_Model;
                $info = $Page_Model->getPage($catid);
                $this->assign([
                    'info' => $info,
                    'catid' => $catid,
                ]);
                return $this->fetch('singlepage');
            }

        }
    }

    //编辑信息
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $this->modelfield->editModelData($data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('编辑成功！');

        } else {
            $catid = $this->request->param('catid/d', 0);
            $id = $this->request->param('id/d', 0);
            $category = getCategory($catid);
            if (empty($category)) {
                $this->error('该栏目不存在！');
            }
            if ($category['type'] == 2) {
                $modelid = $category['modelid'];
                $fieldList = $this->modelfield->getFieldList($modelid, $id);
                $position = cache('Position');
                $array = array();
                foreach ($position as $_key => $_value) {
                    //如果有设置模型，检查是否有该模型
                    if ($_value['modelid'] && $modelid !== $_value['modelid']) {
                        continue;
                    }
                    //如果设置了模型，又设置了栏目
                    if ($_value['modelid'] && $_value['catid'] && $catid !== $_value['catid']) {
                        continue;
                    }
                    //如果设置了栏目
                    if ($_value['catid'] && $catid !== $_value['catid']) {
                        continue;
                    }
                    $array[$_key] = $_value['name'];
                }
                //已经推荐
                $posids = model('PositionData')->where(['id' => $id, 'modelid' => $modelid])->column("posid");
                $this->assign([
                    'posids' => $posids,
                    'position' => $array,
                    'catid' => $catid,
                    'fieldList' => $fieldList,
                ]);
                return $this->fetch();
            } else {
                return $this->fetch('singlepage');
            }

        }

    }

    //删除
    public function delete($ids = 0)
    {
        $catid = $this->request->param('catid/d', 0);
        $ids = $this->request->param('ids/a', null);
        if (empty($ids) || !$catid) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        $modelid = getCategory($catid, 'modelid');
        try {
            $this->modelfield->deleteModelData($modelid, $ids);
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }
        $this->success('删除成功！');
    }

    public function panl()
    {
        return "CMS面板";
    }

    //显示栏目菜单列表
    public function public_categorys()
    {
        $json = [];
        $categorys = cache('Category');
        foreach ($categorys as $rs) {
            $rs = getCategory($rs['id']);
            //剔除无子栏目外部链接
            if ($rs['type'] == 3 && $rs['child'] == 0) {
                continue;
            }
            $data = array(
                'id' => $rs['id'],
                'parentid' => $rs['parentid'],
                'catname' => $rs['catname'],
                'type' => $rs['type'],
            );
            //终极栏目
            if ($rs['child'] == 0) {
                $data['target'] = 'right';
                $data['url'] = url('cms/cms/classlist', array('catid' => $rs['id']));
            } else {
                $data['isParent'] = true;
            }
            //单页
            if ($rs['type'] == 1) {
                $data['target'] = 'right';
                $data['url'] = url('cms/cms/add', array('catid' => $rs['id']));
            }
            $json[] = $data;
        }
        $this->assign('json', json_encode($json));
        return $this->fetch();
    }

    /**
     * 排序
     */
    public function listorder()
    {
        $catid = $this->request->param('catid/d', 0);
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $modelid = getCategory($catid, 'modelid');
        $modelCache = cache("Model");
        if (empty($modelCache[$modelid])) {
            return false;
        };
        $tableName = $modelCache[$modelid]['tablename'];
        if (Db::name($tableName)->where('id', $id)->update(['listorder' => $listorder])) {
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
        $id = $this->request->param('id/d', 0);
        $status = $this->request->param('status/s') === 'true' ? 1 : 0;
        $modelid = getCategory($id, 'modelid');
        $modelCache = cache("Model");
        if (empty($modelCache[$modelid])) {
            return false;
        };
        $tableName = $modelCache[$modelid]['tablename'];
        if (Db::name($tableName)->where('id', $id)->update(['status' => $status])) {
            //更新栏目缓存
            cache('Category', null);
            getCategory($id, '', true);
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

}
