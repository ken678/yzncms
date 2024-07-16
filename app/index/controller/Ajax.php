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
// | Ajax异步请求接口
// +----------------------------------------------------------------------
namespace app\index\controller;

use app\api\controller\Common;
use app\common\controller\Frontend;
use think\Response;

class Ajax extends Frontend
{

    protected $noNeedLogin = ['upload'];
    protected $noNeedRight = ['*'];

    /**
     * 生成后缀图标
     */
    public function icon()
    {
        $suffix                  = $this->request->request("suffix", 'file');
        $data                    = build_suffix_image($suffix);
        $header                  = ['Content-Type' => 'image/svg+xml'];
        $offset                  = 30 * 60 * 60 * 24; // 缓存一个月
        $header['Cache-Control'] = 'public';
        $header['Pragma']        = 'cache';
        $header['Expires']       = gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        $response                = Response::create($data)->header($header);
        return $response;
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $api = app(Common::class);
        return $api->upload();
    }
}
