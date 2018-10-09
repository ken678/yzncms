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
// | 附件上传处理类
// +----------------------------------------------------------------------
namespace app\attachment\controller;

use app\admin\model\AdminUser as AdminUser_model;
use app\attachment\model\Attachment as Attachment_Model;
use app\common\controller\Adminbase;
use think\Db;

class Attachments extends Adminbase
{

    private $uploadUrl = '';
    private $uploadPath = '';

    protected function initialize()
    {
        parent::initialize();
        $this->uploadUrl = config('public_url') . 'uploads/';
        $this->uploadPath = config('upload_path');
    }

    public function index()
    {
        $limit = $this->request->param('limit/d', 10);
        $page = $this->request->param('page/d', 10);
        if ($this->request->isAjax()) {
            $_list = Db::name("attachment")
                ->page($page, $limit)
                ->order('id', 'desc')
                ->withAttr('size', function ($value, $data) {
                    return format_bytes($value);
                })
                ->withAttr('create_time', function ($value, $data) {
                    return date('Y-m-d H:i:s', $value);
                })
                ->select();
            $total = Db::name("attachment")->order('id', 'desc')->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * @param string $type 类型
     * @param $config
     * @return \think\response\Json
     */
    protected function showFileList($type = '')
    {
        /* 获取参数 */
        $size = input('get.size/d', 0);
        $start = input('get.start/d', 0);
        $allowExit = input('get.exit', '');
        if ($size == 0) {
            $size = 20;
        }
        /* 判断类型 */
        switch ($type) {
            /* 列出附件 */
            case 'listfile':
                $allowExit = '' == $allowExit ? config('upload_file_ext') : $allowExit;
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowExit = '' == $allowExit ? config('upload_image_ext') : $allowExit;
        }

        /* 获取附件列表 */
        $filelist = Attachment_Model::order('id desc')->where('ext', 'in', $allowExit)->where('status', 1)->limit($start, $size)->column('id,path,create_time,name,size');
        if (empty($filelist)) {
            return json(array(
                "state" => "没有找到附件",
                "list" => [],
                "start" => $start,
                "total" => 0
            ));
        }
        $uploadUrl = config('public_url');
        $list = [];
        $i = 0;
        foreach ($filelist as $value) {
            $list[$i]['id'] = $value['id'];
            $list[$i]['url'] = $uploadUrl . 'uploads/' . $value['path'];
            $list[$i]['name'] = $value['name'];
            $list[$i]['size'] = format_bytes($value['size']);
            $list[$i]['mtime'] = $value['create_time'];
            $i++;
        }

        /* 返回数据 */
        $result = array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => Attachment_Model::where('ext', 'in', $allowExit)->count(),
        );
        return json($result);

    }

    /**
     * 保存附件
     * @param string $dir 附件存放的目录
     * @param string $from 来源
     * @param string $module 来自哪个模块
     * @return string|\think\response\Json
     */
    protected function saveFile($dir = '', $from = '', $module = '', $thumb = 0, $thumbsize = '', $thumbtype = '', $watermark = 1, $sizelimit = -1, $extlimit = '')
    {
        if (!function_exists("finfo_open")) {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '检测到环境未开启php_fileinfo拓展']);
                default:
                    return json([
                        'status' => 0,
                        'info' => '检测到环境未开启php_fileinfo拓展',
                    ]);
            }
        }
        // 获取附件数据
        switch ($from) {
            case 'ueditor':
                $file_input_name = 'upfile';
                break;
            default:
                $file_input_name = 'file';
        }
        $file = $this->request->file($file_input_name);
        if ($file == null) {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '获取不到文件信息']);
                default:
                    return json([
                        'status' => 0,
                        'info' => '获取不到文件信息',
                    ]);
            }
        }
        // 判断附件是否已存在
        /*if ($file_exists = AttachmentModel::get(['md5' => $file->hash('md5')])) {

        }*/
        // 移动到框架应用根目录指定目录下
        $info = $file->move($this->uploadPath . DIRECTORY_SEPARATOR . $dir);
        if ($info) {
            // 获取附件信息
            $file_info = [
                'uid' => $this->AdminUser_model->isLogin(),
                'name' => $file->getInfo('name'),
                'mime' => $file->getInfo('type'),
                'path' => $dir . '/' . str_replace('\\', '/', $info->getSaveName()),
                'ext' => $info->getExtension(),
                'size' => $info->getSize(),
                'md5' => $info->hash('md5'),
                'sha1' => $info->hash('sha1'),
                'module' => $module,
            ];
            if ($file_add = Attachment_Model::create($file_info)) {
                switch ($from) {
                    case 'ueditor':
                        return json([
                            "state" => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS"
                            "url" => $this->uploadUrl . $file_info['path'], // 返回的地址
                            "title" => $file_info['name'], // 附件名
                        ]);
                        break;
                    default:
                        return json([
                            'status' => 1,
                            'info' => $file_info['name'] . '上传成功',
                            'id' => $file_add['id'],
                            'path' => empty($file_info['thumb']) ? $this->uploadUrl . $file_info['path'] : $this->uploadUrl . $file_info['thumb'],
                        ]);
                }
            } else {
                switch ($from) {
                    case 'ueditor':
                        return json(['state' => '上传失败']);
                        break;
                    default:
                        return json(['status' => 0, 'info' => '上传成功,写入数据库失败']);
                }
            }
        } else {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '上传失败']);
                    break;
                default:
                    return json(['status' => 0, 'info' => $file->getError()]);
            }

        }

    }

}
