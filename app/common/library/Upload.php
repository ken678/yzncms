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
// | Original reference: https://gitee.com/karson/fastadmin
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 上传基础类
// +----------------------------------------------------------------------
namespace app\common\library;

use app\common\exception\UploadException;
use app\common\model\Attachment;
use FilesystemIterator;
use think\facade\Config;
use think\facade\Event;
use think\File;
use think\file\UploadedFile;

class Upload
{
    protected $config   = [];
    protected $file     = null;
    protected $fileInfo = null;
    protected $merging  = false;
    protected $chunkDir = null;

    public function __construct($file = null)
    {
        $this->config   = Config::get('upload');
        $this->chunkDir = runtime_path() . 'chunks';
        if ($file) {
            $this->setFile($file);
        }
    }

    public function setFile($file)
    {
        if (empty($file)) {
            throw new UploadException('未上传文件或超出服务器上传限制');
        }
        $fileInfo['type']     = $file->getOriginalMime();
        $fileInfo['size']     = $file->getSize();
        $fileInfo['name']     = $file->getOriginalName();
        $fileInfo['tmp_name'] = $file->getPathname();

        $suffix                  = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix                  = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';
        $fileInfo['suffix']      = $suffix;
        $fileInfo['imagewidth']  = 0;
        $fileInfo['imageheight'] = 0;
        $this->file              = $file;
        $this->fileInfo          = $fileInfo;
        $this->checkExecutable();
    }

    protected function checkExecutable()
    {
        //禁止上传PHP和HTML文件
        if (in_array($this->fileInfo['type'], ['text/x-php', 'text/html']) || in_array($this->fileInfo['suffix'], ['php', 'asp', 'exe', 'cmd', 'sh', 'bat', 'html', 'htm', 'phtml', 'phar']) || preg_match("/^php(.*)/i", $this->fileInfo['suffix'])) {
            throw new UploadException('上传文件格式受限制');
        }
        return true;
    }

    /**
     * 检测是否图片
     * @param bool $force
     * @return bool
     * @throws UploadException
     */
    protected function checkImage($force = false)
    {
        //验证是否为图片文件
        if (in_array($this->fileInfo['type'], ['image/gif', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/png', 'image/webp']) || in_array($this->fileInfo['suffix'], ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'webp', 'wbmp'])) {
            $imgInfo = getimagesize($this->fileInfo['tmp_name']);
            if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                throw new UploadException('上传文件不是有效的图片文件');
            }
            $this->fileInfo['imagewidth']  = $imgInfo[0] ?? 0;
            $this->fileInfo['imageheight'] = $imgInfo[1] ?? 0;
            return true;
        } else {
            return !$force;
        }
    }

    protected function checkSize()
    {
        preg_match('/([0-9\.]+)(\w+)/', $this->config['maxsize'], $matches);
        $size     = $matches ? $matches[1] : $this->config['maxsize'];
        $type     = $matches ? strtolower($matches[2]) : 'b';
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size     = $size * pow(1024, $typeDict[$type] ?? 0);
        if ($this->fileInfo['size'] > $size) {
            throw new UploadException(
                '当前上传 (' . round($this->fileInfo['size'] / pow(1024, 2), 2) . 'MiB)，最大允许上传文件大小：' . round($size / pow(1024, 2), 2) . 'MiB。'
            );
        }
    }

    protected function checkMimetype()
    {
        $mimetypeArr = explode(',', strtolower($this->config['mimetype']));
        $typeArr     = explode('/', $this->fileInfo['type']);
        //Mimetype值不正确
        if (stripos($this->fileInfo['type'], '/') === false) {
            throw new UploadException('上传文件格式受限制');
        }
        //验证文件后缀
        if ($this->config['mimetype'] === '*'
            || in_array($this->fileInfo['suffix'], $mimetypeArr) || in_array('.' . $this->fileInfo['suffix'], $mimetypeArr)
            || in_array($typeArr[0] . "/*", $mimetypeArr) || (in_array($this->fileInfo['type'], $mimetypeArr) && stripos($this->fileInfo['type'], '/') !== false)) {
            return true;
        }
        throw new UploadException('上传文件格式受限制');
    }

    /**
     * 保存附件
     * @param string $dir 附件存放的目录
     * @param string $from 来源
     * @return string|\think\response\Json
     */
    public function upload($dir = '', $from = '', $savekey = null)
    {
        if (empty($this->file)) {
            throw new UploadException('未上传文件或超出服务器上传限制');
        }
        $this->checkSize();
        $this->checkMimetype();
        $this->checkExecutable();
        $this->checkImage();

        // 判断附件是否已存在
        if ($file_exists = Attachment::getByMd5($this->file->hash('md5'))) {
            return json([
                'code'    => 1,
                'msg'     => $file_exists['name'] . '上传成功',
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
        if (config::get('site.upload_driver') != 'local') {
            $hook_result = Event::trigger('upload_after', ['dir' => $dir, 'file' => $this->file, 'from' => $from], true);
            if (false !== $hook_result) {
                return $hook_result;
            }
        }

        $savekey   = $savekey ?: $this->getSavekey($dir);
        $savekey   = '/' . ltrim($savekey, '/');
        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName  = substr($savekey, strripos($savekey, '/') + 1);
        $destDir   = public_path() . str_replace('/', DS, $uploadDir);

        $sha1 = $this->file->hash();
        $md5  = $this->file->md5();

        //如果是合并文件
        if ($this->merging) {
            $destFile   = $destDir . $fileName;
            $sourceFile = $this->file->getRealPath() ?: $this->file->getPathname();
            $this->file = null;
            if (!is_dir($destDir)) {
                @mkdir($destDir, 0755, true);
            }
            rename($sourceFile, $destFile);
            $file = new UploadedFile($destFile, $fileName);
        } else {
            // 移动到框架应用根目录指定目录下
            $file = $this->file->move($destDir, $fileName);
            if (!$file) {
                // 上传失败获取错误信息
                throw new UploadException($this->file->getError());
            }
        }
        $this->file = $file;
        // 获取附件信息
        $auth      = Auth::instance();
        $file_info = [
            'admin_id'    => (int) session('admin.id'),
            'user_id'     => (int) $auth->id,
            'name'        => mb_substr(htmlspecialchars(strip_tags($this->fileInfo['name'])), 0, 100),
            'mime'        => $this->fileInfo['type'],
            'path'        => cdnurl($uploadDir . $fileName),
            'ext'         => $this->fileInfo['suffix'],
            'size'        => $this->fileInfo['size'],
            'imagewidth'  => $this->fileInfo['imagewidth'],
            'imageheight' => $this->fileInfo['imageheight'],
            'md5'         => $md5,
            'sha1'        => $sha1,
            'driver'      => 'local',
        ];

        $attachment = new Attachment();
        $attachment->data(array_filter($file_info));
        $attachment->save();

        Event::trigger("upload_after", $attachment);

        return json([
            'code'    => 1,
            'msg'     => $file_info['name'] . '上传成功',
            'id'      => $attachment->id,
            'path'    => $file_info['path'],
            "state"   => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS" 兼容百度
            "url"     => $file_info['path'], // 返回的地址 兼容百度
            "title"   => $file_info['name'], // 附件名 兼容百度
            "success" => 1, //兼容editormd
            "message" => $file_info['name'], // 附件名 兼容editormd
        ]);

    }

    /**
     * 合并分片文件
     * @param string $chunkid
     * @param int    $chunkcount
     * @param string $filename
     * @return attachment|\think\Model
     * @throws UploadException
     */
    public function merge($chunkid, $chunkcount, $filename, $dir, $from)
    {
        if (!preg_match('/^[a-z0-9\-]{36}$/', $chunkid)) {
            throw new UploadException('未知参数');
        }

        $filePath  = $this->chunkDir . DS . $chunkid;
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
            throw new UploadException('分片文件错误');
        }

        //如果所有文件分片都上传完毕，开始合并
        $uploadPath = $filePath;
        if (!$destFile = @fopen($uploadPath, "wb")) {
            $this->clean($chunkid);
            throw new UploadException('分片合并错误');
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
            $file = new UploadedFile($uploadPath, $filename, (new File($uploadPath))->getMime(), null, true);
            //重新设置文件
            $this->setFile($file);

            unset($file);
            $this->merging = true;

            //允许大文件
            $this->config['maxsize'] = "1024G";

            $attachment = $this->upload($dir, $from);
        } catch (\Exception $e) {
            @unlink($destFile);
            throw new UploadException($e->getMessage());
        }
        return $attachment;
    }

    /**
     * 清理分片文件
     * @param $chunkid
     */
    public function clean($chunkid)
    {
        if (!preg_match('/^[a-z0-9\-]{36}$/', $chunkid)) {
            throw new UploadException('未知参数');
        }
        $iterator = new \GlobIterator($this->chunkDir . DS . $chunkid . '-*', FilesystemIterator::KEY_AS_FILENAME);
        $array    = iterator_to_array($iterator);
        foreach ($array as &$item) {
            $sourceFile = $item->getRealPath() ?: $item->getPathname();
            $item       = null;
            @unlink($sourceFile);
        }
    }

    /**
     * 分片上传
     */
    public function chunk($chunkid, $chunkindex, $chunkcount, $chunkfilesize = null, $chunkfilename = null, $direct = false)
    {
        if ($this->fileInfo['type'] != 'application/octet-stream') {
            throw new UploadException('上传文件格式受限制');
        }
        if (!preg_match('/^[a-z0-9\-]{36}$/', $chunkid)) {
            throw new UploadException('未知参数');

        }
        $fileName = $chunkid . "-" . $chunkindex . '.part';
        $destFile = $this->chunkDir . DS . $fileName;
        if (!is_dir($this->chunkDir)) {
            @mkdir($this->chunkDir, 0755, true);
        }
        if (!move_uploaded_file($this->file->getPathname(), $destFile)) {
            throw new UploadException('分片写入失败');
        }
        $file = new UploadedFile($destFile, $fileName, (new File($destFile))->getMime());
        $this->setFile($file);
        return $file;
    }

    protected function getSavekey($dir, $savekey = null, $filename = null, $md5 = null)
    {
        if ($filename) {
            $suffix = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        } else {
            $suffix = $this->fileInfo['suffix'] ?? '';
        }
        $suffix     = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';
        $filename   = $filename ?: ($this->fileInfo['name'] ?? 'unknown');
        $filename   = xss_clean(strip_tags(htmlspecialchars($filename)));
        $fileprefix = substr($filename, 0, strripos($filename, '.'));
        $md5        = $md5 ? $md5 : (isset($this->fileInfo['tmp_name']) ? md5_file($this->fileInfo['tmp_name']) : '');
        $replaceArr = [
            '{dir}'        => $dir,
            '{year}'       => date("Y"),
            '{mon}'        => date("m"),
            '{day}'        => date("d"),
            '{hour}'       => date("H"),
            '{min}'        => date("i"),
            '{sec}'        => date("s"),
            '{random}'     => \util\Random::alnum(16),
            '{random32}'   => \util\Random::alnum(32),
            '{filename}'   => substr($filename, 0, 100),
            '{fileprefix}' => substr($fileprefix, 0, 100),
            '{suffix}'     => $suffix,
            '{.suffix}'    => $suffix ? '.' . $suffix : '',
            '{filemd5}'    => $md5,
        ];
        $savekey = $savekey ?: $this->config['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);
        return $savekey;
    }
}
