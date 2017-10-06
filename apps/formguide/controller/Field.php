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
namespace app\formguide\controller;

use app\common\controller\Adminbase;
use think\Cookie;
use think\Db;
use think\Loader;

/**
 * 表单字段管理
 * @author 御宅男  <530765310@qq.com>
 */
class Field extends Adminbase
{
    public $formid, $fields, $banfie;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->formid = $this->request->param('formid/d', 0);
        //字段类型存放目录
        $this->fields = Loader::model('content/ModelField')->getFieldPath();
        //允许使用的字段列表
        $this->banfie = array("text", "textarea", "box", "number", "editor", "datetime", "downfiles", "image", "images", "omnipotent");
        $this->modelfield = Loader::model("formguide/FormguideField");
    }

    //管理字段
    public function index()
    {
        if (empty($this->formid)) {
            $this->error("缺少参数！");
        }
        $model = Db::name("Model")->where(array("modelid" => $this->formid))->find();
        if (empty($model)) {
            $this->error("该表单不存在！");
        }
// 记录当前列表页的cookie
        Cookie::set('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign("formid", $this->formid);
        $this->assign("modelinfo", $model);
        $this->assign("data", $this->modelfield->getModelField($this->formid));
        return $this->fetch();

    }

    //添加字段
    public function add()
    {
        if ($this->request->isPost()) {
            //模型ID
            $modelid = $this->request->param('modelid/d', 0);
            $post = $this->request->param();
            if (empty($post)) {
                $this->error('数据不能为空！');
            }
            $post['issystem'] = 1;
            if ($this->modelfield->addField($post)) {
                cache('Model_form', null);
                $this->success("添加成功！", url("field/index", array("formid" => $modelid)));
            } else {
                $error = $this->modelfield->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            $modelid = $this->request->param('formid/d', 0);
            if (!$modelid) {
                $this->error("请选择需要添加字段的模型！");
            }
            //获取并过滤可用字段类型
            $all_field = array();
            foreach ($this->modelfield->getFieldTypeList() as $formtype => $name) {
                if (!in_array($formtype, $this->banfie)) {
                    continue;
                }
                $all_field[$formtype] = $name;
            }
            $this->assign("all_field", $all_field);
            $this->assign("formid", $this->formid);
            return $this->fetch();
        }

    }

    //编辑字段
    public function edit()
    {
        //模型ID
        $modelid = $this->request->param('modelid/d', 0);
        //字段ID
        $fieldid = $this->request->param('fieldid/d', 0);
        if (empty($modelid)) {
            $this->error('模型ID不能为空！');
        }
        if (empty($fieldid)) {
            $this->error('字段ID不能为空！');
        }
        if ($this->request->isPost()) {
            $post = $this->request->param();
            if (empty($post)) {
                $this->error('数据不能为空！');
            }
            $post['issystem'] = 1;
            //是否作为基本字段
            $post['isbase'] = 0;
            if ($this->modelfield->editField($post, $fieldid)) {
                $this->success("更新成功！", Cookie::get('__forward__'));
            } else {
                $error = $this->modelfield->getError();
                $this->error($error ? $error : '更新失败！');
            }

        } else {
            //模型信息
            $modedata = Db::name("Model")->where(array("modelid" => $modelid))->find();
            if (empty($modedata)) {
                $this->error('该模型不存在！');
            }
            //字段信息
            $fieldData = $this->modelfield->where(array("fieldid" => $fieldid, "modelid" => $modelid))->find();
            if (empty($fieldData)) {
                $this->error('该字段信息不存在！');
            }
            //字段类型过滤
            $all_field = array();
            foreach ($this->modelfield->getFieldTypeList() as $formtype => $name) {
                if (!in_array($formtype, $this->banfie)) {
                    continue;
                }
                $all_field[$formtype] = $name;
            }
            $this->assign("all_field", $all_field);
            //附加属性
            //$this->assign("form_data", $form_data);
            $this->assign("modelid", $modelid);
            $this->assign("fieldid", $fieldid);
            //$this->assign("setting", $setting);
            //字段信息分配到模板
            $this->assign("data", $fieldData);
            $this->assign("modelinfo", $modedata);
            return $this->fetch();
        }

    }

    //删除字段
    public function delete()
    {
        //字段ID
        $fieldid = $this->request->param('fieldid/d', '');
        if (empty($fieldid)) {
            $this->error('字段ID不能为空！');
        }
        if ($this->modelfield->deleteField($fieldid)) {
            $this->success("字段删除成功！");
        } else {
            $error = $this->modelfield->getError();
            $this->error($error ? $error : "删除字段失败！");
        }
    }

    /**
     * 字段排序
     * @author 御宅男  <530765310@qq.com>
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = $this->modelfield->allowField(true)->where(array('fieldid' => $id))->update(array('listorder' => $listorder));
        cache('ModelField', null);
        if ($rs) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序失败！");
        }
    }

}
