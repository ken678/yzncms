<?php

/**
 * 数字字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function number($field, $value, $fieldinfo) {
    $setting = unserialize($fieldinfo['setting']);
    $size = $setting['size']?"style=\"width:{$setting['size']}px;\"":"";
    if ($value == '') {
        $value = $setting['defaultvalue'];
    }
    //错误提示
    $errortips = $fieldinfo['errortips'];
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    return "<input type='text' name='info[{$field}]' id='{$field}' value='{$value}' class='input' {$size} {$fieldinfo['formattribute']} {$fieldinfo['css']} />";
}