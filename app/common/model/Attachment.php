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
namespace app\common\model;

use think\Model;

class Attachment extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function onBeforeInsert($row)
    {
        if (self::where('path', $row['path'])->where('driver', $row['driver'])->find()) {
            return false;
        }
    }

    public function getSizeAttr($value)
    {
        return format_bytes($value);
    }

    public function getCategoryAttr($value): string
    {
        return $value == '' ? 'unclassed' : $value;
    }

    public function setCategoryAttr($value)
    {
        return $value == 'unclassed' ? '' : $value;
    }

    /**
     * 获取定义的附件类别列表
     * @return array
     */
    public static function getCategoryList(): array
    {
        $data = config('site.attachmentcategory') ?? [];
        // 添加未归类选项
        $data['unclassed'] = '未归类';
        return $data;
    }
}
