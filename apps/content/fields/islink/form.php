<?php
/**
 * 转向地址 字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function islink($field, $value, $fieldinfo) {
    if ($value) {
        $url = $this->data['url'];
        $checked = 'checked';
        $_GET['islink'] = 1;
    } else {
        $disabled = 'disabled';
        $url = $checked = '';
        $_GET['islink'] = 0;
    }
    $size = $fieldinfo['size'] ? $fieldinfo['size'] : 180;
    return '<input type="hidden" name="info[islink]" value="0"><input type="text" name="linkurl" id="linkurl" value="' . $url . '" style="width:' . $size . 'px;"maxlength="255" ' . $disabled . ' class="input length_3"> <input name="info[islink]" type="checkbox" id="islink" value="1" onclick="ruselinkurl();" ' . $checked . '> <font color="red">转向链接</font>';
}