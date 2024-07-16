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
namespace app\admin\controller\general;

use app\common\controller\Backend;
use app\common\model\Attachment as AttachmentModel;
use think\facade\Event;

class Attachments extends Backend
{
    private $uploadUrl      = '';
    protected $searchFields = 'id,name';

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AttachmentModel;
        $this->uploadUrl  = config('upload.cdnurl') . '/uploads/';
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $mimetypeQuery = [];
            $allGet        = $this->request->request();
            $filterArr     = isset($allGet['filter']) ? (array) json_decode($allGet['filter'], true) : [];
            if (isset($filterArr['mime']) && preg_match("/(\/|\,|\*)/", $filterArr['mime'])) {
                $mimetype      = $filterArr['mime'];
                $filterArr     = array_diff_key($filterArr, ['mime' => '']);
                $mimetypeQuery = function ($query) use ($mimetype) {
                    $mimetypeArr = array_filter(explode(',', $mimetype));
                    foreach ($mimetypeArr as $index => $item) {
                        $query->whereOr('mime', 'like', '%' . str_replace("/*", "/", $item) . '%');
                    }
                };
            }
            $allGet['filter'] = json_encode($filterArr);
            $this->request->withGet($allGet);

            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

            $count = $this->modelClass
                ->where($where)
                ->where($mimetypeQuery)
                ->order($sort, $order)
                ->count();

            $data = $this->modelClass
                ->where($where)
                ->where($mimetypeQuery)
                ->order($sort, $order)
                ->page($page, $limit)
                ->select();
            $result = ["code" => 0, 'count' => $count, 'data' => $data];
            return json($result);
        }
        return $this->fetch();
    }

    //附件选择
    public function select()
    {
        if ($this->request->isAjax()) {
            return $this->index();
        }
        $mimetype = $this->request->get('mimetype/s', '');
        $mimetype = substr($mimetype, -1) === '/' ? $mimetype . '*' : $mimetype;
        $this->assignconfig('mimetype', $mimetype);
        return $this->fetch();
    }

    public function cropper()
    {
        return $this->fetch();
    }

    //附件删除
    public function del()
    {
        if (false === $this->request->isPost()) {
            $this->error('未知参数');
        }
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('请选择需要删除的附件！');
        }
        if (!is_array($ids)) {
            $ids = [0 => $ids];
        }
        $isAdministrator = $this->auth->isAdministrator();
        Event::listen('upload_delete', function ($params) {
            if ($params['driver'] == 'local') {
                $attachmentFile = public_path() . $params['path'];
                if (is_file($attachmentFile)) {
                    @unlink($attachmentFile);
                }
            }
        });
        $attachmentlist = AttachmentModel::where('id', 'in', $ids)->select();
        foreach ($attachmentlist as $attachment) {
            Event::trigger("upload_delete", $attachment);
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
            'admin_id' => $this->auth->id,
            'thumb'    => '',
        ];
        foreach ($urls as $vo) {
            $vo   = trim(urldecode($vo));
            $host = parse_url($vo, PHP_URL_HOST);
            if ($host != $_SERVER['HTTP_HOST']) {
                //当前域名下的文件不下载
                $fileExt = strrchr($vo, '.'); //$fileExt = '.jpg';非正常后缀图片可以强制设置图片后缀进行抓取下载
                if (!in_array($fileExt, ['.jpg', '.gif', '.png', '.bmp', '.jpeg', '.tiff'])) {
                    $this->success('', '', $content);
                }
                //图片是否合法
                $imgInfo = getimagesize($vo);
                if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                    $this->success('', '', $content);
                }
                $filename = public_path() . 'uploads' . DS . 'temp' . DS . md5($vo) . $fileExt;
                if (http_down($vo, $filename) !== false) {
                    $file_info['md5'] = hash_file('md5', $filename);
                    if ($file_exists = AttachmentModel::where('md5', $file_info['md5'])->find()) {
                        unlink($filename);
                        $localpath = $file_exists['path'];
                    } else {
                        $file_info['sha1'] = hash_file('sha1', $filename);
                        $file_info['size'] = filesize($filename);
                        $file_info['mime'] = mime_content_type($filename);

                        $fpath    = $type . DS . date('Ymd');
                        $savePath = public_path() . 'uploads' . DS . $fpath;
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
        $this->success('', '', $content);
    }

}
