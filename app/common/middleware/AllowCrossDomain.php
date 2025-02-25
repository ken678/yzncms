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
// | 跨域请求支持
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\middleware;

use Closure;
use think\exception\HttpResponseException;
use think\facade\Config;
use think\Request;
use think\Response;

class AllowCrossDomain
{
    //是否允许所有请求头
    protected $allowAllHeaders = true;

    protected $header = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age'           => 1800,
        'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With',
    ];

    /**
     * 允许跨域请求
     * @access public
     * @param Request $request
     * @param Closure $next
     * @param array   $header
     * @return Response
     */
    public function handle(Request $request, Closure $next, array $header = []): Response
    {
        $header = !empty($header) ? array_merge($this->header, $header) : $this->header;

        $origin = $request->header('origin');

        if ($origin && !isset($header['Access-Control-Allow-Origin'])) {
            $info = parse_url($origin);

            $domainArr   = explode(',', Config::get('yzn.cors_request_domain'));
            $domainArr[] = $request->host(true);

            if (in_array("*", $domainArr) || in_array($origin, $domainArr) || (isset($info['host']) && in_array($info['host'], $domainArr))) {
                $header['Access-Control-Allow-Origin'] = $origin;
            } else {
                $response = Response::create('跨域检测无效', 'html', 403);
                throw new HttpResponseException($response);
            }
        }

        if ($request->isOptions()) {
            if ($this->allowAllHeaders) {
                $header['Access-Control-Allow-Headers'] = (string) $request->header('Access-Control-Request-Headers');
            }
            $response = Response::create('', 'html', 204)->header($header);
            throw new HttpResponseException($response);
        }

        return $next($request)->header($header);
    }
}
