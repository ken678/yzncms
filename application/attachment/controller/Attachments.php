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

use app\admin\service\User;
use app\attachment\model\Attachment as Attachment_Model;
use app\common\controller\Adminbase;
use think\Db;
use think\Image;

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

    public function upload($dir = '', $from = '', $module = '', $thumb = 0, $thumbsize = '', $thumbtype = '', $watermark = 1, $sizelimit = -1, $extlimit = '')
    {
        // 临时取消执行时间限制
        //@set_time_limit(0);
        if ($dir == '') {
            return $this->error('没有指定上传目录');
        }
        return $this->saveFile($dir, $from, $module, $thumb, $thumbsize, $thumbtype, $watermark, $sizelimit, $extlimit);
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
                        'code' => -1,
                        'info' => '检测到环境未开启php_fileinfo拓展',
                    ]);
            }
        }
        // 附件大小限制
        $size_limit = $dir == 'images' ? config('upload_image_size') : config('upload_file_size');
        if (-1 != $sizelimit) {
            $sizelimit = intval($sizelimit);
            if ($sizelimit >= 0 && (0 == $size_limit || ($size_limit > 0 && $sizelimit > 0 && $size_limit > $sizelimit))) {
                $size_limit = $sizelimit;
            }
        }
        $size_limit = $size_limit * 1024;
        // 附件类型限制
        $ext_limit = $dir == 'images' ? config('upload_image_ext') : config('upload_file_ext');
        if ('' != $extlimit) {
            $extArr = explode(',', $ext_limit);
            $extArrPara = explode(',', $extlimit);
            $ext_limit = '';
            foreach ($extArrPara as $vo) {
                if (in_array($vo, $extArr) && $vo) {
                    $ext_limit .= $vo . ',';
                }
            }
            if ($ext_limit) {
                $ext_limit = substr($ext_limit, 0, -1);
            }
        }
        $ext_limit = $ext_limit != '' ? parse_attr($ext_limit) : '';
        foreach (['php', 'html', 'htm', 'js'] as $vo) {
            unset($ext_limit[$vo]);
        }
        // 水印参数
        $watermark = $this->request->post('watermark', '');
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
                        'code' => -1,
                        'info' => '获取不到文件信息',
                    ]);
            }
        }
        // 判断附件是否已存在
        /*if ($file_exists = AttachmentModel::get(['md5' => $file->hash('md5')])) {

        }*/

        // 判断附件大小是否超过限制
        if ($size_limit > 0 && ($file->getInfo('size') > $size_limit)) {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '附件过大']);
                    break;
                default:
                    return json([
                        'status' => 0,
                        'info' => '附件过大',
                    ]);
            }
        }
        // 判断附件格式是否符合
        $file_name = $file->getInfo('name');
        $file_ext = strtolower(substr($file_name, strrpos($file_name, '.') + 1));
        $error_msg = '';
        if ($ext_limit == '') {
            $error_msg = '获取文件后缀限制信息失败！';
        }
        try {
            $fileMine = $file->getMime();
        } catch (\Exception $ex) {
            $error_msg = $ex->getMessage();
        }
        if ($fileMine == 'text/x-php' || $fileMine == 'text/html') {
            $error_msg = '禁止上传非法文件！';
        }
        if (preg_grep("/php/i", $ext_limit)) {
            $error_msg = '禁止上传非法文件！';
        }
        if (!preg_grep("/$file_ext/i", $ext_limit)) {
            $error_msg = '附件类型不正确！';
        }
        if (!in_array($file_ext, $ext_limit)) {
            $error_msg = '附件类型不正确！';
        }
        if ($error_msg != '') {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => $error_msg]);
                    break;
                default:
                    return json([
                        'code' => -1,
                        'info' => $error_msg,
                    ]);
            }
        }
        // 移动到框架应用根目录指定目录下
        $info = $file->move($this->uploadPath . DIRECTORY_SEPARATOR . $dir);
        if ($info) {
            // 水印功能
            if ($watermark == '') {
                if ($dir == 'images' && config('upload_thumb_water') == 1 && config('upload_thumb_water_pic') > 0) {
                    model('Attachment')->create_water($info->getRealPath(), config('upload_thumb_water_pic'));
                }
            }

            // 获取附件信息
            $file_info = [
                'uid' => User::instance()->isLogin(),
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
                            'code' => 0,
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
                        return json(['code' => 0, 'info' => '上传成功,写入数据库失败']);
                }
            }
        } else {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '上传失败']);
                    break;
                default:
                    return json(['code' => -1, 'info' => $file->getError()]);
            }

        }

    }

    public function delete($id = '')
    {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('没有勾选需要删除的文件~');
            }
            $Attachment = model('Attachment');
            try {
                $Attachment->deleteFile($ids);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('文件删除成功~');
        } else {
            $id = intval($id);
            if ($id <= 0) {
                $this->error('参数错误~');
            }
            $Attachment = model('Attachment');
            try {
                $Attachment->deleteFile($id);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('文件删除成功~');
        }
    }

    /**
     * html代码远程图片本地化
     * @param string $content html代码
     *  @param string $type 文件类型
     */
    public function getUrlFile()
    {
        $content = $this->request->post('content');
        $type = $this->request->post('type');
        $urls = [];
        preg_match_all("/(src|SRC)=[\"|'| ]{0,}((http|https):\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU", $content, $urls);
        $urls = array_unique($urls[2]);

        $path = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        $file_info = [
            'uid' => User::instance()->isLogin(),
            'module' => 'admin',
            'thumb' => '',
        ];
        foreach ($urls as $vo) {
            $vo = trim(urldecode($vo));
            $host = parse_url($vo, PHP_URL_HOST);
            if ($host != $_SERVER['HTTP_HOST']) {
                //当前域名下的文件不下载
                /*$fileExt = get_url_file_ext($vo);
            $filename = $path . 'temp' . DIRECTORY_SEPARATOR . md5($vo) . '.' . $fileExt;
            if (http_down($vo, $filename) !== false) {
            $file_info['md5'] = hash_file('md5', $filename);
            if ($file_exists = AttachmentModel::get(['md5' => $file_info['md5']])) {
            unlink($filename);
            $localpath = $this->uploadUrl . $file_exists['path'];
            } else {
            $file_info['sha1'] = hash_file('sha1', $filename);
            $file_info['size'] = filesize($filename);
            $file_info['mime'] = mime_content_type($filename);

            $fpath = $type . DIRECTORY_SEPARATOR . date('Ymd');
            $savePath = $path . $fpath;
            if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
            }
            $fname = DIRECTORY_SEPARATOR . md5(microtime(true)) . '.' . $fileExt;
            $file_info['name'] = $vo;
            $file_info['path'] = str_replace(DIRECTORY_SEPARATOR, '/', $fpath . $fname);
            $file_info['ext'] = $fileExt;

            if (rename($filename, $savePath . $fname)) {
            AttachmentModel::create($file_info);
            $localpath = $this->uploadUrl . $file_info['path'];
            }
            }
            $content = str_replace($vo, $localpath, $content);
            }*/
            }
        }
        exit($content);
    }

}
