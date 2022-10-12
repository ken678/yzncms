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
// | 会员组管理
// +----------------------------------------------------------------------
namespace app\admin\controller\member;

use app\admin\model\member\Member as MemberModel;
use app\admin\model\member\MemberGroup as MemberGroup;
use app\common\controller\Adminbase;

class Group extends Adminbase
{
    protected $modelValidate = true;
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass  = new MemberGroup;
        $this->MemberModel = new MemberModel;
    }

    /**
     * 会员组列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $_list = $this->modelClass->order(["listorder" => "DESC", "id" => "DESC"])->select();
            foreach ($_list as $k => $v) {
                //统计会员总数
                $_list[$k]['_count'] = $this->MemberModel->where(["groupid" => $v['id']])->count('id');
            }
            $result = array("code" => 0, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 会员组添加
     */
    public function add()
    {
        $this->modelClass->Membergroup_cache();
        return parent::add();
    }

    /**
     * 会员组编辑
     */
    public function edit()
    {
        $id  = $this->request->param('id/d', 0);
        $row = $this->modelClass->get($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if ($this->request->isPost()) {
            $data   = $this->request->post('row/a');
            $result = $this->validate($data, 'app\admin\validate\member\MemberGroup');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->modelClass->groupEdit($data)) {
                //更新缓存
                $this->modelClass->Membergroup_cache();
                $this->success("修改成功！", url("index"));
            } else {
                $this->error("修改失败！");
            }
        } else {
            $this->assign("data", $row);
            return $this->fetch();
        }
    }

    /**
     * 会员组删除
     */
    public function del()
    {
        $groupid = $this->request->param('id/d', 0);
        if (empty($groupid)) {
            $this->error("没有指定需要删除的会员组别！");
        }
        if ($this->modelClass->groupDelete($groupid)) {
            //更新缓存
            $this->modelClass->Membergroup_cache();
            $this->success("删除成功！", url("index"));
        } else {
            $this->error($this->modelClass->getError());
        }
    }

}
