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

use think\Image;
use think\Model;

class Attachment extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $insert             = ['status' => 1];

    public function getSizeAttr($value)
    {
        return format_bytes($value);
    }

    /**
     * 创建缩略图
     * @param string $file 目标文件，可以是文件对象或文件路径
     * @param string $dir 保存目录，即目标文件所在的目录名
     * @param string $save_name 缩略图名
     * @param string $thumb_size 尺寸
     * @param string $thumb_type 裁剪类型
     * @return string 缩略图路径
     */
    public function create_thumb($file = '', $filename = '', $save_name = '', $thumb_size = '', $thumb_type = '')
    {
        // 获取要生成的缩略图最大宽度和高度
        $thumb_size                               = $thumb_size == '' ? config('upload_image_thumb') : $thumb_size;
        list($thumb_max_width, $thumb_max_height) = explode(',', $thumb_size);
        // 读取图片
        $image = Image::open($file);
        // 生成缩略图
        $thumb_type = $thumb_type == '' ? config('upload_image_thumb_type') : $thumb_type;
        $image->thumb($thumb_max_width, $thumb_max_height, $thumb_type);

        if (!is_dir($filename)) {
            mkdir($filename, 0766, true);
        }
        $image->save($filename . $save_name);
        return $filename;
    }

    /**
     * 添加水印
     */
    public function create_water($file = '', $path = '', $watermark_pos = '', $watermark_alpha = '')
    {
        $thumb_water_pic = realpath(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . $path);
        if (is_file($thumb_water_pic)) {
            // 读取图片
            $image = Image::open($file);
            // 添加水印
            $watermark_pos   = $watermark_pos == '' ? config('upload_thumb_water_position') : $watermark_pos;
            $watermark_alpha = $watermark_alpha == '' ? config('upload_thumb_water_alpha') : $watermark_alpha;
            $image->water($thumb_water_pic, $watermark_pos, $watermark_alpha);
            // 保存水印图片，覆盖原图
            $image->save($file);
        }
    }
}
