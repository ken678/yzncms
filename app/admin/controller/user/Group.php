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
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

            $count = $this->modelClass
                ->withCount('user')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $data = $this->modelClass
                ->withCount('user')
                ->where($where)
                ->order($sort, $order)
                ->page($page, $limit)
                ->select();
            $result = ["code" => 0, 'count' => $count, 'data' => $data];
            return json($result);
        }
        return $this->fetch();
    }

}
