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

use app\admin\model\member\Member as MemberModel;
use app\admin\model\message\Message as MessageModel;
use app\common\controller\Adminbase;

class Message extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new MessageModel;
    }

    /**
     * 发消息
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data              = $this->request->post('info/a');
            $data['send_from'] = $this->auth->username;
            $result            = $this->validate($data, 'app\admin\validate\message\Message');
            if (true !== $result) {
                return $this->error($result);
            }
            if (!MemberModel::getByUsername($data['send_to'])) {
                return $this->error('用户不存在');
            }
            if ($this->modelClass->allowField(true)->save($data)) {
                $this->success('发送成功！');
            } else {
                $this->error('发送失败！');
            }

        } else {
            return $this->fetch();
        }
    }

}
