<?php

/**
 * 选项字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function box($field, $value, $fieldinfo) {
    //错误提示
    $errortips = $fieldinfo['errortips'];
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    //扩展配置
    $setting = unserialize($fieldinfo['setting']);
    if (is_null($value) || $value == ''){
        $value = $setting['defaultvalue'];
    }
    $options = explode("\n", $setting['options']);
    foreach ($options as $_k) {
        $v = explode("|", $_k);
        $k = trim($v[1]);
        $option[$k] = $v[0];
    }
    $values = explode(',', $value);
    $value = array();
    foreach ($values as $_k) {
        if ($_k != '')
            $value[] = $_k;
    }
    $value = implode(',', $value);
    switch ($setting['boxtype']) {
        case 'radio':
            $string = \util\Form::radio($option, $value, "name='info[$field]' {$fieldinfo['formattribute']}", $setting['width'], $field);
            break;

        case 'checkbox':
            $string = \util\Form::checkbox($option, $value, "name='info[$field][]' {$fieldinfo['formattribute']}", 1, $setting['width'], $field);
            break;

        case 'select':
            $string = \util\Form::select($option, $value, "name='info[$field]' id='$field' {$fieldinfo['formattribute']}");
            break;

        case 'multiple':
            $string = \util\Form::select($option, $value, "name='info[$field][]' id='$field ' size=2 multiple='multiple' style='height:60px;' {$fieldinfo['formattribute']}");
            break;
    }
    return $string;
}