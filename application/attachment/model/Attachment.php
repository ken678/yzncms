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

}
