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
namespace app\admin\controller\cms;

use app\admin\model\cms\Models as ModelsModel;
use app\common\controller\Adminbase;
use think\facade\Cache;

class Models extends Adminbase
{
    protected $modelClass    = null;
    protected $modelValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new ModelsModel;
        //取得当前内容模型模板存放目录
        $this->filepath = TEMPLATE_PATH . (empty(config('site.theme')) ? "default" : config('site.theme')) . DS . "cms" . DS;
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

    //模型修改
    public function edit()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'app\admin\validate\cms\Models');
            if (true !== $result) {
                return $this->error($result);
            }
            try {
                $this->modelClass->editModel($data);
                //更新缓存
                cache("Model", null);
                Cache::set('getModel_' . $data['id'], '');
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

    public function multi()
    {
        $id    = $this->request->param('id/d', 0);
        $value = $this->request->param('value/d', 0);
        try {
            $row = $this->modelClass->find($id);
            if (empty($row)) {
                $this->error('数据不存在！');
            }
            $row->status = $value;
            $row->save();
            //更新缓存
            cache("Model", null);
            Cache::set('getModel_' . $id, '');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success("操作成功！");
    }
}
