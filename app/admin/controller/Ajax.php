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
// | 后台常用ajax
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Adminlog;
use app\common\controller\Backend;
use app\common\exception\UploadException;
use app\common\library\Upload as UploadLib;
use Exception;
use think\facade\Db;
use think\Response;

class Ajax extends Backend
{
    protected $noNeedRight = ['*'];

    //过滤内容的敏感词
    public function filterWord($content)
    {
        $content = $this->request->post('content');
        // 获取感词库文件路径
        $wordFilePath = app()->getRootPath() . 'data/words.txt';
        $handle       = \util\SensitiveHelper::init()->setTreeByFile($wordFilePath);
        $word         = $handle->getBadWord($content);
        if ($word) {
            $this->error('内容包含违禁词！', null, $word);
        } else {
            $this->success('内容没有违禁词！');
        }
    }

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
     * 读取省市区数据,联动列表
     */
    public function area()
    {
        $params = $this->request->get("row/a");
        if (!empty($params)) {
            $province = $params['province'] ?? null;
            $city     = $params['city'] ?? null;
        } else {
            $province = $this->request->get('province');
            $city     = $this->request->get('city');
        }
        $where        = ['pid' => 0, 'level' => 1];
        $provincelist = null;
        if ($province !== null) {
            $where['pid']   = $province;
            $where['level'] = 2;
            if ($city !== null) {
                $where['pid']   = $city;
                $where['level'] = 3;
            }
        }
        $provincelist = Db::name('area')->where($where)->field('id as value,name')->select();
        $this->success('', '', $provincelist);
    }

    public function upload($dir = '', $from = '')
    {
        Adminlog::setTitle('附件上传');
        if ($dir == '') {
            return json([
                'code'    => 0,
                'msg'     => '没有指定上传目录',
                'state'   => '没有指定上传目录', //兼容百度
                'message' => '没有指定上传目录', //兼容editormd
            ]);
        }
        $chunkid = $this->request->post("chunkid");
        if ($chunkid) {
            if (!config('upload.chunking')) {
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
                        'code'    => 0,
                        'msg'     => $e->getMessage(),
                        'state'   => $e->getMessage(), //兼容百度
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
                        'code'    => 0,
                        'msg'     => $e->getMessage(),
                        'state'   => $e->getMessage(), //兼容百度
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
                        'code'    => 0,
                        'msg'     => $e->getMessage(),
                        'state'   => $e->getMessage(), //兼容百度
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
                'code'    => 0,
                'msg'     => $e->getMessage(),
                'state'   => $e->getMessage(), //兼容百度
                'message' => $e->getMessage(), //兼容editormd
            ]);
        }
        return $attachment;
    }

}
