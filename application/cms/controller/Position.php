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
// | 推荐位管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\Position as Position_Model;
use app\cms\model\PositionData as PositionData_Model;
use app\common\controller\Adminbase;
use think\Db;

class Position extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Position_Model = new Position_Model;
    }

    /**
     * 推荐位列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = Db::name('Position')->order(array('listorder' => 'ASC', 'id' => 'DESC'))
                ->withAttr('modelid', function ($value, $data) {
                    if ($data['modelid']) {
                        return getModel($data['modelid'], 'name');
                    } else {
                        return '不限模型';
                    }
                })->withAttr('catid', function ($value, $data) {
                if ($data['catid']) {
                    return getCategory($data['catid'], 'catname');
                } else {
                    return '不限栏目';
                }
            })->withAttr('create_time', function ($value, $data) {
                return date('Y-m-d H:i:s', $value);
            })->select();
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 推荐位信息管理
     */
    public function item()
    {
        $limit = $this->request->param('limit/d', 10);
        $page = $this->request->param('page/d', 1);
        $posid = $this->request->param('id/d', 0);
        if ($this->request->isAjax()) {
            $data = model('PositionData')->where(['posid' => $posid])->page($page, $limit)->order(["id" => "DESC"])->select();
            foreach ($data as $k => $v) {
                $data[$k]['data'] = model('ModelField')->getDataInfo($v['modelid'], "id='" . $v['id'] . "'", false);
            }
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        $this->assign('posid', $posid);
        return $this->fetch();

    }

    /**
     * 推荐位添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Position');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Position_Model->positionAdd($data)) {
                $this->success("添加成功！", url('cms/position/index'));
            } else {
                $this->error($this->Position_Model->getError());
            }

        } else {
            $Model = cache('Model');
            $modelinfo = [];
            foreach ($Model as $k => $v) {
                if ($v['type'] == 2) {
                    $modelinfo[$v['id']] = $v['name'];
                }
            }
            $this->assign('modelinfo', $modelinfo);
            return $this->fetch();
        }
    }

    /**
     * 推荐位编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Position');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Position_Model->positionSave($data)) {
                $this->success("编辑成功！", url('cms/position/index'));
            } else {
                $this->error($this->Position_Model->getError());
            }

        } else {
            $posid = $this->request->param('id/d', 0);
            $data = Position_Model::get($posid);
            if (!$data) {
                $this->error('该推荐位不存在！');
            }
            $Model = cache('Model');
            $modelinfo = [];
            foreach ($Model as $k => $v) {
                if ($v['type'] == 2) {
                    $modelinfo[$v['id']] = $v['name'];
                }
            }
            $this->assign('data', $data);
            $this->assign('modelinfo', $modelinfo);
            return $this->fetch();
        }

    }

    /**
     * 推荐位排序
     */
    public function listorder()
    {
        $posid = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);

        $rs = PositionData_Model::update(['listorder' => $listorder], ['posid' => $posid], true);
        if ($rs) {
            $this->success("排序成功！");
        } else {
            $this->error("排序失败！");
        }
    }

    /**
     * 推荐位删除
     */
    public function delete()
    {
        $posid = $this->request->param('id/d', 0);
        if ($this->Position_Model->positionDel($posid)) {
            $this->success('删除成功！', url('cms/position/index'));
        } else {
            $this->error($this->Position_Model->getError() ?: '删除失败');
        }
    }

    //动态加载推荐位栏目
    public function public_category_load()
    {
        $modelid = $this->request->param('modelid/d', 0);
        echo \util\Form::select_category('', 'name=catid', '', $modelid);
    }

}
