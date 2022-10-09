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
// | 阿里云OSS插件
// +----------------------------------------------------------------------
namespace addons\alioss;

use app\attachment\model\Attachment;
use OSS\Core\OssException;
use OSS\OssClient;
use think\Addons;
use think\Db;
use think\Loader;

class Alioss extends Addons
{
    /**
     * 上传附件
     */
    public function uploadAfter($params = [])
    {
        $file      = $params['file'];
        $config    = $this->getAddonConfig();
        $error_msg = '';
        if ($config['accessKey'] == '') {
            $error_msg = '未填写阿里云OSS【AccessKey】';
        } elseif ($config['secrectKey'] == '') {
            $error_msg = '未填写阿里云OSS【SecretKey】';
        } elseif ($config['bucket'] == '') {
            $error_msg = '未填写阿里云OSS【Bucket】';
        } elseif ($config['endpoint'] == '') {
            $error_msg = '未填写阿里云OSS【endpoint】';
        } elseif ($config['domain'] == '') {
            $error_msg = '未填写阿里云OSS【Domain】';
        }
        if ($error_msg != '') {
            return json([
                'code'  => -1,
                'info'  => $error_msg,
                'state' => $error_msg, //兼容百度
            ]);
        }

        // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录https://ram.console.aliyun.com创建RAM账号。
        $accessKeyId     = $config['accessKey'];
        $accessKeySecret = $config['secrectKey'];
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = $config['endpoint'];
        // 存储空间名称
        $bucket = $config['bucket'];
        //图片处理的规则名称stylename
        $stylename = '';
        if (isset($config['stylename']) && $config['stylename']) {
            if (isset($config['separator']) && $config['separator']) {
                $stylename = trim($config['separator']) . trim($config['stylename']);
            } else {
                $stylename = '?x-oss-process=style/' . trim($config['stylename']);
            }

        }
        // 文件信息
        $info   = $file->getInfo();
        $suffix = strtolower(pathinfo($info['name'], PATHINFO_EXTENSION));
        $suffix = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';

        // 要上传文件的本地路径
        $filePath = $info['tmp_name'];

        $file_name = explode('.', $info['name']);
        $ext       = end($file_name);
        $object    = $params['dir'] . '/' . date('Ymd') . '/' . $file->hash('md5') . '.' . $ext;

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket, $object, $filePath);
        } catch (OssException $e) {
            return json([
                'code'    => -1,
                'info'    => $e->getMessage(),
                'state'   => $e->getMessage(), //兼容百度
                'message' => $e->getMessage(), //兼容editormd
            ]);
        }
        // 获取附件信息
        $data = [
            'aid'    => (int) session('admin.id'),
            'uid'    => (int) cookie('uid'),
            'name'   => $info['name'],
            'mime'   => $info['type'],
            'path'   => $config['domain'] . $object . $stylename,
            'ext'    => $suffix,
            'size'   => $file->getSize(),
            'md5'    => $file->hash('md5'),
            'sha1'   => $file->hash('sha1'),
            'driver' => 'alioss',
        ];
        if ($file_add = Attachment::create($data)) {
            // 返回结果
            return json([
                'code'    => 1,
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

    /**
     * 删除附件
     */
    public function uploadDelete($params = [])
    {
        $config          = $this->getAddonConfig();
        $accessKeyId     = $config['accessKey'];
        $accessKeySecret = $config['secrectKey'];
        $endpoint        = $config['endpoint'];
        $bucket          = $config['bucket'];
        $object          = str_replace($config['domain'], '', $params['path']);
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->deleteObject($bucket, $object);
        } catch (OssException $e) {

        }
        return true;

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
        if (isset($options['alioss'])) {
            $this->error = '已存在名为【alioss】的上传驱动';
            return false;
        }
        $upload_driver['options'] .= PHP_EOL . 'alioss:阿里云';

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
            if (isset($options['alioss'])) {
                unset($options['alioss']);
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

    protected function implode_attr($array = [])
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $key . ':' . $value;
        }
        return empty($result) ? '' : implode(PHP_EOL, $result);
    }

    /**
     * 添加命名空间
     */
    public function appInit()
    {
        Loader::addNamespace('OSS', ADDON_PATH . 'alioss' . DS . 'SDK' . DS . 'src' . DS . 'OSS' . DS);
    }

}
