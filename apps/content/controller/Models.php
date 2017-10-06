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
namespace app\content\controller;

use app\common\controller\Adminbase;
use app\content\model\Models as M_models;
use think\Cookie;
use think\Db;

/**
 * 后台模型管理
 * @author 御宅男  <530765310@qq.com>
 */
class Models extends Adminbase
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->Models = new M_models;
    }
    /**
     * 模型列表首页
     * @author 御宅男  <530765310@qq.com>
     */
    public function index()
    {
        $data = $this->Models->where(array("type" => 0))->select();
        // 记录当前列表页的cookie
        Cookie::set('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign("data", $data);
        return $this->fetch();
    }

    /**
     * 模型修改
     * @author 御宅男  <530765310@qq.com>
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (empty($data)) {
                $this->error('提交数据不能为空！');
            }
            if ($this->Models->editModel($data)) {
                $this->success('模型修改成功！', url('index'));
            } else {
                $error = $this->Models->getError();
                $this->error($error ? $error : '修改失败！');
            }
        } else {
            $modelid = $this->request->param('modelid/d', 0);
            $data = $this->Models->where(array("modelid" => $modelid))->find();
            $this->assign("data", $data);
            return $this->fetch();
        }
    }

    /**
     * 添加模型
     * @author 御宅男  <530765310@qq.com>
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (empty($data)) {
                $this->error('提交数据不能为空！');
            }
            if ($this->Models->addModel($data)) {
                $this->success("添加模型成功！", url('models/index'));
            } else {
                $error = $this->Models->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 模型删除
     * @author 御宅男  <530765310@qq.com>
     */
    public function delete()
    {
        $modelid = $this->request->param('modelid/d');
        empty($modelid) && $this->error('参数不能为空！');
        //检查该模型是否已经被使用
        $r = Db::name("Category")->where(array("modelid" => $modelid))->find();
        if ($r) {
            $this->error("该模型使用中，删除栏目后再删除！");
        }
        //这里可以根据缓存获取表名
        $modeldata = $this->Models->where(array("modelid" => $modelid))->find();
        if (!$modeldata) {
            $this->error("要删除的模型不存在！");
        }
        if ($this->Models->deleteModel($modelid)) {
            $this->success("删除成功！", url("index"));
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     * 模型禁用
     * @author 御宅男  <530765310@qq.com>
     */
    public function disabled()
    {
        $modelid = $this->request->param('modelid/d');
        empty($modelid) && $this->error('参数不能为空！');
        $r = $this->Models->where((array('modelid' => $modelid)))->value('disabled');
        $status = $r == '1' ? '0' : '1';
        $this->Models->where((array('modelid' => $modelid)))->update(array('disabled' => $status));
        $this->success("操作成功！");
    }

}
