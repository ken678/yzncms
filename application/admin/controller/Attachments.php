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
namespace app\admin\controller;

use app\common\controller\Adminbase;
use app\common\model\Attachment as AttachmentModel;
use think\facade\Hook;

class Attachments extends Adminbase
{
    private $uploadUrl      = '';
    protected $searchFields = 'id,name';

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AttachmentModel;
        $this->uploadUrl  = config('public_url') . 'uploads/';
    }

    //附件列表页
    public function index()
    {
        if ($this->request->isAjax()) {
            list($page, $limit, $where) = $this->buildTableParames();
            $_list                      = AttachmentModel::where($where)->page($page, $limit)->order('id', 'desc')->select();
            $total                      = AttachmentModel::where($where)->order('id', 'desc')->count();
            $result                     = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    //附件选
    public function select()
    {
        if ($this->request->isAjax()) {
            return $this->index();
        }
        $mimetype = $this->request->get('mimetype/s', '');
        $this->assign('mimetype', $mimetype);
        return $this->fetch();
    }

    public function cropper()
    {
        return $this->fetch();
    }

    //附件删除
    public function del()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('请选择需要删除的附件！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        $isAdministrator = $this->auth->isAdministrator();
        Hook::add('upload_delete', function ($params) {
            if ($params['driver'] == 'local') {
                $attachmentFile = ROOT_PATH . '/public' . $params['path'];
                if (is_file($attachmentFile)) {
                    @unlink($attachmentFile);
                }
            }
        });
        $attachmentlist = AttachmentModel::where('id', 'in', $ids)->select();
        foreach ($attachmentlist as $attachment) {
            Hook::listen("upload_delete", $attachment);
            $attachment->delete();
        }
        $this->success('文件删除成功~');
    }

    /**
     * html代码远程图片本地化
     * @param string $content html代码
     * @param string $type 文件类型
     */
    public function getUrlFile()
    {
        $content = $this->request->post('content');
        $type    = $this->request->post('type');
        $urls    = [];
        $urls    = \util\GetImgSrc::srcList($content);
        $urls    = array_filter(array_map(function ($val) {
            //http开头验证
            if (strpos($val, "http") === 0) {
                return $val;
            }
        }, $urls));

        $file_info = [
            'aid'    => $this->auth->id,
            'module' => 'admin',
            'thumb'  => '',
        ];
        foreach ($urls as $vo) {
            $vo   = trim(urldecode($vo));
            $host = parse_url($vo, PHP_URL_HOST);
            if ($host != $_SERVER['HTTP_HOST']) {
                //当前域名下的文件不下载
                $fileExt = strrchr($vo, '.'); //$fileExt = '.jpg';非正常后缀图片可以强制设置图片后缀进行抓取下载
                if (!in_array($fileExt, ['.jpg', '.gif', '.png', '.bmp', '.jpeg', '.tiff'])) {
                    exit($content);
                }
                //图片是否合法
                $imgInfo = getimagesize($vo);
                if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                    exit($content);
                }
                $filename = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'temp' . DS . md5($vo) . $fileExt;
                if (http_down($vo, $filename) !== false) {
                    $file_info['md5'] = hash_file('md5', $filename);
                    if ($file_exists = AttachmentModel::get(['md5' => $file_info['md5']])) {
                        unlink($filename);
                        $localpath = $file_exists['path'];
                    } else {
                        $file_info['sha1'] = hash_file('sha1', $filename);
                        $file_info['size'] = filesize($filename);
                        $file_info['mime'] = mime_content_type($filename);

                        $fpath    = $type . DS . date('Ymd');
                        $savePath = ROOT_PATH . 'public' . DS . 'uploads' . DS . $fpath;
                        if (!is_dir($savePath)) {
                            mkdir($savePath, 0755, true);
                        }
                        $fname             = DS . md5(microtime(true)) . $fileExt;
                        $file_info['name'] = substr(htmlspecialchars(strip_tags($vo)), 0, 100);
                        $file_info['path'] = $this->uploadUrl . str_replace(DS, '/', $fpath . $fname);
                        $file_info['ext']  = ltrim($fileExt, ".");

                        if (rename($filename, $savePath . $fname)) {
                            AttachmentModel::create($file_info);
                            $localpath = $file_info['path'];
                        }
                    }
                    $content = str_replace($vo, $localpath, $content);
                }
            }
        }
        exit($content);
    }

}
