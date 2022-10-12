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
// | 会员管理
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\common\controller\Adminbase;
use app\member\model\Member as Member_Model;
use app\member\service\User;

class Member extends Adminbase
{
    protected $searchFields = 'id,username,nickname';
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass  = new Member_Model;
        $this->UserService = User::instance();
        $this->groupCache  = cache("Member_Group"); //会员模型
    }

    /**
     * 会员列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            list($page, $limit, $where) = $this->buildTableParames();
            $_list                      = $this->modelClass->where($where)->where('status', 1)->page($page, $limit)->select();
            $total                      = $this->modelClass->where($where)->where('status', 1)->count();
            $result                     = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 审核会员
     */
    public function userverify()
    {
        if ($this->request->isAjax()) {
            list($page, $limit, $where) = $this->buildTableParames();
            $_list                      = $this->modelClass->where($where)->where('status', '<>', 1)->page($page, $limit)->select();
            $total                      = $this->modelClass->where($where)->where('status', '<>', 1)->count();
            $result                     = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);

        }
        return $this->fetch();
    }

    /**
     * 会员增加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'member');
            if (true !== $result) {
                return $this->error($result);
            }
            $data['overduedate'] = strtotime($data['overduedate']);
            if ($this->UserService->userRegister($data['username'], $data['password'], $data['email'], $data['mobile'], $data)) {
                $this->success("添加会员成功！", url("member/index"));
            } else {
                //$this->UserService->delete($memberinfo['userid']);
                $this->error($this->UserService->getError() ?: '添加会员失败！');
            }
        } else {
            foreach ($this->groupCache as $_key => $_value) {
                $groupCache[$_key] = $_value['name'];
            }
            $this->assign('groupCache', $groupCache);
            return $this->fetch();
        }
    }

    /**
     * 会员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $userid = $this->request->param('id/d', 0);
            $data   = $this->request->post();
            $result = $this->validate($data, 'member.edit');
            if (true !== $result) {
                return $this->error($result);
            }
            //获取用户信息
            $userinfo = Member_Model::get($userid);
            if (empty($userinfo)) {
                $this->error('该会员不存在！');
            }
            //修改基本资料
            if ($userinfo['username'] != $data['username'] || !empty($data['password']) || $userinfo['email'] != $data['email']) {
                $res = $this->modelClass->userEdit($userinfo['username'], '', $data['password'], $data['email'], 1);
                if (!$res) {
                    $this->error($this->modelClass->getError());
                }
            }
            unset($data['username'], $data['password'], $data['email']);
            $data['overduedate'] = strtotime($data['overduedate']);
            //更新除基本资料外的其他信息
            if (false === $this->modelClass->allowField(true)->save($data, ['id' => $userid])) {
                $this->error('更新失败！');
            }
            $this->success("更新成功！", url("member/index"));

        } else {
            $userid = $this->request->param('id/d', 0);
            $data   = $this->modelClass->where(["id" => $userid])->withAttr('overduedate', function ($value, $data) {
                return date('Y-m-d H:i:s', $value);
            })->find();
            if (empty($data)) {
                $this->error("该会员不存在！");
            }
            foreach ($this->groupCache as $_key => $_value) {
                $groupCache[$_key] = $_value['name'];
            }
            $this->assign('groupCache', $groupCache);
            $this->assign("data", $data);
            return $this->fetch();
        }
    }

    /**
     * 会员删除
     */
    public function del()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('请选择需要删除的会员！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        foreach ($ids as $uid) {
            $this->UserService->delete($uid);
        }
        $this->success("删除成功！");

    }

    /**
     * 审核会员
     */
    public function pass()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('请选择需要审核的会员！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        foreach ($ids as $uid) {
            $info = Member_Model::where('id', $uid)->update(['status' => 1]);
        }
        $this->success("审核成功！");
    }

}
