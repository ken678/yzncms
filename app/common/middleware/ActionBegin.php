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
// | 控制器中间件配置
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\middleware;

use Closure;
use think\facade\Event;
use think\Request;
use think\Response;

class ActionBegin
{
    /**
     * 框架初始化.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        //监听控制器
        Event::trigger('action_begin', $request);
        return $next($request);
    }

}
