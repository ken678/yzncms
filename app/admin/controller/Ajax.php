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
// | 后台常用ajax
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Adminlog;
use app\common\controller\Backend;
use app\common\exception\UploadException;
use app\common\library\Upload as UploadLib;
use Exception;
use think\facade\Db;
use think\facade\Validate;
use think\Response;

class Ajax extends Backend
{
    protected $noNeedRight = ['*'];

    //过滤内容的敏感词
    public function filterWord($content)
    {
        $content = $this->request->post('content');
        // 获取感词库文件路径
        $wordFilePath = app()->getRootPath() . 'data/words.txt';
        $handle       = \util\SensitiveHelper::init()->setTreeByFile($wordFilePath);
        $word         = $handle->getBadWord($content);
        if ($word) {
            $this->error('内容包含违禁词！', null, $word);
        } else {
            $this->success('内容没有违禁词！');
        }
    }

    /**
     * 生成后缀图标
     */
    public function icon()
    {
        $suffix                  = $this->request->request("suffix", 'file');
        $data                    = build_suffix_image($suffix);
        $header                  = ['Content-Type' => 'image/svg+xml'];
        $offset                  = 30 * 60 * 60 * 24; // 缓存一个月
        $header['Cache-Control'] = 'public';
        $header['Pragma']        = 'cache';
        $header['Expires']       = gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        $response                = Response::create($data)->header($header);
        return $response;
    }

    /**
     * 读取省市区数据,联动列表
     */
    public function area()
    {
        $params = $this->request->get("row/a");
        if (!empty($params)) {
            $province = $params['province'] ?? null;
            $city     = $params['city'] ?? null;
        } else {
            $province = $this->request->get('province');
            $city     = $this->request->get('city');
        }
        $where        = ['pid' => 0, 'level' => 1];
        $provincelist = null;
        if ($province !== null) {
            $where['pid']   = $province;
            $where['level'] = 2;
            if ($city !== null) {
                $where['pid']   = $city;
                $where['level'] = 3;
            }
        }
        $provincelist = Db::name('area')->where($where)->field('id as value,name')->select();
        $this->success('', '', $provincelist);
    }

    public function upload($dir = '', $from = '')
    {
        Adminlog::setTitle('附件上传');
        if ($dir == '') {
            $this->error('没有指定上传目录');
        }
        $chunkid = $this->request->post("chunkid");
        if ($chunkid) {
            if (!config('upload.chunking')) {
                $this->error('未开启分片上传功能');
            }
            //分片
            $action     = $this->request->post("action");
            $chunkindex = $this->request->post("chunkindex/d", 0);
            $chunkcount = $this->request->post("chunkcount/d", 1);
            $filename   = $this->request->post("filename");
            $method     = $this->request->method(true);
            if ($action == 'merge') {
                $attachment = null;
                //合并分片文件
                try {
                    $upload     = new UploadLib();
                    $attachment = $upload->merge($chunkid, $chunkcount, $filename, $dir, $from);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success('上传成功', '', [
                    'url'     => $attachment->path,
                    'fullurl' => cdnurl($attachment->path, true),
                ]);
            } elseif ($method == 'clean') {
                //删除冗余的分片文件
                try {
                    $upload = new UploadLib();
                    $upload->clean($chunkid);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            } else {
                //上传分片文件
                $file = $this->request->file('file');
                try {
                    $upload = new UploadLib($file);
                    $upload->chunk($chunkid, $chunkindex, $chunkcount);
                } catch (UploadException $e) {
                    $this->error($e->getMessage());
                }
                $this->success();
            }
        }

        $attachment = null;
        try {
            //默认普通上传文件
            $file       = $this->request->file('file');
            $upload     = new UploadLib($file);
            $attachment = $upload->upload($dir);
        } catch (UploadException | Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('上传成功', '', [
            'url'     => $attachment->path,
            'fullurl' => cdnurl($attachment->path, true),
        ]);

    }

    //通用排序
    public function weigh()
    {
        //排序的数组
        $ids = $this->request->post("ids");
        //拖动的记录ID
        $changeid = $this->request->post("changeid");
        //操作字段
        $field = $this->request->post("field");
        //操作的数据表
        $table = $this->request->post("table");
        if (!Validate::is($table, "alphaDash")) {
            $this->error('表名规则错误');
        }
        //主键
        $pk = $this->request->post("pk");
        //排序的方式
        $orderway = strtolower($this->request->post("orderway", ""));
        $orderway = $orderway == 'asc' ? 'ASC' : 'DESC';
        $sour     = $weighdata     = [];
        $ids      = explode(',', $ids);
        $prikey   = $pk && preg_match("/^[a-z0-9\-_]+$/i", $pk) ? $pk : (Db::name($table)->getPk() ?: 'id');
        $parentid = $this->request->post("pid", "");
        //限制更新的字段
        $field = in_array($field, ['listorder']) ? $field : 'listorder';

        // 如果设定了parentid的值,此时只匹配满足条件的ID,其它忽略
        if ($parentid !== '') {
            $hasids = [];
            $list   = Db::name($table)->where($prikey, 'in', $ids)->where('parentid', 'in', $parentid)->field("{$prikey},parentid")->select();
            foreach ($list as $k => $v) {
                $hasids[] = $v[$prikey];
            }
            $ids = array_values(array_intersect($ids, $hasids));
        }

        $list = Db::name($table)->field("$prikey,$field")->where($prikey, 'in', $ids)->order($field, $orderway)->select();
        foreach ($list as $k => $v) {
            $sour[]                 = $v[$prikey];
            $weighdata[$v[$prikey]] = $v[$field];
        }
        $position = array_search($changeid, $ids);
        $desc_id  = $sour[$position] ?? end($sour); //移动到目标的ID值,取出所处改变前位置的值
        $sour_id  = $changeid;
        $weighids = [];
        $temp     = array_values(array_diff_assoc($ids, $sour));
        foreach ($temp as $m => $n) {
            if ($n == $sour_id) {
                $offset = $desc_id;
            } else {
                if ($sour_id == $temp[0]) {
                    $offset = $temp[$m + 1] ?? $sour_id;
                } else {
                    $offset = $temp[$m - 1] ?? $sour_id;
                }
            }
            if (!isset($weighdata[$offset])) {
                continue;
            }
            $weighids[$n] = $weighdata[$offset];
            Db::name($table)->where($prikey, $n)->update([$field => $weighdata[$offset]]);
        }
        $this->success('排序成功');
    }

}
