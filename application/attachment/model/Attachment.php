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
// | 附件上传模型
// +----------------------------------------------------------------------
namespace app\attachment\model;

use think\Model;

class Attachment extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function getFileInfo($id = '', $field = 'path', $ifstatus = false)
    {
        if ('' == $id) {
            return '';
        }
        $isIds = strpos($id, ',') !== false;
        $isfields = strpos($field, ',') !== false;
        $ifcache = config('app_cache') && 'admin' != request()->module() ? true : false;
        if ($isIds) {
            $ids = explode(',', $id);
            $result = $ifstatus ? self::where('id', 'in', $ids)->where('status', 1)->cache($ifcache)->column($field) : self::where('id', 'in', $ids)->cache($ifcache)->column($field);
        } else {
            $result = $ifstatus ? self::where('id', $id)->where('status', 1)->cache($ifcache)->field($field)->find() : self::where('id', $id)->cache($ifcache)->field($field)->find();
        }
        return !($isfields || $isIds) ? $result[$field] : $result;
    }

    public function deleteFile($id)
    {
        //throw new \Exception("测试");
        $path = config('upload_path');
        $isAdministrator = model('admin/AdminUser')->isAdministrator();
        $uid = (int) model('admin/AdminUser')->isLogin();

        if (is_array($id)) {
            $files_path = $isAdministrator ? self::where('id', 'in', $id)->column('path,thumb', 'id') : self::where('id', 'in', $id)->where('uid', $uid)->column('path,thumb', 'id');
            if (!empty($files_path)) {
                $mes = '';
                $id = [];
                foreach ($files_path as $key => $value) {
                    $real_path = realpath($path . '/' . $value['path']);
                    $real_path_thumb = realpath($path . '/' . $value['thumb']);

                    if (is_file($real_path) && !unlink($real_path)) {
                        $mes .= "删除" . $real_path . "失败，";
                    }
                    if (is_file($real_path_thumb) && !unlink($real_path_thumb)) {
                        $mes .= "删除" . $real_path_thumb . "失败，";
                    }
                    $id[] = $key;
                }
                self::where('id', 'in', $id)->delete();
                if ('' != $mes) {
                    throw new \Exception($mes);
                }
            } else {
                throw new \Exception($isAdministrator ? "文件数据库记录已不存在~" : "没有权限删除别人上传的附件~");
            }
        } else {
            $file_path = $isAdministrator ? self::where('id', $id)->field('path,thumb')->find() : self::where('id', $id)->where('uid', $uid)->field('path,thumb')->find();
            if (isset($file_path['path'])) {
                $real_path = realpath($path . '/' . $file_path['path']);
                $real_path_thumb = realpath($path . '/' . $file_path['thumb']);

                if (is_file($real_path) && !unlink($real_path)) {
                    throw new \Exception("删除" . $real_path . "失败");
                }
                if (is_file($real_path_thumb) && !unlink($real_path_thumb)) {
                    throw new \Exception("删除" . $real_path_thumb . "失败");
                }
                self::where('id', $id)->delete();
            } else {
                throw new \Exception($isAdministrator ? "文件数据库记录已不存在~" : "没有权限删除别人上传的附件~");
            }
        }
    }

}
