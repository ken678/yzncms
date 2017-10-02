<?php
/**
 * 获取 日期时间字段类型 内容
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return type
 */
function datetime($field, $value)
{
    $setting = unserialize($this->fields[$field]['setting']);
    if ($setting['fieldtype'] == 'date' || $setting['fieldtype'] == 'datetime') {
        return $value;
    } else {
        $format_txt = $setting['format'];
    }
    if (strlen($format_txt) < 6) {
        $isdatetime = 0;
    } else {
        $isdatetime = 1;
    }
    if (empty($value)) {
        $value = time();
    }
    $value = date($format_txt, $value);
    return $value;
}
