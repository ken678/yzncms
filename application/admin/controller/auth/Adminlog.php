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
use app\common\controller\Adminbase;

class Adminlog extends Adminbase
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
        AdminlogModel::where('create_time', '<= time', time() - (86400 * 30))->delete();
        $this->success("删除日志成功！");
    }

    /**
     * 详情
     */
    public function detail($id)
    {
        $row = $this->modelClass->get($id);
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
