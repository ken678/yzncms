<?php
/**
 * 关键字获取时处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return array 返回数组
 */
function keyword($field, $value)
{
    if ($value == '') {
        return '';
    }
    //对关键字进行处理，返回数组
    if (strpos($value, ',') === false) {
        $value = explode(' ', $value);
    } else {
        $value = explode(',', $value);
    }
    return $value;
}
