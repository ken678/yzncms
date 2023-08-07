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
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AdminlogModel;
    }

    //删除一个月前的操作日志
    public function deletelog()
    {
        AdminlogModel::where('create_time', '<= time', time() - (86400 * 30))->delete();
        $this->success("删除日志成功！");
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
