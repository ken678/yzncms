<?php
/**
 * 关键字类型字段，表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function keyword($field, $value, $fieldinfo) {
    //错误提示
    $errortips = $fieldinfo['errortips'];
    //字段最小长度检测
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : "请输入关键字！");
    }
    return "<input type='text' name='info[{$field}]' id='{$field}' value='".\util\Input::forTag($value)."' style='width:280px' {$fieldinfo['formattribute']} {$fieldinfo['css']} class='input' placeholder='请输入关键字'>";
}