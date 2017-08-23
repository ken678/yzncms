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
use think\Controller;

class Attachments extends Controller
{
	/**
     * 保存附件
     * @param string $dir 附件存放的目录
     * @param string $from 来源
     * @param string $module 来自哪个模块
     * @return [type]
     */
    public function saveFile($dir = '', $from = '', $module = '')
    {
        switch ($from) {
            case 'ueditor_scrawl':
                //return $this->saveScrawl();
                break;
            default:
                $file_input_name = 'file';
        }
        $file = $this->request->file($file_input_name);

        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(config('upload_path') . DS . $dir);
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
        	switch ($from) {
				case 'ueditor':
				    return json([
						"state" => "SUCCESS",          //上传状态，上传成功时必须返回"SUCCESS"
						"url" => $file_info['path'],            //返回的地址
						"title" => $info->getFilename(),          //新文件名
						"original" => $file_info['name'],       //原始文件名
						"type" => $file_info['mime'],           //文件类型
						"size" => $file_info['size'],           //文件大小
				    ]);
				    break;
        	}
        }else{
        	//上传失败
        	switch ($from) {
                case 'ueditor':
                    return json(['state' => $info->getError()? : '上传失败']);
                    break;
                default:
                    return json(['code' => 0, 'class' => 'danger', 'info' => $file->getError()]);
            }
        }



    }



}
