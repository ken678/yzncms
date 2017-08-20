<?php

/**
 * 单行文本框字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function text($field, $value, $fieldinfo) {
    //扩展配置
    $setting = unserialize($fieldinfo['setting']);
    $size = $setting['size'] ? "style=\"width:{$setting['size']}px;\"" : '';
    if (empty($value)) {
        $value = $setting['defaultvalue'];
    }
    //文本框类型
    $type = $setting['ispassword'] ? 'password' : 'text';
    //错误提示
    $errortips = $fieldinfo['errortips'];
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    return '<input type="' . $type . '" name="info[' . $field . ']" id="' . $field . '" ' . $size . ' value="' . $value . '" class="input" ' . $fieldinfo['formattribute'] . ' ' . $fieldinfo['css'] . '>';
}