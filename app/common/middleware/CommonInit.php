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
// | 初始化中间件配置
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\middleware;

use Closure;
use think\facade\Config;
use think\facade\Env;
use think\Request;
use think\Response;

class CommonInit
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
        // 设置mbstring字符编码
        mb_internal_encoding('UTF-8');
        //设置DEBUG环境
        $this->initDebugEnv();
        // Form别名
        if (!class_exists('Form')) {
            class_alias('form\\Form', 'Form');
        }

        return $next($request);
    }

    /**
     * 调试模式缓存
     */
    private function initDebugEnv()
    {
        if (Env::get('APP_DEBUG')) {
            // 如果是调试模式将version置为当前的时间戳可避免缓存
            Config::set(['version' => time()], 'site');
            // 如果是开发模式那么将异常模板修改成官方的
            Config::set(['exception_tmpl' => app()->getThinkPath() . 'tpl/think_exception.tpl'], 'app');
        }
    }
}
