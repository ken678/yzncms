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

use think\Request;

/**
 * 百度编辑器
 */
class Ueditor extends Attachments
{

    //编辑器初始配置
    private $confing = array(
        /* 上传图片配置项 */
        'imageActionName' => 'uploadimage',
        'imageFieldName' => 'file',
        'imageMaxSize' => 0, /* 上传大小限制，单位B */
        'imageAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
        'imageCompressEnable' => true,
        'imageCompressBorder' => 1600,
        'imageInsertAlign' => 'none',
        'imageUrlPrefix' => '',
        'imagePathFormat' => '',
        /* 涂鸦图片上传配置项 */
        'scrawlActionName' => 'uploadscrawl',
        'scrawlFieldName' => 'file',
        'scrawlPathFormat' => '',
        'scrawlMaxSize' => 0,
        'scrawlUrlPrefix' => '',
        'scrawlInsertAlign' => 'none',
        /* 截图工具上传 */
        'snapscreenActionName' => 'uploadimage',
        'snapscreenPathFormat' => '',
        'snapscreenUrlPrefix' => '',
        'snapscreenInsertAlign' => 'none',
        /* 抓取远程图片配置 */
        'catcherLocalDomain' => array('127.0.0.1', 'localhost', 'img.baidu.com'),
        'catcherActionName' => 'catchimage',
        'catcherFieldName' => 'source',
        'catcherPathFormat' => '',
        'catcherUrlPrefix' => '',
        'catcherMaxSize' => 0,
        'catcherAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
        /* 上传视频配置 */
        'videoActionName' => 'uploadvideo',
        'videoFieldName' => 'file',
        'videoPathFormat' => '',
        'videoUrlPrefix' => '',
        'videoMaxSize' => 0,
        'videoAllowFiles' => array(".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"),
        /* 上传文件配置 */
        'fileActionName' => 'uploadfile',
        'fileFieldName' => 'file',
        'filePathFormat' => '',
        'fileUrlPrefix' => '',
        'fileMaxSize' => 0,
        'fileAllowFiles' => array(".flv", ".swf"),
        /* 列出指定目录下的图片 */
        'imageManagerActionName' => 'listimage',
        'imageManagerListPath' => '',
        'imageManagerListSize' => 20,
        'imageManagerUrlPrefix' => '',
        'imageManagerInsertAlign' => 'none',
        'imageManagerAllowFiles' => array('.png', '.jpg', '.jpeg', '.gif', '.bmp'),
        /* 列出指定目录下的文件 */
        'fileManagerActionName' => 'listfile',
        'fileManagerListPath' => '',
        'fileManagerUrlPrefix' => '',
        'fileManagerListSize' => '',
        'fileManagerAllowFiles' => array(".flv", ".swf"),
    );

    //初始化
    /*protected function _initialize() {
    //上传文件类型
    }*/

    //编辑器配置
    public function run()
    {
        $action = Request::instance()->param('action'); //上传类型
        $result = array();
        switch ($action) {
            case 'config':
                $result = $this->confing;
                break;
            //上传图片
            case 'uploadimage':
                return $this->saveFile('images', 'ueditor');
                break;
            //上传涂鸦
            case 'uploadscrawl':
                return $this->saveFile('images', 'ueditor_scrawl');
                break;
            //上传附件
            case 'uploadfile':
                break;
            //列出图片
            case 'listimage':
                return $this->showFile('listimage', $this->confing);
                break;
            default:
                $result = array(
                    'state' => '请求地址出错',
                );
                break;
        }
        return json($result);
    }

}
