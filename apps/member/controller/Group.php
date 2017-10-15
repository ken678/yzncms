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
use app\member\model\Member;
use app\member\model\MemberGroup;

/**
 * 会员组管理
 */
class Group extends Adminbase
{
    //会员用户组模型
    protected $memberGroupModel = null;
    //会员数据模型
    protected $member = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->memberGroupModel = new MemberGroup;
    }

    //会员组管理
    public function index()
    {
        $this->member = new Member;
        $data = $this->memberGroupModel->order(array("sort" => "ASC", "groupid" => "DESC"))->select();
        if ($data) {
            $data = collection($data)->toArray();
            foreach ($data as $k => $v) {
                //统计会员总数
                $data[$k]['_count'] = $this->member->where(array("groupid" => $v['groupid']))->count('uid');
            }
        } else {
            $data = array();
        }
        $this->assign("data", $data);
        return $this->fetch();

    }

}
