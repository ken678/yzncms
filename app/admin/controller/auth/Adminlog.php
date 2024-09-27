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
// | 日志首页
// +----------------------------------------------------------------------
namespace app\admin\controller\auth;

use app\admin\model\Adminlog as AdminlogModel;
use app\common\controller\Backend;

class Adminlog extends Backend
{
    protected $modelClass       = null;
    protected $childrenAdminIds = [];

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass       = new AdminlogModel;
        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
    }

    //删除一个月前的操作日志
    public function deletelog()
    {
        $isAdministrator  = $this->auth->isAdministrator();
        $childrenAdminIds = $this->childrenAdminIds;
        $where            = [];
        if (!$isAdministrator) {
            $where[] = ['admin_id', 'in', $childrenAdminIds];
        }
        AdminlogModel::where('create_time', '<= time', time() - (86400 * 30))->where($where)->delete();
        $this->success("删除日志成功！");
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();
            $isAdministrator                       = $this->auth->isAdministrator();
            $childrenAdminIds                      = $this->childrenAdminIds;
            $res                                   = $this->modelClass
                ->where($where)
                ->where(function ($query) use ($isAdministrator, $childrenAdminIds) {
                    if (!$isAdministrator) {
                        $query->where('admin_id', 'in', $childrenAdminIds);
                    }
                })
                ->order($sort, $order)
                ->paginate($limit);

            $result = ["code" => 0, 'count' => $res->total(), 'data' => $res->items()];
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 详情
     */
    public function detail($id)
    {
        $row = $this->modelClass->find($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if (!$this->auth->isAdministrator()) {
            if (!$row['admin_id'] || !in_array($row['admin_id'], $this->childrenAdminIds)) {
                $this->error('你没有权限访问');
            }
        }
        $this->assign("row", $row->toArray());
        return $this->fetch();
    }

    //添加
    public function add()
    {
        $this->error();
    }

    //编辑
    public function edit()
    {
        $this->error();
    }

    //批量更新
    public function multi()
    {
        // 管理员禁止批量操作
        $this->error();
    }

}
