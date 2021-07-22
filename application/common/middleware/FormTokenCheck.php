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
// | 防crsf全局
// +----------------------------------------------------------------------
namespace app\common\middleware;

use think\exception\HttpResponseException;
use think\facade\Session;

class FormTokenCheck
{
    /**
     * 表单令牌检测
     * @access public
     * @param Request $request
     * @param Closure $next
     * @param string  $token 表单令牌Token名称
     * @return Response
     */
    public function handle($request, \Closure $next, string $token = null)
    {
        $check = $this->checkToken($request, $token ?: '__token__');

        if (false === $check) {
            $result = [
                'code' => -1,
                'msg'  => '令牌错误',
                'data' => ['__token__' => $request->token()],
                'url'  => '',
            ];
            throw new HttpResponseException(json($result));
        }

        return $next($request);
    }

    /**
     * 检查请求令牌
     * @access public
     * @param  string $token 令牌名称
     * @param  array  $data  表单数据
     * @return bool
     */
    public function checkToken($request, string $token = '__token__', array $data = [])
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return true;
        }

        if (!Session::has($token)) {
            // 令牌数据无效
            return false;
        }

        // Header验证
        if ($request->header('X-CSRF-TOKEN') && Session::get($token) === $request->header('X-CSRF-TOKEN')) {
            // 防止重复提交
            Session::delete($token); // 验证完成销毁session
            return true;
        }

        if (empty($data)) {
            $data = $request->post();
        }

        // 令牌验证
        if (isset($data[$token]) && Session::get($token) === $data[$token]) {
            // 防止重复提交
            Session::delete($token); // 验证完成销毁session
            return true;
        }

        // 开启TOKEN重置
        Session::delete($token);
        return false;
    }
}
