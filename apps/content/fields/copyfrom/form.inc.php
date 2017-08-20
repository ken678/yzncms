<?php
/**
 * 来源字段 表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function copyfrom($field, $value, $fieldinfo) {
    //扩展配置
    $setting = unserialize($fieldinfo['setting']);
    if (empty($value)) {
        $value = $setting['defaultvalue'];
    }
    //错误提示
    $errortips = $fieldinfo['errortips'];
    //字段最小值判断
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    $width = $setting['width'] ? $setting['width'] : 180;
    return "<input type='text' name='info[{$field}]' value='".\util\Input::forTag($value)."' style='width:{$width}px;' class='input' placeholder='信息来源'/>";
}