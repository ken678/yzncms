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
// | 字段管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\ModelField;
use app\common\controller\Adminbase;
use think\Db;

class Field extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelfield = new ModelField;

    }

    /**
     * 显示字段列表
     */
    public function index()
    {
        $modelid = $this->request->param('id/d', '');
        if (empty($modelid)) {
            $this->error('参数错误！');
        }
        $model = Db::name("Model")->field(true)->where(array("id" => $modelid))->find();
        if (empty($model)) {
            $this->error('该模型不存在！');
        }
        //根据模型读取字段列表
        $banFields = ['id', 'did', 'status', 'uid', 'posid'];
        $data = $this->modelfield->where('modelid', $modelid)->whereNotIn('name', $banFields)->order('listorder,id')->select()->withAttr('create_time', function ($value, $data) {
            return date('Y-m-d H:i:s', $value);
        });
        if ($this->request->isAjax()) {
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        $this->assign("modelid", $modelid);
        return $this->fetch();
    }

    /**
     * 字段状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        $status = $this->request->param('status/d');
        $this->modelfield->where((array('id' => $id)))->update(array('status' => $status));
        $this->success("操作成功！");
    }

    public function setsearch()
    {
        $id = $this->request->param('id/d');
        $search = $this->request->param('search/d');
        $this->modelfield->where((array('id' => $id)))->update(array('ifsearch' => $search));
        $this->success("操作成功！");
    }

    /**
     * 菜单排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = $this->modelfield->allowField(['listorder'])->isUpdate(true)->save(['id' => $id, 'listorder' => $listorder]);
        if ($rs) {
            $this->success("菜单排序成功！");
        } else {
            $this->error("菜单排序失败！");
        }
    }

    /**
     * 显示隐藏
     */
    public function setvisible()
    {
        $id = $this->request->param('id/d', 0);
        $ifvisible = $this->request->param('visible/d', 0);

        $field = $this->modelfield->get($id);
        if ($field->ifrequire && 0 == $ifvisible) {
            $this->error("必填字段不可以设置为隐藏！");
        }
        $field->ifeditable = $ifvisible;
        if ($field->save()) {
            $this->success("设置成功！");
        } else {
            $this->error("设置失败！");
        }
    }

    /**
     * 必须
     */
    public function setrequire()
    {
        $id = $this->request->param('id/d', 0);
        $ifrequire = $this->request->param('require/d', 0);

        $field = $this->modelfield->get($id);
        if (!$field->ifeditable && $ifrequire) {
            $this->error("隐藏字段不可以设置为必填！");
        }
        $field->ifrequire = $ifrequire;
        if ($field->save()) {
            $this->success("设置成功！");
        } else {
            $this->error("设置失败！");
        }
    }

}
