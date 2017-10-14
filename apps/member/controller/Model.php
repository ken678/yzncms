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
namespace app\member\controller;

use app\common\controller\Adminbase;
use think\Loader;

/**
 * 会员模型管理
 */
class Model extends Adminbase
{
    //会员模型
    protected $memberModel = null;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->memberModel = Loader::model('content/Models');
    }

    public function index()
    {
        $data = $this->memberModel->where(array("type" => 2))->order(array("modelid" => "DESC"))->select();
        $this->assign("data", $data);
        return $this->fetch();
    }

    //添加模型
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->param();
            $post['type'] = 2;
            $post['tablename'] = "member_" . $post['tablename'];
            //模型添加验证
            $validate = Loader::validate('Model');
            if (!$validate->scene('add')->check($post)) {
                $this->error($validate->getError());
                return false;
            }
            $modelid = $this->memberModel->allowField(true)->save($post);
            if ($modelid) {
                //创建表
                $this->memberModel->AddModelMember($post['tablename'], $this->memberModel->modelid);
                //更新缓存
                //Loader::model('member/Member')->member_cache();
                $this->success("添加模型成功！", url("Model/index"));
            } else {
                $this->error("添加失败！");
            }
        } else {
            return $this->fetch();
        }
    }

    //编辑模型
    public function edit()
    {
        if ($this->request->isPost()) {
            $post = $this->request->param();
            $modelid = $this->request->param('modelid', 0);
            unset($post['modelid']);
            //模型编辑验证
            $validate = Loader::validate('Model');
            if (!$validate->scene('edit')->check($post)) {
                $this->error($validate->getError());
                return false;
            }
            if ($this->memberModel->where(array('modelid' => $modelid, 'type' => 2))->update($post) !== false) {
                //更新缓存
                //Loader::model('member/Member')->member_cache();
                $this->success("更新模型成功！", url("Model/index"));
            } else {
                $this->error("更新失败！");
            }
        } else {
            $modelid = $this->request->param('modelid', 0);
            $data = $this->memberModel->where(array("modelid" => $modelid, 'type' => 2))->find();
            if (empty($data)) {
                $this->error('该会员模型不存在！');
            }
            $this->assign("data", $data);
            return $this->fetch();
        }

    }

    //删除模型
    public function delete()
    {
        $modelid = $this->request->param('modelid', 0);
        //这里可以根据缓存获取表名
        $modeldata = $this->memberModel->where(array("modelid" => $modelid, 'type' => 2))->find();
        if (empty($modeldata)) {
            $this->error("要删除的模型不存在！");
        }
        if ($this->memberModel->deleteModel($modeldata['modelid'])) {
            //更新缓存
            //Loader::model('member/Member')->member_cache();
            $this->success("删除成功！", url("Model/index"));
        } else {
            $this->error("删除失败！");
        }

    }

    //模型移动
    public function move()
    {

    }

}
