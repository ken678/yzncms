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

use app\cms\model\Models as Models_Model;
use app\common\controller\Adminbase;
use think\Db;

class Models extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Models = new Models_Model;
    }

    /**
     * 模型列表
     */
    public function index()
    {

        if ($this->request->isAjax()) {
            $data = $this->Models->where(['module' => 'cms'])->withAttr('setting', function ($value, $data) {
                return unserialize($value);
            })->select();
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 添加模型
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $result = $this->validate($data, 'Models');
            if (true !== $result) {
                return $this->error($result);
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
     * 模型修改
     * @author 御宅男  <530765310@qq.com>
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $result = $this->validate($data, 'Models');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Models->editModel($data)) {
                $this->success('模型修改成功！', url('index'));
            } else {
                $error = $this->Models->getError();
                $this->error($error ? $error : '修改失败！');
            }
        } else {
            $id = $this->request->param('id/d', 0);
            $data = $this->Models->where(array("id" => $id))->find();
            $data['setting'] = unserialize($data['setting']);
            $this->assign("data", $data);
            return $this->fetch();
        }
    }

    /**
     * 模型删除
     */
    public function delete()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        //检查该模型是否已经被使用
        $r = Db::name("category")->where(array("modelid" => $id))->find();
        if ($r) {
            $this->error("该模型使用中，删除栏目后再删除！");
        }
        //这里可以根据缓存获取表名
        $modeldata = $this->Models->where(array("id" => $id))->find();
        if (!$modeldata) {
            $this->error("要删除的模型不存在！");
        }
        if ($this->Models->deleteModel($id)) {
            $this->success("删除成功！", url("index"));
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     * 模型状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        cache("Model", null);
        $status = $this->request->param('status/d');
        if (Models_Model::update(['status' => $status], ['id' => $id])) {
            $this->success("操作成功！");
        } else {
            $this->error('操作失败！');
        }

    }

    /**
     * 投稿状态
     */
    /*public function setSub()
{
$id = $this->request->param('id/d');
empty($id) && $this->error('参数不能为空！');
cache("Model", null);
$ifsub = $this->request->param('ifsub/s') === 'true' ? 1 : 0;
if (Models_Model::update(['ifsub' => $ifsub], ['id' => $id])) {
$this->success("操作成功！");
} else {
$this->error('操作失败！');
}
}*/

}
