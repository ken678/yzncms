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
// | 公共接口
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\controller\Api;
use app\common\exception\UploadException;
use app\common\library\Upload as UploadLib;
use think\facade\Config;

class Common extends Api
{
    protected $noNeedLogin = ['init', 'captcha'];
    protected $noNeedRight = '*';

    public function initialize()
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Expose-Headers: __token__'); //跨域让客户端获取到
        }
        //跨域检测
        check_cors_request();
        parent::initialize();
    }

    /**
     * 加载初始化
     *
     * @param string $version 版本号
     * @param string $lng 经度
     * @param string $lat 纬度
     */
    public function init()
    {

    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $dir  = $this->request->param("dir");
        $from = $this->request->param("from");
        if ($dir == '') {
            return json([
                'code'  => 0,
                'msg'   => '没有指定上传目录',
                'state' => '没有指定上传目录', //兼容百度
                'message' => '没有指定上传目录', //兼容editormd
            ]);
        }
        $chunkid = $this->request->post("chunkid");
        if ($chunkid) {
            if (!config::get('upload.chunking')) {
                return json([
                    'code' => 0,
                    'msg'  => '未开启分片上传功能',
                ]);
            }
            //分片
            $action     = $this->request->post("action");
            $chunkindex = $this->request->post("chunkindex/d", 0);
            $chunkcount = $this->request->post("chunkcount/d", 1);
            $filename   = $this->request->post("filename");
            $method     = $this->request->method(true);
            if ($action == 'merge') {
                $attachment = null;
                //合并分片文件
                try {
                    $upload     = new UploadLib();
                    $attachment = $upload->merge($chunkid, $chunkcount, $filename, $dir, $from);
                } catch (UploadException $e) {
                    return json([
                        'code'  => 0,
                        'msg'   => $e->getMessage(),
                        'state' => $e->getMessage(), //兼容百度
                        'message' => $e->getMessage(), //兼容editormd
                    ]);
                }
                return $attachment;
            } elseif ($method == 'clean') {
                //删除冗余的分片文件
                try {
                    $upload = new UploadLib();
                    $upload->clean($chunkid);
                } catch (UploadException $e) {
                    return json([
                        'code'  => 0,
                        'msg'   => $e->getMessage(),
                        'state' => $e->getMessage(), //兼容百度
                        'message' => $e->getMessage(), //兼容editormd
                    ]);
                }
                return json(['code' => 1]);
            } else {
                //上传分片文件
                $file = $this->request->file('file');
                try {
                    $upload = new UploadLib($file);
                    $upload->chunk($chunkid, $chunkindex, $chunkcount);
                } catch (UploadException $e) {
                    return json([
                        'code'  => 0,
                        'msg'   => $e->getMessage(),
                        'state' => $e->getMessage(), //兼容百度
                        'message' => $e->getMessage(), //兼容editormd
                    ]);
                }
                return json(['code' => 1]);
            }
        }
        // 获取附件数据
        switch ($from) {
            case 'editormd':
                $file_input_name = 'editormd-image-file';
                break;
            default:
                $file_input_name = 'file';
        }
        $attachment = null;

        try {
            //默认普通上传文件
            $file       = $this->request->file($file_input_name);
            $upload     = new UploadLib($file);
            $attachment = $upload->upload($dir);
        } catch (UploadException | Exception $e) {
            return json([
                'code'  => 0,
                'msg'   => $e->getMessage(),
                'state' => $e->getMessage(), //兼容百度
                'message' => $e->getMessage(), //兼容editormd
            ]);
        }
        return $attachment;
    }

}
