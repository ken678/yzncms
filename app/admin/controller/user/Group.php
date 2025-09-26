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
namespace app\admin\controller\user;

use app\admin\model\user\User as UserModel;
use app\admin\model\user\UserGroup;
use app\common\controller\Backend;

class Group extends Backend
{
    protected $modelValidate = true;
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new UserGroup;
    }

    /**
     * 会员组列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = $this->modelClass->order(["listorder" => "DESC", "id" => "DESC"])->select();
            foreach ($list as $k => $v) {
                //统计会员总数
                $list[$k]['count'] = UserModel::where("group_id", $v['id'])->count('id');
            }
            $result = ["code" => 0, "data" => $list];
            return json($result);
        }
        return $this->fetch();
    }

}
