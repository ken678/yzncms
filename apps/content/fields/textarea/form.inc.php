<?php
/**
 * 多行文本框 表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return string
 */
function textarea($field, $value, $fieldinfo) {
    //扩展配置
    $setting = unserialize($fieldinfo['setting']);
    if(!$value) $value = $setting['defaultvalue'];
    //错误提示
    $errortips = $fieldinfo['errortips'];
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    $str = "<textarea name='info[{$field}]' id='{$field}' style='width:{$setting['width']}%;height:{$setting['height']}px;' {$fieldinfo['formattribute']} {$fieldinfo['css']}";
    //长度处理
    if ($fieldinfo['maxlength']) {
        $str .= " onkeyup=\"strlen_verify(this, '{$field}_len', {$fieldinfo['maxlength']})\"";
    }
    $str .= ">{$value}</textarea>";
    if ($fieldinfo['maxlength'])
        $str .= '还可以输入<B><span id="' . $field . '_len">' . $fieldinfo['maxlength'] . '</span></B>个字符！ ';

    return $str;
}