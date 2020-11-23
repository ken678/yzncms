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
// | 模型管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\Models as ModelsModel;
use app\common\controller\Adminbase;
use think\Db;

class Models extends Adminbase
{
    protected $modelClass = null;
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new ModelsModel;
        //取得当前内容模型模板存放目录
        $this->filepath = TEMPLATE_PATH . (empty(config('theme')) ? "default" : config('theme')) . DS . "cms" . DS;
        //取得栏目频道模板列表
        $this->tp_category = str_replace($this->filepath . DS, '', glob($this->filepath . DS . 'category*'));
        //取得栏目列表模板列表
        $this->tp_list = str_replace($this->filepath . DS, '', glob($this->filepath . DS . 'list*'));
        //取得内容页模板列表
        $this->tp_show = str_replace($this->filepath . DS, '', glob($this->filepath . DS . 'show*'));
        $this->assign("tp_category", $this->tp_category);
        $this->assign("tp_list", $this->tp_list);
        $this->assign("tp_show", $this->tp_show);
    }

    //模型列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->modelClass->where('module', 'cms')->select();
            return json(["code" => 0, "data" => $data]);
        }
        return $this->fetch();
    }

    //添加模型
    public function add()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'Models');
            if (true !== $result) {
                return $this->error($result);
            }
            try {
                $this->modelClass->addModel($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('模型新增成功！', url('index'));
        } else {
            return $this->fetch();
        }
    }

    //模型修改
    public function edit()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'Models');
            if (true !== $result) {
                return $this->error($result);
            }
            try {
                $this->modelClass->editModel($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('模型修改成功！', url('index'));
        } else {
            $id              = $this->request->param('id/d', 0);
            $data            = $this->modelClass->where("id", $id)->find();
            $data['setting'] = unserialize($data['setting']);
            $this->assign("data", $data);
            return $this->fetch();
        }
    }

    //模型删除
    public function del()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        //检查该模型是否已经被使用
        $r = Db::name("category")->where("modelid", $id)->find();
        if ($r) {
            $this->error("该模型使用中，删除栏目后再删除！");
        }
        //这里可以根据缓存获取表名
        $modeldata = $this->modelClass->where("id", $id)->find();
        if (!$modeldata) {
            $this->error("要删除的模型不存在！");
        }
        try {
            $this->modelClass->deleteModel($id);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success("删除成功！", url("index"));
    }

    public function multi()
    {
        cache("Model", null);
        return parent::multi();
    }
}
