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

class Upload extends Base
{
    //上传用户
    public $upname = null;
    //上传用户ID
    public $upuserid = 0;
    //会员组
    public $groupid = 0;
    //是否后台
    public $isadmin = 0;
    //上传模块
    public $module = 'cms';
    private $uploadUrl = '';
    private $uploadPath = '';

    protected function initialize()
    {
        parent::initialize();
        //检查是否后台登录，后台登录下优先级最高，用于权限判断
        if (admin_user::instance()->isLogin()) {
            $this->isadmin = 1;
            $this->upname = admin_user::instance()->username;
            $this->upuserid = admin_user::instance()->id;
        } elseif (home_user::instance()->isLogin()) {
            $this->upname = home_user::instance()->username;
            $this->upuserid = home_user::instance()->id;
            $this->groupid = home_user::instance()->groupid ? home_user::instance()->groupid : 8;
        } else {
            return $this->error('未登录');
        }
        $this->uploadUrl = config('public_url') . 'uploads/';
        $this->uploadPath = config('upload_path');
    }

    public function upload($dir = '', $from = '', $module = '', $thumb = 0, $thumbsize = '', $thumbtype = '', $watermark = 1, $sizelimit = -1, $extlimit = '')
    {
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
        return $this->saveFile($dir, $from, $module, $thumb, $thumbsize, $thumbtype, $watermark, $sizelimit, $extlimit);
    }

    /**
     * 检查是否可以上传
     */
    protected function isUpload($module)
    {
        $module_list = cache('Module');
        if ($module_list[strtolower($module)] || strtolower($module) == 'admin') {
            $this->module = strtolower($module);
        } else {
            return false;
        }
        //如果是前台上传，判断用户组权限
        if ($this->isadmin == 0) {
            $member_group = cache('Member_Group');
            if ((int) $member_group[$this->groupid]['allowattachment'] < 1) {
                return "所在的用户组没有附件上传权限！";
            }
        }
        return true;
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
                'uid' => $this->upuserid,
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

}
