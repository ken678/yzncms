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

use app\cms\model\ModelField as ModelField;
use app\common\controller\Adminbase;
use think\Db;

class Field extends Adminbase
{
    protected $ext_table = '_data';
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $filepath = APP_PATH . 'admin' . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;
        $custom   = str_replace($filepath . DIRECTORY_SEPARATOR, '', glob($filepath . DIRECTORY_SEPARATOR . 'custom*'));
        $this->assign('custom', $custom);
        $this->modelClass = new ModelField;

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
        $model = Db::name("Model")->where("id", $modelid)->find();
        if (empty($model)) {
            $this->error('该模型不存在！');
        }
        if ($this->request->isAjax()) {
            //根据模型读取字段列表
            $banFields                  = ['id', 'catid', 'did', 'uid'];
            list($page, $limit, $where) = $this->buildTableParames();
            $total                      = $this->modelClass->where($where)->where('modelid', $modelid)->whereNotIn('name', $banFields)->count();
            $data                       = $this->modelClass->where($where)->where('modelid', $modelid)->whereNotIn('name', $banFields)->order('listorder,id')->page($page, $limit)->select();
            $result                     = array("code" => 0, "count" => $total, "data" => $data);
            return json($result);
        }
        $this->assign([
            "modelid" => $modelid,
            "name"    => $model['name'],
        ]);
        return $this->fetch();
    }

    /**
     * 增加字段
     */
    public function add()
    {
        $modelid = $this->request->param('modelid/d', '');
        if (empty($modelid)) {
            $this->error('参数错误！');
        }
        if ($this->request->isPost()) {
            //增加字段
            $data   = $this->request->param();
            $result = $this->validate($data, 'ModelField');
            if (true !== $result) {
                return $this->error($result);
            }
            try {
                $res = $this->modelClass->addField($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('新增成功');
        } else {
            $fieldType = Db::name('field_type')->order('listorder')->column('name,title,default_define,ifstring');
            $modelInfo = Db::name('model')->where('id', $modelid)->find();
            $this->assign(
                [
                    'modelType' => $modelInfo['type'],
                    "modelid"   => $modelid,
                    'fieldType' => $fieldType,
                ]
            );
            return $this->fetch();
        }
    }

    /**
     * 修改字段
     */
    public function edit()
    {
        //字段ID
        $fieldid = $this->request->param('fieldid/d', 0);
        if (empty($fieldid)) {
            $this->error('字段ID不能为空！');
        }
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'ModelField');
            if (true !== $result) {
                return $this->error($result);
            }
            try {
                $this->modelClass->editField($data, $fieldid);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success("更新成功！");
        } else {
            //字段信息
            $fieldData = ModelField::get($fieldid);
            //字段扩展配置
            $fieldData['setting'] = unserialize($fieldData['setting']);
            if (empty($fieldData)) {
                $this->error('该字段信息不存在！');
            }
            //模型信息
            $modedata = Db::name('model')->where('id', $fieldData->getAttr('modelid'))->find();
            if (empty($modedata)) {
                $this->error('该模型不存在！');
            }
            $fieldType = Db::name('field_type')->order('listorder')->column('name,title,default_define,ifstring');
            $this->assign([
                'data'      => $fieldData,
                'fieldid'   => $fieldid,
                'fieldType' => $fieldType,
            ]);
            return $this->fetch();
        }

    }

    /**
     * 删除字段
     */
    public function del()
    {
        //字段ID
        $fieldid = $this->request->param('id/d', '');
        if (empty($fieldid)) {
            $this->error('字段ID不能为空！');
        }
        try {
            $this->modelClass->deleteField($fieldid);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success("字段删除成功！");
    }

    /**
     * 菜单排序
     */
    public function listorder()
    {
        $id        = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs        = $this->modelClass->allowField(['listorder'])->isUpdate(true)->save(['id' => $id, 'listorder' => $listorder]);
        if ($rs) {
            $this->success("菜单排序成功！");
        } else {
            $this->error("菜单排序失败！");
        }
    }

    /**
     * 字段状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('value/d');
        if (ModelField::update(['status' => $status], ['id' => $id])) {
            $this->success("操作成功！");
        } else {
            $this->error('操作失败！');
        }
    }

    public function setsearch()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $ifsearch = $this->request->param('value/d');
        if (ModelField::update(['ifsearch' => $ifsearch], ['id' => $id])) {
            $this->success("操作成功！");
        } else {
            $this->error('操作失败！');
        }
    }

    /**
     * 显示隐藏
     */
    public function setvisible()
    {
        $id = $this->request->param('id/d', 0);
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('value/d');

        $field = ModelField::get($id);
        if ($field->ifrequire && 0 == $status) {
            $this->error("必填字段不可以设置为隐藏！");
        }
        $field->isadd = $status;
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
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('value/d');

        $field = ModelField::get($id);
        if (!$field->isadd && $status) {
            $this->error("隐藏字段不可以设置为必填！");
        }
        $field->ifrequire = $status;
        if ($field->save()) {
            $this->success("设置成功！");
        } else {
            $this->error("设置失败！");
        }
    }

}
