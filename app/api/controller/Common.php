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
            $this->error('没有指定上传目录');
        }
        $chunkid = $this->request->post("chunkid");
        if ($chunkid) {
            if (!config::get('upload.chunking')) {
                $this->error('未开启分片上传功能');
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
                    $this->error($e->getMessage());
                }
                $this->success('上传成功', [
                    'url'     => $attachment->path,
                    'fullurl' => cdnurl($attachment->path, true),
                ]);
            } elseif ($method == 'clean') {
                //删除冗余的分片文件
                try {
                    $upload = new UploadLib();
                    $upload->clean($chunkid);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            } else {
                //上传分片文件
                $file = $this->request->file('file');
                try {
                    $upload = new UploadLib($file);
                    $upload->chunk($chunkid, $chunkindex, $chunkcount);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            }
        }

        $attachment = null;
        try {
            //默认普通上传文件
            $file       = $this->request->file('file');
            $upload     = new UploadLib($file);
            $attachment = $upload->upload($dir);
        } catch (UploadException | Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('上传成功', [
            'url'     => $attachment->path,
            'fullurl' => cdnurl($attachment->path, true),
        ]);
    }

}
