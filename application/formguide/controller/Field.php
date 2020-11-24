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
// | 表单字段后台管理
// +----------------------------------------------------------------------
namespace app\formguide\controller;

use app\common\controller\Adminbase;
use app\formguide\model\ModelField as ModelField;
use think\Db;

class Field extends AdminBase
{
    public $fields, $banfie;
    protected $modelClass = null;
    //初始化
    protected function initialize()
    {
        parent::initialize();
        //允许使用的字段列表
        $this->banfie     = array("text", "checkbox", "textarea", "radio", "select", "image", "number", "Ueditor", "color", "file");
        $this->modelClass = new ModelField;
    }

    //首页
    public function index()
    {
        $fieldid = $this->request->param('id/d', 0);
        if ($this->request->isAjax()) {
            list($page, $limit, $where) = $this->buildTableParames();
            $order                      = $this->request->param("order/s", "DESC");
            $sort                       = $this->request->param("sort", 'id');
            $count                      = $this->modelClass->where($where)->where(['modelid' => $fieldid])->count();
            $data                       = $this->modelClass->where($where)->where(['modelid' => $fieldid])->page($page, $limit)->order($sort, $order)->select();
            return json(["code" => 0, 'count' => $count, "data" => $data]);
        } else {
            $this->assign("id", $fieldid);
            return $this->fetch();
        }
    }

    //添加
    public function add()
    {
        if ($this->request->isPost()) {
            //增加字段
            $data   = $this->request->post();
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
            $fieldid   = $this->request->param('id/d', 0);
            $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,default_define,ifoption,ifstring');
            $modelInfo = Db::name('model')->where('id', $fieldid)->find();
            $this->assign(
                [
                    'modelType' => $modelInfo['type'],
                    "modelid"   => $fieldid,
                    'fieldType' => $fieldType,
                ]
            );
            return $this->fetch();
        }

    }

    //编辑
    public function edit()
    {
        $fieldid = $this->request->param('id/d', 0);
        if ($this->request->isPost()) {
            $data   = $this->request->post();
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
            $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,default_define,ifoption,ifstring');
            $this->assign([
                'data'      => $fieldData,
                'fieldid'   => $fieldid,
                'fieldType' => $fieldType,
            ]);
            return $this->fetch();
        }
    }

    //删除
    public function del()
    {
        $fieldid = $this->request->param('id/d', 0);
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
}
