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
use think\Controller;
use app\common\controller\Adminbase;

class Attachments extends Adminbase
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
                return $this->saveScrawl();
                break;
            default:
                $file_input_name = 'file';
        }
        $file = $this->request->file($file_input_name);

        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move(config('upload_path') . $dir);
        if($info){
        	// 获取附件信息
            $file_info = [
                'name'   => $file->getInfo('name'),//原文件名
                'mime'   => $file->getInfo('type'),//文件类型
                'path'   => WEB_PATH.'uploads/' . $dir . '/' . str_replace('\\', '/', $info->getSaveName()),
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

    /**
     * 显示附件列表(ueditor)
     */
    public function showFile($type = '', $config)
    {
        /* 判断类型 */
        switch ($type) {
            case 'listimage':
            default:
                $allowFiles = $config['imageManagerAllowFiles'];
                $listSize = $config['imageManagerListSize'];
                $path = realpath(config('upload_path') .'/images/');
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        /* 获取附件列表 */
        $files = $this->getfiles($path, $allowFiles);
        if (!count($files)) {
            return json(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }
        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        /* 返回数据 */
        $result = array(
            "state" => "SUCCESS",
            "list"  => $list,
            "start" => $start,
            "total" => count($files)
        );

        return json($result);



    }


    /**
     * 保存涂鸦(ueditor)
     */
    private function saveScrawl()
    {
        $base64Data  = $this->request->post('file');
        if (empty($base64Data)) {
            return json(['state' => '没有涂鸦内容']);
        }
        $file_content = base64_decode($base64Data);
        $file_name    = md5($base64Data) . '.jpg';//图片名称
        $dir          = config('upload_path') . 'images' . '/' . date('Ymd', $this->request->time());
        $file_path    = $dir . DS . $file_name;

         //创建目录失败
        if (!file_exists($dir) && !mkdir($dir, 0777, true)) {
            return json(['state' => '目录创建失败']);
        } else if (!is_writeable($dir)) {
            return json(['state' => '目录没有写权限']);
        }

        if (!(file_put_contents($file_path, $file_content) && file_exists($file_path))) { //移动失败
            return json(['state' => '涂鸦上传出错']);
        }
        // 返回成功信息
        return json([
            "state" => "SUCCESS",          // 上传状态，上传成功时必须返回"SUCCESS"
            "url"   => '/'.$dir. '/' . $file_name, // 返回的地址
            "title" => $file_name, // 附件名
        ]);
    }

    /**
     * 遍历获取目录下的指定类型的附件
     * @param string $path 路径
     * @param string $allowFiles 允许查看的类型
     * @param array $files 文件列表
     * @return array|null
     */
    public function getfiles($path = '', $allowFiles = '', &$files = array())
    {
        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> str_replace("\\", "/", substr($path2, strlen($_SERVER['DOCUMENT_ROOT']))),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

    //WebUploader上传界面
    public function WebUploader()
    {
        //上传个数,允许上传的文件类型,是否允许从已上传中选择,图片高度,图片高度,是否添加水印1是
        $args = Request::instance()->param('args');
        //具体配置参数
        $info = explode(",", $args);
        $this->assign("file_upload_limit", (int) $info[0]);
        return $this->fetch();
    }





}
