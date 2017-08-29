<?php

/**
 * 选项字段类型获取数据
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return boolean|string 字段配置
 */
function box($field, $value) {
    $setting = unserialize($this->fields[$field]['setting']);
    if ($setting['boxtype'] == 'checkbox') {
        if (!is_array($value) || empty($value))
            return false;
        //删除添加的默认值
        array_shift($value);
        $value = implode(',', $value);
        return $value;
    } elseif ($setting['boxtype'] == 'multiple') {
        if (is_array($value) && count($value) > 0) {
            $value = implode(',', $value);
            return $value;
        }
    } else {
        return $value;
    }
}
