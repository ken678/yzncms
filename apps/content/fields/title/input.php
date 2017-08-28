<?php

/**
 * 标题字段类型数据获取
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return int
 */
function title($field, $value) {
    return \Input::forTag($value);
}
