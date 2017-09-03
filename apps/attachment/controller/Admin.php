<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\attachment\controller;
use app\common\controller\Adminbase;

class Admin extends Adminbase
{
    const isadmin = 1; //是否后台

    //WebUploader上传
    public function WebUploader($dir = 'images',$from='')
    {
        if ($this->request->isPost()){

            /*$key = md5(config("AUTHCODE") . $sess_id . self::isadmin);
            //密钥验证
            if (I("post.swf_auth_key") != $key) {
                exit("0,权限认证失败！" . I("post.swf_auth_key") . "|" . C("AUTHCODE"));
            }*/






        	$file = $this->request->file('upfile');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move(config('upload_path') . $dir);
            if($info){
            	// 获取附件信息
                $file_info = [
                    'name'   => $file->getInfo('name'),//原文件名
                    'mime'   => $file->getInfo('type'),//文件类型
                    'path'   => '/uploads/' . $dir . '/' . str_replace('\\', '/', $info->getSaveName()),
                    'ext'    => $info->getExtension(),//文件后缀
                    'size'   => $info->getSize()//文件大小
                ];
            	//上传成功
                return json([
                    "state" => "SUCCESS",          //上传状态，上传成功时必须返回"SUCCESS"
                    "url" => $file_info['path'],            //返回的地址
                    "title" => $info->getFilename(),          //新文件名
                    "original" => $file_info['name'],       //原始文件名
                    "type" => $file_info['mime'],           //文件类型
                    "size" => $file_info['size'],           //文件大小
                ]);

            }else{
            	//上传失败
                return json(['code' => 0, 'class' => 'danger', 'info' => $file->getError()]);
            }
        }else{
            return json(["state" => "上传失败"]);
        }
    }













}
