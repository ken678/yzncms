<?php
/**
 * 推荐位字段类型数据获取
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return int
 */
function posid($field, $value)
{
    if (empty($value) || !is_array($value)) {
        return 0;
    }
    $number = count($value);
    $value = $number == 1 ? 0 : 1;
    return $value;
}
