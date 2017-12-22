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
namespace app\announce\controller;

use app\admin\service\User;
use app\announce\model\Announce as AnnounceModel;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 系统公告管理
 * @author 御宅男  <530765310@qq.com>
 */
class Announce extends Adminbase
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->Announce = new AnnounceModel;
    }

    /**
     * [系统公告列表]
     * @author 御宅男  <530765310@qq.com>
     */
    public function index()
    {
        $list = Db::name('Announce')->where('passed', 1)->where('endtime', ['>=', date('Y-m-d')], ['=', '0000-00-00'], 'or')->order(['aid' => 'desc'])->paginate(10);
        $page = $list->render();
        $this->assign('_page', $page);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * [新增系统公告]
     * @author 御宅男  <530765310@qq.com>
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param('announce/a');
            //验证器
            $rule = [
                'title' => 'require',
                'content' => 'require',
            ];
            $msg = [
                'title.require' => '公告标题不得为空',
                'content.require' => '公告内容不得为空',
            ];
            $validate = new \think\Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $userInfo = User::getInstance()->getInfo();
            $data['username'] = $userInfo['username'];
            //不允许结束时间大于开始时间
            if (strtotime($data['endtime']) < strtotime($data['starttime'])) {
                $data['endtime'] = '';
            }
            if ($data['starttime'] == '') {
                $announce['starttime'] = date('Y-m-d');
            }
            //新增
            if ($this->Announce->allowField(true)->save($data)) {
                $this->success('新增成功');
            } else {
                $this->error($this->Announce->getError());
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * [编辑系统公告]
     * @author 御宅男  <530765310@qq.com>
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param('announce/a');
            //验证器
            $rule = [
                'aid' => 'require',
                'title' => 'require',
                'content' => 'require',
            ];
            $msg = [
                'aid.require' => 'ID不得为空',
                'title.require' => '公告标题不得为空',
                'content.require' => '公告内容不得为空',
            ];
            $validate = new \think\Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            //编辑
            if (false !== $this->Announce->isUpdate(true)->allowField(true)->save($data)) {
                $this->success('编辑成功');
            } else {
                $this->error($this->Announce->getError());
            }
        } else {
            $aid = $this->request->param('aid');
            $list = $this->Announce->find($aid);
            $this->assign("list", $list);
            return $this->fetch();
        }

    }

    /**
     * [删除系统公告]
     * @param  integer $ids [description]
     * @author 御宅男  <530765310@qq.com>
     */
    public function delete($ids = 0)
    {
        empty($ids) && $this->error('参数错误！');
        if (is_array($ids)) {
            $map['aid'] = array('in', $ids);
        } elseif (is_numeric($ids)) {
            $map['aid'] = $ids;
        }
        $res = Db::name('Announce')->where($map)->delete();
        if ($res !== false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

}
