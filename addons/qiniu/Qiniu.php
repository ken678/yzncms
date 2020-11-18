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
// | 七牛云插件
// +----------------------------------------------------------------------
namespace addons\qiniu;

use app\admin\service\User as admin_user;
use app\attachment\model\Attachment as Attachment_Model;
use app\member\service\User as home_user;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Addons;
use think\Db;

require ADDON_PATH . 'qiniu/SDK/autoload.php';

class Qiniu extends Addons
{
    //上传用户ID
    public $admin_id = 0;
    public $user_id  = 0;
    //会员组
    public $groupid = 0;
    //是否后台
    public $isadmin     = 0;
    private $uploadUrl  = '';
    private $uploadPath = '';

    /**
     * 上传附件
     */
    public function uploadAfter($params = [])
    {
        $this->isLogin();
        $file   = $params['file'];
        $config = get_addon_config('qiniu');

        $error_msg = '';
        if ($config['accessKey'] == '') {
            $error_msg = '未填写七牛【AccessKey】';
        } elseif ($config['secrectKey'] == '') {
            $error_msg = '未填写七牛【SecretKey】';
        } elseif ($config['bucket'] == '') {
            $error_msg = '未填写七牛【Bucket】';
        } elseif ($config['domain'] == '') {
            $error_msg = '未填写七牛【Domain】';
        }
        if ($error_msg != '') {
            return json([
                'code'  => -1,
                'info'  => $error_msg,
                'state' => $error_msg, //兼容百度
            ]);
        }
        // 文件信息
        $config['domain'] = rtrim($config['domain'], '/') . '/';
        // 移动到框架应用根目录/uploads/ 目录下
        //$info = $file->move($this->uploadPath . DIRECTORY_SEPARATOR . 'temp', '');
        // 文件信息
        $info = $file->getInfo();
        // 要上传文件的本地路径
        $filePath = $info['tmp_name'];
        // 上传到七牛后保存的文件名
        $file_name = explode('.', $info['name']);
        $ext       = end($file_name);
        $key       = $file->hash('md5') . '.' . $ext;
        // 构建鉴权对象
        $auth = new Auth($config['accessKey'], $config['secrectKey']);
        // 初始化空间
        $bucket = $config['bucket'];
        // 生成上传 Token
        $token = $auth->uploadToken($bucket, $key);
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return json([
                'code'    => -1,
                'info'    => '上传失败:' . $err,
                'state'   => '上传失败:' . $err, //兼容百度
                'message' => '上传失败:' . $err, //兼容editormd
            ]);
        } else {
            // 获取附件信息
            $data = [
                'aid'    => $this->admin_id,
                'uid'    => $this->user_id,
                'name'   => $info['name'],
                'mime'   => $info['type'],
                'path'   => $config['domain'] . $key . '?v=' . rand(111111, 999999),
                'ext'    => $file->getExtension(),
                'size'   => $file->getSize(),
                'md5'    => $file->hash('md5'),
                'sha1'   => $file->hash('sha1'),
                'module' => $params['module'],
                'driver' => 'qiniu',
            ];
            if ($file_add = Attachment_Model::create($data)) {
                // 返回结果
                return json([
                    'code'    => 0,
                    'info'    => $data['name'] . '上传成功',
                    'id'      => $file_add['id'],
                    'path'    => $data['path'],
                    "state"   => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS" 兼容百度
                    "url"     => $data['path'], // 返回的地址 兼容百度
                    "title"   => $data['name'], // 附件名 兼容百度
                    "success" => 1, //兼容editormd
                    "message" => $data['name'], // 附件名 兼容editormd
                ]);
            } else {
                return json([
                    'code'    => 0,
                    'info'    => '上传成功,写入数据库失败',
                    'state'   => '上传成功,写入数据库失败', //兼容百度
                    'message' => '上传成功,写入数据库失败', //兼容editormd
                ]);
            }

        }
    }

    /**
     * 删除附件
     */
    public function uploadDelete($params = [])
    {

    }

    //安装
    public function install()
    {
        $upload_driver = Db::name('config')->where(['name' => 'upload_driver', 'group' => 'upload'])->find();
        if (!$upload_driver) {
            $this->error = '未找到【上传驱动】配置';
            return false;
        }
        $options = parse_attr($upload_driver['options']);
        if (isset($options['qiniu'])) {
            $this->error = '已存在名为【qiniu】的上传驱动';
            return false;
        }
        $upload_driver['options'] .= PHP_EOL . 'qiniu:七牛云';

        $result = Db::name('config')
            ->where(['name' => 'upload_driver', 'group' => 'upload'])
            ->setField('options', $upload_driver['options']);

        if (false === $result) {
            $this->error = '上传驱动设置失败';
            return false;
        }
        return true;
    }

    //卸载
    public function uninstall()
    {
        $upload_driver = Db::name('config')->where(['name' => 'upload_driver', 'group' => 'upload'])->find();
        if ($upload_driver) {
            $options = parse_attr($upload_driver['options']);
            if (isset($options['qiniu'])) {
                unset($options['qiniu']);
            }
            $options = $this->implode_attr($options);
            $result  = Db::name('config')
                ->where(['name' => 'upload_driver', 'group' => 'upload'])
                ->update(['options' => $options, 'value' => 'local']);

            if (false === $result) {
                $this->error = '上传驱动设置失败';
                return false;
            }
        }
        return true;
    }

    protected function isLogin()
    {
        //检查是否后台登录，后台登录下优先级最高，用于权限判断
        if (admin_user::instance()->isLogin()) {
            $this->isadmin  = 1;
            $this->admin_id = admin_user::instance()->id;
        } elseif (home_user::instance()->isLogin()) {
            $this->user_id = home_user::instance()->id;
            $this->groupid = home_user::instance()->groupid ? home_user::instance()->groupid : 8;
        } else {
            $this->user_id = 0;
            //return $this->error('未登录');
        }
    }

    protected function implode_attr($array = [])
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $key . ':' . $value;
        }
        return empty($result) ? '' : implode(PHP_EOL, $result);
    }

}
