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

use app\admin\service\User as admin_user;
use app\attachment\model\Attachment as Attachment_Model;
use app\common\controller\Base;
use app\member\service\User as home_user;
use think\facade\Hook;
use FilesystemIterator;

class Upload extends Base
{
    //上传用户ID
    public $admin_id = 0;
    public $user_id  = 0;
    //会员组
    public $groupid = 0;
    //是否后台
    public $isadmin = 0;
    //上传模块
    public $module      = 'cms';

    protected $merging  = false;
    protected $file     = null;
    protected $fileInfo = null;
    protected $chunkDir = null;

    //编辑器初始配置
    private $editorConfig = array(
        /* 上传图片配置项 */
        "imageActionName"         => "uploadimage", /* 执行上传图片的action名称 */
        "imageFieldName"       => "upfile", /* 提交的图片表单名称 */
        "imageMaxSize"     => 2048000, /* 上传大小限制，单位B */
        "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
        "imageCompressEnable" => true, /* 是否压缩图片,默认是true */
        "imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
        "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
        "imageUrlPrefix" => "", /* 图片访问路径前缀 */
        'imagePathFormat' => '',
        /* 涂鸦图片上传配置项 */
        "scrawlActionName"        => "uploadscrawl", /* 执行上传涂鸦的action名称 */
        "scrawlFieldName"      => "upfile", /* 提交的图片表单名称 */
        'scrawlPathFormat' => '',
        "scrawlMaxSize"           => 2048000, /* 上传大小限制，单位B */
        'scrawlUrlPrefix'      => '',
        'scrawlInsertAlign'       => 'none',
        /* 截图工具上传 */
        "snapscreenActionName"    => "uploadimage", /* 执行上传截图的action名称 */
        'snapscreenPathFormat' => '',
        'snapscreenUrlPrefix'     => '',
        'snapscreenInsertAlign'   => 'none',
        /* 抓取远程图片配置 */
        'catcherLocalDomain'      => array('127.0.0.1', 'localhost', 'img.baidu.com'),
        "catcherActionName"       => "catchimage", /* 执行抓取远程图片的action名称 */
        'catcherFieldName'     => 'source',
        'catcherPathFormat'       => '',
        'catcherUrlPrefix'        => '',
        'catcherMaxSize'          => 0,
        'catcherAllowFiles'       => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
        /* 上传视频配置 */
        "videoActionName"         => "uploadvideo", /* 执行上传视频的action名称 */
        "videoFieldName"       => "upfile", /* 提交的视频表单名称 */
        'videoPathFormat'  => '',
        'videoUrlPrefix'          => '',
        'videoMaxSize'            => 0,
        'videoAllowFiles'         => array(".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"),
        /* 上传文件配置 */
        "fileActionName"          => "uploadfile", /* controller里,执行上传视频的action名称 */
        'fileFieldName'        => 'upfile',
        'filePathFormat'          => '',
        'fileUrlPrefix'           => '',
        'fileMaxSize'             => 0,
        'fileAllowFiles'          => array(".flv", ".swf"),
        /* 列出指定目录下的图片 */
        "imageManagerActionName"  => "listimage", /* 执行图片管理的action名称 */
        'imageManagerListPath' => '',
        'imageManagerListSize'    => 20,
        'imageManagerUrlPrefix'   => '',
        'imageManagerInsertAlign' => 'none',
        'imageManagerAllowFiles'  => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
        /* 列出指定目录下的文件 */
        "fileManagerActionName"   => "listfile", /* 执行文件管理的action名称 */
        'fileManagerListPath'  => '',
        'fileManagerUrlPrefix'    => '',
        'fileManagerListSize'     => '',
        'fileManagerAllowFiles'   => array(".flv", ".swf"),
    );

    protected function initialize()
    {
        parent::initialize();
        //检查是否后台登录，后台登录下优先级最高，用于权限判断
        if (admin_user::instance()->isLogin()) {
            $this->isadmin  = 1;
            $this->admin_id = admin_user::instance()->id;
        } elseif (home_user::instance()->isLogin()) {
            $this->user_id = home_user::instance()->id;
            $this->groupid = home_user::instance()->groupid ? home_user::instance()->groupid : 8;
        } else {
            $this->user_id = 0;
        }
        $this->chunkDir = ROOT_PATH . 'runtime' . DS . 'chunks';

        //图片上传大小和类型
        $this->editorConfig['imageMaxSize'] = $this->editorConfig['catcherMaxSize'] = 0 == config('upload_image_size') ? 1024 * 1024 * 1024 : config('upload_image_size') * 1024;
        if (!empty(config('upload_image_ext'))) {
            $imageallowext = parse_attr(config('upload_image_ext'));
            foreach ($imageallowext as $k => $rs) {
                $imageallowext[$k] = ".{$rs}";
            }
            $this->editorConfig['imageAllowFiles'] = $imageallowext;
        }

        //附件上传大小和类型
        $this->editorConfig['fileMaxSize'] = $this->editorConfig['videoMaxSize'] = 0 == config('upload_file_size') ? 1024 * 1024 * 1024 : config('upload_file_size') * 1024;
        if (!empty(config('upload_file_ext'))) {
            $fileallowext = parse_attr(config('upload_file_ext'));
            foreach ($fileallowext as $k => $rs) {
                $fileallowext[$k] = ".{$rs}";
            }
            $this->editorConfig['fileAllowFiles'] = $fileallowext;
        }
    }

    public function setFile($file)
    {
        if (empty($file)) {
            return json([
                'code'  => -1,
                'info'  => '未上传文件或超出服务器上传限制',
                'state' => '未上传文件或超出服务器上传限制', //兼容百度
            ]);
        }
        $fileInfo                = $file->getInfo();
        $suffix                  = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix                  = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';
        $fileInfo['suffix']      = $suffix;
        $fileInfo['imagewidth']  = 0;
        $fileInfo['imageheight'] = 0;
        $this->file              = $file;
        $this->fileInfo          = $fileInfo;
    }

    public function upload($dir = '', $from = '', $module = '')
    {
        if (!function_exists("finfo_open")) {
            return json([
                'code'  => -1,
                'info'  => '检测到环境未开启php_fileinfo拓展',
                'state' => '检测到环境未开启php_fileinfo拓展', //兼容百度
            ]);
        }
        //验证是否可以上传
        $status = $this->isUpload($module);
        if (true !== $status) {
            return json([
                'code' => -1,
                'info' => $status,
            ]);
        }
        if ($dir == '') {
            return $this->error('没有指定上传目录');
        }
        $chunkid = $this->request->post("chunkid");
        if ($chunkid) {
            //分片
            $action     = $this->request->post("action");
            $chunkindex = $this->request->post("chunk/d");
            $chunkcount = $this->request->post("chunks/d");
            $filename   = $this->request->post("filename");
            $method     = $this->request->method(true);
            if ($action == 'merge') {
                return $this->merge($chunkid, $chunkcount, $filename, $dir, $from, $module);
            } else {
                $file = $this->request->file('file');
                $this->setFile($file);
                return $this->chunk($chunkid, $chunkindex, $chunkcount);
            }
        }
        if ($from == 'ueditor') {
            return $this->ueditor();
        }
        // 获取附件数据
        switch ($from) {
            case 'editormd':
                $file_input_name = 'editormd-image-file';
                break;
            case 'ueditor':
                $file_input_name = 'upfile';
                break;
            default:
                $file_input_name = 'file';
        }
        $file = $this->request->file($file_input_name);
        $this->setFile($file);
        return $this->saveFile($dir, $from, $module);
    }

    /**
     * 检查是否可以上传
     */
    protected function isUpload($module)
    {
        $module_list = cache('Module');
        if ($module_list[strtolower($module)] || strtolower($module) == 'admin' || strtolower($module) == 'addons' || strtolower($module) == 'attachment') {
            $this->module = strtolower($module);
        } else {
            return false;
        }
        //如果是前台上传，判断用户组权限
        if ($this->isadmin == 0 && $this->user_id != 0) {
            $member_group = cache('Member_Group');
            if ((int) $member_group[$this->groupid]['allowattachment'] < 1) {
                return "所在的用户组没有附件上传权限！";
            }
        }
        return true;
    }

    /**
     * 分片上传
     */
    protected function chunk($chunkid, $chunkindex, $chunkcount, $chunkfilesize = null, $chunkfilename = null, $direct = false)
    {
        $fileInfo = $this->file->getInfo();
        if ($fileInfo['type'] != 'application/octet-stream') {
            return json([
                'code'  => -1,
                'info'  => '上传文件格式受限制',
                'state' => '上传文件格式受限制', //兼容百度
            ]);
        }
        if (!preg_match('/^[a-z0-9\_]+$/', $chunkid)) {
            return json([
                'code'  => -1,
                'info'  => '未知参数',
                'state' => '未知参数', //兼容百度
            ]);
        }
        $destDir  = ROOT_PATH . 'runtime' . DS . 'chunks';
        $fileName = $chunkid . "-" . $chunkindex . '.part';
        $destFile = $destDir . DS . $fileName;
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }
        if (!move_uploaded_file($this->file->getPathname(), $destFile)) {
            return json([
                'code'  => -1,
                'info'  => '分片写入失败',
                'state' => '分片写入失败', //兼容百度
            ]);
        }
    }

    /**
     * 合并分片文件
     */
    public function merge($chunkid, $chunkcount, $filename, $dir, $from, $module)
    {
        if (!preg_match('/^[a-z0-9\_]+$/', $chunkid)) {
            return json([
                'code'  => -1,
                'info'  => '未知参数',
                'state' => '未知参数', //兼容百度
            ]);
        }

        $filePath = $this->chunkDir . DS  . $chunkid;

        $completed = true;
        //检查所有分片是否都存在
        for ($i = 0; $i < $chunkcount; $i++) {
            if (!file_exists("{$filePath}-{$i}.part")) {
                $completed = false;
                break;
            }
        }
        if (!$completed) {
            $this->clean($chunkid);
            return json([
                'code'  => -1,
                'info'  => '分片文件错误',
                'state' => '分片文件错误', //兼容百度
            ]);
        }

        //如果所有文件分片都上传完毕，开始合并
        $uploadPath = $filePath;

        if (!$destFile = @fopen($uploadPath, "wb")) {
            $this->clean($chunkid);
            return json([
                'code'  => -1,
                'info'  => '分片合并错误',
                'state' => '分片合并错误', //兼容百度
            ]);
        }
        if (flock($destFile, LOCK_EX)) {
            // 进行排他型锁定
            for ($i = 0; $i < $chunkcount; $i++) {
                $partFile = "{$filePath}-{$i}.part";
                if (!$handle = @fopen($partFile, "rb")) {
                    break;
                }
                while ($buff = fread($handle, filesize($partFile))) {
                    fwrite($destFile, $buff);
                }
                @fclose($handle);
                @unlink($partFile); //删除分片
            }

            flock($destFile, LOCK_UN);
        }
        @fclose($destFile);

        $attachment = null;
        try {
            $file = new \think\File($uploadPath);
            $info = [
                'name'     => $filename,
                'type'     => $file->getMime(),
                'tmp_name' => $uploadPath,
                'size'     => $file->getSize(),
            ];
            $file->setSaveName($filename)->setUploadInfo($info);
            $file->isTest(true);

            //重新设置文件
            $this->setFile($file);

            unset($file);
            $this->merging = true;

            //允许大文件
            //$this->config['maxsize'] = "1024G";
            $attachment = $this->saveFile($dir, $from, $module);
        } catch (\Exception $e) {
            @unlink($destFile);
            return json([
                'code'  => -1,
                'info'  => $e->getMessage(),
                'state' => $e->getMessage(), //兼容百度
            ]);
        }
        return $attachment;
    }

    /**
     * 清理分片文件
     * @param $chunkid
     */
    public function clean($chunkid)
    {
        if (!preg_match('/^[a-z0-9\_]+$/', $chunkid)) {
            return json([
                'code'  => -1,
                'info'  => '未知参数',
                'state' => '未知参数', //兼容百度
            ]);
        }
        $iterator = new \GlobIterator($this->chunkDir . DS . $chunkid . '-*', FilesystemIterator::KEY_AS_FILENAME);
        $array = iterator_to_array($iterator);
        foreach ($array as $index => &$item) {
            $sourceFile = $item->getRealPath() ?: $item->getPathname();
            $item = null;
            @unlink($sourceFile);
        }
    }

    /**
     * 保存附件
     * @param string $dir 附件存放的目录
     * @param string $from 来源
     * @param string $module 来自哪个模块
     * @return string|\think\response\Json
     */
    protected function saveFile($dir = '', $from = '', $module = '', $savekey = null)
    {
        $this->checkSize($dir);
        $this->checkExecutable($dir);

        // 判断附件是否已存在
        if ($file_exists = Attachment_Model::get(['md5' => $this->file->hash('md5')])) {
            return json([
                'code'    => 0,
                'info'    => $file_exists['name'] . '上传成功',
                'id'      => $file_exists['id'],
                'path'    => $file_exists['path'],
                "state"   => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS" 兼容百度
                "url"     => $file_exists['path'], // 返回的地址 兼容百度
                "title"   => $file_exists['name'], // 附件名 兼容百度
                "success" => 1, //兼容editormd
                "message" => $file_exists['name'], // 附件名 兼容editormd
            ]);
        }

        // 附件上传钩子，用于第三方文件上传扩展
        if (config('upload_driver') != 'local') {
            $hook_result = Hook::listen('upload_after', ['dir' => $dir, 'file' => $this->file, 'from' => $from, 'module' => $module], true);
            if (false !== $hook_result) {
                return $hook_result;
            }
        }

        $savekey   = $savekey ? $savekey : $this->getSavekey($dir);
        //$savekey   = '/' . ltrim($savekey, '/');
        $savekey   = ltrim($savekey, '/');
        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName  = substr($savekey, strripos($savekey, '/') + 1);
        $destDir   = ROOT_PATH . 'public/' . str_replace('/', DS, $uploadDir);
        $sha1      = $this->file->hash();
        $md5       = $this->file->md5();

        //如果是合并文件
        if ($this->merging) {
            $destFile   = $destDir . $fileName;
            $sourceFile = $this->file->getRealPath() ?: $this->file->getPathname();
            $fileinfo   = $this->file->getInfo();
            $this->file = null;
            if (!is_dir($destDir)) {
                @mkdir($destDir, 0755, true);
            }
            $rs   = rename($sourceFile, $destFile);
            $info = new \think\File($destFile);
            $info->setSaveName($fileName)->setUploadInfo($fileinfo);
        } else {
            // 移动到框架应用根目录指定目录下
            $info = $this->file->move($destDir, $fileName);
        }
        if ($info) {
            // 水印参数
            $thumb = $this->request->post('thumb/d', 0);
            // 水印功能
            if ($thumb) {
                if ($dir == 'images' && config('upload_thumb_water') == 1 && config('upload_thumb_water_pic') != "") {
                    model('Attachment')->create_water($info->getRealPath(), config('upload_thumb_water_pic'));
                }
            }
            // 获取附件信息
            $file_info = [
                'aid'    => $this->admin_id,
                'uid'    => $this->user_id,
                'name'   => substr(htmlspecialchars(strip_tags($this->fileInfo['name'])), 0, 100),
                'mime'   => $this->fileInfo['type'],
                'path'   => config('public_url') . $uploadDir . $info->getSaveName(),
                'ext'    => $this->fileInfo['suffix'],
                'size'   => $this->fileInfo['size'],
                'md5'    => $md5,
                'sha1'   => $sha1,
                'module' => $module,
            ];
            if ($file_add = Attachment_Model::create($file_info)) {
                return json([
                    'code'    => 0,
                    'info'    => $file_info['name'] . '上传成功',
                    'id'      => $file_add['id'],
                    'path'    => $file_info['path'],
                    "state"   => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS" 兼容百度
                    "url"     => $file_info['path'], // 返回的地址 兼容百度
                    "title"   => $file_info['name'], // 附件名 兼容百度
                    "success" => 1, //兼容editormd
                    "message" => $file_info['name'], // 附件名 兼容editormd
                ]);
            } else {
                return json([
                    'code'    => 0,
                    'info'    => '上传成功,写入数据库失败',
                    'state'   => '上传成功,写入数据库失败', //兼容百度
                    'message' => '上传成功,写入数据库失败', //兼容editormd
                ]);
            }
        } else {
            return json([
                'code'    => -1,
                'info'    => $this->file->getError(),
                'state'   => '上传失败', //兼容百度
                'message' => '上传失败', //兼容editormd
            ]);
        }
    }

    protected function checkSize($dir)
    {
        // 附件大小限制
        $size_limit = $dir == 'images' ? config('upload_image_size') : config('upload_file_size');
        $size_limit = $size_limit * 1024;
        // 判断附件大小是否超过限制
        if ($size_limit > 0 && ($this->fileInfo['size'] > $size_limit)) {
            return json([
                'status'  => 0,
                'info'    => '附件过大',
                'state'   => '附件过大', //兼容百度
                'message' => '附件过大', //兼容editormd
            ]);
        }
        return true;
    }

    protected function checkExecutable($dir)
    {
        // 附件类型限制
        $ext_limit = $dir == 'images' ? config('upload_image_ext') : config('upload_file_ext');
        $ext_limit = $ext_limit != '' ? parse_attr($ext_limit) : '';
        // 判断附件格式是否符合
        $file_ext  = $this->fileInfo['suffix'];
        $error_msg = '';
        if ($ext_limit == '') {
            $error_msg = '获取文件后缀限制信息失败！';
        }
        if (in_array($this->fileInfo['type'], ['text/x-php', 'text/html'])) {
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
            return json([
                'code'    => -1,
                'info'    => $error_msg,
                'state'   => $error_msg, //兼容百度
                'message' => $error_msg, //兼容editormd
            ]);
        }
        return true;
    }

    protected function getSavekey($dir, $savekey = null, $filename = null, $md5 = null)
    {
        if ($filename) {
            $suffix = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $suffix = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';
        } else {
            $suffix = $this->fileInfo['suffix'];
        }
        $filename   = $filename ? $filename : ($suffix ? substr($this->fileInfo['name'], 0, strripos($this->fileInfo['name'], '.')) : $this->fileInfo['name']);
        $md5        = $md5 ? $md5 : md5_file($this->fileInfo['tmp_name']);
        $replaceArr = [
            '{dir}'      => $dir,
            '{year}'     => date("Y"),
            '{mon}'      => date("m"),
            '{day}'      => date("d"),
            '{hour}'     => date("H"),
            '{min}'      => date("i"),
            '{sec}'      => date("s"),
            '{random}'   => \util\Random::alnum(16),
            '{random32}' => \util\Random::alnum(32),
            '{filename}' => substr($filename, 0, 100),
            '{suffix}'   => $suffix,
            '{.suffix}'  => $suffix ? '.' . $suffix : '',
            '{filemd5}'  => $md5,
        ];
        $savekey = $savekey ? $savekey : config('savekey');
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);
        return $savekey;
    }

    private function ueditor()
    {
        $action = $this->request->get('action');
        switch ($action) {
            /* 获取配置信息 */
            case 'config':
                $result = $this->editorConfig;
                break;
            /* 上传图片 */
            case 'uploadimage':
                return $this->saveFile('images', 'ueditor');
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
                return $this->saveFile('images', 'ueditor_scrawl');
                break;
            /* 上传视频 */
            case 'uploadvideo':
                return $this->saveFile('videos', 'ueditor');
                break;
            /* 上传附件 */
            case 'uploadfile':
                return $this->saveFile('files', 'ueditor');
                break;
            /* 列出图片 */
            case 'listimage':
                return $this->showFileList('listimage');
                break;

            /* 列出附件 */
            case 'listfile':
                return $this->showFileList('listfile');
                break;
            default:
                $result = array(
                    'state' => '请求地址出错',
                );
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                return htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                return json(['state' => 'callback参数不合法']);
            }
        } else {
            return json($result);
        }
    }

    /**
     * @param string $type 类型
     * @param $config
     * @return \think\response\Json
     */
    protected function showFileList($type = '')
    {
        /* 获取参数 */
        $size      = input('get.size/d', 0);
        $start     = input('get.start/d', 0);
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
                "list"  => [],
                "start" => $start,
                "total" => 0
            ));
        }
        $uploadUrl = config('public_url');
        $list      = [];
        $i         = 0;
        foreach ($filelist as $value) {
            $list[$i]['id']    = $value['id'];
            $list[$i]['url']   = $value['path'];
            $list[$i]['name']  = $value['name'];
            $list[$i]['size']  = format_bytes($value['size']);
            $list[$i]['mtime'] = $value['create_time'];
            $i++;
        }
        /* 返回数据 */
        $result = array(
            "state" => "SUCCESS",
            "list"  => $list,
            "start" => $start,
            "total" => Attachment_Model::where('ext', 'in', $allowExit)->count(),
        );
        return json($result);
    }
}
