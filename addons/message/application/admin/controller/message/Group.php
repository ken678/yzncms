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
// | 短信息后台管理
// +----------------------------------------------------------------------
namespace app\admin\controller\message;

use app\admin\model\message\MessageData as MessageDataModel;
use app\admin\model\message\MessageGroup as MessageGroupModel;
use app\common\controller\Adminbase;

class Group extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->groupCache = cache("Member_Group"); //会员模型

        $this->modelClass       = new MessageGroupModel;
        $this->MessageDataModel = new MessageDataModel;
    }
    /**
     * 群发消息管理
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            list($page, $limit, $where) = $this->buildTableParames();
            $_list                      = $this->modelClass->where($where)->page($page, $limit)->select();
            foreach ($_list as $k => $v) {
                $_list[$k]['groupname'] = $this->groupCache[$v['groupid']]['name'];
                $_list[$k]['count']     = MessageDataModel::where('group_message_id', $v['id'])->count();
            }
            $total  = $this->modelClass->where($where)->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 管理按组或角色 群发消息
     */
    public function add()
    {
        foreach ($this->groupCache as $g) {
            $groupCache[$g['id']] = $g['name'];
        }
        $this->assign("Member_group", $groupCache);
        return parent::add();
        /*if ($this->request->isPost()) {
    $data   = $this->request->post('info/a');
    $result = $this->validate($data, 'message_group');
    if (true !== $result) {
    return $this->error($result);
    }
    if ($this->MessageGroupModel->allowField(true)->save($data)) {
    $this->success('发送成功！');
    } else {
    $this->error('发送失败！');
    }

    } else {
    foreach ($this->groupCache as $g) {
    $groupCache[$g['id']] = $g['name'];
    }
    $this->assign("Member_group", $groupCache);
    return $this->fetch();
    }*/

    }
}
