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
namespace app\admin\controller\user;

use app\admin\model\user\User as UserModel;
use app\admin\model\user\UserGroup;
use app\common\controller\Backend;
use app\common\library\Auth;
use think\exception\ValidateException;

class User extends Backend
{
    protected $searchFields = 'id,username,nickname';
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new UserModel;
        $this->assign('groupCache', UserGroup::column('name', 'id'));
    }

    /**
     * 会员列表
     */
    public function index()
    {
        $this->relationSearch = true;
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();
            $list                                  = $this->modelClass
                ->withJoin(['group' => ['id', 'name']])
                ->where($where)
                ->page($page, $limit)
                ->select();
            $total = $this->modelClass
                ->withJoin(['group'])
                ->where($where)
                ->order($sort, $order)
                ->count();
            $result = ["code" => 0, "count" => $total, "data" => $list];
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
            $list                       = $this->modelClass
                ->where($where)
                ->where('status', '<>', 1)
                ->page($page, $limit)->select();
            $total = $this->modelClass
                ->where($where)
                ->where('status', '<>', 1)
                ->count();
            $result = ["code" => 0, "count" => $total, "data" => $list];
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
            $params = $this->request->post("row/a");

            try {
                $this->validate($params, 'app\admin\validate\user\User');
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            }

            $params['overduedate'] = $params['overduedate'] ? strtotime($params['overduedate']) : null;
            if (Auth::instance()->register($params['username'], $params['password'], $params['email'], $params['mobile'], $params)) {
                $this->success("添加会员成功！", url("index"));
            } else {
                $this->error(Auth::instance()->getError() ?: '添加会员失败！');
            }
        } else {

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
            $params = $this->request->post("row/a");

            try {
                $this->validate($params, 'app\admin\validate\user\User.edit');
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            }

            //获取用户信息
            $row = UserModel::find($userid);
            if (empty($row)) {
                $this->error('该会员不存在！');
            }
            $row->save($params);
            $this->success("更新成功！", url("index"));

        } else {
            $userid = $this->request->param('id/d', 0);
            $data   = $this->modelClass->find($userid);
            if (empty($data)) {
                $this->error("该会员不存在！");
            }
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
            $ids = [0 => $ids];
        }
        foreach ($ids as $uid) {
            Auth::instance()->delete($uid);
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
            $ids = [0 => $ids];
        }
        foreach ($ids as $uid) {
            $info = UserModel::where('id', $uid)->update(['status' => 1]);
        }
        $this->success("审核成功！");
    }

}
