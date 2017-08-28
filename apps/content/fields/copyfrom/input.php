<?php

/**
 * 获取字段来源处理
 * @param type $field
 * @param string $value
 * @return string
 */
function copyfrom($field, $value) {
    return \Input::forTag($value);
}
