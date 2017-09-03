<?php

/**
 * 作者字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function author($field, $value, $fieldinfo) {
    //扩展配置
    $setting = unserialize($fieldinfo['setting']);
    //默认显示
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
    //宽度
    $width = $setting['width'] ? ('width:' . $setting['width'] . 'px') : 'width:180px';
    return '<input type="text" class="input" name="info[' . $field . ']" value="' . \util\Input::forTag($value) . '" style="' . $width . '" placeholder="请输入' . $fieldinfo['name'] . '信息">';
}
