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
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Db;

class Adminlog extends Adminbase
{
    //日志首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = model("adminlog")->select();
            $total = count($data);
            $result = array("code" => 0, "count" => $total, "data" => $data);
            return json($result);
        }
        return $this->fetch();

    }
}
