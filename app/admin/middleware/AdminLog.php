<?php
declare (strict_types = 1);
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
// | 写入管理日志
// +----------------------------------------------------------------------
namespace app\admin\middleware;

use app\admin\model\Adminlog as AdminlogModel;
use think\facade\Config;

class AdminLog
{

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        if (($request->isPost()) && Config::get('yzn.auto_record_admin_log')) {
            AdminLogModel::record();
        }
        return $response;
    }
}
