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
namespace app\member\controller;

use app\common\controller\Adminbase;
use app\member\model\Member as Member_Model;
use app\member\model\MemberGroup as Member_Group;

class Group extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->Member_Group = new Member_Group;
        $this->Member_Model = new Member_Model;
    }

    /**
     * 会员组列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $_list = $this->Member_Group->order(["listorder" => "ASC", "id" => "DESC"])->select();
            foreach ($_list as $k => $v) {
                //统计会员总数
                $_list[$k]['_count'] = $this->Member_Model->where(["groupid" => $v['id']])->count('id');
            }
            $result = array("code" => 0, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

}
