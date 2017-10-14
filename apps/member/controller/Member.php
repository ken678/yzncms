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

/**
 * 会员管理
 */
class Member extends Adminbase
{
    //会员用户组缓存
    protected $groupCache = array();
    //会员模型
    protected $groupsModel = array();
    //会员数据模型
    protected $member = null;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->groupCache = cache("Member_group");
        $this->groupsModel = cache("Model_Member");
    }

    public function manage()
    {
        return $this->fetch();
    }

    //添加会员
    public function add()
    {
        return $this->fetch();

    }

}
