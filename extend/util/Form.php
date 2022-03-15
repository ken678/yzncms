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
// | 表单构建器 【即将弃用 请使用form/FormBuilder.php类生成】
// +----------------------------------------------------------------------
namespace util;

class Form
{
    /**
     * 下拉选择框（弃用）
     * @param type $array 数据
     * @param type $id 默认选择
     * @param type $str 属性
     * @param type $default_option 默认选项
     * @return boolean|string
     */
    public static function select($array = array(), $id = 0, $str = '', $default_option = '')
    {
        $string           = '<select ' . $str . '>';
        $default_selected = (empty($id) && $default_option) ? 'selected' : '';
        if ($default_option) {
            $string .= "<option value='' $default_selected>$default_option</option>";
        }

        if (!is_array($array) || count($array) == 0) {
            return false;
        }

        $ids = array();
        if (isset($id)) {
            $ids = explode(',', $id);
        }

        foreach ($array as $key => $value) {
            $selected = in_array($key, $ids) ? 'selected' : '';
            $string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
        $string .= '</select>';
        return $string;
    }

    /**
     * 复选框（弃用）
     * @param $array 选项 二维数组
     * @param $id 默认选中值，多个用 '逗号'分割
     * @param $str 属性
     * @param $defaultvalue 是否增加默认值 默认值为 -99
     */
    public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $field = '')
    {
        $string = '';
        /*$id = trim($id);
        if ($id != '') {
        $id = strpos($id, ',') ? explode(',', $id) : array($id);
        }*/
        if ($defaultvalue) {
            $string .= '<input type="hidden" ' . $str . ' value="-99">';
        }
        $i = 1;
        foreach ($array as $key => $value) {
            $key     = trim($key);
            $checked = ($id && in_array($key, $id)) ? 'checked' : '';
            $string .= '<input type="checkbox" lay-skin="primary" ' . $str . ' id="' . $field . '_' . $i . '" ' . $checked . ' value="' . htmlspecialchars($key) . '" title="' . htmlspecialchars($value) . '"> ';

            $i++;
        }
        return $string;
    }

    /**
     * 图片上传（弃用）
     * @param string $name 表单名称
     * @param int $id 表单id
     * @param string $value 表单默认值
     * @param string $multiple 是否多图片
     * @param string $alowexts 允许图片格式
     * @param int $size 图片大小限制
     * @param int $watermark_setting  水印 0或1
     */
    public static function images($name, $id = '', $value = '', $multiple = 'false', $ext = '', $size = 0)
    {
        $string = '';
        if (!$id) {
            $id = $name;
        }
        if (!$ext) {
            $ext = 'jpg|jpeg|gif|bmp|png';
        }
        $string .= "<div class='layui-col-xs4'><input type='text' name='{$name}' id='c-{$id}' value='{$value}' class='layui-input'></div>";
        $string .= "<div class='webUpload' id='picker_{$id}' data-multiple='{$multiple}' data-input-id='c-{$id}' data-preview-id='p-{$id}' data-type='image'><i class='layui-icon layui-icon-upload'></i> 上传</div>";
        $string .= " <button type='button' class='layui-btn fachoose' data-multiple='{$multiple}' data-input-id='c-{$id}' id='fachoose-c-{$id}'><i class='iconfont icon-other'></i> 选择</button>";
        $string .= "<ul class='layui-row list-inline plupload-preview' id='p-{$id}'></ul>";
        return $string;
    }

}
