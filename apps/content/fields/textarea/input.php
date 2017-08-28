<?php

/**
 * 多行文本框
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return type
 */
function textarea($field, $value) {
    $setting = unserialize($this->fields[$field]['setting']);
    if (!$setting['enablehtml']) {
        $value = strip_tags($value);
    }
    return $value;
}